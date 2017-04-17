<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JasonGrimes\Paginator;

class DefaultController extends Controller
{
	/**
	 * @Route("/", name="homepage")
	 */
	public function indexAction()
	{
		return $this->render('default/index.html.twig', array(
		));
	}

	/**
	 * @Route("/regions", name="regions")
	 */
	public function regions(Request $request) {
		$em = $this->getDoctrine();
		$data = $request->request->all();
		$id = isset($data['country']) ? $data['country'] : '';
		$field = isset($data['field']) ? $data['field'] : '';
		
		$country = $em->getRepository('AppBundle:ArchCountry')->findOneBy(array('country_id' => $id));
		
		if (count($country) > 0) 
			$regions = $em->getRepository('AppBundle:ArchRegion')->findBy(array('country_id' => $country->getId())); 
		else 
			$regions = array();
		return $this->render('regions.html.twig', array(
				'items' => $regions,
				'field' => $field
		));
	}
	
	public function getCountryNameAction($code) {
		$country = $this->getDoctrine()->getRepository('AppBundle:ArchCountry');
		
		return new Response($country->findOneBy(array('country_id' => $code))->getName());
	}
	
	public function getSexAction($code) {
		return new Response($code == 'F' ? 'Female' : 'Male');
	}
	
	/**
	 * @Route("/result", name="result")
	 */
	public function result(){
		$result = $this->get('session')->get('result');
		
		$result = json_decode($result);
		$products = $result->products;
		foreach ($products as $product) {
			echo $product->id .' '. $product->name .'<br />';
		}
		if (!empty($result->pagination)) {
			$pages = $result->pagination;
			dump($pages);
		}
		die;
	}
	
	/**
	 * @Route("/vend-suppliers", name="vend_suppliers")
	 */
	public function vendSuppliers()
	{
		$config = $this->getDoctrine()->getRepository('ShopBundle:ArchConfig');
		
		// check if Vend access token is set
		$vendToken = $this->get('session')->get('vendToken');
		$vendURL = $this->get('session')->get('vendURL');
		
		if (empty($vendToken)) 
			$vendToken = $this->getVendAccessToken();
		
		// test Vend access token
		$url = $vendURL .'/taxes';
		$context = stream_context_create(array(
				'http' => array(
						'header' => "Authorization: Bearer " . $vendToken,
				),
		));
		
		@$result = file_get_contents($vendURL, false, $context);
		dump($result);
		die;
		//   		return $this->render('home.html.twig', array('content' => $content));
	}
	
	/**
	 * @Route("/vend-authorize", name="vend_authorize")
	 */
	public function vendAuthorize(Request $request) {
		$get = $request->query->all();
		$em = $this->getDoctrine()->getManager();
		
		$config = $em->getRepository('ShopBundle:ArchConfig');
		$vendURL = 'https://'. $config->findOneBy(array('name' => 'vend_prefix'))->getValue() .'.vendhq.com/api';
		
		if ($get['state'] == 'getCode') {
			$data = array(
					'code' => $get['code'],
					'client_id' => $config->findOneBy(array('name' => 'vend_client_id'))->getValue(),
					'client_secret' => $config->findOneBy(array('name' => 'vend_client_secret'))->getValue(),
					'grant_type' => 'authorization_code',
					'redirect_uri' => $config->findOneBy(array('name' => 'vend_redirect_uri'))->getValue(),
			);
			
			$options = array(
					'http' => array(
							'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
							'method'  => 'POST',
							'content' => http_build_query($data)
					)
			);
			$context  = stream_context_create($options);
			$result = file_get_contents($vendURL .'/1.0/token', false, $context);
			if ($result === FALSE) {
				$this->addFlash('warning', 'Failed. Please check your configuration.');
				$this->redirectToRoute('homepage');
			}
			
			$access_token = $config->findOneBy(array('name' => 'vend_access_token'));
			$refresh_token = $config->findOneBy(array('name' => 'vend_refresh_token'));
			
			$result = json_decode($result);
			$access_token->setValue($result->access_token);
			$refresh_token->setValue($result->refresh_token);
			
			$em->flush();
			
			return new Response($result);
		}
	}
	
	/**
	 * @Route("/member", name="member")
	 */
	public function memberAction() {
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