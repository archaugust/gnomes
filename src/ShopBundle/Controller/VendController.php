<?php 
namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JasonGrimes\Paginator;
use AppBundle\Entity\ArchTax;
use AppBundle\Entity\ArchOutlet;
use AppBundle\Entity\ArchRegister;
use AppBundle\Entity\ArchPaymentType;
use AppBundle\Entity\User;
use AppBundle\Entity\ArchProduct;
use AppBundle\Entity\ArchOrder;
use AppBundle\Entity\ArchOrderProduct;

class VendController extends Controller
{
	/**
	 * @Route("/admin/vend-reload", name="vend_reload")
	 */
	public function vendReload(Request $request) {
		$data = $request->query->all();
		$mode = $data['mode'];

		switch ($mode) {
			case 'product':
				$url = 'products';
				$entity = 'ArchProduct';
				break;
			case 'customer':
				$url = 'customers';
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
				$msg = 'Products';
				$items = $result->products;
				foreach ($items as $item) {
					if ($item->has_variants) {
						$product = $repository->findOneBy(array('id' => $item->id));
						
						if (count($product) == 0)
							$product = new ArchProduct();
							
							$product
							->setId(@$item->id)
							->setProductType(@$item->type)
							->setHandle(@$item->handle)
							->setIsActive(@$item->active)
							->setName(@$item->name)
							->setBaseName(@$item->base_name)
							->setTags(@$item->tags)
							->setDescription(@$item->description)
							->setBrandName(@$item->brand_name)
							->setSupplierName(@$item->supplier_name)
							;
							$em->persist($product);
							/*
							 "id": "0515649e-80e5-11df-b0bf-4040f540b50a",
							 "source_id": "magento-123",
							 "source_variant_id": "magento-variant-1-123",
							 "handle": "abc123",
							 "active": true,
							 "name": "MacBook Pro 15&quot;",
							 "tags": "Apple, iPad",
							 "description": "The mobile computing powerhouse.",
							 "variant_option_one_name": "Size",
							 "variant_option_one_value": "15",
							 "variant_option_two_name": null,
							 "variant_option_two_value": "",
							 "variant_option_three_name": null,
							 "variant_option_three_value": "",
							 "image": "/uploads/product/3eb691e90914967d5d25f4c7c3cbc310662c98e5_thumb.png",
							 "sku": "macbookpro15",
							 "brand_name": "MacBook",
							 "supplier_name": "Apple",
							 "inventory": [
							 {
							 "outlet_id": "8319a320-586e-102e-86b1-3945ad97ead1",
							 "outlet_name": "Our discount store",
							 "count": "0.00000",
							 "reorder_point": "",
							 "restock_level": "",
							 },
							 {
							 "outlet_id": "83189cdc-586e-102e-86b1-3945ad97ead1",
							 "outlet_name": "Our retail store",
							 "count": "22.00000",
							 "reorder_point": "33",
							 "restock_level": "11",
							 }],
							 "price_book_entries": [
							 {
							 "id": "164081ec-83cc-11df-b0bf-4040f540b50a",
							 "product_id": "0515649e-80e5-11df-b0bf-4040f540b50a",
							 "type": "GENERAL",
							 "customer_group_name": "All customers",
							 "customer_group_id": "f823c3c2-83cb-11df-b0bf-4040f540b50a",
							 "price": "1162.17",
							 "tax": "174.33",
							 "tax_id": "53b3501c-887c-102d-8a4b-a9cf13f17faa",
							 "tax_rate": "0",
							 "display_retail_price_tax_inclusive": "0",
							 "min_units": "5.00",
							 "max_units": "",
							 "valid_from": "",
							 "valid_to": ""
							 },
							 {
							 "id": "1fa0344e-9dd5-11df-92db-4040f540b50a",
							 "product_id": "0515649e-80e5-11df-b0bf-4040f540b50a",
							 "type": "BASE",
							 "customer_group_name": "All customers",
							 "customer_group_id": "6e1dee9e-80e4-11df-b0bf-4040f540b50a",
							 "price": "1999.13",
							 "tax": "299.87",
							 "tax_id": "53b3501c-887c-102d-8a4b-a9cf13f17faa",
							 "tax_rate": "0",
							 "display_retail_price_tax_inclusive": "0",
							 "min_units": "",
							 "max_units": "",
							 "valid_from": "",
							 "valid_to": ""
							 }],
							 "price": "1999.13",
							 "tax": "299.87",
							 "tax_id": "53b3501c-887c-102d-8a4b-a9cf13f17faa",
							 "tax_rate": "0.150000",
							 "tax_name": "NZ GST",
							 "display_retail_price_tax_inclusive": "0",
							 "updated_at": "2010-09-30 19:22:52",
							 "deleted_at": ""
							 }]*/
							$em->flush();
					}
				}
				break;
			case 'customer':
			case 'customer_view':
				if ($mode == 'customer')
					$msg = 'Customers';
				else 
					$msg = 'Customer';
				$items = $result->customers;
				
				// pagination
				if (isset($result->pagination)) {
					
				}
				
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
				break;
			case 'sales':
				$msg = 'Sales';
				$items = $result->register_sales;
				$order_products = $em->getRepository('AppBundle:ArchOrderProduct');
				
				if (isset($result->pagination)) {
					// loop
				}

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
								->setPriceTotal($product->price)
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
			
			switch ($data['mode']) {
				case 'product':
					$entity = 'ArchProduct';
					break;
				case 'tax':
					$entity = 'ArchTax';
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
			}
			
			$em = $this->getDoctrine()->getManager();
			$item = $em->getRepository('AppBundle:'. $entity)->findOneBy(array('id' => $data['id']));
			if ($data['mode'] == 'product')
				$msg = "'". $item->getBaseName() ."'";
			else
				$msg = "'". $item->getName() ."'";
				
			switch ($data['action']) {
				case 'active':
					$item->setIsActive($data['value']);
					$data['value'] ? $msg .= ' enabled' : $msg .= ' disabled';
					break;
				case 'default':
					if ($data['value'] == 1)
						$em->createQuery('UPDATE AppBundle:'. $entity .' e SET e.is_default = 0')->getResult(); // reset all to 0
						
					$item->setIsDefault($data['value']);
					$data['value'] ? $msg .= ' set to default' : $msg .= ' default status removed';
					break;
			}
			
			$em->flush();
		}
		
		return new Response($msg);
	}
}
?>