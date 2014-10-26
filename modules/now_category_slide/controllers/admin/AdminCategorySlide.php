<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

require_once (_PS_MODULE_DIR_ . 'now_category_slide/now_category_slide.php');

class AdminCategorySlideController extends ModuleAdminController {
	public $module;

	public function __construct()
	{
		$this->module = new now_category_slide();

		parent::__construct();
	}
}