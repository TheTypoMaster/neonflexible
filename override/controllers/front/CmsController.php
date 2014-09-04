<?php

class CmsController extends CmsControllerCore {

    /**
     * Method init() : Initialize cms controller with cms_rewrite params
     *
     * @module now_seo_links
     *
     * @see CmsControllerCore::init()
     */
    public function init()
    {
        // Get rewrite
        $sRewrite = Tools::getValue('cms_rewrite', false);

        if ($sRewrite)
        {
            $iIdCms = (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
				SELECT `id_cms`
				FROM `'._DB_PREFIX_.'cms_lang`
				WHERE `link_rewrite` = \''.$sRewrite.'\'
				AND `id_lang` = '.Context::getContext()->language->id
            );

            if ($iIdCms)
                $_GET['id_cms'] = $iIdCms;
        }

        // Get rewrite
        $sRewriteCategory = Tools::getValue('cms_category_rewrite', false);

        if ($sRewriteCategory)
        {
            $iIdCmsCategory = (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
				SELECT `id_cms_category`
				FROM `'._DB_PREFIX_.'cms_category_lang`
				WHERE `link_rewrite` = \''.$sRewriteCategory.'\'
				AND `id_lang` = '.Context::getContext()->language->id
            );

            if ($iIdCmsCategory)
                $_GET['id_cms_category'] = $iIdCmsCategory;
        }

        parent::init();
    }

}
