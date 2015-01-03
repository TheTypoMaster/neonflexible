<?php
/*
 * 2015
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

require_once(_PS_MODULE_DIR_ . 'now_delivery_time/classes/NowHolidays.php');

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

	const SATURDAY	= 'Sat';
	const SUNDAY	= 'Sun';

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

	/**
	 * Lists of delivery time
	 * @param bool $active
	 * @return array
	 */
	public static function getDeliveryTime($iIdLang = null) {

		if (is_null($iIdLang)) {
			$iIdLang = (int)Context::getContext()->language->id;
		}

		$sSQL = '
			SELECT dt.*, dtl.*, c.*
			FROM `' . _DB_PREFIX_ . 'now_delivery_time` dt
			' . Shop::addSqlAssociation('now_delivery_time', 'dt') . '
			INNER JOIN `' . _DB_PREFIX_ . 'now_delivery_time_lang` dtl ON (dt.`id_now_delivery_time` = dtl.`id_now_delivery_time` AND dtl.`id_lang` = ' . (int)$iIdLang . ')
			LEFT JOIN `' . _DB_PREFIX_ . 'carrier` c ON (c.`id_carrier` = dt.`id_carrier`)
			WHERE dt.`deleted` = 0';

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sSQL);

		foreach ($result as &$row) {
			if (file_exists(_PS_SHIP_IMG_DIR_ . $row['id_carrier'] . '.jpg')) {
				$row['logo'] = _THEME_SHIP_DIR_ . $row['id_carrier'] . '.jpg';
			} else {
				$row['logo'] = false;
			}

			// Définition de la date de livraison minimum
			$row['shipping_date_min'] = NowDeliveryTime::getDateMinDeliveryTime(
				new DateTime(),
				(int)$row['day_min'],
				(bool)$row['saturday_shipping'],
				(bool)$row['sunday_shipping'],
				(bool)$row['shipping_holidays'],
				(bool)$row['saturday_delivery'],
				(bool)$row['sunday_delivery'],
				(bool)$row['delivery_holidays']
			);
		}

		return $result;
	}

	/**
	 * Get the day minimum for the delivery
	 * @param DateTime $today
	 * @param int $iDayMin
	 * @param bool $bSaturdayShipping
	 * @param bool $bSundayShipping
	 * @param bool $bShippingHolidays
	 * @param bool $bSaturdayDelivery
	 * @param bool $bSundayDelivery
	 * @param bool $bDeliveryHolidays
	 * @return bool|string
	 */
	public static function getDateMinDeliveryTime(DateTime $today, $iDayMin = 0, $bSaturdayShipping = false, $bSundayShipping = false, $bShippingHolidays = false, $bSaturdayDelivery = false, $bSundayDelivery = false, $bDeliveryHolidays = false) {

		/**
		 * Defined the finish date of the order preparation
		 */
		if ($today->format('H') >= ((int)ConfigurationCore::get('NOW_DT_HOUR_END_PREP') - (int)ConfigurationCore::get('NOW_DT_HOUR_BEFORE_END_PREP'))) {
			$today->add(new DateInterval('P1D'));
		}

		// It's holidays ?
		$aHolidays = NowHolidays::getHolidaysOnToday($today, true);
		if (!empty($aHolidays)) {
			$today->add(new DateInterval('P' . ( (int)$aHolidays['dayDiff'] +1 ) . 'D'));
		}

		if ($iDayMin === 0) {
			return $today->format('Y-m-d');
		}

		/**
		 * Defined the finish date of the order shipping
		 */
		if ($today->format('D') == NowDeliveryTime::SATURDAY) {
			// The day of today is Saturday ?
			if (!$bSaturdayShipping) {
				$today->add(new DateInterval('P2D'));
			}
		} elseif ($today->format('D') == NowDeliveryTime::SUNDAY) {
			// The day of today is Sunday ?
			if (!$bSundayShipping) {
				$today->add(new DateInterval('P1D'));
			}
		}

		if ($bShippingHolidays) {
			// It's holydays ?
			$aHolidays = NowHolidays::getHolidaysOnToday($today, false, true);
			if (!empty($aHolidays)) {
				$today->add(new DateInterval('P' . ( (int)$aHolidays['dayDiff'] +1 ) . 'D'));
			}
		}

		/**
		 * Defined the finish date of the order delivery
		 */
		$today->add(new DateInterval('P' . (int)$iDayMin . 'D'));

		if ($today->format('D') == NowDeliveryTime::SATURDAY) {
			// The day of today is Saturday ?
			if (!$bSaturdayDelivery) {
				$today->add(new DateInterval('P2D'));
			}
		} elseif ($today->format('D') == NowDeliveryTime::SUNDAY) {
			// The day of today is Sunday ?
			if (!$bSundayDelivery) {
				$today->add(new DateInterval('P1D'));
			}
		}

		if ($bDeliveryHolidays) {
			// It's holydays ?
			$aHolidays = NowHolidays::getHolidaysOnToday($today, false, false, true);
			if (!empty($aHolidays)) {
				$today->add(new DateInterval('P' . ( (int)$aHolidays['dayDiff'] +1 ) . 'D'));
			}
		}

		return $today->format('Y-m-d');
	}
}
