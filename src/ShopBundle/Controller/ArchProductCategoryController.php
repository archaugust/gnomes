<?php 
namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\ArchProductCategory;
use AppBundle\Form\ArchProductCategoryType;
use Doctrine\Common\Collections\ArrayCollection;

class ArchProductCategoryController extends Controller
{
	
	/**
	 * @Route("/admin/category", name="admin_category")
	 */
	public function category(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchProductCategory');
		
		$item = new ArchProductCategory();
		$form = $this->createForm(ArchProductCategoryType::class, $item);
		
		$form->handleRequest($request);
		if ($request->isMethod('POST')) {
			// delete
			$data = $request->request->all();
			if (!empty($data['items'])) {
				$list_action = $data['list_action'];
				
				if ($list_action == 'delete') {
					foreach ($data['items'] as $item) {
						$item = $items->findOneBy(array('id' => $item));
						
						foreach($item->getCollections() as $collection)
							$em->remove($collection);
							
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
				
				$em->flush();
			}
			
			// add
			if ($form->isSubmitted() && $form->isValid()) {

				// collections
				foreach ($item->getCollections() as $collection) {
					$collection->setCategory($item);
					$em->persist($collection);
				}
				
				$em->persist($item);
				
				$em->flush();
				
				$this->addFlash(
					'info',
					'Product Category added.'
				);
				
				return $this->redirectToRoute('admin_category');
			}
		}
		
		return $this->render('admin/categories.html.twig', array(
				'form' => $form->createView(),
				'items' => $items->findBy(array(), array('sort_order' => 'ASC')),
		));
	}
	
	/**
	 * @Route("/admin/category-edit/{id}", name="admin_category_edit")
	 */
	public function categoryEdit(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository('AppBundle:ArchProductCategory')->findOneBy(array('id' => $id));
		
		if ($item == null) {
			$this->addFlash('danger', 'ERROR: Category does not exist.');
			
			return $this->redirectToRoute('admin_category');
		}
		
		$originalCollections = new ArrayCollection();
		
		foreach ($item->getCollections() as $collection) 
			$originalCollections->add($collection);
		
		
		$form = $this->createForm(ArchProductCategoryType::class, $item);
		$form->handleRequest($request);
		
		// edit
		if ($form->isSubmitted() && $form->isValid()) {
			// collections
			$collections = $item->getCollections();
			
			if (count($collections) > 0) {
				foreach ($collections as $collection) {
					if (!is_null($collection->getCollection())) {
						
						$collection
							->setCategory($item);
						$em->persist($collection);
					}
				}
			}
			
			// remove collection
			foreach ($originalCollections as $collection) {
				if (false === $item->getCollections()->contains($collection)) {
					$item->getCollections()->removeElement($collection);
					$em->remove($collection);
				}
			}
			
			
			$em->flush();
		
			$this->addFlash(
				'info',
				'Category updated.'
			);
		}
		
		return $this->render('admin/categories-edit.html.twig', array(
				'form' => $form->createView(),
				'item' => $item,
		));
	}
}
?>