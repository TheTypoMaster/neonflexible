<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

require_once(_PS_MODULE_DIR_ . 'now_seo_links/now_seo_links.php');
require_once(_PS_MODULE_DIR_ . 'now_seo_links/classes/NowLanguageLink.php');

class AdminLanguageLinkController extends ModuleAdminController {

	public function __construct()
	{
		$this->bootstrap = true;
		$this->table = 'now_language_link';
		$this->className = 'NowLanguageLink';
		$this->module = new now_seo_links();

		$this->addRowAction('edit');

		$this->fields_list = array(
			'id_now_language_link'	=> array('title' => $this->module->l('ID', 'AdminLanguageLink'), 'align' => 'center', 'class' => 'fixed-width-xs'),
			'name'				=> array('title' => $this->module->l('Lang', 'AdminLanguageLink'), 'width' => 'auto'),
			'folder_name'			=> array('title' => $this->module->l('Folder name', 'AdminLanguageLink'), 'width' => 'auto'),
			'date_upd'				=> array('title' => $this->module->l('Updated Date', 'AdminLanguageLink'), 'width' => 'auto'),
		);

		$this->_select	= ' l.`name`';
		$this->_join	= ' INNER JOIN `' . _DB_PREFIX_ . 'lang` l ON (l.id_lang = a.id_lang)';

		parent::__construct();
	}

	/**
	 * @return mixed
	 */
	public function renderForm()
	{
		$this->fields_form = array(
			'legend' => array(
				'title' => $this->module->l('URL language', 'AdminLanguageLink'),
				'icon' => 'icon-globe'
			),
			'input' => array(
				array(
					'type' => 'text',
					'label' => $this->module->l('Folder name', 'AdminLanguageLink'),
					'name' => 'folder_name'
				),
				array(
					'type' => 'hidden',
					'name' => 'id_lang'
				),
			),
			'submit' => array(
				'title' => $this->module->l('Save', 'AdminLanguageLink')
			)
		);

		return parent::renderForm();
	}
}