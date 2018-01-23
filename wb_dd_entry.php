<?php
include_once"header.php";
?>
    <center>
    <div class="box" style="width:60%;">
      <div class="box-head">
        <h3>Demand Draft Entry</h3>
      </div>
      <?php
if(isset($_POST['submit']))
{
	$sql = "INSERT INTO `demand_draft` 
			(`for`, `c_id`, `recieved_date`, `dd_no`, `amount`, `bank_name`, `branch`)				 
			VALUES 
			('".strtoupper($_POST['bill_no'])."','".$_POST['customer']."', '".$_POST['date']."', 
			'".$_POST['cheque_no']."', '".$_POST['amount']."', '".$_POST['bank']."', '".$_POST['branch']."');";	
	$sql .="INSERT INTO `bill_payment` 
				(`bill_no`, `date`, `bill_amount`,`cur_balance` , `recieved`, `returned`,`paid`,`type`,`type_id`) 
				(select 
					bl.bill_no,
					'".$_POST['date']."',
					bl.bill_amount,
					bl.bill_amount,
					'".$_POST['amount']."',
					0,
					if(('".$_POST['amount']."')!=bl.bill_amount,0,1),
					2 ,
					'".$_POST['id']."' 
				from bill as bl where bl.bill_no='".$_POST['bill_no']."' LIMIT 1);";
	$sql .="update bill as p set p.trans_close=1 where p.bill_amount<=p.recieved_amount-p.returned_amount and p.bill_no='".$_POST['bill_no']."';";
	$mysqlwb->execute($sql);
	?>
      <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
        <h4 class="alert-heading">Amount Successfully Credited!</h4>
      </div>
      <?php
}
?>
      <div class="box-content">
        <form action="#" class="form-horizontal" method="post" enctype="multipart/form-data">
          <div class="control-group">
            <label for="customer" class="control-label"><strong>Recieved From</strong></label>
            <div class="controls">
              <select name="customer" id="customer" class='cho'>
                <?php
				$sql = "select * from customers as c where c.`is`=1 ;";
				$rst = $mysqlwb->execute($sql);
				while($r = mysqli_fetch_array($rst[0]))
				{
				?>
                <option value="<?php echo $r['id'] ?>"><?php echo $r['name'] ?></option>
                <?php
				}
				?>
              </select>
            </div>
          </div>
          <div class="control-group">
            <label for="bill_no" class="control-label"><strong>Bill No.</strong></label>
            <div class="controls">
              <input type="text" name="bill_no" id="bill_no" class='text'>
            </div>
          </div>
          <div class="control-group">
            <label for="cheque_no" class="control-label"><strong>DD No.</strong></label>
            <div class="controls">
              <input type="text" name="cheque_no" id="cheque_no" class='text'>
            </div>
          </div>
          <div class="control-group">
            <label for="amount" class="control-label"><strong>Amount</strong></label>
            <div class="controls">
              <div class="input-prepend input-append"> <span class="add-on"><b><del>&#2352;</del></b></span>
                <input type="text" id="amount" class='input-square' name="amount" id="twoicons" />
                <span class="add-on">.00</span> </div>
            </div>
          </div>
          <div class="control-group">
            <label for="bank" class="control-label"><strong>Bank</strong></label>
            <div class="controls">
              <input type="text" name="bank" id="bank" class='required'>
            </div>
          </div>
          <div class="control-group">
            <label for="branch" class="control-label"><strong>Branch</strong></label>
            <div class="controls">
              <input type="text" name="branch" id="branch" class='required'>
            </div>
          </div>
          <strong></strong>
          <div class="control-group">
            <label for="date" class="control-label"><strong>DD Date</strong></label>
            <div class="controls">
              <input type="text" name="date" id="date" class='datepick'>
            </div>
          </div>
          <div class="form-actions">
            <input type="submit" name="submit" class='btn btn-blue4' value="Submit">
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php
include_once"footer.php";
?>