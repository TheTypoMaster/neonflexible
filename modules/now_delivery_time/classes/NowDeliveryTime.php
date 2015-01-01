<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

class NowDeliveryTime extends ObjectModel {
	public $id;

	/** @var integer ID */
	public $id_now_delivery_time;

	/** @var  integer ID */
	public $id_carrier;

	/** @var boolean */
	public $saturday_shipping	= 0;

	/** @var boolean */
	public $sunday_shipping		= 0;

	/** @var boolean */
	public $shipping_holidays	= 0;

	/** @var boolean */
	public $saturday_delivery	= 0;

	/** @var boolean */
	public $sunday_delivery		= 0;

	/** @var boolean */
	public $delivery_holidays	= 0;

	/** @var boolean */
	public $day_min				= 0;

	/** @var boolean */
	public $day_max				= 0;

	/** @var boolean */
	public $deleted				= 0;

	/** @var string Object creation date */
	public $date_add;

	/** @var string Object last modification date */
	public $date_upd;

	/** @var string Name */
	public $description;

	/** @var string Timeslot */
	public $timeslots;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'now_delivery_time',
		'primary' => 'id_now_delivery_time',
		'multilang' => true,
		'fields' => array(
			'id_shop'			=> array('type' => self::TYPE_INT),
			'id_carrier'		=> array('type' => self::TYPE_INT,	'validate' => 'isUnsignedInt', 'required' => true),
			'saturday_shipping'	=> array('type' => self::TYPE_BOOL,	'validate' => 'isBool', 'required' => true),
			'sunday_shipping'	=> array('type' => self::TYPE_BOOL,	'validate' => 'isBool', 'required' => true),
			'shipping_holidays'	=> array('type' => self::TYPE_BOOL,	'validate' => 'isBool', 'required' => true),
			'saturday_delivery'	=> array('type' => self::TYPE_BOOL,	'validate' => 'isBool', 'required' => true),
			'sunday_delivery'	=> array('type' => self::TYPE_BOOL,	'validate' => 'isBool', 'required' => true),
			'delivery_holidays'	=> array('type' => self::TYPE_BOOL,	'validate' => 'isBool', 'required' => true),
			'day_min'			=> array('type' => self::TYPE_INT,	'validate' => 'isUnsignedInt', 'required' => true),
			'day_max'			=> array('type' => self::TYPE_INT,	'validate' => 'isUnsignedInt', 'required' => true),
			'deleted'			=> array('type' => self::TYPE_BOOL,	'validate' => 'isBool', 'required' => true),
			'date_add'			=> array('type' => self::TYPE_DATE,	'validate' => 'isDate'),
			'date_upd'			=> array('type' => self::TYPE_DATE,	'validate' => 'isDate'),

			// Lang fields
			'description'		=> array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml'),
			'timeslot'			=> array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'size' => 255),
		),
	);
}
