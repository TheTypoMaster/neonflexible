<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

require_once (_PS_MODULE_DIR_ . 'now_seo_links/classes/Module.php');
require_once (_PS_MODULE_DIR_.'now_seo_links/classes/NowLanguageLink.php');

class now_seo_links extends NowModule {

	public static $ModuleRoutes = array(
		'now_seo_links' => array(
			'controller'	=>  null,
			'rule'			=> '{categories:/}/{id}-{type}/{product_name}.jpg',
			'keywords'		=> array(
				'id'			=> array('regexp' => '[0-9]+', 'param' => 'id_image'),
				'type'			=> array('regexp' => '[/_a-zA-Z0-9-\pL]*', 'param' => 'image_type'),
				'categories'	=> array('regexp' => '[/_a-zA-Z0-9-\pL]*'),
				'product_name'	=> array('regexp' => '[_a-zA-Z0-9-\pL]*'),
			)
		)
	);

	public function __construct() {
		$this->name				= 'now_seo_links';
		$this->tab				= 'administration';
		$this->version			= 1.3;
		$this->author			= 'NinjaOfWeb';
		$this->need_instance	= 0;

		parent::__construct();

		$this->displayName = $this->l('Links without id');
		$this->description = $this->l('This module allows you to configure your routes without id.');

		if ($this->active) {
			$this->module_dir = _PS_MODULE_DIR_.$this->name.DIRECTORY_SEPARATOR;
		}
	}

	/**
	 * Define admin controller which must be installed
	 */
	public function setAdminControllers() {
		$this->aAdminControllers = array(
			'AdminLanguageLink' => array(
				'parent' => 'AdminParentNinjaOfWeb',
				'name' => $this->l('Languages Link')
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

	public function install() {
		return parent::install() &&
			$this->registerHook('moduleRoutes') &&
			$this->registerHook('actionObjectLanguageAddAfter');
	}

	/**
	 * Hook ModuleRoutes
	 * @return array
	 */
	public function hookModuleRoutes() {
		return self::$ModuleRoutes;
	}

	/**
	 * Hook actionObjectLanguageAddAfter
	 * @return array
	 */
	public function hookActionObjectLanguageAddAfter($aParams) {
		$oLanguages = $aParams['object'];

		if (Validate::isLoadedObject($oLanguages)) {
			$oNowLanguageLink				= new NowLanguageLink();
			$oNowLanguageLink->id_lang		= $oLanguages->id;
			$oNowLanguageLink->folder_name	= $oLanguages->iso_code;
			$oNowLanguageLink->save();
		}
	}
}

