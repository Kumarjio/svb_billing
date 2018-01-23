<?php
include_once"header.php";
?>
<center>
    <div class="box" style="width:80%;">
    <form action="#" method="post">
      <div class="box-head">
        <h3>My Shop Config</h3>
        <button type="submit" 
        	name="<?php if($_SESSION['production_table'] == "production_process_stock"){ echo 'production_show'; }else{ echo 'production_hide';} ?>">
            <i class="icon 
            <?php if($_SESSION['production_table'] == "production_process_stock"){ echo 'icon-eye-open'; }else{ echo 'icon-eye-close';} ?>"></i>
         </button>
      </div>
    </form>
      <div class="box-content">
<?php
if(isset($_POST['update']))
{
	$sql = "UPDATE 
					`shop_detail` 
				SET 
					`name`='".$_POST['name']."', `location`='".$_POST['location']."', 
					`opening`='".$_POST['opening']."', `investment`=".$_POST['investment'].", 
					`address`='".$_POST['address']."', `phone`='".$_POST['phone']."', 
					`email`='".$_POST['mail']."', `fax`='".$_POST['fax']."', 
					`tax`=".$_POST['tax'].", `tax_mode`=".$_POST['tax_mode'].", 
					`round_off`=".$_POST['round_off'].",`billing_src`='".$_POST['billing_src']."', `bank_name`='".$_POST['bank']."', 
					`bank_acc_no`='".$_POST['bankno']."',`bank_acc_name`='".$_POST['bankname']."', 
					`bank_branch`='".$_POST['branch']."' WHERE  `is`=1 LIMIT 1;
					select id from shop_detail where `is`=1 limit 1;";
	$_SESSION['shop_name'] = $_POST['name'];
	$id = $mysql->execute($sql);
	if ($_FILES["photo"]["error"] > 0)
	{
	}
	else
	{
		$id = mysqli_fetch_array($id[0]);
		$id = $id[0];
		$filename = $id.".".$_FILES["photo"]["type"];
		$ext = explode("/",$filename);
		$ext = $ext[1];
     	 move_uploaded_file( $_FILES["photo"]["tmp_name"],
      	"img/logo.".$ext);
		$sql = "UPDATE `shop_detail` set `logo_src`='img/logo.".$ext."' WHERE `is`=1";
		$mysql->execute($sql);
		$_SESSION['shop_logo'] = "img/logo.".$ext;
	}
	?><br>
			<div class="alert alert-block alert-success" style="width:50%;">
							  <a class="close" data-dismiss="alert" href="#">×</a>
							  <h4 class="alert-heading">Updated SuccessFully!</h4><br>
							  <a href="shop_details.php">
                              <button class="btn btn-success">View  Branch Profile</button>
                              </a>
							</div>
    <?php
}
	$sql = "select * from shop_detail where  `is`=1";
	$results = $mysql->execute($sql);
	$rst = mysqli_fetch_array($results[0]);
	?>
        <form class="form-horizontal" method="post" enctype="multipart/form-data">
          <div class="control-group">
            <label for="datemask" class="control-label">Name</label>
            <div class="controls">
              <input type="text" name="name" id="datemask" class='text' value="<?php echo $rst['name']; ?>">
            </div>
          </div>
          <div class="control-group">
            <label for="file2" class="control-label">Logo</label>
              <div class="controls">
              	<a href="<?php echo $rst['logo_src']; ?>" class='preview fancy'>
                <img src="<?php echo $rst['logo_src']; ?>" width="50" alt="" title="Image title"></a>
                <input type="file" name="photo" id="file2" class='uniform'>
              </div>
            </div>
          <div class="control-group">
            <label for="selsear" class="control-label">Location</label>
            <div class="controls">
              <select name="location" id="selsear" class='cho'>
                <?php
					$file = fopen("files/district.txt","r");
					while(!feof($file))
					{
						$dist = fgets($file);
						?>
						<option <?php if($rst['location']==rtrim($dist)) echo "selected"; ?> ><?php echo $dist ?></option>
						<?php
					}
				?>
              </select>
            </div>
          </div>
         <div class="control-group">
            <label for="opening" class="control-label">Date Of Opening</label>
            <div class="controls">
              <input type="text" name="opening" id="opening" class='datepick text' value="<?php echo $rst['opening']; ?>">
            </div>
          </div>
          <div class="control-group">
            <label for="investment" class="control-label">Invested Amount</label>
            <div class="controls">
              <input type="text" name="investment" id="investment" class='text' value="<?php echo $rst['investment']; ?>">
            </div>
          </div>
          <div class="control-group">
            <label for="serialnumber" class="control-label">Address</label>
            <div class="controls">
              <textarea name="address"><?php echo $rst['address']; ?></textarea>
            </div>
          </div>
          <div class="control-group">
            <label for="phone" class="control-label">Phone</label>
            <div class="controls">
              <input type="text" name="phone" id="phone" class='' value="<?php echo $rst['phone']; ?>">
            </div>
          </div>
           <div class="control-group">
            <label for="mail" class="control-label">Mail</label>
            <div class="controls">
              <input type="text" name="mail" id="mail" class='' value="<?php echo $rst['email']; ?>">
            </div>
          </div>
          <div class="control-group">
            <label for="fax" class="control-label">Fax</label>
            <div class="controls">
              <input type="text" name="fax" id="fax" class='text' value="<?php echo $rst['fax']; ?>">
            </div>
          </div>   
          <div class="control-group">
            <label for="tax" class="control-label">Tax</label>
            <div class="controls">
              <input type="text" name="tax" id="tax" class='text' value="<?php echo $rst['tax']; ?>">
            </div>
          </div> 
          <div class="control-group">
            <label for="tax_mode" class="control-label">Tax Mode</label>
            <div class="controls">
              <select name="tax_mode" id="tax_mode">
              <option value="0" <?php if($rst['tax_mode']==0) echo 'selected' ?> >Product Wise</option>
              <option value="1" <?php if($rst['tax_mode']==1) echo 'selected' ?> >Net Amount Wise</option>
              </select>
            </div>
          </div> 
          <div class="control-group">
            <label for="round_off" class="control-label">Round Off Mode</label>
            <div class="controls">
              <select name="round_off" id="round_off">
              <option value="0" <?php if($rst['round_off']==0) echo 'selected' ?> >Lower Round</option>
              <option value="1" <?php if($rst['round_off']==1) echo 'selected' ?> >Upper Round</option>
              <option value="2" <?php if($rst['round_off']==2) echo 'selected' ?> >Auto Round</option>
              </select>
            </div>
          </div>
          <div class="control-group">
            <label for="billing_src" class="control-label">Billing Source</label>
            <div class="controls">
              <select name="billing_src" id="billing_src">
              <option value="0" <?php if($rst['billing_src']==0) echo 'selected' ?> >Purchase & Stock</option>
              <option value="1" <?php if($rst['billing_src']==1) echo 'selected' ?> >Products List</option>
              </select>
            </div>
          </div> 
          <div class="control-group">
            <label for="bank" class="control-label">Bank</label>
            <div class="controls">
              <input type="text" name="bank" id="bank" class='text' value="<?php echo $rst['bank_name']; ?>">
            </div>
          </div> 
          <div class="control-group">
            <label for="bankno" class="control-label">Account No</label>
            <div class="controls">
              <input type="text" name="bankno" id="bankno" class='text' value="<?php echo $rst['bank_acc_no']; ?>">
            </div>
          </div> 
           <div class="control-group">
            <label for="bankname" class="control-label">Account Name</label>
            <div class="controls">
              <input type="text" name="bankname" id="bankname" class='text' value="<?php echo $rst['bank_acc_name']; ?>">
            </div>
          </div> 
          <div class="control-group">
            <label for="branch" class="control-label">Branch Name</label>
            <div class="controls">
              <input type="text" name="branch" id="branch" class='text' value="<?php echo $rst['bank_branch']; ?>">
            </div>
          </div> 
          <div class="form-actions">
            <input type="submit" class='btn btn-blue4' value="Update" name="update">
          </div>
        </form>
      </div>
    </div>
</center>
<?php
include_once"footer.php";
?>