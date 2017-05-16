<?php 
namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\ArchTax;
use AppBundle\Entity\ArchOutlet;
use AppBundle\Entity\ArchRegister;
use AppBundle\Entity\ArchPaymentType;
use AppBundle\Entity\User;
use AppBundle\Entity\ArchProduct;
use AppBundle\Entity\ArchOrder;
use AppBundle\Entity\ArchOrderProduct;
use AppBundle\Entity\ArchProductBrand;
use AppBundle\Entity\ArchProductType;
use AppBundle\Entity\ArchProductTag;
use AppBundle\Entity\ArchSupplier;

class VendController extends Controller
{
	/**
	 * @Route("/vend-authorize", name="vend_authorize")
	 */
	public function vendAuthorize(Request $request) {
		$get = $request->query->all();
		$em = $this->getDoctrine()->getManager();
		
		$config = $em->getRepository('AppBundle:ArchConfig');
		$vendURL = 'https://'. $config->findOneBy(array('name' => 'vend_prefix'))->getValue() .'.vendhq.com/api';
		
		if ($get['state'] == 'getCode') {
			$data = array(
					'code' => $get['code'],
					'client_id' => $config->findOneBy(array('name' => 'vend_client_id'))->getValue(),
					'client_secret' => $config->findOneBy(array('name' => 'vend_client_secret'))->getValue(),
					'grant_type' => 'authorization_code',
					'redirect_uri' => $config->findOneBy(array('name' => 'vend_redirect_uri'))->getValue(),
			);
			
			$options = array(
					'http' => array(
							'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
							'method'  => 'POST',
							'content' => http_build_query($data)
					)
			);
			$context  = stream_context_create($options);
			$result = file_get_contents($vendURL .'/1.0/token', false, $context);
			if ($result === FALSE) {
				$this->addFlash('warning', 'Failed. Please check your configuration.');
				$this->redirectToRoute('homepage');
			}
			
			$access_token = $config->findOneBy(array('name' => 'vend_access_token'));
			$refresh_token = $config->findOneBy(array('name' => 'vend_refresh_token'));
			
			$result = json_decode($result);
			$access_token->setValue($result->access_token);
			$refresh_token->setValue($result->refresh_token);
			
			$em->flush();
			dump($result);die;
			return new Response($result);
		}
	}
	
	/**
	 * @Route("/admin/vend-reload", name="vend_reload")
	 */
	public function vendReload(Request $request) {
		$data = $request->query->all();
		$mode = $data['mode'];

		switch ($mode) {
			case 'product':
				$url = 'products';
				if (isset($data['page']))
					$url .= '?page='. $data['page'];
				if (isset($data['id']))
					$url .= '/'. $data['id'];
				$entity = 'ArchProduct';
				break;
			case 'product_edit':
				$url = 'products/'. $data['get_id'];
				$entity = 'ArchProduct';
				break;
			case 'customer':
				$url = 'customers';
				if (isset($data['page']))
					$url .= '?page='. $data['page'];
				$entity = 'User';
				break;
			case 'customer_view':
				$url = 'customers?id='. $data['get_id'];
				$entity = 'User';
				break;
			case 'sales':
				$url = 'register_sales';
				$entity = 'ArchOrder';
				break;
			case 'tax':
				$url = 'taxes';
				$entity = 'ArchTax';
				break;
			case 'supplier':
				$url = 'supplier';
				$entity = 'ArchSupplier';
				break;
			case 'outlet':
				$url = 'outlets';
				$entity = 'ArchOutlet';
				break;
			case 'register':
				$url = 'registers';
				$entity = 'ArchRegister';
				break;
			case 'payment_type':
				$url = 'payment_types';
				$entity = 'ArchPaymentType';
				break;
		}
		
		$result = $this->get('app.vend')->getVend($url);

		if (!$result) // failed
			return new Response('Error connecting to Vend API');

		$em = $this->getDoctrine()->getManager();
		$repository = $em->getRepository('AppBundle:'. $entity);
		switch ($mode) {
			case 'product':
			case 'product_edit':
				$msg = 'Product';
				if ($mode == 'product')
					$msg .= 's';
				
				if (isset($result->pagination)) {
					$pagination = $result->pagination;
					
					while ($pagination->page < $pagination->pages) {
						set_time_limit(120);
						$items = $result->products;
						
						$this->saveProducts($items, $repository, $em);
						
						$next_page = $pagination->page + 1;
						
						// throttle, 20 pages at a time
						if ($next_page % 20 == 0) {
							return new Response($next_page .'|'. $pagination->pages);
							die;
						}
						
						$result = $this->get('app.vend')->getVend('products?page='. $next_page);
						
						$pagination = $result->pagination;
					}
				}
				else {
					$items = $result->products;
					$this->saveProducts($items, $repository, $em);
				}
				break;
			case 'customer':
			case 'customer_view':
				$msg = 'Customer';
				if ($mode == 'customer')
					$msg .= 's';
				
				if (isset($result->pagination)) {
					$pagination = $result->pagination;
					
					while ($pagination->page < $pagination->pages) {

						set_time_limit(120);

						$items = $result->customers;
						$this->saveCustomers($items, $repository, $em);
						
						$next_page = $pagination->page + 1;
						
						// throttle, 20 pages at a time
						if ($next_page % 20 == 0) {
							return new Response($next_page .'|'. $pagination->pages);
							die;
						}
						
						$result = $this->get('app.vend')->getVend('customers?page='. $next_page);
						
						$pagination = $result->pagination;
					}
				}
				else {
					$items = $result->customers;
					$this->saveCustomers($items, $repository, $em);
				}
				break;
			case 'sales':
				$msg = 'Sales';
				
				if (isset($result->pagination)) {
					$pagination = $result->pagination;
					
					while ($pagination->page < $pagination->pages) {
						
						set_time_limit(120);
						
						$items = $result->register_sales;
						$this->saveSales($items, $repository, $em);
						
						$next_page = $pagination->page + 1;
						
						// throttle, 20 pages at a time
						if ($next_page % 20 == 0) {
							return new Response($next_page .'|'. $pagination->pages);
							die;
						}
						
						$result = $this->get('app.vend')->getVend('register_sales?page='. $next_page);
						
						$pagination = $result->pagination;
					}
				}
				else {
					$items = $result->customers;
					$this->saveCustomers($items, $repository, $em);
				}
				break;
			case 'tax':
				$msg = 'Taxes';
				$items = $result->taxes;
				foreach ($items as $item) {
					$arch_tax = $repository->findOneBy(array('id' => $item->id));
					
					if (count($arch_tax) == 0) 
						$arch_tax = new ArchTax();
					
					$arch_tax
						->setId($item->id)
						->setName($item->name)
						->setRate($item->rate)
						->setIsActive($item->active)
						->setIsDefault($item->default)
					;
					$em->persist($arch_tax);
					$em->flush();
				}
				break;
			case 'supplier':
				$msg = 'Suppliers';

				$pages = (isset($result->pages)) ? $result->pages : 1;
				
				if ($pages > 1) {
					$page = $result->page;
					
					while ($page <= $result->pages) {
						
						set_time_limit(120);
						$items = $result->suppliers;

						$this->saveSuppliers($items, $repository, $em);
						
						$next_page = $page + 1;
						
						$result = $this->get('app.vend')->getVend('supplier?page='. $next_page);
						
						$page = $result->page;
					}
				}
				else {
					$items = $result->suppliers;
					$this->saveSuppliers($items, $repository, $em);
				}
				
				break;
			case 'outlet':
				$msg = 'Outlets';
				$items = $result->outlets;
				foreach ($items as $item) {
					$arch_outlet = $repository->findOneBy(array('id' => $item->id));
					
					if (count($arch_outlet) == 0) { 
						$arch_outlet = new ArchOutlet();
						$arch_outlet
							->setId($item->id)
							->setIsDefault(0)
						;
					}
					
					$arch_outlet->setName($item->name);
					
					$em->persist($arch_outlet);
					$em->flush();
				}
				break;
			case 'register':
				$msg = 'Registers';
				$items = $result->registers;
				foreach ($items as $item) {
					$arch_register = $repository->findOneBy(array('id' => $item->id));
					
					if (count($arch_register) == 0) {
						$arch_register = new ArchRegister();
						$arch_register
							->setId($item->id)
							->setIsDefault(0)
						;
					}
					
					$arch_register->setName($item->name);
					
					$em->persist($arch_register);
					$em->flush();
				}
				break;
			case 'payment_type':
				$msg = 'Payment Types';
				$items = $result->payment_types;
				foreach ($items as $item) {
					$arch_payment_type = $repository->findOneBy(array('id' => $item->id));
					
					if (count($arch_payment_type) == 0) {
						$arch_payment_type = new ArchPaymentType();
						$arch_payment_type
							->setId($item->id)
							->setIsDefault(0)
						;
					}
					
					$arch_payment_type->setName($item->name);
					
					$em->persist($arch_payment_type);
					$em->flush();
				}
				break;
		}
		
		return new Response($msg .' Reloaded');
	}
	
	/**
	 * @Route("/admin/vend-toggle", name="vend_toggle")
	 */
	public function vendToggle(Request $request) {
		if ($request->isMethod('POST')) {
			$data = $request->request->all();

			// default bundle
			$bundle = 'AppBundle';
			switch ($data['mode']) {
				
				case 'product':
				case 'product_edit':
					$entity = 'ArchProduct';
					break;
				case 'product_type':
					$entity = 'ArchProductType';
					break;
				case 'collection':
					$entity = 'ArchProductCollection';
					break;
				case 'category':
					$entity = 'ArchProductCategory';
					break;
				case 'discounter':
					$entity = 'ArchProductDiscounter';
					break;
				case 'brand':
					$entity = 'ArchProductBrand';
					break;
				case 'tag':
					$entity = 'ArchProductTag';
					break;
				case 'tax':
					$entity = 'ArchTax';
					break;
				case 'supplier':
					$entity = 'ArchSupplier';
					break;
				case 'outlet':
					$entity = 'ArchOutlet';
					break;
				case 'register':
					$entity = 'ArchRegister';
					break;
				case 'payment_type':
					$entity = 'ArchPaymentType';
					break;
				case 'home_page':
					$entity = 'ArchPageHome';
					$bundle = 'CmsBundle';
					break;
				case 'blog':
					$entity = 'ArchBlog';
					$bundle = 'CmsBundle';
					break;
			}
			
			$em = $this->getDoctrine()->getManager();
			$item = $em->getRepository($bundle .':'. $entity)->findOneBy(array('id' => $data['id']));
			if ($data['mode'] == 'product')
				$msg = "'". $item->getBaseName() ."'";
			else
				$msg = "'". $item->getName() ."'";
				
			switch ($data['action']) {
				case 'active':
					$item->setIsActive($data['value']);
					
					if ($data['mode'] == 'product' || $data['mode' == 'product_edit']) {
						// set variants
						$variants = $em->getRepository('AppBundle:ArchProduct')->findBy(array('variant_parent_id' => $item->getId()));
						foreach ($variants as $variant) {
							$variant->setIsActive($data['value']);
						}
					}
					
					// discounter product discounts
					if ($data['mode'] == 'discounter') {
						if ($data['value'] == 1) {
							// apply discount
							$rate = $item->getRate();
							$suffix = $item->getSuffix();
							
							foreach ($item->getProducts() as $product) {
								$archProduct = $product->getProduct();
								
								$price = $archProduct->getPrice();
								$discounted_price = floor($price * (1 - ($rate/100))) + ($suffix/100);
								$archProduct->setDiscountPrice($discounted_price);
							
								// set variants
								$variants = $em->getRepository('AppBundle:ArchProduct')->findBy(array('variant_parent_id' => $archProduct->getId()));
								foreach ($variants as $variant) {
									$price = $variant->getPrice();
									$discounted_price = floor($price * (1 - ($rate/100))) + ($suffix/100);
									$variant->setDiscountPrice($discounted_price);
								}
							}
						}
						else {
							// remove discount
							foreach ($item->getProducts() as $product) {
								$archProduct = $product->getProduct();
								
								$archProduct->setDiscountPrice(null);
								
								// variants
								$variants = $em->getRepository('AppBundle:ArchProduct')->findBy(array('variant_parent_id' => $archProduct->getId()));
								foreach ($variants as $variant) {
									$variant->setDiscountPrice(null);
								}
							}
						}
					}
					
					$data['value'] ? $msg .= ' enabled' : $msg .= ' disabled';
					break;
				case 'pre_sell':
					$item->setPreSell($data['value']);
					
					if ($data['mode'] == 'product' || $data['mode'] == 'product_edit') {
						// set variants
						$variants = $em->getRepository('AppBundle:ArchProduct')->findBy(array('variant_parent_id' => $item->getId()));
						foreach ($variants as $variant) {
							$variant->setPreSell($data['value']);
						}
					}
					
					$data['value'] ? $msg .= ' pre-selling enabled' : $msg .= ' pre-selling disabled';
					break;
				case 'default':
					if ($data['value'] == 1)
						$em->createQuery('UPDATE '. $bundle .':'. $entity .' e SET e.is_default = 0')->getResult(); // reset all to 0
						
					$item->setIsDefault($data['value']);
					$data['value'] ? $msg .= ' set to default' : $msg .= ' default status removed';
					break;
			}
			
			$em->flush();
		}
		
		return new Response($msg);
	}
	
	private function addToArray($data, $items) {
		if (!empty($data))
			if (!in_array($data, $items))
				$items[] = $data;
		return $items;
	}
	
	private function saveProductData($entity, $items) {
		$em = $this->getDoctrine()->getManager();
		$repository = $em->getRepository('AppBundle:'. $entity);
		foreach ($items as $item) {
			$product_data = $repository->findOneBy(array('name' => $item));
			
			if ($product_data == null) {
				switch ($entity) {
					case 'ArchProductBrand':
						$product_data = new ArchProductBrand();
						break;
					case 'ArchProductType':
						$product_data = new ArchProductType();
						break;
					case 'ArchProductTag':
						$product_data = new ArchProductTag();
						break;
				}
				
				$product_data
					->setName($item)
					->setIsActive(1)
				;
				$em->persist($product_data);
			}
		}
		
		$em->flush();
	}

	private function saveProducts($items, $repository, $em) {
		$product_types = $product_tags = $product_brands = array();
		
		foreach ($items as $item) {
			// locally collect product_types, tags, brands *since they can't be directly pulled off API
			$product_types = $this->addToArray($item->type, $product_types);
			$product_brands = $this->addToArray($item->brand_name, $product_brands);
			
			if (!empty($item->tags)) {
				$tags = explode(', ', $item->tags);
				foreach ($tags as $tag)
					if (!in_array($tag, $product_tags))
						$product_tags[] = $tag;
			}
			
			$product = $repository->findOneBy(array('id' => $item->id));
			
			if ($product == null) {
				$product = new ArchProduct();
				$product
					->setId(@$item->id)
					->setIsActive(0)
				;
			}
			
			$product
				->setProductType(@$item->type)
				->setHandle(@$item->handle)
				->setName(@$item->name)
				->setVendActive(@$item->active)
				->setBaseName(@$item->base_name)
				->setTags(@$item->tags)
				->setDescription(@$item->description)
				->setBrandName(@$item->brand_name)
				->setSupplierName(@$item->supplier_name)
				->setVariantParentId(empty($item->variant_parent_id) ? null : $item->variant_parent_id)
				->setVariantOptionOneName(@$item->variant_option_one_name)
				->setVariantOptionOneValue(@$item->variant_option_one_value)
				->setVariantOptionTwoName(@$item->variant_option_two_name)
				->setVariantOptionTwoValue(@$item->variant_option_two_value)
				->setVariantOptionThreeName(@$item->variant_option_three_name)
				->setVariantOptionThreeValue(@$item->variant_option_three_value)
				->setImage(@$item->image)
				->setSku(@$item->sku)
				->setSupplyPrice(@$item->supply_price)
				->setRetailPrice(@$item->price) // retail_price = price when getting, retail_price = retail_price when posting w/o tax
				->setPrice((@$item->price + @$item->tax))
				->setTax(@$item->tax)
				->setTaxId(@$item->tax_id)
				->setUpdatedAt(empty($item->updated_at) ? \DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s")) : \DateTime::createFromFormat('Y-m-d H:i:s', $item->updated_at))
				->setDeletedAt(empty($item->deleted_at) ? null : \DateTime::createFromFormat('Y-m-d H:i:s', $item->deleted_at))
			;
			
			if (isset($item->inventory)) {
				$default_outlet = $em->getRepository('AppBundle:ArchOutlet')->findOneBy(array('is_default' => 1))->getId();
				foreach ($item->inventory as $inventory) {
					if ($inventory->outlet_id == $default_outlet) {
						$product
							->setOutletId($inventory->outlet_id)
							->setCount(isset($inventory->count) ? $inventory->count : 0)
							->setReorderPoint(isset($inventory->reorder_point) ? $inventory->reorder_point : 0)
							->setRestockLevel(isset($inventory->restock_level) ? $inventory->restock_level : 0)
						;
					}
				}
			}
			
			$em->persist($product);
			
			$em->flush();
		}
		
		$em->clear();
		
		// save product types, tags, brands
		$this->saveProductData('ArchProductType', $product_types);
		$this->saveProductData('ArchProductBrand', $product_brands);
		$this->saveProductData('ArchProductTag', $product_tags);
	}
	
	private function saveCustomers($items, $repository, $em) {
		foreach ($items as $item) {
			if (isset($item->email)) {
				$customer = $repository->findOneBy(array('email' => $item->email));
				
				$name = explode(' ', $item->name);
				$fname = array_shift($name);
				$lname = implode(' ', $name);
				if (count($customer) == 0) {
					$customer = new User();
					
					$password_raw = $this->get('app.misc_functions')->generatePassword();
					$factory = $this->get('security.encoder_factory');
					
					$encoder = $factory->getEncoder($customer);
					$password = $encoder->encodePassword($password_raw, $customer->getSalt());
					
					$customer
					->setUsername($item->email)
					->setPassword($password)
					->setEmail($item->email)
					->setAccountType('Customer')
					;
					
				}
				
				$customer
					->setCustomerId($item->id)
					->setFirstName($fname)
					->setLastName($lname)
					->setFullName($item->name)
					->setCompanyName(isset($item->company_name) ? $item->company_name : '')
					->setCustomerCode(isset($item->customer_code) ? $item->customer_code : '')
					->setUpdatedAt(empty($item->updated_at) ? \DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s")) : \DateTime::createFromFormat('Y-m-d H:i:s', $item->updated_at))
					->setDeletedAt(empty($item->deleted_at) ? null : \DateTime::createFromFormat('Y-m-d H:i:s', $item->deleted_at))
					->setDateOfBirth(empty($item->date_of_birth) ? null : \DateTime::createFromFormat('Y-m-d', $item->date_of_birth))
					->setSex(@$item->sex)
					->setBalance(@$item->balance)
					->setYearToDate(@$item->year_to_date)
					->setPhone(@$item->phone)
					->setMobile(@$item->mobile)
					->setFax(@$item->fax)
					->setWebsite(@$item->website)
					->setPhysicalAddress1(@$item->physical_address1)
					->setPhysicalAddress2(@$item->physical_address2)
					->setPhysicalSuburb(@$item->physical_suburb)
					->setPhysicalCity(@$item->physical_city)
					->setPhysicalState(@$item->physical_state)
					->setPhysicalCountryId(@$item->physical_country_id)
					->setPhysicalPostcode(@$item->physical_postcode)
					->setPostalAddress1(@$item->physical_address1)
					->setPostalAddress2(@$item->physical_address2)
					->setPostalSuburb(@$item->physical_suburb)
					->setPostalCity(@$item->physical_city)
					->setPostalState(@$item->physical_state)
					->setPostalCountryId(@$item->physical_country_id)
					->setPostalPostcode(@$item->physical_postcode)
				;
				
				$em->persist($customer);
				$em->flush();
			}
		}
	}
	
	private function saveSuppliers($items, $repository, $em) {
		foreach ($items as $item) {
			$supplier = $repository->findOneBy(array('id' => $item->id));
			
			if ($supplier == null)
				$supplier = new ArchSupplier();
				
			$supplier
				->setId($item->id)
				->setName($item->name)
				->setDescription(@$item->description)
			;
				
			$em->persist($supplier);
			$em->flush();
		}
	}
	
	private function saveSales($items, $repository, $em) {
		$order_products = $em->getRepository('AppBundle:ArchOrderProduct');
		
		// customer total spent
		$customer_orders = array();
		foreach ($items as $item) {
			// save only sales from site customers
			@$customer = $em->getRepository('AppBundle:User')->findOneBy(array('customer_id' => $item->customer_id));
			
			if (count($customer) > 0) {
				$order = $repository->findOneBy(array('id' => $item->id));
				
				if (count($order) == 0)
					$order = new ArchOrder();
					
				$order
					->setId($item->id)
					->setCustomer($customer)
					->setUser($item->user_name)
					->setSaleDate(\DateTime::createFromFormat('Y-m-d H:i:s', $item->sale_date))
					->setTotalPrice($item->total_price)
					->setTotalTax($item->total_tax)
					->setTaxName($item->tax_name)
					->setNote($item->note)
					->setStatus($item->status)
					->setInvoiceNumber($item->invoice_number)
					->setShortCode($item->short_code)
					;
				$em->persist($order);
				
				if (isset($customer_orders[$item->customer_id])) {
					$array = $customer_orders[$item->customer_id];
					
					$customer_orders[$item->customer_id] = array(
							'total_spent' => $array['total_spent'] + ($item->total_price + $item->total_tax),
							'orders' => ($array['orders'] + 1)
					);
				}
				else
					$customer_orders[$item->customer_id] = array(
							'total_spent' => ($item->total_price + $item->total_tax),
							'orders' => 1
					);
					
				// products in order
				foreach ($item->register_sale_products as $product) {
					$order_product = $order_products->findOneBy(array('id' => $product->id));
					
					if (count($order_product) == 0)
						$order_product = new ArchOrderProduct();
						
					$order_product
						->setId($product->id)
						->setOrder($order)
						->setName($product->name)
						->setQuantity($product->quantity)
						->setPrice($product->price)
						->setDiscount(@$product->discount)
						->setTax($product->tax)
						->setTaxId($product->tax_id)
						->setTaxTotal($product->tax)
						->setPriceTotal($product->price_total)
					;
						
					$arch_product = $em->getRepository('AppBundle:ArchProduct')->findOneBy(array('id' => $product->product_id));
					if (count($arch_product) > 0)
						$order_product->setProduct($arch_product);
						
					$tax = $em->getRepository('AppBundle:ArchTax')->findOneBy(array('id' => $product->tax_id));
					if (count($tax) > 0)
						$order_product->setTaxMap($tax);
						
					$em->persist($order_product);
				}
				
				$em->flush();
			}
		}
		
		// customer total spent
		$customers = $em->getRepository('AppBundle:User');
		foreach ($customer_orders as $key => $value) {
			$customer = $customers->findOneBy(array('customer_id' => $key));
			if ($customer != null)
				$customer
				->setTotalSpent($value['total_spent'])
				->setOrderCount($value['orders'])
				;
		}
		$em->flush();
		
	}
}
?>