<?php
/*
 * 2013
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

include (_PS_MODULE_DIR_.'now_order_return/classes/Module.php');

class now_order_return extends NowModule {

	function __construct() {
		$this->name				= 'now_order_return';
		$this->tab				= 'front_office_features';
		$this->version			= 1.0;
		$this->author			= 'NinjaOfWeb';
		$this->need_instance	= 0;

		parent::__construct();

		$this->displayName = $this->l('Order return');
		$this->description = $this->l('Send an email to the administrator if a customer registers a return');

		if ($this->active) {
			$this->module_dir = _PS_MODULE_DIR_.$this->name.DIRECTORY_SEPARATOR;
		}
	}

	public function install()
	{
		$this->aConfigurationDefaultSettings = array(
			'NOW_ORDER_RETURN_ACTIVE' => true,
			'NOW_ORDER_RETURN_EMAIL' => Configuration::get('PS_SHOP_EMAIL'),
		);

		return parent::install() && $this->registerHook('orderReturn');
	}

	public function hookOrderReturn($params) {
		$sEmail = Configuration::get('NOW_ORDER_RETURN_EMAIL');
		if (Configuration::get('NOW_ORDER_RETURN_ACTIVE') && $sEmail) {
			$oOrderReturn = $params['orderReturn'];
			$oOrder = new Order((int)$oOrderReturn->id_order);

			if (Validate::isLoadedObject($oOrderReturn)) {
				$context = Context::getContext();
				$aProducts = OrderReturn::getOrdersReturnProducts((int)$oOrderReturn->id, $oOrder);

				$aParamsEmail = array(
					'{firstname}' => $context->customer->firstname,
					'{lastname}' => $context->customer->lastname,
					'{id_order_return}' => $oOrderReturn->id,
					'{id_order}' => $oOrderReturn->id_order,
					'{product_html}' => $this->getProductListforEmailHTML($aProducts),
					'{product_txt}' => $this->getProductListforEmailTXT($aProducts),
				);

				$return = Mail::Send(
					$context->language->id,
					'order_return',
					Mail::l('Order Return', $context->language->id),
					$aParamsEmail,
					$sEmail,
					'Administrator',
					$context->customer->email,
					$context->customer->lastname.' - '.$context->customer->firstname,
					NULL, NULL, $this->module_dir.'mails/'
				);

				return $return;
			}
		}
	}

	public function getProductListforEmailHTML($aProducts) {
		$html = '<table width="300">';
		$html .= '<tr>';
		$html .= '<th>'.$this->l('Product name').'</th>';
		$html .= '<th>'.$this->l('Quantity').'</th>';
		$html .= '</tr>';

		foreach ($aProducts as $aProduct) {
			$html .= '<tr>';
			$html .= '<th>'.$aProduct['product_name'].'</th>';
			$html .= '<th>'.$aProduct['product_quantity'].'</th>';
			$html .= '</tr>';
		}
		$html .= '</table>';

		return $html;
	}

	public function getProductListforEmailTXT($aProducts) {
		$html = '';

		foreach ($aProducts as $aProduct) {
			$html .= $aProduct['product_name'].' ('.$this->l('Quantity').': '.$aProduct['product_quantity'].'), ';
		}

		return $html;
	}

	public function getContent()
	{
		$output = '<h2>'.$this->displayName.'</h2>';
		if (Tools::isSubmit('submitOrderReturn'))
		{
			Configuration::updateValue('NOW_ORDER_RETURN_ACTIVE', (int)(Tools::getValue('NOW_ORDER_RETURN_ACTIVE')));
			if (Validate::isEmail(Tools::getValue('NOW_ORDER_RETURN_EMAIL'))) {
				Configuration::updateValue('NOW_ORDER_RETURN_EMAIL', Tools::getValue('NOW_ORDER_RETURN_EMAIL'));
				$output .= '<div class="conf confirm">'.$this->l('Settings updated').'</div>';
			} else {
				$output .= '<div class="error">'.$this->l('Email Error').'</div>';
			}
		}
		return $output.$this->displayForm();
	}

	public function displayForm()
	{
		return '
		<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">
			<fieldset>
				<legend><img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->l('Settings').'</legend>
				<label>'.$this->l('Send a email to the administrator if a customer registers a return ?').'</label>
				<div class="margin-form">
					<input type="radio" name="NOW_ORDER_RETURN_ACTIVE" id="display_on" value="1" '.(Tools::getValue('NOW_ORDER_RETURN_ACTIVE', Configuration::get('NOW_ORDER_RETURN_ACTIVE')) ? 'checked="checked" ' : '').'/>
					<label class="t" for="display_on"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Yes').'" title="'.$this->l('Yes').'" /></label>
					<input type="radio" name="NOW_ORDER_RETURN_ACTIVE" id="display_off" value="0" '.(!Tools::getValue('NOW_ORDER_RETURN_ACTIVE', Configuration::get('NOW_ORDER_RETURN_ACTIVE')) ? 'checked="checked" ' : '').'/>
					<label class="t" for="display_off"> <img src="../img/admin/disabled.gif" alt="'.$this->l('No').'" title="'.$this->l('No').'" /></label>
				</div><br />
				<label>'.$this->l('Email:').'</label>
				<div class="margin-form">
					<input type="text" name="NOW_ORDER_RETURN_EMAIL" id="display_on" value="'.(Configuration::get('NOW_ORDER_RETURN_EMAIL') ? Configuration::get('NOW_ORDER_RETURN_EMAIL') : '').'" />
				</div>
				<center><input type="submit" name="submitOrderReturn" value="'.$this->l('Save').'" class="button" /></center>
			</fieldset>
		</form>';
	}
}

