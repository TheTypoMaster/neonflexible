<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

require_once (_PS_MODULE_DIR_ . 'now_block_cms_footer/now_block_cms_footer.php');
require_once (_PS_MODULE_DIR_ . 'now_block_cms_footer/classes/NowBlockFooterCms.php');
require_once (_PS_MODULE_DIR_ . 'now_block_cms_footer/classes/NowBlockFooterCmsColumn.php');

class AdminNowBlockFooterCmsController extends ModuleAdminController {

	public function __construct()
	{
		$this->bootstrap			= true;
		$this->module				= new now_block_cms_footer();
		$this->override_folder		= 'now_block_cms_footer';

		if (Tools::getIsset('addnow_block_cms_footer') || Tools::getIsset('updatenow_block_cms_footer') || Tools::getIsset('submitAddnow_block_cms_footer')) {
			$this->table				= 'now_block_cms_footer';
			$this->identifier			= 'id_now_block_cms_footer';
			$this->className			= 'NowBlockFooterCms';
		} else {
			$this->table				= 'now_block_cms_footer_column';
			$this->identifier			= 'id_now_block_cms_footer_column';
			$this->className			= 'NowBlockFooterCmsColumn';
		}

		$this->_defaultOrderBy		= 'position';
		$this->orderBy				= 'position';
		$this->position_identifier	= 'position';

		$this->context = Context::getContext();

		parent::__construct();
	}

	/**
	 *
	 */
	public function initPageHeaderToolbar() {

		if ($this->display == 'details') {

			$this->page_header_toolbar_btn['back_to_list'] = array(
				'href' => Context::getContext()->link->getAdminLink('AdminNowBlockFooterCms'),
				'desc' => $this->module->l('Back to list', 'AdminNowBlockFooterCms'),
				'icon' => 'process-icon-back'
			);

			$this->page_header_toolbar_btn['new_link'] = array(
				'href' => self::$currentIndex . '&addnow_block_cms_footer&token=' . $this->token . '&id_now_block_cms_footer_column=' . (int)Tools::getValue('id_now_block_cms_footer_column'),
				'desc' => $this->module->l('Add new link', 'AdminNowBlockFooterCms'),
				'icon' => 'process-icon-new'
			);

		} elseif (empty($this->display)) {
			$this->page_header_toolbar_btn['new_column'] = array(
				'href' => self::$currentIndex . '&addnow_block_cms_footer_column&token=' . $this->token,
				'desc' => $this->module->l('Add new column', 'AdminNowBlockFooterCms'),
				'icon' => 'process-icon-new'
			);
		}

		parent::initPageHeaderToolbar();
	}

	/**
	 * AdminController::renderList() override
	 * @see AdminController::renderList()
	 */
	public function renderList() {

		$this->lang = true;

		$this->addRowAction('edit');
		$this->addRowAction('details');
		$this->addRowAction('delete');

		$this->fields_list = array(
			'id_now_block_cms_footer_column'	=> array('title' => $this->module->l('ID', 'AdminNowBlockFooterCms'), 'align' => 'center', 'class' => 'fixed-width-xs'),
			'name'								=> array('title' => $this->module->l('Column Name', 'AdminNowBlockFooterCms'), 'width' => 'auto'),
			'active'							=> array('title' => $this->module->l('Enabled', 'AdminNowBlockFooterCms'), 'width' => 25, 'align' => 'center', 'active' => 'status', 'type' => 'bool', 'orderby' => false, 'class' => 'fixed-width-sm'),
			'position'							=> array('title' => $this->module->l('Position', 'AdminNowBlockFooterCms'), 'filter_key' => 'a!position', 'position' => 'position', 'align' => 'center'),
			'date_upd'							=> array('title' => $this->module->l('Updated Date', 'AdminNowBlockFooterCms'), 'width' => 'auto', 'type' => 'datetime'),
		);

		return parent::renderList();
	}

	/**
	 * @return mixed
	 */
	public function initProcess() {
		if (Tools::getIsset('detailsnow_block_cms_footer_column')) {
			$this->list_id = 'details';

			if (isset($_POST['submitReset' . $this->list_id])) {
				$this->processResetFilters();
			}
		} elseif (Tools::getIsset('addnow_block_cms_footer') || Tools::getIsset('updatenow_block_cms_footer')) {

			if ($this->tabAccess['add'] === '1') {
				$this->action = 'new';
				$this->display = 'add';
			} else {
				$this->errors[] = Tools::displayError('You do not have permission to add this.');
			}

		} else {
			$this->list_id = 'column';
		}

		return parent::initProcess();
	}

	/**
	 * @return mixed
	 */
	public function renderDetails() {
		if (($id = Tools::getValue('id_now_block_cms_footer_column'))) {
			$this->table				= 'now_block_cms_footer';
			$this->identifier			= 'id_now_block_cms_footer';
			$this->className			= 'NowBlockFooterCms';
			$this->list_id				= 'details';
			$this->lang					= true;

			$this->toolbar_btn['new']	= array(
				'href' => self::$currentIndex . '&amp;addnow_block_cms_footer&amp;token=' . $this->token . '&amp;id_now_block_cms_footer_column=' . (int)Tools::getValue('id_now_block_cms_footer_column'),
				'desc' => $this->l('Add new')
			);

			$this->addRowAction('edit');
			$this->addRowAction('delete');

			$this->fields_list = array(
				'id_now_block_cms_footer'	=> array('title' => $this->module->l('ID', 'AdminNowBlockFooterCms'), 'align' => 'center', 'class' => 'fixed-width-xs'),
				'id_type'					=> array('title' => $this->module->l('type ID', 'AdminNowBlockFooterCms'), 'width' => 'auto'),
				'item_type'					=> array('title' => $this->module->l('Type of link', 'AdminNowBlockFooterCms'), 'width' => 'auto'),
				'name_type'					=> array('title' => $this->module->l('Link to the page', 'AdminNowBlockFooterCms'), 'width' => 'auto'),
				'name'						=> array('title' => $this->module->l('Name of link', 'AdminNowBlockFooterCms'), 'width' => 'auto'),
				'active'					=> array('title' => $this->module->l('Enabled', 'AdminNowBlockFooterCms'), 'width' => 25, 'align' => 'center', 'active' => 'status', 'type' => 'bool', 'orderby' => false, 'class' => 'fixed-width-sm'),
				'position'					=> array('title' => $this->module->l('Position', 'AdminNowBlockFooterCms'), 'filter_key' => 'a!position', 'position' => 'position', 'align' => 'center'),
				'date_upd'					=> array('title' => $this->module->l('Updated Date', 'AdminNowBlockFooterCms'), 'width' => 'auto', 'type' => 'datetime'),
			);

			$this->_select	= '
				IF(a.`id_type` = 0, NULL, a.`id_type`) as id_type,
				(
					CASE a.type
						WHEN "category"		THEN "' . $this->module->l('Category', 'AdminNowBlockFooterCms') . '"
						WHEN "cms"			THEN "' . $this->module->l('CMS', 'AdminNowBlockFooterCms') . '"
						WHEN "link" 		THEN "' . $this->module->l('Link', 'AdminNowBlockFooterCms') . '"
						WHEN "manufacturer"	THEN "' . $this->module->l('Manufacturer', 'AdminNowBlockFooterCms') . '"
					END
				) as "item_type", (
					CASE a.type
						WHEN "category"		THEN (SELECT cl.`name` FROM `' . _DB_PREFIX_ . 'category_lang` cl WHERE cl.`id_category` = a.`id_type` AND cl.`id_lang` = ' . (int)$this->context->language->id . ')
						WHEN "link" 		THEN b.link
						WHEN "manufacturer"	THEN (SELECT m.`name` FROM `' . _DB_PREFIX_ . 'manufacturer` m WHERE m.`id_manufacturer` = a.`id_type`)
						WHEN "cms"			THEN (SELECT cml.`meta_title` FROM `' . _DB_PREFIX_ . 'cms_lang` cml WHERE cml.`id_cms` = a.`id_type` AND cml.`id_lang` = ' . (int)$this->context->language->id . ')
					END
				) as "name_type"';

			$this->_where = 'AND a.`id_now_block_cms_footer_column` = ' . (int)$id;

			self::$currentIndex = self::$currentIndex . '&detailsnow_block_cms_footer_column';

			$this->processFilter();

			return parent::renderList();
		}
	}

	/**
	 * Display edit action link
	 */
	public function displayEditLink($token = null, $id, $name = null)
	{
		if ($this->tabAccess['edit'] == 1) {
			$tpl = $this->createTemplate('helpers/list/list_action_edit.tpl');
			if (!array_key_exists('Edit', self::$cache_lang)) {
				self::$cache_lang['Edit'] = $this->l('Edit', 'Helper');
			}

			if (Tools::isSubmit('detailsnow_block_cms_footer_column') && Tools::getValue('id_now_block_cms_footer_column')) {
				$tpl->assign(array(
					'href' => self::$currentIndex.'&id_now_block_cms_footer=' . $id . '&updatenow_block_cms_footer&token=' . ($token != null ? $token : $this->token),
					'action' => self::$cache_lang['Edit'],
					'id' => $id
				));
			} else {
				$tpl->assign(array(
					'href' => self::$currentIndex.'&id_now_block_cms_footer_column=' . $id . '&updatenow_block_cms_footer_column&token=' . ($token != null ? $token : $this->token),
					'action' => self::$cache_lang['Edit'],
					'id' => $id
				));
			}

			return $tpl->fetch();
		} else {
			return;
		}
	}

	/**
	 * @return mixed
	 */
	public function renderForm() {
		if (Tools::getIsset('addnow_block_cms_footer') || Tools::getIsset('updatenow_block_cms_footer')) {
			return $this->renderFormLink();
		} else {
			return $this->renderFormColumn();
		}
	}

	/**
	 * @return mixed
	 */
	public function renderFormColumn() {
		$this->context->smarty->assign(array(
			'back_url_override' => self::$currentIndex . '&token=' . $this->token
		));

		$this->fields_form = array(
			'legend' => array(
				'title' => $this->module->l('Footer Column', 'AdminNowBlockFooterCms'),
				'icon' => 'icon-list-alt'
			),
			'input' => array(
				array(
					'type' => 'text',
					'label' => $this->module->l('Name', 'AdminNowBlockFooterCms'),
					'name' => 'name',
					'required' => true,
					'lang' => true
				),
				array(
					'type' => 'switch',
					'label' => $this->module->l('Enable', 'AdminNowBlockFooterCms'),
					'name' => 'active',
					'required' => false,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->module->l('Enabled', 'AdminNowBlockFooterCms')
						),
						array(
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->module->l('Disabled', 'AdminNowBlockFooterCms')
						)
					)
				)
			),
			'submit' => array(
				'title' => $this->module->l('Save', 'AdminNowBlockFooterCms')
			)
		);

		return parent::renderForm();
	}

	/**
	 * @return mixed
	 */
	public function renderFormLink() {
		$this->context->smarty->assign(array(
			'back_url_override' => self::$currentIndex . '&id_now_block_cms_footer_column=' . Tools::getValue('id_now_block_cms_footer_column', $this->object->id_now_block_cms_footer_column) . '&detailsnow_block_cms_footer_column&token=' . $this->token
		));

		$obj = $this->loadObject(true);
		$this->fields_form = array(
			'legend' => array(
				'title' => $this->module->l('Link', 'AdminNowBlockFooterCms'),
				'icon' => 'icon-list-alt'
			),
			'input' => array(
				array(
					'type' => 'select',
					'label' => $this->module->l('Column', 'AdminNowBlockFooterCms'),
					'name' => 'id_now_block_cms_footer_column',
					'options' => array(
						'query' => NowBlockFooterCmsColumn::getColumns($this->context->language->id, true, true),
						'id'	=> 'id_now_block_cms_footer_column',
						'name'	=> 'name',
					)
				),
				array(
					'type' => 'select',
					'label' => $this->module->l('Type of link', 'AdminNowBlockFooterCms'),
					'name' => 'type',
					'required' => true,
					'default_value' => NowBlockFooterCms::TYPE_LINK,
					'options' => array(
						'query' => array(
							array('id_type' => NowBlockFooterCms::TYPE_CATEGORY, 'type' => $this->module->l('Category', 'AdminNowBlockFooterCms')),
							array('id_type' => NowBlockFooterCms::TYPE_CMS, 'type' => $this->module->l('CMS', 'AdminNowBlockFooterCms')),
							array('id_type' => NowBlockFooterCms::TYPE_LINK, 'type' => $this->module->l('Link', 'AdminNowBlockFooterCms')),
							array('id_type' => NowBlockFooterCms::TYPE_MANUFACTURER, 'type' => $this->module->l('Manufacturer', 'AdminNowBlockFooterCms')),
						),
						'id'	=> 'id_type',
						'name'	=> 'type',
					)
				),
				array(
					'type' => 'text',
					'label' => $this->module->l('ID type', 'AdminNowBlockFooterCms'),
					'name' => 'id_type',
					'form_group_class' => 'idTypeDiv'
				),
				array(
					'type'  => 'categories',
					'label' => $this->l('Category', 'AdminNowBlockFooterCms'),
					'name'  => 'category',
					'tree'  => array(
						'id' => 'categories-tree',
						'selected_categories'	=> array($obj->type == 'category' ? $obj->id_type : ''),
					),
					'form_group_class' => 'categoryDiv'
				),
				array(
					'type' => 'select',
					'label' => $this->module->l('CMS', 'AdminNowBlockFooterCms'),
					'name' => 'cms',
					'options' => array(
						'query' => CMS::listCms($this->context->language->id),
						'id'	=> 'id_cms',
						'name'	=> 'meta_title',
					),
					'form_group_class' => 'cmsDiv'
				),
				array(
					'type' => 'select',
					'label' => $this->module->l('Manufacturer', 'AdminNowBlockFooterCms'),
					'name' => 'manufacturer',
					'options' => array(
						'query' => Manufacturer::getManufacturers(false, $this->context->language->id),
						'id'	=> 'id_manufacturer',
						'name'	=> 'name',
					),
					'form_group_class' => 'manufacturerDiv'
				),
				array(
					'type' => 'text',
					'label' => $this->module->l('Link', 'AdminNowBlockFooterCms'),
					'name' => 'link',
					'form_group_class' => 'linkDiv',
					'lang' => true
				),
				array(
					'type' => 'text',
					'label' => $this->module->l('Name', 'AdminNowBlockFooterCms'),
					'name' => 'name',
					'lang' => true,
					'desc' => $this->module->l('Complete this field only if you want to change the name that will appear in the footer.', 'AdminNowBlockFooterCms')
				),
				array(
					'type' => 'switch',
					'label' => $this->module->l('Enable', 'AdminNowBlockFooterCms'),
					'name' => 'active',
					'required' => false,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->module->l('Enabled', 'AdminNowBlockFooterCms')
						),
						array(
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->module->l('Disabled', 'AdminNowBlockFooterCms')
						)
					)
				)
			),
			'submit' => array(
				'title' => $this->module->l('Save', 'AdminNowBlockFooterCms')
			)
		);

		return parent::renderForm();
	}

	/**
	 * Manage position in ajax
	 */
	public function ajaxProcessUpdatePositions() {
		if (Tools::isSubmit('now_block_cms_footer_column')) {
			return $this->ajaxProcessUpdatePositionsColumn();
		} elseif (Tools::isSubmit('now_block_cms_footer')) {
			return $this->ajaxProcessUpdatePositionsLink();
		}
	}

	/**
	 * Manage position in ajax
	 */
	public function ajaxProcessUpdatePositionsColumn() {

		$way							= (int)(Tools::getValue('way'));
		$id_now_block_cms_footer_column	= (int)(Tools::getValue('id'));
		$positions						= Tools::getValue('now_block_cms_footer_column');

		foreach ($positions as $position => $value)
		{
			$pos = explode('_', $value);

			if (isset($pos[2]) && (int)$pos[2] === $id_now_block_cms_footer_column) {
				if ($oNowBlockFooterCmsColumn = new NowBlockFooterCmsColumn((int)$pos[2])) {
					if (isset($position) && $oNowBlockFooterCmsColumn->updatePosition($way, $position)) {
						echo 'ok position '.(int)$position.' for bloc footer column '.(int)$pos[1].'\r\n';
					} else {
						echo '{"hasError" : true, "errors" : "Can not update bloc footer column '.(int)$id_now_block_cms_footer_column.' to position '.(int)$position.' "}';
					}
				} else {
					echo '{"hasError" : true, "errors" : "This bloc footer column ('.(int)$id_now_block_cms_footer_column.') can t be loaded"}';
				}

				break;
			}
		}
	}

	/**
	 * Manage position in ajax
	 */
	public function ajaxProcessUpdatePositionsLink() {

		$way							= (int)(Tools::getValue('way'));
		$id_now_block_cms_footer		= (int)(Tools::getValue('id'));
		$positions						= Tools::getValue('now_block_cms_footer');

		foreach ($positions as $position => $value)
		{
			$pos = explode('_', $value);

			if (isset($pos[2]) && (int)$pos[2] === $id_now_block_cms_footer) {
				if ($oNowBlockFooterCms = new NowBlockFooterCms((int)$pos[2])) {
					if (isset($position) && $oNowBlockFooterCms->updatePosition($way, $position)) {
						echo 'ok position '.(int)$position.' for link '.(int)$pos[1].'\r\n';
					} else {
						echo '{"hasError" : true, "errors" : "Can not update link '.(int)$id_now_block_cms_footer.' to position '.(int)$position.' "}';
					}
				} else {
					echo '{"hasError" : true, "errors" : "This link ('.(int)$id_now_block_cms_footer.') can t be loaded"}';
				}

				break;
			}
		}
	}

	public function processSave() {

		if (Tools::getIsset('addnow_block_cms_footer') || Tools::getIsset('updatenow_block_cms_footer') || Tools::getIsset('submitAddnow_block_cms_footer')) {
			$this->redirect_after = self::$currentIndex . '&id_now_block_cms_footer_column=' . Tools::getValue('id_now_block_cms_footer_column', $this->object->id_now_block_cms_footer_column) . '&detailsnow_block_cms_footer_column&token=' . $this->token;
		}

		return parent::processSave();
	}
}