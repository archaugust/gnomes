<?php 
namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;
use JasonGrimes\Paginator;
use AppBundle\Entity\ArchTax;
use AppBundle\Entity\User;
use AppBundle\Entity\ArchProductType;
use AppBundle\Entity\ArchProductCollection;
use AppBundle\Entity\ArchProductBrand;
use AppBundle\Entity\ArchProductTag;
use AppBundle\Entity\ArchSupplier;
use AppBundle\Entity\ArchProduct;
use AppBundle\Form\ArchTaxType;
use AppBundle\Form\UserAdminType;
use AppBundle\Form\ArchProductTypeType;
use AppBundle\Form\ArchProductCollectionType;
use AppBundle\Form\ArchProductBrandType;
use AppBundle\Form\ArchProductTagType;
use AppBundle\Form\ArchSupplierType;
use AppBundle\Form\ArchProductsType;

class AdminController extends Controller
{
	/**
	 * @Route("/admin", name="admin")
	 */
	public function admin()
	{
		return $this->render('admin/index.html.twig', array(
				'header' => 'Dashboard',
		)
				);
	}

	/**
	 * @Route("/admin/product/{mode}", name="admin_product") 
	 */
	public function product(Request $request, $mode = '') {
		// mode can be used to switch template to ajax
		$session = $this->get('session');
		$data = $request->query->all();
		
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchProduct');
		
		$item = new ArchProduct();
		$form = $this->createForm(ArchProductsType::class, $item);
		$form->handleRequest($request);
		
		// post = add or delete
		if ($request->isMethod('POST')) {
			
			// delete
			$data = $request->request->all();
			if (isset($data['products'])) {
				foreach ($data['products'] as $product) {
					$product = $items->findOneBy(array('id' => $product));
					$product->setIsActive(0);
				}
				
				$em->flush();
			}
			
			// add
			if ($form->isSubmitted()) {
				if ($form->isValid()) {
					$input = $request->request->get('arch_products');
					
					// post to Vend
					$tax = explode(':', $input['tax']);

					// use default outlet
					$outlet_id = $em->getRepository('AppBundle:ArchOutlet')->findOneBy(array('is_default' => 1))->getId();
					$data = array(
							"handle" => str_replace(' ', '', $input['name']),
							"type" => $input['product_type'],
							"tags" => $input['tags'],
							"name" => $input['name'],
							"description" => $input['description'],
							"sku" => $input['sku'],
							"variant_option_one_name" => $input['variant_option_one_name'],
							"variant_option_one_value" => $input['variant_option_one_value'],
							"variant_option_two_name" => $input['variant_option_two_name'],
							"variant_option_two_value" => $input['variant_option_two_value'],
							"variant_option_three_name" => $input['variant_option_three_name'],
							"variant_option_three_value" => $input['variant_option_three_value'],
							"supply_price" => $input['supply_price'],
							"retail_price" => $input['price'],
							"tax" => $tax[0],
							"brand_name" => $input['brand_name'],
							"supplier_name" => $input['supplier_name'],
							"inventory" => 
								array(
									array(
										'outlet_id' => $outlet_id,
										'count' => $input['count'],
										'reorder_point' => $input['reorder_point'],
										'restock_level' => $input['restock_level']
									)
								),
									
					);

					$url = 'products';
					
					$result = $this->get('app.vend')->postVend($url, $data);
					if ($result == null) {
						$this->addFlash(
								'danger',
								'ERROR: Unable to post to Vend API.'
								);
						return $this->redirectToRoute('admin_product');
					}
					
					$result = $result->product;

					// images
					$images = $item->getImages();
					if (count($images) > 0) {
						$ctr = 0;
						foreach ($images as $image) {
							// process image
							$ctr ++;
							$filename = $this->upload('product', 'products', $input['name'] .'-'. $ctr, $image->getFilename());
							
							$image
								->setProduct($item)
								->setFileName($filename);
							$em->persist($image);
						}
					}
					
					$item
						->setId($result->id)
						->setName($result->name)
						->setBaseName($input['name'])
						->setBrandName($input['brand_name'])
						->setSupplierName($input['supplier_name'])
						->setProductType($input['product_type'])
						->setTax($result->tax)
						->setRetailPrice($result->price)
						->setPrice(($result->price + $result->tax))
						->setTaxId($tax[0])
						->setOutletId($outlet_id)
						->setCount($input['count'])
						->setReorderPoint($input['reorder_point'])
						->setRestockLevel($input['restock_level'])
						->setVariantOptionOneName($input['variant_option_one_name'])
						->setVariantOptionTwoName($input['variant_option_two_name'])
						->setVariantOptionThreeName($input['variant_option_three_name'])
						->setIsActive(1)
					;
					
					$em->persist($item);
					$em->flush();
					
					$this->addFlash('info','Product added.');
				}
				else
					$this->addFlash('danger', 'ERROR: Please check your input.');
			}
		}
			
		// sort order
		$allowed_fields = array('name', 'product_type', 'brand_name', 'supplier_name', 'is_active');
		
		if ($session->get('sort') != null) {
			$sort = $session->get('sort');
			if (!in_array($sort['name'], $allowed_fields))
				$sort = array('name'=>'name','order'=>'asc');
		}
		else
			$sort = array('name'=>'name','order'=>'asc');
			
		if (isset($data['sort'])) {
			if (in_array($data['sort'], $allowed_fields))
				$sort = array(
						'name' => $data['sort'],
						'order' => (isset($data['order']) && in_array(@$data['order'], array('asc','desc'))) ? $data['order'] : 'asc'
				);
		}
			
		$session->set('sort', $sort);
			
		// filters
		$filters = $session->get('filters_product') != null ? $session->get('filters_product') : array();
		
		// textbox filter = name
		if (isset($data['query'])) {
			$filters['full_name'] = array(
					'label' => '',
					'field' => 'name',
					'operator' => 'LIKE',
					'value' => '%'. $data['query'] .'%'
			);
		}
		
		// dropdown filters
		if (!empty($data['f']) && !empty($data['v'])) {
			$allowed_fields = array('tags', 'product_type', 'brand_name', 'supplier_name', 'is_active');
			if (in_array($data['f'], $allowed_fields)) {
				
				// field
				$field = $data['f'];
				
				// operator
				if ($field == 'tags') 
					$operator = 'LIKE';
				else
					$operator = '=';
				
				// value
				$value =  $field == 'tags' ? '%'. $data['v'] .'%' : $data['v'];
				
				// label
				switch ($field) {
					case 'tags':
						$label = 'Tagged with '. $data['v'];
						break;
					case 'product_type':
						$label = 'Product type is '. $data['v'];
						break;
					case 'brand_name':
						$label = 'Brand name is '. $data['v'];
						break;
					case 'supplier_name':
						$label = 'Supplier is '. $data['v'];
						break;
					case 'is_active':
						$label = ($value == 1 ? 'Visible at store' : 'Not visible at store');
						break;
				}
				
				$filters[$label] = array(
						'label' => $label,
						'field' => $field,
						'operator' => $operator,
						'value' => $value,
				);
			}
		}
		
		// if remove filter
		if (isset($data['remove']))
			unset($filters[$data['remove']]);
		
		$session->set('filters_product', $filters);
		
		$where = '';
		foreach ($filters as $filter)
			$where .= ' AND i.'. $filter['field'] .' '. $filter['operator'] .' :'. $filter['field'];
			
		$items = $items->createQueryBuilder('i')
			->where('i.variant_parent_id IS NULL'. $where)
			->orderBy('i.'. $sort['name'], $sort['order']);
		
		foreach ($filters as $filter)
			$items->setParameter($filter['field'], $filter['value']);
			
		$items = $items
			->getQuery()
			->getResult();
		
		// pagination
		!empty($data['page']) ? $pagenum = $data['page'] : $pagenum = 1;
		$totalItems = count($items);
		
		$itemsPerPage = 50;
		$urlPattern = $request->getPathInfo() .'?page=(:num)';
		$paginator = new Paginator($totalItems, $itemsPerPage, $pagenum, $urlPattern);
			
		return $this->render('admin/'. $mode .'products.html.twig', array(
			'items' => array_slice($items, ($pagenum - 1) * $itemsPerPage, $itemsPerPage),
			'paginator' => $paginator,
			'form' => $form->createView(),
		));
	}

	/**
	 * @Route("/admin/product-edit/{id}", name="admin_product_edit")
	 */
	public function productEdit(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();
		$repository = $em->getRepository('AppBundle:ArchProduct');
		if (($item = $repository->findOneBy(array('id' => $id))) == null) {
			$this->addFlash('danger', 'Product not found or deleted.');
			
			return $this->redirectToRoute('admin_product');
		}
		
		$originalImages = array();
		foreach ($item->getImages() as $image) {
			$originalImages[] = array(
					'id' => $image->getId(),
					'filename' => $image->getFilename(),
			);
		}
		
		if ($item == null)
			return new Response('ERROR: Product does not exist.');
			
		$form = $this->createForm(ArchProductsType::class, $item);
		
		if ($request->isMethod('POST')) {
			
			$data = $request->request->all();
			
			// delete / activate
			if (!empty($data['item']))
				if ($id == $data['item']) {
					$item->setIsActive($data['active']);
					$em->flush();
					
					$this->addFlash('info', "Product's Active status updated.");
					
					return $this->redirectToRoute('admin_product_edit', array('id' => $id));
				}
			
			
			// update
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				$input = $request->request->get('arch_products');

				// post to Vend
				$tax = explode(':', $input['tax']);
				
				// use default outlet
				$outlet_id = $em->getRepository('AppBundle:ArchOutlet')->findOneBy(array('is_default' => 1))->getId();
				$data = array(
						"id" => $id,
						"handle" => $item->getHandle(),
						"type" => $input['product_type'],
						"tags" => $input['tags'],
						"name" => $input['base_name'],
						"description" => $input['description'],
						"sku" => $input['sku'],
						"variant_option_one_name" => $input['variant_option_one_name'],
						"variant_option_one_value" => $input['variant_option_one_value'],
						"variant_option_two_name" => $input['variant_option_two_name'],
						"variant_option_two_value" => $input['variant_option_two_value'],
						"variant_option_three_name" => $input['variant_option_three_name'],
						"variant_option_three_value" => $input['variant_option_three_value'],
						"supply_price" => $input['supply_price'],
						"retail_price" => $input['price'],
						"tax" => $tax[0],
						"brand_name" => $input['brand_name'],
						"supplier_name" => $input['supplier_name'],
						"inventory" =>
							array(
								array(
										'outlet_id' => $outlet_id,
										'count' => $input['count'],
										'reorder_point' => $input['reorder_point'],
										'restock_level' => $input['restock_level']
								)
							),
				);
				
				$url = 'products';
				
				$result = $this->get('app.vend')->postVend($url, $data);
				
				if ($result == null) {
					$this->addFlash(
							'danger',
							'ERROR: Unable to post to Vend API.'
							);
					dump($result);die;
					return $this->redirectToRoute('admin_product_edit', array('id' => $id));
				}
				
				$result = $result->product;
				
				// images
				$ctr_image = count($originalImages);
				$imageList = $request->request->get('image') != null ? $request->request->get('image') : array();
				
				foreach ($originalImages as $image) {
					if (!in_array($image['id'], $imageList)) {
						if ($image['filename'] != '')
							$this->deleteProductImage($image['filename']);
							
						$image = $em->getRepository('AppBundle:ProductItemImage')->find($image['id']);
						$em->remove($image);
						
						$ctr_image--;
					}
				}
					
				$images = $item->getImages();
				if (count($images) > 0) {
					foreach ($images as $image) {
						if (!is_null($image->getFilename())) {
							// process image
							$ctr_image ++;
							$filename = $this->upload('product', 'products', $input['name'] .'-'. $ctr_image .'-'. rand(0,1000), $image->getFilename());
							
							$image
								->setProduct($item)
								->setFileName($filename);
							$em->persist($image);
						}
						else {
							if (is_null($image->getProduct()))
								$item->getImages()->removeElement($image);
							else {
								foreach ($originalImages as $originalImage) {
									if ($image->getId() == $originalImage['id']) {
										$filename = $originalImage['filename'];
										
										$image->setFilename($filename);
										$em->persist($image);
										break;
									}
								}
							}
						}
					}
				}
				
				$item
					->setName($result->name)
					->setBaseName($result->base_name)
					->setBrandName($input['brand_name'])
					->setSupplierName($input['supplier_name'])
					->setProductType($input['product_type'])
					->setTax($result->tax)
					->setRetailPrice($result->price)
					->setPrice(($result->price + $result->tax))
					->setTaxId($tax[0])
					->setOutletId($outlet_id)
					->setCount($input['count'])
					->setReorderPoint($input['reorder_point'])
					->setRestockLevel($input['restock_level'])
					->setVariantOptionOneName($input['variant_option_one_name'])
					->setVariantOptionTwoName($input['variant_option_two_name'])
					->setVariantOptionThreeName($input['variant_option_three_name'])
				;
				
				$em->persist($item);
				$em->flush();
				
				$this->addFlash('info', 'Product updated.');
				$em->flush();
			}
		}
			
			
		return $this->render('admin/products-edit.html.twig', array(
				'item' => $item,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * @Route("/admin/product-variant/{id}", name="admin_product_variant")
	 */
	public function productVariant(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();
		$item = new ArchProduct();
		
		if ($parent = $em->getRepository('AppBundle:ArchProduct')->findOneBy(array('id' => $id)) == null) {
			$this->addFlash('danger', 'Parent product does not exist.');
			return $this->redirectToRoute('admin_product');
		}
			
		
		$form = $this->createForm(ArchProductsType::class, $item);
		
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$input = $request->request->get('arch_products');
			
			// post to Vend
			// use default outlet
			$outlet_id = $em->getRepository('AppBundle:ArchOutlet')->findOneBy(array('is_default' => 1))->getId();
			$data = array(
					"variant_parent_id" => $parent->getId(),
					"handle" => $parent->getHandle(),
					"type" => $input['product_type'],
					"tags" => $input['tags'],
					"name" => $input['name'],
					"description" => $input['description'],
					"sku" => $input['sku'],
					"variant_option_one_name" => $input['variant_option_one_name'],
					"variant_option_one_value" => $input['variant_option_one_value'],
					"variant_option_two_name" => $input['variant_option_two_name'],
					"variant_option_two_value" => $input['variant_option_two_value'],
					"variant_option_three_name" => $input['variant_option_three_name'],
					"variant_option_three_value" => $input['variant_option_three_value'],
					"supply_price" => $input['supply_price'],
					"retail_price" => $input['retail_price'],
					"tax" => $input['tax'],
					"brand_name" => $input['brand_name'],
					"supplier_name" => $input['supplier_name'],
					"inventory" =>
					array(
							array(
									'outlet_id' => $outlet_id,
									'count' => $input['count'],
									'reorder_point' => $input['reorder_point'],
									'restock_level' => $input['restock_level']
							)
					),
					
			);
			
			$url = 'products';
			
			$result = $this->get('app.vend')->postVend($url, $data);
			if ($result == null) {
				$this->addFlash(
						'danger',
						'ERROR: Unable to post to Vend API.'
						);
				return $this->redirectToRoute('admin_product_edit', array('id' => $id));
			}
			
			$result = $result->product;
			
			// images
			$ctr_image = count($originalImages);
			$imageList = $request->request->get('image') != null ? $request->request->get('image') : array();
			
			foreach ($originalImages as $image) {
				if (!in_array($image['id'], $imageList)) {
					if ($image['filename'] != '')
						$this->deleteProductImage($image['filename']);
						
						$image = $em->getRepository('AppBundle:ProductItemImage')->find($image['id']);
						$em->remove($image);
						
						$ctr_image--;
				}
			}
			
			$images = $item->getImages();
			if (count($images) > 0) {
				foreach ($images as $image) {
					if (!is_null($image->getFilename())) {
						// process image
						$ctr_image ++;
						$filename = $this->upload('product', 'products', $input['name'] .'-'. $ctr_image .'-'. rand(0,1000), $image->getFilename());
						
						$image
						->setItem($item)
						->setFileName($filename);
						$em->persist($image);
					}
					else {
						if (is_null($image->getItem()))
							$item->getImages()->removeElement($image);
							else {
								foreach ($originalImages as $originalImage) {
									if ($image->getId() == $originalImage['id']) {
										$filename = $originalImage['filename'];
										
										$image->setFilename($filename);
										$em->persist($image);
										break;
									}
								}
							}
					}
				}
				
				$item
				->setName($result->name)
				->setBaseName($input['name'])
				->setBrandName($input['brand_name'])
				->setSupplierName($input['supplier_name'])
				->setProductType($input['product_type'])
				->setTax($input['tax'])
				->setOutletId($outlet_id)
				->setCount($input['count'])
				->setReorderPoint($input['reorder_point'])
				->setRestockLevel($input['restock_level'])
				->setVariantOptionOneName($input['variant_option_one_name'])
				->setVariantOptionTwoName($input['variant_option_two_name'])
				->setVariantOptionThreeName($input['variant_option_three_name'])
				;
				
				$em->persist($item);
				$em->flush();
				
				$this->addFlash('info', 'Product updated.');
				$em->flush();
			}
		}
		
		
		return true;
	}

	/**
	 * @Route("/admin/product-variant-edit/{id}", name="admin_product_variant_edit")
	 */
	public function productVariantEdit(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();
		$item = $id ? $em->getRepository('AppBundle:ArchProduct')->findOneBy(array('id' => $id)) : new ArchProduct();
		dump($item);die;
		
		if ($item == null) {
			$this->addFlash('danger', 'Product variant does not exist.');
			return $this->redirect($request->headers->get('referer'));
		}
		$form = $this->createForm(ArchProductsType::class, $item);
		
		// update
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$input = $request->request->get('arch_products');
			
			// post to Vend
			// use default outlet
			$outlet_id = $em->getRepository('AppBundle:ArchOutlet')->findOneBy(array('is_default' => 1))->getId();
			$data = array(
					"id" => $id,
					"handle" => str_replace(' ', '', $input['name']),
					"type" => $input['product_type'],
					"tags" => $input['tags'],
					"name" => $input['name'],
					"description" => $input['description'],
					"sku" => $input['sku'],
					"variant_option_one_name" => $input['variant_option_one_name'],
					"variant_option_one_value" => $input['variant_option_one_value'],
					"variant_option_two_name" => $input['variant_option_two_name'],
					"variant_option_two_value" => $input['variant_option_two_value'],
					"variant_option_three_name" => $input['variant_option_three_name'],
					"variant_option_three_value" => $input['variant_option_three_value'],
					"supply_price" => $input['supply_price'],
					"retail_price" => $input['retail_price'],
					"tax" => $input['tax'],
					"brand_name" => $input['brand_name'],
					"supplier_name" => $input['supplier_name'],
					"inventory" =>
					array(
							array(
									'outlet_id' => $outlet_id,
									'count' => $input['count'],
									'reorder_point' => $input['reorder_point'],
									'restock_level' => $input['restock_level']
							)
					),
					
			);
			
			$url = 'products';
			
			$result = $this->get('app.vend')->postVend($url, $data);
			if ($result == null) {
				$this->addFlash(
						'danger',
						'ERROR: Unable to post to Vend API.'
						);
				return $this->redirectToRoute('admin_product_edit', array('id' => $id));
			}
			
			$result = $result->product;
			
			// images
			$ctr_image = count($originalImages);
			$imageList = $request->request->get('image') != null ? $request->request->get('image') : array();
			
			foreach ($originalImages as $image) {
				if (!in_array($image['id'], $imageList)) {
					if ($image['filename'] != '')
						$this->deleteProductImage($image['filename']);
						
						$image = $em->getRepository('AppBundle:ProductItemImage')->find($image['id']);
						$em->remove($image);
						
						$ctr_image--;
				}
			}
			
			$images = $item->getImages();
			if (count($images) > 0) {
				foreach ($images as $image) {
					if (!is_null($image->getFilename())) {
						// process image
						$ctr_image ++;
						$filename = $this->upload('product', 'products', $input['name'] .'-'. $ctr_image .'-'. rand(0,1000), $image->getFilename());
						
						$image
						->setItem($item)
						->setFileName($filename);
						$em->persist($image);
					}
					else {
						if (is_null($image->getItem()))
							$item->getImages()->removeElement($image);
							else {
								foreach ($originalImages as $originalImage) {
									if ($image->getId() == $originalImage['id']) {
										$filename = $originalImage['filename'];
										
										$image->setFilename($filename);
										$em->persist($image);
										break;
									}
								}
							}
					}
				}
				
				$item
				->setName($result->name)
				->setBaseName($input['name'])
				->setBrandName($input['brand_name'])
				->setSupplierName($input['supplier_name'])
				->setProductType($input['product_type'])
				->setTax($input['tax'])
				->setOutletId($outlet_id)
				->setCount($input['count'])
				->setReorderPoint($input['reorder_point'])
				->setRestockLevel($input['restock_level'])
				->setVariantOptionOneName($input['variant_option_one_name'])
				->setVariantOptionTwoName($input['variant_option_two_name'])
				->setVariantOptionThreeName($input['variant_option_three_name'])
				;
				
				$em->persist($item);
				$em->flush();
				
				$this->addFlash('info', 'Product updated.');
				$em->flush();
			}
		}
		
		
		return true;
	}
	
	/**
	 * @Route("/admin/customer/{mode}", name="admin_customer")
	 */
	public function customer(Request $request, $mode = '') {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:User');
		$session = $this->get('session');
		
		$user = new User();
		$form = $this->createForm(UserAdminType::class, $user);
		$form->handleRequest($request);
		
		// post = add or delete
		if ($request->isMethod('POST')) {
			
			if ($form->isSubmitted()) {
				if ($form->isValid()) {
					$input = $request->request->get('user_admin');
					
					// check if email exists
					$duplicate = $items->findOneBy(array('email' => $input['email']));
					
					if (count($duplicate) > 0) 
						$this->addFlash('danger', 'ERROR: User email already in use.');
					else 
					{
						// post to Vend
						$data = array(
								'customer_code' => $input['email'],
								'company_name' => '',
								'date_of_birth' => $input['date_of_birth'],
								'sex' => $input['sex'],
								"first_name" => $input['first_name'],
								"last_name" => $input['last_name'],
								"phone" => $input['phone'],
								"mobile" => $input['mobile'],
								"fax" => $input['fax'],
								"email" => $input['email'],
								"website" => $input['website'],
								"physical_address1" => $input['physical_address1'],
								"physical_address2" => $input['physical_address2'],
								"physical_suburb" => $input['physical_suburb'],
								"physical_city" => $input['physical_city'],
								"physical_postcode" => $input['physical_postcode'],
								"physical_state" => $input['physical_state'],
								"physical_country_id" => $input['physical_country_id'],
								"postal_address1" => $input['postal_address1'],
								"postal_address2" => $input['postal_address2'],
								"postal_suburb" => $input['postal_suburb'],
								"postal_city" => $input['postal_city'],
								"postal_postcode" => $input['postal_postcode'],
								"postal_state" => $input['postal_state'],
								"postal_country_id" => $input['postal_country_id'],
						);
		
						$url = 'customers';
						
						$result = $this->get('app.vend')->postVend($url, $data);
						
						if ($result == null) {
							$this->addFlash(
									'danger',
									'ERROR: Unable to post to Vend API.'
									);
							return $this->redirectToRoute('admin_customer');
						}
	
						$password_raw = $this->get('app.misc_functions')->generatePassword();
						$factory = $this->get('security.encoder_factory');
						
						$encoder = $factory->getEncoder($user);
						$password = $encoder->encodePassword($password_raw, $user->getSalt());
						
						$result = $result->customer;
						
						$user
							->setCustomerId($result->id)
							->setFullName($input['first_name'] .' '. $input['last_name'])
							->setUpdatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', $result->updated_at))
							->setUsername($result->email)
							->setPassword($password)
							->setEmail($result->email)
							->setCustomerCode($result->customer_code)
							->setPhysicalCountryId($input['physical_country_id'])
							->setPostalCountryId($input['postal_country_id'])
							->setAccountType('Customer')
						;
						
						$em->persist($user);
						$em->flush();
						
						$this->addFlash(
								'info',
								'Customer added.'
								);
						
						// email customer
						$this->get('fos_user.mailer')->sendConfirmationEmailMessage($user);
					}
				}
				else
					$this->addFlash('danger', 'ERROR: Please check your input.');
			}
			
			
			// delete
			$data = $request->request->all();
			if (isset($data['customers'])) {
				$now = \DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
				foreach ($data['customers'] as $customer) {
					$user = $items->findOneBy(array('id' => $customer));
					$user->setDeletedAt($now);
				}
				
				$em->flush();
			}
		}
		// get parameters
		$data = $request->query->all();
		
		// sort order
		$allowed_fields = array('last_name', 'physical_city', 'customer_code', 'total_spent', 'order_count');

		if ($session->get('sort') != null) {
			$sort = $session->get('sort');
			if (!in_array($sort['name'], $allowed_fields))
				$sort = array('name'=>'id','order'=>'desc');
		}
		else
			$sort = array('name'=>'id','order'=>'desc');
			
		if (isset($data['sort'])) {
			if (in_array($data['sort'], $allowed_fields))  
				$sort = array(
						'name' => $data['sort'],
						'order' => (isset($data['order']) && in_array(@$data['order'], array('asc','desc'))) ? $data['order'] : 'asc'
				);
		}
		
		$session->set('sort', $sort);
		
		// filters
		$filters = $session->get('filters_customer') != null ? $session->get('filters_customer') : array();
		
		// textbox filter = full_name
		if (isset($data['query'])) {
			$filters['full_name'] = array(
					'label' => '',
					'field' => 'full_name',
					'operator' => 'LIKE',
					'value' => '%'. $data['query'] .'%'
			);
		}
		
		// dropdown filters
		if (!empty($data['f']) && !empty($data['o']) && isset($data['v'])) {
			$allowed_fields = array('total_spent', 'order_count', 'updated_at', 'enabled', 'accepts_marketing');
			if (in_array($data['f'], $allowed_fields)) {
				
				// field
				$field = $data['f'];
				
				// operator
				switch ($data['o']) {
					case 'greater than':
						$operator = '>';
						break;
					case 'less than':
						$operator = '<';
						break;
					case 'not equal to':
						$operator = '<>';
						break;
					default:
					case 'equal to':
						$operator = '=';
						break;
				}
				
				// value
				$value =  $data['v'] == 'updated_at' ? \DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', $data['v'])) : (int)$data['v'];
				
				// label
				switch ($field) {
					case 'total_spent':
						$label = 'Money spent is '. $data['o'] .' '. $data['v'];
						break;
					case 'order_count':
						$label = 'Number of orders is '. $data['o'] .' '. $data['v'];
						break;
					case 'updated_at':
						$label = 'Date created is after '. date('Y-m-d', $data['v']);
						break;
					case 'enabled':
						$label = ($value == 1 ? 'Account status is enabled' : 'Account status is unconfirmed/disabled');
						break;
					case 'accepts_marketing':
						$label = ($value == 1 ? 'Customer accepts marketing' : 'Customer does not accept marketing');
						break;
				}
				
				$filters[$label] = array(
						'label' => $label,
						'field' => $field,
						'operator' => $operator,
						'value' => $value,
				);
			}
		}
		
		// if remove filter
		if (isset($data['remove'])) 
			unset($filters[$data['remove']]);

		$session->set('filters_customer', $filters);
		
		$where = '';
		foreach ($filters as $filter) 
			$where .= ' AND i.'. $filter['field'] .' '. $filter['operator'] .' :'. $filter['field'];
		
		$items = $items->createQueryBuilder('i')
				->where('i.account_type = :account_type AND i.deleted_at IS NULL'. $where)
				->setParameter('account_type', 'Customer')
				->orderBy('i.'. $sort['name'], $sort['order']);
		
		foreach ($filters as $filter)
			$items->setParameter($filter['field'], $filter['value']);
		
		$items = $items
				->getQuery()
				->getResult();
		
		// pagination
		!empty($data['page']) ? $pagenum = $data['page'] : $pagenum = 1;
		$totalItems = count($items);
		
		$itemsPerPage = 50;
		$urlPattern = $request->getPathInfo() .'?page=(:num)';
		$paginator = new Paginator($totalItems, $itemsPerPage, $pagenum, $urlPattern);
		
		return $this->render('admin/'. $mode .'customers.html.twig', array(
				'items' => array_slice($items, ($pagenum - 1) * $itemsPerPage, $itemsPerPage),
				'paginator' => $paginator,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * @Route("/admin/customer-view/{id}/{mode}", name="admin_customer_view")
	 */
	public function customerView(Request $request, $id, $mode = '') {
		$em = $this->getDoctrine()->getManager();
		$repository = $em->getRepository('AppBundle:User');
		$customer = $repository->findOneBy(array('customer_id' => $id));
		$current_email = $customer->getEmail();
		
		if ($customer == null) 
			return new Response('ERROR: Customer does not exist.');
		
		$form = $this->createForm(UserAdminType::class, $customer);

		if ($request->isMethod('POST')) {
			// delete
			if (!empty($delete = $request->request->get('delete'))) 
				if ($customer->getCustomerId() == $delete) {
					$this->addFlash('info', "Customer '". $customer->getFullName() ."' deleted.");
					$customer->setDeletedAt(new \DateTime('now'));
					
					$em->flush();
					
					return $this->redirectToRoute('admin_customer');
				}
			
			// update
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				// entitytype to text dirty fix
				$data = $request->request->get('user_admin');
				$customer
					->setPhysicalCountryId($data['physical_country_id'])
					->setPostalCountryId($data['postal_country_id'])
				;
				
				// check for email duplicates
				if ($current_email != $customer->getEmail()) {
					$duplicate = $repository->findBy(array('email' => $customer->getEmail()));
					if ($duplicate != null) 
						$this->addFlash('danger', 'ERROR: Email address already in use.');
				}
				else {
					// post to Vend
					
					$this->addFlash('info', 'Customer info updated.');
					$em->flush();
				}
			}
		}
		
		return $this->render('admin/'. $mode .'customers-view.html.twig', array(
				'item' => $customer,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * @Route("/admin/sales/{mode}", name="admin_sales")
	 */
	public function sales(Request $request, $mode = '') {
		$em = $this->getDoctrine()->getManager();
		$session = $this->get('session');

		// get parameters
		$data = $request->query->all();
		
		// sort order
		$allowed_fields = array('o.sale_date', 'u.last_name', 'o.invoice_number', 'o.user', 'o.status', 'o.total_price');
		
		if ($session->get('sort') != null) {
			$sort = $session->get('sort');
			if (!in_array($sort['name'], $allowed_fields))
				$sort = array('name'=>'o.id','order'=>'desc');
		}
		else 
			$sort = array('name'=>'o.id','order'=>'desc');
		
		if (isset($data['sort'])) {
			if (in_array($data['sort'], $allowed_fields))
				$sort = array(
						'name' => $data['sort'],
						'order' => (isset($data['order']) && in_array(@$data['order'], array('asc','desc'))) ? $data['order'] : 'asc'
				);
		}
		
		$session->set('sort', $sort);
		
		// filters
		$filters = $session->get('filters_sales') != null ? $session->get('filters_sales') : array();
		
		// textbox filter = full_name
		if (!empty($data['query'])) {
			$filters['full_name'] = array(
					'label' => '',
					'field' => 'u.full_name',
					'field_np' => 'full_name',
					'operator' => 'LIKE',
					'value' => '%'. $data['query'] .'%'
			);
		}
		
		// dropdown filters
		if (!empty($data['date_from'])) {
			$filters['date_from'] = array(
					'label' => 'Dated after '. $data['date_from'],
					'field' => 'o.sale_date',
					'field_np' => 'sale_date_from',
					'operator' => '>=',
					'value' => $data['date_from'],
			);
		}
		if (!empty($data['date_to'])) {
			$filters['date_to'] = array(
					'label' => 'Dated before '. $data['date_to'],
					'field' => 'o.sale_date',
					'field_np' => 'sale_date_to',
					'operator' => '<',
					'value' => $data['date_to'],
			);
		}
		
		// if remove filter
		if (isset($data['remove']))
			unset($filters[$data['remove']]);
			
		$session->set('filters_sales', $filters);
			
		$where = array();
		
		foreach ($filters as $filter)
			$where[] = $filter['field'] .' '. $filter['operator'] .' :'. $filter['field_np'];

		$where = (count($where) > 0) ? 'WHERE '. implode(' AND ', $where) : '';
		
		$query = $em->createQuery(
				'SELECT o, u
			    FROM AppBundle:ArchOrder o
				JOIN o.customer u 
				'. $where .'
			    ORDER BY '. implode(' ', $sort)
		);
		foreach ($filters as $filter)
			$query->setParameter($filter['field_np'], $filter['value']);
				
		$items = $query->getResult();
				
		// pagination
		!empty($data['page']) ? $pagenum = $data['page'] : $pagenum = 1;
		$totalItems = count($items);
				
		$itemsPerPage = 50;
		$urlPattern = $request->getPathInfo() .'?page=(:num)';
		$paginator = new Paginator($totalItems, $itemsPerPage, $pagenum, $urlPattern);
		
		return $this->render('admin/'. $mode .'sales.html.twig', array(
				'items' => array_slice($items, ($pagenum - 1) * $itemsPerPage, $itemsPerPage),
				'paginator' => $paginator,
		));
	}
	
	/**
	 * @Route("/admin/sales/view/{id}", name="admin_sales_view")
	 */
	public function salesView($id) {
		$order = $this->getDoctrine()->getRepository('AppBundle:ArchOrder')->findOneBy(array('id'=>$id));
		if (is_null($order)) 
			return new Response('ERROR: Sale ID does not exist.');
			
		return $this->render('admin/order-view.html.twig', array(
				'item' => $order
		));
	}
	
	/** 
	 * @Route("/admin/register-sales", name="admin_register_sales")
	 */
	public function registerSales(Request $request) {
		{
			// post to Vend
			$now = date("Y-m-d H:i:s");
//			$input = $request->request->get('arch_tax');
//			$rate = (int)$input['rate'] / 100;
			$data = array(
					'register_id' => '060f02b1-c8a3-11e7-e913-139bb3ed4fb3',
					'customer_id' => '060f02b1-c84d-11e7-e913-18dbcba7be7b',
					'sale_date' => $now,
					"user_name" => "website",
					"total_price" => 20, // w/o tax
					"total_tax" => 3,
					"tax_name" => "GST",
					"status" => "CLOSED",
					"invoice_number" => "26",
					"invoice_sequence" => 26,
					"note" => null,
					"register_sale_products" => array(
							array(
									"product_id" => "060f02b1-c84d-11e7-e913-139bb44893d5",
									"quantity" => 1,
									"price" => 10,
									"discount" => 110,
									"tax" => 1.5,
									"tax_id" => "060f02b1-c8a3-11e7-e913-139bb3e351c9",
									"tax_total" => 1.5
							),
							array(
									"product_id" => "060f02b1-c84d-11e7-e913-139bb480e6ab",
									"quantity" => 1,
									"price" => 10,
									"discount" => 55,
									"tax" => 1.5,
									"tax_id" => "060f02b1-c8a3-11e7-e913-139bb3e351c9",
									"tax_total" => 1.5,
							)
						),
					"register_sale_payments" => array(
							array(
									"retailer_payment_type_id" => "060f02b1-c8a3-11e7-e913-139bb3ee4465",
									"payment_date" => $now,
									"amount" => 23
							)
						)
					);
			$url = 'register_sales';
			
			$result = $this->get('app.vend')->postVend($url, $data);
			
			if ($result == null) {
				$this->addFlash(
						'danger',
						'ERROR: Unable to post to Vend API.'
						);
				dump($result);die;
				die;
				return $this->redirectToRoute('admin_tax');
			}
			
			dump($result);die;
		}
	}
	/**
	 * @Route("/admin/settings/{mode}", name="admin_settings")
	 */
	public function settings(Request $request, $mode = 'general') {
		$em = $this->getDoctrine()->getManager();
		$config = $em->getRepository('AppBundle:ArchConfig');
		
		if ($request->isMethod('POST')) {
			// save changes
			$post = $request->request->all();
			foreach($post as $key => $value) 
				$config->findOneBy(array('name' => $key))->setValue($value);
			
			$em->flush();
			
			$this->addFlash('info', 'Settings saved.');
		}
		
		$config = $config->createQueryBuilder('c')->where('c.name LIKE :name');
		switch ($mode) {
			case 'vend-api':
				$title = 'Vend API';
				$config = $config
				->setParameter('name', 'vend_%')
				->getQuery()
				->getResult();
				break;
			default:
			case 'general':
				$title = 'General';
				$config = $config
				->setParameter('name', 'gen_%')
				->getQuery()
				->getResult();
				break;
		}
		
		return $this->render('admin/settings.html.twig', array(
				'mode' => $mode,
				'title' => $title,
				'config' => $config
		));
	}
	
	/**
	 * @Route("/admin/tax/{mode}", name="admin_tax")
	 */
	public function tax(Request $request, $mode = '') {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchTax');
		$default = $items->findBy(array('is_default' => 1));
		
		$tax = new ArchTax();
		$form = $this->createForm(ArchTaxType::class, $tax);
		$form->handleRequest($request);
		
		if ($form->isSubmitted()) {
			if ($form->isValid()) {
				// post to Vend
				$input = $request->request->get('arch_tax');
				$rate = (int)$input['rate'] / 100;
				$data = array(
						'name' => $input['name'],
						'rate' => $rate
				);
				$url = 'taxes';
	
				$result = $this->get('app.vend')->postVend($url, $data);
	
				if ($result == null) {
					$this->addFlash(
							'danger', 
							'ERROR: Unable to post to Vend API.'
					);
					return $this->redirectToRoute('admin_tax');
				}
				
				$tax
					->setId($result->id)
					->setRate($rate)
					->setIsActive($result->active)
					->setIsDefault($result->default)
				;
				
				$em->persist($tax);
				$em->flush();
				
				$this->addFlash(
						'info',
						'Sales Tax added.'
				);
			}
			else
				$this->addFlash('danger', 'ERROR: Please check your input.');
		}
		
		return $this->render('admin/'. $mode .'taxes.html.twig', array(
				'form' => $form->createView(),
				'items' => array_reverse($items->findAll()),
				'default' => count($default)
		));
	}
	
	/**
	 * @Route("/admin/supplier/{mode}", name="admin_supplier")
	 */
	public function supplier(Request $request, $mode = '') {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchSupplier');
		
		$item = new ArchSupplier();
		$form = $this->createForm(ArchSupplierType::class, $item);
		$form->handleRequest($request);
		
		if ($form->isSubmitted()) {
			if ($form->isValid()) {
				// post to Vend
				$input = $request->request->get('arch_supplier');
				$data = array(
						'name' => $input['name'],
						'description' => $input['description']
				);
				$url = 'supplier';
				
				$result = $this->get('app.vend')->postVend($url, $data);
				
				if ($result == null) {
					$this->addFlash(
							'danger',
							'ERROR: Unable to post to Vend API.'
							);
					return $this->redirectToRoute('admin_supplier');
				}
				
				$item
					->setId($result->id)
				;
				
				$em->persist($item);
				$em->flush();
				
				$this->addFlash(
						'info',
						'Supplier added.'
				);
			}
			else
				$this->addFlash('danger', 'ERROR: Please check your input.');
		}
		
		return $this->render('admin/'. $mode .'suppliers.html.twig', array(
				'form' => $form->createView(),
				'items' => array_reverse($items->findAll())
		));
	}
	
	/**
	 * @Route("/admin/product-type", name="admin_product_type")
	 */
	public function productType(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchProductType');
		
		$item = new ArchProductType();
		$form = $this->createForm(ArchProductTypeType::class, $item);
		$form->handleRequest($request);
		
		if ($request->isMethod('POST')) {
			// delete
			$data = $request->request->all();
			if (isset($data['items'])) {
				foreach ($data['items'] as $item) {
					$item = $items->findOneBy(array('id' => $item));
					$item->setIsActive(0);
				}
				
				$em->flush();
			}
			
			// add
			if ($form->isSubmitted() && $form->isValid()) {

				// image
				if ($item->getImage()!= null) {
					$filename = $this->upload('banner_thumb', 'product-types', $item->getName(), $item->getImage());
					$item->setImage($filename);
				}
				
				$em->persist($item);
				$em->flush();
				
				$this->addFlash(
					'info',
					'Product Type added.'
				);
			}
		}
		
		return $this->render('admin/product_types.html.twig', array(
				'form' => $form->createView(),
				'items' => $items->findAll(),
		));
	}

	/**
	 * @Route("/admin/product-type-edit/{id}", name="admin_product_type_edit")
	 */
	public function productTypeEdit(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository('AppBundle:ArchProductType')->findOneBy(array('id' => $id));
		$old_image = $item->getImage();
		
		if ($item == null) {
			$this->addFlash('danger', 'ERROR: Product type does not exist.');
			
			return $this->redirectToRoute('admin_product_type');
		}
		
		$form = $this->createForm(ArchProductTypeType::class, $item);
		$form->handleRequest($request);
		
		// edit
		if ($form->isSubmitted() && $form->isValid()) {
			// image
			if ($item->getImage()!= null) {
				$filename = $this->upload('banner_thumb', 'product-types', $item->getName(), $item->getImage(), $old_image);
				$item->setImage($filename);
			}
			
			$em->persist($item);
			$em->flush();
			
			$this->addFlash(
				'info',
				'Product Type updated.'
			);
		}
		
		return $this->render('admin/product_types-edit.html.twig', array(
			'form' => $form->createView(),
			'item' => $item,
		));
	}
	
	/**
	 * @Route("/admin/collection", name="admin_collection")
	 */
	public function collection(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchProductCollection');
		
		$item = new ArchProductCollection();
		$form = $this->createForm(ArchProductCollectionType::class, $item);
		$form->handleRequest($request);
		
		if ($request->isMethod('POST')) {
			// delete
			$data = $request->request->all();
			if (isset($data['items'])) {
				foreach ($data['items'] as $item) {
					$item = $items->findOneBy(array('id' => $item));
					$item->setIsActive(0);
				}
				
				$em->flush();
			}
			
			// add
			if ($form->isSubmitted() && $form->isValid()) {
				// image
				if ($item->getImage()!= null) {
					$filename = $this->upload('banner_thumb', 'collections', $item->getName(), $item->getImage());
					$item->setImage($filename);
				}
				
				$em->persist($item);
				$em->flush();
				
				$this->addFlash(
						'info',
						'Collection added.'
				);
			}
		}
		
		return $this->render('admin/collections.html.twig', array(
				'form' => $form->createView(),
				'items' => $items->findAll(),
		));
	}
	
	/**
	 * @Route("/admin/collection-view/{id}", name="admin_collection_view")
	 */
	public function collectionView(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository('AppBundle:ArchProductCollection')->findOneBy(array('id' => $id));
		$old_image = $item->getImage();
		
		if ($item == null) {
			$this->addFlash('danger', 'ERROR: Collection does not exist.');
			return $this->redirectToRoute('admin_collection');
		}
		
		$form = $this->createForm(ArchProductCollectionType::class, $item);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			// image
			if ($item->getImage()!= null) {
				$filename = $this->upload('banner_thumb', 'collections', $item->getName(), $item->getImage(), $old_image);
				$item->setImage($filename);
			}
			
			$em->flush();
			
			$this->addFlash(
					'info',
					'Collection updated.'
			);
		}
		
		return $this->render('admin/collections-edit.html.twig', array(
				'form' => $form->createView(),
				'item' => $item,
		));
	}
	
	/**
	 * @Route("/admin/collection-list/{id}", name="admin_collection_list")
	 */
	public function collectionList(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository('AppBundle:ArchProductCollection')->findOneBy(array('id'=>$id));
		
		if ($item == null)
			return new Response('ERROR: Collection does not exist.');
		
		return $this->render('admin/collections-list.html.twig', array(
				'items' => $item->getProductTypes(),
				'item' => $item
		));
	}
	
	/**
	 * @Route("/admin/brand", name="admin_brand")
	 */
	public function brand(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchProductBrand');
		
		$item = new ArchProductBrand();
		$form = $this->createForm(ArchProductBrandType::class, $item);
		$form->handleRequest($request);
		
		if ($request->isMethod('POST')) {
			// delete
			$data = $request->request->all();
			if (isset($data['items'])) {
				foreach ($data['items'] as $item) {
					$item = $items->findOneBy(array('id' => $item));
					$item->setIsActive(0);
				}
				
				$em->flush();
			}
			
			// add
			if ($form->isSubmitted() && $form->isValid()) {
				
				// image
				if ($item->getImage()!= null) {
					$filename = $this->upload('thumb', 'brands', $item->getName(), $item->getImage());
					$item->setImage($filename);
				}
				
				$em->persist($item);
				$em->flush();
				
				$this->addFlash(
						'info',
						'Product Brand added.'
				);
			}
		}
		
		return $this->render('admin/brands.html.twig', array(
				'form' => $form->createView(),
				'items' => $items->findAll(),
		));
	}
	
	/**
	 * @Route("/admin/brand-edit/{id}", name="admin_brand_edit")
	 */
	public function brandEdit(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository('AppBundle:ArchProductBrand')->findOneBy(array('id' => $id));
		$old_image = $item->getImage();
		
		if ($item == null) {
			$this->addFlash('danger', 'ERROR: Brand does not exist.');
			
			return $this->redirectToRoute('admin_brand');
		}
		
		$form = $this->createForm(ArchProductBrandType::class, $item);
		$form->handleRequest($request);
		
		// edit
		if ($form->isSubmitted() && $form->isValid()) {
			// image
			if ($item->getImage()!= null) {
				$filename = $this->upload('thumb', 'brands', $item->getName(), $item->getImage(), $old_image);
				$item->setImage($filename);
			}
			
			$em->persist($item);
			$em->flush();
			
			$this->addFlash(
					'info',
					'Brand updated.'
					);
		}
		
		return $this->render('admin/brands-edit.html.twig', array(
				'form' => $form->createView(),
				'item' => $item,
		));
	}
	
	/**
	 * @Route("/admin/tag", name="admin_tag")
	 */
	public function tag(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchProductTag');
		
		$item = new ArchProductTag();
		$form = $this->createForm(ArchProductTagType::class, $item);
		$form->handleRequest($request);
		
		if ($request->isMethod('POST')) {
			// delete
			$data = $request->request->all();
			if (isset($data['items'])) {
				foreach ($data['items'] as $item) {
					$item = $items->findOneBy(array('id' => $item));
					$item->setIsActive(0);
				}
				
				$em->flush();
			}
			
			// add
			if ($form->isSubmitted() && $form->isValid()) {
				$em->persist($item);
				$em->flush();
				
				$this->addFlash(
						'info',
						'Tag added.'
				);
			}
		}
		
		return $this->render('admin/tags.html.twig', array(
				'form' => $form->createView(),
				'items' => $items->findAll(),
		));
	}
	
	/**
	 * @Route("/admin/tag-edit/{id}", name="admin_tag_edit")
	 */
	public function tagEdit(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository('AppBundle:ArchProductTag')->findOneBy(array('id' => $id));
		
		if ($item == null) {
			$this->addFlash('danger', 'ERROR: Tag does not exist.');
			
			return $this->redirectToRoute('admin_tag');
		}
		
		$form = $this->createForm(ArchProductTagType::class, $item);
		$form->handleRequest($request);
		
		// edit
		if ($form->isSubmitted() && $form->isValid()) {
			$em->persist($item);
			$em->flush();
			
			$this->addFlash(
					'info',
					'Tag updated.'
			);
		}
		
		return $this->render('admin/tags-edit.html.twig', array(
				'form' => $form->createView(),
				'item' => $item,
		));
	}
	
	/**
	 * @Route("/admin/outlet/{mode}", name="admin_outlet")
	 */
	public function outlet(Request $request, $mode = '') {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchOutlet');
		$default = $items->findBy(array('is_default' => 1));

		return $this->render('admin/'. $mode .'outlets.html.twig', array(
				'items' => $items->findAll(),
				'default' => count($default),
		));
	}
	
	/**
	 * @Route("/admin/register/{mode}", name="admin_register")
	 */
	public function register(Request $request, $mode = '') {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchRegister');
		$default = $items->findBy(array('is_default' => 1));
		
		return $this->render('admin/'. $mode .'registers.html.twig', array(
				'items' => $items->findAll(),
				'default' => count($default),
		));
	}
	
	/**
	 * @Route("/admin/payment-type/{mode}", name="admin_payment_type")
	 */
	public function paymentType(Request $request, $mode = '') {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchPaymentType');
		$default = $items->findBy(array('is_default' => 1));
		
		return $this->render('admin/'. $mode .'payment_types.html.twig', array(
				'items' => $items->findAll(),
				'default' => count($default),
		));
	}
	
	/**
	 * @Route("/admin/shipping", name="admin_shipping")
	 */
	public function shipping(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$products = $em->getRepository('AppBundle:ArchProduct');
		
		return $this->render('admin/shipping.html.twig', array(
		));
	}
	
	private function upload($mode, $folder, $name, $image, $old_image = '') {
			$fs = new Filesystem();
			$rootDir = $this->container->getParameter('kernel.root_dir');
			$resizer = $this->get('app.image_resizer');
			$uploader = $this->get('app.file_uploader');

			if ($fs->exists($rootDir . '/../web/images/'. $folder))
				$imagesDir = $rootDir . '/../web/images/'. $folder;
			else
				$imagesDir = $rootDir . '/../public_html/images/'. $folder;
			
			$uploader->setTargetDir($imagesDir);
			
			// if has old_image, delete
			if (!empty($old_image)) {
				switch ($mode) {
					case 'banner_thumb': 
						$fs->remove($imagesDir .'/banner/'. $old_image);
						break;
				}

				$fs->remove($imagesDir .'/'. $old_image);
				$fs->remove($imagesDir .'/thumb/'. $old_image);
			}
				
			// process image
			$alias = $this->get('app.misc_functions')->slug($name);
			$filename = $uploader->upload($image, $alias);
			
			$source = $imagesDir . '/' . $filename;

			switch ($mode) {
				case 'product':
					$resized = $imagesDir . '/full/' . $filename;
					$resizer->resize($source, null, 590, 800, true, $resized, false, false, 90);
					
					$resized = $imagesDir . '/thumb/' . $filename;
					$resizer->resize($source, null, 220, 240, false, $resized, false, false, 90);
					break;
				case 'banner_thumb':
					$resized = $imagesDir . '/banner/' . $filename;
					$resizer->resize($source, null, 900, 368, false, $resized, false, false, 90);
					
					$resized = $imagesDir . '/thumb/' . $filename;
					$resizer->resize($source, null, 600, 600, false, $resized, false, false, 90);
					break;
				case 'thumb':
					$resized = $imagesDir . '/thumb/' . $filename;
					$resizer->resize($source, null, 600, 600, true, $resized, false, false, 90);
					break;
			}

			return $filename;
	}
	
	public function deleteProductImage($image) {
		$fs = new Filesystem();
		$rootDir = $this->container->getParameter('kernel.root_dir');
		$resizer = $this->get('app.image_resizer');
		$uploader = $this->get('app.file_uploader');
		
		if ($fs->exists($rootDir . '/../web/images/'. $folder))
			$imagesDir = $rootDir . '/../web/images/'. $folder;
			else
				$imagesDir = $rootDir . '/../public_html/images/'. $folder;
		
		$fs->remove($imagesDir .'/products/'. $image['filename']);
		$fs->remove($imagesDir .'/products/full/'. $image['filename']);
		$fs->remove($imagesDir .'/products/thumb/'. $image['filename']);
		
	}
	
	/**
	 * @Route("admin/get-select", name="admin_get_select")
	 */
	public function getSelect(Request $request) {
		$field = $request->request->get('field');
		$allowed_fields = array('product_type', 'brand_name', 'supplier_name');
		if (!in_array($field, $allowed_fields))
			die;
		
		switch ($field) {
			case 'product_type':
				$entity = 'ArchProductType';
				break;
			case 'brand_name':
				$entity = 'ArchProductBrand';
				break;
			case 'supplier_name':
				$entity = 'ArchSupplier';
				break;
		}
		
		$items = $this->getDoctrine()->getRepository('AppBundle:'. $entity)->findBy(array('is_active' => 1));
		
		return $this->render('admin/ajax_select_filters.html.twig', array(
				'items' => $items
		));
	}
}
?>