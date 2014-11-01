<?php


define('_PS_IMG_DIR_BP', _PS_IMG_DIR_ . 'now_block_presentation');

class NowBlockPresentation extends ObjectModel {
	public $id;

	/** @var integer ID */
	public $id_now_block_presentation;

	/** @var integer id shop */
	public $id_shop;

	/** @var boolean Status for display */
	public $active = 1;

	/** @var  integer category position */
	public $position;

	/** @var  integer float img */
	public $float;

	/** @var string Object creation date */
	public $date_add;

	/** @var string Object last modification date */
	public $date_upd;

	/** @var string Name */
	public $name;

	/** @var string Description */
	public $description;

	/** @var string string used in rewrited URL */
	public $link;

	protected $image_dir = _PS_IMG_DIR_BP;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'now_block_presentation',
		'primary' => 'id_now_block_presentation',
		'multilang' => true,
		//'multilang_shop' => true,
		'fields' => array(
			'id_shop'			=> array('type' => self::TYPE_INT),
			'active' 			=> array('type' => self::TYPE_BOOL,		'validate' => 'isBool', 'required' => true),
			'position' 			=> array('type' => self::TYPE_INT),
			'float' 			=> array('type' => self::TYPE_STRING,	'validate' => 'isCatalogName', 'required' => true, 'size' => 10),
			'date_add' 			=> array('type' => self::TYPE_DATE,		'validate' => 'isDate'),
			'date_upd' 			=> array('type' => self::TYPE_DATE,		'validate' => 'isDate'),

			// Lang fields
			'name' 				=> array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'required' => true),
			'description' 		=> array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'required' => true),
			'link'				=> array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName')
		)
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
			SELECT `id_now_block_presentation`, `position`
			FROM `'._DB_PREFIX_.'now_block_presentation`
			ORDER BY `position` ASC'
		))
			return false;

		foreach ($res as $aNowBlocPresentation)
			if ((int)$aNowBlocPresentation['id_now_block_presentation'] == (int)$this->id)
				$moved_NowBlocPresentation = $aNowBlocPresentation;

		if (!isset($moved_NowBlocPresentation) || !isset($position))
			return false;

		$sql1 = '
			UPDATE `'._DB_PREFIX_.'now_block_presentation` SET `position`= `position` '.($way ? '- 1' : '+ 1').' WHERE `position`
			'.($way
				? '> '.(int)$moved_NowBlocPresentation['position'].' AND `position` <= '.(int)$position
				: '< '.(int)$moved_NowBlocPresentation['position'].' AND `position` >= '.(int)$position
			);

		$sql2 = '
			UPDATE `'._DB_PREFIX_.'now_block_presentation` SET `position` = '.(int)$position.' WHERE `id_now_block_presentation` = '.(int)$moved_NowBlocPresentation['id_now_block_presentation'];

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
		SELECT `id_now_block_presentation`
		FROM `'._DB_PREFIX_.'now_block_presentation`
		ORDER BY `position` ASC';
		$result = Db::getInstance()->executeS($sql);

		$i = 0;
		foreach ($result as $value)
			$return = Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'now_block_presentation`
			SET `position` = '.(int)$i++.'
			WHERE `id_now_block_presentation` = '.(int)$value['id_now_block_presentation']);
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
				FROM `'._DB_PREFIX_.'now_block_presentation`';
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
			$this->position = NowBlockPresentation::getHigherPosition() + 1;

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
		NowBlockPresentation::cleanPositions();

	}

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
			SELECT r.`id_now_block_presentation`
			FROM `'._DB_PREFIX_.'now_block_presentation` r
			'.Shop::addSqlAssociation('now_block_presentation', 'r').'
			WHERE 1 '.($bActive ? ' AND r.`active` = 1 ' : '').'
			ORDER BY r.`position` ASC';

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sSQL);

		$aNowBlockPresentation = array();

		foreach ($result as $aRow) {
			$oNowBlockPresentation = new NowBlockPresentation($aRow['id_now_block_presentation'], $iIdLang);
			$aNowBlockPresentation[$oNowBlockPresentation->position] = $oNowBlockPresentation;
		}

		return $aNowBlockPresentation;
	}

	/**
	 * Image path
	 * @param string $dir
	 * @return string
	 */
	public function getImageLink($dir = _PS_IMG_) {
		return $dir . 'now_block_presentation' . DIRECTORY_SEPARATOR . $this->getImageName();
	}

	/**
	 * Image Name
	 * @return string
	 */
	public function getImageName() {
		return $this->id . '.png';
	}
}
