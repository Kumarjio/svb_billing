<?php include "header.php";?>
<div class="content">
	  <div class="row-fluid">
		  <div class="span12">
					<div class="box">
						<div class="box-head">
							<h3><b><?php echo $_SESSION['user_name']; ?></b>  Change Password</h3>
</div>
                        <?php
						if(isset($_POST['Save']) && $_POST['old_password']==$_POST['confirm_password'])
						{
							$old_password=md5($_POST['old_password']);
							$new_password=md5($_POST['new_password']);
							$sql="select * from authendication where user_name='".$_SESSION['user_name']."' ";
							$sql1=$mysql->execute($sql);
							$r=mysqli_fetch_array($sql1[0]);
							if($old_password==$r['password'])
							{
								$sql2="update authendication set password='$new_password' where user_name='".$_SESSION['user_name']."' ";
								$mysql->execute($sql2);
								echo "<script type=text/javascript>
									alert('Password Updated Successfully');
									</script>";
							}
							
						}
						?> 
                        
						<div class="box-content">
							<form action="new_password.php" method="post" class="form-horizontal">
							<div class="tab-content">
								<div class="tab-pane active" id="basic">
                                		<div class="control-group">
											<label class="control-label"><h5>Old Password</h5></label>
											<div class="controls">
												<div class="input-append">
													<input type="password" name="old_password" />
												</div>												
											</div>
										</div>
                                        <div class="control-group">
											<label class="control-label"><h5>Confirm Old Password</h5></label>
											<div class="controls">
												<div class="input-append">
													<input type="password" name="confirm_password" />
												</div>												
											</div>
										</div>
										
										<div class="control-group">
											<label class="control-label"><h5>New Password</h5></label>
											<div class="controls">
												<div class="input-append">
													<input type="password" name="new_password" />
												</div>												
											</div>
										</div>
								</div>
								
									
									

										</div>
								

								<div class="form-actions">
									<input  name="Save" type="submit" class='btn btn-blue4' value="Save">
									<input type="reset" class='btn btn-danger' value="Reset">
								</div>
							</form>
						</div>
			</div>
		</div>
	  </div>
</div>	
	</div>
</div>
<?php include "footer.php";?>