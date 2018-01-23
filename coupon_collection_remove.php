<?php include"header.php"; ?>
<?php
if(isset($_POST['from'])){
	$from = $_POST['from'];
	$to   = $_POST['to'];
}else{
	$from = date('Y/m/d');
	$to   = date('Y/m/d');
}
?>
<form action="#" method="post">
<div class="box">
	<div class="box-head"><h3>Coupons</h3></div>
	<div class="box-content">
			<center>
			<table class="table table-bordered" style="width:250px;"><tr>
				<th><input name="from" type="text" class="input-small datepick" value="<?php echo $from ?>" /></th>
				<th>To</th>
				<th><input name="to" type="text" class="input-small datepick" value="<?php echo $to ?>" /></th>
				<th><input type="submit" name="submit" class="btn btn-blue4" ></th>
			</tr></table>
			</center>
			<hr><?php
			if(isset($_POST['update'])){
				$sql = "update
							coupon_collection as c,
							workshop as w
						set
							w.available_point=w.available_point-c.points
						where
							c.workshop=w.id and c.`id` in('".implode("','",$_POST['remove'])."');";
				$sql .= "UPDATE `coupon_collection` SET `is`='0' WHERE  `id` in(".implode(",",$_POST['remove']).");";
				$mysql->execute($sql);?>
				<center>
					<div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
						Removed Successfully
					</div>
				</center><?php
			}
			if(isset($from)){?>
				<table class="table table-bordered table-stripped">
				<thead>
					<th><input type="checkbox" id="selectAll" onclick="$('[name^=remove]').attr('checked',this.checked)" /></th>
					<th>Date</th><th>Staff</th><th>Workshop</th><th>Coupon</th><th>NOS</th><th>POINTS</th></thead><?php
				$sql = "select 
							c.id,
							c.date,
							s.name'sname',
							w.name'wname',
							ct.name'cname',
							c.nos,
							c.points 
						from 
							coupon_collection as c
							join marketting_staff_profile as s
								on c.staff=s.id
							left join workshop as w
								on c.workshop=w.id 
							join coupon_category as ct 
								on c.coupon=ct.id and c.is=1 
						where 
							c.date between '".$from."' and '".$to."'
						order by
							c.date desc";
				$rst = $mysql->execute($sql);
				while($r = mysqli_fetch_array($rst[0])){?>
					<tr>
						<td><input type="checkbox" name="remove[]" value="<?php echo $r['id'] ?>" /></td>
						<td><?php echo $r['date'] ?></td>
						<td><?php echo $r['sname'] ?></td>
						<td><?php echo $r['wname'] ?></td>
						<td><?php echo $r['cname'] ?></td>
						<td><?php echo $r['nos'] ?></td>
						<td><?php echo $r['points'] ?></td>
					</tr><?php
				}?>
				<tr><th colspan="7"><center><input type="submit" name="update" class="btn btn-blue4" ></center></th></tr>
				</table><?php
			}
			?>
	</div>
	</form>
</div>
<?php include"footer.php"; ?>