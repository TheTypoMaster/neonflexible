<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

require_once (_PS_MODULE_DIR_ . 'now_mea_home/now_mea_home.php');
require_once (_PS_MODULE_DIR_ . 'now_mea_home/classes/NowMeaHome.php');

class AdminMeaProductsHomePageController extends ModuleAdminController {

	public function __construct()
	{
		$this->bootstrap = true;
		$this->table = 'now_mea_home';
		$this->className = 'NowMeaHome';
		$this->module = new now_mea_home();

		$this->addRowAction('edit');
		$this->addRowAction('delete');

		$this->bulk_actions = array(
			'delete' => array(
				'text' => $this->module->l('Delete selected', 'AdminBlockReinsurance'),
				'confirm' => $this->module->l('Delete selected items?', 'AdminBlockReinsurance'),
				'icon' => 'icon-trash'
			)
		);

		$this->fields_list = array(
			'id_now_mea_home'	=> array('title' => $this->module->l('ID', 'AdminMeaProductsHomePage'), 'align' => 'center', 'class' => 'fixed-width-xs'),
			'id_product'		=> array('title' => $this->module->l('Product ID', 'AdminMeaProductsHomePage'), 'width' => 'auto'),
			'product_name'		=> array('title' => $this->module->l('Product name', 'AdminMeaProductsHomePage'), 'width' => 'auto'),
			'active'			=> array('title' => $this->module->l('Enabled', 'AdminMeaProductsHomePage'), 'width' => 25, 'align' => 'center', 'active' => 'status', 'type' => 'bool', 'orderby' => false, 'class' => 'fixed-width-sm'),
			'date_upd'			=> array('title' => $this->module->l('Updated Date', 'AdminMeaProductsHomePage'), 'width' => 'auto', 'type' => 'datetime'),
		);

		$this->_select .= ' pl.`name` as product_name ';
		$this->_join .= ' INNER JOIN `' . _DB_PREFIX_ . 'product` p ON (p.`id_product` = a.`id_product`)';
		$this->_join .= ' INNER JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (pl.`id_product` = p.`id_product` AND pl.`id_lang` = ' . (int)Context::getContext()->language->id . ')';

		parent::__construct();
	}

	public function renderForm()
	{
		$this->fields_form = array(
			'legend' => array(
				'title' => $this->module->l('Product', 'AdminMeaProductsHomePage'),
				'icon' => 'icon-money'
			),
			'input' => array(
				array(
					'type' => 'text',
					'label' => $this->module->l('Product ID', 'AdminMeaProductsHomePage'),
					'name' => 'id_product',
					'required' => true
				),
				array(
					'type' => 'switch',
					'label' => $this->module->l('Enable', 'AdminMeaProductsHomePage'),
					'name' => 'active',
					'required' => false,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->module->l('Enabled', 'AdminMeaProductsHomePage')
						),
						array(
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->module->l('Disabled', 'AdminMeaProductsHomePage')
						)
					)
				)
			),
			'submit' => array(
				'title' => $this->module->l('Save', 'AdminMeaProductsHomePage')
			)
		);

		return parent::renderForm();
	}
}