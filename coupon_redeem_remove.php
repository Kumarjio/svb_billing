<?php include"header.php"; ?>
<div class="box">
	<div class="box-head"><h3>Redeem Removal</h3></div>
	<div class="box-content">
			<center><form action="#" method="post">
			<table class="table table-bordered" style="width:250px;"><tr>
				<th><input name="from" type="text" class="input-small datepick" /></th>
				<th>To</th>
				<th><input name="to" type="text" class="input-small datepick" /></th>
				<th><input type="submit" name="submit" class="btn btn-blue4" ></th>
			</tr></table></form>
			</center>
			<hr><?php
			if(isset($_POST['update'])){
				$sql = "update
							coupon_redeem_workshop as r,
							workshop as w
						set
							w.available_point=w.available_point+r.points
						where
							r.workshop=w.id and  r.`id` in(".implode(",",$_POST['remove']).");";
				$sql .= "UPDATE `coupon_redeem_workshop` SET `is`='0' WHERE  `id` in(".implode(",",$_POST['remove']).");";
				$mysql->execute($sql);
			}
			if(isset($_POST['submit'])){?>
				<form action="#" method="post">
				<table class="table table-bordered table-stripped">
				<thead><th></th><th>Date</th><th>Workshop</th><th>POINTS</th></thead><?php
				$sql = "select 
							r.id,
							w.name'wname',
							r.date,
							r.points,
							r.notes 
						from 
							coupon_redeem_workshop as r,
							workshop as w
						where
							r.workshop=w.id and r.is=1 and
							r.date between '".$_POST['from']."' and '".$_POST['to']."'";
				$rst = $mysql->execute($sql);
				while($r = mysqli_fetch_array($rst[0])){?>
					<tr>
						<td><input type="checkbox" name="remove[]" value="<?php echo $r['id'] ?>" /></td>
						<td><?php echo $r['date'] ?></td>
						<td><?php echo $r['wname'] ?></td>
						<td><?php echo $r['points'] ?></td>
					</tr><?php
				}?>
				<tr><th colspan="7"><center><input type="submit" name="update" class="btn btn-blue4" ></center></th></tr>
				</table></form><?php
			}
			?>
	</div>
</div>
<?php include"footer.php"; ?>