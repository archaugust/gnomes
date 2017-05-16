<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\ArchSkiFinderRequest;

class SkiFinderController extends Controller
{
	/**
	 * @Route("/ski-finder", name="ski_finder")
	 */
	public function skiFinder()
	{
		$em = $this->getDoctrine()->getManager();
		
		$site_key = $em->getRepository('AppBundle:ArchConfig')->findOneBy(array('name' => 'google_recaptcha_key'))->getValue();
		
		return $this->render('default/ski_finder.html.twig', array(
				'site_key' => $site_key
		));
	}


	/**
	 * @Route("/ski-finder-request", name="ski_finder_request")
	 */
	public function skiFinderRequest(Request $request) 
	{
		$em = $this->getDoctrine()->getManager();
		$secret = $em->getRepository('AppBundle:ArchConfig')->findOneBy(array('name' => 'google_recaptcha_secret'))->getValue();
		
		if ($request->isMethod('POST')) {
			$data = $request->request->all();
			
			if(isset($data['g-recaptcha-response']) && !empty($data['g-recaptcha-response']) && !empty($data['name'] && !empty($data['email']))) {
				$response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$data['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
				
				if ($response['success'] != false) {
					// save
					$ski_finder_request = new ArchSkiFinderRequest();
					
					$criteria_4 = isset($data['criteria_4']) ? implode(',', $data['criteria_4']) : null;
					$gender = isset($data['gender']) ? $data['gender'] : null;
					
					$ski_finder_request
						->setDateReceived(time())
						->setName($data['name'])
						->setEmail($data['email'])
						->setHeight(@$data['height'])
						->setWeight(@$data['weight'])
						->setAge(@$data['age'])
						->setGender($gender)
						->setTechnicalAbility(@$data['criteria_1'])
						->setExperience(@$data['criteria_2'])
						->setApproach(@$data['criteria_3'])
						->setFavouriteHangouts($criteria_4)
						->setFavouriteFlavour(@$data['criteria_5'])
						->setSpecialist(isset($data['criteria_6']) ? implode(',', $data['criteria_6']) : null)
						->setFatOrSkinny(@$data['criteria_7'])
						->setRetiredFlotilla(@$data['criteria_8'])
						->setLikesDislikes(@$data['criteria_9'])
						->setExpectations(@$data['criteria_10'])
					;
					
					$em->persist($ski_finder_request);
					$em->flush();
					
					// email
					
					// query
					$skis = $em->getRepository('AppBundle:ArchSkiFinder');
					
					$filters = $where = array();
					for ($i = 1; $i <= 7; $i++) {
						if (!empty($data['criteria_'. $i])) {
							switch ($i) {
								case 1:
									$field = 'technical_ability';
									$value = $data['criteria_'. $i];
									break;
								case 2:
									$field = 'experience';
									$value = $data['criteria_'. $i];
									break;
								case 3:
									$field = 'approach';
									$value = $data['criteria_'. $i];
									break;
								case 4:
									$field = 'favourite_hangouts';
									$value = $criteria_4;
									break;
								case 5:
									$field = 'favourite_flavour';
									$value = $data['criteria_'. $i];
									break;
								case 6:
									$field = 'specialist';
									$value = $data['criteria_6'][0];
									break;
								case 7:
									$field = 'fat_or_skinny';
									$value = $data['criteria_'. $i];
									break;
							}
							
							$filters[] = array(
									'field' => $field,
									'value' => $value,
							);
							
							$where[] = "i.". $field ." LIKE :". $field;
						}
					}
					
					$where = implode(' AND ', $where);
					if (empty($where))
						$where = '1 = 1';
					
					$skis = $skis->createQueryBuilder('i')
						->where($where)
					;
					
					foreach ($filters as $filter)
						$skis->setParameter($filter['field'], "%". $filter['value'] ."%");
							
					$skis = $skis
						->getQuery()
						->getResult();

					$items = $brands = array();
					
					$session = $this->get('session');
					$wishlist = ($session->get('wishlist') != null) ? $session->get('wishlist') : array();
						
					foreach ($skis as $ski) {
						$product = $ski->getProduct();
						
						$item_tags = explode(',', $product->getTags());
						$item_tags = array_map('trim', $item_tags);
						
						// gender filter
						if ($gender != null && !in_array($gender, $item_tags)) 
							continue;
						
						$collection_product = $em->getRepository('AppBundle:ArchProductCollectionProduct')->findOneBy(array('product' => $product));
						
						if (!$product->getIsActive() || $product->getDeletedAt() != null)
							continue;
							
						$brand = $product->getBrandName();
							
						if (!in_array($brand, $brands))
							$brands[] = $brand;
							
						if (count($image = $product->getImages()) > 0)
							$image = '/images/products/thumb/'. $image[0]->getFilename();
						else
							$image = $product->getImage();

						// get discount type
						$discount_type = null;
						if ($product->getDiscountPrice()) {
							$discounter = $em->getRepository('AppBundle:ArchProductDiscounterProduct')->findOneBy(array('product' => $product));
							if ($discounter)
								$discount_type = $discounter->getDiscounter()->getDiscountType();
						}
							
						$handle = $product->getHandle();
						array_key_exists($handle, $wishlist) ?	$in_wishlist = true : $in_wishlist = false;
						
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
						
					return $this->render('default/ski_finder-results.html.twig', array(
							'items' => $items,
							'brands' => $brands
					));
				}
			}
		}
		
		return new Response('error');
	}
}