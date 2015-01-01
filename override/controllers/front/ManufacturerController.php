<?php

class ManufacturerController extends ManufacturerControllerCore {

	/**
	 * Method init() : Initialize manufacturer controller with manufacturer_rewrite params
	 *
	 * @module now_seo_links
	 *
	 * @see ManufacturerControllerCore::init()
	 */
	public function init()
	{
		// Get rewrite
		$sRewrite = str_replace('-', '%', Tools::getValue('manufacturer_rewrite', false));

		if ($sRewrite)
		{
			$iIdManufacturer = (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
				SELECT `id_manufacturer`
				FROM `'._DB_PREFIX_.'manufacturer`
				WHERE `name` LIKE \''.$sRewrite.'\'
			');

			if ($iIdManufacturer)
				$_GET['id_manufacturer'] = $iIdManufacturer;
		}

		parent::init();

		// On vérifie si l'URL actuelle est correcte ou pas
		$goodUrl = Context::getContext()->link->getManufacturerLink($this->manufacturer);

		if (!preg_match('#'.$_SERVER['REDIRECT_URL'].'#', $goodUrl)) {
			header('Status: 301 Moved Permanently', false, 301);
			header('Location: '.$goodUrl);
			exit;
		}
	}

}
