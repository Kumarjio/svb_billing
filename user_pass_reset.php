<?php
include_once"header.php";
?>
<?php
if(isset($_POST['reset']))
{
	$id = $_POST['id'];
	$branch = $_POST['branch'];
	$sql = $mysql->execute("update authendication set password = md5('svb') where user_id = $id;");
	?>
    <script type="text/javascript">
	alert("Password Reset to 'arvind' Successfully");
	</script>
    <?php
}
?>
<title>
Employee Details
</title>
<div class="container-fluid">
  <div class="content">
    <center>
    <div class="box">
      <div class="box-head">
        <h3>Employees Details</h3>
      </div>
      <div class="box-content">
        <?php
			$sql = "select * from profile as pr where pr.`is`=1 ;";
			$rst = $mysql->execute($sql);
			?>
            <div class="box">
						<div class="box-content box-nomargin">
							<div class="tab-content">
									<div class="tab-pane active" id="basic">
										<table class='table table-striped table-bordered'>
											<thead>
												<tr>
                                                	<th>Image</th>
													<th>Id</th>
													<th>Name</th>
													<th>Phone</th>
                                                    <th>Designation</th>
                                                    <th>Salary</th>
                                                    <th>Edit</th>
												</tr>
											</thead>
											<tbody>
                                            <?php
											while($r = mysqli_fetch_array($rst[0]))
											{
											?>
												<tr>
													<td>
              	<a href="<?php echo $r['image_source']; ?>" class='preview fancy'><img src="<?php echo $r['image_source']; ?>" width="100px" alt="" title="Image title"></a>
              </td>
													<td><?php echo $r['id'] ?></td>
													<td><?php echo $r['name'] ?></td>
													<td><?php echo $r['phone_number'] ?></td>
													<td><?php echo $r['type'] ?></td>
                                                    <td><?php echo $r['salary'] ?></td>
                                                    <td><form action="user_pass_reset.php" method="post">
                                                    	<input type="hidden" name="id" value="<?php echo $r['id'] ?>">
                                                        <button name="reset" type="submit" class="btn btn-red4">Reset</button>
                                                    	</form>
                                                    </td>
												</tr> 
                                            <?php
											}
											?>
											</tbody>
										</table>
									</div>
							</div>
						</div>
					</div>
      </div>
    </div>
  </div>
</div>
</div>
<?php
include_once"footer.php";
?>