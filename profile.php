<?php
include_once"header.php";
?>
<?php
		if(isset($_REQUEST['id']))
		{
			$id = $_REQUEST['id'];
		}
		else
		{
			$id = $_SESSION['user_id'];
		}
		$sql = "select 
						pr.name,
						pr.phone_number,
						pr.e_mail,
						pr.phone_number,
						pr.address,
						pr.date_of_birth,
						pr.gender,
						pr.material_status,
						pr.image_source,
						d.name'type',
						pr.salary,
						pr.`it`,
						pr.`is` 
					from 
						profile as pr,
						designation as d 
					where 
						pr.id='".$id."' 
						and
						d.`id`=pr.`type`
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
                                <?php
								if(!file_exists($p['photo']))
								{
									if($p['gender']=='Male')
									$p['photo'] = 'img/default_prf_m.png';
									else if($p['gender']=='Female')
									$p['photo'] = 'img/default_prf_f.png';
									else
									$p['photo'] = 'img/default_prf.png';
								}
								?>
								<img src="<?php echo $p['image_source']; ?>" width="150px" alt="">
							</div>
							<div class="details pull-left userprofile">
								<table class="table table-striped table-detail">
									<tr>
										<th>Name: </th>
										<td><?php echo $p['name']; ?></td>
									</tr>
									<tr>
										<th>Register date:</th>
										<td><?php echo $p['it']; ?><td>
									</tr>
									<tr>
										<th>Designation:</th>
										<td><span class="label label-success"><?php echo $p['type']; ?></span> <span class="label label-warning"><?php echo $p['salary']; ?></span></td>
									</tr>
									<tr>
										<th>Email:</th>
										<td>
											<a href="#"><?php echo $p['e_mail']; ?></a>
										</td>
									</tr>
									<tr>
										<th>Phone Number:</th>
										<td><?php echo $p['phone_number']; ?></td>
									</tr>
									<tr>
										<th>Date Of Birth:</th>
										<td><?php echo $p['date_of_birth']; ?></td>
									</tr>
                                    <tr>
										<th>Maritial Status:</th>
										<td><?php echo $p['material_status']; ?></td>
									</tr>
                                    <tr>
										<th>Gender</th>
										<td><?php echo $p['gender']; ?></td>
									</tr>
                                    <tr>
										<th>Address:</th>
										<td><?php echo $p['address']; ?></td>
									</tr>
                                     <tr>
										<th>Current Status:</th>
										<td>
										<?php 
										if($p['status']==1)
										{
											echo 'Active';
										}
										elseif($p['status']==0)
										{
											echo 'In Active';
										}
										elseif($p['status']==2)
										{
											$sql = "select * from relieve as r where r.user_id='".$_POST['id']."' order by id desc limit 1;";
											$rst = $mysql->execute($sql);
											$r = mysqli_fetch_array($rst[0]);
											echo 'Relieved';
											?>
                                            </td>
                                            <tr>
												<th>Date Of Relieve:</th>
											<td><?php echo $r['date']; ?>
                                            </td>
                                            <tr>
												<th>Reason For Relieve:</th>
											<td><?php echo $r['reason']; ?>
                                            <?php
										}
										?>
                                        </td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php
include_once"footer.php";
?>