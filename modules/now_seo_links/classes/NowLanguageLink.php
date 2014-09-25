<?php


class NowLanguageLink extends ObjectModel {
	public $id;

	/** @var integer ID */
	public $id_now_language_link;

	/** @var integer id shop */
	public $id_shop;

	/** @var integer id shop */
	public $id_lang;

	/** @var string foldername */
	public $folder_name;

	/** @var string Object creation date */
	public $date_add;

	/** @var string Object last modification date */
	public $date_upd;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'now_language_link',
		'primary' => 'id_now_language_link',
		'multilang_shop' => true,
		'fields' => array(
			'id_shop'			=> array('type' => self::TYPE_INT,		'validate' => 'isUnsignedInt', 'required' => true),
			'id_lang'			=> array('type' => self::TYPE_INT,		'validate' => 'isUnsignedInt', 'required' => true),
			'folder_name' 		=> array('type' => self::TYPE_STRING,	'validate' => 'isBool', 'required' => true),
			'date_add' 			=> array('type' => self::TYPE_DATE,		'validate' => 'isDate'),
			'date_upd' 			=> array('type' => self::TYPE_DATE,		'validate' => 'isDate')
		)
	);

	/**
	 * Permet de récupérer le nom du dossier pour l'url de la langue
	 * @param int $iIdLang
	 * @return array
	 */
	public static function getFolderNameByIdlang($iIdLang) {

		if (is_null($iIdLang)) {
			$iIdLang = (int)Context::getContext()->language->id;
		}

		$sSQL = '
			SELECT ll.`folder_name`
			FROM `'._DB_PREFIX_.'now_language_link` ll
			'.Shop::addSqlAssociation('now_language_link', 'll').'
			WHERE ll.`id_lang` = ' . (int)$iIdLang;

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sSQL);

		return $result;
	}

	/**
	 * Permet de récupérer le code_iso d'une langue à partir d'un nom de dossier
	 * @param string $sFolderName
	 * @return array
	 */
	public static function getIsoCodeByFolderName($sFolderName) {

		$sSQL = '
			SELECT l.`iso_code`
			FROM `'._DB_PREFIX_.'now_language_link` ll
			'.Shop::addSqlAssociation('now_language_link', 'll').'
			INNER JOIN `'._DB_PREFIX_.'lang` l ON (l.`id_lang` = ll.`id_lang`)
			WHERE ll.`folder_name` = "' . Tools::link_rewrite($sFolderName) . '"';

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sSQL);

		return $result;
	}
}
