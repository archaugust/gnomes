<?php 
namespace ShopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JasonGrimes\Paginator;
use AppBundle\Entity\User;
use AppBundle\Form\UserAdminType;

class ArchCustomerController extends Controller
{
	
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
			
			if ($form->isSubmitted()) {
				if ($form->isValid()) {
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
						$this->get('fos_user.mailer')->sendConfirmationEmailMessage($user);
					}
				}
				else
					$this->addFlash('danger', 'ERROR: Please check your input.');
			}
			
			
			// delete
			$data = $request->request->all();
			if (!empty($data['customers'])) {
				$now = \DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
				foreach ($data['customers'] as $customer) {
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
		if (!empty($data['query'])) {
			$filters[$data['query']] = array(
					'label' => $data['query'],
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
		$repository = $em->getRepository('AppBundle:User');
		$customer = $repository->findOneBy(array('customer_id' => $id));
		$current_email = $customer->getEmail();
		
		if ($customer == null)
			return new Response('ERROR: Customer does not exist.');
			
		$form = $this->createForm(UserAdminType::class, $customer);
		
		if ($request->isMethod('POST')) {
			// delete
			if (!empty($delete = $request->request->get('delete')))
				if ($customer->getCustomerId() == $delete) {
					$this->addFlash('info', "Customer '". $customer->getFullName() ."' deleted.");
					$customer->setDeletedAt(new \DateTime('now'));
					
					$em->flush();
					
					return $this->redirectToRoute('admin_customer');
				}
			
			// update
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				// entitytype to text dirty fix
				$data = $request->request->get('user_admin');
				$customer
				->setPhysicalCountryId($data['physical_country_id'])
				->setPostalCountryId($data['postal_country_id'])
				;
				
				// check for email duplicates
				if ($current_email != $customer->getEmail()) {
					$duplicate = $repository->findBy(array('email' => $customer->getEmail()));
					if ($duplicate != null)
						$this->addFlash('danger', 'ERROR: Email address already in use.');
				}
				else {
					// post to Vend
					
					$this->addFlash('info', 'Customer info updated.');
					$em->flush();
				}
			}
		}
		
		return $this->render('admin/'. $mode .'customers-view.html.twig', array(
				'item' => $customer,
				'form' => $form->createView(),
		));
	}
}
?>