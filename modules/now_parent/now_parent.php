<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

include (_PS_MODULE_DIR_.'now_parent/classes/Module.php');

class now_parent extends NowModule {

	public function __construct()
	{
		$this->name				= 'now_parent';
		$this->tab				= 'administration';
		$this->version			= 1.0;
		$this->author			= 'NinjaOfWeb';
		$this->need_instance	= 0;

		parent::__construct();

		$this->displayName = $this->l('Page Parent of NinjaOfWeb modules');
		$this->description = $this->l('Page wich list all pages of NinjaOfWeb modules.');

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
			'AdminParentNinjaOfWeb' => array(
				'parent' => false,
				'name' => $this->l('Ninja Of Web')
			)
		);
	}
}

