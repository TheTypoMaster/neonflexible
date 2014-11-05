<?php
/*
* 2014
* Author: LEFEVRE LOIC
* Site: www.ninja-of-web.fr
* Mail: contact@ninja-of-web.fr
*/

class NowProduct {

	public static function getIdProductByProductReference($sReference) {
		return Db::getInstance()->getValue('
			SELECT p.`id_product`
			FROM `'._DB_PREFIX_.'product` p
			WHERE p.`reference` = "'.pSQL($sReference).'"
		');
	}

	public static function isRealProduct($iIdProduct) {
		return Db::getInstance()->getValue('
			SELECT 1
			FROM `'._DB_PREFIX_.'product` p
			WHERE p.`id_product` = "'.pSQL($iIdProduct).'"
		');
	}

	/**
	 * Get product width light information
	 *
	 * @param array $aProducts Product id
	 * @return array Product
	 */
	public static function getProductsLight($aProducts) {

		$aProductsID = array();

		// On supprime les quantités
		foreach ($aProducts as $sProduct) {
			preg_match('#([0-9A-Za-z]*)\(([0-9]*)\)#', $sProduct, $matches);
			$aProductsID[] = isset($matches[1]) ? $matches[1] : $sProduct;
		}

		$sql = 'SELECT p.`id_product`, p.`reference`, pl.`name`
				FROM `'._DB_PREFIX_.'product` p
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (
					p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)Context::getContext()->language->id.
					Shop::addSqlRestrictionOnLang('pl').'
				)
				WHERE p.`id_product` IN ("'.implode('", "', $aProductsID).'")
				OR p.`reference` IN ("'.implode('", "', $aProductsID).'")';

		return Db::getInstance()->executeS($sql);
	}

	/**
	 * Get product width light information
	 *
	 * @param array $iIdProduct Product id
	 * @return array Product
	 */
	public static function getProductLight($iIdProduct) {
		$sql = 'SELECT p.`id_product`, p.`reference`, pl.`name`
				FROM `'._DB_PREFIX_.'product` p
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (
					p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)Context::getContext()->language->id.
					Shop::addSqlRestrictionOnLang('pl').'
				)
				WHERE p.`id_product` = '.$iIdProduct;

		return Db::getInstance()->getRow($sql);
	}

	/**
	 * @param $iIdProduct
	 * @param $aAccessories
	 * @return bool
	 */
	public static function changeProductsPacks($iIdProduct, $aProductsPacks) {

		$bResult = true;

		$aProductsID = $aProductsCleaned = array();
		foreach ($aProductsPacks as $sProduct) {
			preg_match('#([0-9A-Za-z]*)\(([0-9]*)\)#', $sProduct, $matches);
			$aProductsID[(isset($matches[1]) ? $matches[1] : $sProduct)] = (int)(isset($matches[2]) ? $matches[2] : 1);
			$aProductsCleaned[] = (isset($matches[1]) ? $matches[1] : $sProduct);
		}

		$aProductsPacks	= NowProduct::getProductsLight($aProductsCleaned);

		foreach ($aProductsPacks as $aProductPack) {
			// On récupère la bonne quantité
			$aProductPack['pack_quantity']		= 1;
			if (array_key_exists($aProductPack['id_product'], $aProductsID)) {
				$aProductPack['pack_quantity']	= (int)$aProductsID[$aProductPack['id_product']];
			} elseif (array_key_exists($aProductPack['reference'], $aProductsID)) {
				$aProductPack['pack_quantity']	= (int)$aProductsID[$aProductPack['reference']];
			}

			$bResult &= PackCore::addItem($iIdProduct, $aProductPack['id_product'], $aProductPack['pack_quantity']);
		}
		return $bResult;
	}

}