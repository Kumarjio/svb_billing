<?php
error_reporting(0);
ini_set('display_errors',0);
session_start();
include"classes/mysql.php";
if ($handle = opendir('migration')) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
			$sql = "SELECT * FROM `migration` WHERE `file` = '".$entry."'";
			$rst = $mysql->execute($sql);
			if(mysqli_num_rows($rst[0])==0){
				include_once("migration/$entry");
				$sql = "INSERT INTO `billing`.`migration` (`file`) VALUES ('".$entry."');";
				$mysql->execute($sql);
			}
        }
    }
    closedir($handle);
}
if($_GET['out']==1)
{
	session_destroy();
}
else
{
	if($_SESSION['allowed'] == 'allowed')
	{
		ob_start();
		header("location:home.php");
		?>
<script type="text/javascript">
		window.location =  "home.php";
		</script>
<?php
		ob_flush();
	}
	else 
	{
		session_destroy();
	}
}


?>
<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
<meta charset="utf-8">
<title>Spiffy Soft Solutions</title>

<!-- CSS styles -->
<link rel='stylesheet' type='text/css' href='css/huraga-red.css'>

<!-- Fav and touch icons -->
<link rel="shortcut icon" href="img/spiffy.png">
</head>
<body>

<!-- Main page container -->
<section class="container login" role="main">
  <center>
    <h1><a href="index.php">
    
    <?php
	$sql = "select d.name from shop_detail as d where d.`is`=1";
	$result = $mysql->execute($sql);
	$shop_detail = mysqli_fetch_array($result[0]);
	echo $shop_detail['name'];
	?>
    </a></h1>
  </center>
  <div class="data-block">
    <form method="post" action="home.php">
      <fieldset>
        <div class="control-group">
          <label class="control-label" for="uname">Username</label>
          <div class="controls">
            <input id="uname" type="text" placeholder="Your username" name="uname">
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="pass">Password</label>
          <div class="controls">
            <input id="pass" type="password" placeholder="Password" name="pass">
          </div>
        </div>
        <?php
		if($_GET['out']==2)
		{?>
        <p><a href="#" class="pull-right"><small>* Please Enter Correct Username and Password</small></a></p>
        <?php } ?>
        <?php
		if($_GET['out']==3)
		{?>
        <p><a href="#" class="pull-right"><small>* You are Not Allowed to work in this time</small></a></p>
        <?php } ?>
      </fieldset><br>
      <center>
          <button class="btn btn-large btn-inverse btn-alt" type="submit"> Log in </button>
         </center><br>
      <p><a href="http://www.spiffysofts.com" class="pull-right"  style="text-decoration:blink">
      <small> Under <img src="img/spiffy.png" width="5%">piffy Care, 
          CopyRights &copy; <?php echo date('Y') ?>&nbsp;&nbsp;</small></a></p>
    </form>
  </div>
</section>
</body>
</html>
