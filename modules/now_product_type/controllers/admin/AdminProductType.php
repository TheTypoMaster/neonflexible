<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

include (_PS_MODULE_DIR_.'now_product_type/now_product_type.php');

class AdminProductTypeController extends ModuleAdminController {
	public $module;

	public function __construct()
	{
		$this->module = new now_product_type();

		parent::__construct();
	}
}