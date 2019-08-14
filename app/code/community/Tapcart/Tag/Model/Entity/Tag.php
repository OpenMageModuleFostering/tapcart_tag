<?php

class Tapcart_Tag_Model_Entity_Tag extends Mage_Core_Model_Abstract {

    protected function _construct()
    {
        $this->_init('tapcart_tag_entity/tag');
    }
	
	public function getProduct() {
	
		return Mage::getModel('catalog/product')->load($this->product_id);
	}
	
	public function save() {

		//update url when product id is changed
		if ($this->getProductId() !== null && ($this->_data["product_id"] !== $this->_origData["product_id"] || $this->_data["store_id"] !== $this->_origData["store_id"])) {
		
			//get url
			$this->setUrl("");
			if ($this->getProductId() !== null) {
			
				//load product and update url
				$this->setUrl(Mage::getModel('catalog/product')
					->setStoreId($this->getStoreId())
					->load($this->getProductId())
					->getProductUrl());
			}
		}

		//save entity
		parent::save();				
	}
	
	public function updateTagUrl() {

		//
		if ($this->getUrl() != $this->getTagUrl()) {
		
			//
			$data = new StdClass();
			$data->id = $this->getTagId();
			$data->url = $this->getUrl();		
			
			//update tag external
			$tagApi = Mage::getSingleton("tapcart_tag/api_tag");
			$tag = $tagApi->updateTag($this->getStore(), $data);
			
			//update field tag_url
			$this->setTagUrl($tag->url);

			//save entity
			parent::save();				
		}	
	}
}