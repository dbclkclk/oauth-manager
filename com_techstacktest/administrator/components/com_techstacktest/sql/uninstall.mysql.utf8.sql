DROP TABLE IF EXISTS `#__teachpro_student_subject`;

DROP TABLE IF EXISTS `#__teachpro_payment`;


DROP TABLE IF EXISTS `#__teachpro_answer`;

DROP TABLE IF EXISTS `#__teachpro_question`;




DROP TABLE IF EXISTS `#__teachpro_goal`;

DROP TABLE IF EXISTS `#__teachpro_student`;

DROP TABLE IF EXISTS `#__teachpro_subject`;



DELETE FROM `#__content_types` WHERE (type_alias LIKE 'com_teachpro.%');