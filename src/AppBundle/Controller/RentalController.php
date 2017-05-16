<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CmsBundle\Entity\ArchRentalBooking;
use CmsBundle\Form\ArchRentalBookingType;

class RentalController extends Controller
{
	/**
	 * @Route("/pages/ski-snowboard-hire", name="ski_snowboard_hire")
	 */
	public function rental(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository('CmsBundle:ArchPageRental')->find(1);
		
		$item->setHits($item->getHits() +1);
		$em->flush();
		
		$booking = new ArchRentalBooking();
		
		$form = $this->createForm(ArchRentalBookingType::class, $booking);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			foreach ($booking->getGuests() as $guest) {
				$guest->setBooking($booking);
				$em->persist($guest);
			}
			
			$booking->setDateReceived(time());
			$em->persist($booking);
			$em->flush();
			
			$this->addFlash('info', 'Thanks for that! Your booking is now blasting through cyberspace en route to our inbox. We’ll take a look and be right in touch!');
			
			return $this->redirectToRoute('ski_snowboard_hire');
		}
		
		return $this->render('default/rental.html.twig', array(
				'item' => $item,
				'form' => $form->createView()
		));
	}
}