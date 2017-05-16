<?php 
namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JasonGrimes\Paginator;
use AppBundle\Entity\ArchProductCollection;
use AppBundle\Entity\ArchProduct;
use AppBundle\Entity\ArchProductCollectionFilter;
use AppBundle\Entity\ArchProductCollectionProduct;
use AppBundle\Entity\ArchProductDiscounter;
use AppBundle\Entity\ArchProductDiscounterFilter;
use AppBundle\Entity\ArchProductDiscounterProduct;
use Doctrine\Common\Collections\ArrayCollection;

class ArchProductSearchController extends Controller
{
	/**
	 * @Route("/admin/product-search", name="admin_product_search") 
	 */
	public function productSearch(Request $request, $mode = '') {
		// mode can be used to switch template to ajax
		$session = $this->get('session');
		$data = $request->query->all();
		
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchProduct');
		
		// collection search uses a different item query
		$collection_search = 0;
		
		// filters
		$filters = $session->get('filters_search') != null ? $session->get('filters_search') : array();
		
		// dropdown filters
		if (!empty($data['f']) && isset($data['v'])) {
			$allowed_fields = array('tags', 'product_type', 'brand_name', 'supplier_name', 'is_active', 'vend_active', 'pre_sell', 'collection');
			
			if (in_array($data['f'], $allowed_fields)) {
				
				if ($data['f'] == 'collection') {
					$collection_search = 1;
					$filters = array();
					$collection = $em->getRepository('AppBundle:ArchProductCollection')->findOneBy(array('name' => $data['v']));
					$items = new ArrayCollection();
					
					foreach ($collection->getProducts() as $product) {
						$items->add($product->getProduct());
					}
				}
				else {
					
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
		}
		
		// if remove filter
		if (isset($data['remove']))
			unset($filters[$data['remove']]);
			
		$session->set('filters_search', $filters);
			
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
									$product = $items->findOneBy(array('id' => $product, 'variant_parent_id' => null));
									
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
									$product = $items->findOneBy(array('id' => $product, 'variant_parent_id' => null));
									
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
						->where('i.variant_parent_id IS NULL AND i.deleted_at IS NULL'. $where)
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
			if (!empty($data['discounter_name']) && !empty($data['rate']) && !empty($data['suffix']) && !empty($data['discount_type'])) {
				
				$discounter = new ArchProductDiscounter();
				$discounter
					->setName($data['discounter_name'])
					->setRate($data['rate'])
					->setSuffix($data['suffix'])
					->setDiscountType($data['discount_type'])
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
						->where('i.variant_parent_id IS NULL AND i.deleted_at IS NULL'. $where)
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
				
				$this->addFlash('info', "New Discount group: '". $data['discounter_name'] ."' added.");
			}
		}

		if ($collection_search == 0) {
			
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
				->where('i.variant_parent_id IS NULL AND i.deleted_at is NULL'. $where)
				->orderBy('i.'. $sort['name'], $sort['order']);
			
			foreach ($filters as $filter)
				$items->setParameter($filter['field'], $filter['value']);
	
			if (!empty($data['query'])) 
				$items->setParameter('name', '%'. $data['query'] .'%');
			
				
			$items = $items
				->getQuery()
				->getResult();

				!empty($data['page']) ? $pagenum = $data['page'] : $pagenum = 1;
				$itemsPerPage = 50;
				$finalItems = array_slice($items, ($pagenum - 1) * $itemsPerPage, $itemsPerPage);
		}
		else {
			$pagenum = 1;
			$finalItems = $items;
			$itemsPerPage = 1000;
		}
		
		// pagination
		$totalItems = count($items);
		
		$urlPattern = $request->getPathInfo() .'?page=(:num)';
		$paginator = new Paginator($totalItems, $itemsPerPage, $pagenum, $urlPattern);
			
		return $this->render('admin/ajax_products-search.html.twig', array(
			'items' => $finalItems,
			'paginator' => $paginator,
		));
	}
}
?>