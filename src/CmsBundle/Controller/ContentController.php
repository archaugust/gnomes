<?php
namespace CmsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Content;
use AppBundle\Form\ContentType;
use AppBundle\Form\MenuItemType;
use AppBundle\Form\MenuType;
use AppBundle\Entity\Menu;
use AppBundle\Entity\MenuItem;
use Symfony\Component\Filesystem\Filesystem;
use JasonGrimes\Paginator;

class ContentController extends Controller
{
    /**
     * @Route("/admin/menu/", name="admin_menu")
     */
    public function adminMenu(Request $request)
    {
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Menu');

        if ($request->request->get('submit')) {
            $em = $this->getDoctrine()->getManager();
            $deleteList = $request->request->get('delete');
            foreach ($deleteList as $item)
            {
                $menu = $repository->find($item);
                $em->remove($menu);
                $em->flush();
            }

            $this->addFlash(
                'notice',
                'Menu(s) deleted.'
            );
        }

        $sortBy = $request->query->get('sort');
        if ($sortBy == '')
            $sortBy = 'id';
        $order = $request->query->get('order');
        if ($order == '')
            $order = 'ASC';
        $content = $repository->findBy([],array($sortBy => $order));

        return $this->render('admin/menu.html.twig', array(
            'contents' => $content,
            'header' => 'Menu',
        ));
    }

    /**
     * @Route("admin/menu/add/", name="admin_menu_add")
     */
    public function newMenuAction(Request $request)
    {
        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->request->get('menu');

            $menu->setTitle($data['title'])
                ->setAlias($this->get('app.misc_functions')->slug($data['title']))
            ;

            $em = $this->getDoctrine()->getManager();
            $em->persist($menu);
            $em->flush();

            $this->addFlash(
                'notice',
                'Menu added.'
            );

            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'admin_menu_add'
                : 'admin_menu';

            return $this->redirectToRoute($nextAction);
        }

        return $this->render('admin/menu_form.html.twig', array(
            'form' => $form->createView(),
            'header' => 'Add New Menu',
        ));
    }

    /**
     * @Route("/admin/menu/edit/{id}/", name="admin_menu_edit", defaults={"id": 1})
     */
    public function editMenuAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $menu = $em->getRepository('AppBundle:Menu')->find($id);
        if (!$menu) {
            throw $this->createNotFoundException(
                'No menu found for id '.$id
            );
        }

        $form = $this->createForm(MenuType::class, $menu);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $request->request->get('menu');

            $menu->setTitle($data['title'])
                ->setAlias($this->get('app.misc_functions')->slug($data['title']))
            ;

            $em->persist($menu);
            $em->flush();

            $this->addFlash(
                'notice',
                'Menu modified.'
            );
        }

        return $this->render('admin/menu_form.html.twig', array(
            'form' => $form->createView(),
            'header' => 'Edit Menu',
        ));
    }

    /**
     * @Route("/admin/content/", name="admin_content")
     */
    public function adminContentAction(Request $request)
    {
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Content');

        if ($request->request->get('submit')) {
            $em = $this->getDoctrine()->getManager();
            $deleteList = $request->request->get('delete');
            foreach ($deleteList as $item)
            {
                $menu = $repository->find($item);
                $em->remove($menu);
                $em->flush();
            }

            $this->addFlash(
                'notice',
                'Content page(s) deleted.'
            );
        }

        $sortBy = $request->query->get('sort');
        if ($sortBy == '')
            $sortBy = 'id';
        $order = $request->query->get('order');
        if ($order == '')
            $order = 'ASC';
        $content = $repository->findBy([],array($sortBy => $order));

        return $this->render('admin/content.html.twig', array(
            'contents' => $content,
            'header' => 'Pages',
        ));
    }

    /**
     * @Route("admin/content/add/", name="admin_content_add")
     */
    public function newContentAction(Request $request)
    {
        $content = new Content();
        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->request->get('content');
            $alias = $this->get('app.misc_functions')->slug($data['title']);

            $tmp = new \DateTime('now');

            $content->setTitle($data['title'])
                ->setAlias($alias)
                ->setDateAdded($tmp)
                ->setDateModified($tmp)
                ->setHits(0);

            $em = $this->getDoctrine()->getManager();
            $em->persist($content);
            $em->flush();

            $this->addFlash(
                'info',
                'Content page added.'
            );

            return $this->redirectToRoute('admin_content');
        }

        return $this->render('admin/content_form.html.twig', array(
            'form' => $form->createView(),
            'header' => 'Add New Content Page',
        ));
    }

    /**
     * @Route("/admin/content/edit/{id}/", name="admin_content_edit", defaults={"id": 1})
     */
    public function editContentAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $content = $em->getRepository('AppBundle:Content')->find($id);
        if (!$content) {
            throw $this->createNotFoundException(
                'No content page found for id '.$id
            );
        }

        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $request->request->get('content');

            $tmp = new \DateTime('now');

            $content
                ->setDateModified($tmp);

            $em->persist($content);
            $em->flush();

            $this->addFlash(
                'info',
                'Page updated.'
            );

            return $this->redirectToRoute('admin_content');
        }

        return $this->render('admin/content_form.html.twig', array(
            'form' => $form->createView(),
            'header' => 'Update Page',
        ));
    }

    /**
     * @Route("/admin/menu/page/{id}/", name="admin_menu_page")
     */
    public function adminMenuPageAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $menu = $em->getRepository('AppBundle:Menu')->find($id);
        if (!$menu) {
            $this->addFlash(
                'notice',
                'Invalid menu.'
            );

            return $this->redirectToRoute('admin_menu');
        }

        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:MenuItem');

        if ($request->request->get('submit')) {
            $em = $this->getDoctrine()->getManager();
            $deleteList = $request->request->get('delete');
            foreach ($deleteList as $item)
            {
                if (strpos($item, '-') !== false) {
                    $item = explode('-',$item);
                    $menuItem = $repository->findOneBy(array('menuId' => $id, 'id' => $item[1]));
                }
                else
                    $menuItem = $repository->find($item);

                $em->remove($menuItem);
                $em->flush();
            }

            $this->addFlash(
                'notice',
                'Menu page(s) deleted.'
            );
        }

        $sortBy = $request->query->get('sort');
        if ($sortBy == '')
            $sortBy = 'sortOrder';
        $order = $request->query->get('order');
        if ($order == '')
            $order = 'ASC';
        $content = $repository->findBy(array('menuId' => $menu->getId(), 'parentId' => '0'),array($sortBy => $order));

        return $this->render('admin/menu_item.html.twig', array(
            'contents' => $content,
            'menu_id' => $id,
            'header' => $menu->getTitle() .' - Pages',
        ));
    }

    /**
     * @Route("admin/menu/page/add/{id}", name="admin_menu_page_add")
     */
    public function newMenuPageAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:MenuItem');

        $menuItem = new MenuItem();
        $menuItem->setMenuId($id);
        $form = $this->createForm(MenuItemType::class, $menuItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->request->get('menu_item');
            ($data['parent_id'] == '') ? $parent_id = 0 : $parent_id = $data['parent_id'];
            $sort_order = $repository->findBy(array('menuId' => $id, 'parentId' => $parent_id));
            $sort_order = count($sort_order);

            $menuItem->setTitle($data['title'])
                ->setAlias($this->get('app.misc_functions')->slug($data['title']))
                ->setSortOrder($sort_order)
                ->setParentId($parent_id)
            ;

            $em = $this->getDoctrine()->getManager();
            $em->persist($menuItem);
            $em->flush();

            $this->addFlash(
                'notice',
                'Menu Item added.'
            );

            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'admin_menu_page_add'
                : 'admin_menu_page';

            return $this->redirectToRoute($nextAction, array('id' => $id));
        }

        return $this->render('admin/menu_item_form.html.twig', array(
            'form' => $form->createView(),
            'menu_id' => $id,
            'header' => 'Add New Menu Item',
        ));
    }

    /**
     * @Route("admin/menu/page/edit/{menu_id}/{id}/", name="admin_menu_page_edit")
     */
    public function editMenuPageAction(Request $request, $menu_id, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $menuItem = $em->getRepository('AppBundle:MenuItem')->find($id);

        $sortOrder = $menuItem->getSortOrder();
        $menuItem->setMenuId($menu_id);
        $form = $this->createForm(MenuItemType::class, $menuItem);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->request->get('menu_item');
            (@$data['parent_id'] == null) ? $parent_id = 0 : $parent_id = $data['parent_id'];

            if ($sortOrder != $data['sort_order'])
            {
                // update all other menu items' sort order +1
                $query = $em->createQuery(
                    'SELECT p
                        FROM AppBundle:MenuItem p
                        WHERE p.parentId = '. $parent_id .' AND p.id <> '. $id .' AND p.sortOrder >= '. $data['sort_order']);
                $menuItems = $query->getResult();

                foreach ($menuItems as $item) {
                    $sortOrder = $item->getSortOrder();
                    $item->setSortOrder(($sortOrder+1));
                }
            }

            $menuItem->setTitle($data['title'])
                ->setAlias($this->get('app.misc_functions')->slug($data['title']))
                ->setParentId($parent_id);

            $em = $this->getDoctrine()->getManager();
            $em->persist($menuItem);
            $em->flush();

            $this->addFlash(
                'notice',
                'Menu Item modified.'
            );

            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'admin_menu_page_add'
                : 'admin_menu_page';

            return $this->redirectToRoute($nextAction, array('id' => $menu_id));
        }

        return $this->render('admin/menu_item_form_edit.html.twig', array(
            'form' => $form->createView(),
            'menu_item' => $menuItem,
            'header' => 'Edit New Menu Item',
        ));
    }

    /**
     * @Route("admin/menu/list_page_type", name="ajax_page_type_items")
     */
    public function ajaxListPageTypeItems(Request $request)
    {
        $pageType = $request->request->get('id');
        $pageTypeId = $request->request->get('id2');

        switch ($pageType)
        {
            case 'text-separator':
                $template = 'text_separator_list';
                $id = 'menu_item_page_type_id';
                $name = 'menu_item[page_type_id]';
                break;
            case 'content':
                $entity = 'Content';
                $template = 'pagetype_list';
                break;
            case 'properties-category':
                $entity = 'Category';
                $template = 'property_categories';
                break;
            default:
            case 'url':
            case 'route':
                $template = 'text_input';
                $id = 'menu_item_page_type_id';
                $name = 'menu_item[page_type_id]';
                break;
        }

        if (!isset($entity))
            return $this->render('admin/ajax_'. $template .'.html.twig', array(
                'id' => $id,
                'name' => $name,
                'id2' => $pageTypeId
            ));
        else {
            $repository = $this->getDoctrine()
                ->getRepository('AppBundle:'. $entity);
            $items = $repository->findBy([]);

            return $this->render('admin/ajax_'. $template .'.html.twig', array(
                'items' => $items,
                'id2' => $pageTypeId
            ));
        }
    }

    /**
     * @Route("admin/menu/list_parent_items", name="ajax_parent_items")
     */
    public function ajaxListParentItems(Request $request)
    {
        $parentId = $request->request->get('id');
        $sort_order = $request->request->get('id2');

        if ($parentId == '')
            $parentId = 0;

        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:MenuItem');
        $items = $repository->findBy(array('parentId' => $parentId), array('sortOrder' => 'ASC'));

        return $this->render('admin/ajax_parent_items.html.twig', array(
            'items' => $items,
            'sort_order' => $sort_order
        ));
    }

    public  function menuItemChildrenAction(Request $request, $menu_id, $parent){
        $sortBy = $request->request->get('sort');
        if ($sortBy == '')
            $sortBy = 'sortOrder';
        $order = $request->request->get('order');
        if ($order == '')
            $order = 'ASC';

        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:MenuItem');
        $items = $repository->findBy(array('menuId' => $menu_id, 'parentId' => $parent),array($sortBy => $order));

        return $this->render('admin/menu_item_parent_items.html.twig', array(
            'items' => $items,
            'menu_id' => $menu_id
        ));
    }

    /**
     * @Route("/{alias}", name="_custom_route", requirements={"alias"=".+"})
     */
    public function customRouteAction($alias)
    {
        $em = $this->getDoctrine()->getManager();
        $alias = explode('/',$alias);
        $slug = end($alias);
        $menu = $em->getRepository('AppBundle:MenuItem');
        $route = $menu->findOneBy(array('alias' => $slug));

        if (count($route) == 0)
        {
            $this->addFlash(
                'danger',
                'Requested page does not exist or is no longer available.'. $slug
            );

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
                $this->addFlash(
                    'danger',
                    'Requested page does not exist or is no longer available.'. $slug
                );

                return $this->redirectToRoute('homepage');
        }

        return $this->render('default/'. $template .'.html.twig', $contentParts);
    }
}