<?php

class Tapcart_Tag_Block_Adminhtml_Tag_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'tapcart_tag';
        $this->_controller = 'adminhtml_tag';

        $this->_updateButton('save', 'label', Mage::helper('tapcart_tag')->__('Save'));

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);
		
		$this->_removeButton('delete');
		$this->_removeButton('reset');

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('tapcart_tag_data') && Mage::registry('tapcart_tag_data')->getId() ) {
            return Mage::helper('tapcart_tag')->__("Edit '%s'", $this->htmlEscape(Mage::registry('tapcart_tag_data')->getTagId()));
        } else {
            return Mage::helper('tapcart_tag')->__('Add Item');
        }
    }
}