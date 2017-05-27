<?php 
namespace CmsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Contact;
use AppBundle\Form\ContactType;
use CmsBundle\Form\ArchPageContactType;

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
	 * @Route("/admin/contact-page", name="admin_contact_page")
	 */
	public function adminContactPage(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository('CmsBundle:ArchPageContact')->find(1);
		
		$form = $this->createForm(ArchPageContactType::class, $item);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$this->addFlash('info', 'Contact page updated.');
			
			$em->flush();
			
			return $this->redirectToRoute('admin_contact_page');
		}
		
		return $this->render('cms/contact-edit.html.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * @Route("/pages/contact", name="contact")
	 */
	public function contact(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$config = $em->getRepository('AppBundle:ArchConfig');
		$secret = $config->findOneBy(array('name' => 'google_recaptcha_secret'))->getValue();
		$email = $config->findOneBy(array('name' => 'gen_sender_email'))->getValue();
		$site_key = $config->findOneBy(array('name' => 'google_recaptcha_key'))->getValue();
		$item = $em->getRepository('CmsBundle:ArchPageContact')->find(1);
		
		// page hit
		$item->setHits($item->getHits() + 1);
		$em->flush();
		
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
					$sender_email = $config->findOneBy(array('name' => 'gen_sender_email_swift'))->getValue();
					$store_name = $config->findOneBy(array('name' => 'gen_store_name'))->getValue();
					
/*					// mail admin
					$message = \Swift_Message::newInstance()
					->setSubject('New Contact Message')
					->setFrom(array($sender_email => $store_name))
					->setTo($email)
					->setReplyTo($email)
					->setBody(
							$this->renderView('email/new_contact.html.twig', array(
									'store_name' => $store_name,
									'item' => $contact,
							)),
							'text/html'
							);
					$this->get('mailer')->send($message);
	*/				
					$admin = "admin@gnomes.co.nz";
					$message = \Swift_Message::newInstance()
					->setSubject('New Contact Message')
					->setFrom(array($sender_email => $store_name))
					->setTo($admin)
					->setReplyTo($email)
					->setBody(
							$this->renderView('email/new_contact.html.twig', array(
									'store_name' => $store_name,
									'item' => $contact,
							)),
							'text/html'
							);
					
					$this->get('mailer')->send($message);
					
					return new Response('success');
				}
			}
			
			return new Response('error');
		}
		
		return $this->render('default/contact.html.twig', array(
				'item' => $item,
				'form' => $form->createView(),
				'site_key' => $site_key,
				'email' => $email
		));
	}
}
?>