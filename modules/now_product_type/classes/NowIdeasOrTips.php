<?php


class NowIdeasOrTips {

	/**
	 * Get product width light information
	 *
	 * @param int $iIdProduct Product id
	 * @return array Product
	 */
	public static function getProductLight($iIdProduct) {
		$sql = 'SELECT p.`id_product`, p.`reference`, pl.`name`
				FROM `'._DB_PREFIX_.'now_ideas_or_tips` it
				INNER JOIN `'._DB_PREFIX_.'product` p ON (it.`id_product_2` = p.`id_product`)
				INNER JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product`	AND pl.`id_lang` = ' . (int)Context::getContext()->language->id . Shop::addSqlRestrictionOnLang('pl') . ')
				WHERE it.`id_product_1` = ' . (int)$iIdProduct;

		return Db::getInstance()->executeS($sql);
	}

	/**
	 * Delete product ideas or tips
	 *
	 * @param $iIdProduct
	 * @return bool Deletion result
	 */
	public static function deleteIdeasOrTips($iIdProduct) {
		return Db::getInstance()->execute('
			DELETE FROM `'._DB_PREFIX_.'now_ideas_or_tips`
			WHERE `id_product_1` = '.(int)$iIdProduct
		);
	}

	/**
	 * Link ideas or tips with product
	 *
	 * @param $iIdProduct
	 * @param $aIdeasOrTips
	 * @return bool
	 */
	public static function changeIdeasOrTips($iIdProduct, $aIdeasOrTips) {
		$bResult = true;
		foreach ($aIdeasOrTips as $iIdIdeasOrTips) {
			$bResult &= Db::getInstance()->insert('now_ideas_or_tips', array(
				'id_product_1' => (int)$iIdProduct,
				'id_product_2' => (int)$iIdIdeasOrTips
			));
		}
		return $bResult;
	}
}
