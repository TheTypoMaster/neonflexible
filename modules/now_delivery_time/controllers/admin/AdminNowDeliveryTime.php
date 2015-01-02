<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

require_once(_PS_MODULE_DIR_ . 'now_delivery_time/now_delivery_time.php');
require_once(_PS_MODULE_DIR_ . 'now_delivery_time/classes/NowDeliveryTime.php');

class AdminNowDeliveryTimeController extends ModuleAdminControllerCore {

	/**
	 * @throws PrestaShopException
	 */
	public function __construct() {
		$this->bootstrap	= true;
		$this->table		= 'now_delivery_time';
		$this->className	= 'NowDeliveryTime';
		$this->module		= new now_delivery_time();
		$this->lang			= true;

		$this->addRowAction('edit');
		$this->addRowAction('delete');

		$this->fields_list = array(
			'id_now_delivery_time'	=> array('title' => $this->module->l('ID', 'AdminNowDeliveryTime'), 'align' => 'center', 'class' => 'fixed-width-xs'),
			'carrier'				=> array('title' => $this->module->l('Carrier', 'AdminNowDeliveryTime'), 'width' => 'auto'),
			'day_min'				=> array('title' => $this->module->l('Minimum day', 'AdminNowDeliveryTime'), 'width' => 'auto'),
			'day_max'				=> array('title' => $this->module->l('Maximum day', 'AdminNowDeliveryTime'), 'width' => 'auto'),
			'saturday_shipping'		=> array('title' => $this->module->l('Saturday shipping', 'AdminNowDeliveryTime'), 'width' => 25, 'align' => 'center', 'active' => 'status', 'type' => 'bool', 'orderby' => false, 'class' => 'fixed-width-sm'),
			'sunday_shipping'		=> array('title' => $this->module->l('Sunday shipping', 'AdminNowDeliveryTime'), 'width' => 25, 'align' => 'center', 'active' => 'status', 'type' => 'bool', 'orderby' => false, 'class' => 'fixed-width-sm'),
			'shipping_holidays'		=> array('title' => $this->module->l('Shipping holidays', 'AdminNowDeliveryTime'), 'width' => 25, 'align' => 'center', 'active' => 'status', 'type' => 'bool', 'orderby' => false, 'class' => 'fixed-width-sm'),
			'saturday_delivery'		=> array('title' => $this->module->l('Saturday delivery', 'AdminNowDeliveryTime'), 'width' => 25, 'align' => 'center', 'active' => 'status', 'type' => 'bool', 'orderby' => false, 'class' => 'fixed-width-sm'),
			'sunday_delivery'		=> array('title' => $this->module->l('Sunday delivery', 'AdminNowDeliveryTime'), 'width' => 25, 'align' => 'center', 'active' => 'status', 'type' => 'bool', 'orderby' => false, 'class' => 'fixed-width-sm'),
			'delivery_holidays'		=> array('title' => $this->module->l('Delivery holidays', 'AdminNowDeliveryTime'), 'width' => 25, 'align' => 'center', 'active' => 'status', 'type' => 'bool', 'orderby' => false, 'class' => 'fixed-width-sm'),
			'date_upd'				=> array('title' => $this->module->l('Updated Date', 'AdminNowDeliveryTime'), 'width' => 'auto', 'type' => 'datetime'),
		);

		$this->_select	.= ' c.`name` as carrier ';
		$this->_join	.= ' LEFT JOIN `' . _DB_PREFIX_ . 'carrier` c ON (c.`id_carrier` = a.`id_carrier`)';

		parent::__construct();
	}

	/**
	 * @return mixed
	 */
	public function renderform() {
		$this->fields_form = array(
			'legend' => array(
				'title'	=> $this->module->l('Add or Update a delivery time rule', 'AdminNowDeliveryTime'),
				'icon'	=> 'icon-settings'
			),
			'input' => array(
				array(
					'type'		=> 'select',
					'label'		=> $this->module->l('Carrier', 'AdminNowDeliveryTime'),
					'name'		=> 'id_carrier',
					'required'	=> true,
					'options'	=> array(
						'query'		=> Carrier::getCarriers(Context::getContext()->language->id, true),
						'id'		=> 'id_carrier',
						'name'		=> 'name'
					)
				),
				array(
					'type'		=> 'switch',
					'label'		=> $this->module->l('Saturday shipping', 'AdminNowDeliveryTime'),
					'name'		=> 'saturday_shipping',
					'required'	=> false,
					'is_bool'	=> true,
					'values'	=> array(
						array(
							'id'	=> 'active_on',
							'value'	=> 1,
							'label'	=> $this->module->l('Enabled', 'AdminNowDeliveryTime')
						),
						array(
							'id'	=> 'active_off',
							'value'	=> 0,
							'label'	=> $this->module->l('Disabled', 'AdminNowDeliveryTime')
						)
					)
				),
				array(
					'type'		=> 'switch',
					'label'		=> $this->module->l('Sunday shipping', 'AdminNowDeliveryTime'),
					'name'		=> 'sunday_shipping',
					'required'	=> false,
					'is_bool'	=> true,
					'values'	=> array(
						array(
							'id'	=> 'active_on',
							'value'	=> 1,
							'label'	=> $this->module->l('Enabled', 'AdminNowDeliveryTime')
						),
						array(
							'id'	=> 'active_off',
							'value'	=> 0,
							'label'	=> $this->module->l('Disabled', 'AdminNowDeliveryTime')
						)
					)
				),
				array(
					'type'		=> 'switch',
					'label'		=> $this->module->l('Shipping holidays', 'AdminNowDeliveryTime'),
					'name'		=> 'shipping_holidays',
					'required'	=> false,
					'is_bool'	=> true,
					'values'	=> array(
						array(
							'id'	=> 'active_on',
							'value'	=> 1,
							'label'	=> $this->module->l('Enabled', 'AdminNowDeliveryTime')
						),
						array(
							'id'	=> 'active_off',
							'value'	=> 0,
							'label'	=> $this->module->l('Disabled', 'AdminNowDeliveryTime')
						)
					)
				),
				array(
					'type'		=> 'switch',
					'label'		=> $this->module->l('Saturday delivery', 'AdminNowDeliveryTime'),
					'name'		=> 'saturday_delivery',
					'required'	=> false,
					'is_bool'	=> true,
					'values'	=> array(
						array(
							'id'	=> 'active_on',
							'value'	=> 1,
							'label'	=> $this->module->l('Enabled', 'AdminNowDeliveryTime')
						),
						array(
							'id'	=> 'active_off',
							'value'	=> 0,
							'label'	=> $this->module->l('Disabled', 'AdminNowDeliveryTime')
						)
					)
				),
				array(
					'type'		=> 'switch',
					'label'		=> $this->module->l('Sunday delivery', 'AdminNowDeliveryTime'),
					'name'		=> 'sunday_delivery',
					'required'	=> false,
					'is_bool'	=> true,
					'values'	=> array(
						array(
							'id'	=> 'active_on',
							'value'	=> 1,
							'label'	=> $this->module->l('Enabled', 'AdminNowDeliveryTime')
						),
						array(
							'id'	=> 'active_off',
							'value'	=> 0,
							'label'	=> $this->module->l('Disabled', 'AdminNowDeliveryTime')
						)
					)
				),
				array(
					'type'		=> 'switch',
					'label'		=> $this->module->l('Delivery holidays', 'AdminNowDeliveryTime'),
					'name'		=> 'delivery_holidays',
					'required'	=> false,
					'is_bool'	=> true,
					'values'	=> array(
						array(
							'id'	=> 'active_on',
							'value'	=> 1,
							'label'	=> $this->module->l('Enabled', 'AdminNowDeliveryTime')
						),
						array(
							'id'	=> 'active_off',
							'value'	=> 0,
							'label'	=> $this->module->l('Disabled', 'AdminNowDeliveryTime')
						)
					)
				),
				array(
					'type'		=> 'text',
					'label'		=> $this->module->l('Minimum day', 'AdminNowDeliveryTime'),
					'name'		=> 'day_min',
					'required'	=> true,
					'suffix'	=> $this->module->l('days', 'AdminNowDeliveryTime'),
				),
				array(
					'type'		=> 'text',
					'label'		=> $this->module->l('Maximum day', 'AdminNowDeliveryTime'),
					'name'		=> 'day_max',
					'required'	=> true,
					'suffix'	=> $this->module->l('days', 'AdminNowDeliveryTime'),
				),
				array(
					'type'			=> 'textarea',
					'label'			=> $this->module->l('Description', 'AdminNowDeliveryTime'),
					'name'			=> 'description',
					'lang'			=> true
				),
				array(
					'type'			=> 'textarea',
					'label'			=> $this->module->l('Timeslot', 'AdminNowDeliveryTime'),
					'name'			=> 'timeslot',
					'lang'			=> true
				),
			),
			'submit' => array(
				'title' => $this->module->l('Save', 'AdminNowDeliveryTime')
			)
		);

		return parent::renderForm();
	}
}