<?php
include"header.php";
?>
<center>
<div class="box">
<div class="box-head">
  <h3>Chequee Reffer</h3>
</div>
<form action="#" method="post">
  <table align="center" cellspacing="10" cellpadding="10" class="table table-stripped table-bordered" style="width:150px; overflow:visible">
    <thead>
      <tr>
        <th>From</th>
        <th><input type="text" class="datepick input-small" name="from"></th>
        <th>To</th>
        <th><input type="text" class="datepick input-small" name="to"></th>
        <th><input type="submit" name="check" value="Check" class="btn btn-blue4"></th>
      </tr>
    </thead>
  </table>
</form>
<br>
<br>
<table class="table table-stripped table-bordered">
<thead>
  <tr>
    <th>Sno</th>
    <th>For</th>
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
$from = $_POST['from'];
if($_POST['to']=='')
	$to = $from;
else
	$to = $_POST['to'];
	  if($to!='')
		  $sql = "SELECT  * FROM `chequee` where `recieved_date` BETWEEN '".$from."' AND '".$to."' and `is`=1;";
	  else
	  	  $sql = "SELECT  * FROM `chequee` where `status` != 'credited' and `is`=1;";
	  $rst = $mysql->execute($sql);
	  while($r = mysqli_fetch_array($rst[0]))
	  {
		  ?>
<tr>
  <td style="text-align:center"><?php echo ++$sno; ?></td>
  <td style="text-align:center"><a href="bill_detail.php?billno=<?php echo $r['for'] ?>"><?php echo $r['for'] ?></a></td>
  <td style="text-align:center"><?php echo $r['recieved_date'] ?></td>
  <td style="text-align:center"><?php 
			  	$sql = "select name from customers where id='".$r['c_id']."'";
				$cus = $mysql->execute($sql);
				$cus = mysqli_fetch_array($cus[0]);
				echo $cus[0];
			  ?></td>
  <td style="text-align:center"><?php echo $r['cheque_no'] ?></td>
  <td style="text-align:right"><?php echo $mysql->currency($r['amount']) ?></td>
  <td style="text-align:center"><?php echo $r['bank_name']." / ".$r['branch'] ?></td>
  <td style="text-align:center"><?php echo $r['status'] ?></td>
  <td><?php 
  if($r['status']!='credited' && $r['status']!='bounced')
  {
  ?>
    <form action="chequee_edit.php" method="post">
      <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
      <input type="submit" name="edit" value="Edit" class="btn btn-blue4">
      <input type="submit" name="remove" value="Remove" class="btn btn-red4" onClick="cancelSubmit(event)">
    </form>
    <form action="chequee_status.php" method="post">
      <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
      <input type="submit" name="status" value="Status Update" class="btn btn-green4">
    </form>
    <?php
  }
  ?></td>
</tr>
<?php
	  }
	  ?>
</table>
</div>
</center>
<?php
include"footer.php";
?>