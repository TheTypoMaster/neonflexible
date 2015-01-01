<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

class Category extends CategoryCore {

	/**
	 * Defines whether the link_rewrite a category already exists or not
	 *
	 * @module now_seo_links
	 *
	 * @param $iIdCategory
	 * @param $sLinkRewrite
	 * @param $iIdLang
	 * @param array $aShop
	 * @return bool
	 */
	public static function linkRewriteIsAlreadyUsed($iIdCategory, $sLinkRewrite, $iIdLang, $aShop = array()) {
		if (!$sLinkRewrite)
			return false;

		$sSQL = '
			SELECT 1
			FROM `'._DB_PREFIX_.'category_lang` cl
			WHERE cl.`link_rewrite` = \''.pSQL($sLinkRewrite).'\'';

		if (count($aShop) > 0)
			$sSQL .= ' AND cl.`id_shop` IN ('.implode(',', $aShop).')';

		if ($iIdLang)
			$sSQL .= ' AND cl.`id_lang` = '.(int)$iIdLang;

		if ($iIdCategory)
			$sSQL .= ' AND cl.`id_category` != '.(int)$iIdCategory;

		return (bool)Db::getInstance()->executeS($sSQL);
	}

}