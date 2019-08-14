<?php

class Tapcart_Tag_Model_Api_Tag extends Tapcart_Tag_Model_Api_Base {
	
	public function getAllTags($store) {
	
		//first authenticate
		$this->authenticate();
		
		//get store
		if ($store === null) {
			$store = $this->getConfig()->store;
		}		
		
		//init vars
		$tags = array();
		$offset = 0;
		$limit = 25;
		
		//loop while break
		while (true) {
		
			//get tags
			$result = $this->restGet('/tapcarts/' . (int)$store . '/tags', array("offset" => $offset, "limit" => $limit));
			
			//check result
			if ($result === null)
				throw new Exception("Invalid response while getting tags");
			
			//add returned tags to tag list
			$tags = array_merge($tags, $result);

			//break when no tags are returned
			if (count($result) != $limit)
				break;
			
			//get next list
			$offset += $limit;
		}
		
		//add store to tags
		foreach ($tags AS $index => $tag) {

			$tag->store = $store;
			$tags[$index] = $tag;
		}

		return $tags;
	}
	
	public function updateTag($store, $data) {

		//first authenticate
		$this->authenticate();

		//update tag
		$result = $this->restPut('/tapcarts/' . (int)$store . '/tag', $data);

		//check tag result
		if ($result === null) {
			throw new Exception("Error saving tag, try to refresh your NFC tags.");
		}

		//return updated tag
		return $result;
	}
}