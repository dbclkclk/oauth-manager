/* CREATE TABLE IF NOT EXISTS  `#__techstack_student` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `dateofbirth` date NOT NULL DEFAULT '0000-00-00',
  `city` varchar(255) NOT NULL,
  `usstate` varchar(255) NOT NULL,
  `zipcode` varchar(255) NOT NULL,
  `userid` INT(11)  NOT NULL ,
   `relationship` VARCHAR(255) NOT NULL,
   PRIMARY KEY (`id`),
   FOREIGN KEY (`userid`) REFERENCES `#__users` (`id`)
   ON UPDATE CASCADE 
   ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS  `#__techstack_test` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `test_name` varchar(100) NOT NULL,
  `remarks` varchar(50) NOT NULL,
    `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS  `#__techstack_section` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `section_id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `grade` varchar(50) NOT NULL,
   `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `testid` int(11) UNSIGNED NOT NULL,
   PRIMARY KEY (`id`),
   FOREIGN KEY (`testid`) REFERENCES `#__techstack_test` (`id`)
   ON UPDATE CASCADE 
   ON DELETE CASCADE
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS  `#__techstack_question` (
  
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  
  `name` varchar(100) NOT NULL,
   `type` int(11) NOT NULL,
  `description` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `sectionid` int(11) UNSIGNED NOT NULL,
   PRIMARY KEY (`id`),
   FOREIGN KEY (`sectionid`) REFERENCES `#__techstack_section` (`id`)
   ON UPDATE CASCADE 
   ON DELETE CASCADE
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS  `#__techstack_answer` (
  
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
   `correct` boolean NOT NULL DEFAULT 0,
  `name` varchar(100) NOT NULL,

  `description` text NOT NULL,

  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `questionid` int(11) UNSIGNED NOT NULL,
   PRIMARY KEY (`id`),
   FOREIGN KEY (`questionid`) REFERENCES `#__techstack_question` (`id`)
   ON UPDATE CASCADE 
   ON DELETE CASCADE
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



ALTER TABLE `#__techstack_test` ADD `grade` varchar(50);

ALTER TABLE `#__techstack_section` DROP COLUMN `grade`;


/*!40101 SET @saved_cs_client     = @@character_set_client */
/*!40101 SET character_set_client = utf8 */

/*
CREATE TABLE IF NOT EXISTS  `#__techstack_student_test` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` int(11) UNSIGNED NOT NULL,
  `test_id` int(11) UNSIGNED NOT NULL,
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_index` (`test_id`,`student_id`),
  FOREIGN KEY (`test_id`) REFERENCES `#__techstack_test`(`id`),
  FOREIGN KEY (`student_id`) REFERENCES `#__techstack_student`(`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */




