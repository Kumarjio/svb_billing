<!DOCTYPE HTML>
<html>
	<head>
<?php
error_reporting(0);
$file = explode("/",$_SERVER["SCRIPT_NAME"]);
$curFile = $file[count($file)-1];
if((!in_array($curFile,$_SESSION['files'])) || in_array($curFile,$_SESSION['restrict']))
{
	header("location:home.php");
	?>
    <script type="text/javascript">
		window.location="home.php";
	</script>
    <?php
}
?>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Admin</title>

		<script type="text/javascript" src="../js/jquery.js"></script>