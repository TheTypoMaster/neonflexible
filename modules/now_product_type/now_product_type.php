<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

include (_PS_MODULE_DIR_.'now_product_type/classes/Module.php');
include (_PS_MODULE_DIR_.'now_product_type/classes/NowProductType.php');
include (_PS_MODULE_DIR_.'now_product_type/classes/NowProductTypeProduct.php');

class now_product_type extends NowModule {

	public function __construct()
	{
		$this->name				= 'now_product_type';
		$this->tab				= 'front_office_features';
		$this->version			= 1.4;
		$this->author			= 'NinjaOfWeb';
		$this->need_instance	= 0;

		parent::__construct();

		$this->displayName = $this->l('Manage Product Type');
		$this->description = $this->l('Manage types of yours products');

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
			'AdminProductType' => array(
				'parent' => 'AdminTools',
				'name' => $this->l('Manage Product Type')
			)
		);
	}

	/**
	 * Define the list of SQL file to execute to install
	 */
	public function setSqlFileToInstall() {
		$this->aSqlFileToInstall = array(
			1.0 => 'install.sql',
			1.2 => 'install-1.2.sql',
		);
	}

	/**
	 * @return bool
	 */
	public function install()
	{
		$this->aConfigurationDefaultSettings = array(

		);

		return parent::install() &&
				$this->registerHook('actionProductUpdate') &&
				$this->registerHook('actionProductAdd') &&
				$this->registerHook('actionProductDelete') &&
				$this->registerHook('displayBackOfficeHeader') &&
				$this->registerHook('actionProductListOverride') &&
				$this->registerHook('displayProductButtons');
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


}