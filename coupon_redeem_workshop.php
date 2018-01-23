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
        <h3>Coupon Redeem</h3>
      </div>
      <center>
        <form action="#" method="post" onSubmit="checkForm()">
          <table class="table table-bordered table-striped" style="width:30%">
            <tr>
              <th>DATE</th>
              <td><input type="text" name="date" class="input-small datepick"  value="<?php echo $date ?>" /></td>
            </tr>
            <tr>
            	<th>WORKSHOP</th>
                <td>
                <select name="workshop" class="cho"><option value="0">---------------</option><?php 
				$sql = "select * from workshop where `is`=1 order by `name`";
				$rst = $mysql->execute($sql);
				while($r = mysqli_fetch_array($rst[0])){
					echo '<option value="'.$r['id'].'">'.$r['wno']." - ".$r['name'].'</option>';
				}
				?></select>
                </td>
            </tr>
			<tr>
              <th>AVAILABLE POINTS</th>
              <td><input type="text" id="available_points" name="available_points" class="form-control input-small" readonly /></td>
            </tr>
			<tr>
              <th>REDEEM POINTS</th>
              <td><input type="text" name="redeem" class="form-control input-small" /></td>
            </tr>
			<tr>
              <th>NOTES</th>
              <td><textarea name="notes" class="form-control"></textarea></td>
            </tr>
            <tr>
              <th colspan="2" style="text-align:center"><input type="submit" name="update" class="btn btn-blue4" /></th>
            </tr>
          </table>
        </form><?php
        if(isset($_POST['update'])){
			
				$sql = "INSERT INTO `coupon_redeem_workshop` 
							(`date`, `workshop`,`available_points`,`points`,`amount`,`notes`)
						VALUES 
							('".$_POST['date']."',".intval($_POST['workshop']).", ".intval($_POST['available_points']).", ".intval($_POST['redeem']).", ".floatval($_POST['redeem']).",'".$_POST['notes']."');
						UPDATE `workshop` SET `available_point`=`available_point`-".intval($_POST['redeem'])." WHERE  `id`=".intval($_POST['workshop']).";	
							";
				$rst = $mysql->execute($sql);
				
			
		}
	?>
<script type="text/javascript">
$(document).ready(function(){
	$("[name=workshop]").on("change",function(){
		$.ajax({
			type 	: 'post',
			url		: 'coupon_get_workshop_points.php?id='+$("[name=workshop]").val(),
			success	: function(rst){
				$("#available_points").val(rst);
			}
		});
	});
	$("[name=redeem]").on("keyup blur",function(){
		if(parseInt($(this).val()) > parseInt($("#available_points").val())){
			alert("Points not Available");
			$(this).val('');
		}
	});
});
</script>
<?php include"footer.php"; ?>