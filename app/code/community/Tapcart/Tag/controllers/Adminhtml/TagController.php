<?php

class Tapcart_Tag_Adminhtml_TagController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {

		$this->loadLayout()
			->_setActiveMenu('catalog/tapcart_tag')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('NFC Tags'), Mage::helper('adminhtml')->__('NFC Tags'));
			
		return $this;
	}
	
	private function initStore() {

		//
		if ($this->getRequest()->getParam("store") !== null) {
			Mage::getSingleton('adminhtml/session')->setTapcartTagCurrentStore($this->getRequest()->getParam("store"));
		} else
			$this->getRequest()->setParam("store", Mage::getSingleton('adminhtml/session')->getTapcartTagCurrentStore());
	}

	public function indexAction() {
		
		$this->initStore();

		//
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
	
		$this->_initAction();

		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('tapcart_tag_entity/tag')->load($id);

		if ($model->getId()) {

			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			
			if (!empty($data)) {
				$model->setData($data);
			}
			
			if ($model->getStoreId() === null) {
				$this->initStore();
				$model->setStoreId($this->getRequest()->getParam("store"));
			}

			Mage::register('tapcart_tag_data', $model);
			
			$this->_addContent($this->getLayout()->createBlock('tapcart_tag/adminhtml_tag_edit'));
			$this->_addLeft($this->getLayout()->createBlock('tapcart_tag/adminhtml_tag_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('tapcart_tag')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function saveAction() {

		$data = $this->getRequest()->getPost();
		
		if ($data) {

			//prevent changing tag_id
			unset($data["tag_id"]);
		
			//
			$model = Mage::getModel('tapcart_tag_entity/tag')->load($this->getRequest()->getParam('id'));
				
			//reset product ids
			if ($data["product_id"] == "NULL") {
				$model->setProductId(null);
				$model->setStoreId(null);
			} else {
				$model->setProductId($data["product_id"]);
				$model->setStoreId($data["store_id"]);
			}

			try {
			
				//save model and send tag
				$model->save();
				
				//save tag url
				$model->updateTagUrl();
				
				//
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('tapcart_tag')->__('NFC Tag was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
				
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('tapcart_tag')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}

	public function refreshAction() {
		
		//
		$this->initStore();
		
		//
		$tagApi = Mage::getSingleton("tapcart_tag/api_tag");
		$tagStore = $tagApi->getConfig($this->getRequest()->getParam("store"))->store;
		
		try {
			$tags = $tagApi->getAllTags($tagStore);

		} catch(Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			$this->_redirect('*/*/');
			return;
		}

		//get all stores 
		$baseUrls = array();
		foreach (Mage::app()->getStores() AS $store) {
			$baseUrls[$store->store_id] = $store->getBaseUrl();
		}
		
		//loop tags
		$saved_ids = array();
		foreach ($tags AS $tag) {
		
			//get tag
			$mage_tag = Mage::getModel('tapcart_tag_entity/tag')->getCollection()->addFieldToFilter("tag_id", $tag->id)->getFirstItem();
			if (!$mage_tag->getId() || $mage_tag->getProductId() === null) {
			
				$mage_tag->setTagId($tag->id);
				$mage_tag->setTagUrl($tag->url);
				$mage_tag->setUrl($tag->url);
				$mage_tag->setStore($tag->store);
				
				//try to set product and store ids
				if ($mage_tag->getProductId() === null && $tag->url != "") {
				
					//loop base urls
					foreach ($baseUrls AS $store_id => $baseUrl) {
					
						//check if url matches base url
						if (strpos($tag->url, $baseUrl) === 0) {
						
							//check if we can match product
							$vPath = substr($tag->url,strlen($baseUrl));
							$oRewrite = Mage::getModel('core/url_rewrite')
								->setStoreId($store_id)
								->loadByRequestPath($vPath);
							
							//
							if ($oRewrite->getProductId()) {
								$mage_tag->setStoreId($store_id);							
								$mage_tag->setProductId($oRewrite->getProductId());	
								break;
							}
						}
					}
				}

				//save
				$mage_tag->save();
			}
			$saved_ids[] = (int)$mage_tag->getId();
		}
		
		//
		if (count($saved_ids) > 0) {
			$tagsToDelete = Mage::getModel('tapcart_tag_entity/tag')->getCollection()->addFieldToFilter("store", $tagStore)->addFieldToFilter("id", array('nin' => $saved_ids));
			foreach ($tagsToDelete AS $tag) {
				$tag->delete();
			}
		}

		//
		Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('tapcart_tag')->__('NFC Tag list was successfully refreshed'));
        $this->_redirect('*/*/');
	}
}