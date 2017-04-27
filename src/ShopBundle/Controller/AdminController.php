<?php 
namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\ArchProduct;

class AdminController extends Controller
{
	/**
	 * @Route("/admin", name="admin")
	 */
	public function admin()
	{
		return $this->render('admin/index.html.twig', array(
				'header' => 'Dashboard',
		)
				);
	}

	/**
	 * @Route("/admin/home-page", name="admin_home_page")
	 */
	public function homePage(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$products = $em->getRepository('AppBundle:ArchProduct');
		
		return $this->render('admin/shipping.html.twig', array(
		));
	}
	
	/*
	/**
	 * @Route("admin/fix-brands", name="fix_brands")
	 */
	/*
	public function fixBrands() {
		$em = $this->getDoctrine()->getManager();
		$brands = $em->getRepository('AppBundle:ArchProductBrand')->findAll();
		$slug = $this->get('app.misc_functions');
		foreach ($brands as $brand) {
			$brand->setHandle($slug->slug($brand->getName()));
			
		}
		$em->flush();
		die;
	}
	*/
}
?>