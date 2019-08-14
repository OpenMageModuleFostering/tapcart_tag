<?php
class Tapcart_Tag_Block_Adminhtml_Tag extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_tag';
    $this->_blockGroup = 'tapcart_tag';
    $this->_headerText = Mage::helper('tapcart_tag')->__('Available nfc tags');
	
    parent::__construct();
	
	//
	$this->_removeButton('add');

	//
	$this->_addButton('refresh', array(
		'label'     => Mage::helper('adminhtml')->__('Refresh'),
		'onclick'   => "setLocation('{$this->getUrl('*/*/refresh')}')",
		'class'     => 'go',
	), -100);	
  }
}