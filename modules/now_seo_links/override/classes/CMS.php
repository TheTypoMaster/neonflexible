<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

class CMS extends CMSCore {

	/**
	 * @see CMSCore::$definition
	 */
	public static $definition = array(
		'table' => 'cms',
		'primary' => 'id_cms',
		'multilang' => true,
		'fields' => array(
			'id_cms_category' => 	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
			'position' => 			array('type' => self::TYPE_INT),
			'indexation' =>     	array('type' => self::TYPE_BOOL),
			'active' => 			array('type' => self::TYPE_BOOL),

			// Lang fields
			'meta_description' => 	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 2000),
			'meta_keywords' => 		array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'size' => 255),
			'meta_title' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true, 'size' => 128),
			'link_rewrite' => 		array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isLinkRewrite', 'required' => true, 'size' => 128),
			'content' => 			array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 3999999999999),
		),
	);

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

	/**
	 * Retourne un objet CMS à partir de son id_cms
	 * @param int $iIdsCms
	 * @param int|null $iIdLang
	 * @return CMS
	 */
	public static function getCmsObjectById($iIdsCms, $iIdLang = null) {

		if (is_null($iIdLang)) {
			$iIdLang = (int)Context::getContext()->language->id;
		}

		return new CMS($iIdsCms, $iIdLang);
	}

}