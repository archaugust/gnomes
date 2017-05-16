<?php
namespace RouteBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
	/**
	 * @Route("/pages/{handle}", name="page")
	 */
	public function page($handle)
	{
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository('CmsBundle:ArchPage')->findOneBy(array('handle' => $handle));
		
		$item->setHits($item->getHits() +1);
		$em->flush();
		
		return $this->render('default/page.html.twig', array(
				'item' => $item,
		));
	}
}