<?php 
namespace ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ActionController extends Controller
{
	
	public function filtersAction($mode, $id) {
		switch ($mode) {
			case 'collection':
				$entity = 'ArchProductCollection';
				break;
			case 'discounter':
				$entity = 'ArchProductDiscounter';
				break;
		}
		$item = $this->getDoctrine()->getRepository('AppBundle:'. $entity)->find($id);
		
		if ($item == null)
			return new Response('-');

		$filter_text = '';
		foreach ($item->getFilters() as $filter) {
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
				
			$filter_text .= $field . $value .'. ';
		}
		
		return new Response($filter_text);
	}

	public function getInventoryAction($id) {
		$em = $this->getDoctrine();
		$item = $em->getRepository('AppBundle:ArchProduct')->findOneBy(array('id' => $id));
		
		if ($item == null)
			return new Response('-');
			
		$variants = $item->getVariants();
		$variant_msg = (count($variants) > 0) ? count($variants) .' variants' : '1 variant';
		
		$stock = $item->getCount();
		foreach ($variants as $variant)
			$stock += $variant->getCount();
			
		return new Response($stock .' in '. $variant_msg);
	}
	
	public function getCountryNameAction($code) {
		$country = $this->getDoctrine()->getRepository('AppBundle:ArchCountry')->findOneBy(array('country_id' => $code));
		if ($country != null)
			$country = $country->getName();
		else
			$country = '';
				
		return new Response($country);
	}
	
	public function getSexAction($code) {
		return new Response($code == 'F' ? 'Female' : 'Male');
	}
	
}