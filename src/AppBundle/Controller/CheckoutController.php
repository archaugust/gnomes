<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\ArchOrder;
use AppBundle\Entity\ArchOrderProduct;
use AppBundle\Entity\ArchOrderShipping;

class CheckoutController extends Controller
{
	/**
	 * @Route("checkout", name="checkout")
	 */
	public function checkout()
	{
		$em = $this->getDoctrine()->getManager();
		$email = $em->getRepository('AppBundle:ArchConfig')->findOneBy(array('name' => 'gen_sender_email'))->getValue();
		$user = $this->getUser();
		
		$session = $this->get('session');
		$items = $session->get('cart') ? $session->get('cart') : array();
		
		$shipping = 6;
		$total = 0;
		foreach ($items as $key => $item) {
			if ($user && $item['discount_price'] > 0)
				$price = $item['discount_price'];
			else
				$price = $item['price'];
				
			$subtotal = $price * $item['qty'];
			$total += $subtotal;
			
			$continue = $key;
		}
		
		if ($total > 99)
			$shipping = 0;

		$continue = $em->getRepository('AppBundle:ArchProduct')->findOneBy(array('id' => $continue))->getBrandName();
		if ($continue == null) 
			$continue = 'skis';
			
		return $this->render('default/checkout_review.html.twig', array(
				'items' => $items,
				'email' => $email,
				'shipping' => $shipping,
				'continue' => $continue
		));
	}

	/**
	 * @Route("checkout/shipping", name="checkout_shipping")
	 */
	public function checkoutShipping(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$email = $em->getRepository('AppBundle:ArchConfig')->findOneBy(array('name' => 'gen_sender_email'))->getValue();
		
		$session = $this->get('session');
		$session_country = $session->get('country');
		if (!$session_country)
			$session->set('country', 'NZ');
		
		$items = $session->get('cart') ? $session->get('cart') : array();
		$user = $this->getUser();
		
		$countries = $em->getRepository('AppBundle:ArchCountry')->findAll();
		$country_items = array();
		foreach ($countries as $country) {
			$country_items[$country->getName()] = $country->getCountryId();
		}
		
		if ($user)
			$defaultData = array(
					'first_name' => $user->getFirstName(),
					'last_name' => $user->getLastName(),
					'email' => $user->getEmail(),
					'phone' => $user->getPhone(),
					'address' => $user->getPostalAddress1(),
					'city' => $user->getPostalCity(),
					'country' => $user->getPostalCountryId() ? $user->getPostalCountryId() : 'NZ'
			);
		else
			$defaultData = array(
					'country' => 'NZ'
			);
		
		$form = $this->createFormBuilder($defaultData)
			->add('first_name', TextType::class, array('label' => 'First Name *'))
			->add('last_name', TextType::class, array('label' => 'Last Name *'))
			->add('email', EmailType::class, array('label' => 'Email *'))
			->add('phone', TextType::class, array('label' => 'Phone *'))
			->add('address', TextType::class, array('label' => 'Address *'))
			->add('city', TextType::class, array('label' => 'City *'))
			->add('region', TextType::class, array(
					'label' => 'Region *',
			))
			->add('country', ChoiceType::class, array(
					'label' => 'Country *',
					'choices' => $country_items,
			))
			->add('postcode', TextType::class, array('label' => 'Postcode *'))
			->add('send', SubmitType::class, array('label' => 'PROCEED TO PAYMENT'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			// data is an array with "name", "email", and "message" keys
			$data = $form->getData();
			
			// temporary order id 
			$order_id = uniqid('ID'); 
			
			$extra = $request->request->all();
			$shipping_method = isset($extra['shipping']) ? $extra['shipping'] : null;
			
			// get shipping method data
			$products = $em->getRepository('AppBundle:ArchProduct');
			$shipping_method = $products->findOneBy(array('id' => $shipping_method));
			
			if ($shipping_method == null) {
				$this->addFlash('danger', "Please select a valid Shipping method.");
				return $this->redirectToRoute('checkout_shipping');
			}
			
			// add shipping method to cart
			$items = $session->get('cart');
			$items[$shipping_method->getId()] = array(
					'qty' => 1,
					'handle' =>  $shipping_method->getHandle(),
					'name' => $shipping_method->getBaseName(),
					'variant' => array(),
					'image' => $shipping_method->getImage(),
					'count' => 1,
					'price' => $shipping_method->getPrice(),
					'discount_price' => 0,
					'discount_type' => null,
			);
			$session->set('cart', $items);
			
			$customer = array(
				'order_id' => $order_id,
				'first_name' => $data['first_name'],
				'last_name' => $data['last_name'],
				'email' => $data['email'],
				'phone' => $data['phone'],
				'address' => substr($data['address'], 0, 255),
				'city' => $data['city'],
				'region' => $data['region'],
				'country' => $data['country'],
				'postcode' => $data['postcode'],
				'shipping_method' => $shipping_method->getName()
			);
			$session->set('customer', $customer);
			
			// save order for updating by PxPay postback later
			$tax = $em->getRepository('AppBundle:ArchTax')->findOneBy(array('is_default' => 1));
			$tax_id = $tax->getId();
			$tax_rate = $tax->getRate();
			
			$order = new ArchOrder();
			
			$total_retail_price = 0; 
			$total_tax = 0;
			foreach ($items as $key => $item) {
				$product = $products->findOneBy(array('id' => $key));
				
				if ($user && $product->getDiscountPrice() > 0) {
					$item_price = $product->getDiscountPrice();
					$item_retail_price = $item_price - ($item_price * $tax_rate);
					$item_retail_price_total = $item_retail_price * $item['qty'];
					$item_tax = $item_price * $tax_rate;
					$item_tax_total = $item_tax * $item['qty'];
					$item_discount = $product->getPrice() - $item_price;
				}
				else {
					$item_price = $product->getPrice();
					$item_retail_price = $product->getRetailPrice();
					$item_retail_price_total = $item_retail_price * $item['qty'];
					$item_tax = $product->getTax();
					$item_tax_total = $item_tax * $item['qty'];
					$item_discount = 0;
				}
				
				$subtotal = $item_price * $item['qty'];
				$total_retail_price += $item_retail_price_total;
				$total_tax += $item_tax_total;
				
				// order product
				$order_product = new ArchOrderProduct();
				$order_product
					->setId(uniqid('ID').time())
					->setOrder($order)
					->setProduct($product)
					->setName($product->getName())
					->setQuantity($item['qty'])
					->setPrice($item_retail_price)
					->setPriceTotal($item_retail_price_total)
					->setTaxId($tax_id)
					->setTax($item_tax)
					->setTaxTotal($item_tax_total)
					->setDiscount($item_discount)
				;
				
				$em->persist($order_product);
			}
			
			// order shipping
			$order_shipping = new ArchOrderShipping();
			$order_shipping
				->setFirstName($customer['first_name'])
				->setLastName($customer['last_name'])
				->setPhone($customer['phone'])
				->setEmail($customer['email'])
				->setAddress($customer['address'])
				->setCity($customer['city'])
				->setRegion($customer['region'])
				->setCountry($customer['country'])
				->setPostcode($customer['postcode'])
				->setShippingMethod($customer['shipping_method'])
			;
			
			$em->persist($order_shipping);
			
			if ($user) {
				$order->setCustomer($user);
			}
			
			$order
				->setId($order_id)
				->setShipping($order_shipping)
				->setTotalPrice($total_retail_price)
				->setTotalTax($total_tax)
				->setTaxName($tax->getName())
				->setStatus('Pending Payment')
			;
			
			$em->persist($order);
			
			$em->flush();
			
			return $this->redirectToRoute('checkout_payment');
		}
		
		$shipping = 6;
		$total = 0;
		foreach ($items as $item) {
			if ($user && $item['discount_price'] > 0)
				$price = $item['discount_price'];
			else
				$price = $item['price'];
			
			$subtotal = $price * $item['qty'];
			$total += $subtotal;
		}
		
		if ($total > 99)
			$shipping = 0;
		
		return $this->render('default/checkout_shipping.html.twig', array(
				'user' => $user,
				'form' => $form->createView(),
				'email' => $email,
				'items' => $items,
				'total' => $total,
				'shipping' => $shipping
		));
	}
}