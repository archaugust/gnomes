<?php 
namespace CmsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use CmsBundle\Form\ArchPageRentalType;
use CmsBundle\Form\ArchPageRentalPriceType;

class ArchPageRentalController extends Controller
{
	/**
	 * @Route("/admin/rental", name="admin_rental") 
	 */
	public function rental(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository('CmsBundle:ArchPageRental')->find(1);
		
		$originalPrices = new ArrayCollection();
		$originalSections = new ArrayCollection();
		$originalSectionDetails = array();
		
		$old_images = array(
				'banner' => $item->getBanner(),
				'pdf' => $item->getPdf()
		);
		
		foreach ($item->getPrices() as $price) {
			$originalPrices->add($price);
		}
		
		foreach ($item->getSections() as $section) {
			$originalSections->add($section);
			$originalSectionDetails[$section->getId()] = array(
					'name' => $section->getName(),
					'standard' => $section->getStandardImage(),
					'performance' => $section->getPerformanceImage(),
					'demo' => $section->getDemoImage(),
					'touring' => $section->getTouringImage(),
			);
		}
			
		$form = $this->createForm(ArchPageRentalType::class, $item);
		
		// update
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			
			$miscFunctions = $this->get('app.misc_functions');
			$imageFunctions = $this->get('app.image_functions');
			$fileUploader = $this->get('app.file_uploader');

			// images
			if ($item->getBanner() != null) {
				$banner = $imageFunctions->upload('banner', 'rental', 'ski-board-rental', $item->getBanner());
			}
			else {
				$banner = $old_images['banner'];
			}
			
			// pdf
			if ($item->getPdf() != null) {
				$pdf = $fileUploader->upload($item->getPdf(), 'pdf', 'rental-price-schedule');
			}
			else {
				$pdf = $old_images['pdf'];
			}
			
			$item
				->setBanner($banner)
				->setPdf($pdf)
			;
			
			// sections
			$sections = $item->getSections();
			
			if (count($sections) > 0) {
				foreach ($sections as $section) {
					$standard_image = null; 
					$performance_image = null;
					$touring_image = null;
					$demo_image = null;
					if (!is_null($section->getStandardImage())) {
						// process image
						$standard_image = $imageFunctions->upload('rental', 'rental', $miscFunctions->slug($section->getName()) .'-standard', $section->getStandardImage());
					}
					else {
						// get original image since no image is sent with a file input type
						foreach ($originalSections as $originalSection) {
							if ($section->getId() == $originalSection->getId()) 
								$standard_image = $originalSectionDetails[$section->getId()]['standard'];
						}
					}

					if (!is_null($section->getPerformanceImage())) {
						// process image
						$performance_image = $imageFunctions->upload('rental', 'rental', $miscFunctions->slug($section->getName()) .'-performance', $section->getPerformanceImage());
					}
					else {
						// get original image since no image is sent with a file input type
						foreach ($originalSections as $originalSection) {
							if ($section->getId() == $originalSection->getId())
								$performance_image = $originalSectionDetails[$section->getId()]['performance'];
						}
					}

					if (!is_null($section->getDemoImage())) {
						// process image
						$demo_image = $imageFunctions->upload('rental', 'rental', $miscFunctions->slug($section->getName()) .'-demo', $section->getDemoImage());
					}
					else {
						// get original image since no image is sent with a file input type
						foreach ($originalSections as $originalSection) {
							if ($section->getId() == $originalSection->getId())
								$demo_image = $originalSectionDetails[$section->getId()]['demo'];
						}
					}
					
					if (!is_null($section->getTouringImage())) {
						// process image
						$touring_image = $imageFunctions->upload('rental', 'rental', $miscFunctions->slug($section->getName()) .'-touring', $section->getTouringImage());
					}
					else {
						// get original image since no image is sent with a file input type
						foreach ($originalSections as $originalSection) {
							if ($section->getId() == $originalSection->getId())
								$touring_image = $originalSectionDetails[$section->getId()]['touring'];
						}
					}
					
					$section
						->setStandardImage($standard_image)
						->setPerformanceImage($performance_image)
						->setDemoImage($demo_image)
						->setTouringImage($touring_image)
						->setPage($item);
					
					$em->persist($section);
				}
			}

			// remove sections
			foreach ($originalSections as $section) {
				if (false === $item->getSections()->contains($section)) {
					// delete image
					$imageFunctions->deleteImage($originalSectionDetails[$section->getId()]['standard'], 'rental');
					$imageFunctions->deleteImage($originalSectionDetails[$section->getId()]['performance'], 'rental');
					$imageFunctions->deleteImage($originalSectionDetails[$section->getId()]['demo'], 'rental');
					$imageFunctions->deleteImage($originalSectionDetails[$section->getId()]['touring'], 'rental');
					
					$item->getSections()->removeElement($section);
					$em->remove($section);
				}
			}
			
			// prices
			$prices = $item->getPrices();
			
			if (count($prices) > 0) {
				foreach ($prices as $price) {
					$price->setPage($item);
					
					$em->persist($price);
				}
			}
			
			// remove prices
			foreach ($originalPrices as $price) {
				if (false === $item->getPrices()->contains($price)) {
					$item->getPrices()->removeElement($price);
					$em->remove($price);
				}
			}
			
			$em->flush();
			$this->addFlash('info', 'Custom Boot Fitting page updated.');
		}
				
			
			
		return $this->render('cms/rental.html.twig', array(
				'item' => $item,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * @Route("/admin/rental-pricing/{id}", name="admin_rental_pricing")
	 */
	public function rentalPricingVariants(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository('CmsBundle:ArchPageRentalPrice')->find($id);
		
		$originalVariants = new ArrayCollection();
		
		foreach ($item->getVariants() as $variant) {
			$originalVariants->add($variant);
		}
		
		$form = $this->createForm(ArchPageRentalPriceType::class, $item);
		
		// update
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {

			$variants = $item->getVariants();
			
			if (count($variants) > 0) {
				foreach ($variants as $variant) {
					$variant->setPrice($item);
					
					$em->persist($variant);
				}
			}
			
			foreach ($originalVariants as $variant) {
				if (false === $item->getVariants()->contains($variant)) {
					$item->getVariants()->removeElement($variant);
					$em->remove($variant);
				}
			}
			
			$em->flush();
			
			$this->addFlash('info', 'Pricing group updated.');
		}
		
		return $this->render('cms/rental_pricing.html.twig', array(
				'item' => $item,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * @Route("/admin/rental-bookings", name="admin_rental_bookings")
	 */
	public function rentalBookings(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('CmsBundle:ArchRentalBooking');
		
		// post = add or delete
		if ($request->isMethod('POST')) {
			
			// list actions
			$data = $request->request->all();
			if (!empty($data['items'])) {
				$list_action = $data['list_action'];
				
				if ($list_action == 'delete') {
					foreach ($data['items'] as $item) {
						$item = $items->findOneBy(array('id' => $item));
						
						foreach ($item->getGuests() as $guest) 
							$em->remove($guest);
						
						$em->remove($item);
					}
				}
				$em->flush();
			}
		}
		
		return $this->render('cms/rental_bookings.html.twig', array(
				'items' => $items->findBy([],array('id' => 'DESC'))
		));
	}
	
	/**
	 * @Route("/admin/rental-booking/{id}", name="admin_rental_booking")
	 */
	public function rentalBooking($id) {
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository('CmsBundle:ArchRentalBooking')->findOneBy(array('id' => $id));
		
		return $this->render('cms/rental_booking.html.twig', array(
				'item' => $item,
		));
	}
}
?>