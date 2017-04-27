<?php 
namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JasonGrimes\Paginator;

class ArchSalesController extends Controller
{
	/**
	 * @Route("/admin/sales/{mode}", name="admin_sales")
	 */
	public function sales(Request $request, $mode = '') {
		$em = $this->getDoctrine()->getManager();
		$session = $this->get('session');

		// get parameters
		$data = $request->query->all();
		
		// sort order
		$allowed_fields = array('o.sale_date', 'u.last_name', 'o.invoice_number', 'o.user', 'o.status', 'o.total_price');
		
		if ($session->get('sort') != null) {
			$sort = $session->get('sort');
			if (!in_array($sort['name'], $allowed_fields))
				$sort = array('name'=>'o.id','order'=>'desc');
		}
		else 
			$sort = array('name'=>'o.id','order'=>'desc');
		
		if (isset($data['sort'])) {
			if (in_array($data['sort'], $allowed_fields))
				$sort = array(
						'name' => $data['sort'],
						'order' => (isset($data['order']) && in_array(@$data['order'], array('asc','desc'))) ? $data['order'] : 'asc'
				);
		}
		
		$session->set('sort', $sort);
		
		// filters
		$filters = $session->get('filters_sales') != null ? $session->get('filters_sales') : array();
		
		// textbox filter = full_name
		if (!empty($data['query'])) {
			$filters['full_name'] = array(
					'label' => '',
					'field' => 'u.full_name',
					'field_np' => 'full_name',
					'operator' => 'LIKE',
					'value' => '%'. $data['query'] .'%'
			);
		}
		
		// dropdown filters
		if (!empty($data['date_from'])) {
			$filters['date_from'] = array(
					'label' => 'Dated after '. $data['date_from'],
					'field' => 'o.sale_date',
					'field_np' => 'sale_date_from',
					'operator' => '>=',
					'value' => $data['date_from'],
			);
		}
		if (!empty($data['date_to'])) {
			$filters['date_to'] = array(
					'label' => 'Dated before '. $data['date_to'],
					'field' => 'o.sale_date',
					'field_np' => 'sale_date_to',
					'operator' => '<',
					'value' => $data['date_to'],
			);
		}
		
		// if remove filter
		if (isset($data['remove']))
			unset($filters[$data['remove']]);
			
		$session->set('filters_sales', $filters);
			
		$where = array();
		
		foreach ($filters as $filter)
			$where[] = $filter['field'] .' '. $filter['operator'] .' :'. $filter['field_np'];

		$where = (count($where) > 0) ? 'WHERE '. implode(' AND ', $where) : '';
		
		$query = $em->createQuery(
				'SELECT o, u
			    FROM AppBundle:ArchOrder o
				JOIN o.customer u 
				'. $where .'
			    ORDER BY '. implode(' ', $sort)
		);
		foreach ($filters as $filter)
			$query->setParameter($filter['field_np'], $filter['value']);
				
		$items = $query->getResult();
				
		// pagination
		!empty($data['page']) ? $pagenum = $data['page'] : $pagenum = 1;
		$totalItems = count($items);
				
		$itemsPerPage = 50;
		$urlPattern = $request->getPathInfo() .'?page=(:num)';
		$paginator = new Paginator($totalItems, $itemsPerPage, $pagenum, $urlPattern);
		
		return $this->render('admin/'. $mode .'sales.html.twig', array(
				'items' => array_slice($items, ($pagenum - 1) * $itemsPerPage, $itemsPerPage),
				'paginator' => $paginator,
		));
	}
	
	/**
	 * @Route("/admin/sales/view/{id}", name="admin_sales_view")
	 */
	public function salesView($id) {
		$order = $this->getDoctrine()->getRepository('AppBundle:ArchOrder')->findOneBy(array('id'=>$id));
		if (is_null($order)) 
			return new Response('ERROR: Sale ID does not exist.');
			
		return $this->render('admin/order-view.html.twig', array(
				'item' => $order
		));
	}
	
	/** 
	 * @Route("/admin/register-sales", name="admin_register_sales")
	 */
	public function registerSales(Request $request) {
		{
			// post to Vend
			$now = date("Y-m-d H:i:s");
//			$input = $request->request->get('arch_tax');
//			$rate = (int)$input['rate'] / 100;
			$data = array(
					'register_id' => '060f02b1-c8a3-11e7-e913-139bb3ed4fb3',
					'customer_id' => '060f02b1-c84d-11e7-e913-18dbcba7be7b',
					'sale_date' => $now,
					"user_name" => "website",
					"total_price" => 20, // w/o tax
					"total_tax" => 3,
					"tax_name" => "GST",
					"status" => "CLOSED",
					"invoice_number" => "26",
					"invoice_sequence" => 26,
					"note" => null,
					"register_sale_products" => array(
							array(
									"product_id" => "060f02b1-c84d-11e7-e913-139bb44893d5",
									"quantity" => 1,
									"price" => 10,
									"discount" => 110,
									"tax" => 1.5,
									"tax_id" => "060f02b1-c8a3-11e7-e913-139bb3e351c9",
									"tax_total" => 1.5
							),
							array(
									"product_id" => "060f02b1-c84d-11e7-e913-139bb480e6ab",
									"quantity" => 1,
									"price" => 10,
									"discount" => 55,
									"tax" => 1.5,
									"tax_id" => "060f02b1-c8a3-11e7-e913-139bb3e351c9",
									"tax_total" => 1.5,
							)
						),
					"register_sale_payments" => array(
							array(
									"retailer_payment_type_id" => "060f02b1-c8a3-11e7-e913-139bb3ee4465",
									"payment_date" => $now,
									"amount" => 23
							)
						)
					);
			$url = 'register_sales';
			
			$result = $this->get('app.vend')->postVend($url, $data);
			
			if ($result == null) {
				$this->addFlash(
						'danger',
						'ERROR: Unable to post to Vend API.'
						);
				dump($result);die;
				die;
				return $this->redirectToRoute('admin_tax');
			}
			
			dump($result);die;
		}
	}
}
?>