<?php include "header.php";?>

<script language="javascript" >
function usearch(val)
{
$.ajax({
			  type: 'POST',
			  url: 'ajax_uname.php',
			  data: {'pval':val       
			  },
			  success: function(data){ document.getElementById('us').innerHTML=data;
				  },
          error: function( xhr, tStatus, err ) {
			alert(err);
            }
							});

	}
</script>
<?php 
if(isset($_POST['submit']))
{
	$uname=$_POST['username'];
	$hname=$_POST['hname'];
	$pass=$_POST['pass'];
    $sql="update authendication set user_name='$uname' where user_name='$hname'";
	$mysql->execute($sql);
	ob_start();
	?>
    <script language="javascript">
	alert("You Logout from the Website");
	window.location="index.php?out=1";
	</script>
    <?php
	header('location:index.php?out=1');
	ob_flush();
	
}
?>
	<div class="content">
	  <div class="row-fluid">
		  <div class="span12">
					<div class="box">
						<div class="box-head tabs">
							<h3><?php echo $_SESSION['user_name']; ?> profile</h3>
</div>
                        <?php
						$sql="select b.user_name,a.e_mail,a.date_of_birth from profile as a,authendication as b where 					                              b.user_name='".$_SESSION['user_name']."' 
							  and a.id=b.user_id";
					    $sql1=$mysql->execute($sql);
						$r=mysqli_fetch_array($sql1[0])
						?> 
						<div class="box-content">
							<form action="user_profile.php" method="post" class="form-horizontal">
							<div class="tab-content">
								<div class="tab-pane active" id="basic">
										<div class="control-group">
											<label for="username" class="control-label">Username</label>
											<div class="controls">
												<pre><input type="text" onKeyUp="usearch(this.value)" onBlur="usearch(this.value)" name="username" id="username" value="<?php echo $r['user_name']  ?>"> <span id="us"></span></pre><input type="hidden" name="hname" id="username" value="<?php echo $_SESSION['user_name']  ?>">
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">Password</label>
											<div class="controls">
												<div class="input-append">
													<input type="password" name="pass" value="<?php echo $_SESSION['cpass'] ?>" readonly />
												</div>
												<a href="new_password.php" class="btn-danger btn">New password</a>
												<p class="help-block">The password is for security reasons hidden!</p>
											</div>
										</div>
								</div>
								
									
									

										</div>
								

								<div class="form-actions">
									<input  name="submit" type="submit" class='btn btn-blue4' value="Save">
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
