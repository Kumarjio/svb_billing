<?php
$sql ="
	ALTER TABLE `product`
		ADD COLUMN `gst` VARCHAR(200) NOT NULL AFTER `vat`,
		ADD COLUMN `hsm_code` VARCHAR(50) NOT NULL AFTER `pid`;
	
	ALTER TABLE `bill_order`
	ADD COLUMN `gst` VARCHAR(100) NOT NULL DEFAULT '0' AFTER `vat`,
	ADD COLUMN `gst_price` FLOAT NOT NULL DEFAULT '0' AFTER `gst`;
	
	
	ALTER ALGORITHM = UNDEFINED 
		 VIEW `bill_orders` AS 
			select `b`.`id` AS `bid`,`o`.`id` AS `oid`,`b`.`bill_no` AS `bill_no`,`o`.`kot` AS `kot`,`b`.`customer_id` AS `customer_id`,`b`.`biller_id` AS `biller_id`,`b`.`date` AS `date`,`b`.`tot_products` AS `tot_products`,`b`.`tax_amnt` AS `tax_amnt`,`b`.`charges_amnt` AS `charges_amnt`,`b`.`bill_amount` AS `bill_amount`,`b`.`actual_amount` AS `actual_amount`,`b`.`recieved_amount` AS `recieved_amount`,`b`.`returned_amount` AS `returned_amount`,`b`.`round_off` AS `round_off`,`b`.`discount_per` AS `discount_per`,`b`.`discount` AS `discount`,`b`.`discount_reason` AS `discount_reason`,`b`.`lock` AS `lock`,`b`.`trans_close` AS `trans_close`,`b`.`status` AS `status`,`o`.`product_id` AS `product_id`,`o`.`pid` AS `pid`,`p`.`pid` AS `product_code`,`p`.`hsm_code` AS `hsm_code`,`p`.`name` AS `product_name`,`o`.`qty` AS `qty`,`o`.`type` AS `type`,`o`.`unit_price` AS `unit_price`,`o`.`vat` AS `vat`,`o`.`gst` AS `gst`,`o`.`gst_price` AS `gst_price`,`o`.`actual_amnt` AS `oactual`,`o`.`recieved_amnt` AS `orecieved`,`o`.`discount` AS `odiscount`,`b`.`it` AS `it` from ((`bill` `b` join `bill_order` `o`) join billing.`product` `p`) where ((`o`.`bill_no` = `b`.`bill_no`) and (`p`.`id` = `o`.`pid`) and (`b`.`is` = 1) and (`o`.`is` = 1))  ;
";		
$mysql->execute($sql);

?>