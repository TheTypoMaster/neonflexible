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
			'id_shop'			=> array('type' => self::TYPE_INT),
			'id_category'		=> array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
			'active'			=> array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'position'			=> array('type' => self::TYPE_INT),
			'date_add'			=> array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			'date_upd'			=> array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
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
			SELECT `id_now_category_slide`, `position`
			FROM `'._DB_PREFIX_.'now_category_slide`
			ORDER BY `position` ASC'
		))
			return false;

		foreach ($res as $aNowCategorySlide)
			if ((int)$aNowCategorySlide['id_now_category_slide'] == (int)$this->id)
				$moved_NowCategorySlide = $aNowCategorySlide;

		if (!isset($moved_NowCategorySlide) || !isset($position))
			return false;

		$sql1 = '
			UPDATE `'._DB_PREFIX_.'now_category_slide` SET `position`= `position` '.($way ? '- 1' : '+ 1').' WHERE `position`
			'.($way
				? '> '.(int)$moved_NowCategorySlide['position'].' AND `position` <= '.(int)$position
				: '< '.(int)$moved_NowCategorySlide['position'].' AND `position` >= '.(int)$position
			);

		$sql2 = '
			UPDATE `'._DB_PREFIX_.'now_category_slide` SET `position` = '.(int)$position.' WHERE `id_now_category_slide` = '.(int)$moved_NowCategorySlide['id_now_category_slide'];

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
		SELECT `id_now_category_slide`
		FROM `'._DB_PREFIX_.'now_category_slide`
		ORDER BY `position` ASC';
		$result = Db::getInstance()->executeS($sql);

		$i = 0;
		foreach ($result as $value)
			$return = Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'now_category_slide`
			SET `position` = '.(int)$i++.'
			WHERE `id_now_category_slide` = '.(int)$value['id_now_category_slide']);
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
				FROM `'._DB_PREFIX_.'now_block_reinsurance`';
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
			$this->position = NowCategorySlide::getHigherPosition() + 1;

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
		NowCategorySlide::cleanPositions();

	}

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
