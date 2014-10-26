<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

if (!class_exists('NowModule'))
{
	class NowModule extends Module {

		/*
		  * Path of this module
		  */
		public $module_dir;

		/*
		  * Uri of this module
		  */
		public $module_uri;

		/*
		  * Name of controllers which are used on this module
		  */
		public $aAdminControllers = array();

		/*
		  * List of params which are added on the configuration database during the installation
		  */
		public $aConfigurationDefaultSettings = array();

		/*
		  * List of sql files for the install module
		  * ex: array('1.5' => 'install_1.5.sql')
		  */
		public $aSqlFileToInstall = array();

		/*
		  * List of sql file for the unistall module
		  * ex: array('1.5' => 'uninstall_1.5.sql')
		  */
		public $aSqlFileToUninstall = array();

		public function __construct() {

			// define path of directory module
			$this->module_dir = _PS_MODULE_DIR_.$this->name.DIRECTORY_SEPARATOR;
			$this->module_uri = DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$this->name.DIRECTORY_SEPARATOR;

			parent::__construct();
		}

		public function install()
		{
			$this->installSqlFiles();
			$this->installModuleTabs();
			$this->installConfigurationSettings();

			return parent::install();
		}

		public function uninstall()
		{
			$this->uninstallSqlFiles();
			$this->uninstallModuleTabs();
			return parent::uninstall();
		}

		public function installSqlFiles() {
			$bReturn = true;
			$this->setSqlFileToInstall();
			if (!empty($this->aSqlFileToInstall)) {
				foreach ($this->aSqlFileToInstall as $sVersion => $sFileName) {
					$bReturn &= $this->executeSqlFile($sVersion, $sFileName);
				}
			}
			return $bReturn;
		}

		public function uninstallSqlFiles() {
			$bReturn = true;
			$this->setSqlFileToUninstall();
			if (!empty($this->aSqlFileToUninstall)) {
				foreach ($this->aSqlFileToUninstall as $sVersion => $sFileName) {
					$bReturn &= $this->executeSqlFile($sVersion, $sFileName);
				}
			}
			return $bReturn;
		}

		public function executeSqlFile($sVersion, $sFileName) {

			if (Tools::version_compare($sVersion, $this->version, '=')) {

				$sFilePath = $this->module_dir.'sql'.DIRECTORY_SEPARATOR;

				if (!file_exists($sFilePath.$sFileName))
					return false;

				else if (!$sql = file_get_contents($sFilePath.$sFileName))
					return false;

				$sql = str_replace(array('PREFIX_', 'ENGINE_TYPE'), array(_DB_PREFIX_, _MYSQL_ENGINE_), $sql);
				$sql = preg_split("/;\s*[\r\n]+/", trim($sql));

				foreach ($sql as $query)
					if (!Db::getInstance()->execute(trim($query)))
						return false;

				return true;
			}

			return false;
		}

		public function installConfigurationSettings() {
			$this->setConfigurationSettings();
			if (!empty($this->aConfigurationDefaultSettings)) {
				foreach ($this->aConfigurationDefaultSettings as $name => $value) {
					if (!Configuration::get($name)) {
						Configuration::updateValue($name, $value);
					}
				}
			}
			return true;
		}

		public function installModuleTabs() {
			$this->setAdminControllers();
			if (!empty($this->aAdminControllers)) {
				foreach ($this->aAdminControllers as $controller_name => $params) {

					@copy(_PS_MODULE_DIR_.$this->name.'/logo.gif', _PS_IMG_DIR_.'t/'.$controller_name.'.gif');

					// Check if the AdminController isset or not
					if (!Tab::getIdFromClassName($controller_name)) {
						$tab = new Tab();
						$tab->name[(int)$this->context->language->id] = pSQL($params['name']);
						$tab->class_name = pSQL($controller_name);
						$tab->module = pSQL($this->name);
						$tab->id_parent = (int)Tab::getIdFromClassName($params['parent']);
						$tab->save();
					}
				}
			}
			return true;
		}

		public function uninstallModuleTabs() {
			if (!empty($this->aAdminControllers)) {
				foreach ($this->aAdminControllers as $controller_name => $params) {
					$id_tab = Tab::getIdFromClassName($controller_name);
					if($id_tab != 0)
					{
						$tab = new Tab((int)$id_tab);
						$tab->delete();
					}
				}
			}
			return true;
		}


		/**
		 * Define the configuration of default settings
		 */
		public function setConfigurationSettings() {}

		/**
		 * Define admin controller which must be installed
		 */
		public function setAdminControllers() {}

		/**
		 * Define the list of SQL file to execute to install
		 */
		public function setSqlFileToInstall() {}

		/**
		 * Define the list of SQL file to execute to uninstall
		 */
		public function setSqlFileToUninstall() {}

	}
}