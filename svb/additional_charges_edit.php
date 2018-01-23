<?php
include"header.php";
?>
<center>
    <div class="box" style="width:60%;">
      <div class="box-head"><h3>Additional Charges Edit</h3></div>
      <?php
	  if(isset($_POST['submit']))
	  {
		  $sql = "UPDATE 
		  			`shop_charges` 
		  		  SET 
				  	 `name`='".$_POST['name']."',
				  	 `type`='".$_POST['type']."', 
					 `type_source`='".$_POST['type_source']."',
					 `rate`='".$_POST['rate']."', 
					 `rate_type`='".$_POST['rate_type']."', 
					 `rate_status`='".$_POST['rate_status']."', 
					 `comments`='".$_POST['comments']."'
				   WHERE 
				   	  `id`='".$_POST['id']."';";
		  $mysql->execute($sql);
		  ?>
          <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
              <h4 class="alert-heading">Successfully Created!</h4>
          </div>
          <?php
	  }
	  else if(isset($_POST['remove']))
	  {
		  $sql = "UPDATE 
		  			`shop_charges` 
		  		  SET 
				  	 `is`='0'
				   WHERE 
				   	  `id`='".$_POST['id']."';";
		  $mysql->execute($sql);
		  ?>
          <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
              <h4 class="alert-heading">Removed Successfully!</h4>
          </div>
          <?php
	  }
	  ?>
      <form action="#" method="post">
      <table style="width:50px; overflow:visible" class="table table-striped table-bordered">
      <thead><tr><th><?php
	  $sql = "select 
					* 
				from 
					shop_charges as s 
				where 
					s.`is`=1";
	 $rst = $mysql->execute($sql);
	 ?>
     <select name="charge" class="cho">
     <?php
	 while($r = mysqli_fetch_array($rst[0]))
	 {
		?><option value="<?php echo $r['id'] ?>" <?php if($_POST['charge']==$r['id']) echo 'selected' ?>><?php echo $r['name'] ?></option><?php
	 }
	  ?>
      </select>
      </th><th style="vertical-align:middle"><input type="submit" name="edit" value="Edit" class="btn btn-blue4" ></th></tr></thead>
      </table>
      </form>
      <br>
      <?php
	  if(isset($_POST['edit']))
	  {
		  $sql = "select 
					* 
				from 
					shop_charges as s 
				where 
					s.`id`=".$_POST['charge']."";
		 $rst = $mysql->execute($sql);
		 $r = mysqli_fetch_array($rst[0]);
	  ?>
      <form action="#" class="form-horizontal" method="post">
      	<input type="hidden" name="id" value="<?php echo $r['id'] ?>" >
      	 <div class="control-group">
            <label for="name" class="control-label">Name</label>
            <div class="controls">
              <input type="text" name="name" id="name" class='text' value="<?php echo $r['name'] ?>">
            </div>
         </div>
         <div class="control-group">
            <label for="type" class="control-label">Type</label>
            <div class="controls">
              <select name="type" id="type" class="cho">
              <option <?php if($r['type']=='input')echo 'selected' ?> >input</option>
              <option <?php if($r['type']=='checkbox')echo 'selected' ?> >checkbox</option>
              <option <?php if($r['type']=='select')echo 'selected' ?> >select</option>
              </select>
            </div>
         </div>
          <div class="control-group">
            <label for="type_source" class="control-label">Value Source</label>
            <div class="controls">
             <select name="type_source" id="type_source" class="cho">
             <option <?php if($r['type_source']=='employees list')echo 'selected' ?> >employees list</option>
             <option <?php if($r['type_source']=='custom')echo 'selected' ?>>custom</option>
             </select>
            </div>
         </div>
         <div class="control-group">
            <label for="rate" class="control-label">Price</label>
            <div class="controls">
              <input type="text" name="rate" id="rate" class='text' value="<?php echo $r['rate'] ?>">
            </div>
         </div>
         <div class="control-group">
            <label for="rate_type" class="control-label">Price Type</label>
            <div class="controls">
              <select name="rate_type" id="rate_type" class="cho">
              <option value="0" <?php if($r['rate_type']=='0')echo 'selected' ?> >price</option>
              <option value="1" <?php if($r['rate_type']=='1')echo 'selected' ?> >% of net</option>
              </select>
            </div>
         </div>
         <div class="control-group">
            <label for="rate_status" class="control-label">Price Status</label>
            <div class="controls">
              <select name="rate_status" id="rate_status" class="cho">
              <option value="1" <?php if($r['rate_status']=='1')echo 'selected' ?> >in use</option>
              <option value="0" <?php if($r['rate_status']=='0')echo 'selected' ?> >dont use rate</option>
              </select>
            </div>
         </div>
         <div class="control-group">
            <label for="comments" class="control-label">Comments</label>
            <div class="controls">
              <textarea name="comments" id="comments"><?php echo $r['comments'] ?></textarea>
            </div>
         </div>
         <center><input type="submit" name="submit" class="btn btn-blue4" value="Submit" >
         &nbsp;&nbsp;<input type="submit" name="remove" class="btn btn-red4" value="Remove" >
         </center>
      </form>
      <?php
	  }
	  ?>
    </div>
</center>
    <?php
include"footer.php";
?>