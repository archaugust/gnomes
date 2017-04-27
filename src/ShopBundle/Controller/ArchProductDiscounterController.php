<?php 
namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\ArchProductCollection;
use AppBundle\Entity\ArchProduct;
use AppBundle\Form\ArchProductCollectionType;
use AppBundle\Entity\ArchProductDiscounter;
use AppBundle\Form\ArchProductDiscounterType;
use AppBundle\Entity\ArchProductDiscounterProduct;

class ArchProductDiscounterController extends Controller
{
	
	/**
	 * @Route("/admin/discounter", name="admin_discounter")
	 */
	public function discounter(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchProductDiscounter');
		
		$item = new ArchProductDiscounter();
		$form = $this->createForm(ArchProductDiscounterType::class, $item);
		
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
						
						foreach($item->getProducts() as $product) {
							// reset discount 
							$archProduct = $product->getProduct();
							$archProduct->setDiscountPrice(null);
							
							$em->remove($product);
						}
						
						$em->remove($item);
					}
				}
				else {
					if ($list_action == 'enable') {
						foreach ($data['items'] as $item) {
							$item = $items->findOneBy(array('id' => $item));
							$item->setIsActive(1);
							
							$rate = $item->getRate();
							$suffix = $item->getSuffix();
							
							// apply discount
							foreach ($item->getProducts() as $product) {
								$archProduct = $product->getProduct();
								
								$price = $archProduct->getPrice();
								$discounted_price = floor($price * (1 - ($rate/100))) + ($suffix/100);
								$archProduct->setDiscountPrice($discounted_price);
							}
						}
					}
					else {
						foreach ($data['items'] as $item) {
							$item = $items->findOneBy(array('id' => $item));
							$item->setIsActive(0);
							
							// remove discount
							foreach ($item->getProducts() as $product) {
								$archProduct = $product->getProduct();
								$archProduct->setDiscountPrice(null);
							}
						}
					}
				}
					
				$this->addFlash('info', 'Discounter(s) '. $list_action .'d.');
				
				$em->flush();
			}
			
			// add
			$form->handleRequest($request);
	
			if ($form->isSubmitted() && $form->isValid()) {
				
				// filters
				$filters = array();
				$where = '';
				foreach ($item->getFilters() as $filter) {
					
					$filter
						->setDiscounter($item);
					
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
				
				// add products to ArchDiscounterProducts
				if ($where != '') {
					$products = $em->getRepository('AppBundle:ArchProduct');
					$discounterItems = $products->createQueryBuilder('i')
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
							->setDiscounter($item)
							->setProduct($discounterItem)
						;
						
						$em->persist($discounterProduct);
					}
				}
				
				$em->persist($item);
				$em->flush();
				
				$this->addFlash(
						'info',
						"New discounter '". $item->getName() ."' added. Enable the discounter to apply the discount rate."
				);
				
				return $this->redirectToRoute('admin_discounter');
			}
		}
		
		return $this->render('admin/discounter.html.twig', array(
				'items' => $items->findAll(),
				'form' => $form->createView(),
		));
	}
	
	/**
	 * @Route("/admin/discounter-edit/{id}", name="admin_discounter_edit")
	 */
	public function discounterEdit(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository('AppBundle:ArchProductDiscounter')->findOneBy(array('id'=>$id));
		
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
		
		
		$old_rate = $item->getRate();
		$old_suffix = $item->getSuffix();
		
		$form = $this->createForm(ArchProductDiscounterType::class, $item);
		$form->handleRequest($request);
		
		// edit
		if ($form->isSubmitted() && $form->isValid()) {

			// redo discounts if rate changed
			if ($old_rate != $item->getRate() || $old_suffix != $item->getSuffix()) {
				$rate = $item->getRate();
				$suffix = $item->getSuffix();
				
				foreach ($item->getProducts() as $product) {
					$archProduct = $product->getProduct();
					
					$price = $archProduct->getPrice();
					$discounted_price = floor($price * (1 - ($rate/100))) + ($suffix/100);
					$archProduct->setDiscountPrice($discounted_price);
				}
				
			}
			
			$em->flush();
			
			$this->addFlash(
					'info',
					'Discounter updated.'
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
			return new Response('ERROR: Discount group does not exist.');
			
		return $this->render('admin/discounter-edit.html.twig', array(
				'form' => $form->createView(),
				'items' => $items,
				'item' => $item
		));
	}
}
?>