<?php
include_once"header.php";
?>
<div class="container-fluid">
  <div class="content">
    <center>
<?php
	$sql = "select * from shop_detail as bd where bd.is =1;
			select pr.id,pr.name,pr.image_source from profile as pr where pr.`is`=1";
	$result = $mysql->execute($sql);
	$rst = mysqli_fetch_array($result[0]);
	$tot = mysqli_num_rows($result[1]);
	?>
    <div class="box">
					<div class="box-head">
						<h3><?php echo $rst['name'] ?></h3>
					</div>
					<div class="box-content">
						<div class="cl">
							<div class="pull-left" style="text-align:left">
								<img src="<?php echo $rst['logo_src'] ?>" style="height:150px" alt="">
							</div>
						</div>
						<h3 class='divide'>
                        Basic information &nbsp;
                        <a href="shop_config.php">
                        <button class="btn" name="edit"><i class=icon-pencil style="cursor:pointer">
                                                        </i>&nbsp;&nbsp;Edit</button>
						</a>
                        </h3>
						<table class="table table-striped table-detail" border="0" width="50%">
                       				<tr>
										<th>Location:</th>
										<td>
											<?php echo $rst['location'];	?>
										</td>
									</tr>
                                    <tr>
										<th>Address:</th>
										<td>
											<?php echo $rst['address'];	?>
										</td>
									</tr>
									<tr>
										<th width="20%">Opened date:</th>
										<td width="80%"><?php echo $mysql->date_format($rst['opening']); ?></td>
									</tr>
                                    <tr>
										<th>Investment:</th>
										<td><?php echo $mysql->currency($rst['investment']); ?></td>
									</tr>
									<tr>
										<th>Total No Of Employees:</th>
										<td><span class="label label-success"><?php echo $tot;	?></span></td>
									</tr>
									<tr>
										<th>Phone Number:</th>
										<td><?php echo $rst['phone']; ?></td>
									</tr>
                                    <tr>
										<th>Mail:</th>
										<td><?php echo $rst['email']; ?></td>
									</tr>
									<tr>
										<th>Fax:</th>
										<td><?php echo $rst['fax']; ?></td>
									</tr>
                                    <tr>
										<th>Tax Mode:</th>
										<td>
										<?php if($rst['tax_mode']==0) echo 'Product Wise';
											  else  echo 'Net Amount Wise' 
										?></td>
									</tr>
                                    <tr>
										<th>Tax:</th>
										<td><?php  echo $rst['tax']; ?>% <?php if($rst['tax_mode']==0) echo '( Not in Use )'; ?></td>
									</tr>
                                    <tr>
										<th>Rounf Off Mode:</th>
										<td>
										<?php if($rst['round_off']==0) echo 'Lower Round';
												else if($rst['round_off']==1) echo 'Upper Round';
													else if($rst['round_off']==2) echo 'Auto Round' 
										?></td>
									</tr>
                                    <tr>
										<th>Current Bill No:</th>
										<td><?php 
											$sql = "select 
														max(q.bill_no)'bill_no'
														
													from 
														bill_que as q
													where 
														q.is=1 		
													limit 1";
											$bill_no = $mysql->execute($sql);
											$bill_no = mysqli_fetch_array($bill_no[0]);
											echo $bill_no['bill_no'];
										 ?></td>
									</tr>
                                     <tr>
										<th>Current Purchase No:</th>
										<td><?php echo $rst['cur_pur_no']; ?></td>
									</tr>
                                    <tr>
										<th>Current Wastage No:</th>
										<td><?php echo $rst['cur_was_no']; ?></td>
									</tr>
                                     <tr>
										<th>Bank Name:</th>
										<td><?php echo $rst['bank_name']; ?></td>
									</tr>
                                     <tr>
										<th>Bank Account NO:</th>
										<td><?php echo $rst['bank_acc_no']; ?></td>
									</tr>
                                     <tr>
										<th>Bank Account Name:</th>
										<td><?php echo $rst['bank_acc_name']; ?></td>
									</tr>
                                     <tr>
										<th>Branch:</th>
										<td><?php echo $rst['bank_branch']; ?></td>
									</tr>
								</table>
                        <div style="text-align:left; padding-left:150px;">
                        <h3>Employees In Our Shop</h3>
                        <?php 
						while($rst1 = mysqli_fetch_array($result[1]))
						{
							?>
							<a href="profile.php?id=<?php echo $rst1['id'] ?>" >
                            <h3><i class="icon-hand-right"></i>&nbsp;&nbsp;
                            <img src="<?php echo $rst1['image_source'] ?>" style="height:25px;">
							<?php echo $rst1['name']; ?></h3>
                            </a>
                         <?php 
						}
						?>
                        </div>
					</div>
				</div>
</center>
</div>
</div>
<?php
include_once"footer.php";
?>