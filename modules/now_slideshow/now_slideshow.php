<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

require_once (_PS_MODULE_DIR_ . 'now_slideshow/classes/Module.php');
require_once (_PS_MODULE_DIR_ . 'now_slideshow/classes/NowSlideshow.php');

class now_slideshow extends NowModule {

	public function __construct()
	{
		$this->name				= 'now_slideshow';
		$this->tab				= 'front_office_features';
		$this->version			= 1.1;
		$this->author			= 'NinjaOfWeb';
		$this->need_instance	= 0;

		parent::__construct();

		$this->displayName = $this->l('Manage Slideshow');
		$this->description = $this->l('Manage Slideshow on your home page');

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
			'AdminSlideshow' => array(
				'parent' => 'AdminParentNinjaOfWeb',
				'name' => $this->l('Manage Slideshow')
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
			'NOW_SLIDESHOW_ENABLE'			=> true
		);

		return parent::install() && $this->registerHook('home') && $this->registerHook('header');
	}

	/**
	 * Home
	 * @param $params
	 * @return mixed
	 */
	public function hookHome($params) {
		if (Configuration::get('NOW_SLIDESHOW_ENABLE')) {

			$aSlides = NowSlideshow::getSlides();

			$this->context->smarty->assign(array(
				'aSlides' => $aSlides
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
		if (Configuration::get('NOW_SLIDESHOW_ENABLE')) {
			$this->context->controller->addCSS($this->_path . 'css/now_slideshow.css', 'all');
			$this->context->controller->addCSS($this->_path . 'css/slick.css', 'all');
			$this->context->controller->addJS($this->_path . 'js/slick.min.js');
			$this->context->controller->addJS($this->_path . 'js/now_slideshow.js');
		}
	}
}

