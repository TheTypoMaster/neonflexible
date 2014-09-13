<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

include (_PS_MODULE_DIR_.'now_seo_links/classes/Module.php');

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
		$this->version			= 1.1;
		$this->author			= 'NinjaOfWeb';
		$this->need_instance	= 0;

		parent::__construct();

		$this->displayName = $this->l('Links without id');
		$this->description = $this->l('This module allows you to configure your routes without id.');

		if ($this->active) {
			$this->module_dir = _PS_MODULE_DIR_.$this->name.DIRECTORY_SEPARATOR;
		}
	}

	public function install() {
		return parent::install() && $this->registerHook('moduleRoutes');
	}

	public function hookModuleRoutes() {
		return self::$ModuleRoutes;
	}
}

