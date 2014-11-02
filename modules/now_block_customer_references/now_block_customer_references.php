<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

require_once (_PS_MODULE_DIR_ . 'now_block_customer_references/classes/Module.php');
require_once (_PS_MODULE_DIR_ . 'now_block_customer_references/classes/NowBlockCustomerReferences.php');

class now_block_customer_references extends NowModule {

	public function __construct()
	{
		$this->name				= 'now_block_customer_references';
		$this->tab				= 'front_office_features';
		$this->version			= 1.2;
		$this->author			= 'NinjaOfWeb';
		$this->need_instance	= 0;

		parent::__construct();

		$this->displayName = $this->l('Manage block customer references');
		$this->description = $this->l('Add a customer references block on footer.');

		if ($this->active) {
			$this->module_dir = _PS_MODULE_DIR_.$this->name.DIRECTORY_SEPARATOR;
			$this->module_uri = DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$this->name.DIRECTORY_SEPARATOR;
		}
	}

	/**
	 * Define admin controller which must be installed
	 */
	public function setAdminControllers() {
		$this->aAdminControllers = array(
			'AdminBlockCustomerReferences' => array(
				'parent' => 'AdminParentNinjaOfWeb',
				'name' => $this->l('Manage block references')
			)
		);
	}

	/**
	 * Define the list of SQL file to execute to install
	 */
	public function setSqlFileToInstall() {
		$this->aSqlFileToInstall = array(
			'1.0' => 'install.sql'
		);
	}

	/**
	 * @return bool
	 */
	public function install()
	{
		$this->aConfigurationDefaultSettings = array(
			'NOW_CUST_REFERENCE_ENABLE'		=> true,
			'NOW_CUST_REFERENCE_CMS_ID'		=> 8
		);

		return parent::install() && $this->registerHook('rightColumn') && $this->registerHook('header');
	}

	/**
	 * Right Column
	 * @param $params
	 * @return mixed
	 */
	public function hookRightColumn($params) {
		if (Configuration::get('NOW_CUST_REFERENCE_ENABLE')) {

			// Lists of items
			$aItems = NowBlockCustomerReferences::getItems();

			$this->context->smarty->assign(array(
				'module_dir'	=> $this->module_uri . 'uploads/',
				'aItems'		=> $aItems,
			));


			return $this->context->smarty->fetch($this->module_dir.'views/templates/hook/footer.tpl');
		}
	}

	/**
	 * HEADER
	 * @param $params
	 * @return mixed
	 */
	public function hookHeader($params) {
		if (Configuration::get('NOW_CUST_REFERENCE_ENABLE')) {
			$this->context->controller->addCSS(($this->_path).'css/now_block_customer_references.css', 'all');
		}
	}
}

