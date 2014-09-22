SET NAMES 'utf8';

CREATE TABLE IF NOT EXISTS `PREFIX_now_mea_home` (
  `id_now_mea_home` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_shop` int(10) unsigned NOT NULL,
  `id_product` int(10) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_now_mea_home`),
  KEY `id_shop` (`id_shop`),
  KEY `id_product` (`id_product`)
) ENGINE=ENGINE_TYPE;

ALTER TABLE `PREFIX_now_mea_home`
ADD CONSTRAINT `PREFIX_now_mea_home_ibfk_1` FOREIGN KEY (`id_shop`) REFERENCES `PREFIX_shop` (`id_shop`),
ADD CONSTRAINT `PREFIX_now_mea_home_ibfk_2` FOREIGN KEY (`id_product`) REFERENCES `PREFIX_product` (`id_product`);
