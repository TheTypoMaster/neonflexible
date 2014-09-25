<?php


class ProductController extends ProductControllerCore {

	/**
	 * Method assignCategory() : Initialize the good category object
	 *
	 * @module now_seo_links
	 *
	 * @see ProductControllerCore::assignCategory()
	 */
	public function assignCategory()
	{
		$this->category = new Category($this->product->id_category_default, Context::getContext()->language->id);
		parent::assignCategory();
	}

	/**
	 * Method init() : Initialize product controller with product_rewrite params
	 *
	 * @module now_seo_links
	 *
	 * @see ProductControllerCore::init()
	 */
	public function init()
	{
		// Get rewrite
		$sRewrite = Tools::getValue('product_rewrite', false);

		if ($sRewrite)
		{
			$iIdProduct = (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
				SELECT `id_product`
				FROM `'._DB_PREFIX_.'product_lang`
				WHERE `link_rewrite` = \''.$sRewrite.'\'
				AND `id_lang` = '.Context::getContext()->language->id
			);

			if ($iIdProduct)
				$_GET['id_product'] = $iIdProduct;
		}

		parent::init();

		// On vérifie si l'URL actuelle est correcte ou pas
		$goodUrl = Context::getContext()->link->getProductLink($this->product);

		if (!preg_match('#'.$_SERVER['REDIRECT_URL'].'#', $goodUrl)) {
			header('Status: 301 Moved Permanently', false, 301);
			header('Location: '.$goodUrl);
			exit;
		}
	}

}