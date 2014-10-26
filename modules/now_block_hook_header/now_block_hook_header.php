<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

require_once (_PS_MODULE_DIR_ . 'now_block_hook_header/classes/Module.php');

class now_block_hook_header extends NowModule {

	public function __construct()
	{
		$this->name				= 'now_block_hook_header';
		$this->tab				= 'front_office_features';
		$this->version			= 1.0;
		$this->author			= 'NinjaOfWeb';
		$this->need_instance	= 0;

		parent::__construct();

		$this->displayName = $this->l('Hook Header');
		$this->description = $this->l('Manage a hook on the header block');

		if ($this->active) {
			$this->module_dir = _PS_MODULE_DIR_.$this->name.DIRECTORY_SEPARATOR;
			$this->module_uri = DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$this->name.DIRECTORY_SEPARATOR;
		}
	}

	/**
	 * @return bool
	 */
	public function install()
	{
		$this->aConfigurationDefaultSettings = array(
			'NOW_ACCR_HEADER_ENABLE'	=> true,
			'NOW_PHONE_INTERNATIONAL'	=> '(+33) 234 321 179',
		);

		return parent::install() && $this->registerHook('top') && $this->registerHook('header');
	}

	/**
	 * TOP
	 * @param $params
	 * @return mixed
	 */
	public function hookTop($params) {
		if (Configuration::get('NOW_ACCR_HEADER_ENABLE')) {

			$this->context->smarty->assign(array(
				'' => ''
			));


			return $this->context->smarty->fetch($this->module_dir.'views/templates/hook/header.tpl');
		}
	}

	/**
	 * HEADER
	 * @param $params
	 * @return mixed
	 */
	public function hookHeader($params) {
		if (Configuration::get('NOW_ACCR_HEADER_ENABLE')) {
			$this->context->controller->addCSS(($this->_path).'css/now_block_hook_header.css', 'all');
		}
	}
}

