<?php
include_once"header.php";
?>
    <center>
    <div class="box" style="width:60%;">
      <div class="box-head">
        <h3>Chequee Status</h3>
      </div>
      <?php
if(isset($_POST['update']))
{
	$sql = "select * from `chequee` WHERE id='".$_POST['id']."';";
	$rst = $mysql->execute($sql);
	$chk = mysqli_fetch_array($rst[0]);
	$for = $chk['for'];
	if($for=='')
	{
		$message = 'For Bill No is not given';
	}
	$sql = '';
	if($_POST['status']=='credited')
	{
		$sql .="INSERT INTO `bill_payment` 
				(`bill_no`, `date`, `bill_amount`,`cur_balance` , `recieved`, `returned`,`paid`,`type`,`type_id`) 
				(select 
					bl.bill_no,
					'".$chk['recieved_date']."',
					bl.bill_amount,
					bl.bill_amount,
					'".$chk['amount']."',
					0,
					if(('".$chk['amount']."')!=bl.bill_amount,0,1),
					1 ,
					'".$_POST['id']."'
				from bill as bl where bl.bill_no='".$for."' LIMIT 1);";
		$sql .="update bill as p set p.trans_close=1 where p.bill_amount<=p.recieved_amount-p.returned_amount and p.bill_no='".$for."';";
	}
	$sql .= "UPDATE `chequee`
			SET 
			`status`='".$_POST['status']."' WHERE id='".$_POST['id']."';";
	$mysql->execute($sql);
	?>
      <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
        <h4 class="alert-heading">Successfully Updated!</h4>
      </div>
      <?php
}
else if(isset($_POST['status']))
{
$sql = "SELECT  * FROM `chequee` where `id` = '".$_POST['id']."';";
$rst = $mysql->execute($sql);
$chk = mysqli_fetch_array($rst[0])
?>
      <div class="box-content">
        <form action="#" class="form-horizontal" method="post" enctype="multipart/form-data">
        	<input type="hidden" name="id" value="<?php echo $_POST['id'] ?>" >
          <div class="control-group">
            <label for="status" class="control-label"><strong>Current Status</strong></label>
            <div class="controls">
              <select name="status" id="status" class='cho'>
              <option <?php if($chk['status']=='pending') echo 'selected' ?> >pending</option>
              <option <?php if($chk['status']=='send for collection') echo 'selected' ?> >send for collection</option>
              <option <?php if($chk['status']=='bounced') echo 'selected' ?> >bounced</option>
              <option <?php if($chk['status']=='credited') echo 'selected' ?> >credited</option>
              </select>
            </div>
          </div>
          <div class="form-actions">
            <input type="submit" name="update" class='btn btn-blue4' value="Update">
          </div>
        </form>
      </div>
<?php
}
else{
	?>
    No Details To View
    <?php
}
?>
    </div>
  </div>
  <?php
include_once"footer.php";
?>