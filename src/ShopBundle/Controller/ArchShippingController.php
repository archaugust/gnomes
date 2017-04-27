<?php 
namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ArchShippingController extends Controller
{
	/**
	 * @Route("/admin/shipping", name="admin_shipping")
	 */
	public function shipping(Request $request) {
		$em = $this->getDoctrine()->getManager();
		
		return $this->render('admin/shipping.html.twig', array(
		));
	}
}
?>