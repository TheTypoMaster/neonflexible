<?php


class NowBlockFooterCmsColumn extends ObjectModel {

	public $id;

	/** @var integer ID */
	public $id_now_block_cms_footer_column;

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
			'id_shop'							=> array('type' => self::TYPE_INT),
			'active' 							=> array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'position' 							=> array('type' => self::TYPE_INT),
			'date_add' 							=> array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			'date_upd' 							=> array('type' => self::TYPE_DATE, 'validate' => 'isDate'),

			// Lang fields
			'name' 								=> array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'required' => true, 'size' => 255)
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
			SELECT `id_now_block_cms_footer_column`, `position`
			FROM `'._DB_PREFIX_.'now_block_cms_footer_column`
			ORDER BY `position` ASC'
		))
			return false;

		foreach ($res as $aNowBlockFooterCmsColumn)
			if ((int)$aNowBlockFooterCmsColumn['id_now_block_cms_footer_column'] == (int)$this->id)
				$moved_NowBlockFooterCmsColumn = $aNowBlockFooterCmsColumn;

		if (!isset($moved_NowBlockFooterCmsColumn) || !isset($position))
			return false;

		$sql1 = '
			UPDATE `'._DB_PREFIX_.'now_block_cms_footer_column` SET `position`= `position` '.($way ? '- 1' : '+ 1').' WHERE `position`
			'.($way
				? '> '.(int)$moved_NowBlockFooterCmsColumn['position'].' AND `position` <= '.(int)$position
				: '< '.(int)$moved_NowBlockFooterCmsColumn['position'].' AND `position` >= '.(int)$position
			);

		$sql2 = '
			UPDATE `'._DB_PREFIX_.'now_block_cms_footer_column` SET `position` = '.(int)$position.' WHERE `id_now_block_cms_footer_column` = '.(int)$moved_NowBlockFooterCmsColumn['id_now_block_cms_footer_column'];

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
	public static function cleanPositions()
	{
		$return = true;

		$sql = '
		SELECT `id_now_block_cms_footer_column`
		FROM `'._DB_PREFIX_.'now_block_cms_footer_column`
		ORDER BY `position` ASC';
		$result = Db::getInstance()->executeS($sql);

		$i = 0;
		foreach ($result as $value)
			$return = Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'now_block_cms_footer_column`
			SET `position` = '.(int)$i++.'
			WHERE `id_now_block_cms_footer_column` = '.(int)$value['id_now_block_cms_footer_column']);
		return $return;
	}

	/**
	 * Gets the highest carrier position
	 *
	 * @return int $position
	 */
	public static function getHigherPosition()
	{
		$sql = 'SELECT MAX(`position`)
				FROM `'._DB_PREFIX_.'now_block_cms_footer_column`';
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
			$this->position = NowBlockFooterCmsColumn::getHigherPosition() + 1;

		if (!parent::add($autodate, $null_values) || !Validate::isLoadedObject($this))
			return false;

		return true;
	}

	/**
	 * @see ObjectModel::delete()
	 */
	public function delete() {
		if (!parent::delete())
			return false;
		NowBlockFooterCmsColumn::cleanPositions();

	}

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
			'.Shop::addSqlAssociation('now_block_cms_footer_column', 'c').'
			INNER JOIN `'._DB_PREFIX_.'now_block_cms_footer_column_lang` cl ON (c.`id_now_block_cms_footer_column` = cl.`id_now_block_cms_footer_column` AND cl.`id_lang` = ' . (int)$iIdLang .')
			WHERE 1  '.($bActive ? 'AND c.`active` = 1' : '').'
			ORDER BY c.`position` ASC';

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sSQL);

		return $result;
	}
}
