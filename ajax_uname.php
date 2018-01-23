<?php 
$val=$_POST['pval'];
include "classes/mysql.php";
$sql="select user_name from authendication where user_name='$val'";
$s=$mysql->execute($sql);
$r=mysqli_fetch_array($s[0]);
if($r['user_name']==$val)
echo "Not available";
else
echo "Available";
?>