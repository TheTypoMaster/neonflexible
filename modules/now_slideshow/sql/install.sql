SET NAMES 'utf8';

CREATE TABLE IF NOT EXISTS `PREFIX_now_slideshow` (
  `id_now_slideshow` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_shop` int(10) unsigned NOT NULL,
  `type` enum('link','category','manufacturer','cms') NOT NULL,
  `id_type` int(10) unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `position` int(10) unsigned NOT NULL,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_now_slideshow`),
  KEY `id_shop` (`id_shop`)
) ENGINE=ENGINE_TYPE;

ALTER TABLE `PREFIX_now_slideshow`
ADD CONSTRAINT `PREFIX_now_slideshow_ibfk_1` FOREIGN KEY (`id_shop`) REFERENCES `PREFIX_shop` (`id_shop`);


CREATE TABLE IF NOT EXISTS `PREFIX_now_slideshow_lang` (
  `id_now_slideshow` int(10) unsigned NOT NULL,
  `id_lang` int(10) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `button_name` varchar(255) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  KEY `id_now_slideshow_lang` (`id_now_slideshow`,`id_lang`),
  KEY `id_lang` (`id_lang`)
) ENGINE=ENGINE_TYPE;

ALTER TABLE `PREFIX_now_slideshow_lang`
ADD CONSTRAINT `PREFIX_now_slideshow_lang_ibfk_3` FOREIGN KEY (`id_now_slideshow`) REFERENCES `PREFIX_now_slideshow` (`id_now_slideshow`),
ADD CONSTRAINT `PREFIX_now_slideshow_lang_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `PREFIX_lang` (`id_lang`);
