<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

include (_PS_MODULE_DIR_.'now_block_cms_footer/now_block_cms_footer.php');

class AdminBlockFooterCmsController extends ModuleAdminController {
	public $module;

	public function __construct()
	{
		$this->module = new now_block_cms_footer();

		parent::__construct();
	}
}