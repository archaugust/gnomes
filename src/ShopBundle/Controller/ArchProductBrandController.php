<?php 
namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\ArchProductBrand;
use AppBundle\Form\ArchProductBrandType;

class ArchProductBrandController extends Controller
{
	
	/**
	 * @Route("/admin/brand", name="admin_brand")
	 */
	public function brand(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchProductBrand');
		
		$item = new ArchProductBrand();
		$form = $this->createForm(ArchProductBrandType::class, $item);
		$form->handleRequest($request);
		
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
		
		return $this->render('admin/brands.html.twig', array(
				'form' => $form->createView(),
				'items' => $items->findBy(array(), array('name' => 'ASC')),
		));
	}
	
	/**
	 * @Route("/admin/brand-edit/{id}", name="admin_brand_edit")
	 */
	public function brandEdit(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository('AppBundle:ArchProductBrand')->findOneBy(array('id' => $id));
		$old_image = $item->getImage();
		
		if ($item == null) {
			$this->addFlash('danger', 'ERROR: Brand does not exist.');
			
			return $this->redirectToRoute('admin_brand');
		}
		
		$form = $this->createForm(ArchProductBrandType::class, $item);
		$form->handleRequest($request);
		
		// edit
		if ($form->isSubmitted() && $form->isValid()) {
			// image
			if ($item->getImage()!= null) {
				$filename = $this->get('app.image_functions')->upload('thumb', 'brands', $item->getName(), $item->getImage(), $old_image);
				$item->setImage($filename);
			}
			else
				$item->setImage($old_image);
				
			$em->flush();
			
			$this->addFlash(
					'info',
					'Brand updated.'
					);
		}
		
		return $this->render('admin/brands-edit.html.twig', array(
				'form' => $form->createView(),
				'item' => $item,
		));
	}
}
?>