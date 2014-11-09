<?php


class NowBlockFooterCms extends ObjectModel {
	public $id;

	/** @var integer ID */
	public $id_now_block_cms_footer;

	/** @var integer column ID */
	public $id_now_block_cms_footer_column;

	/** @var integer id shop */
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
		'table' => 'now_block_cms_footer',
		'primary' => 'id_now_block_cms_footer',
		'multilang' => true,
		'fields' => array(
			'id_shop'							=> array('type' => self::TYPE_INT),
			'id_now_block_cms_footer_column'	=> array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
			'type'								=> array('type' => self::TYPE_STRING,	'validate' => 'isCatalogName', 'required' => true),
			'id_type'							=> array('type' => self::TYPE_INT,		'validate' => 'isUnsignedInt'),
			'active' 							=> array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'position' 							=> array('type' => self::TYPE_INT),
			'date_add' 							=> array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			'date_upd' 							=> array('type' => self::TYPE_DATE, 'validate' => 'isDate'),

			// Lang fields
			'name' 								=> array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'size' => 255),
			'link'				=> array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl'),
		),
	);

	public function getFields()
	{
		$fields = parent::getFields();

		if ($this->id_shop)
			$fields['id_shop'] = (int)$this->id_shop;
		else
			$fields['id_shop'] = Context::getContext()->shop->id;

		return $fields;
	}

	/**
	 * Moves a bloc presentation
	 *
	 * @param boolean $way Up (1) or Down (0)
	 * @param integer $position
	 * @return boolean Update result
	 */
	public function updatePosition($way, $position)
	{
		if (!$res = Db::getInstance()->executeS('
			SELECT `id_now_block_cms_footer`, `position`
			FROM `'._DB_PREFIX_.'now_block_cms_footer`
			WHERE `id_now_block_cms_footer_column` = ' . (int)$this->id_now_block_cms_footer_column . '
			ORDER BY `position` ASC'
		))
			return false;

		foreach ($res as $aNowBlockFooterCms)
			if ((int)$aNowBlockFooterCms['id_now_block_cms_footer'] == (int)$this->id)
				$moved_NowBlockFooterCms = $aNowBlockFooterCms;

		if (!isset($moved_NowBlockFooterCms) || !isset($position))
			return false;

		$sql1 = '
			UPDATE `'._DB_PREFIX_.'now_block_cms_footer` SET `position`= `position` '.($way ? '- 1' : '+ 1').' WHERE `id_now_block_cms_footer_column` = ' . (int)$this->id_now_block_cms_footer_column . ' AND `position`
			'.($way
				? '> '.(int)$moved_NowBlockFooterCms['position'].' AND `position` <= '.(int)$position
				: '< '.(int)$moved_NowBlockFooterCms['position'].' AND `position` >= '.(int)$position
			);

		$sql2 = '
			UPDATE `'._DB_PREFIX_.'now_block_cms_footer` SET `position` = '.(int)$position.' WHERE `id_now_block_cms_footer` = '.(int)$moved_NowBlockFooterCms['id_now_block_cms_footer'];

		return (
			Db::getInstance()->execute($sql1) &&
			Db::getInstance()->execute($sql2)
		);
	}

	/**
	 * Reorders positions.
	 * Called after deleting a carrier.
	 *
	 * @return bool $return
	 */
	public static function cleanPositions($id_now_block_cms_footer_column)
	{
		$return = true;

		$sql = '
		SELECT `id_now_block_cms_footer`
		FROM `'._DB_PREFIX_.'now_block_cms_footer`
		WHERE `id_now_block_cms_footer_column` = ' . (int)$id_now_block_cms_footer_column . '
		ORDER BY `position` ASC';
		$result = Db::getInstance()->executeS($sql);

		$i = 0;
		foreach ($result as $value)
			$return = Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'now_block_cms_footer`
			SET `position` = '.(int)$i++.'
			WHERE `id_now_block_cms_footer` = '.(int)$value['id_now_block_cms_footer']);
		return $return;
	}

	/**
	 * Gets the highest carrier position
	 *
	 * @return int $position
	 */
	public static function getHigherPosition($id_now_block_cms_footer_column)
	{
		$sql = 'SELECT MAX(`position`)
				FROM `'._DB_PREFIX_.'now_block_cms_footer`
				WHERE `id_now_block_cms_footer_column` = ' . (int)$id_now_block_cms_footer_column;
		$position = DB::getInstance()->getValue($sql);
		return (is_numeric($position)) ? $position : -1;
	}

	/**
	 * @param bool $autodate
	 * @param bool $null_values
	 * @return bool
	 * @throws PrestaShopException
	 */
	public function add($autodate = true, $null_values = false)
	{
		if ($this->position <= 0)
			$this->position = NowBlockFooterCms::getHigherPosition($this->id_now_block_cms_footer_column) + 1;

		if (!parent::add($autodate, $null_values) || !Validate::isLoadedObject($this))
			return false;

		return true;
	}

	/**
	 * @see ObjectModel::delete()
	 */
	public function delete() {
		$id_now_block_cms_footer_column = $this->id_now_block_cms_footer_column;

		if (!parent::delete())
			return false;

		NowBlockFooterCms::cleanPositions($id_now_block_cms_footer_column);
	}

	/**
	 * Lists of links
	 * @param bool $active
	 * @return array
	 */
	public static function getLinks($iIdLang = null, $bActive = true) {

		if (!Validate::isBool($bActive)) {
			die(Tools::displayError());
		}

		if (is_null($iIdLang)) {
			$iIdLang = (int)Context::getContext()->language->id;
		}

		$sSQL = '
			SELECT *
			FROM `'._DB_PREFIX_.'now_block_cms_footer` f
			'.Shop::addSqlAssociation('now_block_cms_footer', 'f').'
			INNER JOIN `'._DB_PREFIX_.'now_block_cms_footer_lang` fl ON (f.`id_now_block_cms_footer` = fl.`id_now_block_cms_footer` AND fl.`id_lang` = ' . (int)$iIdLang .')
			INNER JOIN `'._DB_PREFIX_.'now_block_cms_footer_column` c ON (c.`id_now_block_cms_footer_column` = f.`id_now_block_cms_footer_column`)
			WHERE 1  '.($bActive ? 'AND f.`active` = 1' : '').'
			ORDER BY c.`position` ASC, f.`position` ASC';

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sSQL);

		$aNowBlockFooterCms = array();

		foreach ($result as $aRow) {
			$oNowBlockFooterCms = new NowBlockFooterCms($aRow['id_now_block_cms_footer'], $iIdLang);
			$aNowBlockFooterCms[$oNowBlockFooterCms->id_now_block_cms_footer] = $oNowBlockFooterCms;
		}

		return $aNowBlockFooterCms;
	}
}
