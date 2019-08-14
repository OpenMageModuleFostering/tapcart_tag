<?php

class Tapcart_Tag_Model_Api_Auth extends Tapcart_Tag_Model_Api_Base {
	
	private $token = null;
	
	public function getToken() {
	
		if ($this->token == null) {
			
			//
			$stdClass = new StdClass();
			$stdClass->api_key = (string)$this->getConfig()->api_key;
			$stdClass->user_name = (string)$this->getConfig()->user_name;
			$stdClass->password = (string)$this->getConfig()->password;
			
			//get token
			$result = $this->restPost('/api/createAuthToken', $stdClass);
			
			//check result
			if ($result === null)
				throw new Exception("Invalid response while authenticating");
				
			if (!isset($result->token) || $result->token == "")
				throw new Exception("Cannot authenticate, check your tapcart api configuration and try again.");
			
			//save token
			$this->token = $result->token;
		}
	
		return $this->token;
	}
}