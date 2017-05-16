<?php
namespace RouteBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Content;
use AppBundle\Entity\Menu;
use AppBundle\Entity\MenuItem;

class RouteController extends Controller
{
    /**
     * @Route("/{alias}", name="_custom_route", requirements={"alias"=".+"})
     */
    public function customRoute($alias)
    {
        $em = $this->getDoctrine()->getManager();
        $alias = explode('/',$alias);
        $slug = end($alias);
        $menu = $em->getRepository('AppBundle:MenuItem');
        $route = $menu->findOneBy(array('alias' => $slug));

        if (count($route) == 0)
        {
//            $this->addFlash(
//                'danger',
//                'Requested page does not exist or is no longer available.'. $slug
//            );

            return $this->redirectToRoute('homepage');
        }

        $pageType = $route->getPageType();
        $pageTypeId = $route->getPageTypeId();

        switch ($pageType)
        {
            case 'content':
            {
                $content = $em->getRepository('AppBundle:Content')
                    ->findOneBy(array('alias' => $pageTypeId));
                $content->setHits($content->getHits()+1);
                $em->flush();
                $template = 'content';
                $contentParts = array(
                    'content' => $content
                );
                break;
            }
            default:
//                $this->addFlash(
//                    'danger',
//                    'Requested page does not exist or is no longer available.'. $slug
//                );

                return $this->redirectToRoute('homepage');
        }

        return $this->render('default/'. $template .'.html.twig', $contentParts);
    }
}