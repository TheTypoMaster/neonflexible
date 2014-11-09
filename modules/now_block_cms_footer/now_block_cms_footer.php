<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

require_once (_PS_MODULE_DIR_ . 'now_block_cms_footer/classes/Module.php');
require_once (_PS_MODULE_DIR_ . 'now_block_cms_footer/classes/NowBlockFooterCms.php');
require_once (_PS_MODULE_DIR_ . 'now_block_cms_footer/classes/NowBlockFooterCmsColumn.php');

class now_block_cms_footer extends NowModule {

	public function __construct()
	{
		$this->name				= 'now_block_cms_footer';
		$this->tab				= 'front_office_features';
		$this->version			= 1.2;
		$this->author			= 'NinjaOfWeb';
		$this->need_instance	= 0;

		parent::__construct();

		$this->displayName = $this->l('Manage Footer');
		$this->description = $this->l('Manage block CMS on Footer');

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
			'AdminNowBlockFooterCms' => array(
				'parent' => 'AdminParentNinjaOfWeb',
				'name' => $this->l('Manage Footer')
			)
		);
	}

	/**
	 * Define the list of SQL file to execute to install
	 */
	public function setSqlFileToInstall() {
		$this->aSqlFileToInstall = array(
			'1.0' => 'install.sql',
			'1.2' => 'install-1.2.sql'
		);
	}

	/**
	 * @return bool
	 */
	public function install()
	{
		$this->aConfigurationDefaultSettings = array(
			'NOW_FOOTER_ENABLE'			=> true,
			'NOW_MAX_LINE_BY_COLUMN'	=> 5,
		);

		return parent::install() && $this->registerHook('footer') && $this->registerHook('header');
	}

	/**
	 * FOOTER
	 * @param $params
	 * @return mixed
	 */
	public function hookFooter($params) {
		if (Configuration::get('NOW_FOOTER_ENABLE')) {

			// Lists of columns
			$aNowBlockFooterCmsColumn = NowBlockFooterCmsColumn::getColumns();

			// Lists of links
			$aNowBlockFooterCms = NowBlockFooterCms::getLinks();

			$aNowBlockFooterCmsByColumnId		= array();
			foreach ($aNowBlockFooterCms as $oNowBlockFooterCms) {
				$oNewNowBlockFooterCms = $oNowBlockFooterCms;

				if ($oNowBlockFooterCms->type == NowBlockFooterCms::TYPE_CMS) {
					$oNewNowBlockFooterCms->object = new CMS($oNowBlockFooterCms->id_type, Context::getContext()->language->id);
				} elseif ($oNowBlockFooterCms->type == NowBlockFooterCms::TYPE_CATEGORY) {
					$oNewNowBlockFooterCms->object = new Category($oNowBlockFooterCms->id_type, Context::getContext()->language->id);
				} elseif ($oNowBlockFooterCms->type == NowBlockFooterCms::TYPE_MANUFACTURER) {
					$oNewNowBlockFooterCms->object = new Manufacturer($oNowBlockFooterCms->id_type, Context::getContext()->language->id);
				}

				$aNowBlockFooterCmsByColumnId[$oNowBlockFooterCms->id_now_block_cms_footer_column][] = $oNewNowBlockFooterCms;
			}

			$aNowBlockFooterCmsByColumnIdGood	= array();
			foreach ($aNowBlockFooterCmsByColumnId as $id => $aColumn) {
				$aNowBlockFooterCmsByColumnIdGood[$id] = array_chunk($aColumn, Configuration::get('NOW_MAX_LINE_BY_COLUMN'));
			}

			$this->context->smarty->assign(array(
				'aNowBlockFooterCmsByColumnIds'	=> $aNowBlockFooterCmsByColumnIdGood,
				'aNowBlockFooterCmsColumns'		=> $aNowBlockFooterCmsColumn
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
		if (Configuration::get('NOW_FOOTER_ENABLE')) {
			$this->context->controller->addCSS(($this->_path).'css/now_block_cms_footer.css', 'all');
		}
	}
}

