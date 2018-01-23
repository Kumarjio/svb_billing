<?php
include_once"header.php";
?>
<?php
if(isset($_POST['name']))
{
	if(isset($_POST['name']))
	$name = $_POST['name'];
	if(isset($_POST['phone']))
	$phone = $_POST['phone'];
	if(isset($_POST['email']))
	$mail = $_POST['email'];
	if(isset($_POST['address']))
	$address = $_POST['address'];
	$ip = $_SESSION['ip'];
	if(isset($_POST['date_of_birth']))
	$date_of_birth = $_POST['date_of_birth'];
	if(isset($_POST['gender']))
	$gender = $_POST['gender'];
	if(isset($_POST['material_status']))
	$material_status = $_POST['material_status'];
	if(isset($_POST['employee_type']))
	$employee_type = $_POST['employee_type'];
	if(isset($_POST['salary']))
	$salary = $_POST['salary'];
	$bio = $_POST['bio'];
	if(count($_POST['emp_rghts'])>0)
	$emp_rghts = implode(",",$_POST['emp_rghts']);
	$sql = "set @lst='';";
	$sql .= "insert into profile(`name`, `phone_number`, `e_mail`, `address`, `date_of_birth`, `type`, `salary`,`bio_id`) values ('$name', '$phone', '$mail', '$address', '$date_of_birth', '$employee_type','$salary','$bio'); 
	select LAST_INSERT_ID() into @lst;
	select @lst'id';
	insert into authendication(`user_id`, `user_name`, `password`,`access_rights`, `it`, `ip`) values(@lst, CONCAT('$name',@lst), md5('arvind'),'".$emp_rghts."', NOW(), '$ip');
	select * from authendication where id = (select LAST_INSERT_ID());";
	$result = $mysql->execute($sql);
	$rst = mysqli_fetch_array($result[1]);
	$rst1 = mysqli_fetch_array($result[0]);
	if($rst['id']!="")
	{
		if ($_FILES["photo"]["error"] > 0)
		{
		}
		else
		{
			$filename = $rst['user_id'].".".$_FILES["photo"]["type"];
			$ext = explode("/",$filename);
			$ext = $ext[1];
			 move_uploaded_file( $_FILES["photo"]["tmp_name"],
			"img/profile_images/" .$rst1['id'].".".$ext);
			$sql = "update profile set `image_source`='img/profile_images/".$rst1['id'].".".$ext."' where id=".$rst1['id'];
			$rst1 = $mysql->execute($sql);
			$rst1 = mysqli_fetch_array($rst1[0]);	
		}
	}
?>
    <center>
      <br>
      <a class="btn btn-red4" data-toggle="modal" href="#myModal" id="view_detail" >View Last Inserted User Name</a>
      <div class="modal hide" id="myModal">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h3>
            <?php $name ?>
          </h3>
        </div>
        <div class="modal-body">
        <h1>User Created Successfully</h1>
          <p>Your User Name
          <h3><?php echo $rst['user_name']; ?></h3>
          password will be as in Default
          </p>
        </div>
        <div class="modal-footer"> <a href="#" class="btn" data-dismiss="modal">Close</a></div>
      </div>
    </center>
    <script type="text/javascript">
    $(document).ready(function(e) {
        $("#view_detail").click();
    });
    </script>
    <?php
}
?>
<div class="container-fluid">
  <div class="content">
    <center>
    <div class="box" style="width:60%;">
      <div class="box-head">
        <h3>Employee Creation</h3>
      </div>
      <div class="box-content">
        <form action="user_new.php" class="form-horizontal" method="post" enctype="multipart/form-data">
          <div class="control-group">
            <label for="datemask" class="control-label">Name</label>
            <div class="controls">
              <input type="text" name="name" id="name" class='text'>
            </div>
          </div>
          <div class="control-group">
            <label for="phonemask" class="control-label">Phone</label>
            <div class="controls">
              <input type="text" name="phone" id="phonemask" class='text'>
            </div>
          </div>
          <div class="control-group">
            <label for="email2" class="control-label">Email</label>
            <div class="controls">
              <input type="text" name="email" id="email2" class='required'>
            </div>
          </div>
          <div class="control-group">
            <label for="serialnumber" class="control-label">Address</label>
            <div class="controls">
              <textarea name="address"></textarea>
            </div>
          </div>
          <div class="control-group">
            <label for="datemask" class="control-label">Date Of Birth</label>
            <div class="controls">
              <input type="text" name="date_of_birth" id="datepicker" class='datepick'>
            </div>
          </div>
          <div class="control-group">
            <label for="radio2" class="control-label">Gender</label>
            <div class="controls" style="width:200px; padding-left:65px;">
              <input type="radio" name="gender" id="radio" value="male">
              &nbsp;Male&nbsp;&nbsp;
              <input type="radio" name="gender" value="female">
              &nbsp;Female </div>
          </div>
          <div class="control-group">
            <label for="selsear" class="control-label">Material Status</label>
            <div class="controls">
              <select name="material_status" id="selsear" class='cho'>
                <option value="1">Single</option>
                <option value="2">Married</option>
                <option value="3">Widdow</option>
              </select>
            </div>
          </div>
            <div class="control-group">
              <label for="file2" class="control-label">Upload Photo</label>
              <div class="controls">
                <input type="file" name="photo" id="file2" class='uniform'>
              </div>
            </div>
          <div class="control-group">
            <label for="bio" class="control-label">Biometric Id</label>
            <div class="controls">
              <input type="text" name="bio" id="bio" class='text'>
            </div>
          </div>
          <div class="control-group">
            <label for="selsear1" class="control-label">Employee Type</label>
            <div class="controls">
              <select name="employee_type" id="selsear1" class='cho' onChange="setSalary(this.value)">
              <option>select</option>
              <?php
			  $sql = "select ds.id, ds.name,ds.salary from designation as ds where ds.`is`=1";
			  $rst = $mysql->execute($sql);
			  foreach($rst as $desg)
			  {
				  while($des = mysqli_fetch_array($desg))
				  { 
				  ?>
                  <script type="text/javascript">
				  	salary['<?php echo $des['name']; ?>'] = '<?php echo $des['salary']; ?>';
				  </script>
					<option value="<?php echo $des['id']; ?>"><?php echo $des['name']; ?></option>
				   <?php
				  }
			  }
			   ?>
              </select>
            </div>
          </div>
          <div class="control-group">
            <label for="selsear1" class="control-label">Employee Rights</label>
            <div class="controls">
              <select name="emp_rghts[]" class='cho' multiple>
              <?php
			  $sql = "select rf.id,rf.`name` from rights as rf where rf.status=1;";
			  $rst = $mysql->execute($sql);
			  foreach($rst as $desg)
			  {
				  while($des = mysqli_fetch_array($desg))
				  { 
					?>
                     <option value="<?php echo $des['id']; ?>"><?php echo $des['name']; ?></option>
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
                <input type="text" id="salary" class='input-square' name="salary" id="twoicons" />
                <span class="add-on">.00</span> </div>
            </div>
          </div>
          <div class="form-actions">
            <input type="submit" class='btn btn-blue4' value="SUBMIT">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
<?php
include_once"footer.php";
?>