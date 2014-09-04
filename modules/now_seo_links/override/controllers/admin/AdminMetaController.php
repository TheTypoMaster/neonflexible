<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

class AdminMetaController extends AdminMetaControllerCore {

    /**
     * Method addAllRouteFields() : Add a new route rule : attachment_rule
     *
     * @module now_seo_links
     *
     * @see AdminMetaControllerCore::addAllRouteFields()
     */
    public function addAllRouteFields()
    {
        parent::addAllRouteFields();
        $this->addFieldRoute('attachment_rule', $this->l('Route to Attachment'));
    }

}