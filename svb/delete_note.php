<?php
error_reporting(E_ALL^E_NOTICE);
require "data.php";
$id = $_POST['id'];
$query = $DBObject->qarg(0,"UPDATE notes SET status='0' WHERE id=".$id);
$rst = $DBObject->execute_return(0);
echo "1";
?>