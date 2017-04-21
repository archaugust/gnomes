<?php 
namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
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
	
	public function getLinkAction($entity, $name) {
		$item = $this->getDoctrine()->getRepository('AppBundle:Arch'. $entity)->findOneBy(array('name' => $name));
		
		switch ($entity) {
			case 'ProductType':
				$route = 'admin_product_type_edit';
				break;
			case 'ProductBrand':
				$route = 'admin_brand_edit';
				break;
		}
		
		return new Response($item == null ? $link = '#' : $this->generateUrl($route, array('id' => $item->getId())));
	}
}
?>