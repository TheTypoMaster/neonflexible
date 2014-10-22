<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

require_once (_PS_MODULE_DIR_.'now_block_customer_references/now_block_customer_references.php');
require_once (_PS_MODULE_DIR_.'now_block_customer_references/classes/NowBlockCustomerReferences.php');

class now_block_customer_referencesDefaultModuleFrontController extends ModuleFrontController {

	/**
	 *
	 */
	public function initContent() {

		parent :: initContent();

		$module = new now_block_customer_references();

		$this->context->smarty->assign('module_dir', $module->module_uri . 'uploads/');
		$this->context->smarty->assign('nowBlockCustomerReferencesList', NowBlockCustomerReferences::getItems());

		$this->setTemplate('list-customers-references.tpl');

	}

}