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
	$p_name=addslashes($_POST['p_name']);
	$p_name1=addslashes($_POST['p_name1']);
	$p_name2=addslashes($_POST['p_name2']);
	$p_name_def = $_POST['p_name_def'];
	$p_type=$_POST['p_type'];
	$p_price=$_POST['p_price'];
	$p_sel_src = $_POST['p_sel_src'];
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
	$p_vat=$_POST['p_vat'];
	$p_desc=$_POST['p_desc'];
	$id= $_POST['pid'];
	$query="INSERT INTO product (
				`pid`,`hsm_code`,`gid`,`cid`,
				`bid`, `name`, `name1`, 
				`name2`,`name_default`, `type`, 
				`price`,`price_src`,`par_price`, 
				`mrp`, `vat`,`gst`, `desc`,
				`min_status`,`min_value`) 
			values(
				'$id','".$_POST['hsm_code']."','".$_POST['p_group']."','".$_POST['p_cat']."',
				'".$_POST['p_brand']."','$p_name','$p_name1',
				'$p_name2','0','$p_type',
				'$p_price','$p_sel_src','$par_price',
				'$mrp', '$p_vat','".addslashes(json_encode($_REQUEST['gst']))."','$p_desc',
				'$min_status', '$min_value');
			SELECT LAST_INSERT_ID()'id' FROM product limit 1;";
    $rst = $mysql->execute($query);
	$name = mysqli_fetch_array($rst[0]);
	$name = $name['id'];
	if($_FILES['photo']['error']<=0)
	{
		$ext=explode("/",$_FILES["photo"]["type"]);
		$ext = $ext[1];
		move_uploaded_file($_FILES["photo"]["tmp_name"],"img/product_images/".$name.".".$ext);
		$sql="update product set `photo`='img/product_images/".$name.".".$ext."' where id='".$name."';";
		$mysql->execute($sql);
	}
	else
	{
		$sql="update product set `photo`='img/icons/essen/32/shipping.png' where id='".$name."';";
		$mysql->execute($sql);
	}
	?>
    <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
      <h4 class="alert-heading">Successfully Created!</h4>
    </div>
    <?php
}
?>
<div class="box-content">
<form action="productEntry.php" class="form-horizontal" method="post" enctype="multipart/form-data">
<div class="control-group">
  <label for="p_name" class="control-label"><strong>Name</strong></label>
  <div class="controlsx">
    <input type="text" name="p_name" id="p_name" class='text' >
	<span class="hide">
    &nbsp;<input type="radio" name="p_name_def" id="p_name_def" value="0" checked />&nbsp;Use
	</span>
  </div>
</div>


<div class="control-group">
  <label for="pid" class="control-label"><strong>Product Id</strong></label>
  <div class="controlsx">
    <input type="text" name="pid" id="pid" class='text' >
  </div>
</div>


<div class="control-group">
  <label for="hsm_code" class="control-label"><strong>Hsm Code</strong></label>
  <div class="controlsx">
    <input type="text" name="hsm_code" id="hsm_code" class='text' >
  </div>
</div>

<div class="control-group">
  <label for="p_brand" class="control-label"><strong>Brand</strong></label>
  <div class="controlsx">
    <select name="p_brand" id="p_brand" class='cho'>
      <?php
  $sql = "select * from product_brand where `is`=1 order by id";
  $rst = $mysql->execute($sql);
  while($r = mysqli_fetch_array($rst[0]))
  {
  ?>
      <option value="<?php echo $r['id'] ?>"><?php echo  $r['name'] ?></option>
      <?php
  }
  ?>
    </select>
  </div>
</div>
<div class="control-group">
  <label for="p_cat" class="control-label"><strong>Category</strong></label>
  <div class="controlsx">
    <select name="p_cat" id="p_cat" class='cho'>
      <?php
  $sql = "select * from product_category where `is`=1 order by id";
  $rst = $mysql->execute($sql);
  while($r = mysqli_fetch_array($rst[0]))
  {
  ?>
      <option value="<?php echo $r['id'] ?>"><?php echo  $r['name'] ?></option>
      <?php
  }
  ?>
    </select>
  </div>
</div>
<div class="control-group">
  <label for="p_group" class="control-label"><strong>Group</strong></label>
  <div class="controlsx">
    <select name="p_group" id="p_group" class='cho'>
      <?php
  $sql = "select * from product_group where `is`=1 order by id";
  $rst = $mysql->execute($sql);
  while($r = mysqli_fetch_array($rst[0]))
  {
  ?>
      <option value="<?php echo $r['id'] ?>"><?php echo  $r['name'] ?></option>
      <?php
  }
  ?>
    </select>
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
  <label for="file2" class="control-label"><strong>Upload Photo</strong></label>
  <div class="controlsx">
    <input type="file" name="photo" id="file2" class='uniform'>
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
  <label for="p_price" class="control-label"> <strong>Selling Price</strong> </label>
  <div class="controlsx">
    <input type="text" name="p_price" id="p_price" class="text">
  </div>
</div>
<div class="control-group">
  <label for="p_sel_src" class="control-label"><strong>Selling Price Source</strong></label>
  <div class="controlsx">
    <input type="radio" name="p_sel_src" id="p_sel_src" value="0" >&nbsp;Purchased Rate
    <input type="radio" name="p_sel_src" id="p_sel_src" value="1" checked>&nbsp;Current Rate
  </div>
</div>
<div class="control-group">
  <label for="cgst" class="control-label"> <strong>CGST(%)</strong> </label>
  <div class="controlsx">
    <input type="text" name="gst[cgst]" id="cgst" class="text">
  </div>
</div>

<div class="control-group">
  <label for="sgst" class="control-label"> <strong>SGST(%)</strong> </label>
  <div class="controlsx">
    <input type="text" name="gst[sgst]" id="sgst" class="text">
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
  <input type="submit" class="btn btn-blue4" onClick="formSub('form1Sub')" value="SUBMIT" name="addProduct">
</div>
</form>
</div>
</center>
</div>
</div>
<?php
include_once"footer.php"
?>
