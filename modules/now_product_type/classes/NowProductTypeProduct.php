<?php


class NowProductTypeProduct extends ObjectModel {

	public $id;

	/** @var integer ID */
	public $id_now_product_type_product;

	/** @var integer ID */
	public $id_now_product_type;

	/** @var integer ID */
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
		'table' => 'now_product_type_product',
		'primary' => 'id_now_product_type_product',
		'fields' => array(
			'id_now_product_type'	=> array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
			'id_product'			=> array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
			'active' 				=> array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'date_add' 				=> array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			'date_upd' 				=> array('type' => self::TYPE_DATE, 'validate' => 'isDate')
		),
	);

	/**
	 * Permet de récupèrer le type de produit
	 * @param $iIdProduct
	 * @return array
	 */
	public static function getObjectByProductId($iIdProduct) {

		$sSQL = '
			SELECT pt.`id_now_product_type_product`
			FROM `'._DB_PREFIX_.'now_product_type_product` pt
			'.Shop::addSqlAssociation('now_product_type_product', 'pt').'
			WHERE pt.`id_product` = '.(int)$iIdProduct;

		$iIdProductType = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sSQL);

		return new NowProductTypeProduct($iIdProductType);
	}
}
