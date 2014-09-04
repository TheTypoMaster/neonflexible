<?php

class CategoryController extends CategoryControllerCore {

    /**
     * Method init() : Initialize category controller with category_rewrite params
     *
     * @module now_seo_links
     *
     * @see CategoryControllerCore::init()
     */
    public function init()
    {
        // Get rewrite
        $sRewrite = Tools::getValue('category_rewrite', false);

        if ($sRewrite)
        {
            $iIdCategory = (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
				SELECT `id_category`
				FROM `'._DB_PREFIX_.'category_lang`
				WHERE `link_rewrite` = \''.$sRewrite.'\'
				AND `id_lang` = '.Context::getContext()->language->id
            );

            if ($iIdCategory)
                $_GET['id_category'] = $iIdCategory;
        }

        parent::init();

        // On vérifie si l'URL actuelle est correcte ou pas
        $goodUrl = Context::getContext()->link->getCategoryLink($this->category);

        if (!preg_match('#'.$_SERVER['REDIRECT_URL'].'#', $goodUrl)) {
            header('Status: 301 Moved Permanently', false, 301);
            header('Location: '.$goodUrl);
            exit;
        }
    }

}