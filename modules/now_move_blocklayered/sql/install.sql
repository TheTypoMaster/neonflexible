SET NAMES 'utf8';

ALTER TABLE  `PREFIX_feature_value` ADD  `position` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0';

UPDATE `PREFIX_feature_value` SET `position` = `id_feature_value`;