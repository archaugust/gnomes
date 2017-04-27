<?php 
namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\ArchTax;
use AppBundle\Form\ArchTaxType;

class ArchSettingsController extends Controller
{

	/**
	 * @Route("/admin/settings/{mode}", name="admin_settings")
	 */
	public function settings(Request $request, $mode = 'general') {
		$em = $this->getDoctrine()->getManager();
		$config = $em->getRepository('AppBundle:ArchConfig');
		
		if ($request->isMethod('POST')) {
			// save changes
			$post = $request->request->all();
			foreach($post as $key => $value) 
				$config->findOneBy(array('name' => $key))->setValue($value);
			
			$em->flush();
			
			$this->addFlash('info', 'Settings saved.');
		}
		
		$config = $config->createQueryBuilder('c')->where('c.name LIKE :name');
		switch ($mode) {
			case 'vend-api':
				$title = 'Vend API';
				$config = $config
				->setParameter('name', 'vend_%')
				->getQuery()
				->getResult();
				break;
			default:
			case 'general':
				$title = 'General';
				$config = $config
				->setParameter('name', 'gen_%')
				->getQuery()
				->getResult();
				break;
		}
		
		return $this->render('admin/settings.html.twig', array(
				'mode' => $mode,
				'title' => $title,
				'config' => $config
		));
	}

	/**
	 * @Route("/admin/tax/{mode}", name="admin_tax")
	 */
	public function tax(Request $request, $mode = '') {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchTax');
		$default = $items->findBy(array('is_default' => 1));
		
		$tax = new ArchTax();
		$form = $this->createForm(ArchTaxType::class, $tax);
		$form->handleRequest($request);
		
		if ($form->isSubmitted()) {
			if ($form->isValid()) {
				// post to Vend
				$input = $request->request->get('arch_tax');
				$rate = (int)$input['rate'] / 100;
				$data = array(
						'name' => $input['name'],
						'rate' => $rate
				);
				$url = 'taxes';
	
				$result = $this->get('app.vend')->postVend($url, $data);
	
				if ($result == null) {
					$this->addFlash(
							'danger', 
							'ERROR: Unable to post to Vend API.'
					);
					return $this->redirectToRoute('admin_tax');
				}
				
				$tax
					->setId($result->id)
					->setRate($rate)
					->setIsActive($result->active)
					->setIsDefault($result->default)
				;
				
				$em->persist($tax);
				$em->flush();
				
				$this->addFlash(
						'info',
						'Sales Tax added.'
				);
			}
			else
				$this->addFlash('danger', 'ERROR: Please check your input.');
		}
		
		return $this->render('admin/'. $mode .'taxes.html.twig', array(
				'form' => $form->createView(),
				'items' => array_reverse($items->findAll()),
				'default' => count($default)
		));
	}
	
	/**
	 * @Route("/admin/outlet/{mode}", name="admin_outlet")
	 */
	public function outlet(Request $request, $mode = '') {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchOutlet');
		$default = $items->findBy(array('is_default' => 1));

		return $this->render('admin/'. $mode .'outlets.html.twig', array(
				'items' => $items->findAll(),
				'default' => count($default),
		));
	}
	
	/**
	 * @Route("/admin/register/{mode}", name="admin_register")
	 */
	public function register(Request $request, $mode = '') {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchRegister');
		$default = $items->findBy(array('is_default' => 1));
		
		return $this->render('admin/'. $mode .'registers.html.twig', array(
				'items' => $items->findAll(),
				'default' => count($default),
		));
	}
	
	/**
	 * @Route("/admin/payment-type/{mode}", name="admin_payment_type")
	 */
	public function paymentType(Request $request, $mode = '') {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchPaymentType');
		$default = $items->findBy(array('is_default' => 1));
		
		return $this->render('admin/'. $mode .'payment_types.html.twig', array(
				'items' => $items->findAll(),
				'default' => count($default),
		));
	}
}
?>