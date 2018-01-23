<?php
include_once"header.php";
?>
    <center>
      <div class="box" style="width:65%;">
        <div class="box-head">
          <h3>Designation Creation</h3>
        </div>
        <div class="box-content">
          <?php
if(isset($_POST['submit']))
{
	$name = $_POST['name'];
	$description = $_POST['description'];
	$salary = $_POST['salary'];
	$sql = "insert into designation(`name`,`description`, `salary`) values ('$name','$description','$salary')";
	$mysql->execute($sql);
	?>
          <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
            <h4 class="alert-heading">Successfully Created!</h4>
          </div>
          <?php
}
?>
          <form action="designation_new.php" class="form-horizontal" method="post" enctype="multipart/form-data">
            <div class="control-group">
              <label for="datemask" class="control-label">Name</label>
              <div class="controls">
                <input type="text" name="name" id="datemask" class='text'  onBlur="Validate(this.id,'char')">
              </div>
            </div>
            <div class="control-group">
              <label for="serialnumber" class="control-label">Description</label>
              <div class="controls">
                <textarea name="description"></textarea>
              </div>
            </div>
            <div class="control-group">
              <label for="twoicons" class="control-label">Salary</label>
              <div class="controls">
                <div class="input-prepend input-append"> <span class="add-on"><b><del>&#2352;</del></b></span>
                  <input type="text" class='input-square' name="salary" id="twoicons">
                  <span class="add-on">.00</span> </div>
              </div>
            </div>
            <div class="form-actions">
              <input type="submit" class='btn btn-blue4' name="submit" value="Create">
            </div>
          </form>
        </div>
      </div>
    </center>
    <?php
include_once"footer.php";
?>