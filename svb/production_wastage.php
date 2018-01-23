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
        <h3>PRODUCTION WASTAGE ENTRY</h3>
      </div>
      <center>
        <form action="#" method="post" onSubmit="checkForm()">
          <table class="table table-bordered table-striped" style="width:30%">
            <tr>
              <th>DATE</th>
              <td><input type="text" name="date" class="input-small datepick"  value="<?php echo $date ?>" /></td>
            </tr>
            <tr>
              <th>CATEGORY</th>
              <td>
              <select name="category" class="cho"><option value="0">--------</option><?php 
				$sql = "select * from production_raw_material where `is`=1 order by `name`";
				$rst = $mysql->execute($sql);
				while($r = mysqli_fetch_array($rst[0])){?>
					<option <?php if($_POST['category'] == $r['id']) echo 'selected' ?> value="<?php echo $r['id'] ?>">
					<?php echo $r['name'] ?>
                    </option><?php
				}
				?></select>
              </td>
            </tr>
            <tr>
              <th>QTY</th>
              <td><input type="text" name="qty" class="input-small" /></td>
            </tr>
            <tr>
              <th colspan="2" style="text-align:center"><input type="submit" name="update" class="btn btn-blue4" /></th>
            </tr>
          </table>
        </form><?php
        if(isset($_POST['update'])){
				$sql = "INSERT INTO `production_wastage` 
							(`category`, `stock`) 
						VALUES 
							(".intval($_POST['category']).", ".intval($_POST['qty']).")
						ON DUPLICATE KEY update stock=stock+".intval($_POST['qty'])." ";
				$rst = $mysql->execute($sql);
				$sql = "INSERT INTO `production_wastage_stock` 
							(`date`,`category`, `qty`)
						VALUES 
							('".$_POST['date']."', ".intval($_POST['category']).", ".intval($_POST['qty']).")";
				$rst = $mysql->execute($sql);?>
				<div class="alert alert-block alert-success"> 
					<a class="close" data-dismiss="alert" href="#">×</a>
					Wastage Stock Updated Successfully
				</div><?php
		}
?>		
<?php include"footer.php"; ?>