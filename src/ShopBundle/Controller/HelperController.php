<?php 
namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HelperController extends Controller
{
	/**
	 * @Route("admin/get-select", name="admin_get_select")
	 */
	public function getSelect(Request $request) {
		$data = $request->request->all();
		$field = $data['field'];
		$field_name = isset($data['name']) ? $data['name'] : '';
		
		$allowed_fields = array('product_type', 'brand_name', 'supplier_name', 'collection');
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
			case 'collection':
				$entity = 'ArchProductCollection';
				break;
		}
		
		$items = $this->getDoctrine()->getRepository('AppBundle:'. $entity)->findBy(array('is_active' => 1), array('name' => 'ASC'));
		
		return $this->render('admin/ajax_select_filters.html.twig', array(
				'items' => $items,
				'field_name' => $field_name
		));
	}

	/**
	 * @Route("/admin/get-checkboxes/{mode}", name="admin_get_checkboxes")
	 */
	public function getCheckboxes(Request $request, $mode) {
		switch ($mode) {
			case 'collection':
				$items = $this->getDoctrine()->getRepository('AppBundle:ArchProductCollection')->findBy(array(), array('name' => 'ASC'));
				$array = array(
						'items' => $items,
						'field_name' => 'collection'
				);
				break;
			case 'discounter':
				$items = $this->getDoctrine()->getRepository('AppBundle:ArchProductDiscounter');
				$array = array(
						'items_vip' => $items->findBy(array('discount_type' => 'VIP'), array('name' => 'ASC')),
						'items_discount' => $items->findBy(array('discount_type' => 'Discount'), array('name' => 'ASC')),
						'field_name' => 'discounter'
				);
				break;
		}
		
		return $this->render('admin/ajax_checkboxes.html.twig', $array);
	}

	/**
	 * @Route("/admin/collection-product-toggle-new", name="admin_collection_product_toggle_new")
	 */
	public function collectionProductToggleNew(Request $request) {
		$em = $this->getDoctrine()->getManager();
		
		$data = $request->request->all();
		
		$product = $em->getRepository('AppBundle:ArchProductCollectionProduct')->findOneBy(array('product_id' => $data['id']));
		
		if ($product == null)
			return new Response('ERROR: Collection product not found.');
			
		$msg = $product->getProduct()->getName() .' tagged as New.';

		$product->setNew(1);
		$em->flush();
		
		return new Response($msg);
	}
	
	/**
	 * @Route("/admin/collection-remove-product", name="admin_collection_remove_product")
	 */
	public function collectionRemoveProduct(Request $request) {
		$em = $this->getDoctrine()->getManager();
		
		$data = $request->request->all();
		
		$product = $em->getRepository('AppBundle:ArchProductCollectionProduct')->findOneBy(array('collection_id' => $data['collection'], 'product_id' => $data['product']));
		
		if ($product == null)
			return new Response('ERROR: Collection product not found.');
			
		$msg = $product->getProduct()->getName() .' removed from collection.';
		$em->remove($product);
		$em->flush();
		
		return new Response($msg);
	}

	
	/**
	 * @Route("/admin/discounter-remove-product", name="admin_discounter_remove_product")
	 */
	public function discounterRemoveProduct(Request $request) {
		$em = $this->getDoctrine()->getManager();
		
		$data = $request->request->all();
		
		$product = $em->getRepository('AppBundle:ArchProductDiscounterProduct')->findOneBy(array('discounter_id' => $data['discounter'], 'product_id' => $data['product']));
		
		if ($product == null)
			return new Response('ERROR: Discount group product not found.');
			
		$msg = $product->getProduct()->getName() .' removed from discount group.';
		$archProduct = $product->getProduct();
		
		// reset discountPrice
		$archProduct->setDiscountPrice(null);
		
		$variants = $em->getRepository('AppBundle:ArchProduct')->findBy(array('variant_parent_id' => $archProduct->getId()));
		foreach ($variants as $variant) {
			$variant->setDiscountPrice(null);
		}
		
		$em->remove($product);
		$em->flush();
			
		return new Response($msg);
	}

	
	/**
	 * @Route("/admin/product-change-pre-sell-text", name="admin_product_change_pre-sell-text")
	 */
	public function productChangePreSellText(Request $request) {
		$em = $this->getDoctrine()->getManager();
		
		$data = $request->request->all();
		
		$text_id = ($data['text_id'] != '') ? $data['text_id'] : null;
		
		$product = $em->getRepository('AppBundle:ArchProduct')->findOneBy(array('id' => $data['id']));
		$product->setPreSellTextId($text_id);
		
		$em->flush();
		
		return new Response("Product '". $product->getName() ."' pre-sell text template updated.");
	}
	
		
	/**
	 * @Route("/regions", name="regions")
	 */
	public function regions(Request $request) {
		$session = $this->get('session');
		
		$em = $this->getDoctrine();
		$data = $request->request->all();
		if (isset($data['country'])) {
			$id = $data['country'];
			$session->set('country', $data['country']);
		}
		else 
			$id = '';
		
		$field = isset($data['field']) ? $data['field'] : '';
		
		$country = $em->getRepository('AppBundle:ArchCountry')->findOneBy(array('country_id' => $id));
		
		if (count($country) > 0)
			$regions = $em->getRepository('AppBundle:ArchRegion')->findBy(array('country_id' => $country->getId()));
		else
			$regions = array();

		return $this->render('regions.html.twig', array(
				'items' => $regions,
				'field' => $field
		));
	}
}