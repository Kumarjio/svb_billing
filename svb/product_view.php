<?php
include_once"header.php";
?>
<?php
		if(isset($_REQUEST['id']))
		{
			
			   $sql = "select 
					p.id,
					p.pid,
					p.gid,
					p.cid,
					p.bid,
					p.name, 
					p.name1, 
					p.name2,
					p.name_default, 
					p.`type`,
					p.price,
					p.par_price,
					p.price_src,
					p.mrp,
					p.photo,
					p.vat,
					p.`desc`,
					p.min_status,
					p.min_value
				from 
					product as p
				where 
					p.id='".$_REQUEST['id']."' 					
				limit 1;
				
				select 
					id,name 
				from 
					product_category 
				where 
					`is`=1; 
				select 
					id,name 
				from 
					product_group
				where 
					`is`=1;	";
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
								<h3><?php
								if(file_exists($p['photo']))
									$photo = $p['photo'];
								else
									$photo = 'img/images.jpg';
								?></h3>
								<img src="<?php echo $photo; ?>" style="width:150px" alt="">
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
										<th>Name1: </th>
										<td><?php echo $p['name1']; ?></td>
									</tr>
                                    <tr>
										<th>Name2: </th>
										<td><?php echo $p['name2']; ?></td>
									</tr>
                                    <tr>
										<th>Default Name: </th>
										<td><?php 
										if($p['name_default']==0)
												echo $p['name'];
										else if($p['name_default']==1)
												echo $p['name1'];
										else
												echo $p['name2'];
										 ?></td>
									</tr>
									<tr>
										<th>Brand:</th>
										<td><?php 
										$sql = "select 
													name 
												from 
													product_brand
												where 
													`id`='".$p['bid']."';";
										$r = $mysql->execute($sql);
										$br = mysqli_fetch_array($r[0]);
										echo $br['name']; ?><td>
									</tr>
                                    <tr>
										<th>Category:</th>
										<td><?php 
										$sql = "select 
													name 
												from 
													product_category
												where 
													`id`='".$p['cid']."';";
										$r = $mysql->execute($sql);
										$ct = mysqli_fetch_array($r[0]);
										echo $ct['name']; ?><td>
									</tr>
                                    <tr>
										<th>Group:</th>
										<td><?php 
										$sql = "select 
													name 
												from 
													product_group
												where 
													`id`='".$p['gid']."';";
										$r = $mysql->execute($sql);
										$gp = mysqli_fetch_array($r[0]);
										echo $gp['name']; ?><td>
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
										<th>VAT %</th>
										<td><?php echo $p['vat']; ?></td>
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
										<th>Selling Price:</th>
										<td><?php echo $mysql->currency($p['price']); ?></td>
									</tr>
                                    <tr>
										<th>Price Source:</th>
										<td><?php if($p['price_src']==0)
														echo 'Purchased Rate';
												  else
														echo 'Current Rate'; ?></td>
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
													stock as s
												where 
													s.`is`=1
													and
													s.pid='".$p['id']."'";
										$srst = $mysql->execute($sql);
										$stock = mysqli_fetch_array($srst[0]);
										if($stock['qty']>0)
											echo round($stock['qty'],2).' '.$stock['type'];													
										else
											echo 'No Stock Available';
										?></td>
									</tr>
								</table>
                                <?php
								$sql = "SELECT  
											`name`,  `value`
										FROM 
											`product_property` 
										WHERE `pid` = '".$p['id']."';";
								$prt = $mysql->execute($sql);
								if(mysqli_num_rows($prt[0])>0)
								{
									?>
                                    <strong>Properties:</strong><br><br>
                                    <table class="table table-striped">
                                    <?php
									while($prop = mysqli_fetch_array($prt[0]))
									{
										?>
                                        <tr><th><?php echo ++$sno; ?></th><th><?php echo $prop['name'] ?></th><td><?php echo $prop['value'] ?></td></tr>
                                        <?php
									}
									?>
                                    </table>
                                    <?php
								}
								?>
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