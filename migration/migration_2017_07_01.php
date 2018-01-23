<?php
$sql ="
	UPDATE `shop_detail` SET `tax`='18';
	UPDATE `bill_que` SET `ok`='1';	
	INSERT INTO `bill_que` (`user`, `bill_no`) VALUES ('5', 'AAF001');
";		
$mysql->execute($sql);

?>