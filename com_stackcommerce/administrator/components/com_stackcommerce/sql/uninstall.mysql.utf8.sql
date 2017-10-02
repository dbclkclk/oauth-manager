DROP TABLE IF EXISTS `#__stackcommerce_api`;

DELETE FROM `#__content_types` WHERE (type_alias LIKE 'com_stackcommerce.%');