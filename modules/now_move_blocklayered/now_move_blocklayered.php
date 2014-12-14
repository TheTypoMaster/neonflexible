<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

require_once (_PS_MODULE_DIR_ . 'now_move_blocklayered/classes/Module.php');

class now_move_blocklayered extends NowModule {

	public function __construct()
	{
		$this->name				= 'now_move_blocklayered';
		$this->tab				= 'front_office_features';
		$this->version			= 1.0;
		$this->author			= 'NinjaOfWeb';
		$this->need_instance	= 0;

		parent::__construct();

		$this->displayName = $this->l('Move featured values');
		$this->description = $this->l('Ordered featured values on the filter list of blocklayered module.');

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
			'AdminNowFeatures' => array(
				'parent' => 'AdminParentNinjaOfWeb',
				'name' => $this->l('Move featured values')
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
	 * Define the list of SQL file to execute to uninstall
	 */
	public function setSqlFileToUninstall() {
		$this->aSqlFileToUninstall = array(
			'uninstall.sql'
		);
	}

	/**
	 * @return bool
	 */
	public function install() {
		return parent::install() && $this->updatePositionOfFeaturesValues() && $this->registerHook('footer');
	}

	/**
	 * @return bool
	 */
	public function updatePositionOfFeaturesValues() {

		$aFeatures = Feature::getFeatures(Context::getContext()->language->id);

		foreach ($aFeatures as $aFeature) {

			$r = Db::getInstance()->executeS('
				SELECT id_feature_value, position
				FROM ' . _DB_PREFIX_ . 'feature_value
				WHERE `id_feature` = ' . (int)$aFeature['id_feature'] . '
				ORDER BY id_feature_value', false
			);
			$futurePosition = 0;

			while ($line = Db::getInstance()->nextRow($r)) {
				Db::getInstance()->execute('
					UPDATE '._DB_PREFIX_.'feature_value
					SET position = ' . (int)$futurePosition . '
					WHERE id_feature_value = ' . (int)$line['id_feature_value']
				);
				$futurePosition++;
			}
		}

		return true;
	}

	/**
	 * Footer
	 * @param $params
	 * @return mixed
	 */
	public function hookFooter($params) {

		if (Context::getContext()->controller->php_self == 'category') {

			// Lists of featured with thim order
			$this->context->smarty->assign(array(
				'aFeatureLists'			=> Feature::getFeatures(Context::getContext()->language->id)
			));

			return $this->context->smarty->fetch($this->module_dir.'views/templates/hook/footer.tpl');
		}
	}
}

