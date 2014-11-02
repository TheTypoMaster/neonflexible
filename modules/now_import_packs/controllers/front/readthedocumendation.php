<?php
/*
 * 2013
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

$file = _PS_MODULE_DIR_.'now_import_packs/documentation/import_packs.csv';

header('Content-type: application/text');
header("Content-Disposition: attachment;filename=import_packs.csv");
header("Content-Transfer-Encoding: binary ");
readfile($file);
exit;