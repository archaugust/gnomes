<?php 
namespace CmsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use CmsBundle\Form\ArchPageAboutType;

class ArchPageAboutController extends Controller
{
	/**
	 * @Route("/admin/about", name="admin_page_about") 
	 */
	public function pageAbout(Request $request, $mode = '') {
		
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository('CmsBundle:ArchPageAbout')->find(1);
		
		$old_banner = $item->getBanner();
		$old_thumbnail = $item->getThumbnail();

		$originalSections = new ArrayCollection();
		$originalSectionDetails = array();
		
		foreach ($item->getSections() as $section) {
			$originalSections->add($section);
			$originalSectionDetails[$section->getId()] = array(
					'name' => $section->getName(),
					'filename' => $section->getFilename(),
			);
		}

		
		$form = $this->createForm(ArchPageAboutType::class, $item);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			$miscFunctions = $this->get('app.misc_functions');
			$imageFunctions = $this->get('app.image_functions');
			
			// images
			if ($item->getBanner() != null) {
				$banner = $imageFunctions->upload('banner', 'about', 'about-us-banner', $item->getBanner());
			}
			else
				$banner = $old_banner;

			if ($item->getThumbnail() != null) {
				$thumbnail = $imageFunctions->upload('thumb', 'about', 'about-us', $item->getThumbnail());
			}
			else
				$thumbnail = $old_thumbnail;
					
			$item
				->setBanner($banner)
				->setThumbnail($thumbnail)
			;
			
			// sections
			$sections = $item->getSections();
			
			if (count($sections) > 0) {
				foreach ($sections as $section) {
					if (!is_null($section->getFilename())) {
						// process image
						$filename = $imageFunctions->upload('thumb', 'about', $miscFunctions->slug($section->getName()), $section->getFilename());
						
						if ($section->getPage() != null) {
							// delete old image if different text
							if ($section->getName() != $originalSectionDetails[$section->getId()]['name'])
								$imageFunctions->deleteImage($originalSectionDetails[$section->getId()], 'about/thumb');
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
					$imageFunctions->deleteImage($originalSectionDetails[$section->getId()]['filename'], 'about/thumb');
					
					$item->getSections()->removeElement($section);
					$em->remove($section);
				}
			}
			
			$em->flush();
			$this->addFlash('info', 'About page updated.');
		}
				
			
			
		return $this->render('cms/about.html.twig', array(
				'item' => $item,
				'form' => $form->createView(),
		));
	}
}
?>