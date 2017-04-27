<?php 
namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JasonGrimes\Paginator;
use AppBundle\Entity\ArchProductCollection;
use AppBundle\Entity\ArchProduct;
use AppBundle\Form\ArchProductsType;
use AppBundle\Entity\ArchProductOption;
use AppBundle\Entity\ArchProductCollectionFilter;
use AppBundle\Entity\ArchProductCollectionProduct;
use AppBundle\Entity\ArchProductDiscounter;
use AppBundle\Entity\ArchProductDiscounterFilter;
use AppBundle\Entity\ArchProductDiscounterProduct;

class ArchProductController extends Controller
{
	/**
	 * @Route("/admin/product/{mode}", name="admin_product") 
	 */
	public function product(Request $request, $mode = '') {
		// mode can be used to switch template to ajax
		$session = $this->get('session');
		$data = $request->query->all();
		
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchProduct');

		// filters
		$filters = $session->get('filters_product') != null ? $session->get('filters_product') : array();
		
		// dropdown filters
		if (!empty($data['f']) && isset($data['v'])) {
			$allowed_fields = array('tags', 'product_type', 'brand_name', 'supplier_name', 'is_active', 'vend_active', 'pre_sell');
			
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
				$label = $this->get('app.misc_functions')->getLabel($field, $value);
				
				if ($field != 'tags') {
					foreach ($filters as $filter) {
						if ($filter['field'] == $field)
							unset($filters[$filter['label']]);
					}
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
			
		
		$item = new ArchProduct();
		$form = $this->createForm(ArchProductsType::class, $item);
		$form->handleRequest($request);
		
		// post = add or delete
		if ($request->isMethod('POST')) {
			
			// list actions
			$data = $request->request->all();
			if (!empty($data['products'])) {
				$list_action = $data['list_action'];
				$products = $data['products'];

				if ($list_action == 'disable' || $list_action == 'enable') {
					$status = $list_action == 'disable' ? 0 : 1;
					
					foreach ($products as $product) {
						$product = $items->findOneBy(array('id' => $product));
						$product->setIsActive($status);
					}
				}

				if ($list_action == 'collection_add' || $list_action == 'collection_remove') {
					
					if (empty($data['collection'])) {
						$this->addFlash('danger', 'Please specify a collection.');
						
						return $this->redirectToRoute('admin_product');
					}
					
					$msg_collection = array();

					foreach ($data['collection'] as $collection) { 
						$collection = $em->getRepository('AppBundle:ArchProductCollection')->find($collection);
						
						if ($collection != null) {
							$msg_collection[] = "'". $collection->getName() ."'";
							
							if ($list_action == 'collection_add') {
								
								foreach ($products as $product) {
									$product = $items->findOneBy(array('id' => $product));
									
									// do not add if already in list
									$exists = 0;
									foreach ($collection->getProducts() as $collectionProduct) {
										if ($collectionProduct->getProduct() == $product)
											$exists = 1;
									}
									
									if ($exists == 0) {
										$collectionProduct = new ArchProductCollectionProduct();
										$collectionProduct
											->setCollection($collection)
											->setProduct($product)
										;
										$em->persist($collectionProduct);
										
										$collection->addProduct($collectionProduct);
									}
								}
							}
							
							if ($list_action == 'collection_remove') {
		
								foreach ($products as $product) {
									$collectionProduct = $em->getRepository('AppBundle:ArchProductCollectionProduct')->findOneBy(array('collection_id' => $collection->getId(), 'product_id' => $product));
									if ($collectionProduct != null) 
										$em->remove($collectionProduct);
									
								}
		
							}
						}
					}
					
					$msg_collection = implode(', ', $msg_collection);
					if ($list_action == 'collection_add')
						$this->addFlash('info', "Products added to ". $msg_collection .' collection.');
					if ($list_action == 'collection_remove')
						$this->addFlash('info', "Products removed from ". $msg_collection .' collection.');
				}
				
				if ($list_action == 'discounter_add' || $list_action == 'discounter_remove') {
					
					if (empty($data['discounter'])) {
						$this->addFlash('danger', 'Please specify a discount group.');
						
						return $this->redirectToRoute('admin_product');
					}
					
					$msg_discounter = array();
					
					foreach ($data['discounter'] as $discounter) {
						$discounter = $em->getRepository('AppBundle:ArchProductDiscounter')->find($discounter);
						
						if ($discounter != null) {
							$msg_discounter[] = "'". $discounter->getName() ."'";
							
							if ($list_action == 'discounter_add') {
								
								foreach ($products as $product) {
									$product = $items->findOneBy(array('id' => $product));
									
									// do not add if already in list
									$exists = 0;
									foreach ($discounter->getProducts() as $discounterProduct) {
										if ($discounterProduct->getProduct() == $product)
											$exists = 1;
									}
									
									if ($exists == 0) {
										$discounterProduct = new ArchProductDiscounterProduct();
										$discounterProduct
											->setDiscounter($discounter)
											->setProduct($product)
										;
										$em->persist($discounterProduct);
										
										$discounter->addProduct($discounterProduct);
									}
								}
							}
								
							if ($list_action == 'discounter_remove') {
								
								foreach ($products as $product) {
									$discounterProduct = $em->getRepository('AppBundle:ArchProductDiscounterProduct')->findOneBy(array('discounter_id' => $discounter->getId(), 'product_id' => $product));
									if ($discounterProduct != null)
										$em->remove($discounterProduct);
										
								}
							}
						}
					}
					
					$msg_discounter = implode(', ', $msg_discounter);
					if ($list_action == 'discounter_add')
						$this->addFlash('info', "Products added to ". $msg_discounter .' discount group.');
					if ($list_action == 'discounter_remove')
						$this->addFlash('info', "Products removed from ". $msg_discounter .' discount group.');
				}
			
				$em->flush();
			}
			
			// new collection
			if (!empty($data['collection_name'])) {
				$name = $data['collection_name'];
				$collection = new ArchProductCollection();
				$collection
					->setName($name)
					->setHandle($this->get('app.misc_functions')->slug($name));
				;
				
				$em->persist($collection);

				if (count($filters) > 0) {
					$where = '';
					foreach ($filters as $filter) {
						$collectionFilter = new ArchProductCollectionFilter();
						$collectionFilter
							->setCollection($collection)
							->setField($filter['field'])
							->setValue($filter['value'])
						;
						$em->persist($collectionFilter);
						
						$where .= ' AND i.'. $filter['field'] .' '. $filter['operator'] .' :'. $filter['field'];
					}
					
					// add products to ArchCollectionProducts
					$collectionItems = $items->createQueryBuilder('i')
						->where('i.variant_parent_id IS NULL'. $where)
						->orderBy('i.name', 'ASC');
						
					foreach ($filters as $filter)
						$collectionItems->setParameter($filter['field'], $filter['value']);
							
					$collectionItems = $collectionItems
						->getQuery()
						->getResult();
	
					foreach ($collectionItems as $collectionItem) {
						$collectionProduct = new ArchProductCollectionProduct();
						$collectionProduct
							->setCollection($collection)
							->setProduct($collectionItem)
						;
						
						$em->persist($collectionProduct);
					}
				}
					
				$em->flush();
				$this->addFlash('info', "New Collection: '". $name ."' added.");
			}
			
			// new discount group
			if (!empty($data['discounter_name']) && !empty($data['rate']) && !empty($data['suffix'])) {
				$name = $data['discounter_name'];
				$rate = $data['rate'];
				$suffix = $data['suffix'];
				
				$discounter = new ArchProductDiscounter();
				$discounter
					->setName($name)
					->setRate($rate)
					->setSuffix($suffix)
				;
				
				$em->persist($discounter);
				
				if (count($filters) > 0) {
					$where = '';
					foreach ($filters as $filter) {
						$discounterFilter = new ArchProductDiscounterFilter();
						$discounterFilter
							->setDiscounter($discounter)
							->setField($filter['field'])
							->setValue($filter['value'])
						;
						
					$em->persist($discounterFilter);
					
					$where .= ' AND i.'. $filter['field'] .' '. $filter['operator'] .' :'. $filter['field'];
					}
					
					// add products to ArchDiscounterProducts
					$discounterItems = $items->createQueryBuilder('i')
						->where('i.variant_parent_id IS NULL'. $where)
						->orderBy('i.name', 'ASC');
						
					foreach ($filters as $filter)
						$discounterItems->setParameter($filter['field'], $filter['value']);
							
					$discounterItems = $discounterItems
						->getQuery()
						->getResult();
							
					foreach ($discounterItems as $discounterItem) {
						$discounterProduct = new ArchProductDiscounterProduct();
						$discounterProduct
							->setDiscounter($discounter)
							->setProduct($discounterItem)
						;
						
						$em->persist($discounterProduct);
					}
				}					
				$em->flush();
				
				$this->addFlash('info', "New Discount group: '". $name ."' added.");
			}
			
			// add
			if ($form->isSubmitted()) {
				if ($form->isValid()) {
					$input = $request->request->get('arch_products');
					
					$handle = $this->get('app.misc_functions')->slug($input['name']);
					// post to Vend
					$tax = explode(':', $input['tax']);
					
					// use default outlet
					$outlet_id = $em->getRepository('AppBundle:ArchOutlet')->findOneBy(array('is_default' => 1))->getId();
					$data = array(
							"handle" => $handle,
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
							$filename = $this->get('app.image_functions')->upload('product', 'products', $input['name'] .'-'. $ctr, $image->getFilename());
							
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
						->setHandle($handle)
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
						->setIsActive($input['is_active'])
						->setPreSell($input['pre_sell'])
					;
					
					$em->persist($item);
					$em->flush();
					
					$this->addFlash('info','Product added.');
					
					return $this->redirectToRoute('admin_product');
				}
				else
					$this->addFlash('danger', 'ERROR: Please check your input.');
			}
		}
			
		// sort order
		$allowed_fields = array('name', 'product_type', 'brand_name', 'supplier_name', 'is_active', 'vend_active', 'pre_sell');
		
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
			
		$where = '';
		foreach ($filters as $filter)
			$where .= ' AND i.'. $filter['field'] .' '. $filter['operator'] .' :'. $filter['field'];

		// textbox filter = name or handle
		if (!empty($data['query'])) {
			$where .= ' AND (i.name LIKE :name OR i.handle LIKE :name OR i.id LIKE :name)';
		}
			
		$items = $items->createQueryBuilder('i')
			->where('i.variant_parent_id IS NULL'. $where)
			->orderBy('i.'. $sort['name'], $sort['order']);
		
		foreach ($filters as $filter)
			$items->setParameter($filter['field'], $filter['value']);

		if (!empty($data['query'])) 
			$items->setParameter('name', '%'. $data['query'] .'%');
		
			
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
	 * @Route("/admin/product-edit/{id}/{mode}", name="admin_product_edit")
	 */
	public function productEdit(Request $request, $id, $mode = '') {
		$em = $this->getDoctrine()->getManager();
		$repository = $em->getRepository('AppBundle:ArchProduct');
		if (($item = $repository->findOneBy(array('id' => $id))) == null) {
			$this->addFlash('danger', 'Product not found or deleted.');
			
			return $this->redirectToRoute('admin_product');
		}
		
		// check for new variant options
		$variant_options = array();
		if (!empty($variant = $item->getVariantOptionOneName()))
			$variant_options[] = $variant;
		if (!empty($variant = $item->getVariantOptionTwoName()))
			$variant_options[] = $variant;
		if (!empty($variant = $item->getVariantOptionThreeName()))
			$variant_options[] = $variant;
		
		if (count($variant_options) > 0) {
			$variants = $em->getRepository('AppBundle:ArchProductOption');
			
			foreach($variant_options as $variant_option) {
				$exists = $variants->findOneBy(array('name' => $variant_option));
				if (count($exists) > 0) {
					$variant = new ArchProductOption();
					$variant
						->setName($variant_option)
						->setIsActive(1)
					;
					
					$em->persist($variant);
					$em->flush();
				}
			}
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
			if (isset($data['item']))
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
						"handle" => $input['handle'],
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
					return $this->redirectToRoute('admin_product_edit', array('id' => $id));
				}
				
				$result = $result->product;

				$item
					->setName($result->name)
					->setBaseName($result->base_name)
					->setHandle($result->handle)
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
					
				// images
				$ctr_image = count($originalImages);
				$imageList = $request->request->get('image') != null ? $request->request->get('image') : array();
				
				foreach ($originalImages as $image) {
					if (!in_array($image['id'], $imageList)) {
						if ($image['filename'] != '')
							$this->get('app.image_functions')->deleteProductImage($image['filename']);
							
						$image = $em->getRepository('AppBundle:ArchProductImage')->find($image['id']);
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
							$filename = $this->get('app.image_functions')->upload('product', 'products', $input['name'] .'-'. $ctr_image .'-'. rand(0,1000), $image->getFilename());
							
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
				
				$this->addFlash('info', 'Product updated.');
				$em->flush();
			}
		}
		
		// collections
		$collections = $em->getRepository('AppBundle:ArchProductCollectionProduct')->findBy(array('product_id' => $id));
			
			
		return $this->render('admin/'. $mode .'products-edit.html.twig', array(
				'item' => $item,
				'form' => $form->createView(),
				'collections' => $collections,
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
						$this->get('app.image_functions')->deleteProductImage($image['filename']);
						
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
						$filename = $this->get('app.image_functions')->upload('product', 'products', $input['name'] .'-'. $ctr_image .'-'. rand(0,1000), $image->getFilename());
						
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
						$this->get('app.image_functions')->deleteProductImage($image['filename']);
						
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
						$filename = $this->get('app.image_functions')->upload('product', 'products', $input['name'] .'-'. $ctr_image .'-'. rand(0,1000), $image->getFilename());
						
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
}
?>