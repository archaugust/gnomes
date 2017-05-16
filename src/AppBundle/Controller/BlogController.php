<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller
{
	/**
	 * @Route("/blogs/news", name="blog")
	 */
	public function blog()
	{
		$em = $this->getDoctrine();
		$blog = $em->getRepository('CmsBundle:ArchBlogMain')->find(1);
		$items = $em->getRepository('CmsBundle:ArchBlog')->findBy(array('is_active' => 1), array('display_date' => 'DESC'));

		// mobile only css
		if (preg_match("/\b(?:a(?:ndroid|vantgo)|b(?:lackberry|olt|o?ost)|cricket|do‌​como|hiptop|i(?:emob‌​ile|p[ao]d)|kitkat|m‌​(?:ini|obi)|palm|(?:‌​i|smart|windows )phone|symbian|up\.(?:browser|link)|tablet(?: browser| pc)|(?:hp-|rim |sony )tablet|w(?:ebos|indows ce|os))/i", $_SERVER["HTTP_USER_AGENT"]) == true)
			$mobile = true;
		else
			$mobile = false;
				
		return $this->render('default/blog.html.twig', array(
				'blog' => $blog,
				'items' => $items,
				'mobile' => $mobile
		));
	}

	/**
	 * @Route("/blogs/news/{handle}", name="blog_page")
	 */
	public function blogPage($handle)
	{
		$em = $this->getDoctrine()->getManager();
		$repository = $em->getRepository('CmsBundle:ArchBlog');
		$item = $repository->findOneBy(array('handle' => $handle, 'is_active' => 1));
		
		// add hit
		$item->setHits($item->getHits()+1);
		$em->flush();
		
		$breadcrumbs = array(
				array(
						'url' => $this->generateUrl('blog'),
						'text' => 'Blog'
				),
				array(
						'url' => null,
						'text' => $item->getName()
				)
		);
		
		$next = $repository->createQueryBuilder('i')
			->where('i.display_date > :date')
			->orderBy('i.display_date', 'DESC')
			->setParameter('date', $item->getDisplayDate())
			->setMaxResults(1)
			->getQuery()
			->getResult()
		;

		if ($next) 
			$next = $next[0]; 
		else 
			$next = $repository->findOneBy(array(), array('display_date' => 'ASC'));  
		
		return $this->render('default/blog_page.html.twig', array(
				'breadcrumbs' => $breadcrumbs,
				'item' => $item,
				'next' => $next->getHandle()
		));
	}
}