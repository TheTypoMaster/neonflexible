SET NAMES 'utf8';

CREATE TABLE IF NOT EXISTS `PREFIX_now_delivery_time` (
  `id_now_delivery_time` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_shop` int(10) unsigned NOT NULL,
  `id_carrier` int(10) unsigned NOT NULL,
  `saturday_shipping` tinyint(1) NOT NULL DEFAULT '0',
  `sunday_shipping` tinyint(1) NOT NULL DEFAULT '0',
  `shipping_holidays` tinyint(1) NOT NULL DEFAULT '0',
  `saturday_delivery` tinyint(1) NOT NULL DEFAULT '0',
  `sunday_delivery` tinyint(1) NOT NULL DEFAULT '0',
  `delivery_holidays` tinyint(1) NOT NULL DEFAULT '0',
  `day_min` tinyint(1) NOT NULL DEFAULT '0',
  `day_max` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_now_delivery_time`)
) ENGINE=ENGINE_TYPE;


CREATE TABLE IF NOT EXISTS `PREFIX_now_delivery_time_lang` (
  `id_now_delivery_time` int(10) unsigned NOT NULL,
  `id_lang` int(10) unsigned NOT NULL,
  `description` TEXT DEFAULT NULL,
  `timeslot` varchar(255) NOT NULL,
  KEY `id_now_delivery_time` (`id_now_delivery_time`, `id_lang`),
  KEY `id_lang` (`id_lang`)
) ENGINE=ENGINE_TYPE;