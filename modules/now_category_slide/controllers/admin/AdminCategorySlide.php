<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

require_once (_PS_MODULE_DIR_ . 'now_category_slide/now_category_slide.php');
require_once (_PS_MODULE_DIR_ . 'now_category_slide/classes/NowCategorySlide.php');

class AdminCategorySlideController extends ModuleAdminController {

	public function __construct()
	{
		$this->bootstrap = true;
		$this->table = 'now_category_slide';
		$this->className = 'NowCategorySlide';
		$this->module = new now_category_slide();

		$this->_defaultOrderBy		= 'position';
		$this->orderBy				= 'position';
		$this->position_identifier	= 'position';

		$this->addRowAction('edit');
		$this->addRowAction('delete');

		$this->bulk_actions = array(
			'delete' => array(
				'text' => $this->module->l('Delete selected', 'AdminCategorySlide'),
				'confirm' => $this->module->l('Delete selected items?', 'AdminCategorySlide'),
				'icon' => 'icon-trash'
			)
		);

		$this->context = Context::getContext();

		$this->fields_list = array(
			'id_now_category_slide'		=> array('title' => $this->module->l('ID', 'AdminCategorySlide'), 'align' => 'center', 'class' => 'fixed-width-xs'),
			'id_category'				=> array('title' => $this->module->l('Category ID', 'AdminCategorySlide'), 'width' => 'auto'),
			'category_name'				=> array('title' => $this->module->l('Category name', 'AdminCategorySlide'), 'width' => 'auto'),
			'active'					=> array('title' => $this->module->l('Enabled', 'AdminCategorySlide'), 'width' => 25, 'align' => 'center', 'active' => 'status', 'type' => 'bool', 'orderby' => false, 'class' => 'fixed-width-sm'),
			'position'					=> array('title' => $this->module->l('Position', 'AdminCategorySlide'), 'filter_key' => 'a!position', 'position' => 'position', 'align' => 'center'),
			'date_upd'					=> array('title' => $this->module->l('Updated Date', 'AdminCategorySlide'), 'width' => 'auto', 'type' => 'datetime'),
		);

		$this->_select .= ' cl.`name` as category_name ';
		$this->_join .= ' LEFT JOIN `' . _DB_PREFIX_ . 'category` c ON (c.`id_category` = a.`id_category`)';
		$this->_join .= ' LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON (cl.`id_category` = c.`id_category` AND cl.`id_lang` = ' . (int)Context::getContext()->language->id . ')';

		parent::__construct();
	}

	public function renderForm()
	{
		$obj = $this->loadObject(true);
		$this->fields_form = array(
			'legend' => array(
				'title' => $this->module->l('Job Category', 'AdminCategorySlide'),
				'icon' => 'icon-money'
			),
			'input' => array(
				array(
					'type'  => 'categories',
					'label' => $this->l('Category', 'AdminCategorySlide'),
					'name'  => 'id_category',
					'tree'  => array(
						'id'					=> 'categories-tree',
						'selected_categories'	=> array($obj->id_category),
					),
					'form_group_class' => 'categoryDiv'
				),
				array(
					'type' => 'switch',
					'label' => $this->module->l('Enable', 'AdminCategorySlide'),
					'name' => 'active',
					'required' => false,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->module->l('Enabled', 'AdminCategorySlide')
						),
						array(
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->module->l('Disabled', 'AdminCategorySlide')
						)
					)
				)
			),
			'submit' => array(
				'title' => $this->module->l('Save', 'AdminCategorySlide')
			)
		);

		return parent::renderForm();
	}

	/**
	 * Manage position in ajax
	 */
	public function ajaxProcessUpdatePositions()
	{
		$way						= (int)(Tools::getValue('way'));
		$id_now_category_slide	= (int)(Tools::getValue('id'));
		$positions					= Tools::getValue($this->table);

		foreach ($positions as $position => $value)
		{
			$pos = explode('_', $value);

			if (isset($pos[2]) && (int)$pos[2] === $id_now_category_slide) {
				if ($oNowCategorySlide = new NowCategorySlide((int)$pos[2])) {
					if (isset($position) && $oNowCategorySlide->updatePosition($way, $position)) {
						echo 'ok position '.(int)$position.' for bloc category slide '.(int)$pos[1].'\r\n';
					} else {
						echo '{"hasError" : true, "errors" : "Can not update bloc category slide ' . (int)$id_now_category_slide . ' to position ' . (int)$position . ' "}';
					}
				} else {
					echo '{"hasError" : true, "errors" : "This bloc category slide (' . (int)$id_now_category_slide . ') can t be loaded"}';
				}

				break;
			}
		}
	}
}