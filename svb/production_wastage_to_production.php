<?php include"header.php"; ?>
<?php
if(isset($_POST['date'])){
	$date = $_POST['date'];
}else{
	$date = date('Y-m-d');
}
?>
<div class="box" style="min-height:500px">
  <div class="box-head">
    <h3>WASTAGE TO PRODUCTION ENTRY</h3>
  </div>
  <center>
    <form action="#" method="post" onSubmit="checkQty()">
    	<table class="table table-bordered table-striped" style="width:30%">
        	<tr>
              <th>DATE</th>
              <td><input type="text" name="date" class="input-small datepick"  value="<?php echo $date ?>" /></td>
            </tr>
            <tr>
              <th>CATEGORY</th>
              <td>
              <select name="category" class="cho" onChange="checkStock()"><option value="0">--------</option><?php 
				$sql = "select p.id,p.name,w.stock from production_wastage as w,production_raw_material as p where w.category=p.id and p.`is`=1";
				$rst = $mysql->execute($sql);
				while($r = mysqli_fetch_array($rst[0])){?>
					<option data-stock="<?php echo $r['stock'] ?>" <?php if($_POST['category'] == $r['id']) echo 'selected' ?> value="<?php echo $r['id'] ?>">
					<?php echo $r['name'] ?>
                    </option><?php
				}
				?></select>
              </td>
            </tr>
            <tr>
              <th>STOCK AVAILABLE</th>
              <td id="stockAvailable"></td>
            </tr>
            <tr>
              <th>QTY</th>
              <td><input type="text" name="qty" class="input-small" onChange="checkQty()" onKeyUp="checkQty()" onBlur="checkQty()" /></td>
            </tr>
            <tr>
              <th>TO CATEGORY</th>
              <td>
              <select name="to_category" class="cho"><option value="0">--------</option><?php 
				$sql = "select * from production_raw_material where `is`=1 order by `name`";
				$rst = $mysql->execute($sql);
				while($r = mysqli_fetch_array($rst[0])){?>
					<option <?php if($_POST['to_category'] == $r['id']) echo 'selected' ?> value="<?php echo $r['id'] ?>">
					<?php echo $r['name'] ?>
                    </option><?php
				}
				?></select>
              </td>
            </tr>
            <tr>
              <th colspan="2" style="text-align:center"><input type="submit" name="update" class="btn btn-blue4" /></th>
            </tr>
          </table>
    </form><?php
	if(isset($_POST['update'])){
		$sql  = "update production_raw_material set stock=stock+".intval($_POST['qty'])." where `id`=".$_POST['to_category'].";";
		$sql .= "INSERT INTO `production_wastage_to_production` 
							(`date`,`category`,`production_category`, `qty`)
						VALUES 
							('".$_POST['date']."', ".intval($_POST['category']).",".intval($_POST['to_category']).", ".intval($_POST['qty']).")";
				$rst = $mysql->execute($sql);
		?>
				<div class="alert alert-block alert-success"> 
					<a class="close" data-dismiss="alert" href="#">×</a>
					Wastage Stock Updated Successfully
				</div><?php
	}
	?>
  </center>
</div>
<script type="text/javascript">
function checkStock(){
	//$("[name=to_category]").val($("[name=category]").val())
	if($("[name=category]").val() == 0)
		$("#stockAvailable").html('');
	else
		$("#stockAvailable").html($("[name=category]").find("option[value="+$("[name=category]").val()+"]").attr("data-stock"));
}
function checkQty(){
	if($("[name=to_category]").val() == 0){
		event.preventDefault();
		alert("Production Category is Empty");
	}
	if($("[name=qty]").val() == ''){
		event.preventDefault();
		alert("Qty is empty");
	}
	if($("[name=qty]").val()>$("[name=category]").find("option[value="+$("[name=category]").val()+"]").attr("data-stock")){
		event.preventDefault();
		alert("Qty is greater than Stock");
	}
}
</script>
<?php include"footer.php"; ?>