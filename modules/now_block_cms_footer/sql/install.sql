SET NAMES 'utf8';

CREATE TABLE IF NOT EXISTS `PREFIX_now_block_cms_footer_column` (
  `id_now_block_cms_footer_column` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `position` int(10) unsigned NOT NULL,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_now_block_cms_footer_column`)
) ENGINE=ENGINE_TYPE;


CREATE TABLE IF NOT EXISTS `PREFIX_now_block_cms_footer_column_lang` (
  `id_now_block_cms_footer_column` int(10) unsigned NOT NULL,
  `id_lang` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  KEY `id_now_block_cms_footer_column` (`id_now_block_cms_footer_column`,`id_lang`),
  KEY `id_lang` (`id_lang`)
) ENGINE=ENGINE_TYPE;

ALTER TABLE `PREFIX_now_block_cms_footer_column_lang`
ADD CONSTRAINT `PREFIX_now_block_cms_footer_column_lang_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `PREFIX_lang` (`id_lang`),
ADD CONSTRAINT `PREFIX_now_block_cms_footer_column_lang_ibfk_1` FOREIGN KEY (`id_now_block_cms_footer_column`) REFERENCES `PREFIX_now_block_cms_footer_column` (`id_now_block_cms_footer_column`);

CREATE TABLE IF NOT EXISTS `PREFIX_now_block_cms_footer` (
  `id_now_block_cms_footer` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_now_block_cms_footer_column` int(10) unsigned NOT NULL,
  `type` enum('link','category','manufacturer','cms') NOT NULL,
  `id_type` int(10) unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `position` int(10) unsigned NOT NULL,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_now_block_cms_footer`),
  KEY `id_now_block_cms_footer_column` (`id_now_block_cms_footer_column`)
) ENGINE=ENGINE_TYPE;

ALTER TABLE `PREFIX_now_block_cms_footer`
ADD CONSTRAINT `PREFIX_now_block_cms_footer_ibfk_1` FOREIGN KEY (`id_now_block_cms_footer_column`) REFERENCES `PREFIX_now_block_cms_footer_column` (`id_now_block_cms_footer_column`);


CREATE TABLE IF NOT EXISTS `PREFIX_now_block_cms_footer_lang` (
  `id_now_block_cms_footer` int(10) unsigned NOT NULL,
  `id_lang` int(10) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  KEY `id_now_block_cms_footer_lang` (`id_now_block_cms_footer`,`id_lang`),
  KEY `id_lang` (`id_lang`)
) ENGINE=ENGINE_TYPE;

ALTER TABLE `PREFIX_now_block_cms_footer_lang`
ADD CONSTRAINT `PREFIX_now_block_cms_footer_lang_ibfk_3` FOREIGN KEY (`id_now_block_cms_footer`) REFERENCES `PREFIX_now_block_cms_footer` (`id_now_block_cms_footer`),
ADD CONSTRAINT `PREFIX_now_block_cms_footer_lang_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `PREFIX_lang` (`id_lang`);
