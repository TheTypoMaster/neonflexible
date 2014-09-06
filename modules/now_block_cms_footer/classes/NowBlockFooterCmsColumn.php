<?php


class NowBlockFooterCmsColumn extends ObjectModel {

	public $id;

	/** @var integer ID */
	public $id_now_block_cms_footer_column;

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

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'now_block_cms_footer_column',
		'primary' => 'id_now_block_cms_footer_column',
		'multilang' => true,
		'fields' => array(
			'active' 							=> array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'position' 							=> array('type' => self::TYPE_INT),
			'date_add' 							=> array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			'date_upd' 							=> array('type' => self::TYPE_DATE, 'validate' => 'isDate'),

			// Lang fields
			'name' 								=> array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'required' => true, 'size' => 255)
		),
	);

	/**
	 * Lists of columns
	 * @param bool $active
	 * @return array
	 */
	public static function getColumns($iIdLang = null, $bActive = true) {

		if (!Validate::isBool($bActive)) {
			die(Tools::displayError());
		}

		if (is_null($iIdLang)) {
			$iIdLang = (int)Context::getContext()->language->id;
		}

		$sSQL = '
			SELECT *
			FROM `'._DB_PREFIX_.'now_block_cms_footer_column` c
			INNER JOIN `'._DB_PREFIX_.'now_block_cms_footer_column_lang` cl ON (c.`id_now_block_cms_footer_column` = cl.`id_now_block_cms_footer_column` AND cl.`id_lang` = ' . (int)$iIdLang .')
			WHERE 1  '.($bActive ? 'AND c.`active` = 1' : '').'
			ORDER BY c.`position` ASC';

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sSQL);

		return $result;
	}
}
