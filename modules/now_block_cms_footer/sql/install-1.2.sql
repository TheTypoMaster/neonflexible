SET NAMES 'utf8';

ALTER TABLE  `PREFIX_now_block_cms_footer` ADD  `id_shop` INT( 10 ) NOT NULL DEFAULT '1' AFTER  `id_now_block_cms_footer_column`;

ALTER TABLE  `PREFIX_now_block_cms_footer_column` ADD  `id_shop` INT( 10 ) NOT NULL DEFAULT '1' AFTER  `id_now_block_cms_footer_column`;