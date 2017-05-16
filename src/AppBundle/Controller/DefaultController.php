<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
	/**
	 * @Route("/", name="homepage")
	 */
	public function index()
	{
		$em = $this->getDoctrine()->getManager();
		$page = $em->getRepository('CmsBundle:ArchPageHome')->findOneBy(array('is_default' => 1));
		$page->setHits($page->getHits()+1);
		$em->flush();
		
		$blog = $em->getRepository('CmsBundle:ArchBlog')->findBy(array('is_active' => 1), array('display_date' => 'DESC'), 6);

		// mobile only css
		if (preg_match("/\b(?:a(?:ndroid|vantgo)|b(?:lackberry|olt|o?ost)|cricket|do‌​como|hiptop|i(?:emob‌​ile|p[ao]d)|kitkat|m‌​(?:ini|obi)|palm|(?:‌​i|smart|windows )phone|symbian|up\.(?:browser|link)|tablet(?: browser| pc)|(?:hp-|rim |sony )tablet|w(?:ebos|indows ce|os))/i", $_SERVER["HTTP_USER_AGENT"]) == true)
			$mobile = true;
		else 
			$mobile = false;
			
		return $this->render('default/index.html.twig', array(
				'page' => $page,
				'blogs' => $blog,
				'mobile' => $mobile
		));
	}

	/**
	 * @Route("/member", name="member")
	 */
	public function member() {
		if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
			return $this->redirectToRoute('admin');
		
		if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
			$this->addFlash(
					'info',
					'You have successfully logged in.'
					);
		}
		else {
			$this->addFlash(
					'info',
					'Please login to your account.'
					);
		}
		return $this->redirectToRoute('homepage');
	}
	
    /**
     * @Route("/contact-us", name="contact")
     */
    public function contact(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$content = $em->getRepository('AppBundle:Content')->findOneBy(array('alias'=>'contact-us'));
    	$config = $em->getRepository('AppBundle:ArchConfig');
    	$email = $config->findOneBy(array('name' => 'general_email'));
    	
    	$contact = new Contact();
    	$form = $this->createForm(ContactType::class, $contact);

    	$form->handleRequest($request);
    	if ($form->isValid()):
    		$data = $request->request->all();
	    	if(isset($data['g-recaptcha-response']) && !empty($data['g-recaptcha-response'])):
	    		$secret = $config->findOneBy(array('name' => 'recaptcha_secret'))->getValue();
		    	$response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$data['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
		    	
		    	// if the captcha is cleared with google, send the mail and echo ok.
		    	if ($response['success'] != false) :
			    	$contact
				    	->setDateSubmitted(time())
			    	;
			    	$em->persist($contact);
			    	$em->flush();
			    	
			    	$content = 'Thank you. Your message has been sent.';

			    	$message = \Swift_Message::newInstance()
			    		->setSubject('Contact')
				    	->setFrom(array($email->getValue() => $email->getDescription()))
				    	->setTo($email->getValue())
				    	->setBody(
			    			$this->renderView('email/contact.html.twig', array(
			    					'data' => $contact
				    			)),
				    			'text/html'
			    			);

				    $this->get('mailer')->send($message);
		    	else:
		    		$content = 'Robot verification failed, please try again.';
		    	endif;
	    	else:
		    	$content = 'Please click on the reCAPTCHA box.';
	    	endif;
	    	return $this->render('default/contact-send.html.twig', array(
	    			'content' => $content,
	    			'form' => $form->createView()
	    	));
    	else :
	    	$content->setHits($content->getHits() + 1);
	    	$em->flush();
		endif;
	    	
    	return $this->render('default/contact.html.twig', array(
    		'content' => $content,
    		'form' => $form->createView()
    	));
    }
}