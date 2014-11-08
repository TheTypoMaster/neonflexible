<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

require_once (_PS_MODULE_DIR_ . 'now_block_reinsurance/now_block_reinsurance.php');
require_once (_PS_MODULE_DIR_ . 'now_block_reinsurance/classes/NowBlockReinsurance.php');

class AdminBlockReinsuranceController extends ModuleAdminController {

	public function __construct()
	{
		$this->bootstrap = true;
		$this->table = 'now_block_reinsurance';
		$this->className = 'NowBlockReinsurance';
		$this->module = new now_block_reinsurance();

		$this->lang = true;

		$this->_defaultOrderBy		= 'position';
		$this->orderBy				= 'position';
		$this->position_identifier	= 'position';

		$this->addRowAction('edit');
		$this->addRowAction('delete');

		$this->bulk_actions = array(
			'delete' => array(
				'text' => $this->module->l('Delete selected', 'AdminBlockReinsurance'),
				'confirm' => $this->module->l('Delete selected items?', 'AdminBlockReinsurance'),
				'icon' => 'icon-trash'
			)
		);

		$this->context = Context::getContext();

		$this->fieldImageSettings = array(
			'name'	=> 'image',
			'dir'	=> 'now_block_reinsurance'
		);
		$this->imageType = 'png';

		$this->fields_list = array(
			'id_now_block_reinsurance'	=> array('title' => $this->module->l('ID', 'AdminBlockReinsurance'), 'align' => 'center', 'class' => 'fixed-width-xs'),
			'image'						=> array('title' => $this->module->l('Image', 'AdminBlockReinsurance'), 'align' => 'center', 'image' => 'now_block_reinsurance', 'class' => 'fixed-width-xs', 'orderby' => false, 'search' => false),
			'cms'						=> array('title' => $this->module->l('CMS Page linked', 'AdminBlockReinsurance'), 'width' => 'auto'),
			'name'						=> array('title' => $this->module->l('Name', 'AdminBlockReinsurance'), 'width' => 'auto'),
			'active'					=> array('title' => $this->module->l('Enabled', 'AdminBlockReinsurance'), 'width' => 25, 'align' => 'center', 'active' => 'status', 'type' => 'bool', 'orderby' => false, 'class' => 'fixed-width-sm'),
			'position'					=> array('title' => $this->module->l('Position', 'AdminBlockReinsurance'), 'filter_key' => 'a!position', 'position' => 'position', 'align' => 'center'),
			'date_upd'					=> array('title' => $this->module->l('Updated Date', 'AdminBlockReinsurance'), 'width' => 'auto', 'type' => 'datetime'),
		);

		$this->_select	= ' cl.`meta_title` as "cms"';
		$this->_join	= ' LEFT JOIN `' . _DB_PREFIX_ . 'cms_lang` cl ON (cl.id_cms = a.id_cms AND cl.id_lang = ' . $this->context->language->id . ')';

		parent::__construct();
	}

	/**
	 * @return mixed
	 */
	public function renderForm()
	{
		if (($obj = $this->loadObject(true)) && Validate::isLoadedObject($obj))
		{
			$link = $obj->getImageLink();

			if (file_exists($obj->getImageLink(_PS_IMG_DIR_))) {
				$size = round(filesize($obj->getImageLink(_PS_IMG_DIR_)) / 1024);
			}
		}

		$this->fields_form = array(
			'legend' => array(
				'title' => $this->module->l('Block reinsurance', 'AdminBlockReinsurance'),
				'icon' => 'icon-list-alt'
			),
			'input' => array(
				array(
					'type' => 'text',
					'label' => $this->module->l('Name', 'AdminBlockReinsurance'),
					'name' => 'name',
					'required' => true,
					'lang' => true
				),
				array(
					'type' => 'text',
					'label' => $this->module->l('Link', 'AdminBlockReinsurance'),
					'name' => 'link',
					'lang' => true
				),
				array(
					'type' => 'textarea',
					'label' => $this->module->l('Description', 'AdminBlockReinsurance'),
					'name' => 'description',
					'required' => true,
					'lang' => true,
					'autoload_rte' => true
				),
				array(
					'type' => 'file',
					'label' => $this->module->l('Image (.png)', 'AdminBlockReinsurance'),
					'name' => 'image',
					'display_image' => true,
					'show_thumbnail' => true,
					'image' => isset($link) ? '<img src="' . $link . '"/>' : null,
					'size' => isset($size) ? $size : null,
				),
				array(
					'type' => 'select',
					'label' => $this->module->l('CMS page linked', 'AdminBlockReinsurance'),
					'name' => 'id_cms',
					'required' => true,
					'default_value' => 'left',
					'options' => array(
						'query' => CMS::listCms($this->context->language->id),
						'id'	=> 'id_cms',
						'name'	=> 'meta_title',
						'default' => array(
							'label' => $this->module->l('No cms page', 'AdminBlockReinsurance'),
							'value' => 0
						)
					)
				),
				array(
					'type' => 'switch',
					'label' => $this->module->l('Enable', 'AdminBlockReinsurance'),
					'name' => 'active',
					'required' => false,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->module->l('Enabled', 'AdminBlockReinsurance')
						),
						array(
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->module->l('Disabled', 'AdminBlockReinsurance')
						)
					)
				)
			),
			'submit' => array(
				'title' => $this->module->l('Save', 'AdminBlockReinsurance')
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
		$id_now_block_reinsurance	= (int)(Tools::getValue('id'));
		$positions					= Tools::getValue($this->table);

		foreach ($positions as $position => $value)
		{
			$pos = explode('_', $value);

			if (isset($pos[2]) && (int)$pos[2] === $id_now_block_reinsurance) {
				if ($oNowBlockReinsurance = new NowBlockReinsurance((int)$pos[2])) {
					if (isset($position) && $oNowBlockReinsurance->updatePosition($way, $position)) {
						echo 'ok position '.(int)$position.' for bloc reinsurance '.(int)$pos[1].'\r\n';
					} else {
						echo '{"hasError" : true, "errors" : "Can not update bloc reinsurance '.(int)$id_now_block_reinsurance.' to position '.(int)$position.' "}';
					}
				} else {
					echo '{"hasError" : true, "errors" : "This bloc reinsurance ('.(int)$id_now_block_reinsurance.') can t be loaded"}';
				}

				break;
			}
		}
	}

	/**
	 * @return mixed
	 */
	public function processDelete() {
		$oNowBlockReinsurance = $this->loadObject();

		if (!$oNowBlockReinsurance->deleteImage()) {
			$this->errors[] = Tools::displayError('An error occurred while deleting image of the object (NowBlockReinsurance).').' <b>'.$this->table.'</b> ';
		}

		return parent::processDelete();
	}

	/**
	 * @return bool
	 */
	protected function processBulkDelete() {
		if (is_array($this->boxes) && !empty($this->boxes)) {
			foreach ($this->boxes as $iIdNowBlockReinsurance) {
				$oNowBlockReinsurance = new NowBlockReinsurance((int)$iIdNowBlockReinsurance);

				if (!$oNowBlockReinsurance->deleteImage()) {
					$this->errors[] = Tools::displayError('An error occurred while deleting image of the object (NowBlockReinsurance).').' <b>'.$this->table.'</b> ';
					return false;
				}
			}
		}

		return parent::processBulkDelete();
	}
}