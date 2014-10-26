<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

include(_PS_MODULE_DIR_ . 'now_import_accessories/classes/Module.php');

class now_import_accessories extends NowModule {

	public function __construct()
	{
		$this->name				= 'now_import_accessories';
		$this->tab				= 'administration';
		$this->version			= 1.1;
		$this->author			= 'NinjaOfWeb';
		$this->need_instance	= 0;

		parent::__construct();

		$this->displayName = $this->l('Import accessories');
		$this->description = $this->l('Import accessories by .csv file');

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
			'AdminNowImportAccessories' => array(
				'parent' => 'AdminParentNinjaOfWeb',
				'name' => $this->l('Import accessories')
			)
		);
	}

	public function install()
	{
		$this->aConfigurationDefaultSettings = array(
			'NOW_IMPORT_ACCES_FILE'			=> '.csv',
			'NOW_IMPORT_ACCES_SEPARATOR'	=> ';',
			'NOW_IMPORT_ACCES_DELIMITER'	=> 2,
			'NOW_IMPORT_ACCES_DECIMAL'		=> '.',
			'NOW_IMPORT_ACCES_CONVERT_UTF8'	=> 1,
			'NOW_IMPORT_ACCES_PAGINATION'	=> 50
		);

		return parent::install();
	}
}

