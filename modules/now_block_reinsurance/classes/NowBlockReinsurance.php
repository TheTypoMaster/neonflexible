<?php


define('_PS_IMG_DIR_BR', _PS_IMG_DIR_ . 'now_block_reinsurance');

class NowBlockReinsurance extends ObjectModel {
	public $id;

	/** @var integer ID */
	public $id_now_block_reinsurance;

	/** @var integer id shop */
	public $id_shop;

	/** @var integer id_cms */
	public $id_cms;

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

	/** @var string Description */
	public $description;

	/** @var string string used in rewrited URL */
	public $link;

	protected $image_dir = _PS_IMG_DIR_BR;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'now_block_reinsurance',
		'primary' => 'id_now_block_reinsurance',
		'multilang' => true,
		'fields' => array(
			'id_shop'			=> array('type' => self::TYPE_INT),
			'id_cms'			=> array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
			'active' 			=> array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'position' 			=> array('type' => self::TYPE_INT),
			'date_add' 			=> array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			'date_upd' 			=> array('type' => self::TYPE_DATE, 'validate' => 'isDate'),

			// Lang fields
			'name' 				=> array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'required' => true),
			'description' 		=> array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'required' => true),
			'link'				=> array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName'),
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
			SELECT `id_now_block_reinsurance`, `position`
			FROM `'._DB_PREFIX_.'now_block_reinsurance`
			ORDER BY `position` ASC'
		))
			return false;

		foreach ($res as $aNowBlocPresentation)
			if ((int)$aNowBlocPresentation['id_now_block_reinsurance'] == (int)$this->id)
				$moved_NowBlocPresentation = $aNowBlocPresentation;

		if (!isset($moved_NowBlocPresentation) || !isset($position))
			return false;

		$sql1 = '
			UPDATE `'._DB_PREFIX_.'now_block_reinsurance` SET `position`= `position` '.($way ? '- 1' : '+ 1').' WHERE `position`
			'.($way
				? '> '.(int)$moved_NowBlocPresentation['position'].' AND `position` <= '.(int)$position
				: '< '.(int)$moved_NowBlocPresentation['position'].' AND `position` >= '.(int)$position
			);

		$sql2 = '
			UPDATE `'._DB_PREFIX_.'now_block_reinsurance` SET `position` = '.(int)$position.' WHERE `id_now_block_reinsurance` = '.(int)$moved_NowBlocPresentation['id_now_block_reinsurance'];

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
		SELECT `id_now_block_reinsurance`
		FROM `'._DB_PREFIX_.'now_block_reinsurance`
		ORDER BY `position` ASC';
		$result = Db::getInstance()->executeS($sql);

		$i = 0;
		foreach ($result as $value)
			$return = Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'now_block_reinsurance`
			SET `position` = '.(int)$i++.'
			WHERE `id_now_block_reinsurance` = '.(int)$value['id_now_block_reinsurance']);
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
			$this->position = NowBlockReinsurance::getHigherPosition() + 1;

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
		NowBlockReinsurance::cleanPositions();

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
			SELECT r.*, rl.*, cl.meta_title as cms_name
			FROM `'._DB_PREFIX_.'now_block_reinsurance` r
			'.Shop::addSqlAssociation('now_block_reinsurance', 'r').'
			INNER JOIN `'._DB_PREFIX_.'now_block_reinsurance_lang` rl ON (r.`id_now_block_reinsurance` = rl.`id_now_block_reinsurance` AND rl.`id_lang` = ' . (int)$iIdLang .')
			LEFT JOIN `'._DB_PREFIX_.'cms_lang` cl ON (cl.`id_cms` = r.`id_cms` AND cl.`id_lang` = ' . (int)$iIdLang .')
			WHERE 1 '.($bActive ? ' AND r.`active` = 1 ' : '').'
			ORDER BY r.`position` ASC';

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sSQL);

		$aNowBlockReinsurance = array();

		foreach ($result as $aRow) {
			$oNowBlockReinsurance = new NowBlockReinsurance($aRow['id_now_block_reinsurance'], $iIdLang);
			$aNowBlockReinsurance[$oNowBlockReinsurance->position] = $oNowBlockReinsurance;
		}

		return $aNowBlockReinsurance;
	}

	/**
	 * Image path
	 * @param string $dir
	 * @return string
	 */
	public function getImageLink($dir = _PS_IMG_) {
		return $dir . 'now_block_reinsurance' . DIRECTORY_SEPARATOR . $this->getImageName();
	}

	/**
	 * Image Name
	 * @return string
	 */
	public function getImageName() {
		return $this->id . '.png';
	}
}
