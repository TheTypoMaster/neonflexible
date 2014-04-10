<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

class NowProduct {

	public static function getIdProductAndAttributeByReference($sReference) {
		$sql = '
			SELECT DISTINCT p.`id_product`, pa.`id_product_attribute`
			FROM `'._DB_PREFIX_.'product` p
			LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (pa.`id_product` = p.`id_product`)
			WHERE IFNULL (pa.`reference`, p.`reference`) = "'.pSQL($sReference).'"
		';

		return Db::getInstance()->getRow($sql);
	}

}