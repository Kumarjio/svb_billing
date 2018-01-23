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
        <h3>BUNDLE PACKING</h3>
      </div>
      <center>
        <form action="#" method="post">
          <table class="table table-bordered table-striped" style="width:30%">
            <tr>
              <th>DATE</th>
              <td><input type="text" name="date" class="input-small datepick"  value="<?php echo $date ?>" /></td>
            </tr>
            <tr>
            	<th>STAFF</th>
                <td>
                <select name="staff" class="cho"><?php 
				$sql = "select * from profile where `is`=1 and `type`=3 order by `name`";
				$rst = $mysql->execute($sql);
				while($r = mysqli_fetch_array($rst[0])){
					echo '<option value="'.$r['id'].'">'.$r['name'].'</option>';
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
			$sql = "INSERT INTO `production_bundle_packing` 
							(`date`, `staff`,`qty`)
						VALUES 
							('".$_POST['date']."', ".intval($_POST['staff']).", ".intval($_POST['qty']).")";
			$rst = $mysql->execute($sql);?>
			<div class="alert alert-block alert-success"> 
				<a class="close" data-dismiss="alert" href="#">×</a>
				Updated Successfully
			</div><?php
		}?>
    <?php include"footer.php"; ?>