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
        <h3>RAW MATERIAL DISTRIBUTION</h3>
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
				$sql = "select * from profile where `is`=1 and `type`=3 order by `name`";
				$rst = $mysql->execute($sql);
				while($r = mysqli_fetch_array($rst[0])){
					echo '<option value="'.$r['id'].'">'.$r['name'].'</option>';
				}
				?></select>
                </td>
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
              <th colspan="2" style="text-align:center">
              	<input type="radio" name="damage" value="0" checked /> Stock
                <input type="radio" name="damage" value="1"/> Damage
              </th>
            </tr>
            <tr>
              <th>STOCK AVAILABLE</th>
              <td id="stockAvailable"></td>
            </tr>
            <tr>
              <th>QTY</th>
              <td><input type="text" name="qty" class="input-small" onKeyUp="processTrigger()" onChange="processTrigger()" onBlur="processTrigger()" /></td>
            </tr>
            <tr>
              <th colspan="2" style="text-align:center"><input type="submit" name="update" class="btn btn-blue4" /></th>
            </tr>
          </table>
        </form><?php
        if(isset($_POST['update'])){
			$sql  = "update production_raw_material set stock=stock-".intval($_POST['qty'])." where `id`=".$_POST['material'].";";
			$sql .= "INSERT INTO `production_raw_material_distribution` 
							(`date`, `staff`, `material`, `qty`,`damage`)
						VALUES 
							('".$_POST['date']."', ".intval($_POST['staff']).", ".intval($_POST['material']).", ".intval($_POST['qty']).",".intval($_POST['damage']).")";
			$rst = $mysql->execute($sql);?>
			<div class="alert alert-block alert-success"> 
				<a class="close" data-dismiss="alert" href="#">×</a>
				Stock Updated Successfully
			</div><?php
		}
$sql = "select
			d.date,
			pf.name'staff',
			m.name,
			d.qty
		from
			production_raw_material_distribution as d
			join production_raw_material as m
				on d.material=m.id and d.damage=0 and d.date = '".$date."'
			join profile as pf 
				on d.staff=pf.id
		order by
			pf.name,
			m.name";
$rst = $mysql->execute($sql);
$materials 	 = array();
$distribution = array();
while($r = mysqli_fetch_array($rst[0])){
	$distribution[$r['staff']][$r['name']] += $r['qty'];
	$materials[$r['name']] = $r['name'];
}
$sum = array();
?>
<table class="table table-bordered table-striped">
<thead>
	<tr>
    	<th rowspan="2">STAFF/MATERIAL</th><?php 
		foreach($materials as $name){ 
			echo '<th>'.$name.'</th>';
		}?>
    </tr>
</thead>
<tbody><?php
	foreach($distribution as $staff=>$data){  ?>
	<tr><td><?php echo $staff ?></td><?php 
		foreach($materials as $name){ 
				echo '<td style="text-align:center">'.intval($data[$name]).'</td>';
				$sum[$name] += $data[$name];
		}?>
    </tr><?php
	} ?>
</tbody>
<tfoot>
	<tr><th>Total</th><?php 
		foreach($materials as $name){ 
				echo '<th style="text-align:center">'.$sum[$name].'</th>';
		}?>
    </tr>
</tfoot>
</table>
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
		//echo '"'.$r['process'].'": [{"cat":'.$r['process'].',"pid":'.$r['process'].',"stock":'.$r['stock'].'}],';
		echo '"'.$r['id'].'": '.intval($r['stock']).',';
}
?>}
function processTrigger(){
	material 	= $("[name=material]").val();
	qty 		 = $("[name=qty]").val();
	if(typeof(stock[material]) != 'undefined')
		$("#stockAvailable").html(stock[material]+" Qty.");
	if(stock[material] == 0){
		$("[name=qty]").attr("readonly",true);
	}else{
		$("[name=qty]").attr("readonly",false);	
	}
	if(qty>stock[material] && qty!=''){
		alert("Not enough Stock");
		$("[name=qty]").val(0);
	}
}

function checkForm(){
	if($("[name=qty]").val() <=0){
		event.preventDefault();
		alert("Invalid Input");
		return false
	}
	if($("[name=material]").val() == 0){
		event.preventDefault();
		alert("Choose Material");
		return false
	}
	
}
</script>
    <?php include"footer.php"; ?>