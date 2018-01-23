<?php
session_start();
include"classes/mysql.php";
$file =  $_POST['file'];
$user = $_SESSION['user_id'];
$img ='<img src="img/icons/essen/32/'.$_POST['icon'].'" />';
$name = $_POST['name'];
$sql = "INSERT INTO `shortcuts` 
		(`user_id`, `image`, `name`, `file_name`) 
		VALUES 
		('".$user."', '".$img."', '".$name."', '".$file."');
		SELECT LAST_INSERT_ID()'lst' from `shortcuts`;";
$lst = $mysql->execute($sql);
$lst = mysqli_fetch_array($lst[0]);
$_SESSION['shortcuts']['id'][]=$lst[0];
$_SESSION['shortcuts']['image'][]=$img;
$_SESSION['shortcuts']['name'][]=$name;
$_SESSION['shortcuts']['file_name'][]=$file;
echo '1';
?>