<?php
include"header.php";
?>
<center>
<div class="box">
<div class="box-head">
  <h3>Purchase Order Bill</h3>
</div>
      <?php
if(isset($_POST['update']))
{
	$sql = "UPDATE `purchase`
			SET 
			`pur_bill`='".strtoupper($_POST['bill'])."',`bill_date`='".$_POST['date']."' WHERE id='".$_POST['id']."';";	
	$mysql->execute($sql);
	?>
      <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
        <h4 class="alert-heading">Mapped Successfully!</h4>
      </div>
      <?php
}
?>
<table class="table table-striped table-bordered" style="width:10px; overflow:visible">
<thead>
  <tr>
    <th>Sno</th>
    <th>Purchase NO</th>
    <th>Amount</th>
    <th>Dealer Bill No</th>
    <th>Recieved Date</th>
    <th></th>
  </tr>
</thead>
<?php
	  $sql = "SELECT  * FROM `purchase` where `pur_bill`='' and `is`=1;";
	  $rst = $mysql->execute($sql);
	  while($r = mysqli_fetch_array($rst[0]))
	  {
		  ?>
<form action="#" method="post">
  <input type="hidden" name="id" value="<?php echo $r['id'] ?>" >
  <tr>
    <td style="text-align:center"><?php echo ++$sno; ?></td>
    <td style="text-align:center"><a href="purchase_detail.php?billno=<?php echo $r['bill_no'] ?>"><?php echo $r['bill_no'] ?></a></td>
    <td style="text-align:right"><?php echo $mysql->currency($r['bill_amount']) ?></td>
    <td><input type="text" name="bill" ></td>
    <td><input type="text" name="date" class="datepick" ></td>
    <td><input type="submit" name="update" value="Update" class="btn btn-blue4"></td>
  </tr>
</form>
<?php
	  }
	  ?>
</table>
</div>
</center>
<?php
include"footer.php";
?>