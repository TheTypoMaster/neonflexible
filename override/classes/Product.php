<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

class Product extends ProductCore {

	/**
	 * Defines whether the link_rewrite a product already exists or not
	 *
	 * @module now_seo_links
	 *
	 * @param $iIdProduct
	 * @param $sLinkRewrite
	 * @param $iIdLang
	 * @param array $aShop
	 * @return bool
	 */
	public static function linkRewriteIsAlreadyUsed($iIdProduct, $sLinkRewrite, $iIdLang, $aShop = array()) {
		if (!$sLinkRewrite)
			return false;

		$sSQL = '
			SELECT 1
			FROM `'._DB_PREFIX_.'product_lang` pl
			WHERE pl.`link_rewrite` = \''.pSQL($sLinkRewrite).'\'';

		if (count($aShop) > 0)
			$sSQL .= ' AND pl.`id_shop` IN ('.implode(',', $aShop).')';

		if ($iIdLang)
			$sSQL .= ' AND pl.`id_lang` = '.(int)$iIdLang;

		if ($iIdProduct)
			$sSQL .= ' AND pl.`id_product` != '.(int)$iIdProduct;

		return (bool)Db::getInstance()->executeS($sSQL);
	}

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