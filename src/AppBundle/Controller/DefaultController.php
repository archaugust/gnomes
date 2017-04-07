<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Appointment;
use AppBundle\Entity\Contact;
use AppBundle\Entity\Referral;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;
use AppBundle\Form\AppointmentType;
use AppBundle\Form\ContactType;
use AppBundle\Form\ReferralType;
use JasonGrimes\Paginator;

class DefaultController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function adminAction()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        $em = $this->getDoctrine();
        
        $appointments = $em->getRepository('AppBundle:Appointment')->findBy([], array('id' => 'DESC'), 10);
        $referrals = $em->getRepository('AppBundle:Referral')->findBy([], array('id' => 'DESC'), 10);
        $contacts = $em->getRepository('AppBundle:Contact')->findBy([], array('id' => 'DESC'), 10);
        $contents = $em->getRepository('AppBundle:Content')->findBy([], array('hits' => 'DESC'));
        
        return $this->render('admin/index.html.twig', array(
                'header' => 'Dashboard',
        		'appointments' => $appointments,
        		'referrals' => $referrals,
        		'contacts' => $contacts,
        		'contents' => $contents,
            )
        );
    }
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$content = $em->getRepository('AppBundle:Content')->findOneBy(array('alias'=>'home-page'));
    	$content->setHits($content->getHits() + 1);
    	$em->flush();
    	
   		return $this->render('home.html.twig', array('content' => $content));
    }
    
    /**
     * @Route("/draft", name="draft")
     */
    public function draftAction()
    {
    	return $this->render('draft.html.twig');
    }
    
    /**
     * @Route("/book", name="book")
     */
    public function book(Request $request) {
    	$em = $this->getDoctrine()->getManager();
    	$content = $em->getRepository('AppBundle:Content')->findOneBy(array('alias'=>'book'));
    	
		$appointment = new Appointment();
		$form = $this->createForm(AppointmentType::class, $appointment);
		if (!empty($request->request->all())) {
			$form->handleRequest($request);

			$success = false;

			if ($form->isValid()) {
			
				$appointment
				->setDateSubmitted(time())
				;
			
				$em->persist($appointment);
				$em->flush();
				
				$success = true;

				$message = \Swift_Message::newInstance()
					->setSubject('Appointment Booking')
					->setFrom(array('noreply@brightsmiles.nz' => 'Bright Smiles Dental Care'))
					->setTo('hello@brightsmiles.nz')
					->setBody(
						$this->renderView('email/booking.html.twig', array(
							'data' => $appointment			
							)),
						'text/html'
				);
				$this->get('mailer')->send($message);
					
			}

			return $this->render('default/book-send.html.twig', array(
					'success' => $success,
					'id' => md5(time()),
					'form' => $form->createView()
			));
		}
        else {
        	$content->setHits($content->getHits() + 1);
        	$em->flush();
        }
        
        return $this->render('default/book.html.twig', array(
        	'content' => $content,
        	'form' => $form->createView()
        ));
    }

    /**
     * @Route("/referral", name="referral")
     */
    public function referral(Request $request) {
    	$em = $this->getDoctrine()->getManager();
    	$content = $em->getRepository('AppBundle:Content')->findOneBy(array('alias'=>'referral'));
    	 
    	$referral = new Referral();
    	$form = $this->createForm(ReferralType::class, $referral);

    	if (!empty($request->request->all())) {
    		$form->handleRequest($request);
    		
    		$success = false; 
    		
    		if ($form->isValid()) {
    			$now = time();
    			$success = true;

    			// attachments
    			$attachments = $referral->getAttachments();
    			$attach = array();
    			 
    			if (count($attachments) > 0) {
    				$rootDir = $this->container->getParameter('kernel.root_dir');
    			
    				$fs = new Filesystem();
    				if ($fs->exists($rootDir . '/../attachments'))
    					$uploadDir = $rootDir . '/../attachments';
   					else
   						$uploadDir = $rootDir . '/../images/attachments';
    			
    				$uploader = $this->get('app.file_uploader');
    				$uploader->setTargetDir($uploadDir);
    			
					$ctr = 0;
					foreach ($attachments as $attachment) {
						$ctr++;
    					$fileName = $uploader->upload($attachment, $now .'-'. $ctr);
    					
    					if (!is_null($fileName))
    						$attach[] = $fileName;
					}
    			}
    			 
    			$referral
	    			->setDateSubmitted($now)
	    			->setAttachments($attach)
    			;

    			$em->persist($referral);
    			$em->flush();
    
    			$message = \Swift_Message::newInstance()
    			->setSubject('Dentist Referral')
    			->setFrom(array('noreply@brightsmiles.nz' => 'Bright Smiles Dental Care'))
				->setTo('hello@brightsmiles.nz')
				->setBody(
    					$this->renderView('email/referral.html.twig', array(
    							'data' => $referral
    					)),
    					'text/html'
    					);
    			$this->get('mailer')->send($message);
    		}
    		
    		return $this->render('default/referral-send.html.twig', array(
    				'content' => $content,
    				'success' => $success,
    				'form' => $form->createView()
    		));
    	}
    	else {
   			$content->setHits($content->getHits() + 1);
   			$em->flush();
    	}
    
    	return $this->render('default/referral.html.twig', array(
    			'content' => $content,
    			'form' => $form->createView()
    	));
    }
    
    /**
     * @Route("/contact-us", name="contact")
     */
    public function contact(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$content = $em->getRepository('AppBundle:Content')->findOneBy(array('alias'=>'contact-us'));
    	 
    	$contact = new Contact();
    	$form = $this->createForm(ContactType::class, $contact);

    	$form->handleRequest($request);
    	if ($form->isValid()):
    		$data = $request->request->all();
	    	if(isset($data['g-recaptcha-response']) && !empty($data['g-recaptcha-response'])):
		    	//your site secret key
		    	$secret = '6LeEAgwUAAAAAKcX--0rOVfN2ujbLh4Ohc9TIDb9';
		    	//get verify response data
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
				    	->setFrom(array('noreply@brightsmiles.nz' => 'Bright Smiles Dental Care'))
						->setTo('hello@brightsmiles.nz')
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

    /**
     * @Route("/gihq-form", name="gihq_form")
     */
    public function gihqForm(Request $request) {
        $data = $this->getDoctrine()->getRepository('AppBundle:Appointment')->find($request->query->get('uid'));

        $html = $this->renderView('default/gihq-form.html.twig', array(
            'data' => $data,
        ));

        $this->returnPDFResponseFromHTML($html);

        return $this->redirectToRoute('admin_appointment');
    }

    public function returnPDFResponseFromHTML($html){
        $mpdf=new \mPDF('win-1252','A4','','',20,25,15,15,10,10);
        $mpdf->useOnlyCoreFonts = true;    // false is default
        $mpdf->SetProtection(array('print'));
        $mpdf->SetTitle("General Information and Health Questionnaire");
        $mpdf->SetAuthor("Bright Smiles");
//        $mpdf->SetWatermarkImage('http://brightsmiles.nz/images/logo-pdf.png', 1, '', array(170,10));
//        $mpdf->showWatermarkImage = true;

        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);

        $mpdf->Output('bright-smiles-appointment.pdf','D');
    }

    /**
     * @Route("/admin/appointment", name="admin_appointment")
     */
    public function adminAppointment(Request $request) {
        $data = $request->query->all();

        $items = $this->getDoctrine()->getRepository('AppBundle:Appointment')->findBy([],array('id'=>'DESC'));
        $totalItems = count($items);

        !empty($data['page']) ? $pagenum = $data['page'] : $pagenum = 1;

        $items = array_slice($items,($pagenum - 1) * 10, 10);

        $itemsPerPage = 10;
        $urlPattern = $request->getPathInfo() .'?page=(:num)';

        $paginator = new Paginator($totalItems, $itemsPerPage, $pagenum, $urlPattern);

        return $this->render('admin/appointment.html.twig', array(
        	'header' => 'Appointments',
            'items' => $items,
            'paginator' => $paginator
        ));
    }
    
    /**
     * @Route("/admin/appointment/delete/{id}", name="appointment_delete")
     */
    public function adminAppointmentDelete($id) {
    	$em = $this->getDoctrine()->getManager();
    	$appointment = $em->getRepository('AppBundle:Appointment')->find($id);
    	
    	if ($appointment == null) 
    		$this->addFlash('warning', 'Appointment does not exist.');
    	else {
    		$em->remove($appointment);
    		$em->flush();
    		
    		$this->addFlash('info', 'Appointment has been removed.');
    	}
    		
    	return $this->redirectToRoute('admin_appointment');
    }

    /**
     * @Route("/admin/referral", name="admin_referral")
     */
    public function adminReferral(Request $request) {
    	$data = $request->query->all();
    
    	$items = $this->getDoctrine()->getRepository('AppBundle:Referral')->findBy([],array('id'=>'DESC'));
    	$totalItems = count($items);
    
    	!empty($data['page']) ? $pagenum = $data['page'] : $pagenum = 1;
    
    	$items = array_slice($items,($pagenum - 1) * 10, 10);
    
    	$itemsPerPage = 10;
    	$urlPattern = $request->getPathInfo() .'?page=(:num)';
    
    	$paginator = new Paginator($totalItems, $itemsPerPage, $pagenum, $urlPattern);
    
    	return $this->render('admin/referral.html.twig', array(
    			'header' => 'Dentist Referrals',
    			'items' => $items,
    			'paginator' => $paginator
    	));
    }
    
    /**
     * @Route("/admin/contact", name="admin_contact")
     */
    public function adminContact(Request $request) {
        $data = $request->query->all();

        $items = $this->getDoctrine()->getRepository('AppBundle:Contact')->findBy([],array('id'=>'DESC'));
        $totalItems = count($items);

        !empty($data['page']) ? $pagenum = $data['page'] : $pagenum = 1;

        $items = array_slice($items,($pagenum - 1) * 10, 10);

        $itemsPerPage = 10;
        $urlPattern = $request->getPathInfo() .'?page=(:num)';

        $paginator = new Paginator($totalItems, $itemsPerPage, $pagenum, $urlPattern);

        return $this->render('admin/contact.html.twig', array(
        	'header' => 'Contact Messages',
            'items' => $items,
            'paginator' => $paginator
        ));
    }

    /**
     * @Route("/admin/appointment/details", name="admin_appointment_details")
     */
    public function adminAppointmentDetails(Request $request) {
        $data = $request->request->all();

        $item = $this->getDoctrine()->getRepository('AppBundle:Appointment')->find($data['id']);

        return $this->render('admin/appointment_details.html.twig', array(
            'item' => $item,
        ));
    }
    
    /**
     * @Route("/admin/referral/details", name="admin_referral_details")
     */
    public function adminReferralDetails(Request $request) {
    	$data = $request->request->all();
    
    	$item = $this->getDoctrine()->getRepository('AppBundle:Referral')->find($data['id']);
    
    	return $this->render('admin/referral_details.html.twig', array(
    			'item' => $item,
    	));
    }

    public function hideEmailAction($email)
    {
    	$character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
    	$key = str_shuffle($character_set); $cipher_text = ''; $id = 'e'.rand(1,999999999);
    	for ($i=0;$i<strlen($email);$i+=1) $cipher_text.= $key[strpos($character_set,$email[$i])];
    	$script = 'var a="'.$key.'";var b=a.split("").sort().join("");var c="'.$cipher_text.'";var d="";';
    	$script.= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));';
    	$script.= 'document.getElementById("'.$id.'").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"';
    	$script = "eval(\"".str_replace(array("\\",'"'),array("\\\\",'\"'), $script)."\")";
    	$script = '<script type="text/javascript">/*<![CDATA[*/'.$script.'/*]]>*/</script>';
    
    	return new Response('<span id="'.$id.'">[javascript protected email address]</span>'.$script);
    }
}