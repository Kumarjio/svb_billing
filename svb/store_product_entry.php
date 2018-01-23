<?php
include_once"header.php";
?>
<div class="container-fluid">
<div class="content">
<center>
<div class="box" style="width:50%">
<div class="box-head">
  <h3> Product Creation </h3>
</div>
<?php
if(isset($_POST['addProduct']))
{
	$p_name=$_POST['p_name'];	
	$p_type=$_POST['p_type'];
	$par_price=$_POST['par_price'];
	$mrp=$_POST['mrp'];
	if(isset($_POST['min_status']))
	{
		$min_status=1;
		$min_value=$_POST['min_value'];
	}
	else
	{
		$min_status=0;
		$min_value=0000;
	}
	$p_desc=$_POST['p_desc'];
	$id= $_POST['pid'];
	$query="INSERT INTO `store_products` 
				(`pid`, `name`, `type`, 
				`mrp`,`pur_price`,
				`desc`, `min_status`, `min_value`) 
			values(
				'$id','$p_name','$p_type',
				'$mrp','$par_price',
				'$p_desc','$min_status', '$min_value');";
    $rst = $mysql->execute($query);
	?>
    <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
      <h4 class="alert-heading">Successfully Created!</h4>
    </div>
    <?php
}
?>
<div class="box-content">
<form action="#" class="form-horizontal" method="post" enctype="multipart/form-data">
<body>
<div class="control-group">
  <label for="p_name" class="control-label"><strong>Name</strong></label>
  <div class="controlsx">
    <input type="text" name="p_name" id="p_name" class='text' >
  </div>
</div>
<div class="control-group">
  <label for="pid" class="control-label"><strong>Product Id</strong></label>
  <div class="controlsx">
    <input type="text" name="pid" id="pid" class='text' >
  </div>
</div>
<div class="control-group">
  <label for="p_type" class="control-label"><strong>Weightage Type</strong></label>
  <div class="controlsx">
    <select name="p_type" id="p_type" class='cho'>
      <?php
  $sql ="select c.name from conversion as c where c.`is`=1 and c.`using`=1";
  $rst = $mysql->execute($sql);
  while($r = mysqli_fetch_array($rst[0]))
  {
  ?>
      <option><?php echo  $r['name'] ?></option>
      <?php
  }
  ?>
    </select>
  </div>
</div>
<div class="control-group">
  <label for="mrp" class="control-label"> <strong>MRP</strong> </label>
  <div class="controlsx">
    <input type="text" name="mrp" id="mrp" class="text">
  </div>
</div>
<div class="control-group">
  <label for="par_price" class="control-label"> <strong>Parchase Price</strong> </label>
  <div class="controlsx">
    <input type="text" name="par_price" id="par_price" class="text">
  </div>
</div>
<div class="control-group">
  <label for="p_desc" class="control-label"><strong>Description</strong></label>
  <div class="controlsx">
    <textarea name="p_desc" id="p_desc"></textarea>
  </div>
</div>
<div class="control-group">
  <label for="min_value" class="control-label"><strong>Minimum_Stack</strong></label>
  <div class="controlsx">
    <input type="checkbox" name="min_status" id="min_status" />
    <strong>Use</strong>
    <input type="text" name="min_value" id="min_value" class="text"/>
  </div>
</div>
<div class="form-actions">
  <input type="submit" class="btn btn-blue4"  value="SUBMIT" name="addProduct">
</div>
</body>
</form>
</div>
</center>
</div>
</div>
<?php
include_once"footer.php"
?>
