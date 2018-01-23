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
        <h3>STOCK - MONITOR</h3>
      </div>
      <center>
        <form action="#" method="post" onSubmit="checkForm()">
          <table class="table table-bordered table-striped" style="width:30%">
            <tr>
              <th>PROCESS</th>
              <td>
              <select name="process" class="cho" onChange="processTrigger()"><?php 
				$sql = "select * from production_process  where `is`=1 order by `type`,`order`";
				$rst = $mysql->execute($sql);
				while($r = mysqli_fetch_array($rst[0])){?>
					<option <?php if($_POST['process'] == $r['id']) echo 'selected' ?> 
                    	value="<?php echo $r['id']?>" 
                        data-type="<?php echo $r['type'] ?>">
					<?php echo $r['name']?>
                    </option><?php
				}
				?></select>
              </td>
            </tr>
            <tbody id="category"><tr>
              <th>CATEGORY</th>
              <td>
              <select name="category" class="cho" onChange="processTrigger()"><option value="0">--------</option><?php 
				$sql = "select * from production_category where `is`=1 order by `name`";
				$rst = $mysql->execute($sql);
				while($r = mysqli_fetch_array($rst[0])){?>
					<option <?php if($_POST['category'] == $r['id']) echo 'selected' ?> value="<?php echo $r['id'] ?>">
					<?php echo $r['name'] ?>
                    </option><?php
				}
				?></select>
              </td>
            </tr></tbody>
            <tbody id="products" class="hide"><tr>
              <th>PRODUCT</th>
              <td>
              <select name="product" class="cho" onChange="processTrigger()"><option value="0">--------</option><?php 
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
              <th>MIN</th>
              <td><input type="text" name="min" class="input-small" /></td>
            </tr>
            <tr>
              <th>MAX</th>
              <td><input type="text" name="max" class="input-small" /></td>
            </tr>
            <tr>
              <th colspan="2" style="text-align:center"><input type="submit" name="update" value="Update" class="btn btn-blue4" /></th>
            </tr>
          </table>
        </form><?php
        if(isset($_POST['update'])){
			$sql = "INSERT INTO `production_stock_monitor` 
						(`process`, `category`, `pid`, `min`, `max`) 
					VALUES 
						(".intval($_POST['process']).", ".intval($_POST['category']).", ".intval($_POST['product']).", ".intval($_POST['min']).", ".intval($_POST['max']).")
					ON DUPLICATE KEY update `min`=".intval($_POST['min']).", `max`=".intval($_POST['max'])." ;";
			$mysql->execute($sql);
			?>
            <div class="alert alert-block alert-success"> 
                <a class="close" data-dismiss="alert" href="#">×</a>
                Production Stock Updated Successfully
            </div><?php
		}
		
$sql = "select 
			pc.name	,
			if(c.name is null,p.name,c.name)'category',
			m.min,
			m.max 
		from 
			production_process as pc
			join production_stock_monitor as m
				on pc.id=m.`process`
			left join production_category as c
				on m.category=c.id
			left join product as p
				on m.pid=p.id
		order by
			pc.`type`,
			pc.`order`,
			c.name,
			p.name;";
$sql .= "select 
			p.name,
			if(c.name is null,p1.pid,c.name)'category',
			pc.stock		
		from 
			production_process as p
			join production_process_category as pc
				on p.id=pc.`process`
			left join production_category as c 
				on p.`type`=0 and c.id=pc.category
			left join product as p1
				on p.`type`=1 and p1.id=pc.pid
		order by
			p.`type`,
			p.`order`,c.name,p1.pid";
$rst = $mysql->execute($sql);
$category = array();
$stockData = array();
$monitorDate = array();
while($r = mysqli_fetch_array($rst[0])){
	$category[$r['category']] = $r['category'];
	$monitorDate[$r['name']][$r['category']] = $r;
	$stockData[$r['name']][$r['category']]['stock'] = 0;
}
while($r = mysqli_fetch_array($rst[1])){
	$stockData[$r['name']][$r['category']] = $r['stock'];
}
?>
<table class="table table-bordered table-striped">
<thead>
	<tr>
        <th rowspan="2">PROCESS/CATEGORY</th><?php
        foreach($category as $cat){ ?>
           <th colspan="3" style="text-align:center"><?php echo $cat ?></th><?php
        }?>
    </tr>
    <tr><?php
        foreach($category as $cat){ ?>
           <th>MIN</th>
		   <th>MAX</th>
		   <th>STOCK</th><?php
        }?>
    </tr>
</thead>
<tbody><?php
foreach($monitorDate as $process=>$data){?>
	<tr>
    	<th><?php echo $process ?></th><?php
        foreach($category as $cat){ ?>
           <td style="text-align:center"><?php echo $data[$cat]['min'] ?></td>
		   <td style="text-align:center"><?php echo $data[$cat]['max'] ?></td>
		   <td style="text-align:center">
           	<span 
            	style="color:<?php 
					if($stockData[$process][$cat]['stock']< $data[$cat]['min'] || $stockData[$process][$cat]['stock']>$data[$cat]['min']){
						echo "red";
					}?>">
		   	<?php echo $stockData[$process][$cat]['stock'] ?>
            </span>
           </td><?php
        }?>
    </tr><?php
}
?>
</tbody>
</table>


      </center>
    </div>
<script type="text/javascript">
$(document).ready(function(e) {
    processTrigger();
});
var stock = {<?php
$sql = "select 
			p.`process`,
			p.category,
			p.pid,
			p.min,
			p.max 
		from 
			production_stock_monitor as p";
$rst = $mysql->execute($sql);
while($r = mysqli_fetch_array($rst[0])){
		//echo '"'.$r['process'].'": [{"cat":'.$r['process'].',"pid":'.$r['process'].',"stock":'.$r['stock'].'}],';
		echo '"'.++$sno.'": [{"process":'.$r['process'].',"cat":'.$r['category'].',"pid":'.$r['pid'].',"min":'.$r['min'].',"max":'.$r['max'].'}],';
}
?>}
function processTrigger(){
	type = $("[name=process]").find('option:selected').attr("data-type")
	$("[name=min]").val('');
	$("[name=max]").val('');
	if(type == 0){
			$("#category").show();
			$("#products").hide();
			$("[name=product]").val('0');
	}else{
			$("#products").show();
			$("#category").hide();
			$("[name=category]").val('0');
	}
	val = $("[name=process]").val();
	cat = $("[name=category]").val();
	pid = $("[name=product]").val();
	$.each(stock, function (index, data) {
		data = data[0];
		if(data['process'] == val && data['min']>=0){
			if((data['cat'] == cat && cat!=0) || (data['pid'] == pid && pid !=0)){
					$("[name=min]").val(data['min']);
					$("[name=max]").val(data['max']);
			}
		}
	});

}

function checkForm(){
	if($("[name=min]").val() <0 && $("[name=max]").val() <=0){
		event.preventDefault();
		alert("Invalid Input");
		return false;
	}
	
	cat = $("[name=category]").val();
	pid = $("[name=product]").val();
	
	if(cat==0 && pid==0){
		event.preventDefault();
		alert("Choose Item to Distribute");
		return false;
	}
}
</script>
    <?php include"footer.php"; ?>