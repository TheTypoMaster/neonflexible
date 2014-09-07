<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

include (_PS_MODULE_DIR_.'now_block_cms_footer/classes/Module.php');
include (_PS_MODULE_DIR_.'now_block_cms_footer/classes/NowBlockFooterCms.php');
include (_PS_MODULE_DIR_.'now_block_cms_footer/classes/NowBlockFooterCmsColumn.php');

class now_block_cms_footer extends NowModule {

	public function __construct()
	{
		$this->name				= 'now_block_cms_footer';
		$this->tab				= 'front_office_features';
		$this->version			= 1.1;
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
			'AdminBlockFooterCms' => array(
				'parent' => 'AdminTools',
				'name' => $this->l('Manage Footer')
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
			$aColumns = NowBlockFooterCmsColumn::getColumns();

			// Lists of links
			$aLinks = NowBlockFooterCms::getLinks();

			$aLinksByColumnId		= array();
			foreach ($aLinks as $aLink) {
				$aNewLink = $aLink;

				if ($aLink['type'] == NowBlockFooterCms::TYPE_CMS) {
					$aNewLink['object'] = new CMS($aLink['id_type'], Context::getContext()->language->id);
				} elseif ($aLink['type'] == NowBlockFooterCms::TYPE_CATEGORY) {
					$aNewLink['object'] = new Category($aLink['id_type'], Context::getContext()->language->id);
				} elseif ($aLink['type'] == NowBlockFooterCms::TYPE_MANUFACTURER) {
					$aNewLink['object'] = new Manufacturer($aLink['id_type'], Context::getContext()->language->id);
				}

				$aLinksByColumnId[$aLink['id_now_block_cms_footer_column']][] = $aNewLink;
			}

			$aLinksByColumnIdGood	= array();
			foreach ($aLinksByColumnId as $id => $aColumn) {
				$aLinksByColumnIdGood[$id] = array_chunk($aColumn, Configuration::get('NOW_MAX_LINE_BY_COLUMN'));
			}

			$this->context->smarty->assign(array(
				'aLinksByColumnId'	=> $aLinksByColumnIdGood,
				'aColumns'			=> $aColumns
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

