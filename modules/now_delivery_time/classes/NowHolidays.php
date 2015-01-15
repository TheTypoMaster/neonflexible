<?php
/*
 * 2015
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

class NowHolidays extends ObjectModel {

	public $id;

	/** @var integer ID */
	public $id_now_holidays;

	/** @var  ENUM string */
	public $type;

	/** @var string Name */
	public $evenment_name;

	/** @var string date */
	public $date_start;

	/** @var string date */
	public $date_end;

	/** @var boolean */
	public $preparation	= 0;

	/** @var boolean */
	public $shipping	= 0;

	/** @var boolean */
	public $delivery	= 0;

	/** @var string Object creation date */
	public $date_add;

	/** @var string Object last modification date */
	public $date_upd;

	/** @var string Timeslot */
	public $timeslots;

	const PUBLIC_HOLIDAYS	= 'public_holidays';
	const HOLIDAYS			= 'holidays';

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'now_holidays',
		'primary' => 'id_now_holidays',
		'fields' => array(
			'id_shop'			=> array('type' => self::TYPE_INT),
			'type'				=> array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName'),
			'evenment_name'		=> array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName'),
			'date_start'		=> array('type' => self::TYPE_DATE,	'validate' => 'isDate'),
			'date_end'			=> array('type' => self::TYPE_DATE,	'validate' => 'isDate'),
			'preparation'		=> array('type' => self::TYPE_BOOL,	'validate' => 'isBool', 'required' => true),
			'shipping'			=> array('type' => self::TYPE_BOOL,	'validate' => 'isBool', 'required' => true),
			'delivery'			=> array('type' => self::TYPE_BOOL,	'validate' => 'isBool', 'required' => true),
			'date_add'			=> array('type' => self::TYPE_DATE,	'validate' => 'isDate'),
			'date_upd'			=> array('type' => self::TYPE_DATE,	'validate' => 'isDate')
		),
	);

	/**
	 * Permer de récupérer les jours de congés selon la date du jours
	 * @param DateTime $today
	 * @param bool $preparation
	 * @param bool $shipping
	 * @param bool $delivery
	 * @return array
	 * @throws PrestaShopDatabaseException
	 */
	public static function getHolidaysOnToday(DateTime $today, $preparation = false, $shipping = false, $delivery = false) {

		$sSQL = '
			SELECT h.`type`, h.`date_start`, h.`date_end`, h.`preparation`, h.`shipping`, h.`delivery`, DATEDIFF(DATE(h.`date_end`), "' . $today->format('Y-m-d') . '") AS dayDiff
			FROM `' . _DB_PREFIX_ . 'now_holidays` h
			' . Shop::addSqlAssociation('now_holidays', 'h') . '
			WHERE "' . $today->format('Y-m-d') . '" BETWEEN DATE(h.`date_start`) AND DATE(h.`date_end`) ';

		if ($preparation) {
			$sSQL .= ' AND h.`preparation` = 1';
		}

		if ($shipping) {
			$sSQL .= ' AND h.`shipping` = 1';
		}

		if ($delivery) {
			$sSQL .= ' AND h.`delivery` = 1';
		}

		return Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sSQL);
	}
}
