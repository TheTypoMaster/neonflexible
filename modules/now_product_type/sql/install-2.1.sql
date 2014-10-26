SET NAMES 'utf8';

CREATE TABLE IF NOT EXISTS `PREFIX_now_ideas_or_tips` (
  `id_product_1` int(10) unsigned NOT NULL,
  `id_product_2` int(10) unsigned NOT NULL,
  KEY `ideas_or_tips_product` (`id_product_1`, `id_product_2`)
) ENGINE=ENGINE_TYPE;