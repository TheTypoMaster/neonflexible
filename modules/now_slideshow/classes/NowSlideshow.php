<?php


class NowSlideshow extends ObjectModel {
	public $id;

	/** @var integer ID */
	public $id_now_slideshow;

	/** @var integer column ID */
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

	/** @var string title */
	public $title;

	/** @var string button name */
	public $button_name;

	/** @var string description */
	public $description;

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
		'table' => 'now_slideshow',
		'primary' => 'id_now_slideshow',
		'multilang' => true,
		'fields' => array(
			'id_shop'			=> array('type' => self::TYPE_INT),
			'type'				=> array('type' => self::TYPE_STRING,	'validate' => 'isCatalogName', 'required' => true),
			'id_type'			=> array('type' => self::TYPE_INT,		'validate' => 'isUnsignedInt'),
			'active'			=> array('type' => self::TYPE_BOOL,		'validate' => 'isBool', 'required' => true),
			'position'			=> array('type' => self::TYPE_INT),
			'date_add'			=> array('type' => self::TYPE_DATE,		'validate' => 'isDate'),
			'date_upd'			=> array('type' => self::TYPE_DATE,		'validate' => 'isDate'),

			// Lang fields
			'name'				=> array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'required' => true, 'size' => 255),
			'title'				=> array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'required' => true, 'size' => 255),
			'button_name'		=> array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'required' => true, 'size' => 255),
			'description'		=> array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => true),
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
			SELECT `id_now_slideshow`, `position`
			FROM `'._DB_PREFIX_.'now_slideshow`
			ORDER BY `position` ASC'
		))
			return false;

		foreach ($res as $aNowSlideshow)
			if ((int)$aNowSlideshow['id_now_slideshow'] == (int)$this->id)
				$moved_NowSlideshow = $aNowSlideshow;

		if (!isset($moved_NowSlideshow) || !isset($position))
			return false;

		$sql1 = '
			UPDATE `'._DB_PREFIX_.'now_slideshow` SET `position`= `position` '.($way ? '- 1' : '+ 1').' WHERE `position`
			'.($way
				? '> '.(int)$moved_NowSlideshow['position'].' AND `position` <= '.(int)$position
				: '< '.(int)$moved_NowSlideshow['position'].' AND `position` >= '.(int)$position
			);

		$sql2 = '
			UPDATE `'._DB_PREFIX_.'now_slideshow` SET `position` = '.(int)$position.' WHERE `id_now_slideshow` = '.(int)$moved_NowSlideshow['id_now_slideshow'];

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
		SELECT `id_now_slideshow`
		FROM `'._DB_PREFIX_.'now_slideshow`
		ORDER BY `position` ASC';
		$result = Db::getInstance()->executeS($sql);

		$i = 0;
		foreach ($result as $value)
			$return = Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'now_slideshow`
			SET `position` = '.(int)$i++.'
			WHERE `id_now_slideshow` = '.(int)$value['id_now_slideshow']);
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
				FROM `'._DB_PREFIX_.'now_slideshow`';
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
			$this->position = NowSlideshow::getHigherPosition() + 1;

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
		NowSlideshow::cleanPositions();

	}

	/**
	 * Lists of slides
	 * @param bool $active
	 * @return array
	 */
	public static function getSlides($iIdLang = null, $bActive = true) {

		if (!Validate::isBool($bActive)) {
			die(Tools::displayError());
		}

		if (is_null($iIdLang)) {
			$iIdLang = (int)Context::getContext()->language->id;
		}

		$sSQL = '
			SELECT *
			FROM `'._DB_PREFIX_.'now_slideshow` s
			'.Shop::addSqlAssociation('now_slideshow', 's').'
			INNER JOIN `'._DB_PREFIX_.'now_slideshow_lang` sl ON (sl.`id_now_slideshow` = s.`id_now_slideshow` AND sl.`id_lang` = ' . $iIdLang . ')
			WHERE 1 '.($bActive ? ' AND s.`active` = 1 ' : '') . '
		';

		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sSQL);

		$aNowSlideshow = array();

		foreach ($result as $aRow) {
			$oNowSlideshow = new NowSlideshow($aRow['id_now_slideshow'], $iIdLang);
			$aNowSlideshow[$oNowSlideshow->position] = $oNowSlideshow;
		}

		return $aNowSlideshow;
	}

	/**
	 * Image path
	 * @param string $dir
	 * @return string
	 */
	public function getImageLink($dir = _PS_IMG_) {
		return $dir . 'now_slideshow' . DIRECTORY_SEPARATOR . $this->getImageName();
	}

	/**
	 * Image Name
	 * @return string
	 */
	public function getImageName() {
		return $this->id . '.jpg';
	}
}
