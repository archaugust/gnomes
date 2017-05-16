<?php
namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\ShopifyProduct;
use AppBundle\Entity\ArchProductImage;
use Doctrine\Common\Collections\ArrayCollection;

class ShopifyController extends Controller
{
	/**
	 * @Route("/admin/shopify/get-products", name="shopify_get_products")
	 */
	public function getProducts()
	{
		$em = $this->getDoctrine()->getManager();
		$config = $em->getRepository('AppBundle:ArchConfig');
		$api_key = $config->findOneBy(array('name' => 'shopify_api_key'))->getValue();
		$api_password = $config->findOneBy(array('name' => 'shopify_api_password'))->getValue();
		$url = $config->findOneBy(array('name' => 'shopify_url'))->getValue();
		
		// get count
		@$result = file_get_contents('https://'. $api_key .':'. $api_password .'@'. $url .'/admin/products/count.json');
		$result = json_decode($result);
		
		$count = $result->count;
		
		sleep(2);
		
		// get products
		$ctr = 0;
		for ($page = 0; $page < ($count/50); $page++) {
			$options = array(
					'http' => array(
							'method'  => 'GET',
					)
			);
			
			$context  = stream_context_create($options);
			@$result = file_get_contents('https://'. $api_key .':'. $api_password .'@'. $url .'/admin/products.json?page='. ($page+1), false, $context);
			$result = json_decode($result);
			
			foreach ($result->products as $product) {
				$item = new ShopifyProduct();
				$item
					->setId($product->id)
					->setName($product->title)
					->setHandle($product->handle)
				;
				
				$em->persist($item);
				$ctr ++;
			}
			$em->flush();
		}
			
		return new Response($ctr .' products loaded.');
	}
	
	/**
	 * @Route("/admin/shopify/get-product-images/{id}", name="shopify_get_product_images")
	 */
	public function getProductImages(Request $request, $id = '') {
		$em = $this->getDoctrine()->getManager();
		$config = $em->getRepository('AppBundle:ArchConfig');
		$api_key = $config->findOneBy(array('name' => 'shopify_api_key'))->getValue();
		$api_password = $config->findOneBy(array('name' => 'shopify_api_password'))->getValue();
		$url = $config->findOneBy(array('name' => 'shopify_url'))->getValue();
		
		$products = $em->getRepository('AppBundle:ShopifyProduct')->findAll();
		
		$data = $request->query->all();
		$page = isset($data['page']) ? $data['page'] : 0;
		
		$itemsPerPage = 20;
		$items = array_slice($products, ($page) * $itemsPerPage, $itemsPerPage);
		
		$pages = count($products)/$itemsPerPage;
		
		$ctr = 0;
		$imageFunctions = $this->get('app.image_functions');
		$product_images = $em->getRepository('AppBundle:ArchProductImage');
		
		set_time_limit(90);
		$repo = $em->getRepository('AppBundle:ArchProduct');
		$null = 0;
		$redo = 0;
		$change = 0;
		$processed = new ArrayCollection();
		foreach ($items as $item) {
			$product = $repo->findOneBy(array('handle' => $item->getHandle(), 'variant_parent_id' => null));
			if ($product == null) 
				$product = $repo->findOneBy(array('name' => $item->getName(), 'variant_parent_id' => null));
			
			if ($product != null) {
				$processed->add($product);
				// get images
				@$result = file_get_contents('https://'. $api_key .':'. $api_password .'@'. $url .'/admin/products/'. $item->getId() .'/images.json');
				$result = json_decode($result);
				
				$img_ctr = 1;
				foreach($result->images as $image) {
					$type = exif_imagetype($image->src);
					switch($type) {
						case 1:
							$ext = 'gif';
							break;
						case 2:
							$ext = 'jpg';
							break;
						case 3:
							$ext = 'png';
							break;
					}
					
					$filename = $item->getHandle() .'-'. $img_ctr .'.'. $ext;

					$product_image = $product_images->findOneBy(array('filename' => $filename));  
					if ($product_image == null) {
						$product_image = new ArchProductImage();
						$imageFunctions->createImageFromURL($image->src, $filename, 'products', true);
						$redo ++;
					}
					
					if ($product_image->getProduct() != $product)
						$change++;
					
					$product_image
						->setProduct($product)
						->setFilename($filename)
					;
					
					$img_ctr++;
						
					$em->persist($product_image);
				}
				
				$em->flush();
				$ctr++;
			}
			else {
				$null++;
			}
		}
		
		return $this->render('admin/shopify_get_images.html.twig', array(
				'page' => $page,
				'offset' => ($page + 1) * $itemsPerPage, 
				'pages' => $pages,
				'change' => $change,
				'redo' => $redo,
				'null' => $null,
				'processed' => $processed
		));
	}
}