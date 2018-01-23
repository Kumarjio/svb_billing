<?php
session_start();
if($_GET['out'])
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
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Aravind Electricals</title>
<meta name="description" content="">

<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1">

<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/jquery.fancybox.css">
<link rel="stylesheet" href="css/login.css">
</head>
<body class='login_body'>
	<div class="wrap">
		<center><br><font style="font-size:19px; font-weight:bold;">ARAVIND ELECTRICALS</font><br><br><h4>Welcome to the login page</h4></center>
		<form action="home.php" autocomplete="off" method="post">
		<div class="login">
			<div class="email" style="text-align:right">
				<label for="user">UserName</label><div class="email-input"><div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span><input type="text" id="uname" name="uname" class="text"></div></div>
			</div>
			<div class="pw" style="text-align:right">
				<label for="pw">Password</label><div class="pw-input"><div class="input-prepend"><span class="add-on"><i class="icon-lock"></i></span><input type="password" id="pass" name="pass"></div></div>
			</div><br>
			<!--<div class="remember">
				<label class="checkbox">
					<input type="checkbox" value="1" name="remember"> Remember me on this computer
				</label>
			</div>-->
            <div style="text-align:center">
			<button class="btn btn-red4"><b>Login</b></button>
		</div>
		</div>
		<center>
        <div style="text-align:right" class="spiffy">
        
        <font face="Times New Roman, Times, serif" color="#FFFFFF" size="3">
            <a href="http://www.spiffysofts.com" style="color:#000; text-decoration:blink">
            Under <img src="img/spiffy.png" width="5%">piffy Care, 
            CopyRights &copy; <?php echo date('Y') ?>&nbsp;&nbsp;</a>
            </font>
        </div>
        </center>
		</form>
	</div>
</body>
</html>