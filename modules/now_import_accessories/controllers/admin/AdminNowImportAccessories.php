<?php
/*
 * 2013
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

include(_PS_MODULE_DIR_ . 'now_import_accessories/now_import_accessories.php');
include(_PS_MODULE_DIR_ . 'now_import_accessories/classes/CSV.php');
include(_PS_MODULE_DIR_ . 'now_import_accessories/classes/Product.php');

class AdminNowImportAccessoriesController extends ModuleAdminControllerCore
{
	public $module;
	public $sFilePath;
	public $sUploadDirectory;
	public $aFile = array();
	public $oCSV;
	public $iStep = 0;
	public $aColumns = array();

	public function __construct()
	{
        $this->bootstrap = true;
		$this->module = new now_import_accessories();

		$aDelimiter = array();
		foreach (NowCSV::$aDelimiter as $value => $name) {
			$aDelimiter[] = array('value' => $value, 'name' => $name);
		}

		parent::__construct();

		if (!$this->module->active) {
			$this->errors[] = sprintf($this->module->l('You must active this module : %s', 'AdminNowImportAccessories'), $this->module->name);
			return;
		}

		$this->sUploadDirectory = $this->module->module_dir.'uploads/';

		$this->fields_options = array(
			'import_stock' => array(
				'title' =>	$this->module->l('Manage settings of import stock', 'AdminNowImportAccessories'),
				'fields' =>	array(
					'NOW_IMPORT_ACCES_FILE' => array(
						'title' => $this->module->l('Choose your file type', 'AdminNowImportAccessories'),
						'type' => 'select',
						'identifier' => 'value',
						'list' => array(
							array('value' => '.csv', 'name' => $this->module->l('CSV file (.csv)', 'AdminNowImportAccessories')),
							array('value' => '.txt', 'name' => $this->module->l('TEXT file (.txt)', 'AdminNowImportAccessories'))
						),
						'visibility' => Shop::CONTEXT_ALL
					),
					'NOW_IMPORT_ACCES_SEPARATOR' => array(
						'title' => $this->module->l('Choose your separator', 'AdminNowImportAccessories'),
						'type' => 'select',
						'identifier' => 'value',
						'list' => array(
							array('value' => ';', 'name' => ';'),
							array('value' => ',', 'name' => ','),
							array('value' => '|', 'name' => '|'),
							array('value' => '^', 'name' => '^')
						),
						'visibility' => Shop::CONTEXT_ALL
					),
					'NOW_IMPORT_ACCES_DELIMITER' => array(
						'title' => $this->module->l('Choose your text delimiter', 'AdminNowImportAccessories'),
						'type' => 'select',
						'identifier' => 'value',
						'list' => $aDelimiter,
						'visibility' => Shop::CONTEXT_ALL
					),
					'NOW_IMPORT_ACCES_PAGINATION' => array(
						'title' => $this->module->l('Pagination', 'AdminNowImportAccessories'),
						'type' => 'select',
						'identifier' => 'value',
						'list' => array(
							array('value' => '10', 'name' => '10'),
							array('value' => '25', 'name' => '25'),
							array('value' => '50', 'name' => '50'),
							array('value' => '100', 'name' => '100'),
						),
						'visibility' => Shop::CONTEXT_ALL
					),
				),
				'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => $this->l('Save'),
                )
			)
		);

		$this->setCurrentStep();
		if ($this->iStep > 1) {
			$sTypeFile          = Configuration::get('NOW_IMPORT_ACCES_FILE');
			$sSeparator         = Configuration::get('NOW_IMPORT_ACCES_SEPARATOR');
			$sDelimiter         = Configuration::get('NOW_IMPORT_ACCES_DELIMITER') == 1 ? '\'' : '"';
			$sDecimalDelimiter  = Configuration::get('NOW_IMPORT_ACCES_DECIMAL');
			$bConvertFileToUTF8 = Configuration::get('NOW_IMPORT_ACCES_CONVERT_UTF8');
			$this->oCSV         = new NowCSV($this->aFile, $sTypeFile, $sSeparator, $sDelimiter, $sDecimalDelimiter, $bConvertFileToUTF8);
		}
	}

	public function processFirstStep() {

		$tpl = $this->createTemplate('step-1.tpl');

		$aParams = array(
			'current_step'  => $this->iStep,
			'pagination'    => Configuration::get('NOW_IMPORT_ACCES_PAGINATION'),
		);
		$aParams = $this->setTemplateParams($aParams);

		$tpl->assign($aParams);

		return $tpl->fetch();
	}

	public function processSecondStep() {

		$tpl = $this->createTemplate('step-2.tpl');

		$this->oCSV->readData();
		$this->oCSV->copyFile($this->sUploadDirectory);
		$this->errors = $this->oCSV->aErrors;

		$aParams = array(
			'current_step'  => $this->iStep,
			'file_path'     => $this->sUploadDirectory.$this->oCSV->sNewFilename,
			'aDatas'        => $this->oCSV->aData,
			'aColumns'      => $this->getColumns(),
			'pagination'    => Configuration::get('NOW_IMPORT_ACCES_PAGINATION'),
		);
		$aParams = $this->setTemplateParams($aParams);

		$tpl->assign($aParams);

		return $tpl->fetch();
	}

	public function processThirdStep() {
		$aColumns           = Tools::getValue('columns', array());
		$aLines             = Tools::getValue('lines', array());
		$sFilePath          = Tools::getValue('file_path');

		$sTypeFile          = Configuration::get('NOW_IMPORT_ACCES_FILE');
		$sSeparator         = Configuration::get('NOW_IMPORT_ACCES_SEPARATOR');
		$sDelimiter         = Configuration::get('NOW_IMPORT_ACCES_DELIMITER') == 1 ? '\'' : '"';
		$sDecimalDelimiter  = Configuration::get('NOW_IMPORT_ACCES_DECIMAL');
		$bConvertFileToUTF8 = Configuration::get('NOW_IMPORT_ACCES_CONVERT_UTF8');

		$this->oCSV             = new NowCSV(array(), $sTypeFile, $sSeparator, $sDelimiter, $sDecimalDelimiter, $bConvertFileToUTF8);
		$this->oCSV->sFilename  = $sFilePath;
		$bCheckFile             = (bool)$this->oCSV->checkFile();
		$this->errors           = $this->oCSV->aErrors;

		if ($bCheckFile && $this->iStep == 3) {
			// Read file data
			$this->oCSV->readData();

			// Remove lines selected
			if (!empty($aLines))
				$this->oCSV->removeLinesInData($aLines);

			// Remove columns where name is "ignore_columns"
			if (!empty($aColumns))
				$this->oCSV->removeColumnsInData($aColumns);

			if (!in_array('product_reference', $aColumns) || !in_array('current_stock', $aColumns) || !in_array('warehouse', $aColumns)) {
				$this->errors[] = $this->module->l('You must defined this columns before to go in the step 3: Product reference, Current stock, Warehouse reference.', 'AdminNowImportAccessories');
			} else {

				$aNewData = array(0 => array(
					'id_product'            => $this->module->l('Product ID', 'AdminNowImportAccessories'),
					'id_product_attribute'  => $this->module->l('Product Attribute ID', 'AdminNowImportAccessories'),
					'product_name'          => $this->module->l('Product Name', 'AdminNowImportAccessories'),
					'product_reference'     => $this->module->l('Product reference', 'AdminNowImportAccessories'),
					'id_warehouse'          => $this->module->l('Warehouse ID', 'AdminNowImportAccessories'),
					'warehouse'             => $this->module->l('Warehouse reference', 'AdminNowImportAccessories'),
					'name_warehouse'        => $this->module->l('Warehouse Name', 'AdminNowImportAccessories'),
					'current_stock'         => $this->module->l('Current stock', 'AdminNowImportAccessories'),
					'quantity_physical'     => $this->module->l('Physical Quantity', 'AdminNowImportAccessories'),
					'error'                 => $this->module->l('Errors', 'AdminNowImportAccessories'),
				));

				$this->calculateStock($this->oCSV->aData, $aNewData);
			}

			$tpl = $this->createTemplate('step-3.tpl');

			$aParams = array(
				'current_step'  => $this->iStep,
				'file_path'     => $sFilePath,
				'file_name'     => substr($sFilePath, strrpos($sFilePath, '/') + 1),
				'aDatas'        => $aNewData,
				'aColumns'      => $aColumns,
				'aLines'        => $aLines,
				'pagination'    => Configuration::get('NOW_IMPORT_ACCES_PAGINATION'),
			);
			$aParams = $this->setTemplateParams($aParams);

			$tpl->assign($aParams);

			return $tpl->fetch();
		} else {
			return $this->processFirstStep();
		}
	}

	public function processFourthStep() {
		$aColumns           = Tools::getValue('aColumns', array());
		$aLines             = Tools::getValue('aLines', array());
		$sFilePath          = Tools::getValue('file_path');

		$sTypeFile          = Configuration::get('NOW_IMPORT_ACCES_FILE');
		$sSeparator         = Configuration::get('NOW_IMPORT_ACCES_SEPARATOR');
		$sDelimiter         = Configuration::get('NOW_IMPORT_ACCES_DELIMITER') == 1 ? '\'' : '"';
		$sDecimalDelimiter  = Configuration::get('NOW_IMPORT_ACCES_DECIMAL');
		$bConvertFileToUTF8 = Configuration::get('NOW_IMPORT_ACCES_CONVERT_UTF8');

		$this->oCSV             = new NowCSV(array(), $sTypeFile, $sSeparator, $sDelimiter, $sDecimalDelimiter, $bConvertFileToUTF8);
		$this->oCSV->sFilename  = $sFilePath;
		$bCheckFile             = (bool)$this->oCSV->checkFile();
		$this->errors           = $this->oCSV->aErrors;

		if ($bCheckFile && $this->iStep == 4) {
			// Read file data
			$this->oCSV->readData();

			// Remove lines selected
			if (!empty($aLines))
				$this->oCSV->removeLinesInData($aLines);

			// Remove columns where name is "ignore_columns"
			if (!empty($aColumns))
				$this->oCSV->removeColumnsInData($aColumns);

			$aDataToImported = array();
			$aDataImported = array(0 => array(
				'product_reference'     => $this->module->l('Product reference', 'AdminNowImportAccessories'),
				'product_name'          => $this->module->l('Product Name', 'AdminNowImportAccessories'),
				'name_warehouse'        => $this->module->l('Warehouse Name', 'AdminNowImportAccessories'),
				'action_type'           => $this->module->l('Sign', 'AdminNowImportAccessories'),
				'current_stock'         => $this->module->l('Current stock', 'AdminNowImportAccessories'),
				'action'                => $this->module->l('Label', 'AdminNowImportAccessories'),
			));

			$this->calculateStock($this->oCSV->aData, $aDataToImported);
			$this->importStock($aDataToImported, $aDataImported);

			$tpl = $this->createTemplate('step-4.tpl');

			$aParams = array(
				'current_step'  => $this->iStep,
				'file_path'     => $this->sUploadDirectory.$this->oCSV->sNewFilename,
				'file_name'     => substr($sFilePath, strrpos($sFilePath, '/') + 1),
				'aDatas'        => $aDataImported,
				'pagination'    => Configuration::get('NOW_IMPORT_ACCES_PAGINATION'),
			);
			$aParams = $this->setTemplateParams($aParams);

			$tpl->assign($aParams);

			return $tpl->fetch();
		} else {
			return $this->processFirstStep();
		}
	}

	public function calculateStock($aDatas, &$aNewData) {
		foreach ($aDatas as $aData) {
			$aData['error'] = "";
			if (empty($aData['product_reference'])) {
				$aData['error'] = $this->module->l('Product reference is empty.', 'AdminNowImportAccessories');
			}

			if (!isset($aData['warehouse']) || empty($aData['warehouse'])) {
				$aData['error'] = $this->module->l('Warehouse is empty or doesn\'t exist.', 'AdminNowImportAccessories');
			}

			// Get id_product and id_product_attribute
			$aIds = NowProduct::getIdProductAndAttributeByReference($aData['product_reference']);

			if (empty($aIds) || !$aIds || !isset($aIds['id_product'])) {
				$aData['error'] = sprintf($this->module->l('Product reference doesn\'t exist: "%s".', 'AdminNowImportAccessories'), $aData['product_reference']);
			}

			// Get Wharehouse id
			if (is_numeric($aData['warehouse'])) {
				$iIdWarehouse = $aData['warehouse'];
			} else {
				$iIdWarehouse = NowWarehouse::getWarehouseIdByReference($aData['warehouse']);
			}

			$sWarehouseName = Warehouse::getWarehouseNameById((int)$iIdWarehouse);

			if (!$sWarehouseName || empty($sWarehouseName)) {
				$aData['error'] = sprintf($this->module->l('Warehouse doesn\'t exist: "%s".', 'AdminNowImportAccessories'), $aData['warehouse']);
			}

			$aData['id_product']            = (int)$aIds['id_product'];
			$aData['id_product_attribute']  = (int)$aIds['id_product_attribute'];
			$aData['name_warehouse']       = $sWarehouseName;
			$aData['id_warehouse']         = (int)$iIdWarehouse;

			// Stock manager initialisation
			$oSM  = new StockManager();

			// Get physical quantity
			$iQuantityPhysical = (int)$oSM->getProductPhysicalQuantities((int)$aData['id_product'], (int)$aData['id_product_attribute'], (int)$iIdWarehouse, false);
			$aData['quantity_physical'] = (int)$iQuantityPhysical;

			$aNewData[] = array(
				'id_product'            => $aData['id_product'],
				'id_product_attribute'  => $aData['id_product_attribute'],
				'product_name'          => Product::getProductName((int)$aData['id_product'], (int)$aData['id_product_attribute'], (int)$this->context->language->id),
				'product_reference'     => $aData['product_reference'],
				'id_warehouse'          => $aData['id_warehouse'],
				'warehouse'             => $aData['warehouse'],
				'name_warehouse'        => $aData['name_warehouse'],
				'current_stock'         => $aData['current_stock'],
				'quantity_physical'     => $aData['quantity_physical'],
				'error'                 => $aData['error'],
			);
		}
	}

	public function importStock($aDataToImported, &$aDataImported) {

		$oSM = new StockManager();
		$aWarehouseCache = array();

		foreach ($aDataToImported as $aData) {

			if (!isset($aWarehouseCache[(int)$aData['id_warehouse']])) {
				$aWarehouseCache[(int)$aData['id_warehouse']] = new Warehouse($aData['id_warehouse']);
			}

			$oWarehouse = $aWarehouseCache[(int)$aData['id_warehouse']];

			if (!Validate::isLoadedObject($oWarehouse)) {
				if (!isset($this->errors['warehouse_object']))
					$this->errors['warehouse_object'] = sprintf($this->module->l('Warehouse doesn\'t exists (Name: %1$s, Reference: %2$s, ID: %3$d)', 'AdminNowImportAccessories'), $aData['name_warehouse'], $aData['warehouse'], $aData['id_warehouse']);
				continue;
			}

			if (empty($aData['error'])) {

				if ($aData['current_stock'] == $aData['quantity_physical']) {
					// Stock identical : no action
					$aData['action'] = $this->module->l('Identical stock', 'AdminNowImportAccessories');
					$aData['action_type'] = 'equal';
				} elseif ($aData['current_stock'] > $aData['quantity_physical']) {
					// Increase the available stock
					$iQuantity = (int)$aData['current_stock'] - (int)$aData['quantity_physical'];
					$fPriceTE = NowProduct::getWholesalePrice((int)$aData['id_product'], (int)$aData['id_product_attribute']);

					if (!$oSM->addProduct((int)$aData['id_product'], (int)$aData['id_product_attribute'], $oWarehouse, (int)$iQuantity, (int)Configuration::get('NOW_IMPORT_INCREASE_STOCK'), $fPriceTE, true)) {
						$this->errors[] = sprintf($this->module->l('Impossible to increase the stock of the following product: %1$s (Reference: %2$s)', 'AdminNowImportAccessories'), $aData['product_name'], $aData['product_reference']);
						continue;
					}

					$aData['action'] = $this->module->l('Increase the available stock', 'AdminNowImportAccessories');
					$aData['action_type'] = 'increase';
				} elseif ($aData['current_stock'] < $aData['quantity_physical']) {
					// Decrease the stock available
					$iQuantity = (int)$aData['quantity_physical'] - (int)$aData['current_stock'];

					if (!$oSM->removeProduct((int)$aData['id_product'], (int)$aData['id_product_attribute'], $oWarehouse, (int)$iQuantity, (int)Configuration::get('NOW_IMPORT_DECREASE_STOCK'), true)) {
						$this->errors[] = sprintf($this->module->l('Impossible to decrease the stock of the following product: %1$s (Reference: %2$s)', 'AdminNowImportAccessories'), $aData['product_name'], $aData['product_reference']);
						continue;
					}

					$aData['action'] = $this->module->l('Decrease the stock available', 'AdminNowImportAccessories');
					$aData['action_type'] = 'decrease';
				}

				$aDataImported[] = array(
					'product_reference'     => $aData['product_reference'],
					'product_name'          => Product::getProductName((int)$aData['id_product'], (int)$aData['id_product_attribute'], (int)$this->context->language->id),
					'name_warehouse'        => $aData['name_warehouse'],
					'action_type'           => $aData['action_type'],
					'current_stock'         => $aData['current_stock'],
					'action'                => $aData['action'],
				);
			} else {
				$this->errors[] = $aData['error'];
			}

		}

	}

	public function getColumns() {
		$this->setColumns();
		return $this->aColumns;
	}

	public function setColumns() {

		$this->aColumns = array(
			'ignore_column'         => $this->module->l('Ignore this column', 'AdminNowImportAccessories'),
			'product_reference'     => $this->module->l('Product reference', 'AdminNowImportAccessories'),
			'current_stock'         => $this->module->l('Current stock', 'AdminNowImportAccessories'),
			'warehouse'             => $this->module->l('Warehouse reference', 'AdminNowImportAccessories'),
		);
	}

	/**
	 * Assign smarty variables for all default views, list and form, then call other init functions
	 * Override AdminController::initContent()
	 */
	public function initContent()
	{
		$this->initToolbar();
		$this->initToolbarTitle();

		// Delete button "Save" AND "new" for settings
		unset($this->toolbar_btn['save']);
		unset($this->toolbar_btn['new']);

		if ($this->iStep > 1) {

			if ($sFilename = Tools::getValue('file_name')) {
				$this->oCSV->sNewFilename = $sFilename;
				$this->oCSV->sFilename = $this->sUploadDirectory.$sFilename;
			}

			$bCheckFile     = (bool)$this->oCSV->checkFile();
			$this->errors   = $this->oCSV->aErrors;

			if ($bCheckFile && $this->iStep == 2) {
				$this->content .= $this->processSecondStep();
			} elseif($this->iStep == 3) {
				$this->content .= $this->processThirdStep();
			} elseif($this->iStep == 4) {
				$this->content .= $this->processFourthStep();
			} else {
				$this->content .= $this->processFirstStep();
			}
		} else {
			$this->content .= $this->processFirstStep();
		}

		// Generate HTML popin
		$this->content .= $this->displayPopinImportFile();
		$this->content .= $this->displayPopinSettings();

		$this->context->smarty->assign(array(
			'content' => $this->content,
			'url_post' => self::$currentIndex.'&token='.$this->token,
		));
	}

	/**
	 * Check if we can to import file
	 *
	 * @return bool
	 */
	public function setCurrentStep() {
		$this->iStep = 1;

        if (array_key_exists('file', $_FILES))
		    $this->aFile = $_FILES['file'];

		if ((Tools::isSubmit('submitFileUpload') && isset($this->aFile['name'])) || (Tools::getValue('step') == 2 && Tools::getValue('file_name'))) {
			$this->iStep = 2;
		} elseif (Tools::getValue('action') == '_third_Step') {
			$this->iStep = 3;
		} elseif (Tools::getValue('action') == '_fourth_Step') {
			$this->iStep = 4;
		}
	}

	/**
	 * Override AdminController::initToolbar() method for add bouton "Import File" by stock file
	 */
	public function initToolbar() {
		$this->toolbar_btn['edit'] = array(
			'href' => '#display_popin_settings',
			'desc' => $this->module->l('Settings', 'AdminNowImportAccessories')
		);

		if ($this->iStep == 1) {
			$this->toolbar_btn['import'] = array(
				'href' => '#display_popin_import_file',
				'desc' => $this->module->l('Import File', 'AdminNowImportAccessories')
			);
		} elseif ($this->iStep == 2) {
			$this->toolbar_btn['analyse-data'] = array(
				'href' => '#',
				'desc' => $this->module->l('Analyse Data', 'AdminNowImportAccessories')
			);
			$this->toolbar_btn['cancel'] = array(
				'href' => $this->context->link->getAdminLink('AdminNowImportAccessories', true),
				'desc' => $this->module->l('Cancel', 'AdminNowImportAccessories')
			);
			unset($this->toolbar_btn['edit']);
		} elseif ($this->iStep == 3) {
			$this->toolbar_btn['save-data'] = array(
				'href' => '#',
				'desc' => $this->module->l('Import Data', 'AdminNowImportAccessories')
			);
			$sFilePath = Tools::getValue('file_path');
			$this->toolbar_btn['back'] = array(
				'href' => $this->context->link->getAdminLink('AdminNowImportAccessories', true).'&step=2&file_name='.substr($sFilePath, strrpos($sFilePath, '/') + 1),
				'desc' => $this->module->l('Return to step 2', 'AdminNowImportAccessories')
			);
			$this->toolbar_btn['cancel'] = array(
				'href' => $this->context->link->getAdminLink('AdminNowImportAccessories', true),
				'desc' => $this->module->l('Cancel', 'AdminNowImportAccessories')
			);
			unset($this->toolbar_btn['edit']);
		} elseif ($this->iStep == 4) {
			$this->toolbar_btn['back'] = array(
				'href' => $this->context->link->getAdminLink('AdminNowImportAccessories', true),
				'desc' => $this->module->l('Return to step 1', 'AdminNowImportAccessories')
			);
			unset($this->toolbar_btn['edit']);
		}


		parent::initToolbar();
	}

	public function initToolbarForPopin() {

		unset($this->toolbar_btn['preview']);
		unset($this->toolbar_btn['import']);
		unset($this->toolbar_btn['edit']);

		$this->toolbar_title = '';
		$this->toolbar_scroll = false;
	}

	public function setTemplateParams($aParams)
	{
		$aParams['title']           = $this->toolbar_title;
		$aParams['toolbar_btn']     = $this->toolbar_btn;
		$aParams['show_toolbar']    = $this->show_toolbar;
		$aParams['toolbar_scroll']  = $this->toolbar_scroll;
		$aParams['module_path']     = $this->module->module_dir.'/views/templates/admin/'.$this->module->name.'/';

		return $aParams;
	}

	public function setMedia() {

		$this->addCSS(array(
			$this->module->module_uri.'css/bootstrap.min.css' => 'all',
			$this->module->module_uri.'css/DT_bootstrap.css' => 'all',
			$this->module->module_uri.'css/'.$this->module->name.'.css' => 'all',
		));

		parent::setMedia();

		$this->addJS(array(
			$this->module->module_uri.'js/jquery.dataTables.js',
			$this->module->module_uri.'js/'.$this->module->name.'.js',
		));

		$this->addjqueryPlugin('fancybox');
	}

	public function displayPopinImportFile() {

		$tpl = $this->createTemplate('popin_import.tpl');

		return $tpl->fetch();
	}

	public function displayPopinSettings() {

		$this->initToolbarForPopin();
		$tpl = $this->createTemplate('popin_settings.tpl');

		$tpl->assign('content', $this->renderOptions());

		return $tpl->fetch();
	}

	public function setFilePath() {
		// Get module path
		$sModuleDir = $this->module->module_dir;

		// Get upload folders
		$sDirectories = 'uploads'.DIRECTORY_SEPARATOR.date('Y').DIRECTORY_SEPARATOR.date('F').DIRECTORY_SEPARATOR;

		$this->sFilePath = $sModuleDir.$sDirectories.basename($this->aFile['name']);
	}

	public function renderOptions()
	{
		if ($this->fields_options && is_array($this->fields_options))
		{
			if (isset($this->display) && $this->display != 'options' && $this->display != 'list')
				$this->show_toolbar = false;
			else
				$this->display = 'options';

			//unset($this->toolbar_btn);
			$this->initToolbar();
			$this->initToolbarForPopin();

			$helper = new HelperOptions($this);
			$this->setHelperDisplay($helper);
			$helper->id = $this->id;
			$helper->tpl_vars = $this->tpl_option_vars;
			$options = $helper->generateOptions($this->fields_options);

			return $options;
		}
	}
}