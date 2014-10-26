SET NAMES 'utf8';

ALTER TABLE  `ps_now_product_type` ADD  `type` ENUM(  'BUTTON',  'CONTENT' ) NOT NULL AFTER  `id_now_product_type`;