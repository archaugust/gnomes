<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BrandsController extends Controller
{
	/**
	 * @Route("/pages/brands", name="brands")
	 */
	public function brands()
	{
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository('CmsBundle:ArchPageBrand')->find(1);
		$items = $em->getRepository('AppBundle:ArchProductBrand')->findBy(array('is_active' => 1), array('name' => 'asc')); 
		
		return $this->render('default/brand_main.html.twig', array(
				'item' => $item,
				'items' => $items,
		));
	}
}