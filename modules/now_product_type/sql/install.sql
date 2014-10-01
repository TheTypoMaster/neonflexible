SET NAMES 'utf8';

CREATE TABLE IF NOT EXISTS `PREFIX_now_product_type` (
  `id_now_product_type` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_shop` int(10) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_now_product_type`),
  KEY `id_shop` (`id_shop`)
) ENGINE=ENGINE_TYPE;

ALTER TABLE `PREFIX_now_product_type`
ADD CONSTRAINT `PREFIX_now_product_type_ibfk_1` FOREIGN KEY (`id_shop`) REFERENCES `PREFIX_shop` (`id_shop`);


CREATE TABLE IF NOT EXISTS `PREFIX_now_product_type_lang` (
  `id_now_product_type` int(10) unsigned NOT NULL,
  `id_lang` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `button_name` varchar(255) NOT NULL,
  KEY `id_now_product_type` (`id_now_product_type`, `id_lang`),
  KEY `id_lang` (`id_lang`)
) ENGINE=ENGINE_TYPE;

ALTER TABLE `PREFIX_now_product_type_lang`
ADD CONSTRAINT `PREFIX_now_product_type_lang_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `PREFIX_lang` (`id_lang`),
ADD CONSTRAINT `PREFIX_now_product_type_lang_ibfk_1` FOREIGN KEY (`id_now_product_type`) REFERENCES `PREFIX_now_product_type` (`id_now_product_type`);

CREATE TABLE IF NOT EXISTS `PREFIX_now_product_type_product` (
  `id_now_product_type_product` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_now_product_type` int(10) unsigned NOT NULL,
  `id_product` int(10) unsigned NOT NULL,
  `id_shop` int(10) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_now_product_type_product`),
  KEY `id_shop` (`id_shop`),
  KEY `id_now_product_type` (`id_now_product_type`),
  KEY `id_product` (`id_product`)
) ENGINE=ENGINE_TYPE;

ALTER TABLE `PREFIX_now_product_type_product`
ADD CONSTRAINT `PREFIX_now_product_type_product_ibfk_1` FOREIGN KEY (`id_shop`) REFERENCES `PREFIX_shop` (`id_shop`),
ADD CONSTRAINT `PREFIX_now_product_type_product_ibfk_2` FOREIGN KEY (`id_now_product_type`) REFERENCES `PREFIX_now_product_type` (`id_now_product_type`),
ADD CONSTRAINT `PREFIX_now_product_type_product_ibfk_3` FOREIGN KEY (`id_product`) REFERENCES `PREFIX_product` (`id_product`);