<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

require_once (_PS_MODULE_DIR_ . 'now_block_customer_references/now_block_customer_references.php');
require_once (_PS_MODULE_DIR_ . 'now_block_customer_references/classes/NowBlockCustomerReferences.php');

class AdminBlockCustomerReferencesController extends ModuleAdminController {

	public function __construct()
	{
		$this->bootstrap = true;
		$this->table = 'now_block_customer_references';
		$this->className = 'NowBlockCustomerReferences';
		$this->module = new now_block_customer_references();

		$this->lang = true;

		$this->_defaultOrderBy		= 'position';
		$this->orderBy				= 'position';
		$this->position_identifier	= 'position';

		$this->addRowAction('edit');
		$this->addRowAction('delete');

		$this->bulk_actions = array(
			'delete' => array(
				'text' => $this->module->l('Delete selected', 'AdminBlockCustomerReferences'),
				'confirm' => $this->module->l('Delete selected items?', 'AdminBlockCustomerReferences'),
				'icon' => 'icon-trash'
			)
		);

		$this->context = Context::getContext();

		$this->fieldImageSettings = array(
			'name'	=> 'image',
			'dir'	=> 'now_block_customer_references'
		);
		$this->imageType = 'png';

		$this->fields_list = array(
			'id_now_block_customer_references'	=> array('title' => $this->module->l('ID', 'AdminBlockCustomerReferences'), 'align' => 'center', 'class' => 'fixed-width-xs'),
			'image'								=> array('title' => $this->module->l('Image', 'AdminBlockCustomerReferences'), 'align' => 'center', 'image' => 'now_block_customer_references', 'class' => 'fixed-width-xs', 'orderby' => false, 'search' => false),
			'name'								=> array('title' => $this->module->l('Name', 'AdminBlockCustomerReferences'), 'width' => 'auto'),
			'active'							=> array('title' => $this->module->l('Enabled', 'AdminBlockCustomerReferences'), 'width' => 25, 'align' => 'center', 'active' => 'status', 'type' => 'bool', 'orderby' => false, 'class' => 'fixed-width-sm'),
			'position'							=> array('title' => $this->module->l('Position', 'AdminBlockCustomerReferences'), 'filter_key' => 'a!position', 'position' => 'position', 'align' => 'center'),
			'date_upd'							=> array('title' => $this->module->l('Updated Date', 'AdminBlockCustomerReferences'), 'width' => 'auto', 'type' => 'datetime'),
		);

		$aCmsList = array();

		foreach (CMS::listCms($this->context->language->id) as $aRow) {
			$aCmsList[] = array(
				'id_cms' => $aRow['id_cms'],
				'name' => $aRow['meta_title'],
			);
		}

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
				'title' => $this->module->l('Bloc customer reference', 'AdminBlockCustomerReferences'),
				'icon' => 'icon-list-alt'
			),
			'input' => array(
				array(
					'type' => 'text',
					'label' => $this->module->l('Name', 'AdminBlockCustomerReferences'),
					'name' => 'name',
					'required' => true,
					'lang' => true
				),
				array(
					'type' => 'text',
					'label' => $this->module->l('Link', 'AdminBlockCustomerReferences'),
					'name' => 'link',
					'lang' => true
				),
				array(
					'type' => 'textarea',
					'label' => $this->module->l('Description', 'AdminBlockCustomerReferences'),
					'name' => 'description',
					'required' => true,
					'lang' => true,
					'autoload_rte' => true
				),
				array(
					'type' => 'file',
					'label' => $this->module->l('Image (.png)', 'AdminBlockCustomerReferences'),
					'name' => 'image',
					'display_image' => true,
					'show_thumbnail' => true,
					'image' => isset($link) ? '<img src="' . $link . '"/>' : null,
					'size' => isset($size) ? $size : null,
				),
				array(
					'type' => 'switch',
					'label' => $this->module->l('Enable', 'AdminBlockCustomerReferences'),
					'name' => 'active',
					'required' => false,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->module->l('Enabled', 'AdminBlockCustomerReferences')
						),
						array(
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->module->l('Disabled', 'AdminBlockCustomerReferences')
						)
					)
				)
			),
			'submit' => array(
				'title' => $this->module->l('Save', 'AdminBlockCustomerReferences')
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
		$id_now_block_customer_references	= (int)(Tools::getValue('id'));
		$positions					= Tools::getValue($this->table);

		foreach ($positions as $position => $value)
		{
			$pos = explode('_', $value);

			if (isset($pos[2]) && (int)$pos[2] === $id_now_block_customer_references) {
				if ($oNowBlockCustomerReferences = new NowBlockCustomerReferences((int)$pos[2])) {
					if (isset($position) && $oNowBlockCustomerReferences->updatePosition($way, $position)) {
						echo 'ok position '.(int)$position.' for bloc customer reference '.(int)$pos[1].'\r\n';
					} else {
						echo '{"hasError" : true, "errors" : "Can not update bloc customer reference '.(int)$id_now_block_customer_references.' to position '.(int)$position.' "}';
					}
				} else {
					echo '{"hasError" : true, "errors" : "This bloc customer reference ('.(int)$id_now_block_customer_references.') can t be loaded"}';
				}

				break;
			}
		}
	}

	/**
	 * @return mixed
	 */
	public function processDelete() {
		$oNowBlockCustomerReferences = $this->loadObject();

		if (!$oNowBlockCustomerReferences->deleteImage()) {
			$this->errors[] = Tools::displayError('An error occurred while deleting image of the object (NowBlockCustomerReferences).').' <b>'.$this->table.'</b> ';
		}

		return parent::processDelete();
	}

	/**
	 * @return bool
	 */
	protected function processBulkDelete() {
		if (is_array($this->boxes) && !empty($this->boxes)) {
			foreach ($this->boxes as $iIdNowBlockCustomerReferences) {
				$oNowBlockCustomerReferences = new NowBlockCustomerReferences((int)$iIdNowBlockCustomerReferences);

				if (!$oNowBlockCustomerReferences->deleteImage()) {
					$this->errors[] = Tools::displayError('An error occurred while deleting image of the object (NowBlockCustomerReferences).').' <b>'.$this->table.'</b> ';
					return false;
				}
			}
		}

		return parent::processBulkDelete();
	}
}