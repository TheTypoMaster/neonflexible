<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

class CMS extends CMSCore {

	/**
	 * Defines whether the link_rewrite a cms page is existing or not
	 *
	 * @module now_seo_links
	 *
	 * @param $iIdCms
	 * @param $sLinkRewrite
	 * @param $iIdLang
	 * @param array $aShop
	 * @return bool
	 */
	public static function linkRewriteIsAlreadyUsed($iIdCms, $sLinkRewrite, $iIdLang, $aShop = array()) {
		if (!$sLinkRewrite)
			return false;

		$sSQL = '
			SELECT 1
			FROM `'._DB_PREFIX_.'cms_lang` cl';

		if (count($aShop) > 0)
			$sSQL .= ' LEFT JOIN `'._DB_PREFIX_.'cms_shop` cs ON (cs.`id_cms` = cl.`id_cms`) ';

		$sSQL .= ' WHERE cl.`link_rewrite` = \''.pSQL($sLinkRewrite).'\'';

		if (count($aShop) > 0)
			$sSQL .= ' AND cs.`id_shop` IN ('.implode(',', $aShop).')';

		if ($iIdLang)
			$sSQL .= ' AND cl.`id_lang` = '.(int)$iIdLang;

		if ($iIdCms)
			$sSQL .= ' AND cl.`id_cms` != '.(int)$iIdCms;

		return (bool)Db::getInstance()->executeS($sSQL);
	}

}