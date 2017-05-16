<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\Common\Collections\ArrayCollection;

class CollectionController extends Controller
{
	/**
	 * @Route("/collections/{handle}", name="collection")
	 */
	public function collection($handle)
	{
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository('AppBundle:ArchProductCollection')->findOneBy(array('handle' => $handle, 'is_active' => 1));
		
		if ($item == null) {
			// try brands
			$item = $em->getRepository('AppBundle:ArchProductBrand')->findOneBy(array('handle' => $handle, 'is_active' => 1));
			
			if ($item == null) {
				$this->addFlash('danger', 'Product collection does not exist or has been unpublished.');
				
				return $this->redirectToRoute('homepage');
			}
			else {
				$mode = 'brand';
				$products = $em->getRepository('AppBundle:ArchProduct')->findBy(array('brand_name' => $item->getName(), 'variant_parent_id' => null));
				$repository = $em->getRepository('AppBundle:ArchProductCollectionProduct');
				
				$collection_products = new ArrayCollection();
				foreach ($products as $product) {
					$collection_product = $repository->findOneBy(array('product' => $product));
					if ($collection_product)
						$collection_products->add($collection_product);
				}
			}
		}
		else {
			$mode = 'collection';
			$collection_products = $item->getProducts();
		}
		
		$item->setHits($item->getHits() + 1);
		$em->flush();
		
		$items = $brands = array();

		$session = $this->get('session');
		$wishlist = ($session->get('wishlist') != null) ? $session->get('wishlist') : array();

		foreach ($collection_products as $collection_product) {
			$product = $collection_product->getProduct();
			
			if (!$product->getIsActive() || $product->getDeletedAt() != null)
				continue;
			
			$brand = $product->getBrandName();
			
			if (!in_array($brand, $brands))
				$brands[] = $brand;
			
			if (count($image = $product->getImages()) > 0) 
				$image = '/images/products/thumb/'. $image[0]->getFilename();
			else 
				$image = $product->getImage();
			
			$handle = $product->getHandle();
			array_key_exists($handle, $wishlist) ?	$in_wishlist = true : $in_wishlist = false;
			
			// get discount type
			$discount_type = null;
			if ($product->getDiscountPrice()) {
				$discounter = $em->getRepository('AppBundle:ArchProductDiscounterProduct')->findOneBy(array('product' => $product));
				if ($discounter)
					$discount_type = $discounter->getDiscounter()->getDiscountType();
			}
			
				
			$items[] = array(
					'name' => $product->getBaseName(),
					'handle' => $handle,
					'new' => $collection_product->getNew(),
					'price' => $product->getPrice(),
					'discount' => $product->getDiscountPrice(),
					'discount_type' => $discount_type,
					'image' => $image,
					'tags' => $product->getTags(),
					'brand' => $brand,
					'updated_at' => $product->getUpdatedAt(),
					'in_wishlist' => $in_wishlist
			);
		}

		// sort array by element
		usort($items, function($a, $b) { 
			return $a['name'] > $b['name'];
		});
		
		asort($brands);
		
		return $this->render('default/'. $mode .'.html.twig', array(
				'item' => $item,
				'items' => $items,
				'brands' => $brands
		));
	}
}