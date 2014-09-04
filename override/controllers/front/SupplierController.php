<?php

class SupplierController extends SupplierControllerCore {

    /**
     * Method init() : Initialize supplier controller with supplier_rewrite params
     *
     * @module now_seo_links
     *
     * @see SupplierControllerCore::init()
     */
    public function init()
    {
        // Get rewrite
        $sRewrite = str_replace('-', '%', Tools::getValue('supplier_rewrite', false));

        if ($sRewrite)
        {
            $iIdSupplier = (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
				SELECT `id_supplier`
				FROM `'._DB_PREFIX_.'supplier`
				WHERE `name` LIKE \''.$sRewrite.'\'
			');

            if ($iIdSupplier)
                $_GET['id_supplier'] = $iIdSupplier;
        }

        parent::init();

        // On vérifie si l'URL actuelle est correcte ou pas
        $goodUrl = Context::getContext()->link->getSupplierLink($this->supplier);

        if (!preg_match('#'.$_SERVER['REDIRECT_URL'].'#', $goodUrl)) {
            header('Status: 301 Moved Permanently', false, 301);
            header('Location: '.$goodUrl);
            exit;
        }
    }
}