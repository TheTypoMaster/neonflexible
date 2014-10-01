<?php


class NowProductType extends ObjectModel {
	public $id;

	/** @var integer ID */
	public $id_now_product_type;

	/** @var integer id shop */
	public $id_shop;

	/** @var boolean Status for display */
	public $active = 1;

	/** @var string Object creation date */
	public $date_add;

	/** @var string Object last modification date */
	public $date_upd;

	/** @var string Name */
	public $name;

	/** @var string button name */
	public $button_name;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'now_product_type',
		'primary' => 'id_now_product_type',
		'multilang' => true,
		'multilang_shop' => true,
		'fields' => array(
			'id_shop'							=> array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
			'active' 							=> array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'date_add' 							=> array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			'date_upd' 							=> array('type' => self::TYPE_DATE, 'validate' => 'isDate'),

			// Lang fields
			'name' 								=> array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'required' => true, 'size' => 255),
			'button_name' 						=> array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'required' => true, 'size' => 255),
		),
	);
}
