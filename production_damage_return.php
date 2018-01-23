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
        <h3>DAMAGE ENTRY</h3>
      </div>
      <center>
        <form action="#" method="post" onSubmit="checkForm()">
          <table class="table table-bordered table-striped" style="width:30%">
            <tr>
              <th>DATE</th>
              <td><input type="text" name="date" class="input-small datepick"  value="<?php echo $date ?>" /></td>
            </tr>
            <tr>
            	<th>BILL NO</th>
                <td><input type="text" name="billno" class="input-small" /></td>
            </tr>
            <tbody id="products"><tr>
              <th>PRODUCT</th>
              <td>
              <select name="product" class="cho"><option value="0">--------</option><?php 
				$sql = "select * from product where `is`=1 order by `name`";
				$rst = $mysql->execute($sql);
				while($r = mysqli_fetch_array($rst[0])){?>
                	<option <?php if($_POST['product'] == $r['id']) echo 'selected' ?> value="<?php echo $r['id'] ?>">
					<?php echo $r['name']." ".$r['pid'] ?>
                    </option><?php
				}
				?></select>
              </td>
            </tr></tbody>
            <tr>
              <th>QTY</th>
              <td><input type="text" name="qty" class="input-small" /></td>
            </tr>
            <tr>
              <th>NOTE</th>
              <td><textarea name="note"></textarea></td>
            </tr>
            <tr>
              <th colspan="2" style="text-align:center"><input type="submit" name="update" class="btn btn-blue4" /></th>
            </tr>
          </table>
        </form><?php
        if(isset($_POST['update'])){
			$pid = $_POST['product'];
			$qty = $_POST['qty'];
			$sql = "UPDATE 
						`product` 
					SET 
						`available`=`available`-$qty
					WHERE  
						`id`=$pid;";
			$sql .= "INSERT INTO `production_damage` 
						(`date`, `bill_no`, `pid`, `qty`, `note`) 
					VALUES 
						('".$_POST['date']."', '".$_POST['billno']."', $pid, $qty, '".$_POST['note']."');";
			$mysql->execute($sql);?>
				<div class="alert alert-block alert-success"> 
					<a class="close" data-dismiss="alert" href="#">×</a>
					Updated Successfully
				</div><?php
			
		}
?>
      </center>
    </div>
    <?php include"footer.php"; ?>