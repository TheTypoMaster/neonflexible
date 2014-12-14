<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

class FeatureValue extends FeatureValueCore {

	/** @var  position */
	public $position;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'feature_value',
		'primary' => 'id_feature_value',
		'multilang' => true,
		'fields' => array(
			'id_feature' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
			'custom' => 	array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'position' => 	array('type' => self::TYPE_INT, 'validate' => 'isInt'),

			// Lang fields
			'value' => 		array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true, 'size' => 255),
		),
	);

	/**
	 * @param bool $autodate
	 * @param bool $nullValues
	 * @return bool
	 */
	public function add($autodate = true, $nullValues = false) {
		if ($this->position <= 0) {
			$this->position = FeatureValue::getHigherPosition($this->id_feature) + 1;
		}

		return parent::add($autodate, $nullValues);
	}

	/**
	 * @return bool
	 */
	public function delete() {
		$iIdFeature = $this->id_feature;

		$return = parent::delete();

		/* Reinitializing position */
		$this->cleanPositions($iIdFeature);

		return $return;
	}

	/**
	 * Move a feature
	 * @param boolean $way Up (1)  or Down (0)
	 * @param integer $position
	 * @return boolean Update result
	 */
	public function updatePosition($way, $position, $id_feature_value = null)
	{
		if (!$res = Db::getInstance()->executeS('
			SELECT `position`, `id_feature_value`
			FROM `'._DB_PREFIX_.'feature_value`
			WHERE `id_feature_value` = '.(int)($id_feature_value ? $id_feature_value : $this->id).' AND `id_feature` = ' . (int)$this->id_feature . '
			ORDER BY `position` ASC'
		))
			return false;

		foreach ($res as $featureValue)
			if ((int)$featureValue['id_feature_value'] == (int)$this->id)
				$moved_feature = $featureValue;

		if (!isset($moved_feature) || !isset($position))
			return false;

		// < and > statements rather than BETWEEN operator
		// since BETWEEN is treated differently according to databases
		return (Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'feature_value`
			SET `position`= `position` '.($way ? '- 1' : '+ 1').'
			WHERE `position`
			'.($way
					? '> '.(int)$moved_feature['position'].' AND `position` <= '.(int)$position
					: '< '.(int)$moved_feature['position'].' AND `position` >= '.(int)$position) .
			' AND `id_feature` = ' . (int)$this->id_feature)
			&& Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'feature_value`
			SET `position` = '.(int)$position.'
			WHERE `id_feature_value`='.(int)$moved_feature['id_feature_value']));
	}

	/**
	 * Reorder feature position
	 * Call it after deleting a feature.
	 *
	 * @return bool $return
	 */
	public static function cleanPositions($iIdFeature)
	{
		//Reordering positions to remove "holes" in them (after delete for instance)
		$sql = "SELECT id_feature_value, position FROM "._DB_PREFIX_."feature_value WHERE `id_feature` = " . (int)$iIdFeature . " ORDER BY position";
		$db = Db::getInstance();
		$r = $db->executeS($sql, false);
		$shiftTable = array(); //List of update queries (one query is necessary for each "hole" in the table)
		$currentDelta = 0;
		$minId = 0;
		$maxId = 0;
		$futurePosition = 0;
		while ($line = $db->nextRow($r))
		{
			$delta = $futurePosition - $line['position']; //Difference between current position and future position
			if ($delta != $currentDelta)
			{
				$shiftTable[] = array('minId' => $minId, 'maxId' => $maxId, 'delta' => $currentDelta);
				$currentDelta = $delta;
				$minId = $line['id_feature_value'];
			}
			$futurePosition++;
		}

		$shiftTable[] = array('minId' => $minId, 'delta' => $currentDelta);

		//Executing generated queries
		foreach ($shiftTable as $line)
		{
			$delta = $line['delta'];
			if ($delta == 0)
				continue;
			$delta = $delta > 0 ? '+'.(int)$delta : (int)$delta;
			$minId = $line['minId'];
			$sql = 'UPDATE '._DB_PREFIX_.'feature_value SET position = '.(int)$delta.' WHERE id_feature_value = '.(int)$minId;
			Db::getInstance()->execute($sql);
		}
	}

	/**
	 * getHigherPosition
	 *
	 * Get the higher feature position
	 *
	 * @return integer $position
	 */
	public static function getHigherPosition($iIdFeature)
	{
		$sql = 'SELECT MAX(`position`)
				FROM `'._DB_PREFIX_.'feature_value`
				WHERE `id_feature` = ' . (int)$iIdFeature;
		$position = DB::getInstance()->getValue($sql);
		return (is_numeric($position)) ? $position : -1;
	}

	/**
	 * @param $iIdFeature
	 * @return array
	 * @throws PrestaShopDatabaseException
	 */
	public static function getFeatureValues($iIdFeature)
	{
		return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT *
			FROM `' . _DB_PREFIX_ . 'feature_value` fv
			INNER JOIN `' . _DB_PREFIX_ . 'feature_value_lang` fvl ON (fvl.`id_feature_value` = fv.`id_feature_value` AND fvl.`id_lang` = ' . Context::getContext()->language->id . ')
			WHERE fv.`id_feature` = ' . (int)$iIdFeature . '
			ORDER BY fv.`position` DESC
		');
	}

}