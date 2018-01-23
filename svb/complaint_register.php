<?php
include_once"header.php";
?>
<div class="container-fluid">
  <div class="content">
    <center>
    <div class="box" style="width:60%;">
      <div class="box-head">
        <h3>Complaints Register</h3>
      </div>
      <div class="box-content">
<?php
if(isset($_POST['submit']))
{
	$cid = 0;
	if(isset($_POST['cust']))
	$cid = $_POST['cust'];
	$name = $_POST['name'];
	$phone = $_POST['phone'];
	$prod = $_POST['prod'];
	$comp = 0;
	if(isset($_POST['comp']))
	$comp = $_POST['comp'];
	$sql = "INSERT INTO `product_complaint` (`cid`, `name`, `phone`, `pid`, `comp`) VALUES (".$cid.", '".$name."', '".$phone."', ".$prod.", '".$comp."');";
	$mysql->execute($sql);
	?>
      <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
        <h4 class="alert-heading">Successfully Created!</h4>
      </div>
      <?php
}
?>
        <form action="#" class="form-horizontal" method="post" enctype="multipart/form-data">
        	
            <div class="control-group">
            <label for="cust" class="control-label">Customer</label>
            <div class="controls">
              <select name="cust" id="cust" class='cho'>
              <option>select</option>
              <?php
			  $sql = "select * from customers where `is`=1";
			  $rst = $mysql->execute($sql);
			  while($des = mysqli_fetch_array($rst[0]))
			  { 
			  ?>
				<option value="<?php echo $des['id']; ?>"><?php echo $des['name']; ?></option>
			   <?php
			  }
			   ?>
              </select>
            </div>
          </div>
          
          <div class="control-group">
            
            <div class="controls">
              (OR)
            </div>
          </div>
        
          <div class="control-group">
            <label for="name" class="control-label">Name</label>
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
            
            <div class="controls">
             
            </div>
          </div>
          <div class="control-group">
            <label for="prod" class="control-label">Product</label>
            <div class="controls">
              <select name="prod" id="prod" class='cho'>
              <option>select</option>
              <?php
			  $sql = "select * from product where `is`=1";
			  $rst = $mysql->execute($sql);
			  while($des = mysqli_fetch_array($rst[0]))
			  { 
			  ?>
				<option value="<?php echo $des['id']; ?>"><?php echo $des['name']; ?></option>
			   <?php
			  }
			   ?>
              </select>
            </div>
          </div>
         <div class="control-group">
            <label for="comp" class="control-label">Complaint</label>
            <div class="controls">
              <textarea name="comp" id="comp"></textarea>
            </div>
          </div>
          <div class="form-actions">
            <input type="submit" name="submit" class='btn btn-blue4' value="SUBMIT">
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