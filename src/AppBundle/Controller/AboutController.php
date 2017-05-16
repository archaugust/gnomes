<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AboutController extends Controller
{
	/**
	 * @Route("/pages/about-us", name="about")
	 */
	public function about()
	{
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository('CmsBundle:ArchPageAbout')->find(1);
		
		$item->setHits($item->getHits()+1);
		$em->flush();
		
		return $this->render('default/about.html.twig', array(
				'item' => $item
		));
	}
}