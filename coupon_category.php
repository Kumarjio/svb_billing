<?php
include_once"header.php";
?>
<div class="container-fluid">
  <div class="content">
    <center>
    <div class="box" style="width:60%;">
      <div class="box-head">
        <h3>Coupon Category Master</h3>
      </div>
      <div class="box-content">				
		<?php
		if(isset($_POST['name']))
		{
			if(isset($_POST['name']))
				$name = $_POST['name'];
			if(isset($_POST['points']))
				$points = $_POST['points'];
			if(isset($_POST['amount']))
				$amount = $_POST['amount'];
				
			if(intval($_POST['id'])>0){
				$sql = "update 
							`coupon_category` 
						set
							`name`='$name', `points`='$points', `amount`='$amount'
						where	
							id=".$_POST['id'].";";
				$result = $mysql->execute($sql);?>
				<center>
					<div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
						Category Updated Successfully
					</div>
				</center>
			<?php
			}else{
				$sql = "INSERT INTO `coupon_category` (`name`, `points`, `amount`) VALUES ('$name', '$points', '$amount');";
				$result = $mysql->execute($sql);?>
				<center>
					<div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
						Category Created Successfully
					</div>
				</center>
			<?php
			}
		}
		if(isset($_POST['id'])){
				$sql = "select * from coupon_category as pr where pr.`id`=".$_POST['id']." ;";	
				$rst = $mysql->execute($sql);
				$data = mysqli_fetch_array($rst[0]);
		}
		if(intval($_POST['remove_id'])>0){
				$sql = "update 
							`coupon_category` 
						set
							`is`=0
						where	
							id=".$_POST['remove_id'].";";
				$result = $mysql->execute($sql);?>
				<center>
					<div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
						Category Removed Successfully
					</div>
				</center>
			<?php
			}
		?>
		
        <form action="#" class="form-horizontal" method="post" >
			
			<input type="hidden" name="id" value="<?php echo $data['id'] ?>">
          <div class="control-group">
            <label for="datemask" class="control-label">Category Name</label>
            <div class="controls">
              <input type="text" name="name" id="name" class='text' value="<?php echo $data['name'] ?>">
            </div>
          </div>
          <div class="control-group">
            <label for="points" class="control-label">Points</label>
            <div class="controls">
              <input type="text" name="points" id="points" class='text' value="<?php echo $data['points'] ?>">
            </div>
          </div>
          <div class="control-group">
            <label for="amount" class="control-label">Amount</label>
            <div class="controls">
              <input type="text" name="amount" id="amount" class='required' value="<?php echo $data['amount'] ?>">
            </div>
          </div>
          <div class="form-actions">
            <input type="submit" class='btn btn-blue4' value="SUBMIT">
          </div>
        </form>
      </div>
    </div>
	
	      <?php
			$sql = "select * from coupon_category as pr where pr.`is`=1 ;";
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
													<th>Points</th>
													<th>Amount</th>
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
													<td><?php echo $r['id'] ?></td>
													<td><?php echo $r['name'] ?></td>
													<td><?php echo $r['points'] ?></td>
													<td><?php echo $r['amount'] ?></td>
                                                    <td><form action="#" method="post">
                                                    	<input type="hidden" name="id" value="<?php echo $r['id'] ?>">
                                                        <button type="submit" style="border:#FFF 0px solid">
                                                        <i class=icon-pencil style="cursor:pointer">
                                                        </i>
                                                        </button>
                                                    	</form>
                                                    </td>
													<td><form action="#" method="post">
                                                    	<input type="hidden" name="remove_id" value="<?php echo $r['id'] ?>">
                                                        <button type="submit" style="border:#FFF 0px solid">
                                                        <i class="icon-trash" style="cursor:pointer">
                                                        </i>
                                                        </button>
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
</div>
<?php
include_once"footer.php";
?>