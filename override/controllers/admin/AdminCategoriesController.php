<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

class AdminCategoriesController extends AdminCategoriesControllerCore {

    /**
     * Method processSave() : add or update category object
     *
     * @module now_seo_links
     * @return object Category
     *
     * @see AdminCategoriesControllerCore::processSave()
     */
    public function processSave() {

        $iIdCategory = Tools::getValue('id_category');
        $aShops = array_keys(Tools::getValue('checkBoxShopAsso_category', array()));

        $aLinkRewrite = array();

        foreach (Language::getLanguages(true) as $aLang) {
            if (array_key_exists('link_rewrite_'.(int)$aLang['id_lang'], $_POST)) {
                $aLinkRewrite[(int)$aLang['id_lang']] = Tools::getValue('link_rewrite_'.(int)$aLang['id_lang']);
            }
        }

        // Check if name already exist
        foreach ($aLinkRewrite as $iIdLang => $sLinkRewrite) {
            if (Category::linkRewriteIsAlreadyUsed($iIdCategory, $sLinkRewrite, $iIdLang, $aShops)) {
                $this->errors[] = sprintf(
                    Tools::displayError('Ce link_rewrite "%s" (%s) existe déjà pour une autre catégorie et ne peut être utilisé une nouvelle fois.'),
                    $sLinkRewrite,
                    Language::getIsoById($iIdLang)
                    );
            }
        }

        return parent::processSave();
    }
}