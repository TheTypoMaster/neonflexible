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
			'id_now_product_type'	=> array('title' => $this->l('ID'), 'align' => 'center', 'class' => 'fixed-width-xs'),
			'name'					=> array('title' => $this->l('Name'), 'width' => 'auto'),
			'button_name'			=> array('title' => $this->l('Button name'), 'width' => 'auto'),
			'active'				=> array('title' => $this->l('Enabled'), 'width' => 25, 'align' => 'center', 'active' => 'status', 'type' => 'bool', 'orderby' => false, 'class' => 'fixed-width-sm')
		);

		parent::__construct();
	}

	public function initPageHeaderToolbar()
	{
		if (empty($this->display))
			$this->page_header_toolbar_btn['new_product_type'] = array(
				'href' => self::$currentIndex.'&addtax&token='.$this->token,
				'desc' => $this->l('Add new product type', null, null, false),
				'icon' => 'process-icon-new'
			);

		parent::initPageHeaderToolbar();
	}

	public function renderForm()
	{
		$this->fields_form = array(
			'legend' => array(
				'title' => $this->l('Product type'),
				'icon' => 'icon-money'
			),
			'input' => array(
				array(
					'type' => 'text',
					'label' => $this->l('Name'),
					'name' => 'name',
					'required' => true,
					'lang' => true
				),
				array(
					'type' => 'text',
					'label' => $this->l('Button name'),
					'name' => 'button_name',
					'required' => true,
					'lang' => true
				),
				array(
					'type' => 'select',
					'label' => $this->l('Type de page'),
					'name' => 'type',
					'required' => true,
					'default_value' => 'CONTENT',
					'options' => array(
						'query' => array(
							array('id' => 'CONTENT', 'name' => $this->l('Contenu')),
							array('id' => 'BUTTON', 'name' => $this->l('Bouton')),
						),
						'id' => 'id',
						'name' => 'name'
					)
				),
				array(
					'type' => 'switch',
					'label' => $this->l('Enable'),
					'name' => 'active',
					'required' => false,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						),
						array(
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						)
					)
				)
			),
			'submit' => array(
				'title' => $this->l('Save')
			)
		);

		return parent::renderForm();
	}
}