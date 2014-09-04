<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

class now_seo_linksDisplayImageModuleFrontController extends ModuleFrontController {

    public function __construct() {
        p($_GET);
        p($_POST);
        d($_REQUEST);
        $this->redirect();
    }

    public function getImageLink() {
        return 'http://www.romance-florale.local/28-large_default/orchide-blanche.jpg';
    }

    public function redirect() {
        $sFile = $this->getImageLink();

        $sFileName = basename($sFile);

        $sFileExtension = strtolower(substr(strrchr($sFileName, "."), 1));

        switch($sFileExtension) {
            case 'gif':
                $sContentType = 'image/gif';
                break;
            case 'png':
                $sContentType = 'image/png';
                break;
            case 'jpeg':
            case 'jpg':
                $sContentType = 'image/jpg';
                break;
            default;
        }

        header('Content-type: ' . $sContentType);
        readfile($sFile);
    }
}