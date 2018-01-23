<?php
include"header.php";
?>
<center>
<div class="box">
<div class="box-head">
  <h3>Chequee Reffer</h3>
</div>
      <?php
if(isset($_POST['update']))
{
	$sql = "UPDATE `chequee`
			SET 
			`for`='".strtoupper($_POST['bill'])."' WHERE id='".$_POST['id']."';";	
	$mysqlwb->execute($sql);
	?>
      <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
        <h4 class="alert-heading">Mapped Successfully!</h4>
      </div>
      <?php
}
?>
<table class="table table-striped table-bordered">
<thead>
  <tr>
    <th>Sno</th>
    <th>For Bill</th>
    <th>Recieved Date</th>
    <th>From</th>
    <th>Chequee No</th>
    <th>Amount</th>
    <th>Bank / Branch</th>
    <th>Status</th>
    <th></th>
  </tr>
</thead>
<?php
	  $sql = "SELECT  * FROM `chequee` where `for`='' and `is`=1;";
	  $rst = $mysqlwb->execute($sql);
	  while($r = mysqli_fetch_array($rst[0]))
	  {
		  ?>
<form action="#" method="post">
  <input type="hidden" name="id" value="<?php echo $r['id'] ?>" >
  <tr>
    <td style="text-align:center"><?php echo ++$sno; ?></td>
    <td><input type="text" name="bill" ></td>
    <td style="text-align:center"><?php echo $r['recieved_date'] ?></td>
    <td><?php 
			  	$sql = "select name from customers where id='".$r['c_id']."'";
				$cus = $mysqlwb->execute($sql);
				$cus = mysqli_fetch_array($cus[0]);
				echo $cus[0];
			  ?></td>
    <td style="text-align:center"><?php echo $r['cheque_no'] ?></td>
    <td style="text-align:right"><?php echo $mysqlwb->currency($r['amount']) ?></td>
    <td style="text-align:center"><?php echo $r['bank_name']." / ".$r['branch'] ?></td>
    <td><?php echo $r['status'] ?></td>
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