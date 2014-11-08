<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

require_once (_PS_MODULE_DIR_ . 'now_block_presentation/now_block_presentation.php');
require_once (_PS_MODULE_DIR_ . 'now_block_presentation/classes/NowBlockPresentation.php');

class AdminBlockPresentationController extends ModuleAdminController {

	public function __construct()
	{
		$this->bootstrap = true;
		$this->table = 'now_block_presentation';
		$this->className = 'NowBlockPresentation';
		$this->module = new now_block_presentation();

		$this->lang = true;

		$this->_defaultOrderBy		= 'position';
		$this->orderBy				= 'position';
		$this->position_identifier	= 'position';

		$this->addRowAction('edit');
		$this->addRowAction('delete');

		$this->bulk_actions = array(
			'delete' => array(
				'text' => $this->module->l('Delete selected', 'AdminBlockPresentation'),
				'confirm' => $this->module->l('Delete selected items?', 'AdminBlockPresentation'),
				'icon' => 'icon-trash'
			)
		);

		$this->context = Context::getContext();

		$this->fieldImageSettings = array(
			'name'	=> 'image',
			'dir'	=> 'now_block_presentation'
		);
		$this->imageType = 'png';

		$this->fields_list = array(
			'id_now_block_presentation'	=> array('title' => $this->module->l('ID', 'AdminBlockPresentation'), 'align' => 'center', 'class' => 'fixed-width-xs'),
			'image'						=> array('title' => $this->module->l('Image', 'AdminBlockPresentation'), 'align' => 'center', 'image' => 'now_block_presentation', 'class' => 'fixed-width-xs', 'orderby' => false, 'search' => false),
			'name'						=> array('title' => $this->module->l('Name', 'AdminBlockPresentation'), 'width' => 'auto'),
			'active'					=> array('title' => $this->module->l('Enabled', 'AdminBlockPresentation'), 'width' => 25, 'align' => 'center', 'active' => 'status', 'type' => 'bool', 'orderby' => false, 'class' => 'fixed-width-sm'),
			'position'					=> array('title' => $this->module->l('Position', 'AdminBlockPresentation'), 'filter_key' => 'a!position', 'position' => 'position', 'align' => 'center'),
			'date_upd'					=> array('title' => $this->module->l('Updated Date', 'AdminBlockPresentation'), 'width' => 'auto', 'type' => 'datetime'),
		);

		$aCmsList = array();

		foreach (CMS::listCms($this->context->language->id) as $aRow) {
			$aCmsList[] = array(
				'id_cms' => $aRow['id_cms'],
				'name' => $aRow['meta_title'],
			);
		}

		$this->fields_options = array(
			'contact' => array(
				'title' =>	$this->module->l('Presentation of the company option', 'AdminBlockPresentation'),
				'fields' =>	array(
					'NOW_PRESENTATION_CMS_ID' => array(
						'title' => $this->module->l('CMS Page', 'AdminBlockPresentation'),
						'desc' => $this->module->l('CMS page witch redirect when to click on the button "EN SAVOIR PLUS sur neon flexible"', 'AdminBlockPresentation'),
						'cast' => 'intval',
						'type' => 'select',
						'identifier' => 'id_cms',
						'list' => $aCmsList,
						'visibility' => Shop::CONTEXT_ALL
					),
				),
				'submit' => array('title' => $this->module->l('Save', 'AdminBlockPresentation'))
			)
		);

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
				'title' => $this->module->l('Presentation of the company', 'AdminBlockPresentation'),
				'icon' => 'icon-list-alt'
			),
			'input' => array(
				array(
					'type' => 'text',
					'label' => $this->module->l('Name', 'AdminBlockPresentation'),
					'name' => 'name',
					'required' => true,
					'lang' => true
				),
				array(
					'type' => 'text',
					'label' => $this->module->l('Link', 'AdminBlockPresentation'),
					'name' => 'link',
					'lang' => true
				),
				array(
					'type' => 'textarea',
					'label' => $this->module->l('Description', 'AdminBlockPresentation'),
					'name' => 'description',
					'required' => true,
					'lang' => true,
					'autoload_rte' => true
				),
				array(
					'type' => 'file',
					'label' => $this->module->l('Image (.png)', 'AdminBlockPresentation'),
					'name' => 'image',
					'display_image' => true,
					'show_thumbnail' => true,
					'image' => isset($link) ? '<img src="' . $link . '"/>' : null,
					'size' => isset($size) ? $size : null,
				),
				array(
					'type' => 'select',
					'label' => $this->module->l('Float', 'AdminBlockPresentation'),
					'name' => 'float',
					'required' => true,
					'default_value' => 'left',
					'options' => array(
						'query' => array(
							array('id' => 'left', 'name' => $this->module->l('Left', 'AdminBlockPresentation')),
							array('id' => 'right', 'name' => $this->module->l('Right', 'AdminBlockPresentation')),
						),
						'id' => 'id',
						'name' => 'name'
					)
				),
				array(
					'type' => 'switch',
					'label' => $this->module->l('Enable', 'AdminBlockPresentation'),
					'name' => 'active',
					'required' => false,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->module->l('Enabled', 'AdminBlockPresentation')
						),
						array(
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->module->l('Disabled', 'AdminBlockPresentation')
						)
					)
				)
			),
			'submit' => array(
				'title' => $this->module->l('Save', 'AdminBlockPresentation')
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
		$id_now_block_presentation	= (int)(Tools::getValue('id'));
		$positions					= Tools::getValue($this->table);

		foreach ($positions as $position => $value)
		{
			$pos = explode('_', $value);

			if (isset($pos[2]) && (int)$pos[2] === $id_now_block_presentation) {
				if ($oNowBlockPresentation = new NowBlockPresentation((int)$pos[2])) {
					if (isset($position) && $oNowBlockPresentation->updatePosition($way, $position)) {
						echo 'ok position '.(int)$position.' for bloc presentation '.(int)$pos[1].'\r\n';
					} else {
						echo '{"hasError" : true, "errors" : "Can not update bloc presentation '.(int)$id_now_block_presentation.' to position '.(int)$position.' "}';
					}
				} else {
					echo '{"hasError" : true, "errors" : "This bloc presentation ('.(int)$id_now_block_presentation.') can t be loaded"}';
				}

				break;
			}
		}
	}

	/**
	 * @return mixed
	 */
	public function processDelete() {
		$oNowBlockPresentation = $this->loadObject();

		if (!$oNowBlockPresentation->deleteImage()) {
			$this->errors[] = Tools::displayError('An error occurred while deleting image of the object (NowBlockPresentation).').' <b>'.$this->table.'</b> ';
		}

		return parent::processDelete();
	}

	/**
	 * @return bool
	 */
	protected function processBulkDelete() {
		if (is_array($this->boxes) && !empty($this->boxes)) {
			foreach ($this->boxes as $iIdNowBlockPresentation) {
				$oNowBlockPresentation = new NowBlockPresentation((int)$iIdNowBlockPresentation);

				if (!$oNowBlockPresentation->deleteImage()) {
					$this->errors[] = Tools::displayError('An error occurred while deleting image of the object (NowBlockPresentation).').' <b>'.$this->table.'</b> ';
					return false;
				}
			}
		}

		return parent::processBulkDelete();
	}
}