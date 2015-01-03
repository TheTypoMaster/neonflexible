<?php
/*
 * 2015
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

require_once(_PS_MODULE_DIR_ . 'now_delivery_time/now_delivery_time.php');
require_once(_PS_MODULE_DIR_ . 'now_delivery_time/classes/NowHolidays.php');

class AdminNowHolidaysController extends ModuleAdminControllerCore {

	/**
	 * @throws PrestaShopException
	 */
	public function __construct() {
		$this->bootstrap	= true;
		$this->table		= 'now_holidays';
		$this->className	= 'NowHolidays';
		$this->module		= new now_delivery_time();

		$this->addRowAction('edit');
		$this->addRowAction('delete');

		$this->fields_list = array(
			'id_now_holidays'	=> array('title' => $this->module->l('ID', 'AdminNowHolidays'), 'align' => 'center', 'class' => 'fixed-width-xs'),
			'type_name'			=> array('title' => $this->module->l('Type', 'AdminNowHolidays'), 'width' => 'auto'),
			'evenment_name'		=> array('title' => $this->module->l('Evenment name', 'AdminNowHolidays'), 'width' => 'auto'),
			'date_start'		=> array('title' => $this->module->l('Start date', 'AdminNowHolidays'), 'width' => 'auto', 'type' => 'date'),
			'date_end'			=> array('title' => $this->module->l('End date', 'AdminNowHolidays'), 'width' => 'auto', 'type' => 'date'),
			'preparation'		=> array('title' => $this->module->l('Preparation', 'AdminNowHolidays'), 'width' => 25, 'align' => 'center', 'active' => 'status', 'type' => 'bool', 'orderby' => false, 'class' => 'fixed-width-sm'),
			'shipping'			=> array('title' => $this->module->l('Shipping', 'AdminNowHolidays'), 'width' => 25, 'align' => 'center', 'active' => 'status', 'type' => 'bool', 'orderby' => false, 'class' => 'fixed-width-sm'),
			'delivery'			=> array('title' => $this->module->l('Delivery', 'AdminNowHolidays'), 'width' => 25, 'align' => 'center', 'active' => 'status', 'type' => 'bool', 'orderby' => false, 'class' => 'fixed-width-sm'),
			'date_upd'			=> array('title' => $this->module->l('Updated Date', 'AdminNowHolidays'), 'width' => 'auto', 'type' => 'datetime'),
		);

		$this->_select	.= ' IF(a.`type` = "public_holidays", "' . $this->module->l('Public holidays', 'AdminNowHolidays') . '", "' . $this->module->l('Holidays', 'AdminNowHolidays') . '") as type_name ';

		parent::__construct();
	}

	/**
	 * @return mixed
	 */
	public function renderform() {
		$this->fields_form = array(
			'legend' => array(
				'title'	=> $this->module->l('Add or Update holidays date or public holidays date', 'AdminNowHolidays'),
				'icon'	=> 'icon-settings'
			),
			'input' => array(
				array(
					'type'			=> 'text',
					'label'			=> $this->module->l('Evenment name', 'AdminNowHolidays'),
					'required'		=> true,
					'name'			=> 'evenment_name'
				),
				array(
					'type'		=> 'select',
					'label'		=> $this->module->l('Type', 'AdminNowHolidays'),
					'name'		=> 'type',
					'required'	=> true,
					'options'	=> array(
						'query'		=> array(
							array(
								'id' => NowHolidays::PUBLIC_HOLIDAYS,
								'name' => $this->module->l('Public holidays', 'AdminNowHolidays'),
							),
							array(
								'id' => NowHolidays::HOLIDAYS,
								'name' => $this->module->l('Holidays', 'AdminNowHolidays'),
							),
						),
						'id'		=> 'id',
						'name'		=> 'name'
					)
				),
				array(
					'type'			=> 'date',
					'label'			=> $this->module->l('Start date', 'AdminNowHolidays'),
					'required'		=> true,
					'name'			=> 'date_start'
				),
				array(
					'type'			=> 'date',
					'label'			=> $this->module->l('End date', 'AdminNowHolidays'),
					'required'		=> true,
					'name'			=> 'date_end'
				),
				array(
					'type'		=> 'switch',
					'label'		=> $this->module->l('Preparation', 'AdminNowHolidays'),
					'hint'		=> $this->module->l('Indicate whether the preparation of orders not will be carried out over this period', 'AdminNowHolidays'),
					'name'		=> 'preparation',
					'required'	=> true,
					'is_bool'	=> true,
					'values'	=> array(
						array(
							'id'	=> 'active_on',
							'value'	=> 1,
							'label'	=> $this->module->l('Enabled', 'AdminNowHolidays')
						),
						array(
							'id'	=> 'active_off',
							'value'	=> 0,
							'label'	=> $this->module->l('Disabled', 'AdminNowHolidays')
						)
					)
				),
				array(
					'type'		=> 'switch',
					'label'		=> $this->module->l('Shipping', 'AdminNowHolidays'),
					'hint'		=> $this->module->l('Indicate whether the shipment of orders not will be carried out over this period', 'AdminNowHolidays'),
					'name'		=> 'shipping',
					'required'	=> true,
					'is_bool'	=> true,
					'values'	=> array(
						array(
							'id'	=> 'active_on',
							'value'	=> 1,
							'label'	=> $this->module->l('Enabled', 'AdminNowHolidays')
						),
						array(
							'id'	=> 'active_off',
							'value'	=> 0,
							'label'	=> $this->module->l('Disabled', 'AdminNowHolidays')
						)
					)
				),
				array(
					'type'		=> 'switch',
					'label'		=> $this->module->l('Delivery', 'AdminNowHolidays'),
					'hint'		=> $this->module->l('Indicate whether the delivery of orders not will be carried out over this period', 'AdminNowHolidays'),
					'name'		=> 'delivery',
					'required'	=> true,
					'is_bool'	=> true,
					'values'	=> array(
						array(
							'id'	=> 'active_on',
							'value'	=> 1,
							'label'	=> $this->module->l('Enabled', 'AdminNowHolidays')
						),
						array(
							'id'	=> 'active_off',
							'value'	=> 0,
							'label'	=> $this->module->l('Disabled', 'AdminNowHolidays')
						)
					)
				),
			),
			'submit' => array(
				'title' => $this->module->l('Save', 'AdminNowHolidays')
			)
		);

		return parent::renderForm();
	}
}