<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

include (_PS_MODULE_DIR_.'now_mea_home/classes/Module.php');
include (_PS_MODULE_DIR_.'now_mea_home/classes/NowMeaHome.php');

class now_mea_home extends NowModule {

	public function __construct()
	{
		$this->name				= 'now_mea_home';
		$this->tab				= 'front_office_features';
		$this->version			= 1.0;
		$this->author			= 'NinjaOfWeb';
		$this->need_instance	= 0;

		parent::__construct();

		$this->displayName = $this->l('Highlighted on the home page');
		$this->description = $this->l('Manage products to display on the home page.');

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
			'AdminMeaProductsHomePage' => array(
				'parent' => 'AdminParentNinjaOfWeb',
				'name' => $this->l('Highlighted')
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
			'NOW_MEA_HOME_ENABLE'	=> true,
			'NOW_MEA_HOME_NB_PRODUCT'	=> 5,
		);

		return parent::install() && $this->registerHook('home') && $this->registerHook('header');
	}

	/**
	 * Home
	 * @param $params
	 * @return mixed
	 */
	public function hookHome($params) {
		if (Configuration::get('NOW_MEA_HOME_ENABLE')) {

			// Lists of products
			$aProducts = NowMeaHome::getProductsCollection();

			$this->context->smarty->assign(array(
				'aProducts'		=> $aProducts
			));


			return $this->context->smarty->fetch($this->module_dir.'views/templates/hook/home.tpl');
		}
	}

	/**
	 * HEADER
	 * @param $params
	 * @return mixed
	 */
	public function hookHeader($params) {
		if (Configuration::get('NOW_MEA_HOME_ENABLE')) {
			$this->context->controller->addCSS(($this->_path).'css/now_mea_home.css', 'all');
		}
	}
}

