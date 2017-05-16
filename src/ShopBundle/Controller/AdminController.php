<?php 
namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\ArchProduct;

class AdminController extends Controller
{
	/**
	 * @Route("/admin", name="admin")
	 */
	public function admin()
	{
		return $this->render('admin/index.html.twig', array(
				'header' => 'Dashboard',
		)
				);
	}
}
?>