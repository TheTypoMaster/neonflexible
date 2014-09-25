<?php
/**
  * PM_AdvancedTopMenu Front Office Feature
  *
  * @category menu
  * @authors Presta-Module.com <support@presta-module.com>
  * @copyright Presta-Module 2011-2014
  * +
  * +Languages: EN, FR
  * +PS version: 1.6, 1.5, 1.4, 1.3, 1.2, 1.1
  **/
class		AdvancedTopMenuColumnClass extends ObjectModel
{
	public 		$id;

	public 		$id_wrap;

	public 		$id_menu;

	public 		$id_category;

	public 		$id_cms;

	public 		$id_supplier;

	public 		$id_manufacturer;

	/** @var string Name */
	public 		$name;

	/** @var string Link */
	public 		$link;

	/** @var boolean Status for display */
	public 		$active = 1;

	public 		$type;

	public 		$privacy;

	public 		$have_icon;

	public 		$image_type;

	public 		$value_over;

	public 		$value_under;

	public 		$id_menu_depend;

	public 		$target;

	public		$is_column = true;

	protected $tables = array ('pm_advancedtopmenu_columns', 'pm_advancedtopmenu_columns_lang');

	protected 	$fieldsRequired = array('active','type','id_wrap','id_menu');
 	protected 	$fieldsSize = array('active' => 1);
 	protected 	$fieldsValidate = array('active' => 'isBool');
	protected 	$fieldsRequiredLang = array();
 	protected 	$fieldsSizeLang = array('name' => 64);
 	protected 	$fieldsValidateLang = array('name' => 'isCatalogName','link'=>'isUrl');

	protected 	$table = 'pm_advancedtopmenu_columns';
	protected 	$identifier = 'id_column';

	public static $definition = array(
		'table' => 'pm_advancedtopmenu_columns',
		'primary' => 'id_column',
		'multishop' => false,
		'multilang_shop' => false,
		'multilang' => true,
		'fields' => array(
			'name' => 				array('type' => 3, 'lang' => true, 'required' => false, 'size' => 64),
			'link' => 				array('type' => 3, 'lang' => true, 'required' => false),
			'value_over' => 		array('type' => 3, 'lang' => true, 'required' => false),
			'value_under' => 		array('type' => 3, 'lang' => true, 'required' => false)
		)
	);

	public function __construct($id_column = NULL, $id_lang = NULL)
	{
		if(version_compare(_PS_VERSION_, '1.3.0.0', '<')) {
			$this->fieldsValidateLang['value_over'] = 'isCleanHTML';
			$this->fieldsValidateLang['value_under'] = 'isCleanHTML';
		 }else {
		 	$this->fieldsValidateLang['value_over'] = 'isString';
			$this->fieldsValidateLang['value_under'] = 'isString';
		 }
		parent::__construct($id_column, $id_lang);
	}

	public function getFields()
	{
		parent::validateFields();
		if (isset($this->id))
			$fields['id_column'] = intval($this->id);
		$fields['id_wrap'] 	= intval($this->id_wrap);
		$fields['id_menu'] 	= intval($this->id_menu);
		$fields['active'] 	= intval($this->active);
		$fields['have_icon'] = intval($this->have_icon);
		$fields['image_type'] = pSQL($this->image_type);
		$fields['id_category'] = intval($this->id_category);
		$fields['id_cms'] = intval($this->id_cms);
		$fields['id_supplier'] = intval($this->id_supplier);
		$fields['id_manufacturer'] = intval($this->id_manufacturer);
		$fields['type'] = intval($this->type);
		$fields['target'] = pSQL($this->target);
		$fields['id_menu_depend'] 	= intval($this->id_menu_depend);
		$fields['privacy'] = intval($this->privacy);
		return $fields;
	}

	/**
	  * Check then return multilingual fields for database interaction
	  *
	  * @return array Multilingual fields
	  */
	public function getTranslationsFieldsChild()
	{
		parent::validateFieldsLang();
		$fieldsArray = array('name','link');
		$fields = array();
		$languages = Language::getLanguages(false);
		$defaultLanguage = Configuration::get('PS_LANG_DEFAULT');
		foreach ($languages as $language)
		{
			$fields[$language['id_lang']]['id_lang'] = $language['id_lang'];
			$fields[$language['id_lang']][$this->identifier] = intval($this->id);
			$fields[$language['id_lang']]['value_over'] = (isset($this->value_over[$language['id_lang']])) ? pSQL($this->value_over[$language['id_lang']], true) : '';
			$fields[$language['id_lang']]['value_under'] = (isset($this->value_under[$language['id_lang']])) ? pSQL($this->value_under[$language['id_lang']], true) : '';
			foreach ($fieldsArray as $field)
			{
				if (!Validate::isTableOrIdentifier($field))
					die(Tools::displayError());

				/* Check fields validity */
				if (isset($this->{$field}[$language['id_lang']]) AND !empty($this->{$field}[$language['id_lang']]))
					$fields[$language['id_lang']][$field] = pSQL($this->{$field}[$language['id_lang']]);
				elseif (in_array($field, $this->fieldsRequiredLang))
					$fields[$language['id_lang']][$field] = pSQL($this->{$field}[$defaultLanguage]);
				else
					$fields[$language['id_lang']][$field] = '';
			}
		}
		return $fields;
		return parent::getTranslationsFields(array('name','link'));
	}

	public	function add($autodate = true, $nullValues = false)
	{

		$ret = parent::add($autodate, $nullValues);
		return $ret;
	}

	public	function update($nullValues = false)
	{

		$ret = parent::update($nullValues);
		return $ret;
	}

	public function delete()
	{

		/* Get childs */
		$toDelete = array(intval($this->id));
		$toDelete = array_unique($toDelete);
		foreach ($toDelete as $id_column) {
			if(file_exists(_PS_ROOT_DIR_.'/modules/pm_advancedtopmenu/column_icons/'.$id_column. '.'.($this->image_type?$this->image_type:'jpg'))) {
				@unlink(_PS_ROOT_DIR_.'/modules/pm_advancedtopmenu/column_icons/'.$id_column. '.'.($this->image_type?$this->image_type:'jpg'));
			}
		}
		/* Delete its child from database */
		$list = sizeof($toDelete) > 1 ? implode(',', $toDelete) : intval($this->id);
		Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'pm_advancedtopmenu_columns` WHERE `id_column` IN ('.$list.')');
		Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'pm_advancedtopmenu_columns_lang` WHERE `id_column` IN ('.$list.')');
		$elements = AdvancedTopMenuElementsClass::getElementIds($list);
		foreach ($elements as $id_element) {
			$obj = new AdvancedTopMenuElementsClass($id_element);
			$obj->delete();
		}
		return true;
	}
	public static function getIdColumnCategoryDepend($id_menu,$id_category)
	{
		$row = Db::getInstance()->getRow('SELECT `id_column`
				FROM `'._DB_PREFIX_.'pm_advancedtopmenu_columns`
				WHERE `id_menu_depend` = '.intval($id_menu).' AND `id_category` = '.intval($id_category));
		return (isset($row['id_column'])?$row['id_column']:false);
	}
	public static function getIdColumnCategoryDependEmptyColumn($id_menu,$id_category)
	{
		$row = Db::getInstance()->getRow('SELECT atmc.`id_column`
				FROM `'._DB_PREFIX_.'pm_advancedtopmenu_columns` as atmc
				WHERE atmc.`id_menu_depend` = '.intval($id_menu).' AND atmc.`id_category` = '.intval($id_category).'');
		return (isset($row['id_column'])?$row['id_column']:false);
	}
	public static function getIdColumnManufacturerDependEmptyColumn($id_menu,$id_manufacturer)
	{
		$row = Db::getInstance()->getRow('SELECT atmc.`id_column`
				FROM `'._DB_PREFIX_.'pm_advancedtopmenu_columns` as atmc
				LEFT JOIN `'._DB_PREFIX_.'pm_advancedtopmenu_elements` atme ON (atmc.`id_column` = atme.`id_column_depend`)
				WHERE atmc.`id_menu_depend` = '.intval($id_menu).' AND atme.`id_manufacturer` = '.intval($id_manufacturer).'');
		return (isset($row['id_column'])?$row['id_column']:false);
	}
	public static function getIdColumnSupplierDependEmptyColumn($id_menu,$id_supplier)
	{
		$row = Db::getInstance()->getRow('SELECT atmc.`id_column`
				FROM `'._DB_PREFIX_.'pm_advancedtopmenu_columns` as atmc
				LEFT JOIN `'._DB_PREFIX_.'pm_advancedtopmenu_elements` atme ON (atmc.`id_column` = atme.`id_column_depend`)
				WHERE atmc.`id_menu_depend` = '.intval($id_menu).' AND atme.`id_supplier` = '.intval($id_supplier).'');
		return (isset($row['id_column'])?$row['id_column']:false);
	}
	public static function getIdMenuByIdColumn($id_column)
	{
		$row = Db::getInstance()->getRow('SELECT `id_menu`
				FROM `'._DB_PREFIX_.'pm_advancedtopmenu_columns`
				WHERE `id_column` = '.intval($id_column).'');
		return (isset($row['id_menu'])?$row['id_menu']:false);
	}
	public static function columnHaveDepend($id_column)
	{
		$sql = 'SELECT `id_column`
				FROM `'._DB_PREFIX_.'pm_advancedtopmenu_elements`
				WHERE `id_column_depend` = '.intval($id_column);
		return Db::getInstance()->ExecuteS($sql);
	}
	public static function getMenuColums($id_wrap,$id_lang,$active = true, $groupRestrict = false)
	{
		$sql_groups_join = '';
		$sql_groups_where = '';
		if ($groupRestrict && ((version_compare(_PS_VERSION_, '1.5.0.0', '>=') && Group::isFeatureActive()) || (version_compare(_PS_VERSION_, '1.5.0.0', '<') && version_compare(_PS_VERSION_, '1.2.5.0', '>=')))) {
			$groups = PM_AdvancedTopMenu::getCustomerGroups();
			if (sizeof($groups)) {
				$sql_groups_join = 'LEFT JOIN `'._DB_PREFIX_.'category_group` cg ON (cg.`id_category` = ca.`id_category`)';
				$sql_groups_where = 'AND IF (atmc.`id_category` IS NULL OR atmc.`id_category` = 0, 1, cg.`id_group` IN ('.implode(',', $groups).'))';
			}
		}
		
		$sql = 'SELECT atmc.*, atmcl.*,
				cl.link_rewrite, cl.meta_title,
				cal.link_rewrite as category_link_rewrite, cal.name as category_name,
				m.name as manufacturer_name,
				s.name as supplier_name
				FROM `'._DB_PREFIX_.'pm_advancedtopmenu_columns` atmc
				LEFT JOIN `'._DB_PREFIX_.'pm_advancedtopmenu_columns_lang` atmcl ON (atmc.`id_column` = atmcl.`id_column` AND atmcl.`id_lang` = '.intval($id_lang).')
				LEFT JOIN '._DB_PREFIX_.'cms c ON (c.id_cms = atmc.`id_cms`)
				'.(version_compare(_PS_VERSION_, '1.5.0.0', '>=') ? Shop::addSqlAssociation('cms', 'c',false):'').'
				LEFT JOIN '._DB_PREFIX_.'cms_lang cl ON (c.id_cms = cl.id_cms AND cl.id_lang = '.intval($id_lang).')
				LEFT JOIN '._DB_PREFIX_.'category ca ON (ca.id_category = atmc.`id_category`)
				' . $sql_groups_join . '
				LEFT JOIN '._DB_PREFIX_.'category_lang cal ON (ca.id_category = cal.id_category AND cal.id_lang = '.intval($id_lang).(version_compare(_PS_VERSION_, '1.5.0.0', '>=') ? Shop::addSqlRestrictionOnLang('cal'):'').')
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (atmc.`id_manufacturer` = m.`id_manufacturer`)
				'.(version_compare(_PS_VERSION_, '1.5.0.0', '>=') ? Shop::addSqlAssociation('manufacturer', 'm',false):'').'
				LEFT JOIN `'._DB_PREFIX_.'supplier` s ON (atmc.`id_supplier` = s.`id_supplier`)
				'.(version_compare(_PS_VERSION_, '1.5.0.0', '>=') ? Shop::addSqlAssociation('supplier', 's',false):'').'
				WHERE '.($active ? ' atmc.`active` = 1 AND ':'').' atmc.`id_wrap` = '.intval($id_wrap).'
				'.($active ? 'AND ((atmc.`id_manufacturer` = 0 AND atmc.`id_supplier` = 0 AND atmc.`id_category` = 0 AND atmc.`id_cms` = 0)
				OR c.id_cms IS NOT NULL OR m.id_manufacturer IS NOT NULL OR ca.id_category IS NOT NULL OR s.`id_supplier` IS NOT NULL)':'') 
				. $sql_groups_where . '
				GROUP BY atmc.`id_column`
				ORDER BY atmc.`position`';
		return Db::getInstance()->ExecuteS($sql);
	}
	public static function getMenuColumsByIdMenu($id_menu, $id_lang, $active = true, $groupRestrict = false)
	{
		$sql_groups_join = '';
		$sql_groups_where = '';
		if ($groupRestrict && ((version_compare(_PS_VERSION_, '1.5.0.0', '>=') && Group::isFeatureActive()) || (version_compare(_PS_VERSION_, '1.5.0.0', '<') && version_compare(_PS_VERSION_, '1.2.5.0', '>=')))) {
			$groups = PM_AdvancedTopMenu::getCustomerGroups();
			if (sizeof($groups)) {
				$sql_groups_join = 'LEFT JOIN `'._DB_PREFIX_.'category_group` cg ON (cg.`id_category` = ca.`id_category`)';
				$sql_groups_where = 'AND IF (atmc.`id_category` IS NULL OR atmc.`id_category` = 0, 1, cg.`id_group` IN ('.implode(',', $groups).'))';
			}
		}
		
		$sql = 'SELECT atmc.*, atmcl.*,
				cl.link_rewrite, cl.meta_title,
				cal.link_rewrite as category_link_rewrite, cal.name as category_name,
				m.name as manufacturer_name,
				s.name as supplier_name
				FROM `'._DB_PREFIX_.'pm_advancedtopmenu_columns` atmc
				LEFT JOIN `'._DB_PREFIX_.'pm_advancedtopmenu_columns_lang` atmcl ON (atmc.`id_column` = atmcl.`id_column` AND atmcl.`id_lang` = '.intval($id_lang).')
				LEFT JOIN '._DB_PREFIX_.'cms c ON (c.id_cms = atmc.`id_cms`)
				'.(version_compare(_PS_VERSION_, '1.5.0.0', '>=') ? Shop::addSqlAssociation('cms', 'c',false):'').'
				LEFT JOIN '._DB_PREFIX_.'cms_lang cl ON (c.id_cms = cl.id_cms AND cl.id_lang = '.intval($id_lang).')
				LEFT JOIN '._DB_PREFIX_.'category ca ON (ca.id_category = atmc.`id_category`)
				' . $sql_groups_join . '
				LEFT JOIN '._DB_PREFIX_.'category_lang cal ON (ca.id_category = cal.id_category AND cal.id_lang = '.intval($id_lang).(version_compare(_PS_VERSION_, '1.5.0.0', '>=') ? Shop::addSqlRestrictionOnLang('cal'):'').')
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (atmc.`id_manufacturer` = m.`id_manufacturer`)
				'.(version_compare(_PS_VERSION_, '1.5.0.0', '>=') ? Shop::addSqlAssociation('manufacturer', 'm',false):'').'
				LEFT JOIN `'._DB_PREFIX_.'supplier` s ON (atmc.`id_supplier` = s.`id_supplier`)
				'.(version_compare(_PS_VERSION_, '1.5.0.0', '>=') ? Shop::addSqlAssociation('supplier', 's',false):'').'
				WHERE '.($active ? ' atmc.`active` = 1 AND':'').' atmc.`id_menu` = '.intval($id_menu).'
				'.($active ? 'AND ((atmc.`id_manufacturer` = 0 AND atmc.`id_supplier` = 0 AND atmc.`id_category` = 0 AND atmc.`id_cms` = 0)
				OR c.id_cms IS NOT NULL OR m.id_manufacturer IS NOT NULL OR ca.id_category IS NOT NULL OR s.`id_supplier` IS NOT NULL)':'')
				. $sql_groups_where . '
				GROUP BY atmc.`id_column`
				ORDER BY atmc.`position`';
		return Db::getInstance()->ExecuteS($sql);
	}

	public static function getMenusColums($menus, $id_lang, $groupRestrict = false)
	{
		$columns = array();
		foreach($menus as $columnsWrap) {
			foreach($columnsWrap as $columnWrap) {
				$columns[$columnWrap['id_wrap']] = self::getMenuColums($columnWrap['id_wrap'], $id_lang, true, $groupRestrict);
			}
		}
		return $columns;
	}
	public static function getColumnIds($ids_wrap)
	{
		$result =  Db::getInstance()->ExecuteS('
		SELECT `id_column`
		FROM '._DB_PREFIX_.'pm_advancedtopmenu_columns
		WHERE `id_wrap` IN('.pSQL($ids_wrap).')');
		$columns = array();
		foreach ($result as $row) {
			$columns[] = $row['id_column'];
		}
		return $columns;
	}
}

?>