<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HelperController extends Controller
{
	/**
	 * @Route("subscribe-process", name="subscribe")
	 */
	public function subscribeProcess(Request $request) 
	{
		return new Response('Nice one! You’re all set.');
	}
	
	/**
	 * @Route("get-product-price/{id}", name="get_product_price")
	 */
	public function getProductPrice($id) {
		$em = $this->getDoctrine();
		$products = $em->getRepository('AppBundle:ArchProduct');
		$product = $products->findOneBy(array('id' => $id));
		$session = $this->get('session');
		$items = $session->get('cart');
		$country = $session->get('country');
		
		$price = $product->getPrice();
		
		$skis = 0;
		foreach ($items as $key => $item) {
			$product = $products->findOneBy(array('id' => $key));
			
			$product_type = $product->getProductType();
			if ($product_type == 'Skis')
				$skis += $item['qty'];
		}
		
		if ($country == 'AU' && $skis > 0)
			$price = $price * $skis;
		return new Response($price);
	}
	
	/**
	 * @Route("reload-shipping", name="reload_shipping")
	 */
	public function reloadShipping() {
		$em = $this->getDoctrine();
		$user = $this->getUser();
		$products = $em->getRepository('AppBundle:ArchProduct');
		$session = $this->get('session');
		$items = $session->get('cart');
		$country = $session->get('country');
		
		$skis = 0;
		$bindings = 0;
		$boots = 0;
		$total = 0;
		foreach ($items as $key => $item) {
			$product = $products->findOneBy(array('id' => $key));
			
			$product_type = $product->getProductType();
			switch ($product_type) {
				case 'Skis':
					$skis += $item['qty'];
					break;
				case 'Ski Bindings':
					$bindings += $item['qty'];
					break;
				case 'Ski Boots':
					$boots += $item['qty'];
					break;
			}
			
			if ($user && $product->getDiscountPrice() > 0)
				$price = $product->getDiscountPrice();
			else
				$price = $product->getPrice();
				
			$subtotal = $price * $item['qty'];
			$total += $subtotal;
		}
		
		$options = array();
		// collect
		$options[] = array(
					'value' => '0af7b240-ab2d-11e7-eddc-38f361702dd1',
					'text' => 'Collect from store',
					'price' => '0'
		);
		// NZ under 100
		if ($total < 100 && $country == 'NZ') {
			$options[] = array(
					'value' => 'fff531fe-9500-11e2-b1f5-4040782fde00',
					'text' => 'Shipping for orders under $100',
					'price' => '6'
			);
		}
		// NZ free over 100
		if ($total >= 100 && $country == 'NZ') {
			$options[] = array(
					'value' => '0af7b240-ab2d-11e7-eddc-38f387222ec1',
					'text' => 'Free Shipping within NZ',
					'price' => '0'
			);
		}
		// NZ overnight 5kg
		if ($skis == 0 && $country == 'NZ') {
			$options[] = array(
					'value' => '0af7b240-ab2d-11e7-eddc-38ef991dd2ca',
					'text' => 'Overnight or Saturday delivery (small items only)',
					'price' => '30'
			);
		}
		// AU ski bindings 150-1500
		if ($skis == 0 && $bindings > 0 && $country == 'AU' && $total >= 150 && $total <= 1500) {
			$options[] = array(
					'value' => '0af7b240-ab2d-11e7-eddc-38f3b846bd8c',
					'text' => 'Ski Bindings delivery to Australia',
					'price' => '35'
			);
		}
		// AU ski boots 300-2000
		if ($skis == 0 && $boots > 0 && $country == 'AU' && $total >= 300 && $total <= 2000) {
			$options[] = array(
					'value' => '0af7b240-ab2d-11e7-eddc-38f4108ad6cf',
					'text' => 'Ski Boots delivery to Australia',
					'price' => '50'
			);
		}
		// AU Skis & Bindings $700-$4000
		if ($skis > 0 && $bindings > 0 && $country == 'AU' && $total >= 700 && $total <= 4000) {
			$options[] = array(
					'value' => '0af7b240-ab2d-11e7-eddc-38f460d91251',
					'text' => 'Skis & Bindings delivery to Australia',
					'price' => '150'
			);
		}
		// AU Skis Only (per pair)  $125.00
		if ($skis > 0 && $bindings == 0 && $boots == 0 && $country == 'AU' && $total >= 600 && $total <= 4000) {
			$options[] = array(
					'value' => '0af7b240-ab2d-11e7-eddc-38f43eb09748',
					'text' => 'Skis only delivery to Australia (per pair)',
					'price' => ($skis * 125)
			);
		}
		// AU Small Items (under 2kg)  $25.00
		if ($skis == 0 && $bindings == 0 && $boots == 0 && $country == 'AU' && $total <= 1000) {
			$options[] = array(
					'value' => '0af7b240-ab2d-11e7-eddc-38f043f1a2eb',
					'text' => 'Small items delivery to Australia',
					'price' => '25'
			);
		}
		
		return $this->render('default/ajax_shipping_options.html.twig', array(
				'options' => $options
		));
	}
	
	/**
	 * @Route("delete-duplicate", name="delete_duplicate")
	 */
	public function deleteDuplicate()
	{
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('AppBundle:ArchProductImage');
		$images = $repo->findBy([], array('id' => 'ASC'));
		$ctr_dup = array();
		
		foreach ($images as $image) {
			$same = $repo->findBy(array('filename' => $image->getFilename()), array('id' => 'ASC'));
			
			if (count($same) > 1) {
				$ctr = 0;
				foreach ($same as $duplicate) {
					if ($ctr > 0) {
						$ctr_dup[] = $duplicate->getFilename();
						$em->remove($duplicate);
					}
					$ctr++;
				}
				$em->flush();
			}
		}
		dump($ctr_dup);die;
	}
	
	/**
	 * @Route("no-match")
	 */
	public function noMatch() {
		$em = $this->getDoctrine()->getManager();
		$products = $em->getRepository('AppBundle:ShopifyProduct')->findAll();
		
		$nomatch = array();
		$ctr = 0;
		foreach ($products as $item) {
			$product = $em->getRepository('AppBundle:ArchProduct')->findOneBy(array('handle' => $item->getHandle(), 'variant_parent_id' => null));
			if ($product == null) {
				$product = $em->getRepository('AppBundle:ArchProduct')->findOneBy(array('base_name' => $item->getName(), 'variant_parent_id' => null));
				if ($product == null) {
					$nomatch[] = $item->getName() .' - '. $item->getHandle();
					$ctr ++;
				}
			}
		}
		echo $ctr;
		dump ($nomatch);die;
	}
}