<?php
namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function adminMenu(FactoryInterface $factory, array $options)
    {
    	$em = $this->container->get('doctrine')->getManager();
        $menu = $factory->createItem('root');
        $menu->setChildrenAttributes(['class' => 'nav navbar-nav side-nav']);

        $menu->addChild('Dashboard', array('route' => 'admin'))
            ->setAttribute('icon', 'fa fa-fw fa-dashboard');

        $menu->addChild('Booked Appointments', array('route' => 'admin_appointment'))
            ->setAttribute('icon', 'fa fa-fw fa-calendar');
        $menu->addChild('Dentist Referrals', array('route' => 'admin_referral'))
            ->setAttribute('icon', 'fa fa-fw fa-share');
        $menu->addChild('Contact Messages', array('route' => 'admin_contact'))
            ->setAttribute('icon', 'fa fa-fw fa-comment');
            
        $menu->addChild('Pages', array('uri' => '#', 'attributes' => array('icon' => 'fa fa-fw fa-arrows-v', 'collapse' => true, 'collapseData' => 'subPages')))
            ->setAttribute('icon', 'fa fa-fw fa-file-text');
        $menu_level_2 = $menu['Pages']->addChild('View All', array('route' => 'admin_content'), 'attributes');
        $repository = $em->getRepository('AppBundle:Content');
        $result = $repository->findBy([]);
        foreach ($result as $item) 
            $menu['Pages']->addChild($item->getTitle(), array('route' => 'admin_content_edit', 'routeParameters' => array('id' => $item->getId())));

        $menu['Pages']->setChildrenAttributes(array('class' => 'collapse in','id' => 'subPages'));
        $menu_level_2->setChildrenAttributes(array('class' => 'nav'));
        		
        $menu->addChild('Change Password', array('route' => 'fos_user_change_password'))
        	->setAttribute('icon', 'fa fa-fw fa-key');
        /*
        $menu->addChild('Menu', array('uri' => '#', 'attributes' => array('icon' => 'fa fa-fw fa-arrows-v', 'collapse' => true, 'collapseData' => 'subMenu')))
            ->setAttribute('icon', 'fa fa-fw fa-list-alt');
        $menu_level_2 = $menu['Menu']->addChild('View All', array('route' => 'admin_menu'), 'attributes');
        $repository = $em->getRepository('AppBundle:Menu');
        $result = $repository->findBy([]);
        foreach ($result as $item) {
            $item_id = $item->getId();
            $item_title = $item->getTitle();
            $menu_level_2->addChild($item_title, array('route' => 'admin_menu_page', 'routeParameters' => array('id' => $item_id)));
        }

        $menu['Menu']->addChild('Add New Menu', array('route' => 'admin_menu_add'));
        $menu['Menu']->setChildrenAttributes(array('class' => 'collapse','id' => 'subMenu'));
        $menu_level_2->setChildrenAttributes(array('class' => 'nav'));
		*/
        return $menu;
    }

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttributes(['class' => 'nav navbar-nav main-menu']);

        $em = $this->container->get('doctrine')->getManager()->getRepository('AppBundle:MenuItem');
        $result = $em->findBy(array('menuId' => 1, 'parentId' => 0), array('sortOrder' => 'ASC'));
        foreach ($result as $item) {
            $title = $item->getTitle();

            $children = $em->findBy(array('parentId' => $item->getId()), array('sortOrder' => 'ASC'));
            if (empty($children))
            {
                $pageType = $item->getPageType();
                switch ($pageType)
                {
                    case  'text-separator':
                        $array = array('uri' => '#');
                        break;
                    case 'content':
                        $array = array(
                            'route' => '_custom_route',
                            'routeParameters' => array('alias' => $item->getAlias()),
                        );
                        break;
                    case 'route':
                        $array = array('route' => $item->getPageTypeId());
                        break;
                    case 'properties-category':
                        $array = array(
                            'route' => 'properties_category',
                            'routeParameters' => array(
                                'category' => $item->getPageTypeId()
                            )
                        );
                        break;
                    case 'url':
                    default:
                        $array = array('uri' => $item->getPageTypeId());
                        break;
                }
                $menu->addChild($title, $array);
            }
            else {
                $menu->addChild($title, array(
                    'route' => '_custom_route',
                    'routeParameters' => array('alias' => $item->getAlias(),
                    )))
                    ->setAttribute('class', 'dropdown')
                    ->setLinkAttribute('class', 'dropdown-toggle')
                    ->setLinkAttribute('data-toggle', 'dropdown');

                foreach ($children as $child)
                {
                    $pageType = $child->getPageType();
                    switch ($pageType)
                    {
                        case  'text-separator':
                            $array = array('uri' => '#');
                            break;
                        case 'content':
                            $array = array(
                                'route' => '_custom_route',
                                'routeParameters' => array('alias' => $item->getAlias() .'/'. $child->getAlias()),
                            );
                            break;
                        case 'properties-category':
                            $array = array(
                                'route' => 'properties_category',
                                'routeParameters' => array(
                                    'category' => $child->getPageTypeId()
                                )
                            );
                            break;
                        case 'route':
                            $array = array('route' => $child->getPageTypeId());
                            break;
                        default:
                        case 'url':
                            $array = array('uri' => $child->getPageTypeId());
                            break;
                    }

                    $menu[$title]->addChild($child->getTitle(), $array);
                }
                $menu[$title]->setChildrenAttributes(array('class' => 'dropdown-menu'));
            }
        }
        return $menu;
    }
}