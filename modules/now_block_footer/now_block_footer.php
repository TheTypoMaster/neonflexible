<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

include(_PS_MODULE_DIR_ . 'now_block_footer/classes/Module.php');

class now_block_footer extends NowModule {

	public function __construct() {
		$this->name				= 'now_block_footer';
		$this->tab				= 'front_office_features';
		$this->version			= 1.0;
		$this->author			= 'NinjaOfWeb';
		$this->need_instance	= 0;

		parent::__construct();

		$this->displayName = $this->l('Add a block on footer');
		$this->description = $this->l('Group elements by column on footer');

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
			'AdminNowBlockFooter' => array(
				'parent' => 'AdminTools',
				'name' => $this->l('Manage footer bloc')
			)
		);
	}

	public function install() {
		$this->aConfigurationDefaultSettings = array(
			'NOW_FOOTER_ENABLE'	=> true
		);

		return parent::install() && $this->registerHook('footer');
	}

	public function hookDisplayFooter($params) {
		if (Configuration::get('NOW_FOOTER_ENABLE')) {

			return $this->context->smarty->fetch($this->module_dir.'views/templates/hook/footer.tpl');
		}
	}
}

