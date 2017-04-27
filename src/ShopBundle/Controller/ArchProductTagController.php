<?php 
namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\ArchProductTag;
use AppBundle\Form\ArchProductTagType;

class ArchProductTagController extends Controller
{
	
	/**
	 * @Route("/admin/tag", name="admin_tag")
	 */
	public function tag(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchProductTag');
		
		if ($request->isMethod('POST')) {
			// delete
			$data = $request->request->all();
			if (isset($data['items'])) {
				$status = ($data['list_action'] == 'enable') ? 1 : 0;
				
				foreach ($data['items'] as $item) {
					$item = $items->findOneBy(array('id' => $item));
					$item->setIsActive($status);
				}
				
				$em->flush();
			}
		}
		
		return $this->render('admin/tags.html.twig', array(
				'items' => $items->findAll(),
		));
	}
	
	/**
	 * @Route("/admin/tag-edit/{id}", name="admin_tag_edit")
	 */
	public function tagEdit(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository('AppBundle:ArchProductTag')->findOneBy(array('id' => $id));
		
		if ($item == null) {
			$this->addFlash('danger', 'ERROR: Tag does not exist.');
			
			return $this->redirectToRoute('admin_tag');
		}
		
		$form = $this->createForm(ArchProductTagType::class, $item);
		$form->handleRequest($request);
		
		// edit
		if ($form->isSubmitted() && $form->isValid()) {
			$em->persist($item);
			$em->flush();
			
			$this->addFlash(
					'info',
					'Tag updated.'
			);
		}
		
		return $this->render('admin/tags-edit.html.twig', array(
				'form' => $form->createView(),
				'item' => $item,
		));
	}
}
?>