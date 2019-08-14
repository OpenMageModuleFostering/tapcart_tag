<?php

class Tapcart_Tag_Model_Resource_Tag extends Mage_Core_Model_Resource_Db_Abstract{

    protected function _construct()
    {
        $this->_init('tapcart_tag_entity/tag', 'id');
    }
}