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
					*
				from customers as c 
				where 
					id = '".$id."'
				limit 1 
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
								<img src="<?php echo $p['photo']; ?>" width="150px" alt="">
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
										<th>Gender:</th>
										<td>
											<a href="#"><?php echo $p['gender']; ?></a>
										</td>
									</tr>
									<tr>
										<th>Email:</th>
										<td>
											<a href="#"><?php echo $p['email']; ?></a>
										</td>
									</tr>
									<tr>
										<th>Phone Number:</th>
										<td><?php echo $p['phone']; ?></td>
									</tr>
									<tr>
										<th>Date Of Birth:</th>
										<td><?php echo $p['dob']; ?></td>
									</tr>
                                    <tr>
										<th>Wedding Day:</th>
										<td><?php echo $p['wedding_day']; ?></td>
									</tr>
                                    <tr>
										<th>Gender</th>
										<td><?php echo $p['gender']; ?></td>
									</tr>
                                    <tr>
										<th>Address:</th>
										<td><?php echo $p['address']; ?></td>
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