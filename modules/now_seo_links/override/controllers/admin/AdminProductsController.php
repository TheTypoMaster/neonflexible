<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

class AdminProductsController extends AdminProductsControllerCore {

		/**
		 * Method processSave() : add or update product object
		 *
		 * @module now_seo_links
		 * @return object Product
		 *
		 * @see AdminProductsControllerCore::processSave()
		 */
		public function processSave() {

			$iIdProduct = Tools::getValue('id_product');
			$aShops = Context::getContext()->shop->getContextListShopID();

			$aLinkRewrite = array();

			foreach (Language::getLanguages(true) as $aLang) {
				if (array_key_exists('link_rewrite_'.(int)$aLang['id_lang'], $_POST)) {
					$aLinkRewrite[(int)$aLang['id_lang']] = Tools::getValue('link_rewrite_'.(int)$aLang['id_lang']);
				}
			}

			// Check if name already exist
			foreach ($aLinkRewrite as $iIdLang => $sLinkRewrite) {
				if (Product::linkRewriteIsAlreadyUsed($iIdProduct, $sLinkRewrite, $iIdLang, $aShops)) {
					$this->errors[] = sprintf(
						Tools::displayError('Ce link_rewrite "%s" (%s) existe déjà pour un autre produit et ne peut être utilisé une nouvelle fois.'),
						$sLinkRewrite,
						Language::getIsoById($iIdLang)
					);
				}
			}

			return parent::processSave();
		}

		/**
		 * Method processAddAttachments() : Change name of file which are uploaded for this product
		 * Rules:
		 *      - For the first upload the filename has been : name-of-product.extention
		 *      - For the second upload : name-of-product-1.extention
		 *      - ...
		 *
		 * @module now_seo_links
		 * @return void
		 *
		 * @see AdminProductsControllerCore::processAddAttachments()
		 */
		public function processAddAttachments()
		{
			$languages = Language::getLanguages(false);
			$is_attachment_name_valid = false;
			foreach ($languages as $language)
			{
				$attachment_name_lang = Tools::getValue('attachment_name_'.(int)($language['id_lang']));
				if (Tools::strlen($attachment_name_lang ) > 0)
					$is_attachment_name_valid = true;

				if (!Validate::isGenericName(Tools::getValue('attachment_name_'.(int)($language['id_lang']))))
					$this->errors[] = Tools::displayError('Invalid Name');
				elseif (Tools::strlen(Tools::getValue('attachment_name_'.(int)($language['id_lang']))) > 32)
					$this->errors[] = sprintf(Tools::displayError('The name is too long (%d chars max).'), 32);
				if (!Validate::isCleanHtml(Tools::getValue('attachment_description_'.(int)($language['id_lang']))))
					$this->errors[] = Tools::displayError('Invalid description');
			}
			if (!$is_attachment_name_valid)
				$this->errors[] = Tools::displayError('An attachment name is required.');

			if (empty($this->errors))
			{
				if (isset($_FILES['attachment_file']) && is_uploaded_file($_FILES['attachment_file']['tmp_name']))
				{
					if ($_FILES['attachment_file']['size'] > (Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE') * 1024 * 1024))
						$this->errors[] = sprintf(
							$this->l('The file is too large. Maximum size allowed is: %1$d kB. The file you\'re trying to upload is: %2$d kB.'),
							(Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE') * 1024),
							number_format(($_FILES['attachment_file']['size'] / 1024), 2, '.', '')
						);
					else
					{
						do $uniqid = sha1(microtime());
						while (file_exists(_PS_DOWNLOAD_DIR_.$uniqid));
						if (!copy($_FILES['attachment_file']['tmp_name'], _PS_DOWNLOAD_DIR_.$uniqid))
							$this->errors[] = $this->l('File copy failed');
						@unlink($_FILES['attachment_file']['tmp_name']);
					}
				}
				elseif ((int)$_FILES['attachment_file']['error'] === 1)
				{
					$max_upload = (int)ini_get('upload_max_filesize');
					$max_post = (int)ini_get('post_max_size');
					$upload_mb = min($max_upload, $max_post);
					$this->errors[] = sprintf(
						$this->l('The file %1$s exceeds the size allowed by the server. The limit is set to %2$d MB.'),
						'<b>'.$_FILES['attachment_file']['name'].'</b> ',
						'<b>'.$upload_mb.'</b>'
					);
				}
				else
					$this->errors[] = Tools::displayError('The file is missing.');

				if (empty($this->errors) && isset($uniqid))
				{
					$attachment = new Attachment();
					foreach ($languages as $language)
					{
						if (Tools::getIsset('attachment_name_'.(int)$language['id_lang']))
							$attachment->name[(int)$language['id_lang']] = Tools::getValue('attachment_name_'.(int)$language['id_lang']);
						if (Tools::getIsset('attachment_description_'.(int)$language['id_lang']))
							$attachment->description[(int)$language['id_lang']] = Tools::getValue('attachment_description_'.(int)$language['id_lang']);
					}

					if (Tools::getIsset('name_'.(int)Configuration::get('PS_LANG_DEFAULT'))) {
						$sFilename = $_FILES['attachment_file']['name'];
						$sExtention = substr($sFilename, strrpos($sFilename, '.') + 1);
						$attachment->file_name = Tools::link_rewrite(trim(Tools::getValue('name_'.(int)Configuration::get('PS_LANG_DEFAULT'))));

						// On regarde si c'est le premier document joint au produit ou pas
						$aAttachmentOfProduct = $attachment->getAttachments(Context::getContext()->language->id, (int)Tools::getValue('id_product'));

						$iNb = count($aAttachmentOfProduct);
						if ($iNb > 0)
							$attachment->file_name .= '-'.$iNb;

						$attachment->file_name .= '.'.$sExtention;
					}

					$attachment->file = $uniqid;
					$attachment->mime = $_FILES['attachment_file']['type'];
					if (empty($attachment->mime) || Tools::strlen($attachment->mime) > 128)
						$this->errors[] = Tools::displayError('Invalid file extension');
					if (!Validate::isGenericName($attachment->file_name))
						$this->errors[] = Tools::displayError('Invalid file name');
					if (Tools::strlen($attachment->file_name) > 128)
						$this->errors[] = Tools::displayError('The file name is too long.');
					if (empty($this->errors))
					{
						$res = $attachment->add();
						if (!$res)
							$this->errors[] = Tools::displayError('This attachment was unable to be loaded into the database.');
						else
						{
							$id_product = (int)Tools::getValue($this->identifier);
							$res = $attachment->attachProduct($id_product);
							if (!$res)
								$this->errors[] = Tools::displayError('We were unable to associate this attachment to a product.');
						}
					}
					else
						$this->errors[] = Tools::displayError('Invalid file');
				}
			}
		}

}