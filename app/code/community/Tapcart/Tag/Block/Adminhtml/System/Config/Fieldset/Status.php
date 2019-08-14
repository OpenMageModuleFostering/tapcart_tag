<?php

class Tapcart_Tag_Block_Adminhtml_System_Config_Fieldset_Status extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface
{
    protected $_template = 'tapcart/system/config/fieldset/status.phtml';
	private $_credentialsValid = null;

	public function credentialsKnown() {
		
		//
		return Mage::getSingleton("tapcart_tag/api_account")->isConfigValid(Mage::getSingleton("tapcart_tag/api_account")->getConfig()->store);
	}

	public function credentialsValid() {
		
		//
		return Mage::getSingleton("tapcart_tag/api_account")->isAuthenticated();
	}
	
	public function getAccount() {
		
		try {
		
			return Mage::getSingleton("tapcart_tag/api_account")->getAccount(Mage::getSingleton("tapcart_tag/api_account")->getConfig()->store);
		} catch (Exception $e) { }
		
		return null;
	}
	
	public function getQuota() {
		
		try {
		
			return Mage::getSingleton("tapcart_tag/api_account")->getQuota(Mage::getSingleton("tapcart_tag/api_account")->getConfig()->store);
		} catch (Exception $e) { }
		
		return null;
	}
	
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        return $this->toHtml();
    }
}

