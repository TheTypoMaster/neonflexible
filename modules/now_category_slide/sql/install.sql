SET NAMES 'utf8';

CREATE TABLE IF NOT EXISTS `PREFIX_now_category_slide` (
  `id_now_category_slide` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_shop` int(10) unsigned NOT NULL,
  `id_category` int(10) unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `position` int(10) unsigned NOT NULL,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_now_category_slide`),
  KEY `id_shop` (`id_shop`),
  KEY `id_category` (`id_category`)
) ENGINE=ENGINE_TYPE;

ALTER TABLE `PREFIX_now_category_slide`
ADD CONSTRAINT `PREFIX_now_category_slide_ibfk_1` FOREIGN KEY (`id_shop`) REFERENCES `PREFIX_shop` (`id_shop`),
ADD CONSTRAINT `PREFIX_now_category_slide_ibfk_2` FOREIGN KEY (`id_category`) REFERENCES `PREFIX_category` (`id_category`);
