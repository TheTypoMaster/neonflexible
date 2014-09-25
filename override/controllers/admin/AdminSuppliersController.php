<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

class AdminSuppliersController extends AdminSuppliersControllerCore {

	/**
	 * Method postProcess() : add or update supplier object
	 *
	 * @module now_seo_links
	 * @return object Supplier
	 *
	 * @see AdminSuppliersControllerCore::postProcess()
	 */
	public function postProcess() {

		$aShops = array_keys(Tools::getValue('checkBoxShopAsso_supplier', array()));

		// Check if name already exist
		if (Supplier::nameIsAlreadyUsed(Tools::getValue('id_supplier'), Tools::getValue('name'), $aShops)) {
			$this->errors[] = Tools::displayError('Ce nom de fournisseur existe déjà et ne peut être utilisé une nouvelle fois.');
		}

		return parent::postProcess();
	}
}

