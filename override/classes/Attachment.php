<?php
/*
 * 2014
 * Author: LEFEVRE LOIC
 * Site: www.ninja-of-web.fr
 * Mail: contact@ninja-of-web.fr
 */

class Attachment extends AttachmentCore {
	public static function getIdAttachmentByFileName($sFileName)
	{
		return (int)Db::getInstance()->getValue('
			SELECT `id_attachment`
			FROM `'._DB_PREFIX_.'attachment`
			WHERE `file_name` = "'.$sFileName.'"'
		);
	}

	public static function getProductIdByIdAttachment($iIdAttachment)
	{
		return Db::getInstance()->getValue('
			SELECT `id_product`
			FROM `'._DB_PREFIX_.'product_attachment`
			WHERE `id_attachment` = '.(int)$iIdAttachment
		);
	}
}
