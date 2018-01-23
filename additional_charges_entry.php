<?php
include"header.php";
?>
<center>
    <div class="box" style="width:60%;">
      <div class="box-head"><h3>Additional Charges Entry</h3></div>
      <?php
	  if(isset($_POST['submit']))
	  {
		  $sql = "INSERT INTO `shop_charges` 
		  				(`name`, `type`, `type_source`, `rate`, `rate_type`, `rate_status`, `comments`) 
				  VALUES 
				  		('".$_POST['name']."', '".$_POST['type']."', '".$_POST['type_source']."', '".$_POST['rate']."', '".$_POST['rate_type']."', '".$_POST['rate_status']."', '".$_POST['comments']."');";
		  $mysql->execute($sql);
		  ?>
          <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
              <h4 class="alert-heading">Successfully Created!</h4>
          </div>
          <?php
	  }
	  ?>
      <br>
      <form action="#" class="form-horizontal" method="post">
      	 <div class="control-group">
            <label for="name" class="control-label">Name</label>
            <div class="controls">
              <input type="text" name="name" id="name" class='text'>
            </div>
         </div>
         <div class="control-group">
            <label for="type" class="control-label">Type</label>
            <div class="controls">
              <select name="type" id="type" class="cho">
              <option>input</option>
              <option>checkbox</option>
              <option>select</option>
              </select>
            </div>
         </div>
          <div class="control-group">
            <label for="type_source" class="control-label">Value Source</label>
            <div class="controls">
             <select name="type_source" id="type_source" class="cho">
             <option>employees list</option>
             <option>custom</option>
             </select>
            </div>
         </div>
         <div class="control-group">
            <label for="rate" class="control-label">Price</label>
            <div class="controls">
              <input type="text" name="rate" id="rate" class='text'>
            </div>
         </div>
         <div class="control-group">
            <label for="rate_type" class="control-label">Price Type</label>
            <div class="controls">
              <select name="rate_type" id="rate_type" class="cho">
              <option value="0">price</option>
              <option value="1">% of net</option>
              </select>
            </div>
         </div>
         <div class="control-group">
            <label for="rate_status" class="control-label">Price Status</label>
            <div class="controls">
              <select name="rate_status" id="rate_status" class="cho">
              <option value="1">in use</option>
              <option value="0">dont use rate</option>
              </select>
            </div>
         </div>
         <div class="control-group">
            <label for="comments" class="control-label">Comments</label>
            <div class="controls">
              <textarea name="comments" id="comments"></textarea>
            </div>
         </div>
         <center><input type="submit" name="submit" class="btn btn-blue4" value="Submit" ></center>
      </form>
    </div>
</center>
    <?php
include"footer.php";
?>