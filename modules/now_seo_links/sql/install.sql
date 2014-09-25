SET NAMES 'utf8';

CREATE TABLE IF NOT EXISTS `PREFIX_now_language_link` (
  `id_now_language_link` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_shop` int(10) unsigned NOT NULL,
  `id_lang` int(10) unsigned NOT NULL,
  `folder_name` varchar(20) NOT NULL,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_now_language_link`),
  KEY `id_shop` (`id_shop`),
  KEY `id_lang` (`id_lang`)
) ENGINE=ENGINE_TYPE;

ALTER TABLE `PREFIX_now_language_link`
ADD CONSTRAINT `PREFIX_now_language_link_ibfk_1` FOREIGN KEY (`id_shop`) REFERENCES `PREFIX_shop` (`id_shop`),
ADD CONSTRAINT `PREFIX_now_language_link_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `PREFIX_lang` (`id_lang`);
