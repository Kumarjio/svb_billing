<?php
include "header.php";
?>
  <?php
if(isset($_POST['editCustomer']))
{
	$id=$_POST['id'];
	$customer_name=$_POST['customer_name'];
	$customer_email=$_POST['customer_email'];
	$customer_phone=$_POST['customer_phone'];
	$date_of_birth=$_POST['date_of_birth'];
	$wedding_day=$_POST['wedding_day'];
	$customer_address=$_POST['customer_address'];
	$discount = $_POST['discount'];
	$photo="";
	$ip=$_SESSION['ip'];
	$query = "UPDATE  `customers` SET `name`='$customer_name', `email`='$customer_email', `dob`='$date_of_birth',`gender`='".$_POST['gender']."',`wedding_day`='$wedding_day', `phone`='$customer_phone', `address`='$customer_address', `it`=NOW(),`discount`='".floatval($discount)."',`tin_no`='".$_POST['customer_tin']."' WHERE  `id`=$id;";
	$mysql->execute($query);
	$r=$mysql->execute($sql);
if ($_FILES["photo"]["error"] > 0)
	{
	}
	else
	{
		$filename = $_FILES["photo"]["type"];
			$ext = explode("/",$filename);
			$ext = $ext[1];
move_uploaded_file( $_FILES["photo"]["tmp_name"],
"img/customer_images/c" .$lid.".".$ext);	
}
?>
  <center>
    <br>
    <a class="btn btn-primary" data-toggle="modal" href="#myModal" id="view_detail" ></a>
    <div class="modal hide" id="myModal">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>
          <?php $customer_name ?>
        </h3>
      </div>
      <div class="modal-body">
        <h1>User Updated Successfully</h1>
      </div>
      <div class="modal-footer">
        <form action="customerDetails.php" method="post">
          <input type="hidden" name="id" value="<?php echo $_POST['id'] ?>">
          <button type="submit" style="border:0px" class="btn">Close</button>
        </form>
      </div>
    </div>
  </center>
  <script type="text/javascript">
    $(document).ready(function(e) {
        $("#view_detail").click();
    });
    </script>
  <?php
}
else if(isset($_POST['id']))
{
$bQuery="select * from customers where id=".$_POST['id'].";";
$result = $mysql->execute($bQuery);
$customer = mysqli_fetch_array($result[0]);
?>
  <div class="container-fluid">
    <div class="content">
      <center>
        <div class="box" style="width:50%;">
          <div class="box-head">
            <h3><?php echo $customer['name']; ?></h3>
          </div>
          <div class="box-content">
            <form action="customer_edit.php" class="form-horizontal" method="post" enctype="multipart/form-data">
              <input name="id" type="hidden" value="<?php echo $customer['id']; ?>">
              <div class="control-group">
                <label for="name" class="control-label">Name</label>
                <div class="controlsx">
                  <input type="text" name="customer_name" id="customer_name" class='text' value="<?php echo $customer['name'] ?>" />
                </div>
              </div> 
              <div class="control-group">
                <label for="email" class="control-label">E-Mail</label>
                <div class="controlsx`">
                  <input type="text" name="customer_email" id="customer_email" class='text'  value="<?php echo $customer['email'] ?>" />
                </div>
              </div>
              <div class="control-group">
                <label for="phone" class="control-label">Phone</label>
                <div class="controlsx">
                  <input type="text" name="customer_phone" id="customer_phone" value="<?php echo $customer['phone'] ?>">
                </div>
              </div>
              <div class="control-group">
                <label for="dealer_tin" class="control-label">TIN no</label>
                <div class="controlsx">
                  <input type="text" name="customer_tin" id="customer_tin" class='text' value="<?php echo $customer['tin_no'] ?>">
                </div>
              </div>
              <div class="control-group">
                <label for="dealer_cst" class="control-label">CST no</label>
                <div class="controlsx">
                  <input type="text" name="customer_cst" id="customer_cst" class='text' value="<?php echo $customer['cst_no'] ?>">
                </div>
              </div>
              <div class="control-group">
                <label for="dealer_cst_date" class="control-label">CST Date</label>
                <div class="controlsx">
                  <input type="text" name="customer_cst_date" id="customer_cst_date" class='datepick' value="<?php echo $customer['cst_date'] ?>">
                </div>
              </div>
              <div class="control-group">
                <label for="file2" class="control-label">Upload Photo</label>
                <div class="controlsx">
                  <input type="file" name="photo" id="file2" class='uniform' value="<?php echo $customer['photo'] ?>">
                </div>
              </div>
              <div class="control-group">
                <label for="datemask" class="control-label">Date Of Birth</label>
                <div class="controls">
                  <input type="text" name="date_of_birth" id="datepicker" class='datepick' value="<?php echo $customer['dob'] ?>">
                </div>
              </div>
              <div class="control-group">
                <label for="radio2" class="control-label">Gender</label>
                <div class="controls" style="width:200px; padding-left:65px;">
                  <input type="radio" name="gender" id="radio" value="male" <?php if($customer['gender']=="male") echo"checked" ?> >
                  &nbsp;Male&nbsp;&nbsp;
                  <input type="radio" name="gender" value="female" <?php if($customer['gender']=="female") echo"checked" ?>>
                  &nbsp;Female </div>
              </div>
              <div class="control-group">
                <label for="wedding_day" class="control-label">Weeding Day</label>
                <div class="controls">
                  <input type="text" name="wedding_day" id="wedding_day" class='datepick' value="<?php echo $customer['wedding_day'] ?>">
                </div>
              </div>
              <div class="control-group">
                <label for="customer_addresss" class="control-label">Address</label>
                <div class="controlsx">
                  <textarea name="customer_address"><?php echo $customer['address'] ?></textarea>
                </div>
              </div>
              <div class="form-actions">
                <input type="submit" class='btn btn-blue4' onClick="formSub('form1Sub')" value="UPDATE" name="editCustomer">
              </div>
            </form>
          </div>
        </div>
      </center>
    </div>
  </div>
</div>
<?php
}
include "footer.php";
?>
