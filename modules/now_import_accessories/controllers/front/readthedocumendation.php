<?php
/*
 * 2013
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

$file = _PS_MODULE_DIR_.'now_import_stock/documentation/now_import_stock.pdf';

header('Content-type: application/pdf');
header("Content-Disposition: attachment;filename=now_import_stock.pdf");
header("Content-Transfer-Encoding: binary ");
readfile($file);
exit;