<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

include (_PS_MODULE_DIR_.'now_mea_home/now_mea_home.php');

class AdminMeaProductsHomePage extends ModuleAdminController {
	public $module;

	public function __construct()
	{
		$this->module = new now_mea_home();

		parent::__construct();
	}
}