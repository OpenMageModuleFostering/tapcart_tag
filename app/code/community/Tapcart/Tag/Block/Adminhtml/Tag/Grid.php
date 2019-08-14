<?php

class Tapcart_Tag_Block_Adminhtml_Tag_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('tagGrid');
      $this->setDefaultSort('tag_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }
  
	public function getMainButtonsHtml() {
		$html = parent::getMainButtonsHtml();
		$store_switcher = $this->getLayout()->createBlock('adminhtml/store_switcher')
			->setTemplate('tapcart/store/switcher.phtml')
			->setUseConfirm(false);
			
		$html .= $store_switcher->toHtml();
		return $html;
	}  

  protected function _prepareCollection()
  {
	  //
	  $tagApi = Mage::getSingleton("tapcart_tag/api_tag");
	  
      $collection = Mage::getModel('tapcart_tag_entity/tag')->getCollection()
		->addFieldToFilter("store", $tagApi->getConfig($this->getRequest()->getParam("store"))->store);

      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
	$products = array();
	foreach (Mage::getModel('tapcart_tag_entity/tag')->getCollection() AS $tag) {
	
		//
		if ($tag->product_id != "" && !isset($products[$tag->product_id]))
			$products[$tag->product_id] = $tag->getProduct()->getName();
	}
  
      $this->addColumn('tag_id', array(
          'header'    => Mage::helper('tapcart_tag')->__('NFC tag'),
          'align'     => 'left',
          'index'     => 'tag_id'
      ));
	  
      $this->addColumn('product_id', array(
          'header'    => Mage::helper('tapcart_tag')->__('Product'),
          'align'     => 'left',
          'index'     => 'product_id',
		  'type'      => 'options',
		  'options'   => $products
      ));
	  
      $this->addColumn('url', array(
          'header'    => Mage::helper('tapcart_tag')->__('Url'),
          'align'     => 'left',
          'index'     => 'url'
      ));
	  
      $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('tapcart_tag')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('tapcart_tag')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
	  
      return parent::_prepareColumns();
  }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}