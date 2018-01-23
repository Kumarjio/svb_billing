<?php
include"header.php";
?>
    <div class="box">
      <div class="box-head">
        <h3>Stock Cancellation Request</h3>
      </div>
      <div class="box-content">
<?php
if(isset($_POST['send']))
{
	$sql = "INSERT INTO `purchase_cancel_request` 
		(`bill_no`, `reason`) VALUES 
		('".$_POST['bill_no']."', '".$_POST['reason']."');";
	$rst = $mysql->execute($sql);
	?>
    <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
      <h4 class="alert-heading">Request Send Successfully!</h4>
    </div>
    <?php	
}
?>
        <form action="#" class="form-horizontal" method="post" enctype="multipart/form-data">
          <div class="control-group">
            <label for="selsear2" class="control-label">Stock Entry No</label>
            <div class="controls">
              <input type="text" class="text" name="bill_no">
            </div>
          </div>  
          <div class="control-group">
            <label for="twoicons" class="control-label">Reason</label>
            <div class="controls">
              <div class="input-prepend input-append"> 
                <textarea type="text" name="reason" id="reason"></textarea> </div>
            </div>
          </div>
          <div class="form-actions">
            <input type="submit" name="send" value="Send" class="btn btn-blue4 btn-large">
          </div>
        </form>
      </div>
    </div>
<?php
include"footer.php";
?>