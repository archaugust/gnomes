<?php 
namespace CmsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use CmsBundle\Form\ArchPageBootType;

class ArchBootController extends Controller
{
	/**
	 * @Route("/admin/page-boot", name="admin_page_boot") 
	 */
	public function pageHome(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository('CmsBundle:ArchPageBoot')->find(1);
		
		$originalPrices = new ArrayCollection();
		$originalSections = new ArrayCollection();
		$originalSectionDetails = array();
		
		$old_images = array(
				'banner' => $item->getBanner(),
				'content_top_image' => $item->getContentTopImage(),
				'content_middle_image' => $item->getContentMiddleImage(),
		);
		

		foreach ($item->getPrices() as $price) {
			$originalSections->add($price);
		}
		
		foreach ($item->getSections() as $section) {
			$originalSections->add($section);
			$originalSectionDetails[$section->getId()] = array(
					'name' => $section->getName(),
					'filename' => $section->getFilename(),
			);
		}
			
		$form = $this->createForm(ArchPageBootType::class, $item);
		
		// update
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			
			$miscFunctions = $this->get('app.misc_functions');
			$imageFunctions = $this->get('app.image_functions');

			// images
			if ($item->getBanner() != null) {
				$banner = $imageFunctions->upload('banner', 'custom-boot-fitting', 'custom-boot-fitting', $item->getBanner());
			}
			else {
				$banner = $old_images['banner'];
			}
			
			if ($item->getContentTopImage() != null) {
				$content_top_image= $imageFunctions->upload('thumb', 'custom-boot-fitting', 'custom-boot-fitting-top', $item->getContentTopImage());
			}
			else {
				$content_top_image= $old_images['content_top_image'];
			}
				
			if ($item->getContentMiddleImage() != null) {
				$content_middle_image= $imageFunctions->upload('thumb', 'custom-boot-fitting', 'custom-boot-fitting-middle', $item->getContentMiddleImage());
			}
			else {
				$content_middle_image= $old_images['content_middle_image'];
			}
			
			$item
				->setBanner($banner)
				->setContentTopImage($content_top_image)
				->setContentMiddleImage($content_middle_image)
			;
			
			// sections
			$sections = $item->getSections();
			
			if (count($sections) > 0) {
				foreach ($sections as $section) {
					$filename = null;
					if (!is_null($section->getFilename())) {
						// process image
						$filename = $imageFunctions->upload('thumb', 'custom-boot-fitting', $miscFunctions->slug($section->getName()), $section->getFilename());
						
						if ($section->getPage() != null) {
							// delete old image if different text
							if ($section->getName() != $originalSectionDetails[$section->getId()]['name'])
								$imageFunctions->deleteImage(@$originalSectionDetails[$section->getId()]['filename'], 'custom-boot-fitting/thumb');
						}
					}
					else {
						// get original image since no image is sent with a file input type
						foreach ($originalSections as $originalSection) {
							if ($section->getId() == $originalSection->getId()) 
								$filename = $originalSectionDetails[$section->getId()]['filename'];
						}
					}
					
					$section
						->setFilename($filename)
						->setPage($item);
					
					$em->persist($section);
				}
			}

			// remove sections
			foreach ($originalSections as $section) {
				if (false === $item->getSections()->contains($section)) {
					// delete image
					$imageFunctions->deleteImage(@$originalSectionDetails[$section->getId()]['filename'], 'home-page/sections');
					
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
				
			
			
		return $this->render('cms/boot.html.twig', array(
				'item' => $item,
				'form' => $form->createView(),
		));
	}
}
?>