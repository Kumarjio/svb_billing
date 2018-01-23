<?php
include_once"header.php";
?>
    <center>
    <div class="box" style="width:60%;">
      <div class="box-head">
        <h3>Chequee Edit</h3>
      </div>
      <?php
if(isset($_POST['update']))
{
	$sql = "UPDATE `chequee`
			SET 
			`for`='".strtoupper($_POST['bill_no'])."', `c_id`='".$_POST['customer']."', `recieved_date`='".$_POST['date']."', 
			`cheque_no`='".$_POST['chequeeno']."', `amount`='".$_POST['amount']."'
			, `bank_name`='".$_POST['bank']."', `branch`='".$_POST['branch']."' WHERE id='".$_POST['id']."';";	
	$mysql->execute($sql);
	?>
      <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
        <h4 class="alert-heading">Successfully Updated!</h4>
      </div>
      <?php
}
elseif(isset($_POST['remove']))
{
	$sql = "UPDATE `chequee`
			SET 
			`is`=0 WHERE id='".$_POST['id']."';";	
	$mysql->execute($sql);
	?>
      <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
        <h4 class="alert-heading">Successfully Removed!</h4>
      </div>
      <?php
}
else if(isset($_POST['edit']))
{
$sql = "SELECT  * FROM `chequee` where `id` = '".$_POST['id']."';";
$rst = $mysql->execute($sql);
$chk = mysqli_fetch_array($rst[0])
?>
      <div class="box-content">
        <form action="#" class="form-horizontal" method="post" enctype="multipart/form-data">
        	<input type="hidden" name="id" value="<?php echo $_POST['id'] ?>" >
          <div class="control-group">
            <label for="customer" class="control-label"><strong>Recieved From</strong></label>
            <div class="controls">
              <select name="customer" id="customer" class='cho'>
                <?php
				$sql = "select * from customers as c where c.`is`=1 ;";
				$rst = $mysql->execute($sql);
				while($r = mysqli_fetch_array($rst[0]))
				{
				?>
                <option <?php if($chk['c_id']==$r['id']) echo 'selected'; ?> value="<?php echo $r['id'] ?>"><?php echo $r['name'] ?></option>
                <?php
				}
				?>
              </select>
            </div>
          </div>
          <div class="control-group">
            <label for="bill_no" class="control-label"><strong>Bill No.</strong></label>
            <div class="controls">
              <input type="text" name="bill_no" id="bill_no" class='text' value="<?php echo $chk['for'] ?>">
            </div>
          </div>
          <div class="control-group">
            <label for="chequeeno" class="control-label"><strong>Chequee No.</strong></label>
            <div class="controls">
              <input type="text" name="chequeeno" id="chequeeno" class='text' value="<?php echo $chk['cheque_no'] ?>">
            </div>
          </div>
          <div class="control-group">
            <label for="amount" class="control-label"><strong>Amount</strong></label>
            <div class="controls">
              <div class="input-prepend input-append"> <span class="add-on"><b><del>&#2352;</del></b></span>
                <input type="text" id="amount" class='input-square' name="amount" id="twoicons" value="<?php echo $chk['amount'] ?>" />
                <span class="add-on">.00</span> </div>
            </div>
          </div>
          <div class="control-group">
            <label for="bank" class="control-label"><strong>Bank</strong></label>
            <div class="controls">
              <input type="text" name="bank" id="bank" class='required' value="<?php echo $chk['bank_name'] ?>">
            </div>
          </div>
          <div class="control-group">
            <label for="branch" class="control-label"><strong>Branch</strong></label>
            <div class="controls">
              <input type="text" name="branch" id="branch" class='required' value="<?php echo $chk['branch'] ?>">
            </div>
          </div>
          <strong></strong>
          <div class="control-group">
            <label for="date" class="control-label"><strong>Recieved Date</strong></label>
            <div class="controls">
              <input type="text" name="date" id="date" class='datepick' value="<?php echo $chk['recieved_date'] ?>">
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