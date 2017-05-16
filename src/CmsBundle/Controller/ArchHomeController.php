<?php 
namespace CmsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use CmsBundle\Entity\ArchPageHome;
use CmsBundle\Form\ArchPageHomeType;
use Doctrine\Common\Collections\ArrayCollection;

class ArchHomeController extends Controller
{
	/**
	 * @Route("/admin/page-home/{mode}", name="admin_page_home") 
	 */
	public function pageHome(Request $request, $mode = '') {
		// mode can be used to switch template to ajax
		$data = $request->query->all();
		
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('CmsBundle:ArchPageHome');
		
		$item = new ArchPageHome();
		$form = $this->createForm(ArchPageHomeType::class, $item);
		$form->handleRequest($request);
		
		// post = add or delete
		if ($request->isMethod('POST')) {
			$miscFunctions = $this->get('app.misc_functions');
			
			// list actions
			$data = $request->request->all();
			if (!empty($data['items'])) {
				if ($data['list_action'] == 'delete') {
					foreach ($data['items'] as $item) {
						$item = $items->findOneBy(array('id' => $item));
						
						// delete slides
						foreach ($item->getSlides() as $slide) {
							$em->remove($slide);
						}
						
						// delete sections
						foreach ($item->getSections() as $section) {
							$em->remove($section);
						}
						
						$em->remove($item);
					}
				}
				
				$em->flush();
			}
			
			// add
			if ($form->isSubmitted() && $form->isValid()) {
					
				// slides
				$slides = $item->getSlides();
				if (count($slides) > 0) {
					$ctr = 0;
					foreach ($slides as $slide) {
						
						// process image
						$filename = $this->get('app.image_functions')->upload('banner', 'home-page/slides', $miscFunctions->slug($item->getName()) .'-'. $ctr, $slide->getFilename());
						$slide
							->setPage($item)
							->setFilename($filename)
						;
						
						$ctr++;
						$em->persist($slide);
					}
				}

				// sections
				$sections = $item->getSections();
				if (count($sections) > 0) {
					foreach ($sections as $section) {

						// process image
						$filename = $this->get('app.image_functions')->upload('home_section', 'home-page/sections', $miscFunctions->slug($section->getBannerText()), $slide->getFilename());
						$section
							->setPage($item)
							->setFilename($filename)
						;
						$em->persist($section);
					}
				}
				
				$em->persist($item);
				$em->flush();
				
				$this->addFlash('info','Home page added.');
				
				return $this->redirectToRoute('admin_page_home');
			}
			else
				$this->addFlash('danger', 'ERROR: Please check your input.');
		}
			
		return $this->render('cms/'. $mode .'home.html.twig', array(
			'items' => $items->findAll(),
			'form' => $form->createView(),
		));
	}

	/**
	 * @Route("/admin/page-home-edit/{id}", name="admin_page_home_edit")
	 */
	public function pageHomeEdit(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();
		$repository = $em->getRepository('CmsBundle:ArchPageHome');
		if (($item = $repository->findOneBy(array('id' => $id))) == null) {
			$this->addFlash('danger', 'Home page not found or deleted.');
			
			return $this->redirectToRoute('admin_page_home');
		}
		
		$originalSlides = new ArrayCollection();
		$originalSections = new ArrayCollection();
		$originalSlideImages = array();
		$originalSectionDetails = array();
		
		foreach ($item->getSlides() as $slide) {
			$originalSlides->add($slide);
			$originalSlideImages[$slide->getId()] = $slide->getFilename();
		}

		foreach ($item->getSections() as $section) {
			$originalSections->add($section);
			$originalSectionDetails[$section->getId()] = array(
					'banner_text' => $section->getBannerText(),
					'filename' => $section->getFilename(),
			);
		}
			
		$form = $this->createForm(ArchPageHomeType::class, $item);
		
		// update
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			
			$miscFunctions = $this->get('app.misc_functions');
			$imageFunctions = $this->get('app.image_functions');

			// slides
			$slides = $item->getSlides();
			
			if (count($slides) > 0) {
				$ctr_slide = count($originalSlides);
				foreach ($slides as $slide) {
					$rand = rand(0,1000);
					if (!is_null($slide->getFilename())) {
						// process image
						if ($slide->getId()) 
							$ctr = $slide->getId();
						else {
							$ctr_slide++;
							$ctr = $ctr_slide;
						}
							
						$filename = $imageFunctions->upload('banner', 'home-page/slides', $miscFunctions->slug($item->getName()) .'-'. $ctr .'-'. $rand, $slide->getFilename());
						
						// delete old image
						$imageFunctions->deleteImage($originalSlideImages[$slide->getId()], 'home-page/slides');
					}
					else {
						// get original image since no image is sent with a file input type
						foreach ($originalSlides as $originalSlide) {
							if ($slide == $originalSlide) 
								$filename = $originalSlideImages[$slide->getId()];
						}
					}
					
					$slide
						->setPage($item)
						->setFileName($filename);
					$em->persist($slide);
				}
			}

			// remove slides
			foreach ($originalSlides as $slide) {
				if (false === $item->getSlides()->contains($slide)) {
					// delete image
					$imageFunctions->deleteImage($originalSlideImages[$slide->getId()], 'home-page/slides');
					
					$item->getSlides()->removeElement($slide);
					$em->remove($slide);
				}
			}
			
			// sections
			$sections = $item->getSections();
			
			if (count($sections) > 0) {
				foreach ($sections as $section) {
					$rand = rand(0,1000);
					if (!is_null($section->getFilename())) {
						// process image
						$filename = $imageFunctions->upload('home_section', 'home-page/sections', $miscFunctions->slug($section->getBannerText()), $section->getFilename());
						
						if ($section->getPage() != null) {
							// delete old image if different text
							if ($section->getBannerText() != $originalSectionDetails[$section->getId()]['banner_text'])
								$imageFunctions->deleteImage($originalSectionDetails[$section->getId()]['filename'], 'home-page/sections');
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
					$imageFunctions->deleteImage($originalSectionDetails[$section->getId()]['filename'], 'home-page/sections');
					
					$item->getSections()->removeElement($section);
					$em->remove($section);
				}
			}
			
			$em->flush();
			$this->addFlash('info', 'Home page updated.');
		}
				
			
			
		return $this->render('cms/home-edit.html.twig', array(
				'item' => $item,
				'form' => $form->createView(),
		));
	}
}
?>