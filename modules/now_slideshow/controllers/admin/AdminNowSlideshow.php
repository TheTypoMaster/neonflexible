<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

require_once (_PS_MODULE_DIR_ . 'now_slideshow/now_slideshow.php');
require_once (_PS_MODULE_DIR_ . 'now_slideshow/classes/NowSlideshow.php');

class AdminNowSlideshowController extends ModuleAdminController {

	public function __construct()
	{
		$this->bootstrap = true;
		$this->table = 'now_slideshow';
		$this->className = 'NowSlideshow';
		$this->override_folder = 'now_slideshow';
		$this->module = new now_slideshow();

		$this->lang = true;

		$this->_defaultOrderBy		= 'position';
		$this->orderBy				= 'position';
		$this->position_identifier	= 'position';

		$this->addRowAction('edit');
		$this->addRowAction('delete');

		$this->bulk_actions = array(
			'delete' => array(
				'text' => $this->module->l('Delete selected', 'AdminNowSlideshow'),
				'confirm' => $this->module->l('Delete selected items?', 'AdminNowSlideshow'),
				'icon' => 'icon-trash'
			)
		);

		$this->context = Context::getContext();

		$this->fieldImageSettings = array(
			'name'	=> 'image',
			'dir'	=> 'now_slideshow'
		);
		$this->imageType = 'jpg';

		$this->fields_list = array(
			'id_now_slideshow'			=> array('title' => $this->module->l('ID', 'AdminNowSlideshow'), 'align' => 'center', 'class' => 'fixed-width-xs'),
			'image'						=> array('title' => $this->module->l('Image', 'AdminNowSlideshow'), 'align' => 'center', 'image' => 'now_slideshow', 'class' => 'fixed-width-xs', 'orderby' => false, 'search' => false),
			'id_type'					=> array('title' => $this->module->l('type ID', 'AdminNowSlideshow'), 'width' => 'auto'),
			'item_type'					=> array('title' => $this->module->l('Type of link', 'AdminNowSlideshow'), 'width' => 'auto'),
			'name_type'					=> array('title' => $this->module->l('Name of link', 'AdminNowSlideshow'), 'width' => 'auto'),
			'name'						=> array('title' => $this->module->l('Slide Name', 'AdminNowSlideshow'), 'width' => 'auto'),
			'title'						=> array('title' => $this->module->l('Slide title', 'AdminNowSlideshow'), 'width' => 'auto'),
			'active'					=> array('title' => $this->module->l('Enabled', 'AdminNowSlideshow'), 'width' => 25, 'align' => 'center', 'active' => 'status', 'type' => 'bool', 'orderby' => false, 'class' => 'fixed-width-sm'),
			'position'					=> array('title' => $this->module->l('Position', 'AdminNowSlideshow'), 'filter_key' => 'a!position', 'position' => 'position', 'align' => 'center'),
			'date_upd'					=> array('title' => $this->module->l('Updated Date', 'AdminNowSlideshow'), 'width' => 'auto', 'type' => 'datetime'),
		);

		$this->_select	= '
		IF(a.`id_type` = 0, NULL, a.`id_type`) as id_type,
		(
			CASE a.type
				WHEN "category"		THEN "' . $this->module->l('Category', 'AdminNowSlideshow') . '"
				WHEN "cms"			THEN "' . $this->module->l('CMS', 'AdminNowSlideshow') . '"
				WHEN "link" 		THEN "' . $this->module->l('Link', 'AdminNowSlideshow') . '"
				WHEN "manufacturer"	THEN "' . $this->module->l('Manufacturer', 'AdminNowSlideshow') . '"
			END
		) as "item_type", (
			CASE a.type
				WHEN "category"		THEN (SELECT cl.`name` FROM `' . _DB_PREFIX_ . 'category_lang` cl WHERE cl.`id_category` = a.`id_type` AND cl.`id_lang` = ' . (int)$this->context->language->id . ')
				WHEN "link" 		THEN b.link
				WHEN "manufacturer"	THEN (SELECT m.`name` FROM `' . _DB_PREFIX_ . 'manufacturer` m WHERE m.`id_manufacturer` = a.`id_type`)
				WHEN "cms"			THEN (SELECT cml.`meta_title` FROM `' . _DB_PREFIX_ . 'cms_lang` cml WHERE cml.`id_cms` = a.`id_type` AND cml.`id_lang` = ' . (int)$this->context->language->id . ')
			END
		) as "name_type"';

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
				'title' => $this->module->l('Block Slideshow', 'AdminNowSlideshow'),
				'icon' => 'icon-list-alt'
			),
			'input' => array(
				array(
					'type' => 'text',
					'label' => $this->module->l('Name', 'AdminNowSlideshow'),
					'name' => 'name',
					'required' => true,
					'lang' => true
				),
				array(
					'type' => 'text',
					'label' => $this->module->l('Title', 'AdminNowSlideshow'),
					'name' => 'title',
					'required' => true,
					'lang' => true
				),
				array(
					'type' => 'text',
					'label' => $this->module->l('Button name', 'AdminNowSlideshow'),
					'name' => 'button_name',
					'required' => true,
					'lang' => true
				),
				array(
					'type' => 'textarea',
					'label' => $this->module->l('Description', 'AdminNowSlideshow'),
					'name' => 'description',
					'required' => true,
					'lang' => true
				),
				array(
					'type' => 'select',
					'label' => $this->module->l('Type of link', 'AdminNowSlideshow'),
					'name' => 'type',
					'required' => true,
					'default_value' => NowSlideshow::TYPE_LINK,
					'options' => array(
						'query' => array(
							array('id_type' => NowSlideshow::TYPE_CATEGORY, 'type' => $this->module->l('Category', 'AdminNowSlideshow')),
							array('id_type' => NowSlideshow::TYPE_CMS, 'type' => $this->module->l('CMS', 'AdminNowSlideshow')),
							array('id_type' => NowSlideshow::TYPE_LINK, 'type' => $this->module->l('Link', 'AdminNowSlideshow')),
							array('id_type' => NowSlideshow::TYPE_MANUFACTURER, 'type' => $this->module->l('Manufacturer', 'AdminNowSlideshow')),
						),
						'id'	=> 'id_type',
						'name'	=> 'type',
					)
				),
				array(
					'type' => 'text',
					'label' => $this->module->l('ID type', 'AdminNowSlideshow'),
					'name' => 'id_type'
				),
				array(
					'type' => 'text',
					'label' => $this->module->l('Link', 'AdminNowSlideshow'),
					'name' => 'link',
					'lang' => true
				),
				array(
					'type' => 'file',
					'label' => $this->module->l('Image (.png)', 'AdminNowSlideshow'),
					'name' => 'image',
					'display_image' => true,
					'show_thumbnail' => true,
					'image' => isset($link) ? '<img src="' . $link . '"/>' : null,
					'size' => isset($size) ? $size : null,
				),
				array(
					'type' => 'switch',
					'label' => $this->module->l('Enable', 'AdminNowSlideshow'),
					'name' => 'active',
					'required' => false,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->module->l('Enabled', 'AdminNowSlideshow')
						),
						array(
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->module->l('Disabled', 'AdminNowSlideshow')
						)
					)
				)
			),
			'submit' => array(
				'title' => $this->module->l('Save', 'AdminNowSlideshow')
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
		$id_now_slideshow			= (int)(Tools::getValue('id'));
		$positions					= Tools::getValue($this->table);

		foreach ($positions as $position => $value)
		{
			$pos = explode('_', $value);

			if (isset($pos[2]) && (int)$pos[2] === $id_now_slideshow) {
				if ($oNowSlideshow = new NowSlideshow((int)$pos[2])) {
					if (isset($position) && $oNowSlideshow->updatePosition($way, $position)) {
						echo 'ok position '.(int)$position.' for bloc slideshow '.(int)$pos[1].'\r\n';
					} else {
						echo '{"hasError" : true, "errors" : "Can not update bloc slideshow '.(int)$id_now_slideshow.' to position '.(int)$position.' "}';
					}
				} else {
					echo '{"hasError" : true, "errors" : "This bloc slideshow ('.(int)$id_now_slideshow.') can t be loaded"}';
				}

				break;
			}
		}
	}

	/**
	 * @return mixed
	 */
	public function processDelete() {
		$oNowSlideshow = $this->loadObject();

		if (!$oNowSlideshow->deleteImage()) {
			$this->errors[] = Tools::displayError('An error occurred while deleting image of the object (NowSlideshow).').' <b>'.$this->table.'</b> ';
		}

		return parent::processDelete();
	}

	/**
	 * @return bool
	 */
	protected function processBulkDelete() {
		if (is_array($this->boxes) && !empty($this->boxes)) {
			foreach ($this->boxes as $iIdNowSlideshow) {
				$oNowSlideshow = new NowSlideshow((int)$iIdNowSlideshow);

				if (!$oNowSlideshow->deleteImage()) {
					$this->errors[] = Tools::displayError('An error occurred while deleting image of the object (NowSlideshow).').' <b>'.$this->table.'</b> ';
					return false;
				}
			}
		}

		return parent::processBulkDelete();
	}
}