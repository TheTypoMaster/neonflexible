<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

require_once(_PS_MODULE_DIR_ . 'now_product_type/now_product_type.php');
require_once(_PS_MODULE_DIR_ . 'now_product_type/classes/NowProductType.php');
require_once(_PS_MODULE_DIR_ . 'now_product_type/classes/NowProductTypeProduct.php');

class AdminNowProductTypeController extends ModuleAdminControllerCore {

	public function __construct()
	{
		$this->bootstrap = true;
		$this->module = new now_product_type();

		parent::__construct();
	}
}