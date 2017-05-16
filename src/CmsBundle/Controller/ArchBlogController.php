<?php 
namespace CmsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use CmsBundle\Entity\ArchBlog;
use CmsBundle\Form\ArchBlogType;
use CmsBundle\Form\ArchBlogMainType;

class ArchBlogController extends Controller
{
	/**
	 * @Route("/admin/blog/{mode}", name="admin_blog") 
	 */
	public function blog(Request $request, $mode = '') {
		// mode can be used to switch template to ajax
		$data = $request->query->all();
		
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('CmsBundle:ArchBlog');
		
		$item = new ArchBlog();
		$form = $this->createForm(ArchBlogType::class, $item);

		$blog = $em->getRepository('CmsBundle:ArchBlogMain')->find(1);;
		$blog_form = $this->createForm(ArchBlogMainType::class, $blog);
		$old_blog_image = $blog->getFilename();
		
		// post = add or delete
		if ($request->isMethod('POST')) {
			$miscFunctions = $this->get('app.misc_functions');
			
			// list actions
			$data = $request->request->all();
			if (!empty($data['items'])) {
				$list_action = $data['list_action'];
				
				if ($list_action == 'delete') {
					foreach ($data['items'] as $item) {
						$item = $items->findOneBy(array('id' => $item));
						$em->remove($item);
					}
				}
				else {
					$status = $list_action == 'disable' ? 0 : 1;
					
					foreach ($data['items'] as $item) {
						$item = $items->findOneBy(array('id' => $item));
						$item->setIsActive($status);
					}
				}
			}
			
			// update blog main
			$blog_form->handleRequest($request);
			if ($blog_form->isSubmitted() && $blog_form->isValid()) {
				if ($blog->getFilename() != null) {
					// process image
					$filename = $this->get('app.image_functions')->upload('banner', 'blog', 'blog', $blog->getFilename());
				}
				else 
					$filename = $old_blog_image;
				
				$blog->setFilename($filename);
				
				$this->addFlash('info', 'Blog updated.');
			}
				
			// add blog page
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				$imageFunctions = $this->get('app.image_functions');
				$handle = $miscFunctions->slug($item->getName());
				
				// check for duplicate handles
				$ctr = 2;
				$handle_tmp = $handle;
				while (count($items->findBy(array('handle' => $handle_tmp))) > 0) {
					$handle_tmp = $handle  .'-'. $ctr;
					$ctr++;
				}
				if ($handle_tmp != $handle) 
					$handle = $handle_tmp;
				
				// process images
				if ($item->getImageMain() != null) {
					$image_main = $imageFunctions->upload('blog', 'blog', $handle, $item->getImageMain());
					$item->setImageMain($image_main);
				}
				if ($item->getImageMiddle() != null) {
					$image_middle = $imageFunctions->upload('blog_subimage', 'blog', $handle, $item->getImageMiddle());
					$item->setImageMiddle($image_middle);
				}
				
				$item->setHandle($handle);
				
				$em->persist($item);
				
				$this->addFlash('info','Blog page added.');
				
				$em->flush();
				
				return $this->redirectToRoute('admin_blog');
			}
			$em->flush();
		}
		
		return $this->render('cms/'. $mode .'blog.html.twig', array(
			'blog' => $blog,
			'blog_form' => $blog_form->createView(),
			'items' => $items->findBy(array(), array('display_date' => 'DESC')),
			'form' => $form->createView(),
		));
	}

	/**
	 * @Route("/admin/blog-edit/{id}", name="admin_blog_edit")
	 */
	public function blogEdit(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('CmsBundle:ArchBlog');
		if (($item = $items->findOneBy(array('id' => $id, 'is_active' => 1))) == null) {
			$this->addFlash('danger', 'Blog page not found or deleted.');
			
			return $this->redirectToRoute('admin_blog');
		}

		$originalName = $item->getName();
		$originalImageMain = $item->getImageMain();
		$originalImageMiddle = $item->getImageMiddle();
		
		$form = $this->createForm(ArchBlogType::class, $item);
		
		// update
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$handle = $item->getHandle();
			
			// check for duplicate handles
			$ctr = 2;
			$handle_tmp = $handle;
			while (count($items->findBy(array('handle' => $handle_tmp))) > 1) { // 1 = counts old handle
				$handle_tmp = $handle  .'-'. $ctr;
				$ctr++;
			}
			if ($handle_tmp != $handle)
				$handle = $handle_tmp;
				
			// images
			$imageFunctions = $this->get('app.image_functions');
			if ($item->getImageMain()!= null) {
				$image_main = $imageFunctions->upload('blog', 'blog', $item->getHandle(), $item->getImageMain());
				$item->setImageMain($image_main);
			}
			else
				$item->setImageMain($originalImageMain);

			if ($item->getImageMiddle()!= null) {
				$image_middle = $imageFunctions->upload('blog_subimage', 'blog', $item->getHandle(), $item->getImageMiddle());
				$item->setImageMiddle($image_middle);
			}
			else
				$item->setImageMiddle($originalImageMiddle);

			// delete original image is name changed
			if ($item->getName() != $originalName) {
				$imageFunctions->deleteImage($originalImageMain, 'blog/thumb');
				$imageFunctions->deleteImage($originalImageMain, 'blog/main');
				$imageFunctions->deleteImage($originalImageMiddle, 'blog/middle');
			}
			
			$item
				->setHandle($handle)
				->setUpdatedAt(new \DateTime("now"))
			;
				
			$em->flush();
			$this->addFlash('info', 'Blog page updated.');
		}
			
		return $this->render('cms/blog-edit.html.twig', array(
				'item' => $item,
				'form' => $form->createView(),
		));
	}
}
?>