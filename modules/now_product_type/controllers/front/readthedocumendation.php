<?php
/*
 * 2013
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

$filename = 'exemple-import-product-type.csv';
if (Tools::getValue('type') == 'ideas') {
	$filename = 'exemple-import-ideas-or-tips.csv';
}

$file = _PS_MODULE_DIR_.'now_product_type/documentation/' . $filename;

header('Content-type: application/pdf');
header("Content-Disposition: attachment;filename=$filename");
header("Content-Transfer-Encoding: binary ");
readfile($file);
exit;