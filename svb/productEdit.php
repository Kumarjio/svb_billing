<?php
include_once"header.php";
?>
  <center>
  <div class="box" style="width:50%">
    <div class="box-head">
      <h3> Product Update </h3>
    </div>
    <?php 
	if(!isset($_POST['id']))
	{
	?>
    <br>
    <form action="#" method="post">
      <select name="id" id="id" class='cho' style="width:350px;">
        <?php
  		$sql = "select * from product where `is`=1 order by id";
		$rst = $mysql->execute($sql);
		while($r = mysqli_fetch_array($rst[0]))
		{  ?>
        <option <?php if($_POST['id']==$r['id']) echo 'selected' ?> value="<?php echo $r['id'] ?>"><?php echo $r['pid']." ".$r['name'] ?></option>
        <?php }?>
      </select>
      <br>
      <br>
      <input type="submit" name="load" value="Edit" class="btn btn-blue4" >
    </form>
    <?php
	}
	?>
    <?php
if(isset($_POST['editProduct']))
{ 
    $p_name= addslashes(htmlentities($_POST['name']));
	$p_name1=addslashes(htmlentities($_POST['name1']));
	$p_name2=addslashes(htmlentities($_POST['name2']));
	$p_name_def = $_POST['p_name_def'];
	$p_type=$_POST['p_type'];
	$p_price=$_POST['price'];
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
	$p_vat=$_POST['vat'];
	$p_desc=addslashes($_POST['desc']);
	$id= $_POST['pid'];
	$query="update product set
				`pid`='".$id."',`gid`='".$_POST['p_group']."',`cid`='".$_POST['p_cat']."',
				`bid`='".$_POST['p_brand']."', `name`='".($p_name)."', `name1`='".($p_name1)."', 
				`name2`='".($p_name2)."',`name_default`='0', `type`='".$p_type."', 
				`price`='".$p_price."',`price_src`='".$p_sel_src."',`par_price`='".$par_price."', 
				`mrp`='".$mrp."', `vat`='".$p_vat."', `desc`='".addcslashes($p_desc)."',
				`min_status`='".$min_status."',`min_value`='".$min_value."' 
			where 
				`id`='".$_POST['id']."';";
    $rst = $mysql->execute($query);
	$name = mysqli_fetch_array($rst[0]);
	$name = $_POST['id'];
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
      <h4 class="alert-heading">Successfully Updated!</h4>
    </div>
    <?php
}
if(isset($_POST['id']))
{
 $editquery="select * from product where id='".$_POST['id']."';";
 $result=$mysql->execute($editquery);
 $row=mysqli_fetch_array($result[0]);
?>
    <div class="box-content">
      <form action="productEdit.php" class="form-horizontal" method="post" enctype="multipart/form-data">
        <input name="id" type="hidden" value="<?php echo $_POST['id']; ?>">
        <div class="control-group">
          <label for="name" class="control-label"><strong>Name</strong></label>
          <div class="controlsx">
            <input type="text" name="name" id="name" class='text' value="<?php echo $row['name']; ?>" >
            &nbsp;<input type="radio" name="p_name_def" id="p_name_def" value="1" <?php if($row['name_default']==0) echo 'checked'; ?> />&nbsp;Use
          </div>
        </div>
        
        
        <div class="control-group">
          <label for="pid" class="control-label"><strong>Product Id</strong></label>
          <div class="controlsx">
            <input type="text" name="pid" id="pid" class='text' value="<?php echo $row['pid']; ?>"  >
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
              <option <?php if($row['bid']==$r['id']) echo 'selected' ?>  value="<?php echo $r['id'] ?>"><?php echo  $r['name'] ?></option>
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
              <option <?php if($row['cid']==$r['id']) echo 'selected' ?>  value="<?php echo $r['id'] ?>"><?php echo  $r['name'] ?></option>
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
              <option <?php if($row['gid']==$r['id']) echo 'selected' ?> value="<?php echo $r['id'] ?>"><?php echo  $r['name'] ?></option>
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
              <option <?php if($row['type']==$r['name']) echo 'selected' ?> ><?php echo  $r['name'] ?></option>
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
            <a href="<?php echo $r['photo']; ?>" class='preview_fancy'><img src="<?php echo $row['photo']; ?>" width="50px" alt="" title="Image title"></a> </div>
        </div>
        <div class="control-group">
          <label for="mrp" class="control-label"> <strong>MRP</strong> </label>
          <div class="controlsx">
            <input type="text" name="mrp" id="mrp" class="text" value="<?php echo $row['mrp']; ?>">
          </div>
        </div>
        <div class="control-group">
          <label for="par_price" class="control-label"> <strong>Parchase Price</strong> </label>
          <div class="controlsx">
            <input type="text" name="par_price" id="par_price" class="text" value="<?php echo $row['par_price']; ?>">
          </div>
        </div>
        <div class="control-group">
          <label for="price" class="control-label"> <strong>Selling Price</strong> </label>
          <div class="controlsx">
            <input type="text" name="price" id="price" class='text' value="<?php echo $row['price']; ?>">
          </div>
        </div>
        <div class="control-group">
          <label for="p_sel_src" class="control-label"><strong>Selling Price Source</strong></label>
          <div class="controlsx">
            <input type="radio" name="p_sel_src" id="p_sel_src" value="0" >&nbsp;Purchased Rate
            <input type="radio" name="p_sel_src" id="p_sel_src" value="1" checked="checked"  >&nbsp;Current Rate
          </div>
        </div>
        <div class="control-group">
          <label for="vat" class="control-label"> <strong>Vat(%)</strong> </label>
          <div class="controlsx">
            <input type="text" name="vat" id="vat" class='text' value="<?php echo $row['vat']; ?>">
          </div>
        </div>
        <div class="control-group">
          <label for="desc" class="control-label"><strong>Description</strong></label>
          <div class="controlsx">
            <textarea name="desc" id="desc" ><?php echo $row['desc']; ?></textarea >
          </div>
        </div>
        <div class="control-group">
          <label for="min_value" class="control-label"><strong>Minimum_Stack</strong></label>
          <div class="controlsx">
            <input type="checkbox" name="min_status" id="min_status" <?php if($row['min_status']) echo 'checked' ?> />
            <strong>use</strong>
            <input type="text" name="min_value" id="min_value" class="text" value="<?php echo $row['min_value'] ?>"/>
          </div>
        </div>
        <div class="form-actions">
          <input type="submit" class="btn btn-blue4" onClick="formSub('form1Sub')" value="UPDATE" name="editProduct">
        </div>
      </form>
      <?php
}
?>
    </div>
    </center>
    <?php
include_once"footer.php"
?>
