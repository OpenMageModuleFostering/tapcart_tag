<?php
class Tapcart_Tag_Block_Adminhtml_Renderer_Product extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
	{
		return $row->getProduct()->getName();
	} 
}
?>