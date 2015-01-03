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
  `day_min` int(10) NOT NULL DEFAULT '0',
  `day_max` int(10) NOT NULL DEFAULT '0',
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

CREATE TABLE IF NOT EXISTS `PREFIX_now_holidays` (
  `id_now_holidays` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_shop` int(10) unsigned NOT NULL,
  `type` enum('public_holidays','holidays') NOT NULL,
  `evenment_name` varchar(255) NOT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `preparation` tinyint(1) NOT NULL DEFAULT '0',
  `shipping` tinyint(1) NOT NULL DEFAULT '0',
  `delivery` tinyint(1) NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_now_holidays`)
) ENGINE=ENGINE_TYPE;



INSERT INTO `PREFIX_now_holidays` (`id_now_holidays`, `id_shop`, `type`, `evenment_name`, `date_start`, `date_end`, `preparation`, `shipping`, `delivery`, `date_add`, `date_upd`) VALUES
  (1, 0, 'public_holidays', 'Jour de l''an 2016', '2016-01-01 00:00:00', '2016-01-01 00:00:00', 1, 1, 1, NOW(), NOW()),
  (2, 0, 'public_holidays', 'Lundi de Pâques', '2015-04-06 00:00:00', '2015-04-06 00:00:00', 1, 1, 1, NOW(), NOW()),
  (3, 0, 'public_holidays', 'Fête du Travail', '2015-05-01 00:00:00', '2015-05-01 00:00:00', 0, 0, 0, NOW(), NOW()),
  (4, 0, 'public_holidays', '8 Mai 1945', '2015-05-08 00:00:00', '2015-05-08 00:00:00', 1, 1, 1, NOW(), NOW()),
  (5, 0, 'public_holidays', 'Jeudi de l''Ascension', '2015-05-14 00:00:00', '2015-05-14 00:00:00', 1, 1, 1, NOW(), NOW()),
  (6, 0, 'public_holidays', 'Lundi de Pentecôte', '2015-05-25 00:00:00', '2015-05-25 00:00:00', 1, 1, 1, NOW(), NOW()),
  (7, 0, 'public_holidays', 'Fête Nationale', '2015-07-14 00:00:00', '2015-07-14 00:00:00', 1, 1, 1, NOW(), NOW()),
  (8, 0, 'public_holidays', 'Assomption', '2015-08-15 00:00:00', '2015-08-15 00:00:00', 1, 1, 1, NOW(), NOW()),
  (9, 0, 'public_holidays', 'La Toussaint', '2015-11-01 00:00:00', '2015-11-01 00:00:00', 1, 1, 1, NOW(), NOW()),
  (10, 0, 'public_holidays', 'Armistice', '2015-11-11 00:00:00', '2015-11-11 00:00:00', 1, 1, 1, NOW(), NOW()),
  (11, 0, 'public_holidays', 'Noël', '2015-12-25 00:00:00', '2015-12-25 00:00:00', 1, 1, 1, NOW(), NOW());