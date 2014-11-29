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

	public function __construct()
	{
		$this->bootstrap = true;
		$this->table = 'now_delivery_time';
		$this->className = 'NowDeliveryTime';
		$this->module = new now_delivery_time();
		$this->lang = true;
		$this->addRowAction('edit');
		$this->addRowAction('delete');

		$this->fields_list = array(
			'id_now_delivery_time'	=> array('title' => $this->module->l('ID', 'AdminNowDeliveryTime'), 'align' => 'center', 'class' => 'fixed-width-xs'),
			'carrier'				=> array('title' => $this->module->l('Carrier', 'AdminNowDeliveryTime'), 'width' => 'auto'),
			'day_min'				=> array('title' => $this->module->l('Minimum day', 'AdminNowDeliveryTime'), 'width' => 'auto'),
			'day_max'				=> array('title' => $this->module->l('Maximum day', 'AdminNowDeliveryTime'), 'width' => 'auto'),
			'saturday_shipping'		=> array('title' => $this->module->l('Saturday shipping', 'AdminNowDeliveryTime'), 'width' => 'auto'),
			'sunday_shipping'		=> array('title' => $this->module->l('Sunday shipping', 'AdminNowDeliveryTime'), 'width' => 'auto'),
			'shipping_holidays'		=> array('title' => $this->module->l('Shipping holidays', 'AdminNowDeliveryTime'), 'width' => 'auto'),
			'saturday_delivery'		=> array('title' => $this->module->l('Saturday delivery', 'AdminNowDeliveryTime'), 'width' => 'auto'),
			'sunday_delivery'		=> array('title' => $this->module->l('Sunday delivery', 'AdminNowDeliveryTime'), 'width' => 'auto'),
			'delivery_holidays'		=> array('title' => $this->module->l('Delivery holidays', 'AdminNowDeliveryTime'), 'width' => 'auto'),
			'date_upd'				=> array('title' => $this->module->l('Updated Date', 'AdminNowDeliveryTime'), 'width' => 'auto', 'type' => 'datetime'),
		);

		parent::__construct();
	}
}