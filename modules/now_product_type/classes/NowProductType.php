<?php


class NowProductType extends ObjectModel {
	public $id;

	/** @var integer ID */
	public $id_now_product_type;

	/** @var enum/string type : button, content */
	public $type;

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

	const TYPE_BUTTON		= 'BUTTON';
	const TYPE_CONTENT 		= 'CONTENT';

	/** @var array liste des types */
	public $typeList = array(NowProductType::TYPE_BUTTON, NowProductType::TYPE_CONTENT);

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'now_product_type',
		'primary' => 'id_now_product_type',
		'multilang' => true,
		'fields' => array(
			'active' 							=> array('type' => self::TYPE_BOOL, 	'validate' => 'isBool', 		'required' => true),
			'type' 								=> array('type' => self::TYPE_STRING, 	'validate' => 'isCatalogName', 	'required' => true),
			'date_add' 							=> array('type' => self::TYPE_DATE, 	'validate' => 'isDate'),
			'date_upd' 							=> array('type' => self::TYPE_DATE, 	'validate' => 'isDate'),

			// Lang fields
			'name' 								=> array('type' => self::TYPE_STRING, 	'lang' => true, 'validate' => 'isCatalogName', 'required' => true, 'size' => 255),
			'button_name' 						=> array('type' => self::TYPE_STRING, 	'lang' => true, 'validate' => 'isCatalogName', 'required' => true, 'size' => 255),
		),
	);

	/**
	 * Permet de récupèrer tous les types de produits
	 * @param null $iIdLang
	 * @param bool $bActive
	 * @return array
	 * @throws PrestaShopDatabaseException
	 */
	public static function getItems($iIdLang = null, $bActive = true) {

		if (!Validate::isBool($bActive)) {
			die(Tools::displayError());
		}

		if (is_null($iIdLang)) {
			$iIdLang = (int)Context::getContext()->language->id;
		}

		$sSQL = '
			SELECT *
			FROM `'._DB_PREFIX_.'now_product_type` pt
			INNER JOIN `'._DB_PREFIX_.'now_product_type_lang` ptl ON (pt.`id_now_product_type` = ptl.`id_now_product_type` AND ptl.`id_lang` = ' . (int)$iIdLang .')
			WHERE 1  '.($bActive ? 'AND pt.`active` = 1' : '');

		$aProductTypes = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sSQL);

		return $aProductTypes;
	}

	/**
	 * @param array $aIdProductType
	 * @param null $iIdLang
	 * @param bool $bActive
	 * @return mixed
	 * @throws PrestaShopDatabaseException
	 */
	public static function getByIdProductTypes($aIdProductType = array(), $iIdLang = null, $bActive = true) {

		if (!Validate::isBool($bActive)) {
			die(Tools::displayError());
		}

		if (is_null($iIdLang)) {
			$iIdLang = (int)Context::getContext()->language->id;
		}

		$sSQL = '
			SELECT *
			FROM `'._DB_PREFIX_.'now_product_type` pt
			INNER JOIN `'._DB_PREFIX_.'now_product_type_lang` ptl ON (pt.`id_now_product_type` = ptl.`id_now_product_type` AND ptl.`id_lang` = ' . (int)$iIdLang .')
			WHERE 1  '.($bActive ? 'AND pt.`active` = 1' : '') . '
			AND pt.`id_now_product_type` IN (' . implode(',', $aIdProductType) . ')
		';

		$aResult = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sSQL);

		$aProductTypes = array();

		foreach ($aResult as $aRow) {
			$aProductTypes[$aRow['id_now_product_type']] = $aRow;
		}

		return $aProductTypes;
	}
}
