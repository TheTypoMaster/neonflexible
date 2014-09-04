<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

class AdminManufacturersController extends AdminManufacturersControllerCore {

    /**
     * Method postProcess() : add or update manufacturer object
     *
     * @module now_seo_links
     * @return object Manufacturer
     *
     * @see AdminManufacturersControllerCore::postProcess()
     */
    public function postProcess() {

        $aShops = array_keys(Tools::getValue('checkBoxShopAsso_manufacturer', array()));

        // Check if name already exist
        if (Manufacturer::nameIsAlreadyUsed(Tools::getValue('id_manufacturer'), Tools::getValue('name'), $aShops)) {
            $this->errors[] = Tools::displayError('Ce nom de marque existe déjà et ne peut être utilisé une nouvelle fois.');
        }

        return parent::postProcess();
    }

}

