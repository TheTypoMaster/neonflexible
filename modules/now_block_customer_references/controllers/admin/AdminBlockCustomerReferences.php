<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

include (_PS_MODULE_DIR_.'now_block_customer_references/now_block_customer_references.php');

class AdminBlockCustomerReferencesController extends ModuleAdminController {
	public $module;

	public function __construct()
	{
		$this->module = new now_block_customer_references();

		parent::__construct();
	}
}