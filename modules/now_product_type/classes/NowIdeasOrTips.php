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
	 * Get product accessories
	 *
	 * @param integer $id_lang Language id
	 * @return array Product accessories
	 */
	public static function getItems($iIdProduct, $id_lang, $active = true, Context $context = null)
	{
		if (!$context)
			$context = Context::getContext();

		$sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`description`, pl.`description_short`, pl.`link_rewrite`,
					pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, pl.`available_now`, pl.`available_later`,
					MAX(image_shop.`id_image`) id_image, il.`legend`, m.`name` as manufacturer_name, cl.`name` AS category_default,
					DATEDIFF(
						p.`date_add`,
						DATE_SUB(
							NOW(),
							INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY
						)
					) > 0 AS new
				FROM `'._DB_PREFIX_.'now_ideas_or_tips`
				LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = `id_product_2`
				'.Shop::addSqlAssociation('product', 'p').'
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (
					p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').'
				)
				LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (
					product_shop.`id_category_default` = cl.`id_category`
					AND cl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').'
				)
				LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product`)'.
			Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (p.`id_manufacturer`= m.`id_manufacturer`)
				'.Product::sqlStock('p', 0).'
				WHERE `id_product_1` = '.(int)$iIdProduct.
			($active ? ' AND product_shop.`active` = 1 AND product_shop.`visibility` != \'none\'' : '').'
				GROUP BY product_shop.id_product';

		if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql))
			return false;

		foreach ($result as &$row)
			$row['id_product_attribute'] = Product::getDefaultAttribute((int)$row['id_product']);
		return Product::getProductsProperties($id_lang, $result);
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
