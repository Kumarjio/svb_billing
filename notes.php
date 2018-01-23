<?php
include"header.php";
?>
    <?php
$sql = '';
if(isset($_POST['remove']))
{
	$sql ="update notes set `is`=0 where `id`='".$_POST['id']."';";
}
$sql .="SELECT * FROM notes where `user`='".$_SESSION['user_id']."' AND `is`=1 ORDER BY id DESC";
$query = $mysql->execute($sql);
$notes = '';
$left='';
$top='';
$zindex='';

while($row=mysqli_fetch_array($query[0]))
{
	list($left,$top,$zindex) = explode('x',$row['xyz']);
	$notes.= '
	<div class="note '.$row['color'].'" style="left:'.$left.'px;top:'.$top.'px;z-index:'.$zindex.';">
		'.htmlspecialchars($row['text']).'
		<div class="author">'.htmlspecialchars($row['name']).'</div>
		<div style="position:absolute; right:5px;top:5px;">
		<form action="#" method="POST">
		<input type="hidden" name="id" value="'.$row['id'].'" >
		<button type="submit" name="remove" style="background-color:transparent;border:0px;" onClick="confirmDel(event)">
		<b>X</b></button>
		</form>
		</div>
		<span class="data">'.$row['id'].'</span>
	</div>';
}
?>
    <link rel="stylesheet" type="text/css" href="styles.css" />
    <link rel="stylesheet" type="text/css" href="js/fancybox/jquery.fancybox-1.2.6.css" media="screen" />
    <script type="text/javascript" src="js/fancybox/jquery.fancybox-1.2.6.pack.js"></script> 
    <script type="text/javascript" src="js/script.js"></script>
    </head>
    <body>
    <div id="main"> <a id="addButton" class="green-button" href="add_note.php">Add a note</a> <?php echo $notes?> </div>
    <?php 
include"footer.php";
?>
