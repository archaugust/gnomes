<?php 
namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\ArchProductCollection;
use AppBundle\Entity\ArchProduct;
use AppBundle\Form\ArchProductCollectionType;
use AppBundle\Entity\ArchProductCollectionProduct;
use AppBundle\Form\ArchProductCollectionNewType;
use AppBundle\Form\ArchProductCollectionGuideType;
use AppBundle\Entity\ArchProductCollectionGuide;
use Doctrine\Common\Collections\ArrayCollection;

class ArchProductCollectionController extends Controller
{
	
	/**
	 * @Route("/admin/collection", name="admin_collection")
	 */
	public function collection(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$collections = $em->getRepository('AppBundle:ArchProductCollection');

		$item = new ArchProductCollection();
		$form = $this->createForm(ArchProductCollectionNewType::class, $item);
		
		if ($request->isMethod('POST')) {
			// delete
			$data = $request->request->all();
			if (isset($data['items'])) {
				$list_action = $data['list_action'];
				if ($list_action == 'delete') {
					foreach ($data['items'] as $item) {
						$item = $collections->findOneBy(array('id' => $item));
						
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
						$item = $collections->findOneBy(array('id' => $item));
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
							$value = '%'. $value .'%';
							$filter->setValue($value);
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
							->setCollection($item)
							->setProduct($collectionItem)
						;
						
						$em->persist($collectionProduct);
					}
				}
					
				$miscFunctions = $this->get('app.misc_functions');
				$handle = $miscFunctions->slug($item->getName());
				while ($collections->findBy(array('handle' => $handle))) {
					$handle = $handle .'-';
				}
					
				$item->setHandle($handle);
				$em->persist($item);
					
				$em->flush();
					
				$this->addFlash(
					'info',
					"New collection '". $item->getName() ."' added."
				);
				
				return $this->redirectToRoute('admin_collection');
			}
		}
		
		$items = array();
		foreach ($collections->findAll() as $collection) {
			$filters = '';
			foreach ($collection->getFilters() as $filter) {
				switch($filter->getField()) {
					case 'name':
						$field = 'Name has ';
						break;
					case 'product_type':
						$field = 'Product type is ';
						break;
					case 'brand_name':
						$field = 'Brand name is ';
						break;
					case 'supplier_name':
						$field = 'Supplier is ';
						break;
					case 'tags':
						$field = 'Tagged with ';
						break;
					case 'is_active':
						$field = 'Site visibility is ';
						break;
					case 'vend_active':
						$field = 'Vend status is ';
						break;
				}
				
				$value = ($filter->getField() == 'tags') ? "'". substr($filter->getValue(),1,-1) ."'" : $filter->getValue();
				
				$filters .= $field . $value .'. ';
			}
			
			$items[] = array(
					'id' => $collection->getId(),
					'name' => $collection->getName(),
					'products' => count($collection->getProducts()),
					'filters' => $filters,
					'image' => $collection->getImage(),
					'isActive' => $collection->getIsActive()
			);
		}
		
		
		return $this->render('admin/collections.html.twig', array(
				'items' => $items,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * @Route("/admin/collection-edit/{id}", name="admin_collection_edit")
	 */
	public function collectionEdit(Request $request, $id) {
		$this->get('session')->set('filters_search', null);
		
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository('AppBundle:ArchProductCollection')->findOneBy(array('id'=>$id));
		
		if ($item == null) {
			$this->addFlash('danger', 'ERROR: Collection not found.');
			return $this->redirectToRoute('admin_collection');
		}

		$old_image = $item->getImage();
		$old_handle = $item->getHandle();
		
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
		
		// collection form
		$form = $this->createForm(ArchProductCollectionType::class, $item);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			// check for duplicate handles
			$handles = $em->getRepository('AppBundle:ArchProductCollection')->findBy(array('handle' => $item->getHandle()));
			if (count($handles) > 0 && $item->getHandle() != $old_handle) {
				$this->addFlash('danger', 'Handle already used by another collection.');
				return $this->redirectToRoute('admin_collection_edit', array('id' => $item->getId()));
			}
			
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

		// buying guide form
		$guide = $item->getGuides();
		if (count($guide) == 0)
			$guide = new ArchProductCollectionGuide();
		else 
			$guide = $guide[0];
		
		$originalSteps = new ArrayCollection();
		$originalStepImages = array();
		foreach ($guide->getSteps() as $step) {
			$originalSteps->add($step);
			$originalStepImages[$step->getId()] = $step->getImage();
		}
		
		$form_guide = $this->createForm(ArchProductCollectionGuideType::class, $guide);
		$form_guide->handleRequest($request);
		
		if ($form_guide->isSubmitted() && $form_guide->isValid()) {
			foreach ($guide->getSteps() as $step) {
				
				// image
				if ($step->getImage()!= null) {
					$filename = $this->get('app.misc_functions')->slug($step->getName()) .'-'. $step->getId();
					if (isset($originalStepImages[$step->getId()]))
						$filename = $this->get('app.image_functions')->upload('thumb_square', 'collections-guides', $filename, $step->getImage(), $originalStepImages[$step->getId()]);
					else
						$filename = $this->get('app.image_functions')->upload('thumb_square', 'collections-guides', $filename, $step->getImage());
						
					$step->setImage($filename);
				}
				else {
					if (isset($originalStepImages[$step->getId()]))
						$step->setImage($originalStepImages[$step->getId()]);
				}
				
				$step->setGuide($guide);
				$em->persist($step);
			}
			
			$guide->setCollection($item);
			$em->persist($guide);

			// remove collection
			foreach ($originalSteps as $step) {
				if (false === $guide->getSteps()->contains($step)) {
					$guide->getSteps()->removeElement($step);
					$em->remove($step);
				}
			}
			
			$em->flush();
			
			$this->addFlash('info', 'Buying Guide updated.');
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
			
		return $this->render('admin/collections-edit.html.twig', array(
				'form' => $form->createView(),
				'form_guide' => $form_guide->createView(),
				'items' => $items,
				'item' => $item
		));
	}
}
?>