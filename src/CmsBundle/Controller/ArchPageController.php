<?php 
namespace CmsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use CmsBundle\Form\ArchPageType;
use CmsBundle\Entity\ArchPage;

class ArchPageController extends Controller
{
	/**
	 * @Route("/admin/page", name="admin_page") 
	 */
	public function page(Request $request) {
		
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('CmsBundle:ArchPage');
		
		$item = new ArchPage();
		$form = $this->createForm(ArchPageType::class, $item);
		$form->handleRequest($request);

		if ($request->isMethod('POST')) {
			$miscFunctions = $this->get('app.misc_functions');
			
			// list actions
			$data = $request->request->all();
			if (!empty($data['items'])) {
				$list_action = $data['list_action'];
				$ctr = 0;
				
				if ($list_action == 'delete') {
					foreach ($data['items'] as $item) {
						$item = $items->findOneBy(array('id' => $item, 'core' => null));
						if ($item) {
							$ctr++;
							$em->remove($item);
						}
					}
				}
				else {
					$status = $list_action == 'disable' ? 0 : 1;
					
					foreach ($data['items'] as $item) {
						$item = $items->findOneBy(array('id' => $item));
						$item->setIsActive($status);
					}
				}
				
				$em->flush();
				$this->addFlash('info', $ctr .' page(s) deleted.');
			}
			
			if ($form->isSubmitted() && $form->isValid()) {
				$imageFunctions = $this->get('app.image_functions');
				
				$handle = $miscFunctions->slug($item->getName());
				// check duplicate
				while (count($items->findOneBy(array('handle' => $handle))) > 0) {
					$handle = $handle .'-';
				}
				
				$item->setHandle($handle);
				
				// images
				if ($item->getBanner() != null) {
					$banner = $imageFunctions->upload('page', 'pages', $handle, $item->getBanner());
					$item->setBanner($banner);
				}
				
				$em->persist($item);
				$em->flush();
				$this->addFlash('info', 'Page added.');
				
				return $this->redirectToRoute('admin_page');
			}
		}
			
		return $this->render('cms/page.html.twig', array(
				'items' => $items->findBy(array()),
				'form' => $form->createView(),
		));
	}

	/**
	 * @Route("/admin/page-edit/{id}", name="admin_page_edit")
	 */
	public function pageEdit(Request $request, $id) {
		
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('CmsBundle:ArchPage');
		$item = $items->find($id);
		$old_handle = $item->getHandle();
		
		if ($item == null) {
			$this->addFlash('danger', 'Page not found.');
			
			return $this->redirectToRoute('admin_page');
		}
		
		$old_banner = $item->getBanner();
		
		$form = $this->createForm(ArchPageType::class, $item);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			$miscFunctions = $this->get('app.misc_functions');
			$imageFunctions = $this->get('app.image_functions');
			
			$handle = $miscFunctions->slug($item->getName());
			// check duplicate
			if ($handle != $old_handle) {
				while (count($items->findOneBy(array('handle' => $handle))) > 0) {
					$handle = $handle .'-';
				}
			}
			
			// images
			if ($item->getBanner() != null) {
				$banner = $imageFunctions->upload('page', 'pages', $handle, $item->getBanner(), $old_banner);
			}
			else
				$banner = $old_banner;
				
			$item
				->setHandle($handle)
				->setBanner($banner)
			;
				
			$em->flush();
			$this->addFlash('info', 'Page updated.');
			
			return $this->redirectToRoute('admin_page_edit', array('id' => $id));
		}
		
		return $this->render('cms/page_edit.html.twig', array(
				'item' => $item,
				'form' => $form->createView(),
		));
	}
	
}
?>