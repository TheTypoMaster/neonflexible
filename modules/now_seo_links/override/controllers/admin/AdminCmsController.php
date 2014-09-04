<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

class AdminCmsController extends AdminCmsControllerCore
{
    /**
     * Method postProcess() : add or update cms object
     *
     * @module now_seo_links
     * @return object CMS
     *
     * @see AdminCmsControllerCore::postProcess()
     */
    public function postProcess() {

        if (Tools::isSubmit('submitAddcms') || Tools::isSubmit('submitAddcmsAndPreview')) {
            $iIdCms = Tools::getValue('id_cms', false);
            $aShops = array_keys(Tools::getValue('checkBoxShopAsso_cms', array()));

            $aLinkRewrite = array();

            foreach (Language::getLanguages(true) as $aLang) {
                if (array_key_exists('link_rewrite_'.(int)$aLang['id_lang'], $_POST)) {
                    $aLinkRewrite[(int)$aLang['id_lang']] = Tools::getValue('link_rewrite_'.(int)$aLang['id_lang']);
                }
            }

            // Check if name already exist
            foreach ($aLinkRewrite as $iIdLang => $sLinkRewrite) {
                if (CMS::linkRewriteIsAlreadyUsed($iIdCms, $sLinkRewrite, $iIdLang, $aShops)) {
                    $this->errors[] = sprintf(
                        Tools::displayError('Ce link_rewrite "%s" (%s) existe déjà pour une autre page CMS et ne peut être utilisé une nouvelle fois.'),
                        $sLinkRewrite,
                        Language::getIsoById($iIdLang)
                    );
                }
            }
        }

        return parent::postProcess();
    }

}