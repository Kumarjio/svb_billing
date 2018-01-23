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
        <h3>PRODUCTION PROCESS</h3>
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
              <th colspan="2" style="text-align:center">
              	<input type="radio" name="damage" value="0" checked /> Stock
                <input type="radio" name="damage" value="1"/> Damage
                <input type="radio" name="damage" value="2"/> Wrong Entry
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
			$sql = "select * from production_process where `type`=1 order by `order` desc limit 1";
			$rst = $mysql->execute($sql);
			$r = mysqli_fetch_array($rst[0]);
			$sql = "select 
						p2.cid,
						if(p1.`order`=0,-1,if(p2.stock is null,0,p2.stock))'stock'
					from 
					(	
						select 
							p.id,
							c.id'category',
							p1.id'pid',
							p.`type`,
							p.`order`
						from 
							production_process as p 
							left join production_category as c 
								on p.`type`=0
							left join product as p1
								on p.`type`=1
					) as p1 left join
					(select 
						p.id,
						p.`order`,	
						c.id'cid',
						c.category,
						c.pid,
						c.stock
					from 
						production_process as p,
						production_process_category as c
					where
						p.id=c.`process`
						) as p2
					on if(p1.`type`=0,p1.category=p2.category,p1.pid=p2.pid) and p2.order = p1.order-1
					where p1.id=".$_POST['process']." and (p1.category=".$_POST['category']." or p1.pid=".$_POST['product'].")";
			$srst = $mysql->execute($sql);
			$sr = mysqli_fetch_array($srst[0]);
			if($sr['cid']>0){
				$sql = "update production_process_category set stock=stock-".intval($_POST['qty'])." where `id`=".$sr['cid'];
				$mysql->execute($sql);
			}
			if($_POST['process'] == 4){
				$sql = "update production_process_category set stock=stock-".intval($_POST['qty'])." where `process`=3 and category=".$_POST['category'];
				$prevCat = $_POST['category'];
				$_POST['category'] = 0;
				$mysql->execute($sql);
			}
			if($_POST['damage']==0){
				if($r['id'] == $_POST['process']){
					$sql = "UPDATE `product` SET `available`=`available`+".intval($_POST['qty'])." WHERE  `id`=".$_POST['product'].";";
					$mysql->execute($sql);
				}
				$sql = "INSERT INTO `production_process_category` 
							(`process`, `category`, `pid`, `stock`) 
						VALUES 
							(".intval($_POST['process']).", ".intval($_POST['category']).", ".intval($_POST['product']).", ".intval($_POST['qty']).")
						ON DUPLICATE KEY update stock=stock+".intval($_POST['qty'])." ";
				$rst = $mysql->execute($sql);
				$sql = "INSERT INTO `production_process_stock` 
							(`date`, `staff`, `process`, `category`,`preCat`, `pid`, `qty`)
						VALUES 
							('".$_POST['date']."', ".intval($_POST['staff']).", ".intval($_POST['process']).", ".intval($_POST['category']).", ".intval($prevCat).", ".intval($_POST['product']).", ".intval($_POST['qty']).")";
				$rst = $mysql->execute($sql);?>
				<div class="alert alert-block alert-success"> 
					<a class="close" data-dismiss="alert" href="#">×</a>
					Production Stock Updated Successfully
				</div><?php
			}else{
				$sql = "INSERT INTO `production_process_stock` 
							(`date`, `staff`, `process`, `category`, `pid`, `qty`,`damage`)
						VALUES 
							('".$_POST['date']."', ".intval($_POST['staff']).", ".intval($_POST['process']).", ".intval($_POST['category']).", ".intval($_POST['product']).", ".intval($_POST['qty']).",".intval($_POST['damage']).")";
				$rst = $mysql->execute($sql);
			}
		}
		

$sql = "select
			s.date,
			pf.name'staff',
			p.name,
			s.damage,
			if(c.name is null,pr.pid,c.name)'category',
			s.qty
		from
			".$_SESSION['production_table']." as s 
			join production_process as p
				on s.`process`=p.id and s.damage=0 and s.date = '".$date."'
			join profile as pf 
				on s.staff=pf.id
			left join production_category as c
				on s.category=c.id
			left join product as pr
				on s.pid=pr.id
		order by
			pf.name,
			p.`type`,
			p.`order`,
			c.name,
			pr.name";
$rst = $mysql->execute($sql);
$process 	 = array();
$processData = array();
while($r = mysqli_fetch_array($rst[0])){
	$processData[$r['staff']][$r['name']][$r['category']] += $r['qty'];
	$process[$r['name']][$r['category']] = $r['category'];
}
$sum = array();
?>
<table class="table table-bordered table-striped">
<thead>
	<tr>
    	<th rowspan="2">STAFF/PRODUCTION</th><?php 
		foreach($process as $pname=>$data){ 
			echo '<th colspan="'.count($data).'">'.$pname.'</th>';
		}?>
    </tr>
    <tr><?php 
		foreach($process as $pname=>$data){ 
			foreach($data as $category){	
				echo '<th>'.$category.'</th>';
			}
		}?>
    </tr>
</thead>
<tbody><?php
	foreach($processData as $staff=>$pdata){  ?>
	<tr><td><?php echo $staff ?></td><?php 
		foreach($process as $pname=>$data){ 
			foreach($data as $category){	
				echo '<td style="text-align:center">'.intval($pdata[$pname][$category]).'</td>';
				$sum[$pname][$category] += $pdata[$pname][$category];
			}
		}?>
    </tr><?php
	} ?>
</tbody>
<tfoot>
	<tr><th>Total</th><?php 
		foreach($process as $pname=>$data){ 
			foreach($data as $category){	
				echo '<th style="text-align:center">'.$sum[$pname][$category].'</th>';
			}
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
$sql = "select 
			p1.id'process',
			p1.`order`,
			if(p1.category is null,0,p1.category)'category',
			if(p1.pid is null,0,p1.pid)'pid',
			if(p1.`order`=0,-1,if(p2.stock is null,0,p2.stock))'stock'
		from 
		(	
			select 
				p.id,
				c.id'category',
				p1.id'pid',
				p.`type`,
				p.`order`
			from 
				production_process as p 
				left join production_category as c 
					on p.`type`=0
				left join product as p1
					on p.`type`=1
		) as p1 left join
		(select 
			p.id,
			p.`order`,
			c.category,
			c.pid,
			c.stock
		from 
			production_process as p,
			production_process_category as c
		where
			p.id=c.`process`
			) as p2
		on if(p1.`type`=0,p1.category=p2.category,p1.pid=p2.pid) and p2.order = p1.order-1
	";
$rst = $mysql->execute($sql);
while($r = mysqli_fetch_array($rst[0])){
		//echo '"'.$r['process'].'": [{"cat":'.$r['process'].',"pid":'.$r['process'].',"stock":'.$r['stock'].'}],';
		echo '"'.++$sno.'": [{"process":'.$r['process'].',"cat":'.$r['category'].',"pid":'.$r['pid'].',"stock":'.$r['stock'].'}],';
}
?>}
var preStock = {<?php
$sql = "select 
			p.id,
			p.`order`,
			pc.id'category',
			if(c.stock is null,0,c.stock)'stock'
		from 
			production_process as p
			join production_category as pc
			left join production_process_category as c
				on	p.id=c.`process` and c.category=pc.id 
		where
			 p.id=3";
$rst = $mysql->execute($sql);
$sno = 0;
while($r = mysqli_fetch_array($rst[0])){
		//echo '"'.$r['process'].'": [{"cat":'.$r['process'].',"pid":'.$r['process'].',"stock":'.$r['stock'].'}],';
		echo '"'.++$sno.'": [{"process":'.$r['id'].',"cat":'.$r['category'].',"stock":'.$r['stock'].'}],';
}
?>}
function processTrigger(){
	type = $("[name=process]").find('option:selected').attr("data-type")
	if(type == 0){
		$("#category").show();
		$("#products").hide();
		$("[name=product]").val('0');
	}else if($("[name=process]").val() == 4){
		$("#products").show();
	}else{
		$("#products").show();
		$("#category").hide();
		$("[name=category]").val('0');
	}
	val = $("[name=process]").val();
	cat = $("[name=category]").val();
	pid = $("[name=product]").val();
	qty = $("[name=qty]").val();
	if(val == 4){
		preVal = val-1;
		$.each(preStock, function (index, data) {
			data = data[0];
			if(data['process'] == preVal && data['stock']>=0 && data['cat'] == cat && cat!=0){
				$("#stockAvailable").html(data['stock']+" Qty.");
				if(data['stock'] == 0){
					$("[name=qty]").attr("readonly",true);
				}else{
					$("[name=qty]").attr("readonly",false);	
				}
				if(qty >data['stock']){
					$("[name=qty]").val(0);
					alert("Stock is not available.. Available Stock: "+data['stock']);
				}
			}
		});
	}
	if(val != 1)
	$.each(stock, function (index, data) {
		data = data[0];
		
		if(data['process'] == val && data['stock']>=0){
			if((data['cat'] == cat && cat!=0) || (data['pid'] == pid && pid !=0)){
							console.log(data);
				$("#stockAvailable").html(data['stock']+" Qty.");
				if(data['stock'] == 0){
					$("[name=qty]").attr("readonly",true);
				}else{
					$("[name=qty]").attr("readonly",false);	
				}
				if(qty >data['stock']){
					$("[name=qty]").val(0);
					alert("Stock is not available.. Available Stock: "+data['stock']);
				}
			}
		}
	});	

	
}

function checkForm(){
	if($("[name=qty]").val() <=0){
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