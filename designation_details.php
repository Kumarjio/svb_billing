<?php
include_once"header.php";
?>
    <center>
     <div class="box" style="width:50%;">
      <div class="box-head">
        <h3>Designation Details</h3>
      </div>
      <div class="box-content">
<?php
$sql='';
if(isset($_POST['name']))
{
	$name = $_POST['name'];
	$description = $_POST['description'];
	$salary = $_POST['salary'];
	$desg_id = $_POST['desg_id'];
	$sql = "update designation set `name` = '$name',`description` = '$description', `salary` = '$salary' where id = '$desg_id' ;";
	?>
    <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
      <h4 class="alert-heading">Successfully Updated!</h4>
    </div>
    <?php
}
$sql .= "select ds.id,ds.name,ds.salary,ds.description from designation as ds where ds.`is`=1 ;";
if(isset($_POST['designation']))
$sql .= "select ds.id,ds.name,ds.salary,ds.description from designation as ds where ds.id=".$_POST['designation']." and ds.`is`=1 ;";
$results = $mysql->execute($sql);
?>
      <form action="designation_details.php" class="form-horizontal" method="post" enctype="multipart/form-data">
      <div class="control-group">
            <label for="selsear" class="control-label">Choose Designation</label>
            <div class="controls">
              <select name="designation" id="selsear" class='cho'>
              <?php
              while($rst = mysqli_fetch_array($results[0]))
			  {
			  ?>
                <option value="<?php echo $rst['id']; ?>"><?php echo $rst['name']; ?></option>
              <?php
             }
			  ?>
              </select>
            </div>
          </div>
          <div class="form-actions">
            <input id="submit" type="submit" class='btn btn-primary'>
          </div>
      </form>
     <?php
	 if(isset($_POST['designation']))
	 {
		 $rst = mysqli_fetch_array($results[1]);
	 ?>
        <form action="designation_details.php" class="form-horizontal" method="post" enctype="multipart/form-data">
        <input type="hidden" name="desg_id" value="<?php echo $rst['id']; ?>">
          <div class="control-group">
            <label for="datemask" class="control-label">Name</label>
            <div class="controls">
              <input type="text" name="name" id="datemask" class='text' value="<?php echo $rst['name']; ?>">
            </div>
          </div>
          <div class="control-group">
            <label for="serialnumber" class="control-label">Description</label>
            <div class="controls">
              <textarea name="description"><?php echo $rst['description']; ?></textarea>
            </div>
          </div>
          <div class="control-group">
            <label for="twoicons" class="control-label">Salary</label>
            <div class="controls">
              <div class="input-prepend input-append"><span class="add-on"><b><del>&#2352;</del></b></span>
                <input type="text" class='input-square' name="salary" id="twoicons" value="<?php echo $rst['salary']; ?>">
                <span class="add-on">.00</span> </div>
            </div>
          </div>
          <div class="form-actions">
            <input type="submit" class='btn btn-primary'>
          </div>
        </form>
       	<?php
       	}
		?>
      </div>
    </div>
  </center>
<?php
include_once"footer.php";
?>