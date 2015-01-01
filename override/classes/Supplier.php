<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

class Supplier extends SupplierCore {

	/**
	 * Defines whether the name of supplier already exists or not
	 *
	 * @module now_seo_links
	 *
	 * @param $iIdSupplier
	 * @param $sName
	 * @param array $aShop
	 * @return bool
	 */
	public static function nameIsAlreadyUsed($iIdSupplier, $sName, $aShop = array()) {
		$sSQL = '
			SELECT 1
			FROM `'._DB_PREFIX_.'supplier` s
			LEFT JOIN `'._DB_PREFIX_.'supplier_shop` ss ON (ss.`id_supplier` = s.`id_supplier`)
			WHERE s.`name` = \''.pSQL($sName).'\'';

		if (count($aShop) > 0)
			$sSQL .= ' AND ss.`id_shop` IN ('.implode(',', $aShop).')';

		if ($iIdSupplier)
			$sSQL .= ' AND s.`id_supplier` != '.(int)$iIdSupplier;

		return (bool)Db::getInstance()->executeS($sSQL);
	}

}