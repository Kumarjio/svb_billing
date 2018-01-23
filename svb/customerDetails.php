<?php
	include 'header.php';
?>

    <div class="container-fluid">
      <div class="content">
        <center>
          <div class="box">
            <div class="box-head">
              <h3>Customer Details</h3>
            </div>
            <div class="box-content">
          	<?php
				if(isset($_POST['remove_id']))
				{
					$id=$_POST['remove_id'];
					$sql="UPDATE `customers` SET `is`='0' WHERE `id`=$id";
					$mysql->execute($sql);
				}
			?>
            <?php
				$sql = "select * from customers as c where c.`is`=1 order by c.name ;";
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
                                                    <th>Discount</th>
                                                    <th>Gender</th>
													<th>E-Mail</th>
                                                    <th>Phone</th>
                                                    <th>Tin No</th>
                                                    <th>CST No</th>
                                                    <th>CST Date</th>
													<th>DOB</th>
                                                    <th>Weeding Day</th>
                                                    <th>Address</th> 
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
													<td><?php echo $r['name'] ?></td>
                                                    <td><?php echo $r['discount'] ?></td>
                                                    <td><?php echo $r['gender'] ?></td>													
													<td><?php echo $r['email'] ?></td>
                                                    <td><?php echo $r['phone'] ?></td>
                                                    <td><?php echo $r['tin_no'] ?></td>
                                                    <td><?php echo $r['cst_no'] ?></td>
                                                    <td><?php echo $r['cst_date'] ?></td>
													<td><?php echo $r['dob'] ?></td>
													<td><?php echo $r['wedding_day'] ?></td>
                                                    <td><?php echo $r['address'] ?></td>                         
                                                    <td><form action="customer_edit.php" method="post">
                                                    	<input type="hidden" name="id" value="<?php echo $r['id'] ?>">       
                                                        <button type="submit" style="border:#FFF 0px solid">
                                                        <i class=icon-pencil style="cursor:pointer">
                                                        </i>
                                                        </button>
                                                    	</form>
                                                    </td>
                                                    <td >
                                                    <form action="customerDetails.php" method="post" onSubmit="confirmDel(event)">
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