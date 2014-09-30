<?php


class NowSlideshow extends ObjectModel {
	public $id;

	/** @var integer ID */
	public $id_now_slideshow;

	/** @var integer column ID */
	public $id_shop;

	/** @var enum/string type : category, link, manufacturer, cms */
	public $type;

	/** @var integer id_type : category, link, manufacturer, cms */
	public $id_type;

	/** @var boolean Status for display */
	public $active = 1;

	/** @var  integer category position */
	public $position;

	/** @var string Object creation date */
	public $date_add;

	/** @var string Object last modification date */
	public $date_upd;

	/** @var string Name */
	public $name;

	/** @var string string used in rewrited URL */
	public $link;

	const TYPE_CATEGORY		= 'category';
	const TYPE_LINK			= 'link';
	const TYPE_MANUFACTURER = 'manufacturer';
	const TYPE_CMS 			= 'cms';

	/** @var array liste des types de liens */
	public $typeList = array(self::TYPE_CATEGORY, self::TYPE_LINK, self::TYPE_MANUFACTURER, self::TYPE_CMS);

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'now_slideshow',
		'primary' => 'id_now_slideshow',
		'multilang' => true,
		'fields' => array(
			'id_shop'			=> array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
			'type'				=> array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
			'id_type'			=> array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
			'active'			=> array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'position'			=> array('type' => self::TYPE_INT),
			'date_add'			=> array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			'date_upd'			=> array('type' => self::TYPE_DATE, 'validate' => 'isDate'),

			// Lang fields
			'name'				=> array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'required' => true, 'size' => 255),
			'title'				=> array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'required' => true, 'size' => 255),
			'button_name'		=> array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'required' => true, 'size' => 255),
			'description'		=> array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'required' => true, 'size' => 255),
			'link'				=> array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isLinkRewrite', 'required' => true, 'size' => 255),
		),
	);

	/**
	 * Lists of slides
	 * @param bool $active
	 * @return array
	 */
	public static function getSlides($iIdLang = null, $bActive = true) {

		if (!Validate::isBool($bActive)) {
			die(Tools::displayError());
		}

		if (is_null($iIdLang)) {
			$iIdLang = (int)Context::getContext()->language->id;
		}

		$sSQL = '
			SELECT *
			FROM `'._DB_PREFIX_.'now_slideshow` s
			'.Shop::addSqlAssociation('now_slideshow', 's').'
			INNER JOIN `'._DB_PREFIX_.'now_slideshow_lang` sl ON (sl.`id_now_slideshow` = s.`id_now_slideshow` AND sl.`id_lang` = ' . $iIdLang . ')
			WHERE 1 '.($bActive ? ' AND s.`active` = 1 ' : '') . '
		';

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sSQL);

		return $result;
	}
}
