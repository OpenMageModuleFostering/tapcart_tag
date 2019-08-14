<?php

class Tapcart_Tag_Block_Adminhtml_Tag_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$formValues = array();
		if ( Mage::registry('tapcart_tag_data') )
			$formValues = Mage::registry('tapcart_tag_data')->getData();
			
		//get product collection
		$productCollection = Mage::getModel('catalog/product')
			->getCollection()
			->addAttributeToSelect('sku')
			->addAttributeToSelect('name')
			->addAttributeToFilter('visibility', array('neq' => Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE))
			->addAttributeToFilter('status', array('eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED))
			->setOrder("name", "asc");

		//get products
		$product_ids = array();
		$products = array(array("label" => "-- " . Mage::helper('tapcart_tag')->__('Select a product') . " --", "value" => "NULL"));
		foreach ($productCollection AS $product) {
			$products[] = array(
				"label" => $product->getName() . " (" . $product->getSku() . ")", 
				"value" => $product->getId()
			);
			$product_ids[] = $product->getId();
		}
		
		//get stores
		$stores = Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true);
		$stores[0]['label'] = "-- " . Mage::helper('tapcart_tag')->__('Select a store view') . " --";
		$stores[0]['value'] = "";

		//generate form
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('import_general', array('legend'=>Mage::helper('tapcart_tag')->__('General')));

		//add product_id
		$product_id_field = $fieldset->addField('product_id', 'select', array(
			'label'     => Mage::helper('tapcart_tag')->__('Product'),
			'class'     => 'required-entry',
			'required'  => true,
			'name'      => 'product_id',
			'values' => $products
		));

		//add store_id
		$store_id_field = $fieldset->addField('store_id', 'select', array(
			'label'     => Mage::helper('tapcart_tag')->__('Store'),
			'class'     => 'required-entry',
			'required'  => true,
			'name'      => 'store_id',
			'values' => $stores
		));
 
        $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
            ->addFieldMap($product_id_field->getHtmlId(), $product_id_field->getName())
            ->addFieldMap($store_id_field->getHtmlId(), $store_id_field->getName())
            ->addFieldDependence(
                $store_id_field->getName(),
                $product_id_field->getName(),
                $product_ids
            )
        );

		$form->setValues($formValues);

		return parent::_prepareForm();
	}
}