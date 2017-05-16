<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ActionController extends Controller
{
	public function mainMenuAction() {
		$session = $this->get('session');

		if (($menu = $session->get('main-menu')) == null) {
			$items = $this->getDoctrine()->getRepository('AppBundle:ArchProductCategory')->findBy(array('is_active' => 1), array('sort_order' => 'ASC'));
			
			$menu = array();
			foreach ($items as $item) {
				$collection_items = $item->getCollections();
				
				$collections = array();
				foreach($collection_items as $collection_item) {
					$collection = $collection_item->getCollection();
					$collections[] = array(
							'handle' => $collection->getHandle(),
							'name' => $collection->getName()
					);
				}
				$menu[] = array(
					'name' => $item->getName(),
					'collections' => $collections
				);
			};
			
			$session->set('main-menu',$menu);
		}
		
		return $this->render('menu.html.twig', array(
				'menu' => $menu
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