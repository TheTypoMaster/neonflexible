<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

require_once (_PS_MODULE_DIR_ . 'now_move_blocklayered/now_move_blocklayered.php');

class AdminNowFeaturesController extends ModuleAdminController {
	protected $position_identifier = 'id_feature_value';

	public function __construct() {
		$this->bootstrap = true;
		$this->table = 'feature';
		$this->className = 'Feature';
		$this->module = new now_move_blocklayered();
		$this->lang = true;

		$this->fields_list = array(
			'id_feature' => array(
				'title' => $this->l('ID'),
				'align' => 'center',
				'class' => 'fixed-width-xs'
			),
			'name' => array(
				'title' => $this->l('Name'),
				'width' => 'auto',
				'filter_key' => 'b!name'
			),
			'value' => array(
				'title' => $this->l('Values'),
				'orderby' => false,
				'search' => false,
				'align' => 'center',
				'class' => 'fixed-width-xs'
			)
		);

		parent::__construct();
	}

	/**
	 * AdminController::renderList() override
	 * @see AdminController::renderList()
	 */
	public function renderList()
	{
		$this->addRowAction('view');

		return parent::renderList();
	}

	/**
	 * Change object type to feature value (use when processing a feature value)
	 */
	protected function setTypeValue()
	{
		$this->table = 'feature_value';
		$this->className = 'FeatureValue';
		$this->identifier = 'id_feature_value';
	}

	/**
	 * Change object type to feature (use when processing a feature)
	 */
	protected function setTypeFeature()
	{
		$this->table = 'feature';
		$this->className = 'Feature';
		$this->identifier = 'id_feature';
	}

	public function renderView()
	{
		if (($id = Tools::getValue('id_feature')))
		{
			$this->setTypeValue();
			$this->list_id = 'feature_value';
			$this->lang = true;

			if (!Validate::isLoadedObject($obj = new Feature((int)$id))) {
				$this->errors[] = Tools::displayError('An error occurred while updating the status for an object.').' <b>'.$this->table.'</b> '.Tools::displayError('(cannot load object)');
				return;
			}

			$this->feature_name = $obj->name;
			$this->toolbar_title = $this->feature_name[$this->context->employee->id_lang];
			$this->fields_list = array(
				'id_feature_value' => array(
					'title' => $this->l('ID'),
					'align' => 'center',
					'class' => 'fixed-width-xs'
				),
				'value' => array(
					'title' => $this->l('Value')
				),
				'position' => array(
					'title' => $this->l('Position'),
					'filter_key' => 'a!position',
					'align' => 'center',
					'class' => 'fixed-width-xs',
					'position' => 'position'
				)
			);

			$this->_where = sprintf('AND `id_feature` = %d', (int)$id);
			self::$currentIndex = self::$currentIndex.'&id_feature='.(int)$id.'&viewfeature';
			$this->processFilter();
			return parent::renderList();
		}
	}

	public function initProcess()
	{
		// Are we working on feature values?
		if (Tools::getValue('id_feature_value')
			|| Tools::isSubmit('deletefeature_value')
			|| Tools::isSubmit('submitAddfeature_value')
			|| Tools::isSubmit('addfeature_value')
			|| Tools::isSubmit('updatefeature_value')
			|| Tools::isSubmit('submitBulkdeletefeature_value')) {
			$this->setTypeValue();
		}

		if (Tools::getIsset('viewfeature')) {
			$this->list_id = 'feature_value';
			$this->_defaultOrderBy = 'position';
			$this->_defaultOrderWay = 'ASC';

			if (isset($_POST['submitReset'.$this->list_id])) {
				$this->processResetFilters();
			}
		}
		else
		{
			$this->list_id = 'feature';
			$this->_defaultOrderBy = 'position';
			$this->_defaultOrderWay = 'ASC';
		}

		parent::initProcess();

	}

	/**
	 * AdminController::initContent() override
	 * @see AdminController::initContent()
	 */
	public function initContent() {
		if (Feature::isFeatureActive()) {
			// toolbar (save, cancel, new, ..)
			$this->initTabModuleList();
			$this->initToolbar();
			$this->initPageHeaderToolbar();
			if ($this->display == 'edit' || $this->display == 'add') {
				if (!$this->loadObject(true)) {
					return;
				}
				$this->content .= $this->renderForm();
			} else if ($this->display == 'view') {
				// Some controllers use the view action without an object
				if ($this->className) {
					$this->loadObject(true);
				}
				$this->content .= $this->renderView();
			} else if (!$this->ajax) {
				// If a feature value was saved, we need to reset the values to display the list
				$this->setTypeFeature();
				$this->content .= $this->renderList();
			}
		} else {
			$url = '<a href="index.php?tab=AdminPerformance&token='.Tools::getAdminTokenLite('AdminPerformance').'#featuresDetachables">'.$this->l('Performance').'</a>';
			$this->displayWarning(sprintf($this->l('This feature has been disabled. You can activate it here: %s.'), $url));
		}

		$this->context->smarty->assign(array(
			'content' => $this->content,
			'url_post' => self::$currentIndex.'&token='.$this->token,
			'show_page_header_toolbar' => $this->show_page_header_toolbar,
			'page_header_toolbar_title' => $this->page_header_toolbar_title,
			'page_header_toolbar_btn' => $this->page_header_toolbar_btn
		));
	}

	/**
	 * AdminController::getList() override
	 * @see AdminController::getList()
	 */
	public function getList($id_lang, $order_by = null, $order_way = null, $start = 0, $limit = false, $id_lang_shop = false)
	{
		if ($this->table == 'feature_value')
			$this->_where .= ' AND (a.custom = 0 OR a.custom IS NULL)';

		parent::getList($id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop);

		if ($this->table == 'feature')
		{
			$nb_items = count($this->_list);
			for ($i = 0; $i < $nb_items; ++$i)
			{
				$item = &$this->_list[$i];

				$query = new DbQuery();
				$query->select('COUNT(fv.id_feature_value) as count_values');
				$query->from('feature_value', 'fv');
				$query->where('fv.id_feature ='.(int)$item['id_feature']);
				$query->where('(fv.custom=0 OR fv.custom IS NULL)');
				$res = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
				$item['value'] = (int)$res;
				unset($query);
			}
		}
	}

	public function ajaxProcessUpdatePositions() {
		if ($this->tabAccess['edit'] === '1') {
			$way = (int)Tools::getValue('way');
			$id_feature_value = (int)Tools::getValue('id');
			$positions = Tools::getValue('feature_value');

			$new_positions = array();
			foreach ($positions as $k => $v) {
				if (!empty($v)) {
					$new_positions[] = $v;
				}
			}

			foreach ($new_positions as $position => $value) {
				$pos = explode('_', $value);

				if (isset($pos[2]) && (int)$pos[2] === $id_feature_value) {
					if ($feature = new FeatureValue((int)$pos[2])) {
						if (isset($position) && $feature->updatePosition($way, $position, $id_feature_value)) {
							echo 'ok position '.(int)$position.' for feature value '.(int)$pos[1].'\r\n';
						} else {
							echo '{"hasError" : true, "errors" : "Can not update feature value ' . (int)$id_feature_value . ' to position ' . (int)$position . ' "}';
						}
					} else {
						echo '{"hasError" : true, "errors" : "This feature value (' . (int)$id_feature_value . ') can t be loaded"}';
					}

					break;
				}
			}
		}
	}

}