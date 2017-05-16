<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WishlistController extends Controller
{
	/**
	 * @Route("/wishlist-toggle", name="wishlist_toggle")
	 */
	public function wishlistToggle(Request $request)
	{
		$data = $request->request->all();
		$handle = isset($data['handle']) ? $data['handle'] : '';
		$mode = isset($data['mode']) ? $data['mode'] : '';
		
		$em = $this->getDoctrine();
		$items = $em->getRepository('AppBundle:ArchProduct');
		$item = $items->findOneBy(array('handle' => $handle));
		
		if ($item == null) 
			return new Response('Product does not exist or has been unpublished');
		
		$session = $this->get('session');
		$wishlist = $session->get('wishlist');

		if ($wishlist == null) 
			$wishlist = array();
		
		if ($mode == 'add') {
		
			$images = $item->getImages();
			$image = count($images) > 0 ? $images[0]->getFilename() : $item->getImage();
			
			$discount_type = $em->getRepository('AppBundle:ArchProductDiscounterProduct')->findOneBy(array('product' => $item));
			$discount_type = $discount_type ? $discount_type->getDiscounter()->getDiscountType() : null;
			
			$in_wishlist = false;
			if (isset($wishlist[$item->getHandle()])) {
				$in_wishlist = true;
			}
			
			if (!$in_wishlist) {
				
				$wishlist[$item->getHandle()] = array(
						'handle' =>  $item->getHandle(),
						'name' => $item->getBaseName(),
						'image' => $image,
						'price' => $item->getPrice(),
						'discount_price' => $item->getDiscountPrice(),
						'discount_type' => $discount_type
				);
				
			}
			
			$msg = 'Product added to Wishlist';
		}
		else {
			if (isset($wishlist[$item->getHandle()]))
				unset($wishlist[$item->getHandle()]);
				
			$msg = 'Product removed from Wishlist';
		}
		
		$session->set('wishlist', $wishlist);

		return new Response($msg);
	}

	
	/**
	 * @Route("wishlist/reload", name="wishlist_reload")
	 */
	public function wishlistReload(Request $request) {
		$session = $this->get('session');
		$items = $session->get('wishlist') ? $session->get('wishlist') : array();
		
		return $this->render('default/wishlist_reload.html.twig', array(
				'items' => $items
		));
	}
}