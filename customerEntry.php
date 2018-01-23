<?php
include_once"header.php";
?>
<?php
if(isset($_POST['addCustomer']))
{
	$customer_name=$_POST['customer_name'];
	$customer_email=$_POST['customer_email'];
	$customer_phone=$_POST['customer_phone'];
	$date_of_birth=$_POST['date_of_birth'];
	$wedding_day=$_POST['wedding_day'];
	$customer_phone=$_POST['customer_phone'];
	$customer_address=$_POST['customer_address'];
	$tin_no = $_POST['customer_tin'];
	$cst_no = $_POST['customer_cst'];
	$gst_no = $_POST['customer_gst'];
	$cst_date = $_POST['customer_cst_date'];
	$photo="";
	$ip=$_SESSION['ip'];
	$query="INSERT INTO `customers` 
				(`name`,`gender`, `email`, `dob`,`wedding_day`, `phone`, `photo`,`address`,`tin_no`,`cst_no`,`cst_date`,`gst_no`,`discount`) 
			VALUES 
				('$customer_name','".$_POST['gender']."','$customer_email', '$date_of_birth','$wedding_day','$customer_phone','$photo','$customer_address',
	'".$tin_no."','".$cst_no."','".$cst_date."','".$gst_no."','".floatval($discount)."');
	SELECT LAST_INSERT_ID();";
	$r=$mysql->execute($query);
	$res=mysqli_fetch_array($r[0]);
	$lid=$res[0];
	if($_FILES['customer_photo']['error']<=0)
	{
	
				$filename = $res['name'].".".$_FILES["customer_photo"]["type"];
				$ext = explode("/",$filename);
				$ext = $ext[1];
	move_uploaded_file( $_FILES["customer_photo"]["tmp_name"],
	"img/customer_images/c" .$lid.".".$ext);
	$sql = "update customers set `photo`='img/customer_images/c".$lid.".".$ext."' where id=".$lid.";";
	$mysql->execute($sql);	
	}
	if($_GET['doy'])
	{
		?>
        <script type="text/javascript">
		//var address = <?php //echo $customer_address ?>;
		//address = address.replace(/\n\r?/g, '<br />');
        window.opener.setCustomer(<?php echo $lid ?>,'<?php echo $customer_name ?>','<?php echo $customer_phone ?>','<?php echo $discount ?>');
        </script>
        <?php
	}
}
?>
    <div class="container-fluid">
      <div class="content">
        <center>
          <div class="box"  style="width:60%;">
            <div class="box-head">
              <h3>Customer Creation</h3>
              <?php
			  if($billing)
			  {
				  ?>
                  <span style="color:#000; font-size:15px; font-weight:bold" onClick="closeCustomer()">X</span>
                  <script type="text/javascript">
                  function closeCustomer()
                  {
                     
                  }
                  </script>
              	<?php
			  }
			  ?>
            </div>
            <div class="box-content">
              <form  class="form-horizontal" method="post" enctype="multipart/form-data">
               	<table border="0" width="100%" class="table table-striped">
                <tr>
                <th><label for="dealer_name" class="control-label">Name</label></th>
                <td>
                    <input type="text" name="customer_name" id="customer_name" class='text'>
                </td>
                </tr>
                <tr><th>
                  <label for="email" class="control-label">E-Mail</label>
                </th>
                <td>
                    <input type="text" name="customer_email" id="customer_email" class='text'>
                </td></tr>
                <tr><th>
                  <label for="dealer_phone" class="control-label">Phone</label>
                </th><td>
                    <input type="text" name="customer_phone" id="customer_phone" class='text'>
                </td></tr>
                <tr><th>
                  <label for="dealer_tin" class="control-label">Tin no</label>
                </th><td>
                    <input type="text" name="customer_tin" id="customer_tin" class='text'>
                </td></tr>
                <tr><th>
                  <label for="dealer_cst" class="control-label">CST no</label>
                </th><td>
                    <input type="text" name="customer_cst" id="customer_cst" class='text'>
                </td></tr>
                <tr><th>
                  <label for="dealer_cst_date" class="control-label">CST Date</label>
                </th><td>
                    <input type="text" name="customer_cst_date" id="customer_cst_date" class='datepick'>
                </td></tr>
				<tr><th>
                  <label for="dealer_gst" class="control-label">GST no</label>
                </th><td>
                    <input type="text" name="customer_gst" id="customer_gst" class='text'>
                </td></tr>
                <tr><th>
                  <label for="file2" class="control-label">Upload Photo</label>
                </th><td>
                    <input type="file" name="customer_photo" id="file2" class='uniform'>
                </td></tr>
                <tr>
                <th>
                  <label for="datemask" class="control-label">Date Of Birth</label>
                </th>
                <td>
                    <input type="text" name="date_of_birth" id="datepicker" class='datepick text' >
                </td></tr>
                 <tr>
                  <th><label for="radio2" class="control-label">Gender</label></th>
                  <th> <input type="radio" name="gender" id="radio" value="male">
                    &nbsp;Male&nbsp;&nbsp;
                    <input type="radio" name="gender" value="female">
                    &nbsp;Female </th>
                </tr>
                <tr><th>
                  <label for="wedding_day" class="control-label">Weeding Day</label>
                </th><td>
                    <input type="text" name="wedding_day" id="wedding_day" class='datepick text'>
                </td></tr>
                <tr><th>
                  <label for="customer_address" class="control-label">Address</label>
                </th><td>
                    <textarea name="customer_address"></textarea>
                </td></tr>
                <tr><td colspan="2" style="text-align:center">
                  <input type="submit" class='btn btn-blue4' value="Add" name="addCustomer">
                </td></tr>
                </table>
              </form>
            </div>
          </div>
        </center>
      </div>
    </div>
<?php 
include_once"footer.php";
?>