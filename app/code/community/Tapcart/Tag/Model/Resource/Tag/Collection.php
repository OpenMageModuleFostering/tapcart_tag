<?php

class Tapcart_Tag_Model_Resource_Tag_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {
	
    protected function _construct()
    {
        $this->_init('tapcart_tag_entity/tag');
    }
}