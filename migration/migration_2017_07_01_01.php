<?php
$sql ="
	ALTER TABLE `customers`
	ADD COLUMN `gst_no` VARCHAR(100) NULL DEFAULT NULL AFTER `cst_no`;
";		
$mysql->execute($sql);

?>