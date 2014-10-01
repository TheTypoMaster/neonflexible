<?php


class NowCategorySlide extends ObjectModel {
	public $id;

	/** @var integer ID */
	public $id_now_category_slide;

	/** @var integer column ID */
	public $id_shop;

	/** @var integer id_category */
	public $id_category;

	/** @var boolean Status for display */
	public $active = 1;

	/** @var  integer category position */
	public $position;

	/** @var string Object creation date */
	public $date_add;

	/** @var string Object last modification date */
	public $date_upd;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'now_category_slide',
		'primary' => 'id_now_category_slide',
		'multilang_shop' => true,
		'fields' => array(
			'id_shop'			=> array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
			'id_category'		=> array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
			'active'			=> array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'position'			=> array('type' => self::TYPE_INT),
			'date_add'			=> array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			'date_upd'			=> array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
		),
	);

	/**
	 * Lists of slides
	 * @param bool $active
	 * @return array
	 */
	public static function getCategorySlides($iIdLang = null, $bActive = true) {

		if (!Validate::isBool($bActive)) {
			die(Tools::displayError());
		}

		if (is_null($iIdLang)) {
			$iIdLang = (int)Context::getContext()->language->id;
		}

		$sSQL = '
			SELECT cs.`id_category`, cs.`position`
			FROM `' . _DB_PREFIX_ . 'now_category_slide` cs
			' . Shop::addSqlAssociation('now_category_slide', 'cs') .
			'WHERE 1 ' . ($bActive ? ' AND cs.`active` = 1 ' : '') . '
			ORDER BY cs.`position` ASC
		';

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sSQL);

		$aCategory = array();

		foreach ($result as $aRow) {
			$oCategory = new Category($aRow['id_category'], $iIdLang);
			$aCategory[$aRow['position']] = $oCategory;
		}

		return $aCategory;
	}
}
