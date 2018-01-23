<?php
include "header.php";
?>
<?php
if(isset($_POST['editDealer']))
{
	$id=$_POST['id'];
	$dealer_name=$_POST['dealer_name'];
	$email=$_POST['email'];
	$dealer_phone=$_POST['dealer_phone'];
	$company_name=$_POST['company_name'];
	$comany_phone=$_POST['company_phone'];
	$company_address=$_POST['company_address'];
	$photo="";
	$ip=$_SESSION['ip'];
$query="UPDATE `dealer` SET `dealer_name`='$dealer_name', `email`='$email', `dealer_phone`='$dealer_phone',`gender`='".$_POST['gender']."', `company_name`='$company_name', `company_phone`='$comany_phone', `company_address`='$company_address', `it`=NOW() WHERE  `id`=$id LIMIT 1;";
$mysql->execute($query);
if ($_FILES["photo"]["error"] > 0)
	{
	}
	else
	{
		$filename = $_FILES["photo"]["type"];
			$ext = explode("/",$filename);
			$ext = $ext[1];
move_uploaded_file( $_FILES["photo"]["tmp_name"],
"img/dealer_images/d" .$lid.".".$ext);
$sql = "update dealer set `photo`='img/dealer_images/d".$lid.".".$ext."' where id=".$id.";";
$mysql->execute($sql);	
}
?>
    <center>
      <br>
      <a class="btn btn-primary" data-toggle="modal" href="#myModal" id="view_detail" ></a>
      <div class="modal hide" id="myModal">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h3>
            <?php $dealer_name ?>
          </h3>
        </div>
        <div class="modal-body">
        <h1>User Updated Successfully</h1>
        </div>
        <div class="modal-footer"><form action="dealerdetails.php" method="post"><input type="hidden" name="id" value="<?php echo $_POST['id'] ?>">
        <button type="submit" style="border:0px" class="btn">Close</button></form>
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
$bQuery="select * from dealer where id=".$_POST['id'].";";
$result = $mysql->execute($bQuery);
$dealer = mysqli_fetch_array($result[0]);
?>
<div class="container-fluid">
  <div class="content">
    <center>
    <div class="box" style="width:50%;">
      <div class="box-head">
        <h3><?php echo $dealer['dealer_name']; ?></h3>
      </div>
      <div class="box-content">
        <form action="dealer_edit.php" class="form-horizontal" method="post" enctype="multipart/form-data">
          <input name="id" type="hidden" value="<?php echo $dealer['id']; ?>">
                <div class="control-group">
                  <label for="dealer_name" class="control-label">Name</label>
                  <div class="controlsx`">
             <input type="text" name="dealer_name" id="dealer_name" class='text' value="<?php echo $dealer['dealer_name'] ?>" />
                  </div>
                </div>
                <div class="control-group">
                  <label for="email" class="control-label">E-Mail</label>
                  <div class="controlsx`">
                    <input type="text" name="email" id="email" class='text'  value="<?php echo $dealer['email'] ?>" />
                  </div>
                </div>
                <div class="control-group">
                  <label for="dealer_phone" class="control-label">Phone</label>
                  <div class="controlsx">
                    <input type="text" name="dealer_phone" id="dealer_phone" class='mask_phone' value="<?php echo $dealer['dealer_phone'] ?>">
                  </div>
                </div>
                <div class="control-group">
                  <label for="file2" class="control-label">Upload Photo</label>
                  <div class="controlsx">
                    <input type="file" name="photo" id="file2" class='uniform' value="<?php echo $dealer['photo'] ?>">
                  </div>
                </div>
                <div class="control-group">
                    <label for="radio2" class="control-label">Gender</label>
                    <div class="controls" style="width:200px; padding-left:65px;">
                      <input type="radio" name="gender" id="radio" value="male" <?php if($dealer['gender']=="male") echo"checked" ?> >
                      &nbsp;Male&nbsp;&nbsp;
                      <input type="radio" name="gender" value="female" <?php if($dealer['gender']=="female") echo"checked" ?>>
                      &nbsp;Female </div>
                  </div>
                <div class="control-group">
                  <label for="company_name" class="control-label">Company Name</label>
                  <div class="controlsx`">
                    <input type="text" name="company_name" id="company_name" class='text' value="<?php echo $dealer['company_name'] ?>">
                  </div>
                </div>
                <div class="control-group">
                  <label for="company_phone" class="control-label">Company Phone</label>
                  <div class="controlsx">
                    <input type="text" name="company_phone" id="company_phone" class='mask_phone' value="<?php echo $dealer['company_phone'] ?>">
                  </div>
                </div>
                <div class="control-group">
                  <label for="company_address" class="control-label">Company Address</label>
                  <div class="controlsx">
                    <textarea name="company_address"><?php echo $dealer['company_address'] ?></textarea>
                  </div>
                </div>
                
                <div class="form-actions">
            <input type="submit" class='btn btn-blue4' value="UPDATE" name="editDealer">
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