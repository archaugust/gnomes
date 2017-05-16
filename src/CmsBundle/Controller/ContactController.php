<?php 
namespace CmsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Contact;
use AppBundle\Form\ContactType;

class ContactController extends Controller
{
	/**
	 * @Route("/admin/contact", name="admin_contact") 
	 */
	public function contactAdmin(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:Contact');
		
		// post = add or delete
		if ($request->isMethod('POST')) {
			
			// list actions
			$data = $request->request->all();
			if (!empty($data['items'])) {
				$list_action = $data['list_action'];
				
				if ($list_action == 'delete') {
					foreach ($data['items'] as $item) {
						$item = $items->findOneBy(array('id' => $item));
						$em->remove($item);
					}
				}
				$em->flush();
			}
		}
		
		return $this->render('cms/contact.html.twig', array(
				'items' => $items->findBy([],array('id'=>'DESC')),
		));
	}
	
	/**
	 * @Route("/pages/contact", name="contact")
	 */
	public function contact(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$secret = $em->getRepository('AppBundle:ArchConfig')->findOneBy(array('name' => 'google_recaptcha_secret'))->getValue();
		$email = $em->getRepository('AppBundle:ArchConfig')->findOneBy(array('name' => 'gen_sender_email'))->getValue();
		$site_key = $em->getRepository('AppBundle:ArchConfig')->findOneBy(array('name' => 'google_recaptcha_key'))->getValue();
		
		$contact = new Contact();
		
		$form = $this->createForm(ContactType::class, $contact);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$data = $request->request->all();
			
			if(isset($data['g-recaptcha-response']) && !empty($data['g-recaptcha-response'])) {
				$response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$data['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
				
				if ($response['success'] != false) {
					// save
					$contact->setDateSubmitted(time());
					$em->persist($contact);
					$em->flush();
					
					// email
					
					return new Response('success');
				}
			}
			
			return new Response('error');
		}
		
		return $this->render('default/contact.html.twig', array(
				'form' => $form->createView(),
				'site_key' => $site_key,
				'email' => $email
		));
	}
}
?>