<?php
	include 'header.php';
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
				if(isset($_POST['remove_id']))
				{
					$id=$_POST['remove_id'];
					$sql="UPDATE `dealer` SET `is`='0' WHERE `id`=$id";
					$mysql->execute($sql);
				}
			?>
            <?php
				$sql = "select * from dealer as d where d.`is`=1 ;";
				$rst = $mysql->execute($sql);
				?>				
            <div class="box">
						<div class="box-content box-nomargin">
							<div class="tab-content">
									<div class="tab-pane active" id="basic">
										<table class='table table-striped dataTable table-bordered dataTable-tools'>
											<thead>
												<tr>
                                                	
													<th>Id</th>
                                                    <th>Name</th>
													<th>E-Mail</th>
													<th>Dealer Phone</th>
													<th>Photo</th>
													<th>Company Name</th>
                                                    <th>Company Phone</th>
                                                    <th>Company Address</th> 
                                                    <th>Edit</th>                                                   
                                                    <th>Remove</th>
												</tr>
											</thead>
											<tbody>
                                            <?php
											while($r = mysqli_fetch_array($rst[0]))
											{
											?>
												<tr>
													
													<td><?php echo $r['id'] ?></td>
													<td><?php echo $r['dealer_name'] ?></td>													
													<td><?php echo $r['email'] ?></td>
                                                    <td><?php echo $r['dealer_phone'] ?></td>
                                                    <td>
              	<a href="<?php echo $r['photo']; ?>" class='preview_fancy'><img src="<?php echo $r['photo']; ?>" width="100px" alt="" title="Image title"></a>
              </td>
													<td><?php echo $r['company_name'] ?></td>
													<td><?php echo $r['company_phone'] ?></td>
                                                    <td><?php echo $r['company_address'] ?></td>                         
                                                    <td><form action="dealer_edit.php" method="post">
                                                    	<input type="hidden" name="id" value="<?php echo $r['id'] ?>">       
                                                        <button type="submit" style="border:#FFF 0px solid">
                                                        <i class=icon-pencil style="cursor:pointer">
                                                        </i>
                                                        </button>
                                                    	</form>
                                                    </td>
                                                    <td >
                                                    <form action="dealerDetails.php" method="post" onSubmit="confirmDel(event)">
                                                        <input type="hidden" name="remove_id" value="<?php echo $r['id'] ?>">
                                                    	<button type="submit" style="border:#FFF 0px solid">
                                                        <i class=icon-trash style="cursor:pointer">
                                                        </i></button>
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