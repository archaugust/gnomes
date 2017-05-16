<?php
namespace ShopBundle;

class Vend
{
	protected $em, $vendToken, $vendURL;
	
	public function __construct(\Doctrine\ORM\EntityManager $em)
	{
		$this->em = $em;
		
		// vendURL 
		$config = $em->getRepository('AppBundle:ArchConfig');
		$this->vendURL = 'https://'. $config->findOneBy(array('name' => 'vend_prefix'))->getValue() .'.vendhq.com/api';

		// vendToken
		$now = time();
		$access_token = $config->findOneBy(array('name' => 'vend_access_token'));
		$access_token_expiry = $config->findOneBy(array('name' => 'vend_access_token_expiry'));
		
		// refresh Vend access token if expired
		if ($now > (int)($access_token_expiry->getValue())) {
			$data = array(
					'refresh_token' => $config->findOneBy(array('name' => 'vend_refresh_token'))->getValue(),
					'client_id' => $config->findOneBy(array('name' => 'vend_client_id'))->getValue(),
					'client_secret' => $config->findOneBy(array('name' => 'vend_client_secret'))->getValue(),
					'grant_type' => 'refresh_token',
			);
			
			$options = array(
					'http' => array(
							'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
							'method'  => 'POST',
							'content' => http_build_query($data)
					)
			);
			
			$context  = stream_context_create($options);
			$result = file_get_contents($this->vendURL .'/1.0/token', false, $context);
			
			if ($result === false)
				die('Error refreshing Vend API access token.');
				
			$result = json_decode($result);
			
			$access_token->setValue($result->access_token);
			// add 400 second allowance to avoid token expiring while running tasks
			$access_token_expiry->setValue((int)($result->expires) - 3600); 
			
			// change refresh_token if added to response
			if (isset($result->refresh_token)) {
				$refresh_token = $config->findOneBy(array('name' => 'vend_refresh_token'));
				$refresh_token->setValue($result->refresh_token);
			}
			
			$em->flush();
			
			$access_token = $result->access_token;
		}
		else
			$access_token = $access_token->getValue();
			
		$this->vendToken = $access_token;
	}
	
	public function getToken() {
		return $this->vendToken;
	}
	
	public function postVend($url, $data) {
		$options = array(
			'http' => array(
					'header'  => 
						"Authorization: Bearer " . $this->vendToken ."\r\n".
						"Content-type: application/json\r\n".
						"Accept: application/json\r\n",
					'method'  => 'POST',
					'content' => json_encode($data)
			)
		);
		
		$context  = stream_context_create($options);
		@$result = file_get_contents($this->vendURL .'/'. $url, false, $context);

		return json_decode($result);
	}
	
	public function postVendWebhook($url, $data) {
		$data = array('data' => json_encode($data));
		
		$options = array(
				'http' => array(
					'header'  =>
						"Authorization: Bearer " . $this->vendToken ."\r\n".
						"Content-type: application/x-www-form-urlencoded\r\n",
						'method'  => 'POST',
						'content' => http_build_query($data)
				)
		);
		
		$context  = stream_context_create($options);
		@$result = file_get_contents($this->vendURL .'/'. $url, false, $context);
		
		return json_decode($result);
	}
	
	public function getVend($url, $data = false) {
		$options = array(
			'http' => array(
					'header' => "Authorization: Bearer " . $this->vendToken,
			),
		);
		
		if ($data) 
			$options['http']['content'] = json_encode($data);

		$context = stream_context_create($options);
		
		@$result = file_get_contents($this->vendURL .'/'. $url, false, $context);
		
		return json_decode($result);
	}

	public function deleteVend($url) {
		$options = array(
				'http' => array(
					'header'  =>
						"Authorization: Bearer " . $this->vendToken ."\r\n".
						"Accept: application/json\r\n",
						'method'  => 'DELETE',
				)
		);
		
		$context  = stream_context_create($options);
		@$result = file_get_contents($this->vendURL .'/'. $url, false, $context);
		
		return json_decode($result);
	}
}