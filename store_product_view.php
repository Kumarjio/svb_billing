<?php
include_once"header.php";
?>
<?php
		if(isset($_REQUEST['id']))
		{
			
				$sql = "select 
					p.id,
					p.pid,
					p.name, 
					p.`type`,
					p.pur_price,
					p.mrp,
					p.`desc`,
					p.min_status,
					p.min_value
				from 
					store_products as p
				where 
					p.id='".$_REQUEST['id']."' 
					and
					p.`is`=1
				limit 1;
						";
			$rst = $mysql->execute($sql);
			$p = mysqli_fetch_array($rst[0]);
			?>
<div class="row-fluid">
			<div class="span12">
				<div class="box">
					<div class="box-head">
						<h3><?php echo $p['name']; ?></h3>
					</div>
					<div class="box-content">
						<div class="cl">
							<div class="pull-left">
								<h3><?php echo $p['name']; ?></h3>
							</div>
							<div class="details pull-left userprofile">
								<table class="table table-striped table-detail">
									<tr>
										<th>Product Id: </th>
										<td><?php echo $p['pid']; ?></td>
									</tr>
                                    <tr>
										<th>Name: </th>
										<td><?php echo $p['name']; ?></td>
									</tr>
									<tr>
										<th>Description:</th>
										<td><?php echo $p['desc']; ?></td>
									</tr>
									<tr>
										<th>Weightage Type:</th>
										<td>
											<?php echo $p['type']; ?>
										</td>
									</tr>
                                    <tr>
										<th>MRP:</th>
										<td><?php echo $mysql->currency($p['mrp']); ?></td>
									</tr>
									<tr>
										<th>Purchase Price:</th>
										<td><?php echo $mysql->currency($p['par_price']); ?></td>
									</tr>
                                    <tr>
										<th>Minimum Status:</th>
										<td><?php 
										if($p['min_status']==1) echo $p['min_value'].' '.$p['type']; 
                                        else echo 'Not Considered' ?></td>
									</tr>
                                    <tr>
										<th>Stock Status:</th>
										<td><?php 
										$sql = "select 
													sum(s.available)'qty',
													s.`type`
												from 
													store_stock as s
												where 
													s.`is`=1
													and
													s.pid='".$p['id']."'";
										$srst = $mysql->execute($sql);
										$stock = mysqli_fetch_array($srst[0]);
										if($stock['qty']>0)
											echo $stock['qty'].' '.$stock['type'];													
										else
											echo 'No Stock Available';
										?></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php
}
?>
<?php
include_once"footer.php";
?>