<?php 
namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\ArchProductCollection;
use AppBundle\Entity\ArchProduct;
use AppBundle\Form\ArchProductCollectionType;
use AppBundle\Entity\ArchProductCollectionProduct;
use AppBundle\Form\ArchProductCollectionNewType;
use AppBundle\Form\ArchProductCollectionGuideType;
use AppBundle\Entity\ArchProductCollectionGuide;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\ArchSkiFinder;
use AppBundle\Form\ArchSkiFinderType;

class ArchSkiFinderController extends Controller
{
	
	/**
	 * @Route("/admin/ski-finder", name="admin_ski_finder")
	 */
	public function skiFinder(Request $request) {

		$em = $this->getDoctrine()->getManager();
		$products = $em->getRepository('AppBundle:ArchProduct')->findBy(array('product_type' => 'Skis', 'variant_parent_id' => null), array('name' => 'ASC'));
		
		$collection_items = new ArrayCollection();
		$items = array();
		
		// add only products in a collection
		$collection_products = $em->getRepository('AppBundle:ArchProductCollectionProduct');
		foreach ($products as $product) {
			$collection_item = $collection_products->findOneBy(array('product' => $product));
			if ($collection_item) 
				$collection_items->add($collection_item);
		}
		
		// check for ski finder data
		$ski_finder = $em->getRepository('AppBundle:ArchSkiFinder');
		foreach ($collection_items as $collection_item) {
			$product = $collection_item->getProduct();
			
			$check_data = $ski_finder->findOneBy(array('product' => $product));
			$has_data = $check_data ? true : false;
			
			$items[] = array(
					'id' => $product->getId(),
					'name' => $product->getBaseName(),
					'is_active' => $product->getIsActive(),
					'has_data' => $has_data
			);
		}
		
		return $this->render('admin/ski_finder.html.twig', array(
				'items' => $items,
		));
	}
	
	/**
	 * @Route("/admin/ski-finder-edit/{id}", name="admin_ski_finder_edit")
	 */
	public function skiFinderEdit(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository('AppBundle:ArchProductCollectionProduct')->findOneBy(array('product_id'=>$id));
		
		if ($item == null) {
			$this->addFlash('danger', 'ERROR: Product does not exist or is not part of a collection.');
			return $this->redirectToRoute('admin_ski_finder');
		}
		
		$item = $item->getProduct();
		
		$ski_finder = $em->getRepository('AppBundle:ArchSkiFinder')->findOneBy(array('id' => $id));
		if ($ski_finder == null)
			$ski_finder = new ArchSkiFinder();
		
		$form = $this->createForm(ArchSkiFinderType::class, $ski_finder);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			
			$ski_finder
				->setId($item->getId())
				->setProduct($item)
			;
			$em->persist($ski_finder);
			$em->flush();
			
			$this->addFlash('info', "Ski finder tags for '". $item->getBaseName() ."' updated.");
		}
				
		return $this->render('admin/ski_finder-edit.html.twig', array(
				'form' => $form->createView(),
				'item' => $item
		));
	}

	/**
	 * @Route("/admin/ski-finder-requests", name="admin_ski_finder_requests")
	 */
	public function skiFinderRequests(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchSkiFinderRequest');
		
		// post = add or delete
		if ($request->isMethod('POST')) {
			
			// list actions
			$data = $request->request->all();
			if (!empty($data['items'])) {
				$list_action = $data['list_action'];
				
				if ($list_action == 'delete') {
					foreach ($data['items'] as $item) {
						$item = $items->findOneBy(array('id' => $item));
						
						$em->remove($item);
					}
				}
				$em->flush();
			}
		}
		
		return $this->render('admin/ski_finder-requests.html.twig', array(
				'items' => $items->findBy([],array('id' => 'DESC'))
		));
	}
	
	/**
	 * @Route("/admin/ski-finder-request/{id}", name="admin_ski_finder_request")
	 */
	public function skiFinderRequest($id) {
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository('AppBundle:ArchSkiFinderRequest')->findOneBy(array('id' => $id));
		
		$criteria = array();
		
		$criteria[1] = '';
		switch ($item->getTechnicalAbility()) {
			case 1:
				$criteria[1] = "Beginner: I won’t be uploading to social just yet";
				break;
			case 2:
				$criteria[1] = "Intermediate:  I look tidy on moderate trails but steeper gradients get the better of me";
				break;
			case 3:
				$criteria[1] = "Advanced:  I ski well in most conditions but tricky snow catches me out";
				break;
			case 4:
				$criteria[1] = "Expert: I should be/was a ski instructor. Technically correct in all terrain, in all snow conditions";
				break;
		}
		
		$criteria[2] = '';
		switch ($item->getExperience()) {
			case 1:
				$criteria[2] = "Limited:  I'm really just starting out";
				break;
			case 2:
				$criteria[2] = "So-so:  I have skied at least one NZ season";
				break;
			case 3:
				$criteria[2] = "Oodles:  I have a few season passes to prove it";
				break;
			case 4:
				$criteria[2] = "Colossal:  These grey hairs can tell stories of the best pow days";
				break;
		}
		
		$criteria[3] = '';
		switch ($item->getApproach()) {
			case 1:
				$criteria[3] = "Cautious:  I like to stay in one piece";
				break;
			case 2:
				$criteria[3] = "Dabbler:  When I’m feeling it, I’ll open it up";
				break;
			case 3:
				$criteria[3] = "Lion Cub:   I usually ski hard but it's nice to chill at times";
				break;
			case 4:
				$criteria[3] = "Lion King:  I'm adrenaline loaded and constantly crank high speed turns";
				break;
		}
		
		$criteria[4] = array();
		$hangouts = explode(',', $item->getFavouriteHangouts());
		foreach ($hangouts as $hangout) {
			switch ($hangout){
				case 1:
					$criteria[4][] = 'North Island Resorts';
					break;
				case 2:
					$criteria[4][] = 'South Island Resorts';
					break;
				case 3:
					$criteria[4][] = 'The Clubbies';
					break;
				case 4:
					$criteria[4][] = 'Nth America';
					break;
				case 5:
					$criteria[4][] = 'Japan';
					break;
			}
		}
		
		$criteria[5] = '';
		switch ($item->getFavouriteFlavour()) {
			case 1:
				$criteria[5] = "Groomers: I love to hang out on prepared trails";
				break;
			case 2:
				$criteria[5] = "50/50: I'll go off-piste if conditions are good but I'm just as happy on groomers";
				break;
			case 3:
				$criteria[5] = "Freeride:  I only ski on groomers for a warmup or if the snow is shockingly bad";
				break;
			case 4:
				$criteria[5] = "Powder: These bad boys are for Japan, North America or those epic days in NZ";
				break;
			case 5:
				$criteria[5] = "Backcountry: I'm looking for something lighter that I can mount with touring bindings";
				break;
		}
		
		$criteria[6] = '';
		switch($item->getSpecialist()) {
			case 1:
				$criteria[6] = "Yes, this will be my only pair";
				break;
			case 2:
				$criteria[6] = "No, I’m adding these to my quiver";
				break;
		}
		
		$criteria[7] = "";
		switch($item->getFatOrSkinny()) {
			case 1:
				$criteria[7] = "I’ll leave it to your expert advice";
				break;
			case 2:
				$criteria[7] = "70-80mm";
				break;
			case 3:
				$criteria[7] = "81-90mm";
				break;
			case 4:
				$criteria[7] = "91-100mm";
				break;
			case 5:
				$criteria[7] = "101-110mm";
				break;
			case 6:
				$criteria[7] = "111+";
				break;
		}

		return $this->render('admin/ski_finder-request.html.twig', array(
				'item' => $item,
				'criteria' => $criteria
		));
	}
}
?>