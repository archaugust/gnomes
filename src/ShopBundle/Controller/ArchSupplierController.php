<?php 
namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\ArchSupplier;
use AppBundle\Form\ArchSupplierType;

class ArchSupplierController extends Controller
{
	
	/**
	 * @Route("/admin/supplier/{mode}", name="admin_supplier")
	 */
	public function supplier(Request $request, $mode = '') {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchSupplier');
		
		$item = new ArchSupplier();
		$form = $this->createForm(ArchSupplierType::class, $item);
		$form->handleRequest($request);
		
		if ($form->isSubmitted()) {
			if ($form->isValid()) {
				// post to Vend
				$input = $request->request->get('arch_supplier');
				$data = array(
						'name' => $input['name'],
						'description' => $input['description']
				);
				$url = 'supplier';
				
				$result = $this->get('app.vend')->postVend($url, $data);
				
				if ($result == null) {
					$this->addFlash(
							'danger',
							'ERROR: Unable to post to Vend API.'
							);
					return $this->redirectToRoute('admin_supplier');
				}
				
				$item
					->setId($result->id)
				;
				
				$em->persist($item);
				$em->flush();
				
				$this->addFlash(
						'info',
						'Supplier added.'
				);
				
				return $this->redirectToRoute('admin_supplier');
			}
			else
				$this->addFlash('danger', 'ERROR: Please check your input.');
		}
		
		return $this->render('admin/'. $mode .'suppliers.html.twig', array(
				'form' => $form->createView(),
				'items' => $items->findBy(array(), array('name' => 'ASC'))
		));
	}
}
?>