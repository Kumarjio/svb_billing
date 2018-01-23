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
        <h3>RAW MATERIAL INWARD</h3>
      </div>
      <center>
        <form action="#" method="post" onSubmit="checkForm()">
          <table class="table table-bordered table-striped" style="width:30%">
            <tr>
              <th>DATE</th>
              <td><input type="text" name="date" class="input-small datepick"  value="<?php echo $date ?>" /></td>
            </tr>
            <tr>
              <th>MATERIAL</th>
              <td>
              <select name="material" class="cho" onChange="processTrigger()">
			  	<option value="0">Choose Material</option><?php 
				$sql = "select * from production_raw_material where `is`=1 order by `name`";
				$rst = $mysql->execute($sql);
				while($r = mysqli_fetch_array($rst[0])){?>
					<option <?php if($_POST['material'] == $r['id']) echo 'selected' ?> value="<?php echo $r['id']?>">
					<?php echo $r['name']?>
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
              <td><input type="text" name="qty" class="input-small" /></td>
            </tr>
                       <tr>
            	<th>NOTES</th>
                <td><textarea name="notes" ></textarea>
                </td>
            </tr>
            <tr>
              <th colspan="2" style="text-align:center"><input type="submit" name="update" class="btn btn-blue4" /></th>
            </tr>
          </table>
        </form><?php
        if(isset($_POST['update'])){
			$sql  = "update production_raw_material set stock=stock+".intval($_POST['qty'])." where `id`=".$_POST['material'].";";
			$sql .= "INSERT INTO `production_raw_material_inward` 
							(`date`,`material`, `qty`)
						VALUES 
							('".$_POST['date']."', ".intval($_POST['material']).", ".intval($_POST['qty']).")";
			$rst = $mysql->execute($sql);?>
			<div class="alert alert-block alert-success"> 
				<a class="close" data-dismiss="alert" href="#">×</a>
				Stock Updated Successfully
			</div><?php
		}?>
</center>
</div>
<script type="text/javascript">
$(document).ready(function(e) {
    processTrigger()
});
var stock = {<?php
$sql = "select * from production_raw_material where `is`=1";
$rst = $mysql->execute($sql);
while($r = mysqli_fetch_array($rst[0])){
		echo '"'.$r['id'].'": '.intval($r['stock']).',';
}
?>}
function processTrigger(){
	material 	= $("[name=material]").val();
	qty 		 = $("[name=qty]").val();
	if(typeof(stock[material]) != 'undefined')
		$("#stockAvailable").html(stock[material]+" Qty.");
}

function checkForm(){
	if($("[name=qty]").val() <=0){
		event.preventDefault();
		alert("Invalid Input");
	}
	if($("[name=material]").val() == 0){
		event.preventDefault();
		alert("Choose Material");
		return false
	}
}
</script>
    <?php include"footer.php"; ?>