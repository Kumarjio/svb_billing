<?php include"header.php"; ?>
<div class="box">
	<div class="box-head"><h3>Coupons Collection</h3></div>
	<div class="box-content">
			<center><form action="#" method="post">
			<table class="table table-bordered" style="width:350px;"><tr>
				<th><input name="from" type="text" class="input-small datepick" value="<?php echo $_POST['from'] ?>" /></th>
				<th>To</th>
				<th><input name="to" type="text" class="input-small datepick" value="<?php echo $_POST['to'] ?>" /></th>
				<th>Workshop</th>
				<th>
				<?php
				$sql = "select w.id,w.wno,w.name from workshop as w";
				$rst = $mysql->execute($sql);
				?>
				<select name="workshop[]" class="cho input-large" multiple><?php
				while($r = mysqli_fetch_array($rst[0])){ ?>
					<option value="<?php echo $r['id'] ?>" <?php if(in_array($r['id'],$_POST['workshop'])) echo 'selected'; ?>><?php echo $r['wno']." - ".$r['name'] ?></option><?php
				} ?>
				</select>
				</th>
				<th style="white-space:nowrap">Per %</th>
				<th><input name="per" type="text" class="input-small" style="text-align:right" placeholder="Amount %" value="<?php echo $_POST['per']==''?'75':$_POST['per'] ?>" /></th>
				<th><input type="submit" name="submit" class="btn btn-blue4" ></th>
			</tr></table></form>
			</center>
			<hr><?php
			if(isset($_POST['submit'])){
				$sql = "select w.id,w.opening from workshop as w where w.id in(".implode(",",$_POST['workshop']).")";
				$openingBalance = array();
				$rst = $mysql->execute($sql);
				while($r = mysqli_fetch_array($rst[0])){
					$openingBalance[$r['id']] = $r['opening'];
				}
				$sql = "select 
							c.id,
							w.id'wid',
							c.groupid,
							c.date,
							s.name'sname',
							w.wno,
							if(w.name is null,'-',w.name)'wname',
							ct.id'cid',
							ct.name'cname',
							c.nos,
							ct.points 
						from 
							coupon_collection as c
							join marketting_staff_profile as s on c.staff=s.id 
							left join workshop as w on c.workshop=w.id 
							join coupon_category as ct on c.coupon=ct.id and c.is=1 and c.date between '".$_POST['from']."' and '".$_POST['to']."'";
				if(count($_POST['workshop'])){
					$sql .= " where w.id in(".implode(",",$_POST['workshop']).")";
				}
				echo $sql;
				$header = array();
				$content = array();
				$rst = $mysql->execute($sql);
				while($r = mysqli_fetch_array($rst[0])){
					$header[$r['cid']] = $r['cname'];
					$content[$r['groupid']]['wid'] = $r['wid'];
					$content[$r['groupid']]['basic'] = array($r['date'],$r['sname'],$r['wno']." - ".$r['wname']);
					$content[$r['groupid']]['coupon'][$r['cid']] = $r['nos'];
					$content[$r['groupid']]['points'][$r['cid']] = $r['nos']*$r['points'];
				}
				?>
				<form action="#" method="post">
				<table class="table table-bordered table-stripped">
				<thead>
					<th>Date</th><th>Staff</th><th>Workshop</th><?php
					foreach($header as $hid=>$hname){ ?>
						<th><?php echo $hname ?></th><?php
					}?>
					<th>Total Nos</th>
					<th>Total Points</th>
					<th>Amount(<?php echo $_POST['per'] ?>%)</th>
				</thead><tbody><?php
				$netNos    = array();
				$netPoints = 0;
				foreach($content as $cont){			
					$totalPoints = 0;
					$totalNos 	 = 0;
					if(intval($openingBalance[$cont['wid']])>0){?>
						<tr>
							<td style="text-align:center"><?php echo $cont['basic'][0] ?></td>
							<td><?php echo $cont['basic'][1] ?></td>
							<td><?php echo $cont['basic'][2] ?></td>
							<td colspan="<?php echo count($header)+1 ?>"  style="text-align:right">Opening Balance</td>
							<td style="text-align:right"><?php echo $totalPoints = $openingBalance[$cont['wid']];  ?></td>
							<td style="text-align:right"><?php echo number_format((float)($totalPoints/100)*$_POST['per'], 2, '.', '');  ?></td>
						</tr><?php
						$openingBalance[$cont['wid']] = 0;
					}
					?>
					<tr>
						<td style="text-align:center"><?php echo $cont['basic'][0] ?></td>
						<td><?php echo $cont['basic'][1] ?></td>
						<td><?php echo $cont['basic'][2] ?></td><?php
						foreach($header as $hid=>$hname){ ?>
							<td style="text-align:right"><?php echo $cont['coupon'][$hid] ?></td><?php
							$totalPoints 		+= $cont['points'][$hid];
							$totalNos 			+= $cont['coupon'][$hid];
							$netNos[$hid] 		+= $totalNos;
						}?>
						<td style="text-align:right"><?php echo $totalNos ?></td>
						<td style="text-align:right"><?php echo $totalPoints;  ?></td>
						<td style="text-align:right"><?php echo number_format((float)($totalPoints/100)*$_POST['per'], 2, '.', '');  ?></td>
					</tr><?php
					$netPoints		 	+= $totalPoints;
				}?></tbody>
				<tfoot>
					<tr>
						<th colspan="3" style="text-align:right" >Total</th>
						<?php
						$totalPoints = 0;
						$totalNos 	 = 0;
						foreach($header as $hid=>$hname){ ?>
							<th style="text-align:right"><?php echo $netNos[$hid] ?></th><?php
							$totalNos 			+= $netNos[$hid];
						}?>
						<th style="text-align:right"><?php echo $totalNos ?></th>
						<th style="text-align:right"><?php echo $netPoints ?></th>
						<th style="text-align:right"><?php echo number_format((float)($netPoints/100)*$_POST['per'], 2, '.', '') ?></th>
					</tr>
				</tfoot>
				</table>
				<center><input type="submit" name="update" class="btn btn-blue4" ></center></form><?php
			}
			?>
	</div>
</div>
<?php include"footer.php"; ?>