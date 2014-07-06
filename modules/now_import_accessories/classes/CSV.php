<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

@ini_set('max_execution_time', 0);

/** correct Mac error on eof */
@ini_set('auto_detect_line_endings', '1');

/** No max line limit since the lines can be more than 4096. Performance impact is not significant. */
define('MAX_LINE_SIZE', 0);

/** Used for validatefields diying without user friendly error or not */
define('UNFRIENDLY_ERROR', false);

/** this value set the number of columns visible on each page */
define('MAX_COLUMNS', 6);

if (!class_exists('NowCSV'))
{
	class NowCSV
	{
		public $sFilename;
		public $sNewFilename;
		public $sFile;
		public $iFileSize;
		public $iMaxFileSize;
		public $aTypeFile = array('.csv', '.txt');
		public $sTypeFile;
		public $sSeparator;
		public $sDelimiter;
		public $sDecimalDelimiter;
		public $bConvertFileToUTF8;
		public $aData = array();
		public $aErrors = array();
		public $aFile = array();
		public static $aDelimiter = array(1 => "&#39;", 2 => '&quot;');

		/**
		 * Loads objects, filename and optionnaly a delimiter.
		 * @param string $sFilename : used later to save the file
		 * @param string $sDelimiter Optional : delimiter used
		 */
		public function __construct($aFile, $sTypeFile = '.csv', $sSeparator = ';', $sDelimiter = '"', $sDecimalDelimiter = '.', $bConvertFileToUTF8 = true, $iMaxFileSize = 0)
		{
			$this->aFile                = $aFile;
			$this->sTypeFile            = $sTypeFile;
			$this->sSeparator           = $sSeparator;
			$this->sDelimiter           = $sDelimiter;
			$this->sDecimalDelimiter    = $sDecimalDelimiter;
			$this->bConvertFileToUTF8   = (bool)$bConvertFileToUTF8;
			$this->iMaxFileSize         = $iMaxFileSize;
            if (array_key_exists('tmp_name', $aFile))
			    $this->sFilename        = $aFile['tmp_name'];
			$this->context              = Context::getContext();

			$this->setNewFilename();
		}

		/**
		 * Check if this file is ok
		 *
		 * @return bool
		 */
		public function checkFile() {

			if (!file_exists($this->sFilename)) {
				$this->aErrors[] = 'File not found. Make sure you specified the correct path.';
			}

			if (!$this->sFile = @fopen($this->sFilename, "r")) {
				$this->aErrors[] = 'Error opening data file.';
			}

			if (!$this->iFileSize = @filesize($this->sFilename)) {
				$this->aErrors[] = 'File is empty.';
			}

            if (array_key_exists('error', $this->aFile)) {
                switch ($this->aFile['error'])
                {
                    case UPLOAD_ERR_INI_SIZE:
                        $this->aErrors[] = 'The uploaded file exceeds the upload_max_filesize directive in php.ini. If your server configuration allows it, you may add a directive in your .htaccess.';
                        break;
                    case UPLOAD_ERR_FORM_SIZE:
                        $this->aErrors[] = 'The uploaded file exceeds the post_max_size directive in php.ini. If your server configuration allows it, you may add a directive in your .htaccess, for example: <br/><a href="'.$this->context->link->getAdminLink('AdminMeta').'" ><code>php_value post_max_size 20M</code> (click to open "Generators" page)</a>';
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $this->aErrors[] = 'The uploaded file was only partially uploaded.';
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        $this->aErrors[] = 'No file was uploaded.';
                        break;
                }
            }

			if (!empty($this->aErrors)) {
				return false;
			} else {
				NowCSV::setLocale();
				NowCSV::rewindBomAware($this->sFile);
				return true;
			}
		}

		public function readData() {
			for ($current_line = 0; $line = fgetcsv($this->sFile, MAX_LINE_SIZE, $this->sSeparator); $current_line++) {
				if ($this->bConvertFileToUTF8)
					$this->aData[] = $this->utf8EncodeArray($line);
				else
					$this->aData[] = $line;
			}

			$this->closeCsvFile($this->sFile);
		}

		public function removeLinesInData($aLines) {
			foreach ($aLines as $key => $value) {
				if (isset($this->aData[$key]))
					unset($this->aData[$key]);
			}
		}

		public function removeColumnsInData($aColumns) {
			foreach ($this->aData as &$aData) {
				$aData = array_combine($aColumns, $aData);
				if (isset($aData['ignore_column']))
					unset($aData['ignore_column']);
			}
		}

		public function copyFile($sDirectoryPath) {

			if (!file_exists($sDirectoryPath.$this->sNewFilename)) {

				if (!file_exists($sDirectoryPath)) {
					$this->aErrors[] = sprintf('Folder not found. Make sure you specified the correct path (%s)', $sDirectoryPath);
				}

				if (!is_uploaded_file($this->sFilename)) {
					$this->aErrors[] = 'Uploaded file incorrect.';
				}

				if (!@move_uploaded_file($this->sFilename, $sDirectoryPath.$this->sNewFilename)) {
					$this->aErrors[] = sprintf('An error occurred while uploading / copying this file: "%s"', $sDirectoryPath.$this->sNewFilename);
					return false;
				} else {
					if (!is_writable($sDirectoryPath.$this->sNewFilename)) {
						if (!@chmod($sDirectoryPath.$this->sNewFilename, 0666)) {
							$this->aErrors[] = sprintf('Cannot change the mode of file (%s)', $sDirectoryPath.$this->sNewFilename);
							return false;
						};
					}
					return true;
				}
			}
		}

		public function setNewFilename() {
			$this->sNewFilename = 'import_file_'.date('Y_m_d_H_i_s').$this->sTypeFile;
		}

		public function setData() {

		}

		protected function closeCsvFile($handle)
		{
			fclose($handle);
		}

		public function utf8EncodeArray($aArray)
		{
			return (is_array($aArray) ? array_map('utf8_encode', $aArray) : utf8_encode($aArray));
		}

		protected static function rewindBomAware($handle)
		{
			// A rewind wrapper that skip BOM signature wrongly
			rewind($handle);
			if (($bom = fread($handle, 3)) != "\xEF\xBB\xBF")
				rewind($handle);
		}

		public static function setLocale()
		{
			$iso_lang  = trim(Tools::getValue('iso_lang'));
			setlocale(LC_COLLATE, strtolower($iso_lang).'_'.strtoupper($iso_lang).'.UTF-8');
			setlocale(LC_CTYPE, strtolower($iso_lang).'_'.strtoupper($iso_lang).'.UTF-8');
		}

		/**
		 * Main function
		 * Adds headers
		 * Outputs
		 */
		public function export()
		{
			$this->headers();

			$header_line = false;

			foreach ($this->collection as $object)
			{
				$vars = get_object_vars($object);
				if (!$header_line)
				{
					$this->output(array_keys($vars));
					$header_line = true;
				}

				// outputs values
				$this->output($vars);
				unset($vars);
			}
		}

		/**
		 * Wraps data and echoes
		 * Uses defined sDelimiter
		 */
		public function output($data)
		{
			$wraped_data = array_map(array('CSVCore', 'wrap'), $data);
			echo sprintf("%s\n", implode($this->sDelimiter, $wraped_data));
		}

		/**
		 * Escapes data
		 * @param string $data
		 * @return string $data
		 */
		public static function wrap($data)
		{
			$data = Tools::safeOutput($data, '";');
			return sprintf('"%s"', $data);
		}

		/**
		 * Adds headers
		 */
		public function headers()
		{
			header('Content-type: text/csv');
			header('Content-Type: application/force-download; charset=UTF-8');
			header('Cache-Control: no-store, no-cache');
			header('Content-disposition: attachment; filename="'.$this->sFilename.'.csv"');
		}
	}
}



