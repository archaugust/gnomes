<?php 
namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;
use AppBundle\Entity\ArchTax;
use AppBundle\Entity\User;
use AppBundle\Form\ArchTaxType;
use AppBundle\Form\UserAdminType;
use JasonGrimes\Paginator;

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

	/**
	 * @Route("/admin/product/{mode}", name="admin_product") 
	 */
	public function product(Request $request, $mode = '') {
		// mode can be used to switch template to ajax
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchProduct');
		
		return $this->render('admin/'. $mode .'products.html.twig', array(
			'items' => $items->findAll()
		));
	}

	/**
	 * @Route("/admin/product/view/{id}", name="admin_product_view")
	 */
	public function productView(Request $request, $id) {
		// mode can be used to switch template to ajax
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchProduct');
		
		return $this->render('admin/products.html.twig', array(
				'items' => $items->findAll()
		));
	}
	
	/**
	 * @Route("/admin/customer/{mode}", name="admin_customer")
	 */
	public function customer(Request $request, $mode = '') {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:User');
		$session = $this->get('session');
		
		$user = new User();
		$form = $this->createForm(UserAdminType::class, $user);
		$form->handleRequest($request);
		
		// post = add or delete
		if ($request->isMethod('POST')) {
			
			if ($form->isSubmitted() && $form->isValid()) {
				$input = $request->request->get('user_admin');
				
				// check if email exists
				$duplicate = $items->findOneBy(array('email' => $input['email']));
				
				if (count($duplicate) > 0) 
					$this->addFlash('danger', 'ERROR: User email already in use.');
				else 
				{
					// post to Vend
					$data = array(
							'customer_code' => $input['email'],
							'company_name' => '',
							'date_of_birth' => $input['date_of_birth'],
							'sex' => $input['sex'],
							"first_name" => $input['first_name'],
							"last_name" => $input['last_name'],
							"phone" => $input['phone'],
							"mobile" => $input['mobile'],
							"fax" => $input['fax'],
							"email" => $input['email'],
							"website" => $input['website'],
							"physical_address1" => $input['physical_address1'],
							"physical_address2" => $input['physical_address2'],
							"physical_suburb" => $input['physical_suburb'],
							"physical_city" => $input['physical_city'],
							"physical_postcode" => $input['physical_postcode'],
							"physical_state" => $input['physical_state'],
							"physical_country_id" => $input['physical_country_id'],
							"postal_address1" => $input['postal_address1'],
							"postal_address2" => $input['postal_address2'],
							"postal_suburb" => $input['postal_suburb'],
							"postal_city" => $input['postal_city'],
							"postal_postcode" => $input['postal_postcode'],
							"postal_state" => $input['postal_state'],
							"postal_country_id" => $input['postal_country_id'],
					);
	
					$url = 'customers';
					
					$result = $this->get('app.vend')->postVend($url, $data);
					
					if ($result == null) {
						$this->addFlash(
								'danger',
								'ERROR: Unable to post to Vend API.'
								);
						return $this->redirectToRoute('admin_customer');
					}

					$password_raw = $this->get('app.misc_functions')->generatePassword();
					$factory = $this->get('security.encoder_factory');
					
					$encoder = $factory->getEncoder($user);
					$password = $encoder->encodePassword($password_raw, $user->getSalt());
					
					$result = $result->customer;
					
					$user
						->setCustomerId($result->id)
						->setFullName($input['first_name'] .' '. $input['last_name'])
						->setUpdatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', $result->updated_at))
						->setUsername($result->email)
						->setPassword($password)
						->setEmail($result->email)
						->setCustomerCode($result->customer_code)
						->setPhysicalCountryId($input['physical_country_id'])
						->setPostalCountryId($input['postal_country_id'])
						->setAccountType('Customer')
					;
					
					$em->persist($user);
					$em->flush();
					
					$this->addFlash(
							'info',
							'Customer added.'
							);
					
					// email customer
				}
			}
			
			if ($form->isSubmitted() && !$form->isValid())
				$this->addFlash('danger', 'ERROR: Please check your input.');
			
			// delete
			$data = $request->request->all();
			if (isset($data['customers'])) {
				$now = \DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
				foreach ($customers as $customer) {
					$user = $items->findOneBy(array('id' => $customer));
					$user->setDeletedAt($now);
				}
				
				$em->flush();
			}
		}
		// get parameters
		$data = $request->query->all();
		
		// sort order
		$allowed_fields = array('last_name', 'physical_city', 'customer_code', 'total_spent', 'order_count');

		if ($session->get('sort') != null) {
			$sort = $session->get('sort');
			if (!in_array($sort['name'], $allowed_fields))
				$sort = array('name'=>'id','order'=>'desc');
		}
		else
			$sort = array('name'=>'id','order'=>'desc');
			
		if (isset($data['sort'])) {
			if (in_array($data['sort'], $allowed_fields))  
				$sort = array(
						'name' => $data['sort'],
						'order' => (isset($data['order']) && in_array(@$data['order'], array('asc','desc'))) ? $data['order'] : 'asc'
				);
		}
		
		$session->set('sort', $sort);
		
		// filters
		$filters = $session->get('filters_customer') != null ? $session->get('filters_customer') : array();
		
		// textbox filter = full_name
		if (isset($data['query'])) {
			$filters['full_name'] = array(
					'label' => '',
					'field' => 'full_name',
					'operator' => 'LIKE',
					'value' => '%'. $data['query'] .'%'
			);
		}
		
		// dropdown filters
		if (!empty($data['f']) && !empty($data['o']) && isset($data['v'])) {
			$allowed_fields = array('total_spent', 'order_count', 'updated_at', 'enabled', 'accepts_marketing');
			if (in_array($data['f'], $allowed_fields)) {
				
				// field
				$field = $data['f'];
				
				// operator
				switch ($data['o']) {
					case 'greater than':
						$operator = '>';
						break;
					case 'less than':
						$operator = '<';
						break;
					case 'not equal to':
						$operator = '<>';
						break;
					default:
					case 'equal to':
						$operator = '=';
						break;
				}
				
				// value
				$value =  $data['v'] == 'updated_at' ? \DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', $data['v'])) : (int)$data['v'];
				
				// label
				switch ($field) {
					case 'total_spent':
						$label = 'Money spent is '. $data['o'] .' '. $data['v'];
						break;
					case 'order_count':
						$label = 'Number of orders is '. $data['o'] .' '. $data['v'];
						break;
					case 'updated_at':
						$label = 'Date created is after '. date('Y-m-d', $data['v']);
						break;
					case 'enabled':
						$label = ($value == 1 ? 'Account status is enabled' : 'Account status is unconfirmed/disabled');
						break;
					case 'accepts_marketing':
						$label = ($value == 1 ? 'Customer accepts marketing' : 'Customer does not accept marketing');
						break;
				}
				
				$filters[$label] = array(
						'label' => $label,
						'field' => $field,
						'operator' => $operator,
						'value' => $value,
				);
			}
		}
		
		// if remove filter
		if (isset($data['remove'])) 
			unset($filters[$data['remove']]);

		$session->set('filters_customer', $filters);
		
		$where = '';
		foreach ($filters as $filter) 
			$where .= ' AND i.'. $filter['field'] .' '. $filter['operator'] .' :'. $filter['field'];
		
		$items = $items->createQueryBuilder('i')
				->where('i.account_type = :account_type AND i.deleted_at IS NULL'. $where)
				->setParameter('account_type', 'Customer')
				->orderBy('i.'. $sort['name'], $sort['order']);
		
		foreach ($filters as $filter)
			$items->setParameter($filter['field'], $filter['value']);
		
		$items = $items
				->getQuery()
				->getResult();
		
		// pagination
		!empty($data['page']) ? $pagenum = $data['page'] : $pagenum = 1;
		$totalItems = count($items);
		
		$itemsPerPage = 50;
		$urlPattern = $request->getPathInfo() .'?page=(:num)';
		$paginator = new Paginator($totalItems, $itemsPerPage, $pagenum, $urlPattern);
		
		return $this->render('admin/'. $mode .'customers.html.twig', array(
				'items' => array_slice($items, ($pagenum - 1) * $itemsPerPage, $itemsPerPage),
				'paginator' => $paginator,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * @Route("/admin/customer-view/{id}/{mode}", name="admin_customer_view")
	 */
	public function customerView(Request $request, $id, $mode = '') {
		$em = $this->getDoctrine()->getManager();
		$customer = $em->getRepository('AppBundle:User')->findOneBy(array('customer_id' => $id));

		if ($customer == null) 
			return new Response('ERROR: Customer does not exist.');
		
		// set session variable for passing to Vend reloader
		$this->get('session')->set('customer_id', $customer->getCustomerId());
		
		$form = $this->createForm(UserAdminType::class, $customer);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			$this->addFlash('info', 'Customer info updated.');
			
			// entitytype to text dirty fix
			$data = $request->request->get('user_admin');
			$customer
				->setPhysicalCountryId($data['physical_country_id'])
				->setPostalCountryId($data['postal_country_id'])
			;
			
			$em->flush();
		}
		
		return $this->render('admin/'. $mode .'customers-view.html.twig', array(
				'item' => $customer,
				'form' => $form->createView(),
		));
	}
	
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
	/**
	 * @Route("/admin/settings/{mode}", name="admin_settings")
	 */
	public function settings(Request $request, $mode = 'general') {
		$em = $this->getDoctrine()->getManager();
		$config = $em->getRepository('AppBundle:ArchConfig');
		
		if ($request->isMethod('POST')) {
			// save changes
			$post = $request->request->all();
			foreach($post as $key => $value) 
				$config->findOneBy(array('name' => $key))->setValue($value);
			
			$em->flush();
			
			$this->addFlash('info', 'Settings saved.');
		}
		
		$config = $config->createQueryBuilder('c')->where('c.name LIKE :name');
		switch ($mode) {
			case 'vend-api':
				$title = 'Vend API';
				$config = $config
				->setParameter('name', 'vend_%')
				->getQuery()
				->getResult();
				break;
			default:
			case 'general':
				$title = 'General';
				$config = $config
				->setParameter('name', 'gen_%')
				->getQuery()
				->getResult();
				break;
		}
		
		return $this->render('admin/settings.html.twig', array(
				'mode' => $mode,
				'title' => $title,
				'config' => $config
		));
	}
	
	/**
	 * @Route("/admin/tax/{mode}", name="admin_tax")
	 */
	public function tax(Request $request, $mode = '') {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchTax');
		$default = $items->findBy(array('is_default' => 1));
		
		$tax = new ArchTax();
		$form = $this->createForm(ArchTaxType::class, $tax);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			// post to Vend
			$input = $request->request->get('arch_tax');
			$rate = (int)$input['rate'] / 100;
			$data = array(
					'name' => $input['name'],
					'rate' => $rate
			);
			$url = 'taxes';

			$result = $this->get('app.vend')->postVend($url, $data);

			if ($result == null) {
				$this->addFlash(
						'danger', 
						'ERROR: Unable to post to Vend API.'
				);
				return $this->redirectToRoute('admin_tax');
			}
			
			$tax
				->setId($result->id)
				->setRate($rate)
				->setIsActive($result->active)
				->setIsDefault($result->default)
			;
			
			$em->persist($tax);
			$em->flush();
			
			$this->addFlash(
					'info',
					'Sales Tax added.'
			);
		}
		
		if ($form->isSubmitted() && !$form->isValid()) 
			$this->addFlash('danger', 'ERROR: Please check your input.');

		return $this->render('admin/'. $mode .'taxes.html.twig', array(
				'form' => $form->createView(),
				'items' => array_reverse($items->findAll()),
				'default' => count($default)
		));
	}
	
	/**
	 * @Route("/admin/outlet/{mode}", name="admin_outlet")
	 */
	public function outlet(Request $request, $mode = '') {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchOutlet');
		$default = $items->findBy(array('is_default' => 1));

		return $this->render('admin/'. $mode .'outlets.html.twig', array(
				'items' => $items->findAll(),
				'default' => count($default),
		));
	}
	
	/**
	 * @Route("/admin/register/{mode}", name="admin_register")
	 */
	public function register(Request $request, $mode = '') {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchRegister');
		$default = $items->findBy(array('is_default' => 1));
		
		return $this->render('admin/'. $mode .'registers.html.twig', array(
				'items' => $items->findAll(),
				'default' => count($default),
		));
	}
	
	/**
	 * @Route("/admin/payment-type/{mode}", name="admin_payment_type")
	 */
	public function paymentType(Request $request, $mode = '') {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository('AppBundle:ArchPaymentType');
		$default = $items->findBy(array('is_default' => 1));
		
		return $this->render('admin/'. $mode .'payment_types.html.twig', array(
				'items' => $items->findAll(),
				'default' => count($default),
		));
	}
	
	/**
	 * @Route("/admin/shipping", name="admin_shipping")
	 */
	public function shipping(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$products = $em->getRepository('AppBundle:ArchProduct');
		
		return $this->render('admin/shipping.html.twig', array(
		));
	}
}
?>