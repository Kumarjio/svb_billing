<?php
	include 'header.php';
?>
<?php
if(isset($_POST['load']))
{
	$sql = "select cs.id,cs.name,cs.phone,cs.address from customers as cs where cs.id=".$_POST['id'];
	$rst = $mysql->execute($sql);
	$r = mysqli_fetch_array($rst[0]);
	if($_GET['doy'])
	{
		?>
        <script type="text/javascript">
		//var address = '<?php //echo $r['address'] ?>';
		//address = address.replace(/\r\n|\r|\n/g,"<br />");
       window.opener.setCustomer(<?php echo $r['id'] ?>,'<?php echo $r['name'] ?>','<?php echo $r['phone'] ?>');
        </script>
        <?php
	}
}
?>
    <div class="container-fluid">
      <div class="content">
        <center>
          <div class="box">
            <div class="box-head">
              <h3>Dealer Details</h3>
            </div>
            <div class="box-content">
            <?php
				$sql = "select * from customers as c where c.`is`=1 ;";
				$rst = $mysql->execute($sql);
				?>				
            <div class="box">
						<div class="box-content box-nomargin">
							<div class="tab-content">
									<div class="tab-pane active" id="basic">
										<table class='table table-striped dataTable table-bordered'>
											<thead>
												<tr>
                                                	
													<th>Id</th>
                                                    <th>Name</th>
                                                    <th>Gender</th>
													<th>E-Mail</th>
													<th>Phone</th>
													<th>Photo</th>
													<th>DOB</th>
                                                    <th>Weeding Day</th>
                                                    <th>Address</th> 
                                                    <th>Load</th>                                                   
												</tr>
											</thead>
											<tbody>
                                            <?php
											while($r = mysqli_fetch_array($rst[0]))
											{
											?>
												<tr>
													
													<td><?php echo $r['id'] ?></td>
													<td><?php echo $r['name'] ?></td>
                                                    <td><?php echo $r['gender'] ?></td>													
													<td><?php echo $r['email'] ?></td>
                                                    <td><?php echo $r['phone'] ?></td>
                                                    <td>
              	<a href="<?php echo $r['photo']; ?>" class='preview_fancy'><img src="<?php echo $r['photo']; ?>" width="100px" alt="" title="Image title"></a>
              </td>
													<td><?php echo $r['dob'] ?></td>
													<td><?php echo $r['wedding_day'] ?></td>
                                                    <td><?php echo $r['address'] ?></td>                         
                                                    <td><form method="post">
                                                    	<input type="hidden" name="id" value="<?php echo $r['id'] ?>">       
                                                        <button type="submit" name="load" class="btn btn-blue4">Load</button>
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
        </center>
      </div>
    </div>
    <?php 
include_once"footer.php";
?>