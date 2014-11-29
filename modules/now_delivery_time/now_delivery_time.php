<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

require_once (_PS_MODULE_DIR_.'now_delivery_time/classes/Module.php');
require_once (_PS_MODULE_DIR_.'now_delivery_time/classes/NowDeliveryTime.php');

class now_delivery_time extends NowModule {

	public function __construct()
	{
		$this->name				= 'now_delivery_time';
		$this->tab				= 'front_office_features';
		$this->version			= 1.0;
		$this->author			= 'NinjaOfWeb';
		$this->need_instance	= 0;

		parent::__construct();

		$this->displayName = $this->l('Manage Delivery time');
		$this->description = $this->l('Manage all delivery time by carrier on your front office');

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
			'AdminNowDeliveryTime' => array(
				'parent' => 'AdminParentNinjaOfWeb',
				'name' => $this->l('Manage delivery time')
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
	public function install() {
		return parent::install() &&
				$this->registerHook('actionCarrierUpdate') &&
				$this->registerHook('displayCarrierDeliveryTimeList');
	}

	/**
	 * hook: actionCarrierUpdate
	 * @param $aParams
	 */
	public function hookActionCarrierUpdate($aParams) {
		p('Module now_delivery time');
		d($aParams);

	}

	/**
	 * hook: displayCarrierDeliveryTimeList
	 * @param $aParams
	 */
	public function hookDisplayCarrierDeliveryTimeList($aParams) {

		$aDeliveryTimeList = array();

		$this->context->smarty->assign(array(
			'aDeliveryTimeList' => $aDeliveryTimeList
		));


		return $this->context->smarty->fetch($this->module_dir.'views/templates/hook/product-delivery.tpl');
	}


}