<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

include (_PS_MODULE_DIR_.'now_product_type/classes/Module.php');
include (_PS_MODULE_DIR_.'now_product_type/classes/NowProductType.php');
include (_PS_MODULE_DIR_.'now_product_type/classes/NowProductTypeProduct.php');

class now_product_type extends NowModule {

	public function __construct()
	{
		$this->name				= 'now_product_type';
		$this->tab				= 'front_office_features';
		$this->version			= 1.1;
		$this->author			= 'NinjaOfWeb';
		$this->need_instance	= 0;

		parent::__construct();

		$this->displayName = $this->l('Manage Product Type');
		$this->description = $this->l('Manage types of yours products');

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
			'AdminProductType' => array(
				'parent' => 'AdminTools',
				'name' => $this->l('Manage Product Type')
			)
		);
	}

	/**
	 * Define the list of SQL file to execute to install
	 */
	public function setSqlFileToInstall() {
		$this->aSqlFileToInstall = array(
			1.0 => 'install.sql'
		);
	}

	/**
	 * @return bool
	 */
	public function install()
	{
		$this->aConfigurationDefaultSettings = array(

		);

		return parent::install();
	}

}

