<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

require_once (_PS_MODULE_DIR_.'now_product_type/classes/Module.php');
require_once (_PS_MODULE_DIR_.'now_product_type/classes/NowProductType.php');
require_once (_PS_MODULE_DIR_.'now_product_type/classes/NowProductTypeProduct.php');
require_once (_PS_MODULE_DIR_.'now_product_type/classes/NowIdeasOrTips.php');

class now_product_type extends NowModule {

	private static $aNowIdeasOrTips = array();

	public function __construct()
	{
		$this->name				= 'now_product_type';
		$this->tab				= 'front_office_features';
		$this->version			= 2.2;
		$this->author			= 'NinjaOfWeb';
		$this->need_instance	= 0;

		parent::__construct();

		$this->displayName = $this->l('Manage Product Type');
		$this->description = $this->l('Manage types of yours products, add a tip block and ideas and import product types');

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
			'AdminNowProductType' => array(
				'parent' => 'AdminParentNinjaOfWeb',
				'name' => $this->l('Manage Product Type')
			),
			'AdminNowImportProductType' => array(
				'parent' => 'AdminParentNinjaOfWeb',
				'name' => $this->l('Import Product Type')
			),
			'AdminNowImportTipsAndIdeas' => array(
				'parent' => 'AdminParentNinjaOfWeb',
				'name' => $this->l('Import tips and ideas')
			)
		);
	}

	/**
	 * Define the list of SQL file to execute to install
	 */
	public function setSqlFileToInstall() {
		$this->aSqlFileToInstall = array(
			'1.0' => 'install.sql',
			'1.2' => 'install-1.2.sql',
			'2.1' => 'install-2.1.sql'
		);
	}

	/**
	 * @return bool
	 */
	public function install()
	{
		$this->aConfigurationDefaultSettings = array(
			'NOW_IMPORT_PRO_TYPE_FILE'			=> '.csv',
			'NOW_IMPORT_PRO_TYPE_SEPARATOR'		=> ';',
			'NOW_IMPORT_PRO_TYPE_DELIMITER'		=> 2,
			'NOW_IMPORT_PRO_TYPE_DECIMAL'		=> '.',
			'NOW_IMPORT_PRO_TYPE_CONVERT_UTF8'	=> 1,
			'NOW_IMPORT_PRO_TYPE_PAGINATION'	=> 50
		);

		return parent::install() &&
				$this->registerHook('actionProductUpdate') &&
				$this->registerHook('actionProductAdd') &&
				$this->registerHook('actionProductDelete') &&
				$this->registerHook('displayBackOfficeHeader') &&
				$this->registerHook('actionProductListOverride') &&
				$this->registerHook('displayProductButtons') &&
				$this->registerHook('displayProductTab') &&
				$this->registerHook('displayProductTabContent');
	}

	/**
	 * Hook actionProductUpdate
	 * @param $aParams
	 */
	public function hookActionProductUpdate($aParams) {

		if (isset($_POST['type_product']) && preg_match('#id_now_product_type_([0-9]*)#', $_POST['type_product'], $matches)) {

			$iIdNowProductType	= (int)$matches[1];
			$oProduct			= $aParams['product'];

			if (Validate::isLoadedObject($oProduct)) {
				$oProductTypeProduct = NowProductTypeProduct::getObjectByProductId($oProduct->id);
				if (Validate::isLoadedObject($oProductTypeProduct)) {
					$oProductTypeProduct->id_now_product_type = $iIdNowProductType;
					$oProductTypeProduct->update();
				} else {
					return $this->hookActionProductAdd($aParams);
				}
			}
		} else {
			return $this->hookActionProductDelete($aParams);
		}

		return false;
	}

	/**
	 * Hook actionProductAdd
	 * @param $aParams
	 */
	public function hookActionProductAdd($aParams) {

		if (isset($_POST['type_product']) && preg_match('#id_now_product_type_([0-9]*)#', $_POST['type_product'], $matches)) {

			$iIdNowProductType	= (int)$matches[1];
			$oProduct			= $aParams['product'];

			if (Validate::isLoadedObject($oProduct)) {
				$oProductTypeProduct = new NowProductTypeProduct();
				$oProductTypeProduct->id_now_product_type	= $iIdNowProductType;
				$oProductTypeProduct->id_product			= $oProduct->id;
				return $oProductTypeProduct->add();
			}
		} else {
			return $this->hookActionProductDelete($aParams);
		}

		return false;
	}

	/**
	 * Hook actionProductDelete
	 * @param $aParams
	 * @return bool
	 */
	public function hookActionProductDelete($aParams) {

		$oProduct = $aParams['product'];
		if (Validate::isLoadedObject($oProduct)) {
			$oProductTypeProduct = NowProductTypeProduct::getObjectByProductId($oProduct->id);
			if (Validate::isLoadedObject($oProductTypeProduct)) {
				return $oProductTypeProduct->delete();
			}
		}

		return false;
	}

	/**
	 * Hook displayBackOfficeHeader
	 * @param $aParams
	 * @return bool
	 */
	public function hookDisplayBackOfficeHeader($aParams) {
		if (in_array(get_class($this->context->controller), array('AdminProductsController', 'AdminProductsControllerCore')) && isset($_GET['id_product'])) {
			$this->context->smarty->assign(array(
				'aNowProductTypes'			=> NowProductType::getItems(),
				'oNowProductTypeProduct'	=> NowProductTypeProduct::getObjectByProductId($_GET['id_product']),
			));
		}
	}

	/**
	 * Hook actionProductListOverride
	 * @param $aParams
	 * @return bool
	 */
	public function hookActionProductListOverride($aParams) {
		if ((int)$aParams['nbProducts'] > 0) {
			$aProductsTypesProducts	= NowProductTypeProduct::getProductsById();
			$aProductsTypes			= NowProductType::getByIdProductTypes($aProductsTypesProducts);

			foreach ($aParams['catProducts'] as &$aProduct) {
				if (array_key_exists($aProduct['id_product'], $aProductsTypesProducts) && array_key_exists($aProductsTypesProducts[$aProduct['id_product']], $aProductsTypes)) {
					$aProduct['product_type'] = $aProductsTypes[$aProductsTypesProducts[$aProduct['id_product']]];
				}
			}
		}
	}

	/**
	 * Hook displayProductButtons
	 * @param $aParams
	 * @return bool
	 */
	public function hookDisplayProductButtons($aParams) {
		// Permet de tester si un produit est typer "sur commande" pour gérer le bouton directement dans le template
	}

	/**
	 * Hook displayProductTab
	 * @param $aParams
	 * @return bool
	 */
	public function hookDisplayProductTab($aParams) {
		if (Validate::isLoadedObject($aParams['product'])) {
			$aNowIdeasOrTips = self::getIdeasOrTipsByproductId($aParams['product']->id);

			$this->context->smarty->assign(array(
				'aNowIdeasOrTips' => $aNowIdeasOrTips
			));

			return $this->context->smarty->fetch($this->module_dir.'views/templates/hook/product-tab.tpl');
		}
	}

	/**
	 * Hook displayProductTabContent
	 * @param $aParams
	 * @return bool
	 */
	public function hookDisplayProductTabContent($aParams) {
		if (Validate::isLoadedObject($aParams['product'])) {
			$aNowIdeasOrTips = self::getIdeasOrTipsByproductId($aParams['product']->id);

			$this->context->smarty->assign(array(
				'aNowIdeasOrTips' => $aNowIdeasOrTips
			));

			return $this->context->smarty->fetch($this->module_dir.'views/templates/hook/product-tab-content.tpl');
		}
	}

	/**
	 * @param $iIdProduct
	 * @return mixed
	 */
	private static function getIdeasOrTipsByproductId($iIdProduct) {
		if (!array_key_exists($iIdProduct, self::$aNowIdeasOrTips)) {
			self::$aNowIdeasOrTips[$iIdProduct] = NowIdeasOrTips::getItems($iIdProduct, Context::getcontext()->language->id);
		}

		return self::$aNowIdeasOrTips[$iIdProduct];
	}


}