<?php
/*
 * 2013
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

$file = _PS_MODULE_DIR_.'now_all_import/documentation/now_all_import.csv';

header('Content-type: application/text');
header("Content-Disposition: attachment;filename=now_all_import.csv");
header("Content-Transfer-Encoding: binary ");
readfile($file);
exit;