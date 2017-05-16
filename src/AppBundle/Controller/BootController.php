<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BootController extends Controller
{
	/**
	 * @Route("/pages/boot-fitting", name="boot")
	 */
	public function page()
	{
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository('CmsBundle:ArchPageBoot')->find(1);
		
		$item->setHits($item->getHits() +1);
		$em->flush();
		
		$session = $this->get('session');
		$wishlist = ($session->get('wishlist') != null) ? $session->get('wishlist') : array();
		
		$collection = $em->getRepository('AppBundle:ArchProductCollection')->findOneBy(array('name' => 'Ski Boots'));

		$related_collection = $collection->getProducts()->toArray();
		shuffle($related_collection);
		
		$related_items = array_slice($related_collection, 0, 4);
		$related_collection = array();
		
		foreach ($related_items as $related_item) {
			$product = $related_item->getProduct();
			
			if (count($image = $product->getImages()) > 0)
				$image = '/images/products/thumb/'. $image[0]->getFilename();
			else
				$image = $product->getImage();
				
			$handle = $product->getHandle();
			in_array($handle, $wishlist) ?	$in_wishlist = true : $in_wishlist = false;
			
			$related_collection[] = array(
					'name' => $product->getBaseName(),
					'handle' => $handle,
					'new' => $related_item->getNew(),
					'price' => $product->getPrice(),
					'discount' => $product->getDiscountPrice(),
					'image' => $image,
					'updated_at' => $product->getUpdatedAt(),
					'in_wishlist' => $in_wishlist
			);
		}
		
		return $this->render('default/boot.html.twig', array(
				'item' => $item,
				'related_collection' => $related_collection,
		));
	}
}