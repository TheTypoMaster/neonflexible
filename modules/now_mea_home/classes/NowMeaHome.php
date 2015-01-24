<?php


class NowMeaHome extends ObjectModel {
	public $id;

	/** @var integer ID */
	public $id_now_mea_home;

	/** @var integer id shop */
	public $id_shop;

	/** @var integer id shop */
	public $id_product;

	/** @var boolean Status for display */
	public $active = 1;

	/** @var string Object creation date */
	public $date_add;

	/** @var string Object last modification date */
	public $date_upd;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'now_mea_home',
		'primary' => 'id_now_mea_home',
		'multilang_shop' => true,
		'fields' => array(
			'id_shop'			=> array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
			'id_product'		=> array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
			'active' 			=> array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'date_add' 			=> array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			'date_upd' 			=> array('type' => self::TYPE_DATE, 'validate' => 'isDate')
		)
	);

	/**
	 * Lists of items
	 * @param int $iIdLang
	 * @param bool $active
	 * @return array
	 */
	public static function getProductsCollection($iIdLang = null, $bActive = true) {

		if (!Validate::isBool($bActive)) {
			die(Tools::displayError());
		}

		if (is_null($iIdLang)) {
			$iIdLang = (int)Context::getContext()->language->id;
		}

		$sSQL = '
			SELECT r.`id_product`
			FROM `'._DB_PREFIX_.'now_mea_home` r
			' . Shop::addSqlAssociation('now_mea_home', 'r') . '
			LEFT JOIN `'._DB_PREFIX_.'now_product_type_product` pt ON (pt.`id_product` = r.`id_product`)
			WHERE 1 ' . ($bActive ? ' AND r.`active` = 1 ' : '') . '
			AND pt.`id_now_product_type_product` IS NULL
			ORDER BY RAND() LIMIT 0 , ' . Configuration::get('NOW_MEA_HOME_NB_PRODUCT');

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sSQL);

		$aProducts = array();

		foreach ($result as $row) {
			$oProduct = new Product($row['id_product'], false, $iIdLang);
			$oProduct->loadStockData();
			$aProducts[] = $oProduct;
		}

		return $aProducts;
	}
}
