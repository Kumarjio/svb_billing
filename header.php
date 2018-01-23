<?php 
error_reporting(0);
ini_set('display_errors',0);
include"authendicate.php";
setlocale(LC_MONETARY,"en_US");
$curFile = end(explode("/",$_SERVER["SCRIPT_NAME"]));
if($curFile=='production_stock_wb.php' || $curFile=='billing.php' || $curFile=='purchase.php' || $curFile=='wastage.php' || $curFile=='store_purchase.php' || $curFile=='store_stock_request.php'  || $curFile=='bill_printing.php' || $curFile == 'billing_wb.php'  || $curFile=='bill_printing_wb.php' || $curFile=='quatation.php' || $curFile=='quatation_printing.php')
{
	$dataOnly =1;
}
if(isset($_POST['production_show'])){
	$_SESSION['production_table'] = "production_process_stock_wb";	
}
if(isset($_POST['production_hide'])){
	$_SESSION['production_table'] = "production_process_stock";	
}
if($_SESSION['production_table']== ''){
	$_SESSION['production_table'] = "production_process_stock";	
}
if($_GET['doy']==1)
	$dataOnly =1;
if(!in_array($curFile,$_SESSION['files']))
{
	header("location:home.php");
	?>
<script type="text/javascript">
		window.location="home.php";
	</script>
<?php
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<?php
if(isset($_GET['theme']))
{
	$mysql->execute("update profile set theme='".$_GET['theme']."' where `id`='".$_SESSION['user_id']."';");
	$_SESSION['theme']=$_GET['theme'];
}
include"css.php";
?>
<link rel="icon" href="img/spiffy.png">
<style type="text/css">
@media print{
body,a{
	color:#000;
}
.noprint{
	display:none;
}
.content .box {
    margin-top: 0px;
    border: none ;
    border-radius: 0px 0px 0px 0px;
    background: none repeat scroll 0% 0% rgb(255, 255, 255);
}
.box-head{
	display:none;
}
.breadcrumbs {
    height: 0px;
    border-right: 0px solid rgb(20, 69, 150);
    border-width: 0px 0px 0px;
    border-style: none solid solid;
    border-color: none;
    border-image: none;
    background: none ;
    box-shadow: none;
}
nav{
	display:none;
}
}
</style>
<title><?php echo $_SESSION['title'][$curFile]['page'] ?></title>
</head>
<?php
include"js.php";
?>

<body class="paper">
<?php
if(!$dataOnly)
{
?>
<div class="topbar noprint">
  <div class="container-fluid"> <a href="index.php" class='company'><font face="Times New Roman, Times, serif"> <img src="<?php echo $_SESSION['shop_logo'] ?>" style="height:30px; margin-top:-10px" > <?php echo strtoupper($_SESSION['shop_name']) ?> </font></a>&nbsp;&nbsp;
    <form action="#">
      <input class="searchBox" type="text" value="Search Here...">
      <div class="searchBoxResult"> </div>
    </form>
    <ul class='mini'>
      <li style="border:none; background-color:transparent;box-shadow:none"> <font face="Times New Roman, Times, serif" color="#FFFFFF" size="2"> <a href="http://www.spiffysofts.com" style="text-decoration:blink;color:#ccc">Under <img src="img/spiffy.png" width="20">piffy Care, CopyRights &copy; <?php echo date('Y') ?></a> </font> </li>
      <li class='dropdown dropdown-noclose supportContainer'> <a data-toggle="modal" data-backdrop="static" href="#stocklack"> <i class="icon-leaf  icon-white"></i><span class="label label-warning" style="text-decoration:blink" id="stock_count"></span></a> </li>
      <li class='dropdown pendingContainer'> <a data-toggle="modal" data-backdrop="static" href="#pendingorders"> <i class="icon-file  icon-white"></i><span class="label label-important" id="pendingOrders" style="text-decoration:blink"></span> </a> </li>
      <li class='dropdown messageContainer'> <a data-toggle="modal" data-backdrop="static" href="#notification"> <i class="icon-envelope   icon-white"></i><span class="label label-info" style="text-decoration:blink">0</span> </a> </li>
      <li class="dropdown messageContainer">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
      <img src="<?php echo $_SESSION['photo']; ?>" width="20" alt="" style="border:#144596 1px solid"> <?php echo $_SESSION['name']; ?> <i class="icon-cog  icon-white"></i></a>
      <nav>
        <ul class="dropdown-menu pull-right custom custom-dark">
          <li class="custom"> <a href="profile.php"> <i class="icon-user"></i> View Profile </a>
          <li class="custom"> <a href="user_profile.php"> <i class="icon-cog"></i> Change Password </a>
          <li> <a href="index.php?out=1"> <i class="icon-off"></i> Logout </a> </li>
          </li>
        </ul>
      </nav>
      </li>
      <li> </li>
    </ul>
  </div>
</div>
<?php
}
?>
<?php 
if($_GET['doy']!=1)
{
?>
<div class="breadcrumbs" >
<div class="container-fluid">
  <div class="navbar">
    <ul class="bread pull-left" style="padding-top:5px;">
      <li  class="dropdown"> <a href="home.php"><i class="icon-home icon-white"></i></a> </li>
    </ul>
    <nav>
      <?php include "menu.php"; ?>
    </nav>
  </div>
</div>
<?php
}
?>
<a class="btn btn-primary" data-toggle="modal" id="favouriteSet" href="#favorites" style="display:none">Launch Modal</a>
<div class="modal hide" id="favorites">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3>Choose Icon</h3>
  </div>
  <div class="modal-body">
    <table align="center" cellpadding="5" cellspacing="5">
      <tr>
        <th><strong>Name </strong></th>
        <td><input type="text" name="short_name" id="short_name"></td>
      </tr>
      <tr>
        <th valign="top"><strong>Icon </strong></th>
        <td><input type="hidden" name="short_icon" id="short_icon" value="donate.png">
          <div>
            <?php
		$dir  = "img\\icons\\essen\\32\\";
		$files1 = scandir($dir);
		foreach($files1 as $fl)
		{
			if(strstr($fl,".png") && file_exists("img\\icons\\essen\\32\\".$fl))
			{
			?>
            <img class="favoriteIcon" src="img/icons/essen/16/<?php echo $fl ?>" 
            onClick="setFavrIcon('<?php echo $fl ?>',$(this))" style="cursor:pointer;" />
            <?php
			}
		}
		?>
          </div></td>
      </tr>
    </table>
    </p>
  </div>
  <div class="modal-footer"> <a href="#" class="btn" data-dismiss="modal">Close</a> <a href="#" class="btn btn-blue4" data-dismiss="modal" onClick="setFavourite()">Add Favourite</a> </div>
</div>
<script type="text/javascript">
		function removeFav(e,id)
		{
			if (!e) var e = window.event;
			e.preventDefault();
			cnf = confirm('Sure To remove');
			if(cnf)
			{
			$.ajax({type:"POST",
					url:"removeFavourite.php",
					data:{"id":id},
					success: function(data){
					alert("Removed Successfully");
					location.reload();
				}
			});
			}
		}
</script>
<div class="style_switcher"><br><br>
  <ul class="quicktasks">
  <li>
  <form action="#" method="get" id="theme_form">
  Theme <select name="theme" onChange="$('#theme_form').submit()" style="width:100px">
  <option value='' <?php if($_SESSION['theme']=='') echo 'selected' ?> >Black</option>
  <option value='_blue' <?php if($_SESSION['theme']=='_blue') echo 'selected' ?> >Blue</option>
  </select>
  </form>
  </li>
    <?php if($curFile!='home.php' && in_array($curFile,$_SESSION['shortcuts']['file_name'])!=1){ ?>
    <li onClick="getFavrSettings()"> <font face="Times New Roman, Times, serif" color="#FFFFFF" size="2" style="width:60%;cursor:pointer"> <img src="img/icons/essen/16/plus.png"> Add Favorite</font> </li>
    <?php }?>
    <script type="text/javascript">
	  		function getFavrSettings()
			{
				$("#favouriteSet").click();
			}
			function setFavrIcon(val,id)
			{
				$(".favoriteIcon").css("border","");
				id.css("border","#000 2px solid");
				id.css("width","25px");
				$("#short_icon").val(val)
			}
            function setFavourite()
			{
				name = $("#short_name").val();
				icon = $("#short_icon").val();
				$.ajax({type:"POST",
						url:"setFavourite.php",
						data:{'file':'<?php echo $curFile ?>','name':name,'icon':icon},
						beforeSend: function(e){
						},
						success: function(e){
							if(e)
							{
								alert("Added successfully");
								$("#short_icon").val('donate.png');
								$("#short_name").val('');
							}
						}
				});
			}
            </script>
    <li style="width:80px;"><u>My Favorites</u></li>
    <li style="width:80px; word-break:break-strict" class="shortcuts" id="shortcut<?php echo $_SESSION['shortcuts']['id'][$id] ?>"> <a href="home.php"> <img src="img/icons/essen/16/home.png" /> <span><b>HOME</b> </span> </a> </li>
    <?php
			  foreach($_SESSION['shortcuts']['image'] as $id=>$img)
			  {
			?>
    <li style="width:80px; font-size:10px; word-break:break-strict" class="shortcuts" id="shortcut<?php echo $_SESSION['shortcuts']['id'][$id] ?>"> <a href="<?php echo $_SESSION['shortcuts']['file_name'][$id] ?>">
      <div style="position:absolute; margin-top:-5px; margin-left:-10px"> <span onClick="removeFav(event,<?php echo $_SESSION['shortcuts']['id'][$id] ?>)"><b>X</b></span></div>
      <?php
										echo str_replace("/32/","/16/",$img);
										?>
      <span><b>
      <?php 
										echo str_replace(" ","<br>",$_SESSION['shortcuts']['name'][$id]); 
										?>
      </b> </span> </a> </li>
    <?php
							}
			  ?>
  </ul>
</div>
<!-- sidebar --> 
<a href="javascript:void(0)" class="sidebar_switch  on_switch" title="Hide Sidebar"></a>
<div class="content">
