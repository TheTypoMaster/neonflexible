<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

require_once (_PS_MODULE_DIR_ . 'now_category_slide/classes/Module.php');
require_once (_PS_MODULE_DIR_ . 'now_category_slide/classes/NowCategorySlide.php');

class now_category_slide extends NowModule {

	public function __construct()
	{
		$this->name				= 'now_category_slide';
		$this->tab				= 'front_office_features';
		$this->version			= 1.0;
		$this->author			= 'NinjaOfWeb';
		$this->need_instance	= 0;

		parent::__construct();

		$this->displayName = $this->l('Manage Category Slide');
		$this->description = $this->l('Manage Category Slide on your home page');

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
			'AdminCategorySlide' => array(
				'parent' => 'AdminParentNinjaOfWeb',
				'name' => $this->l('Manage Category Slide')
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
	public function install()
	{
		$this->aConfigurationDefaultSettings = array(
			'NOW_CATEG_SLIDE_ENABLE' => true
		);

		return parent::install() && $this->registerHook('home') && $this->registerHook('header');
	}

	/**
	 * Home
	 * @param $params
	 * @return mixed
	 */
	public function hookHome($params) {
		if (Configuration::get('NOW_CATEG_SLIDE_ENABLE')) {

			$aSlides = NowCategorySlide::getCategorySlides();

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
		if (Configuration::get('NOW_CATEG_SLIDE_ENABLE')) {
			$this->context->controller->addCSS($this->_path . 'css/now_category_slide.css', 'all');
			$this->context->controller->addJS($this->_path . 'js/now_category_slide.js');
		}
	}
}

