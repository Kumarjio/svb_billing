<?php
$sql = "CREATE TABLE `migration` (
		`id` INT(11) NOT NULL AUTO_INCREMENT,
		`file` VARCHAR(50) NULL DEFAULT NULL,
		`it` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`),
		UNIQUE INDEX `file` (`file`)
	)
	ENGINE=InnoDB;
";
$mysql->execute($sql);
$sql ="INSERT INTO `menu` (`role`, `title`, `pagtit`, `titimg`, `name`, `url`, `class`, `img`, `o1`, `o2`, `o3`, `o4`, `o5`, `is`) VALUES ('1', 'Production', 'Payment', '', 'Payment', '#', 'icon-certificate', '<img src=\"img/icons/fugue/arrow-000-small.png\" />', 6, 9, 6, 1, 1, 1);
INSERT INTO `menu` (`role`, `title`, `pagtit`, `titimg`, `name`, `url`, `class`, `img`, `o1`, `o2`, `o3`, `o4`, `o5`, `is`) VALUES ('1', 'Production', 'Cash Pay', '', 'Cash Pay', 'wb_bill_payment.php', 'icon-certificate', '<img src=\"img/icons/fugue/arrow-000-small.png\" />', 6, 9, 6, 2, 1, 1);
INSERT INTO `menu` (`role`, `title`, `pagtit`, `titimg`, `name`, `url`, `class`, `img`, `o1`, `o2`, `o3`, `o4`, `o5`, `is`) VALUES ('1', 'Production', 'Payment Detail', '', 'Payment Detail', 'wb_bill_payment_detail.php', 'icon-certificate', '<img src=\"img/icons/fugue/arrow-000-small.png\" />', 6, 9, 6, 3, 1, 1);
INSERT INTO `menu` (`role`, `title`, `pagtit`, `titimg`, `name`, `url`, `class`, `img`, `o1`, `o2`, `o3`, `o4`, `o5`, `is`) VALUES ('1', 'Production', 'Payment Removal', '', 'Payment Removal', 'wb_bill_payment_remove.php', 'icon-certificate', '<img src=\"img/icons/fugue/arrow-000-small.png\" />', 6, 9, 6, 4, 1, 1);
INSERT INTO `menu` (`role`, `title`, `pagtit`, `titimg`, `name`, `url`, `class`, `img`, `o1`, `o2`, `o3`, `o4`, `o5`, `is`) VALUES ('1', 'Production', 'Pending Report', '', 'Pending Report', 'wb_bill_payment_pending.php', 'icon-certificate', '<img src=\"img/icons/fugue/arrow-000-small.png\" />', 6, 9, 6, 5, 1, 1);
INSERT INTO `menu` (`role`, `title`, `pagtit`, `titimg`, `name`, `url`, `class`, `img`, `o1`, `o2`, `o3`, `o4`, `o5`, `is`) VALUES ('1', 'Production', 'Chequee', '', 'Chequee', '#', 'icon-envelope', '<img src=\"img/icons/fugue/arrow-000-small.png\" />', 6, 9, 6, 6, 1, 1);
INSERT INTO `menu` (`role`, `title`, `pagtit`, `titimg`, `name`, `url`, `class`, `img`, `o1`, `o2`, `o3`, `o4`, `o5`, `is`) VALUES ('1', 'Production', 'Entry', '', 'Entry', 'wb_chequee_new.php', 'icon-envelope', '<img src=\"img/icons/fugue/arrow-000-small.png\" />', 6, 9, 6, 6, 2, 1);
INSERT INTO `menu` (`role`, `title`, `pagtit`, `titimg`, `name`, `url`, `class`, `img`, `o1`, `o2`, `o3`, `o4`, `o5`, `is`) VALUES ('1', 'Production', 'Map Bill To Chequee', '', 'Map Bill To Chequee', 'wb_chequee_mapping.php', 'icon-resize-horizontal', '<img src=\"img/icons/fugue/arrow-000-small.png\" />', 6, 9, 6, 6, 3, 1);
INSERT INTO `menu` (`role`, `title`, `pagtit`, `titimg`, `name`, `url`, `class`, `img`, `o1`, `o2`, `o3`, `o4`, `o5`, `is`) VALUES ('1', 'Production', 'Status Update', '', 'Status Update', 'wb_chequee_choose.php', 'icon-magnet', '<img src=\"img/icons/fugue/arrow-000-small.png\" />', 6, 9, 6, 6, 4, 1);
INSERT INTO `menu` (`role`, `title`, `pagtit`, `titimg`, `name`, `url`, `class`, `img`, `o1`, `o2`, `o3`, `o4`, `o5`, `is`) VALUES ('1', 'Production', 'Reffer', '', 'Reffer', 'wb_chequee_check.php', 'icon-info-sign', '<img src=\"img/icons/fugue/arrow-000-small.png\" />', 6, 9, 6, 6, 5, 1);
INSERT INTO `menu` (`role`, `title`, `pagtit`, `titimg`, `name`, `url`, `class`, `img`, `o1`, `o2`, `o3`, `o4`, `o5`, `is`) VALUES ('1', 'Production', 'Demand Draft', '', 'Demand Draft', '#', 'icon-envelope', '<img src=\"img/icons/fugue/arrow-000-small.png\" />', 6, 9, 6, 7, 1, 1);
INSERT INTO `menu` (`role`, `title`, `pagtit`, `titimg`, `name`, `url`, `class`, `img`, `o1`, `o2`, `o3`, `o4`, `o5`, `is`) VALUES ('1', 'Production', 'Demand Draft New', '', 'New Entry', 'wb_dd_entry.php', 'icon-envelope', '<img src=\"img/icons/fugue/arrow-000-small.png\" />', 6, 9, 6, 7, 2, 1);
INSERT INTO `menu` (`role`, `title`, `pagtit`, `titimg`, `name`, `url`, `class`, `img`, `o1`, `o2`, `o3`, `o4`, `o5`, `is`) VALUES ('1', 'Production', 'Demand Draft Status', '', 'Status', 'wb_dd_status.php', 'icon-envelope', '<img src=\"img/icons/fugue/arrow-000-small.png\" />', 6, 9, 6, 7, 3, 1);
INSERT INTO `menu` (`role`, `title`, `pagtit`, `titimg`, `name`, `url`, `class`, `img`, `o1`, `o2`, `o3`, `o4`, `o5`, `is`) VALUES ('1', 'Production', 'Reports', '', 'Reports', '#', 'icon-envelope', '<img src=\"img/icons/fugue/arrow-000-small.png\" />', 6, 9, 6, 8, 1, 1);
INSERT INTO `menu` (`role`, `title`, `pagtit`, `titimg`, `name`, `url`, `class`, `img`, `o1`, `o2`, `o3`, `o4`, `o5`, `is`) VALUES ('1', 'Production', 'Statement', '', 'Statement', 'wb_report_customer_payment.php', 'icon-envelope', '<img src=\"img/icons/fugue/arrow-000-small.png\" />', 6, 9, 6, 8, 2, 1);
";		
$mysql->execute($sql);
?>