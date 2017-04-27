<?php 
namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\ArchProductType;
use AppBundle\Form\ArchProductTypeType;

class ArchProductTypeController extends Controller
{
	/**
	 * @Route("/admin/product-type", name="admin_product_type")
	 */
	public function productType(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchProductType');
		
		$item = new ArchProductType();
		$form = $this->createForm(ArchProductTypeType::class, $item);
		$form->handleRequest($request);
		
		if ($request->isMethod('POST')) {
			
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
		
		return $this->render('admin/product_types.html.twig', array(
				'form' => $form->createView(),
				'items' => $items->findBy(array(), array('name' => 'ASC')),
		));
	}

	/**
	 * @Route("/admin/product-type-edit/{id}", name="admin_product_type_edit")
	 */
	public function productTypeEdit(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository('AppBundle:ArchProductType')->findOneBy(array('id' => $id));
		$old_image = $item->getImage();
		
		if ($item == null) {
			$this->addFlash('danger', 'ERROR: Product type does not exist.');
			
			return $this->redirectToRoute('admin_product_type');
		}
		
		$form = $this->createForm(ArchProductTypeType::class, $item);
		$form->handleRequest($request);
		
		// edit
		if ($form->isSubmitted() && $form->isValid()) {
			// image
			if ($item->getImage()!= null) {
				$filename = $this->get('app.image_functions')->upload('banner_thumb', 'product-types', $item->getName(), $item->getImage(), $old_image);
				$item->setImage($filename);
			}
			else
				$item->setImage($old_image);
				
			$em->persist($item);
			$em->flush();
			
			$this->addFlash(
				'info',
				'Product Type updated.'
			);
		}
		
		return $this->render('admin/product_types-edit.html.twig', array(
			'form' => $form->createView(),
			'item' => $item,
		));
	}
}
?>