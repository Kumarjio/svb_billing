<?php
error_reporting(E_ALL^E_NOTICE);
require "data.php";
if(!isset($_POST['author']) || !isset($_POST['body']) || !isset($_POST['color']))
die("0");
if(ini_get('magic_quotes_gpc'))
{
	$_POST['author']=addslashes($_POST['author']);
	$_POST['body']=addslashes($_POST['body']);
}
$author =addslashes($_POST['author']);
$body =addslashes($_POST['body']);
$color = mysql_real_escape_string($_POST['color']);
$zindex = (int)$_POST['zindex'];
$query = $DBObject->qarg(0,"UPDATE notes SET text='".$body."' ,name='".$author."',color='".$color."' WHERE id=".$_POST['id']);
$rst = $DBObject->execute_return(0);
echo '1';
?>