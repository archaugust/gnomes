<?php 
namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\ArchProductCollection;
use AppBundle\Entity\ArchProduct;
use AppBundle\Form\ArchProductCollectionType;
use AppBundle\Entity\ArchProductCollectionProduct;
use AppBundle\Form\ArchProductCollectionNewType;

class ArchProductCollectionController extends Controller
{
	
	/**
	 * @Route("/admin/collection", name="admin_collection")
	 */
	public function collection(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchProductCollection');

		$item = new ArchProductCollection();
		$form = $this->createForm(ArchProductCollectionNewType::class, $item);
		
		if ($request->isMethod('POST')) {
			// delete
			$data = $request->request->all();
			if (isset($data['items'])) {
				$list_action = $data['list_action'];
				if ($list_action == 'delete') {
					foreach ($data['items'] as $item) {
						$item = $items->findOneBy(array('id' => $item));
						
						foreach($item->getFilters() as $filter) 
							$em->remove($filter);
						
						foreach($item->getProducts() as $product)
							$em->remove($product);
						
						// remove associated categories
						$categoryCollections = $em->getRepository('AppBundle:ArchProductCategoryCollection')->findBy(array('collection_id' => $item->getId()));
						foreach ($categoryCollections as $categoryCollection) 
							$em->remove($categoryCollection);
								
						$em->remove($item);
					}
				}
				else {
					$status = $list_action == 'disable' ? 0 : 1;
					
					foreach ($data['items'] as $item) {
						$item = $items->findOneBy(array('id' => $item));
						$item->setIsActive($status);
					}
					
				}
				
				$this->addFlash('info', 'Collection(s) '. $list_action .'d.');
				
				$em->flush();
			}

			// add
			$form->handleRequest($request);
			
			if ($form->isSubmitted() && $form->isValid()) {
				
				if (count($item->getFilters()) > 0) {
						
					// filters
					$filters = array();
					$where = '';
					foreach ($item->getFilters() as $filter) {
						
						$filter
							->setCollection($item);
						
						$field = $filter->getField();
						$value = $filter->getValue();
						
						if ($field == 'tags') {
							$filter->setValue('%'. $value .'%');
							$operator = 'LIKE';
						}
						else
							$operator = '=';
									
						$filters[] = array(
							'field' => $field,
							'operator' => $operator,
							'value' => $value,
						);
						$where .= ' AND i.'. $field .' '. $operator .' :'. $field;
						
						$em->persist($filter);
					}
					
					// add products to ArchCollectionProducts
					$products = $em->getRepository('AppBundle:ArchProduct');
					
					$collectionItems = $products->createQueryBuilder('i')
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
							->setCollection($item)
							->setProduct($collectionItem)
						;
						
						$em->persist($collectionProduct);
					}
				}
					
					
				$item->setHandle($this->get('app.misc_functions')->slug($item->getName()));
				$em->persist($item);
					
				$em->flush();
					
				$this->addFlash(
					'info',
					"New collection '". $item->getName() ."' added."
				);
				
				return $this->redirectToRoute('admin_collection');
			}
		}
		
		return $this->render('admin/collections.html.twig', array(
				'items' => $items->findAll(),
				'form' => $form->createView(),
		));
	}
	
	/**
	 * @Route("/admin/collection-edit/{id}", name="admin_collection_edit")
	 */
	public function collectionEdit(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository('AppBundle:ArchProductCollection')->findOneBy(array('id'=>$id));
		
		if ($item == null) {
			$this->addFlash('danger', 'ERROR: Collection not found.');
			return $this->redirectToRoute('admin_collection');
		}
			
		$old_image = $item->getImage();
		
		// view list
		$data = $request->query->all();
		if (isset($data['viewlist'])) {
			$filters = array();
			foreach ($item->getFilters() as $filter) {
				$allowed_fields = array('name', 'tags', 'product_type', 'brand_name', 'supplier_name', 'is_active', 'vend_active', 'pre_sell');
				if (in_array($field = $filter->getField(), $allowed_fields)) {
					
					// operator
					if ($field == 'tags' || $field == 'name')
						$operator = 'LIKE';
					else
						$operator = '=';
					
						
					// value
					$value = $filter->getValue();
					
					// label
					$label = $this->get('app.misc_functions')->getLabel($field, $value);
					
					$filters[$label] = array(
							'label' => $label,
							'field' => $field,
							'operator' => $operator,
							'value' => $value,
					);
				}
			}
			
			$this->get('session')->set('filters_product', $filters);
			
			return $this->redirectToRoute('admin_product');
		}
		
		$form = $this->createForm(ArchProductCollectionType::class, $item);
		$form->handleRequest($request);
		
		// edit
		if ($form->isSubmitted() && $form->isValid()) {
			
			// image
			if ($item->getImage()!= null) {
				$filename = $this->get('app.image_functions')->upload('banner_thumb', 'collections', $item->getName(), $item->getImage(), $old_image);
				$item->setImage($filename);
			}
			else 
				$item->setImage($old_image);
			
			$em->flush();
			
			$this->addFlash(
				'info',
				'Collection info updated.'
			);
		}
		
		$items = array();
		foreach ($item->getFilters() as $filter) {
			switch($filter->getField()) {
				case 'name':
					$field = 'Name has';
					break;
				case 'product_type':
					$field = 'Product Type';
					break;
				case 'brand_name':
					$field = 'Brand Name';
					break;
				case 'supplier_name':
					$field = 'Supplier';
					break;
				case 'tags':
					$field = 'Tagged with';
					break;
				case 'is_active':
					$field = 'Site Visibility';
					break;
				case 'vend_active':
					$field = 'Vend Status';
					break;
			}
			$items[] = array(
					'field' => $field,
					'value' => $filter->getValue()
			);
		}
		
		if ($item == null)
			return new Response('ERROR: Collection does not exist.');
			
		return $this->render('admin/collections-edit.html.twig', array(
				'form' => $form->createView(),
				'items' => $items,
				'item' => $item
		));
	}
}
?>