<?php
include_once"header.php";
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
										<table class='table table-striped dataTable table-bordered dataTable-tools'>
											<thead>
												<tr>
                                                	<th>Image</th>
													<th>Id</th>
													<th>Name</th>
													<th>Phone</th>
													<th>E-Mail</th>
													<th>Address</th>
                                                    <th>DOB</th>
                                                    <th>Salary</th>
                                                    <th></th>
                                                    <th></th>
												</tr>
											</thead>
											<tbody>
                                            <?php
											while($r = mysqli_fetch_array($rst[0]))
											{
											?>
												<tr>
													<td>
              	<a href="<?php echo $r['image_source']; ?>" class='preview_fancy'><img src="<?php echo $r['image_source']; ?>" width="100px" alt="" title="Image title"></a>
              </td>
													<td><?php echo $r['id'] ?></td>
													<td><?php echo $r['name'] ?></td>
													<td><?php echo $r['phone_number'] ?></td>
													<td><?php echo $r['e_mail'] ?></td>
                                                    <td><?php echo $r['address'] ?></td>
													<td><?php echo $r['date_of_birth'] ?></td>
                                                    <td><?php echo $mysql->currency($r['salary']) ?></td>
                                                    <td><form action="user_edit.php" method="post">
                                                    	<input type="hidden" name="id" value="<?php echo $r['id'] ?>">
                                                        <button type="submit" style="border:#FFF 0px solid">
                                                        <i class=icon-pencil style="cursor:pointer">
                                                        </i>
                                                        </button>
                                                    	</form>
                                                    </td>
                                                    <td><form action="profile.php" method="post">
                                                      <input type="hidden" name="id" value="<?php echo $r['id'] ?>">
                                                      <button type="submit" class="btn btn-green4">
                                                      View Profile
                                                    </form></td>
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