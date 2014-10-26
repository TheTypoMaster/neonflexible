<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

require_once (_PS_MODULE_DIR_ . 'now_slideshow/now_slideshow.php');

class AdminSlideshowController extends ModuleAdminController {
	public $module;

	public function __construct()
	{
		$this->module = new now_slideshow();

		parent::__construct();
	}
}