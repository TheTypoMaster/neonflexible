<?php
/*
 * 2015
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

class Product extends ProductCore {

	/**
	 * Permet d'ajouter des propriété supplémentaires
	 * @param $id_lang
	 * @param $query_result
	 * @return array
	 * @module now_product_type
	 */
	public static function getProductsProperties($id_lang, $query_result) {
		$products = parent::getProductsProperties($id_lang, $query_result);

		require_once (_PS_MODULE_DIR_.'now_product_type/classes/NowProductType.php');
		require_once (_PS_MODULE_DIR_.'now_product_type/classes/NowProductTypeProduct.php');

		if ((int)count($products) > 0) {
			$aProductsTypesProducts	= NowProductTypeProduct::getProductsById();
			$aProductsTypes			= NowProductType::getByIdProductTypes($aProductsTypesProducts);

			foreach ($products as &$aProduct) {
				if (array_key_exists($aProduct['id_product'], $aProductsTypesProducts) && array_key_exists($aProductsTypesProducts[$aProduct['id_product']], $aProductsTypes)) {
					$aProduct['product_type'] = $aProductsTypes[$aProductsTypesProducts[$aProduct['id_product']]];
				}
			}
		}

		return $products;
	}

}