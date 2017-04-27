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
				$entity = 'ArchProductCollection';
				$field_name = 'collection';
				break;
			case 'discounter':
				$entity = 'ArchProductDiscounter';
				$field_name = 'discounter';
				break;
		}
		$items = $this->getDoctrine()->getRepository('AppBundle:'. $entity)->findBy(array(), array('name' => 'ASC'));
		
		return $this->render('admin/ajax_checkboxes.html.twig', array(
				'items' => $items,
				'field_name' => $field_name
		));
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
		
		$product = $em->getRepository('AppBundle:ArchProductDiscounterProduct')->findOneBy(array('discounter_id' => $data['collection'], 'product_id' => $data['product']));
		
		if ($product == null)
			return new Response('ERROR: Discount group product not found.');
			
		$msg = $product->getProduct()->getName() .' removed from discount group.';
		$em->remove($product);
		$em->flush();
			
		return new Response($msg);
	}

	/**
	 * @Route("/regions", name="regions")
	 */
	public function regions(Request $request) {
		$em = $this->getDoctrine();
		$data = $request->request->all();
		$id = isset($data['country']) ? $data['country'] : '';
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