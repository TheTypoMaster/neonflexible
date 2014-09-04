<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

class CMSCategory extends CMSCategoryCore {

    /**
     * Defines whether the link_rewrite a category cms is existing or not
     *
     * @module now_seo_links
     *
     * @param $iIdCmsCategory
     * @param $sLinkRewrite
     * @param $iIdLang
     * @return bool
     */
    public static function linkRewriteIsAlreadyUsed($iIdCmsCategory, $sLinkRewrite, $iIdLang) {
        if (!$sLinkRewrite)
            return false;

        $sSQL = '
            SELECT 1
            FROM `'._DB_PREFIX_.'cms_category_lang` ccl
            WHERE ccl.`link_rewrite` = \''.pSQL($sLinkRewrite).'\'';

        if ($iIdLang)
            $sSQL .= ' AND ccl.`id_lang` = '.(int)$iIdLang;

        if ($iIdCmsCategory)
            $sSQL .= ' AND ccl.`id_cms_category` != '.(int)$iIdCmsCategory;

        return (bool)Db::getInstance()->executeS($sSQL);
    }

}


