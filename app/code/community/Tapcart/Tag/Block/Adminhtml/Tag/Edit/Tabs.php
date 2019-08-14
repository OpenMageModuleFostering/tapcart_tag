<?php

class Tapcart_Tag_Block_Adminhtml_Tag_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('tapcart_tag_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('tapcart_tag')->__('NFC Tag'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('general_section', array(
          'label'     => Mage::helper('tapcart_tag')->__('General'),
          'title'     => Mage::helper('tapcart_tag')->__('General'),
          'content'   => $this->getLayout()->createBlock('tapcart_tag/adminhtml_tag_edit_tab_general')->toHtml(),
      ));      
     
      return parent::_beforeToHtml();
  }
}