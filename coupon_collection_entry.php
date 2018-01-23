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
        <h3>Coupon Collection</h3>
      </div>
      <center>
        <form action="#" method="post" onSubmit="checkForm()">
          <table class="table table-bordered table-striped" style="width:30%">
            <tr>
              <th>DATE</th>
              <td><input type="text" name="date" class="input-small datepick"  value="<?php echo $date ?>" /></td>
            </tr>
            <tr>
            	<th>STAFF</th>
                <td>
                <select name="staff" class="cho"><?php 
				$sql = "select * from marketting_staff_profile where `is`=1 order by `name`";
				$rst = $mysql->execute($sql);
				while($r = mysqli_fetch_array($rst[0])){
					echo '<option value="'.$r['id'].'">'.$r['name'].'</option>';
				}
				?></select>
                </td>
            </tr>
			
            <tr>
            	<th>WORKSHOP</th>
                <td>
                <select name="workshop" class="cho">
				<option value=0>Staff Collection - No workshow</option><?php 
				$sql = "select * from workshop where `is`=1 order by `name`";
				$rst = $mysql->execute($sql);
				while($r = mysqli_fetch_array($rst[0])){
					echo '<option value="'.$r['id'].'">'.$r['wno']." - ".$r['name'].'</option>';
				}
				?></select>
                </td>
            </tr>
            <tr>
              <th>COUPON</th>
              <td><table class="table table-stripped table-bordered">
				<thead><tr><th>Coupon</th><th>Nos</th></thead><?php 
				$sql = "select * from coupon_category where `is`=1 order by `name`";
				$rst = $mysql->execute($sql);
				while($r = mysqli_fetch_array($rst[0])){?>
					<tr><td><?php echo $r['name'] ?></td><td>
					<input type="number" name="coupon[<?php echo $r['id'] ?>]" class="form-control input-small" /></td></tr><?php
				}	
				?>
				<tfoot><tr><td>Total</td><td><input type="text" class="form-control input-small" readonly  id="totalPoints" /></td></tr></tfoot>
				</table>
              </td>
            </tr>
            <tr>
              <th colspan="2" style="text-align:center"><input type="submit" name="update" class="btn btn-blue4" /></th>
            </tr>
          </table>
        </form><?php
        if(isset($_POST['update'])){
				$sql ="set @groupid = 1;
						select if(max(groupid) is null,1,max(groupid)+1) into @groupid from coupon_collection;";
				$rst = $mysql->execute($sql);
				foreach($_POST['coupon'] as $cid=>$nos){
					if(intval($nos) == 0)
						continue;
					$sql = "INSERT INTO `coupon_collection` 
								(`groupid`,`date`, `staff`, `coupon`, `workshop`, `nos`,`points`)
							VALUES 
								(@groupid,'".$_POST['date']."', ".intval($_POST['staff']).", ".$cid.", 
								".intval($_POST['workshop']).", ".intval($nos).",(select c.points*".intval($nos)." from coupon_category as c where c.id=".intval($cid)."));
							UPDATE `workshop` SET `available_point`=`available_point`+(select c.points*".intval($nos)." from coupon_category as c where c.id=".intval($cid).") WHERE  `id`=".intval($_POST['workshop']);
					$rst = $mysql->execute($sql);
				}?>
				<center>
					<div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
						Updated Successfully
					</div>
				</center><?php
			
		}
	?>
<script type="text/javascript">
$(document).ready(function(){
	$("[name^=coupon]").on("change blur",function(){
		total = 0;
		$("[name^=coupon]").each(function(){
			if($(this).val() >0){
				total = total+parseInt($(this).val());
			}
		});
		$("#totalPoints").val(total);
	});
});
</script>
<?php include"footer.php"; ?>