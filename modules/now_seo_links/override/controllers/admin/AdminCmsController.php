<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

class AdminCmsController extends AdminCmsControllerCore
{
	/**
	 * Method postProcess() : add or update cms object
	 *
	 * @module now_seo_links
	 * @return object CMS
	 *
	 * @see AdminCmsControllerCore::postProcess()
	 */
	public function postProcess() {

		if (Tools::isSubmit('submitAddcms') || Tools::isSubmit('submitAddcmsAndPreview')) {
			$iIdCms = Tools::getValue('id_cms', false);
			$aShops = array_keys(Tools::getValue('checkBoxShopAsso_cms', array()));

			$aLinkRewrite = array();

			foreach (Language::getLanguages(true) as $aLang) {
				if (array_key_exists('link_rewrite_'.(int)$aLang['id_lang'], $_POST)) {
					$aLinkRewrite[(int)$aLang['id_lang']] = Tools::getValue('link_rewrite_'.(int)$aLang['id_lang']);
				}
			}

			// Check if name already exist
			foreach ($aLinkRewrite as $iIdLang => $sLinkRewrite) {
				if (CMS::linkRewriteIsAlreadyUsed($iIdCms, $sLinkRewrite, $iIdLang, $aShops)) {
					$this->errors[] = sprintf(
						Tools::displayError('Ce link_rewrite "%s" (%s) existe déjà pour une autre page CMS et ne peut être utilisé une nouvelle fois.'),
						$sLinkRewrite,
						Language::getIsoById($iIdLang)
					);
				}
			}
		}

		return parent::postProcess();
	}

	/**
	 * Surcharge de cette méthode afin d'ajouter un champs textarea pour meta_descriptions au lieu d'un champs de type texte
	 * @return AdminCmsControllerCore::renderForm()
	 */
	public function renderForm()
	{
		if (!$this->loadObject(true))
			return;

		if (Validate::isLoadedObject($this->object))
			$this->display = 'edit';
		else
			$this->display = 'add';

		$this->initToolbar();
		$this->initPageHeaderToolbar();

		$categories = CMSCategory::getCategories($this->context->language->id, false);
		$html_categories = CMSCategory::recurseCMSCategory($categories, $categories[0][1], 1, $this->getFieldValue($this->object, 'id_cms_category'), 1);

		$this->fields_form = array(
			'tinymce' => true,
			'legend' => array(
				'title' => $this->l('CMS Page'),
				'icon' => 'icon-folder-close'
			),
			'input' => array(
				// custom template
				array(
					'type' => 'select_category',
					'label' => $this->l('CMS Category'),
					'name' => 'id_cms_category',
					'options' => array(
						'html' => $html_categories,
					),
				),
				array(
					'type' => 'text',
					'label' => $this->l('Meta title'),
					'name' => 'meta_title',
					'id' => 'name', // for copyMeta2friendlyURL compatibility
					'lang' => true,
					'required' => true,
					'class' => 'copyMeta2friendlyURL',
					'hint' => $this->l('Invalid characters:').' &lt;&gt;;=#{}'
				),
				array(
					'type' => 'textarea',
					'label' => $this->l('Meta description'),
					'name' => 'meta_description',
					'lang' => true,
					'hint' => $this->l('Invalid characters:').' &lt;&gt;;=#{}'
				),
				array(
					'type' => 'tags',
					'label' => $this->l('Meta keywords'),
					'name' => 'meta_keywords',
					'lang' => true,
					'hint' => array(
						$this->l('To add "tags" click in the field, write something, and then press "Enter."'),
						$this->l('Invalid characters:').' &lt;&gt;;=#{}'
					)
				),
				array(
					'type' => 'text',
					'label' => $this->l('Friendly URL'),
					'name' => 'link_rewrite',
					'required' => true,
					'lang' => true,
					'hint' => $this->l('Only letters and the hyphen (-) character are allowed.')
				),
				array(
					'type' => 'textarea',
					'label' => $this->l('Page content'),
					'name' => 'content',
					'autoload_rte' => true,
					'lang' => true,
					'rows' => 5,
					'cols' => 40,
					'hint' => $this->l('Invalid characters:').' <>;=#{}'
				),
				array(
					'type' => 'switch',
					'label' => $this->l('Indexation by search engines'),
					'name' => 'indexation',
					'required' => false,
					'class' => 't',
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'indexation_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						),
						array(
							'id' => 'indexation_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						)
					),
				),
				array(
					'type' => 'switch',
					'label' => $this->l('Displayed'),
					'name' => 'active',
					'required' => false,
					'is_bool' => true,
					'values' => array(
						array(
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->l('Enabled')
						),
						array(
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->l('Disabled')
						)
					),
				),
			),
			'submit' => array(
				'title' => $this->l('Save'),
			),
			'buttons' => array(
				'save_and_preview' => array(
					'name' => 'viewcms',
					'type' => 'submit',
					'title' => $this->l('Save and preview'),
					'class' => 'btn btn-default pull-right',
					'icon' => 'process-icon-preview'
				)
			)
		);

		if (Shop::isFeatureActive())
		{
			$this->fields_form['input'][] = array(
				'type' => 'shop',
				'label' => $this->l('Shop association'),
				'name' => 'checkBoxShopAsso',
			);
		}

		$this->tpl_form_vars = array(
			'active' => $this->object->active,
			'PS_ALLOW_ACCENTED_CHARS_URL', (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL')
		);
		return AdminController::renderForm();
	}

}