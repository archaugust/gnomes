<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
	/**
	 * @Route("cart/add", name="cart_add")
	 */
	public function cartAdd(Request $request)
	{
		$session = $this->get('session');
		$cart = $session->get('cart') ? $session->get('cart') : array();
		
		$data = $request->request->all();
		if (empty($data['product'])) {
			return new Response('Product variant is out of stock. Please check other variants.');
		}
		
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchProduct');
		$item = $items->findOneBy(array('id' => $data['product'], 'is_active' => 1));
		
		if ($item == null) {
			return new Response('Product variant is out of stock. Please check other variants.');
		}
		else {
			if ($item->getCount() < 1 && $item->getPreSell() == 0)
				return new Response('Product variant is out of stock. Please check other variants.');
		}
		
		// check if variant, use parent images
		if ($item->getVariantParentId() != null) {
			$parent = $items->findOneBy(array('id' => $item->getVariantParentId()));
			$images = $parent->getImages();
			$image = count($images) > 0 ? $images[0]->getFilename() : $parent->getImage();
		}
		else {
			$images = $item->getImages();
			$image = count($images) > 0 ? $images[0]->getFilename() : $item->getImage();
		}
		
		
		$variant = array();
		if ($item->getVariantOptionOneName()) {
			$variant[] = array(
					'name' => $item->getVariantOptionOneName(),
					'value' => $item->getVariantOptionOneValue()
			);
		}
		if ($item->getVariantOptionTwoName()) {
			$variant[] = array(
					'name' => $item->getVariantOptionTwoName(),
					'value' => $item->getVariantOptionTwoValue()
			);
		}
		if ($item->getVariantOptionThreeName()) {
			$variant[] = array(
					'name' => $item->getVariantOptionThreeName(),
					'value' => $item->getVariantOptionThreeValue()
			);
		}
		
		$discount_type = $em->getRepository('AppBundle:ArchProductDiscounterProduct')->findOneBy(array('product' => $item));
		$discount_type = $discount_type ? $discount_type->getDiscounter()->getDiscountType() : null;
		
		$in_cart = false;
		if (isset($cart[$item->getId()])) {
			$cart[$item->getId()]['qty'] = $cart[$item->getId()]['qty'] + 1;
			$in_cart = true;
		}
				
		if (!$in_cart) {

			$cart[$item->getId()] = array(
					'qty' => 1,
					'handle' =>  $item->getHandle(),
					'name' => $item->getBaseName(),
					'variant' => $variant,
					'image' => $image,
					'count' => $item->getCount(),
					'price' => $item->getPrice(),
					'discount_price' => $item->getDiscountPrice(),
					'discount_type' => $discount_type,
			);
			
		}
		
		$session->set('cart', $cart);
		
		return new Response ($item->getName() ." added to shopping cart.");

	}
	
	/**
	 * @Route("cart/update", name="cart_update")
	 */
	public function cartUpdate(Request $request) {
		$session = $this->get('session');
		$cart = $session->get('cart') ? $session->get('cart') : array();
		
		$data = $request->request->all();
		if (!empty($data['product'])) {
			// stock check
			$product = $this->getDoctrine()->getRepository('AppBundle:ArchProduct')->findOneBy(array('id' => $data['product'], 'is_active' => 1));
			if ($product->getCount() >= $data['qty'] || $product->getPreSell() == 1) {
				$cart[$data['product']]['qty'] = $data['qty'];
				$msg = 'Order quantity updated';
			}
			else  {
				$msg = 'Quantity limited to current stock available.';
			}
		}
		
		$session->set('cart', $cart);
		
		return new Response($msg);
	}
	
	/**
	 * @Route("cart/remove", name="cart_remove")
	 */
	public function cartRemove(Request $request) {
		$session = $this->get('session');
		$cart = $session->get('cart') ? $session->get('cart') : array();
		
		$data = $request->request->all();
		if (!empty($data['product'])) {
			unset($cart[$data['product']]);
		}
		
		$session->set('cart', $cart);
		
		return new Response('Item removed from shopping bag.');
	}

	/**
	 * @Route("cart/reload", name="cart_reload")
	 */
	public function cartReload(Request $request) {
		$session = $this->get('session');
		$items = $session->get('cart') ? $session->get('cart') : array();
		
		return $this->render('default/cart_reload.html.twig', array(
				'items' => $items
		));
	}
}