<?php
include_once"header.php";
?>
 <center>
    <div class="box" style="width:50%;">
      <div class="box-head">
        <h3>User Edit</h3>
      </div>
      <div class="box-content">
<?php
if(isset($_POST['user_id']))
{
	$id = $_POST['user_id'];
	$name = $_POST['name'];
	$phone = $_POST['phone'];
	$mail = $_POST['email'];
	$address = $_POST['address'];
	$ip = $_SESSION['ip'];
	$updated_user = $_SESSION['user_id'];
	$brach_id = $_POST['branch'];
	$date_of_birth = $_POST['date_of_birth'];
	$gender = $_POST['gender'];
	$material_status = $_POST['material_status'];
	$employee_type = $_POST['employee_type'];
	$salary = $_POST['salary'];
	$bio = $_POST['bio'];
	$sql = "update profile set name='".$name."', phone_number = '".$phone."', e_mail='".$mail."', address='".$address."', date_of_birth='".$date_of_birth."', type='".$employee_type."', salary='".$salary."', gender='".$gender."',material_status='".$material_status."',`bio_id`='".$bio."' where id=".$id." ;";
	$rst1 = $mysql->execute($sql);
	if ($_FILES["photo"]["error"] > 0)
	{
	}
	else
	{
		$filename = $_FILES["photo"]["type"];
		$ext = explode("/",$filename);
		$ext = $ext[1];
		 move_uploaded_file( $_FILES["photo"]["tmp_name"],
		"img/profile_images/" .$id.".".$ext);
		$sql = "update profile set `image_source`='img/profile_images/".$id.".".$ext."' where id=".$id.";";;
		$rst1 = $mysql->execute($sql);	
	}
?>
<div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
      <h4 class="alert-heading">Successfully Updated!</h4>
    </div>
    <?php
	ob_start();
	header("location:user_details.php");
	?>
	<script type="text/javascript">
	window.location="user_details.php";
	</script>
	<?php
	ob_flush();
}
else if(isset($_POST['id']))
{
$bQuery="select * from profile where id=".$_POST['id'].";";
$result = $mysql->execute($bQuery);
$profile = mysqli_fetch_array($result[0]);
?>
        <form action="user_edit.php" class="form-horizontal" method="post" enctype="multipart/form-data">
        <input name="user_id" type="hidden" value="<?php echo $profile['id']; ?>">
          <div class="control-group">
            <label for="datemask" class="control-label">Name</label>
            <div class="controls">
              <input type="text" name="name" id="datemask" class='text' value="<?php echo $profile['name'] ?>">
            </div>
          </div>
          <div class="control-group">
            <label for="phonemask" class="control-label">Phone</label>
            <div class="controls">
              <input type="text" name="phone" id="phonemask" class='' value="<?php echo $profile['phone_number'] ?>">
            </div>
          </div>
          <div class="control-group">
            <label for="email2" class="control-label">Email</label>
            <div class="controls">
              <input type="text" name="email" id="email2"  value="<?php echo $profile['e_mail'] ?>">
            </div>
          </div>
          <div class="control-group">
            <label for="serialnumber" class="control-label">Address</label>
            <div class="controls">
              <textarea name="address"><?php echo $profile['address'] ?></textarea>
            </div>
          </div>
          <div class="control-group">
            <label for="datemask" class="control-label">Date Of Birth</label>
            <div class="controls">
              <input type="text" name="date_of_birth" id="datepicker" class='datepick' value="<?php echo $profile['date_of_birth'] ?>">
            </div>
          </div>
          <div class="control-group">
            <label for="radio2" class="control-label">Gender</label>
            <div class="controls" style="width:200px; padding-left:65px;">
              <input type="radio" name="gender" id="radio" value="male" <?php if($profile['gender']=="male") echo"checked" ?> >
              &nbsp;Male&nbsp;&nbsp;
              <input type="radio" name="gender" value="female" <?php if($profile['gender']=="female") echo"checked" ?>>
              &nbsp;Female </div>
          </div>
          <div class="control-group">
            <label for="selsear" class="control-label">marital Status</label>
            <div class="controls">
              <select name="material_status" id="selsear" class='cho'>
                <option <?php if($profile['material_status']=="Single") echo"selected" ?> >Single</option>
                <option <?php if($profile['material_status']=="Married") echo"selected" ?> >Married</option>
                <option <?php if($profile['material_status']=="Widdow") echo"selected" ?> >Widdow</option>
              </select>
            </div>
          </div>
            <div class="control-group">
              <label for="file2" class="control-label">Upload Photo</label>
              <div class="controls">
              <img src="<?php echo $profile['image_source'] ?>" width="40%" alt="">
                <input type="file" name="photo" id="file2" class='uniform'>
              </div>
            </div> 
          <div class="control-group">
            <label for="bio" class="control-label">Biometric Id</label>
            <div class="controls">
              <input type="text" name="bio" id="bio" class='text' value="<?php echo $profile['bio_id'] ?>">
            </div>
          </div>
          <div class="control-group">
            <label for="selsear1" class="control-label">Employee Type</label>
            <div class="controls">
              <select name="employee_type" id="selsear1" class='cho'>
              <option>select</option>
              <?php
			  $sql = "select ds.id, ds.name 'desName',ds.salary from designation as ds where ds.`is`=1";
			  $rst = $mysql->execute($sql);
			  foreach($rst as $desg)
			  {
				  while($des = mysqli_fetch_array($desg))
				  { 
				  ?>
					<option value="<?php echo $des['id']; ?>" <?php if($profile['type'] == $des['id']) echo "selected" ?>><?php echo $des['desName']; ?></option>
				   <?php
				  }
			  }
			   ?>
              </select>
            </div>
          </div>
          <div class="control-group">
            <label for="twoicons" class="control-label">Salary</label>
            <div class="controls">
              <div class="input-prepend input-append"> <span class="add-on"><b><del>&#2352;</del></b></span>
                <input type="text" id="salary" class='input-square' name="salary" id="twoicons" value="<?php echo $profile['salary']; ?>" />
                <span class="add-on">.00</span> </div>
            </div>
          </div>
          <div class="form-actions">
            <input type="submit" class='btn btn-primary'>
          </div>
        </form>
      </div>
<?php
}
else
{
	ob_start();
	header("location:home.php");
	?>
	<script type="text/javascript">
	window.location="home.php";
	</script>
	<?php
	ob_flush();
}
?>
</div>
    </center>
<?php
include_once"footer.php";
?>