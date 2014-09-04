<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

class AttachmentController extends AttachmentControllerCore {

    /**
     * Method postProcess() : Initialize id_attachment with attachment_file_name params
     *
     * @module now_seo_links
     *
     * @see AttachmentControllerCore::postProcess()
     */
    public function postProcess() {
        if ($sFileName = Tools::getValue('attachment_file_name')) {
            $_GET['id_attachment'] = Attachment::getIdAttachmentByFileName($sFileName);
        }

        parent::postProcess();
    }

}