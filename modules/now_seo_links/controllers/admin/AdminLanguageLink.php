<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

class AdminLanguageLinkController extends ModuleAdminController {
	public $module;

	public function __construct()
	{
		$this->module = new now_seo_links();

		parent::__construct();
	}
}