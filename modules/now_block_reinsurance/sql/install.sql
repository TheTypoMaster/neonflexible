SET NAMES 'utf8';

CREATE TABLE IF NOT EXISTS `PREFIX_now_block_reinsurance` (
  `id_now_block_reinsurance` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_shop` int(10) unsigned NOT NULL,
  `id_cms` int(10) unsigned NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `position` int(10) unsigned NOT NULL,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_now_block_reinsurance`),
  KEY `id_cms` (`id_cms`),
  KEY `id_shop` (`id_shop`)
) ENGINE=ENGINE_TYPE;

ALTER TABLE `PREFIX_now_block_reinsurance`
ADD CONSTRAINT `PREFIX_now_block_reinsurance_ibfk_1` FOREIGN KEY (`id_cms`) REFERENCES `PREFIX_cms` (`id_cms`),
ADD CONSTRAINT `PREFIX_now_block_reinsurance_ibfk_2` FOREIGN KEY (`id_shop`) REFERENCES `PREFIX_shop` (`id_shop`);


CREATE TABLE IF NOT EXISTS `PREFIX_now_block_reinsurance_lang` (
  `id_now_block_reinsurance` int(10) unsigned NOT NULL,
  `id_lang` int(10) unsigned NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  KEY `id_now_block_reinsurance_lang` (`id_now_block_reinsurance`, `id_lang`),
  KEY `id_lang` (`id_lang`)
) ENGINE=ENGINE_TYPE;

ALTER TABLE `PREFIX_now_block_reinsurance_lang`
ADD CONSTRAINT `PREFIX_now_block_reinsurance_lang_ibfk_3` FOREIGN KEY (`id_now_block_reinsurance`) REFERENCES `PREFIX_now_block_reinsurance` (`id_now_block_reinsurance`),
ADD CONSTRAINT `PREFIX_now_block_reinsurance_lang_ibfk_4` FOREIGN KEY (`id_lang`) REFERENCES `PREFIX_lang` (`id_lang`);
