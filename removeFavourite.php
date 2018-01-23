<?php
session_start();
include"classes/mysql.php";
$id =  $_POST['id'];
$sql = "update `shortcuts` set `is`='0' WHERE  `id`=".$id." LIMIT 1;";
$mysql->execute($sql);
$id = array_search($id,$_SESSION['shortcuts']['id']);
unset($_SESSION['shortcuts']['id'][$id]);
unset($_SESSION['shortcuts']['image'][$id]);
unset($_SESSION['shortcuts']['name'][$id]);
unset($_SESSION['shortcuts']['file_name'][$id]);
echo '1';
?>