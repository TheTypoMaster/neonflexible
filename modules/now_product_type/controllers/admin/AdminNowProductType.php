<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

require_once(_PS_MODULE_DIR_ . 'now_product_type/now_product_type.php');
require_once(_PS_MODULE_DIR_ . 'now_product_type/classes/NowProductType.php');
require_once(_PS_MODULE_DIR_ . 'now_product_type/classes/NowProductTypeProduct.php');

class AdminNowProductTypeController extends ModuleAdminControllerCore {

	public function __construct()
	{
		$this->bootstrap = true;
		$this->table = 'now_product_type';
		$this->className = 'NowProductType';
		$this->module = new now_product_type();
		$this->lang = true;
		$this->addRowAction('edit');
		$this->addRowAction('delete');

		$this->fields_list = array(
			'id_now_product_type'	=> array('title' => $this->module->l('ID', 'AdminNowProductType'), 'align' => 'center', 'class' => 'fixed-width-xs'),
			'name'					=> array('title' => $this->module->l('Name', 'AdminNowProductType'), 'width' => 'auto'),
			'button_name'			=> array('title' => $this->module->l('Button name', 'AdminNowProductType'), 'width' => 'auto'),
			'active'				=> array('title' => $this->module->l('Enabled', 'AdminNowProductType'), 'width' => 25, 'align' => 'center', 'active' => 'status', 'type' => 'bool', 'orderby' => false, 'class' => 'fixed-width-sm')
		);

		parent::__construct();
	}

	public function initPageHeaderToolbar()
	{
		if (empty($this->display))
			$this->page_header_toolbar_btn['new_product_type'] = array(
				'href' => self::$currentIndex.'&addtax&token='.$this->token,
				'desc' => $this->module->l('Add new product type', 'AdminNowProductType', null, false),
				'icon' => 'process-icon-new'
			);

		parent::initPageHeaderToolbar();
	}

	public function renderForm()
	{
		$this->fields_form = array(
			'legend' => array(
				'title' => $this->module->l('Product type', 'AdminNowProductType'),
				'icon' => 'icon-money'
			),
			'input' => array(
				array(
					'type' => 'text',
					'label' => $this->module->l('Name', 'AdminNowProductType'),
					'name' => 'name',
					'required' => true,
					'lang' => true
				),
				array(
					'type' => 'text',
					'label' => $this->module->l('Button name', 'AdminNowProductType'),
					'name' => 'button_name',
					'required' => true,
					'lang' => true
				),
				array(
					'type' => 'select',
					'label' => $this->module->l('Type de page', 'AdminNowProductType'),
					'name' => 'type',
					'required' => true,
					'default_value' => 'CONTENT',
					'options' => array(
						'query' => array(
							array('id' => 'CONTENT', 'name' => $this->module->l('Contenu', 'AdminNowProductType')),
							array('id' => 'BUTTON', 'name' => $this->module->l('Bouton', 'AdminNowProductType')),
						),
						'id' => 'id',
						'name' => 'name'
					)
				),
				array(
					'type' => 'switch',
					'label' => $this->module->l('Enable', 'AdminNowProductType'),
					'name' => 'active',
					'required' => false,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->module->l('Enabled', 'AdminNowProductType')
						),
						array(
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->module->l('Disabled', 'AdminNowProductType')
						)
					)
				)
			),
			'submit' => array(
				'title' => $this->module->l('Save', 'AdminNowProductType')
			)
		);

		return parent::renderForm();
	}
}