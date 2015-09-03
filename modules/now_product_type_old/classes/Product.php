<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

class NowProduct {

	public static function getIdProductByProductReference($sReference) {
		return Db::getInstance()->getValue('
			SELECT p.`id_product`
			FROM `'._DB_PREFIX_.'product` p
			WHERE p.`reference` = "'.pSQL($sReference).'"
		');
	}

	public static function isRealProduct($iIdProduct) {
		return Db::getInstance()->getValue('
			SELECT 1
			FROM `'._DB_PREFIX_.'product` p
			WHERE p.`id_product` = "'.pSQL($iIdProduct).'"
		');
	}

	/**
	 * Get product width light information
	 *
	 * @param array $aProducts Product id
	 * @return array Product
	 */
	public static function getProductsLight($aProducts) {
		$sql = 'SELECT p.`id_product`, p.`reference`, pl.`name`
				FROM `'._DB_PREFIX_.'product` p
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (
					p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)Context::getContext()->language->id.
					Shop::addSqlRestrictionOnLang('pl').'
				)
				WHERE p.`id_product` IN ("'.implode('","', $aProducts).'")
				OR p.`reference` IN ("'.implode('","', $aProducts).'")';

		return Db::getInstance()->executeS($sql);
	}

	/**
	 * Get product width light information
	 *
	 * @param array $iIdProduct Product id
	 * @return array Product
	 */
	public static function getProductLight($iIdProduct) {
		$sql = 'SELECT p.`id_product`, p.`reference`, pl.`name`
				FROM `'._DB_PREFIX_.'product` p
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (
					p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)Context::getContext()->language->id.
					Shop::addSqlRestrictionOnLang('pl').'
				)
				WHERE p.`id_product` = '.$iIdProduct;

		return Db::getInstance()->getRow($sql);
	}

}