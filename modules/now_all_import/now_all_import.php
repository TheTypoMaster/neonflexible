<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

include(_PS_MODULE_DIR_ . 'now_all_import/classes/Module.php');

class now_all_import extends NowModule {

	public function __construct()
	{
		$this->name				= 'now_all_import';
		$this->tab				= 'administration';
		$this->version			= 1.0;
		$this->author			= 'NinjaOfWeb';
		$this->need_instance	= 0;

		parent::__construct();

		$this->displayName = $this->l('Import accessories, packs, type of product, ideas or tips');
		$this->description = $this->l('Import accessories, packs, type of product, ideas or tips with only one .csv file');

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
			'AdminNowAllImport' => array(
				'parent' => 'AdminParentNinjaOfWeb',
				'name' => $this->l('All import')
			)
		);
	}

	public function install()
	{
		$this->aConfigurationDefaultSettings = array(
			'NOW_IMPORT_FILE'			=> '.csv',
			'NOW_IMPORT_SEPARATOR'	=> ';',
			'NOW_IMPORT_DELIMITER'	=> 2,
			'NOW_IMPORT_DECIMAL'		=> '.',
			'NOW_IMPORT_CONVERT_UTF8'	=> 1,
			'NOW_IMPORT_PAGINATION'	=> 50
		);

		return parent::install();
	}
}

