<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

include (_PS_MODULE_DIR_.'now_block_reinsurance/classes/Module.php');
include (_PS_MODULE_DIR_.'now_block_reinsurance/classes/NowBlockReinsurance.php');

class now_block_reinsurance extends NowModule {

	public function __construct()
	{
		$this->name				= 'now_block_reinsurance';
		$this->tab				= 'administration';
		$this->version			= 1.0;
		$this->author			= 'NinjaOfWeb';
		$this->need_instance	= 0;

		parent::__construct();

		$this->displayName = $this->l('Manage block reinsurance');
		$this->description = $this->l('Add reinsurance item on a block.');

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
			'AdminBlockReinsurance' => array(
				'parent' => 'AdminTools',
				'name' => $this->l('Manage block reinsurance')
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
			'NOW_REINSURANCE_ENABLE'	=> true
		);

		return parent::install() && $this->registerHook('rightColumn') && $this->registerHook('header');
	}

	/**
	 * Right Column
	 * @param $params
	 * @return mixed
	 */
	public function hookRightColumn($params) {
		if (Configuration::get('NOW_FOOTER_ENABLE')) {

			// Lists of items
			$aItems = NowBlockReinsurance::getItems();

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
		if (Configuration::get('NOW_REINSURANCE_ENABLE')) {
			$this->context->controller->addCSS(($this->_path).'css/now_block_reinsurance.css', 'all');
		}
	}
}

