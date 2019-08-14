<?php

	$installer = $this;

	$installer->startSetup();

	$installer->run("
	CREATE TABLE IF NOT EXISTS {$this->getTable('tapcart_tag')} (
	 `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	 `tag_id` VARCHAR(255) NOT NULL,
	 `store` VARCHAR(255) NOT NULL,
	 `tag_url` VARCHAR(255) NOT NULL DEFAULT '' ,
	 `url` VARCHAR(255) NOT NULL DEFAULT '',
	 `product_id` INT UNSIGNED NULL,
	 `store_id` INT UNSIGNED NULL
	 ) ENGINE = INNODB DEFAULT CHARSET=utf8;
	");

$installer->endSetup(); 