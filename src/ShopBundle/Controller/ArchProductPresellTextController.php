<?php 
namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ArchProductPresellTextController extends Controller
{
	
	/**
	 * @Route("/admin/pre-sell-text", name="admin_pre_sell_text")
	 */
	public function preSellText(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchProductPresellText');
		
		if ($request->isMethod('POST')) {
			$contents = $request->request->get('content');

			$ctr = 1;
			foreach ($contents as $content) {
				$item = $items->find($ctr);
				
				$item->setContent($content);
				$ctr++;
			}
			
			$em->flush();
			
			$this->addFlash(
				'info',
				'Pre-sell text templates updated.'
			);
			
			return $this->redirectToRoute('admin_pre_sell_text');
		}
		
		return $this->render('admin/pre_sell_text.html.twig', array(
				'items' => $items->findAll(),
		));
	}
}
?>