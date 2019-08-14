<?php

class Tapcart_Tag_Model_Api_Account extends Tapcart_Tag_Model_Api_Base {
	
	public function getAccount($store = null) {
		
		//first authenticate
		$this->authenticate();
		
		//get store
		if ($store === null) {
			$store = $this->getConfig()->store;
		}		
		
		//get account
		$result = $this->restGet('/tapcarts/' . (int)$store . '/account', array("expand" => "location"));
			
		//check result
		if ($result === null)
			throw new Exception("Invalid response while getting account information");

		return $result;
	}
	
	public function getQuota($store = null) {
	
		//first authenticate
		$this->authenticate();
		
		//get store
		if ($store === null) {
			$store = $this->getConfig()->store;
		}		
		
		//get account
		$result = $this->restGet('/tapcarts/' . (int)$store . '/quota');
			
		//check result
		if ($result === null)
			throw new Exception("Invalid response while getting account information");

		return $result;
	}
}