<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;

class ProductController extends Controller
{
	/**
	 * @Route("/products/{handle}", name="product")
	 */
	public function product($handle)
	{
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchProduct');
		$item = $items->findOneBy(array('handle' => $handle, 'variant_parent_id' => null, 'deleted_at' => null));
		
		if ($item == null) 
			return new Response('Product does not exist or has been unpublished.');
		
		$item->setViews($item->getViews()+1);
		$em->flush();

		$collection_product = $em->getRepository('AppBundle:ArchProductCollectionProduct')->findOneBy(array('product_id' => $item->getId()));
		if ($collection_product == null) {
			$this->addFlash('danger', 'Product is currently unavailable.');
			
			return $this->redirectToRoute('homepage');
		}
		else
			$item_new = $collection_product->getNew();
		
		$collection = $collection_product->getCollection();
		
		
		// discount group type
		$discount_type = null;
		if ($item->getDiscountPrice()) {
			$discounter = $em->getRepository('AppBundle:ArchProductDiscounterProduct')->findOneBy(array('product' => $item));
			if ($discounter)
				$discount_type = $discounter->getDiscounter()->getDiscountType();
		}

		$variants = $items->findBy(array('variant_parent_id' => $item->getId(), 'is_active' => 1, 'deleted_at' => null));
		
		$session = $this->get('session');
		$wishlist = ($session->get('wishlist') != null) ? $session->get('wishlist') : array();
		
		$tags = explode(', ', $item->getTags());
		$where = array();
		if (count($tags) > 0) {
			foreach ($tags as $tag)
				$where[] = "i.tags LIKE '%". $tag ."%'";
			
			$where = ' AND ('. implode(' OR ', $where) .')';

			$related_tags= $items->createQueryBuilder('i')
				->where('i.variant_parent_id IS NULL AND i.deleted_at is NULL'. $where)
				->getQuery()
				->getResult()
			;
			shuffle($related_tags);
			
			// get only products in collections
			$ctr = 0;
			$related_items = new ArrayCollection();
			$collectionProducts = $em->getRepository('AppBundle:ArchProductCollectionProduct');
			foreach ($related_tags as $related_tag) {
				if ($ctr < 4) {
					$exists = $collectionProducts->findOneBy(array('product_id' => $related_tag->getId()));
					if ($exists) {
						$related_items->add($exists);
						$ctr++;
					}
				}
				else 
					break;
			}
		}
		else 
			$related_items = null;
		
		$related_tags = array();

		foreach ($related_items as $related_item) {
			$product = $related_item->getProduct();
			
			if (count($image = $product->getImages()) > 0)
				$image = '/images/products/thumb/'. $image[0]->getFilename();
			else
				$image = $product->getImage();
				
			$handle = $product->getHandle();
			array_key_exists($handle, $wishlist) ? $in_wishlist = true : $in_wishlist = false;
			
			$related_tags[] = array(
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
			array_key_exists($handle, $wishlist) ?	$in_wishlist = true : $in_wishlist = false;
			
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
		
		return $this->render('default/product.html.twig', array(
				'item' => $item,
				'item_new' => $item_new,
				'variants' => $variants,
				'collection' => $collection,
				'discount_type' => $discount_type,
				'related_tags' => $related_tags,
				'related_collection' => $related_collection,
		));
	}
}