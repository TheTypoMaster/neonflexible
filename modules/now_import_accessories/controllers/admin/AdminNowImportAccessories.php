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
			'import_accessories' => array(
				'title' =>	$this->module->l('Manage settings of import accessories', 'AdminNowImportAccessories'),
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
			$sTypeFile			= Configuration::get('NOW_IMPORT_ACCES_FILE');
			$sSeparator			= Configuration::get('NOW_IMPORT_ACCES_SEPARATOR');
			$sDelimiter			= Configuration::get('NOW_IMPORT_ACCES_DELIMITER') == 1 ? '\'' : '"';
			$sDecimalDelimiter	= Configuration::get('NOW_IMPORT_ACCES_DECIMAL');
			$bConvertFileToUTF8	= Configuration::get('NOW_IMPORT_ACCES_CONVERT_UTF8');
			$this->oCSV			= new NowCSV($this->aFile, $sTypeFile, $sSeparator, $sDelimiter, $sDecimalDelimiter, $bConvertFileToUTF8);
		}
	}

	public function processFirstStep() {

		$tpl = $this->createTemplate('step-1.tpl');

		$aParams = array(
			'current_step'	=> $this->iStep,
			'pagination'	=> Configuration::get('NOW_IMPORT_ACCES_PAGINATION'),
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
			'current_step'	=> $this->iStep,
			'file_path'		=> $this->sUploadDirectory.$this->oCSV->sNewFilename,
			'aDatas'		=> $this->oCSV->aData,
			'aColumns'		=> $this->getColumns(),
			'pagination'	=> Configuration::get('NOW_IMPORT_ACCES_PAGINATION'),
		);
		$aParams = $this->setTemplateParams($aParams);

		$tpl->assign($aParams);

		return $tpl->fetch();
	}

	public function processThirdStep() {
		$aColumns			= Tools::getValue('columns', array());
		$aLines				= Tools::getValue('lines', array());
		$sFilePath			= Tools::getValue('file_path');

		$sTypeFile			= Configuration::get('NOW_IMPORT_ACCES_FILE');
		$sSeparator			= Configuration::get('NOW_IMPORT_ACCES_SEPARATOR');
		$sDelimiter			= Configuration::get('NOW_IMPORT_ACCES_DELIMITER') == 1 ? '\'' : '"';
		$sDecimalDelimiter	= Configuration::get('NOW_IMPORT_ACCES_DECIMAL');
		$bConvertFileToUTF8	= Configuration::get('NOW_IMPORT_ACCES_CONVERT_UTF8');

		$this->oCSV				= new NowCSV(array(), $sTypeFile, $sSeparator, $sDelimiter, $sDecimalDelimiter, $bConvertFileToUTF8);
		$this->oCSV->sFilename	= $sFilePath;
		$bCheckFile				= (bool)$this->oCSV->checkFile();
		$this->errors			= $this->oCSV->aErrors;

		if ($bCheckFile && $this->iStep == 3) {
			// Read file data
			$this->oCSV->readData();

			// Remove lines selected
			if (!empty($aLines))
				$this->oCSV->removeLinesInData($aLines);

			// Remove columns where name is "ignore_columns"
			if (!empty($aColumns))
				$this->oCSV->removeColumnsInData($aColumns);

			if (!(in_array('product_reference', $aColumns) || in_array('product_id', $aColumns)) || !in_array('accessories', $aColumns)) {
				$this->errors[] = $this->module->l('You must defined this columns before to go in the step 3: Product reference or Product ID and Accessories (Separated by "::").', 'AdminNowImportAccessories');
			} else {

				$aNewData = array(0 => array(
					'id_product'			=> $this->module->l('Product ID', 'AdminNowImportAccessories'),
					'product_reference'		=> $this->module->l('Product reference', 'AdminNowImportAccessories'),
					'product_name'			=> $this->module->l('Product Name', 'AdminNowImportAccessories'),
					'accessories'			=> $this->module->l('Accessories', 'AdminNowImportAccessories'),
					'new_accessories'		=> $this->module->l('New accessories', 'AdminNowImportAccessories'),
					'old_accessories'		=> $this->module->l('Old accessories', 'AdminNowImportAccessories'),
					'error'					=> $this->module->l('Errors', 'AdminNowImportAccessories'),
				));

				$this->checkAccessories($this->oCSV->aData, $aNewData);
			}

			$tpl = $this->createTemplate('step-3.tpl');

			$aParams = array(
				'current_step'	=> $this->iStep,
				'file_path'		=> $sFilePath,
				'file_name'		=> substr($sFilePath, strrpos($sFilePath, '/') + 1),
				'aDatas'		=> $aNewData,
				'aColumns'		=> $aColumns,
				'aLines'		=> $aLines,
				'pagination'	=> Configuration::get('NOW_IMPORT_ACCES_PAGINATION'),
			);
			$aParams = $this->setTemplateParams($aParams);

			$tpl->assign($aParams);

			return $tpl->fetch();
		} else {
			return $this->processFirstStep();
		}
	}

	public function processFourthStep() {
		$aColumns			= Tools::getValue('aColumns', array());
		$aLines				= Tools::getValue('aLines', array());
		$sFilePath			= Tools::getValue('file_path');

		$sTypeFile			= Configuration::get('NOW_IMPORT_ACCES_FILE');
		$sSeparator			= Configuration::get('NOW_IMPORT_ACCES_SEPARATOR');
		$sDelimiter			= Configuration::get('NOW_IMPORT_ACCES_DELIMITER') == 1 ? '\'' : '"';
		$sDecimalDelimiter	= Configuration::get('NOW_IMPORT_ACCES_DECIMAL');
		$bConvertFileToUTF8	= Configuration::get('NOW_IMPORT_ACCES_CONVERT_UTF8');

		$this->oCSV				= new NowCSV(array(), $sTypeFile, $sSeparator, $sDelimiter, $sDecimalDelimiter, $bConvertFileToUTF8);
		$this->oCSV->sFilename	= $sFilePath;
		$bCheckFile				= (bool)$this->oCSV->checkFile();
		$this->errors			= $this->oCSV->aErrors;

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
				'id_product'			=> $this->module->l('Product ID', 'AdminNowImportAccessories'),
				'product_reference'		=> $this->module->l('Product reference', 'AdminNowImportAccessories'),
				'product_name'			=> $this->module->l('Product Name', 'AdminNowImportAccessories'),
				'accessories'			=> $this->module->l('Accessories', 'AdminNowImportAccessories'),
				'error'					=> $this->module->l('Errors', 'AdminNowImportAccessories'),
			));

			$this->checkAccessories($this->oCSV->aData, $aDataToImported);
			$this->importAccessories($aDataToImported, $aDataImported);

			$tpl = $this->createTemplate('step-4.tpl');

			$aParams = array(
				'current_step'	=> $this->iStep,
				'file_path'		=> $this->sUploadDirectory.$this->oCSV->sNewFilename,
				'file_name'		=> substr($sFilePath, strrpos($sFilePath, '/') + 1),
				'aDatas'		=> $aDataImported,
				'pagination'	=> Configuration::get('NOW_IMPORT_ACCES_PAGINATION'),
			);
			$aParams = $this->setTemplateParams($aParams);

			$tpl->assign($aParams);

			return $tpl->fetch();
		} else {
			return $this->processFirstStep();
		}
	}

	public function checkAccessories($aDatas, &$aNewData) {
		foreach ($aDatas as $aData) {
			// Initialisation
			$aData['error'] = "";

			if (!array_key_exists('product_reference', $aData))
				$aData['product_reference'] = '';

			if (!array_key_exists('product_id', $aData))
				$aData['product_id'] = '';

			if (empty($aData['product_reference']) && empty($aData['product_id'])) {
				$aData['error'] = $this->module->l('Product reference and product ID is empty.', 'AdminNowImportAccessories');
			}

			// On vérifie l'Id produit si il existe
			if (empty($aData['product_id']) || !NowProduct::isRealProduct($aData['product_id'])) {
				// Get product by product reference
				if (empty($aData['product_reference']) || !$aData['product_id'] = NowProduct::getIdProductByProductReference($aData['product_reference'])) {
					$aData['error'] = sprintf($this->module->l('Product reference doesn\'t exist: "%s".', 'AdminNowImportAccessories'), $aData['product_reference']);
				}
			}

			// On récuprère les informations du produit
			$aProduct = NowProduct::getProductLight($aData['product_id']);

			$aNewData[] = array(
				'id_product'			=> $aProduct['id_product'],
				'product_name'			=> $aProduct['name'],
				'product_reference'		=> $aProduct['reference'],
				'accessories'			=> $aData['accessories'],
				'new_accessories'		=> $this->getNewAccessories($aData['accessories']),
				'old_accessories'		=> $this->getOldAccessories($aData['product_id']),
				'error'					=> $aData['error'],
			);
		}
	}

	public function getNewAccessories($sAccessories) {
		if ($sAccessories == '')
			return '';
		$aAccessories = NowProduct::getProductsLight(explode('::', $sAccessories));

		$sAccessories = '<ul>';
		foreach ($aAccessories as $aAccessory) {
			$sAccessories .= '<li>'.sprintf($this->module->l('%1$s (Id product: %2$s, Reference: %3$s)', 'AdminNowImportAccessories'), $aAccessory['name'], $aAccessory['id_product'], $aAccessory['reference']).'</li>';
		}
		$sAccessories .= '</ul>';

		return $sAccessories;
	}

	public function getOldAccessories($iIdProduct) {
		$aAccessories = Product::getAccessoriesLight($this->context->language->id, $iIdProduct);

		$sAccessories = '<ul>';
		foreach ($aAccessories as $aAccessory) {
			$sAccessories .= '<li>'.sprintf($this->module->l('%1$s (Id product: %2$s, Reference: %3$s)', 'AdminNowImportAccessories'), $aAccessory['name'], $aAccessory['id_product'], $aAccessory['reference']).'</li>';
		}
		$sAccessories .= '</ul>';

		return $sAccessories;
	}

	public function importAccessories($aDataToImported, &$aDataImported) {

		foreach ($aDataToImported as $aData) {

			if (empty($aData['error'])) {

				// get product information
				$aProduct = NowProduct::getProductLight($aData['id_product']);

				// Delete old accessories
				if (!NowProduct::deleteAccessories($aData['id_product'])) {
					$aData['error'] = sprintf($this->module->l('Impossible to deleting old accesories to this product: %s', 'AdminNowImportAccessories'), $aProduct['reference']);
				}

				// Insert new accesssories
				if (!NowProduct::changeAccessories($aData['id_product'], explode('::', $aData['accessories']))) {
					$aData['error'] = sprintf($this->module->l('Impossible to adding the new accesories to this product: %s', 'AdminNowImportAccessories'), $aProduct['reference']);
				}

				$aDataImported[] = array(
					'id_product'			=> $aProduct['id_product'],
					'product_reference'		=> $aProduct['reference'],
					'product_name'			=> $aProduct['name'],
					'accessories'			=> $this->getNewAccessories($aData['accessories']),
					'error'					=> $aData['error']
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
			'ignore_column'			=> $this->module->l('Ignore this column', 'AdminNowImportAccessories'),
			'product_reference'		=> $this->module->l('Product reference', 'AdminNowImportAccessories'),
			'product_id'			=> $this->module->l('Product ID', 'AdminNowImportAccessories'),
			'accessories'			=> $this->module->l('Accessories (Separated by "::")', 'AdminNowImportAccessories'),
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

			$bCheckFile		= (bool)$this->oCSV->checkFile();
			$this->errors	= $this->oCSV->aErrors;

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

		if (array_key_exists('file', $_FILES)) {
			$this->aFile = $_FILES['file'];
		}

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
		$this->toolbar_btn['cloud-download icon-cloud-download'] = array(
			'href' => $this->context->link->getModuleLink('now_import_accessories', 'readthedocumendation'),
			'desc' => $this->module->l('Exemple', 'AdminNowImportAccessories')
		);
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
		$aParams['title']			= $this->toolbar_title;
		$aParams['toolbar_btn']		= $this->toolbar_btn;
		$aParams['show_toolbar']	= $this->show_toolbar;
		$aParams['toolbar_scroll']	= $this->toolbar_scroll;
		$aParams['module_path']		= $this->module->module_dir.'/views/templates/admin/'.$this->module->name.'/';
        $aParams['is_multishop']	= Shop::isFeatureActive();

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