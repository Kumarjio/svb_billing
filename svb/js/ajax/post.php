<?php
session_start();
// Error reporting
error_reporting(E_ALL^E_NOTICE);

require "../../classes/mysql.php";

// Checking whether all input variables are in place:
if(!is_numeric($_POST['zindex']) || !isset($_POST['author']) || !isset($_POST['body']) || !in_array($_POST['color'],array('yellow','green','blue')))
die("0");

if(ini_get('magic_quotes_gpc'))
{
	// If magic_quotes setting is on, strip the leading slashes that are automatically added to the string:
	$_POST['author']=stripslashes($_POST['author']);
	$_POST['body']=stripslashes($_POST['body']);
}

// Escaping the input data:

$author = strip_tags($_POST['author']);
$body = strip_tags($_POST['body']);
$color = ($_POST['color']);
$zindex = (int)$_POST['zindex'];
/* Inserting a new record in the notes DB: */
$rst = $mysql->execute('INSERT INTO notes (user,text,name,color,xyz)
				VALUES ("'.$_SESSION['user_id'].'","'.$body.'","'.$author.'","'.$color.'","35x35x'.$zindex.'");
				select LAST_INSERT_ID()');
$r = mysqli_fetch_array($rst[0]);
echo $r[0];

?>