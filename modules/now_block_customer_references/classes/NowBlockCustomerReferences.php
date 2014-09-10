<?php


class NowBlockCustomerReferences extends ObjectModel {
	public $id;

	/** @var integer ID */
	public $id_now_block_customer_references;

	/** @var integer id shop */
	public $id_shop;

	/** @var boolean Status for display */
	public $active = 1;

	/** @var  integer category position */
	public $position;

	/** @var string Object creation date */
	public $date_add;

	/** @var string Object last modification date */
	public $date_upd;

	/** @var string */
	public $file_name;

	/** @var string Name */
	public $name;

	/** @var string Description */
	public $description;

	/** @var string string used in rewrited URL */
	public $link;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'now_block_customer_references',
		'primary' => 'id_now_block_customer_references',
		'multilang' => true,
		'multilang_shop' => true,
		'fields' => array(
			'id_shop'			=> array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
			'active' 			=> array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'position' 			=> array('type' => self::TYPE_INT),
			'date_add' 			=> array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			'date_upd' 			=> array('type' => self::TYPE_DATE, 'validate' => 'isDate'),

			// Lang fields
			'name' 				=> array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'required' => true, 'size' => 255),
			'description' 		=> array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'required' => true, 'size' => 255),
			'link'				=> array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isLinkRewrite', 'required' => true, 'size' => 255),
			'file_name'			=> array('type' => self::TYPE_STRING, 'validate' => 'isFileName'),
		)
	);

	/**
	 * Lists of items
	 * @param bool $active
	 * @return array
	 */
	public static function getItems($iIdLang = null, $bActive = true) {

		if (!Validate::isBool($bActive)) {
			die(Tools::displayError());
		}

		if (is_null($iIdLang)) {
			$iIdLang = (int)Context::getContext()->language->id;
		}

		$sSQL = '
			SELECT r.*, rl.*
			FROM `'._DB_PREFIX_.'now_block_customer_references` r
			'.Shop::addSqlAssociation('now_block_customer_references', 'r').'
			INNER JOIN `'._DB_PREFIX_.'now_block_customer_references_lang` rl ON (r.`id_now_block_customer_references` = rl.`id_now_block_customer_references` AND rl.`id_lang` = ' . (int)$iIdLang .')
			WHERE 1 '.($bActive ? ' AND r.`active` = 1 ' : '').'
			ORDER BY r.`position` ASC';

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sSQL);

		return $result;
	}
}
