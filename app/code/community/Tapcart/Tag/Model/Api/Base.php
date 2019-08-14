<?php

class Tapcart_Tag_Model_Api_Base extends Mage_Core_Model_Abstract {

	private $url = 'https://api.tapster.nl';
	private $version = "v1";
	private $token = null;
	private $config = null;
	
	public function getConfig($store_id = null) {
		
		if ($this->config === null) {
			
			if ($store_id === null)
				$store_id = $this->getCurrentStoreId();
			
			//
			$this->config = new StdClass();
			$this->config->api_key = (string)Mage::getStoreConfig('tapcart_tag/api_credentials/api_key', $store_id);
			$this->config->user_name = (string)Mage::getStoreConfig('tapcart_tag/api_credentials/username', $store_id);
			$this->config->password = (string)Mage::getStoreConfig('tapcart_tag/api_credentials/password', $store_id);
			$this->config->store = (string)Mage::getStoreConfig('tapcart_tag/api_credentials/store', $store_id);
		}
		
		return $this->config;
	}
	
	public function isConfigValid($store_id) {
		
		return (
			$this->getConfig($store_id)->api_key != "" &&
			$this->getConfig($store_id)->user_name != "" &&
			$this->getConfig($store_id)->password != "" &&
			$this->getConfig($store_id)->store != ""
		);
	}
	
	public function isAuthenticated() {
		
		try {
			$this->authenticate();
		} catch (Exception $e) {}
		
		return ($this->token != "");
	}
	
	protected function authenticate() {

		//get token
		$auth = Mage::getSingleton("tapcart_tag/api_auth");
		$this->token = $auth->getToken();	
	}
	
	protected function restPost($path, $data) {
	
		//
		if (!is_object($data))
			throw new Exception("data must be an object");

		//
		$rest = new Zend_Rest_Client($this->url);
		if ($this->token !== null)
			$rest->getHttpClient()->setHeaders('Authorization', "Bearer " . $this->token);

		return json_decode($rest->restPost("/" . $this->version . $path, $data)->getBody());
	}

	protected function restGet($path, $data = array()) {
	
		//
		if (!is_array($data))
			throw new Exception("data must be an array");

		//
		$rest = new Zend_Rest_Client($this->url);
		if ($this->token !== null)
			$rest->getHttpClient()->setHeaders('Authorization', "Bearer " . $this->token);
		
		return json_decode($rest->restGet("/" . $this->version . $path, $data)->getBody());
	}

	protected function restPut($path, $data) {
	
		//
		if (!is_object($data))
			throw new Exception("data must be an object");

		//
		$rest = new Zend_Rest_Client($this->url);
		if ($this->token !== null)
			$rest->getHttpClient()->setHeaders('Authorization', "Bearer " . $this->token);

		return json_decode($rest->restPut("/" . $this->version . $path, $data)->getBody());
	}
	
	private function getCurrentStoreId() {
		
		if (strlen($code = Mage::getSingleton('adminhtml/config_data')->getStore())) { // store level
			return Mage::getModel('core/store')->load($code)->getId();
		}
		
		if (strlen($code = Mage::getSingleton('adminhtml/config_data')->getWebsite())) // website level
		{
			$website_id = Mage::getModel('core/website')->load($code)->getId();
			return Mage::app()->getWebsite($website_id)->getDefaultStore()->getId();
		}
		
		return 0;		
	}
}