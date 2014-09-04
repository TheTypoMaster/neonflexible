<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

class AdminCmsCategoriesController extends AdminCmsCategoriesControllerCore {

    /**
     * Method processSave() : add or update cms category object
     *
     * @module now_seo_links
     * @return object CMS Category
     *
     * @see AdminCmsCategoriesControllerCore::processSave()
     */
    public function processSave() {

        $iIdCmsCategory = Tools::getValue('id_cms_category', false);

        $aLinkRewrite = array();

        foreach (Language::getLanguages(true) as $aLang) {
            if (array_key_exists('link_rewrite_'.(int)$aLang['id_lang'], $_POST)) {
                $aLinkRewrite[(int)$aLang['id_lang']] = Tools::getValue('link_rewrite_'.(int)$aLang['id_lang']);
            }
        }

        // Check if name already exist
        foreach ($aLinkRewrite as $iIdLang => $sLinkRewrite) {
            if (CMSCategory::linkRewriteIsAlreadyUsed($iIdCmsCategory, $sLinkRewrite, $iIdLang)) {
                $this->errors[] = sprintf(
                    Tools::displayError('Ce link_rewrite "%s" (%s) existe déjà pour une autre catégorie de CMS et ne peut être utilisé une nouvelle fois.'),
                    $sLinkRewrite,
                    Language::getIsoById($iIdLang)
                );
            }
        }

        return parent::processSave();
    }

}
