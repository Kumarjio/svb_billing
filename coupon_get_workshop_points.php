<?php
include "classes/mysql.php";
if($_REQUEST['id']>0){
	$sql = "select * from workshop where `id`=".$_REQUEST['id'];
	$rst = $mysql->execute($sql);
	$r = mysqli_fetch_array($rst[0]);
	echo $r['available_point']; 
}
?>
