<?php
$sql ="
INSERT INTO `menu` (`role`, `title`, `pagtit`, `titimg`, `name`, `url`, `class`, `img`, `o1`, `o2`, `o3`, `o4`, `o5`, `is`) VALUES ('0', '', 'Password Change', '', '', 'quatation_printing.php', '', '', 0, 2, 1, 1, 1, 1);
INSERT INTO `menu` (`role`, `title`, `pagtit`, `titimg`, `name`, `url`, `class`, `img`, `o1`, `o2`, `o3`, `o4`, `o5`, `is`) VALUES ('1', 'Billing', 'New Quatation', '', 'New', 'quatation.php', 'icon-filter', '<img src=\"img/icons/fugue/arrow-000-small.png\" />', 2, 9, 2, 1, 1, 1);
INSERT INTO `menu` (`role`, `title`, `pagtit`, `titimg`, `name`, `url`, `class`, `img`, `o1`, `o2`, `o3`, `o4`, `o5`, `is`) VALUES ('1', 'Billing', 'Quatation Lock/ Unlock Status', '', 'Lock/ Unlock Status', 'quatation_lock.php', 'icon-info-sign', '<img src=\"img/icons/fugue/arrow-000-small.png\" />', 2, 9, 6, 1, 1, 1);
INSERT INTO `menu` (`role`, `title`, `pagtit`, `titimg`, `name`, `url`, `class`, `img`, `o1`, `o2`, `o3`, `o4`, `o5`, `is`) VALUES ('1', 'Billing', 'Quatation Search', '', 'Search', 'quatation_search.php', 'icon-info-sign', '<img src=\"img/icons/fugue/arrow-000-small.png\" />', 2, 9, 5, 1, 1, 1);
INSERT INTO `menu` (`role`, `title`, `pagtit`, `titimg`, `name`, `url`, `class`, `img`, `o1`, `o2`, `o3`, `o4`, `o5`, `is`) VALUES ('1', 'Billing', 'Quatation Print View', '', 'Print View', 'quatation_print_view.php', 'icon-info-sign', '<img src=\"img/icons/fugue/arrow-000-small.png\" />', 2, 9, 4, 1, 1, 1);
INSERT INTO `menu` (`role`, `title`, `pagtit`, `titimg`, `name`, `url`, `class`, `img`, `o1`, `o2`, `o3`, `o4`, `o5`, `is`) VALUES ('1', 'Billing', 'Quatation Confirmation', '', 'Confirmation', 'quatation_confirm.php', 'icon-info-sign', '<img src=\"img/icons/fugue/arrow-000-small.png\" />', 2, 9, 3, 1, 1, 1);
INSERT INTO `menu` (`role`, `title`, `pagtit`, `titimg`, `name`, `url`, `class`, `img`, `o1`, `o2`, `o3`, `o4`, `o5`, `is`) VALUES ('1', 'Billing', 'Quatation', '', 'Quatation', '#', 'icon-filter', '<img src=\"img/icons/fugue/arrow-000-small.png\" />', 2, 9, 1, 1, 1, 1);
";		
$mysql->execute($sql);
$sql = "CREATE TABLE `quatation` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`bill_no` VARCHAR(50) NOT NULL DEFAULT '0',
	`customer_id` INT(11) NOT NULL DEFAULT '0',
	`biller_id` INT(11) NOT NULL DEFAULT '0',
	`date` DATE NOT NULL,
	`tot_products` INT(11) NOT NULL DEFAULT '1',
	`tax` FLOAT NOT NULL DEFAULT '0',
	`tax_amnt` FLOAT NOT NULL DEFAULT '0',
	`charges_amnt` FLOAT NOT NULL DEFAULT '0',
	`actual_amount` FLOAT NOT NULL DEFAULT '0',
	`bill_amount` FLOAT NOT NULL DEFAULT '0',
	`recieved_amount` FLOAT NOT NULL DEFAULT '0',
	`returned_amount` FLOAT NOT NULL DEFAULT '0',
	`round_off` FLOAT NOT NULL DEFAULT '0',
	`discount_per` FLOAT NOT NULL DEFAULT '0',
	`discount` FLOAT NOT NULL DEFAULT '0',
	`discount_reason` VARCHAR(500) NOT NULL,
	`lock` INT(11) NOT NULL DEFAULT '0',
	`trans_close` INT(11) NOT NULL DEFAULT '0',
	`status` CHAR(2) NOT NULL DEFAULT 's',
	`flow` VARCHAR(50) NOT NULL,
	`cancel` INT(10) NOT NULL DEFAULT '0',
	`cancel_reason` TEXT NOT NULL,
	`print_count` INT(11) NOT NULL DEFAULT '0',
	`last_time` TIMESTAMP NULL DEFAULT NULL,
	`confirmed_bill_no` VARCHAR(50) NULL DEFAULT NULL,
	`confirmed_time` DATETIME NULL DEFAULT NULL,
	`it` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`ip` VARCHAR(50) NOT NULL,
	`is` INT(10) NULL DEFAULT '1',
	PRIMARY KEY (`id`),
	UNIQUE INDEX `bill_no` (`bill_no`),
	INDEX `customer_id` (`customer_id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=MyISAM
ROW_FORMAT=DYNAMIC
AUTO_INCREMENT=722
;
CREATE TABLE `quatation_charges` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`bill_no` VARCHAR(50) NULL DEFAULT '0',
	`c_id` VARCHAR(50) NULL DEFAULT '0',
	`value` VARCHAR(50) NULL DEFAULT '0',
	`rate_status` INT(11) NULL DEFAULT '0',
	`rate` FLOAT NULL DEFAULT '0',
	`it` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`ip` VARCHAR(50) NULL DEFAULT NULL,
	`is` INT(10) NULL DEFAULT '1',
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=MyISAM
ROW_FORMAT=DYNAMIC
AUTO_INCREMENT=1441
;
CREATE TABLE `quatation_kot` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`bill_no` VARCHAR(50) NOT NULL DEFAULT '0',
	`kot` INT(11) NOT NULL DEFAULT '0',
	`product_id` INT(10) NOT NULL DEFAULT '0',
	`product_name` VARCHAR(100) NOT NULL DEFAULT '0',
	`qty` FLOAT NOT NULL DEFAULT '0',
	`type` VARCHAR(50) NOT NULL DEFAULT '0',
	`status` INT(11) NOT NULL DEFAULT '0',
	`it` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`ip` VARCHAR(50) NOT NULL,
	`is` INT(10) NULL DEFAULT '1',
	PRIMARY KEY (`id`),
	INDEX `product_id` (`product_id`),
	INDEX `bill_no` (`bill_no`)
)
COLLATE='latin1_swedish_ci'
ENGINE=MyISAM
ROW_FORMAT=DYNAMIC
AUTO_INCREMENT=11342
;
CREATE TABLE `quatation_order` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`bill_no` VARCHAR(50) NOT NULL DEFAULT '0',
	`kot` INT(11) NOT NULL DEFAULT '0',
	`pid` INT(10) NOT NULL DEFAULT '0',
	`product_id` INT(10) NOT NULL DEFAULT '0',
	`product_name` VARCHAR(100) NOT NULL DEFAULT '0',
	`qty` FLOAT NOT NULL DEFAULT '0',
	`type` VARCHAR(50) NOT NULL DEFAULT '0',
	`vat` FLOAT NOT NULL DEFAULT '0',
	`unit_price` FLOAT NOT NULL DEFAULT '0',
	`actual_amnt` FLOAT NOT NULL DEFAULT '0',
	`recieved_amnt` FLOAT NOT NULL DEFAULT '0',
	`discount` FLOAT NOT NULL DEFAULT '0',
	`stock_reduction` FLOAT NOT NULL DEFAULT '0',
	`it` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`ip` VARCHAR(50) NOT NULL,
	`is` INT(10) NULL DEFAULT '1',
	PRIMARY KEY (`id`),
	INDEX `product_id` (`product_id`),
	INDEX `bill_no` (`bill_no`),
	INDEX `pid` (`pid`),
	INDEX `kot` (`kot`)
)
COLLATE='latin1_swedish_ci'
ENGINE=MyISAM
ROW_FORMAT=DYNAMIC
AUTO_INCREMENT=11373
;
DROP TABLE IF EXISTS `quatation_orders`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` VIEW `quatation_orders` AS select `b`.`id` AS `bid`,`o`.`id` AS `oid`,`b`.`bill_no` AS `bill_no`,`o`.`kot` AS `kot`,`b`.`customer_id` AS `customer_id`,`b`.`biller_id` AS `biller_id`,`b`.`date` AS `date`,`b`.`tot_products` AS `tot_products`,`b`.`tax_amnt` AS `tax_amnt`,`b`.`charges_amnt` AS `charges_amnt`,`b`.`bill_amount` AS `bill_amount`,`b`.`actual_amount` AS `actual_amount`,`b`.`recieved_amount` AS `recieved_amount`,`b`.`returned_amount` AS `returned_amount`,`b`.`round_off` AS `round_off`,`b`.`discount_per` AS `discount_per`,`b`.`discount` AS `discount`,`b`.`discount_reason` AS `discount_reason`,`b`.`lock` AS `lock`,`b`.`trans_close` AS `trans_close`,`b`.`confirmed_bill_no` AS `confirmed_bill_no`,`b`.`confirmed_time` AS `confirmed_time`,`b`.`status` AS `status`,`o`.`product_id` AS `product_id`,`p`.`pid` AS `product_code`,`o`.`pid` AS `pid`,`p`.`name` AS `product_name`,`p`.`type` AS `product_type`,`o`.`qty` AS `qty`,`o`.`type` AS `type`,`o`.`unit_price` AS `unit_price`,`o`.`vat` AS `vat`,`o`.`actual_amnt` AS `oactual`,`o`.`recieved_amnt` AS `orecieved`,`o`.`discount` AS `odiscount`,`b`.`it` AS `it` from ((`quatation` `b` join `quatation_order` `o`) join `product` `p`) where ((`o`.`bill_no` = `b`.`bill_no`) and (`p`.`id` = `o`.`pid`) and (`b`.`is` = 1) and (`o`.`is` = 1)) ;
CREATE TABLE `quatation_print` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`bill_no` VARCHAR(50) NULL DEFAULT '0',
	`type` INT(11) NULL DEFAULT '0' COMMENT '0-original,1-duplicate',
	`inserted_user` INT(11) NULL DEFAULT '0',
	`it` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`ip` VARCHAR(50) NULL DEFAULT NULL,
	`is` INT(10) NULL DEFAULT '1',
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=MyISAM
ROW_FORMAT=DYNAMIC
;
CREATE TABLE `quatation_que` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`user` INT(10) NULL DEFAULT '0',
	`bill_no` VARCHAR(10) NULL DEFAULT '0',
	`ok` INT(10) NULL DEFAULT '0',
	`re_use` INT(10) NULL DEFAULT '0',
	`deque_time` DATETIME NULL DEFAULT NULL,
	`it` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`is` INT(10) NULL DEFAULT '1',
	PRIMARY KEY (`id`),
	UNIQUE INDEX `bill_no` (`bill_no`)
)
COLLATE='latin1_swedish_ci'
ENGINE=MyISAM
ROW_FORMAT=DYNAMIC
AUTO_INCREMENT=988
;
INSERT INTO `locks` (`id`, `for`, `lock`, `it`, `ip`, `is`) VALUES (2, 'quatation', 0, '2013-04-24 07:36:00', NULL, 1);
INSERT INTO `quatation_que` (`user`, `bill_no`, `ok`, `re_use`) VALUES (4, 'QA0000', 1, 0);
INSERT INTO `quatation_kot` (`id`, `bill_no`, `kot`, `product_id`, `product_name`, `qty`, `type`, `status`, `it`, `ip`, `is`) VALUES (1, '0', 1, 0, '0', 0, '0', 0, '2017-05-10 08:59:54', '', 1);

CREATE TABLE `bill_sale_return` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`bill_no` VARCHAR(50) NOT NULL,
	`pid` INT(11) NOT NULL DEFAULT '0',
	`qty` INT(11) NOT NULL DEFAULT '0',
	`unit_price` FLOAT NOT NULL DEFAULT '0',
	`discount` FLOAT NOT NULL DEFAULT '0',
	`recieved_price` FLOAT NOT NULL DEFAULT '0',
	`tax_amount` FLOAT NOT NULL DEFAULT '0',
	`net_amount` FLOAT NOT NULL DEFAULT '0',
	`it` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`is` INT(11) NULL DEFAULT '1',
	PRIMARY KEY (`id`),
	INDEX `bill_no` (`bill_no`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;
INSERT INTO `billing`.`menu` (`role`, `title`, `pagtit`, `titimg`, `name`, `url`, `class`, `img`, `o1`, `o2`, `o3`, `o4`, `o5`) VALUES ('1', 'Reports', 'Sale Return', 'icon-list-alt  icon-white', 'Sale Return', 'report_sales_return.php', 'icon-pencil', '<img src=\"img/icons/fugue/arrow-000-small.png\" />', '8', '2', '9', '1', '1');


";
$mysql->execute($sql);
?>