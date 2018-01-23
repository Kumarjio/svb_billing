<?php
include"header.php"
?>
<form action="#" method="post">
<div class="box">
<div class="box-head"><h3>Income</h3></div>
<?php 
if(isset($_POST['submit']))
{
	$sql ="INSERT INTO `income` (`date`, `e_id`, `person`, `amount`, `reason`) VALUES ('".$_POST['date']."', ".$_POST['exp'].", '".$_POST['per']."', 
			".$_POST['amnt'].", '".$_POST['rea']."');";
	$mysql->execute($sql);
	?>
    <div class="alert alert-block alert-success">
							  <a class="close" data-dismiss="alert" href="#">×</a>
							  <h4 class="alert-heading">Added Successfully!</h4>
							</div>
    <?php
}
?>
<table align="center" cellpadding="10" cellspacing="10">
<thead>
<tr><th>Date</th><th><input type="text" class="datepick" name="date" ></th></tr>
<tr><th>Income Type</th><td>
<select name="exp" class="cho">
<?php
$sql = "select * from income_type where `is`=1;";
$rst = $mysql->execute($sql);
while($r= mysqli_fetch_array($rst[0]))
{
?>
<option value="<?php echo $r['id'] ?>"> <?php echo $r['name'] ?></option>
<?php
}
?>
</select>
</td></tr>
<tr><th>Amount</th><th><div class="controls">
              <div class="input-prepend input-append"> <span class="add-on"><b><del>&#2352;</del></b></span>
                <input type="text" class='input-square' name="amnt" id="twoicons" />
                <span class="add-on">.00</span> </div>
            </div></th></tr>
<tr><th>Person</th><th><input type="text" name="per" ></th></tr>
<tr><th>Reason</th><th><textarea name="rea"></textarea></th></tr>
<tr><th colspan="2" align="center"><input type="submit" name="submit" value="Submit" class="btn btn-blue4" ></th></tr>
</thead>
</table>
</div>
</form>
<?php
include"footer.php"
?>