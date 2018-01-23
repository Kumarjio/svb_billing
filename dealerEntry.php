<?php
include_once"header.php";
?>
    <?php
if(isset($_POST['addDealer']))
{
	$dealer_name=$_POST['dealer_name'];
	$email=$_POST['email'];
	$dealer_phone=$_POST['dealer_phone'];
	$company_name=$_POST['company_name'];
	$comany_phone=$_POST['company_phone'];
	$company_address=$_POST['company_address'];
	$gender = $_POST['gender'];
	$photo="";
	$ip=$_SESSION['ip'];
$query="INSERT INTO `dealer` (`dealer_name`, `email`, `dealer_phone`,`gender`, `company_name`, `company_phone`, `company_address`, `it`, `ip`) VALUES ('$dealer_name', '$email','$dealer_phone','".$gender."', '$company_name', '$comany_phone', '$company_address', NOW(), '$ip');SELECT LAST_INSERT_ID() FROM dealer limit 1;";
$r=$mysql->execute($query);
$res=mysqli_fetch_array($r[0]);
$lid=$res[0];
if($_FILES['photo']['error']<=0)
{

			$filename = $rst['user_name'].".".$_FILES["photo"]["type"];
			$ext = explode("/",$filename);
			$ext = $ext[1];
move_uploaded_file( $_FILES["photo"]["tmp_name"],
"img/dealer_images/d" .$lid.".".$ext);
$sql = "update dealer set `photo`='img/dealer_images/d".$lid.".".$ext."' where id=".$lid.";";
$mysql->execute($sql);	
}
	if($_GET['doy'])
	{
		?>
    <script type="text/javascript">
        window.opener.setCustomer(<?php echo $lid ?>,'<?php echo $dealer_name ?>','<?php echo $dealer_phone ?>');
        </script>
    <?php
	}
}
?>
    <center>
      <div class="box"  style="width:60%;">
        <div class="box-head">
          <h3>Dealer Creation</h3>
        </div>
        <div class="box-content">
          <form class="form-horizontal" method="post" enctype="multipart/form-data">
            <table class="table table-striped">
              <tbody class="entry">
                <tr>
                  <th> <label for="dealer_name" class="control-label">Name</label>
                  </th>
                  <td><input type="text" name="dealer_name" id="dealer_name" class='text'></td>
                </tr>
                <tr>
                  <th> <label for="email" class="control-label">E-Mail</label>
                  </th>
                  <td><input type="text" name="email" id="email" class='text'></td>
                </tr>
                <tr>
                  <th> <label for="dealer_phone" class="control-label">Phone</label>
                  </th>
                  <td><input type="text" name="dealer_phone" id="dealer_phone" class='text'></td>
                </tr>
                <tr>
                  <th> <label for="file2" class="control-label">Upload Photo</label>
                  </th>
                  <td><input type="file" name="photo" id="file2" class='uniform'></td>
                </tr>
                <tr>
                  <th><label for="radio2" class="control-label">Gender</label></th>
                  <th> <input type="radio" name="gender" id="radio" value="male">
                    &nbsp;Male&nbsp;&nbsp;
                    <input type="radio" name="gender" value="female">
                    &nbsp;Female </th>
                </tr>
                <tr>
                  <th> <label for="company_name" class="control-label">Company Name</label>
                  </th>
                  <td><input type="text" name="company_name" id="company_name" class='text'></td>
                </tr>
                <tr>
                  <th> <label for="company_phone" class="control-label">Company Phone</label>
                  </th>
                  <td><input type="text" name="company_phone" id="company_phone" class='text'></td>
                </tr>
                <tr>
                  <th> <label for="company_address" class="control-label">Company Address</label>
                  </th>
                  <td><textarea name="company_address"></textarea></td>
                </tr>
                <tr>
                  <th style="text-align:center" colspan="2"> 
                  <input type="submit" class='btn btn-blue4' value="SUBMIT" name="addDealer"></th>
                </tr>
              </tbody>
            </table>
          </form>
        </div>
      </div>
    </center>
    <?php 
include_once"footer.php";
?>