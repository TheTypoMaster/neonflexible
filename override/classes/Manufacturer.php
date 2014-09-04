<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

class Manufacturer extends ManufacturerCore {

    /**
     * Defines whether the name of manufacturer already exists or not
     *
     * @module now_seo_links
     *
     * @param $iIdManufacturer
     * @param $sName
     * @param array $aShop
     * @return bool
     */
    public static function nameIsAlreadyUsed($iIdManufacturer, $sName, $aShop = array()) {
        $sSQL = '
            SELECT 1
            FROM `'._DB_PREFIX_.'manufacturer` m
            LEFT JOIN `'._DB_PREFIX_.'manufacturer_shop` ms ON (ms.`id_manufacturer` = m.`id_manufacturer`)
            WHERE m.`name` = \''.pSQL($sName).'\'';

        if (count($aShop) > 0)
            $sSQL .= ' AND ms.`id_shop` IN ('.implode(',', $aShop).')';

        if ($iIdManufacturer)
            $sSQL .= ' AND m.`id_manufacturer` != '.(int)$iIdManufacturer;

        return (bool)Db::getInstance()->executeS($sSQL);
    }
}