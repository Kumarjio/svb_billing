<?php
include_once"header.php";
?>
<div class="container-fluid">
  <div class="content">
    <center>
    <div class="box" style="width:60%;">
      <div class="box-head">
        <h3>Workshop Master</h3>
      </div>
      <div class="box-content">				
		<?php
		if(isset($_POST['name']))
		{
			if(isset($_POST['name']))
				$name = $_POST['name'];
			if(isset($_POST['phone']))
				$phone = $_POST['phone'];
			if(isset($_POST['points']))
				$points = $_POST['points'];
			if(isset($_POST['wno']))
				$wno = $_POST['wno'];
			if(isset($_POST['address']))
				$address = $_POST['address'];	
				
			if(intval($_POST['id'])>0){
				$sql = "update 
							`workshop` 
						set
							`wno`='$wno',`name`='$name', `mobile`='$phone',`address`='$address', 
							`available_point`=`available_point`-`opening`+".$points.",`opening`='$points'
						where	
							id=".$_POST['id'].";";
				$result = $mysql->execute($sql);?>
				<center>
					<div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
						Workshop Updated Successfully
					</div>
				</center>
			<?php
			}else{
				$sql = "INSERT INTO `workshop` (`wno`,`name`, `mobile`,`address`, `available_point`,`opening`) VALUES ('$wno','$name', '$phone','$address', '$points', '$points');";
				$result = $mysql->execute($sql);?>
				<center>
					<div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
						Workshop Created Successfully
					</div>
				</center>
			<?php
			}
		}
		if(isset($_POST['id'])){
				$sql = "select * from workshop as pr where pr.`id`=".$_POST['id']." ;";	
				$rst = $mysql->execute($sql);
				$data = mysqli_fetch_array($rst[0]);
		}
		if(intval($_POST['remove_id'])>0){
				$sql = "update 
							`workshop` 
						set
							`is`=0
						where	
							id=".$_POST['remove_id'].";";
				$result = $mysql->execute($sql);?>
				<center>
					<div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
						Workshop Removed Successfully
					</div>
				</center>
			<?php
			}
		?>
		
        <form action="#" class="form-horizontal" method="post" >
			
		  <input type="hidden" name="id" value="<?php echo $data['id'] ?>">
		  <div class="control-group">
            <label for="wno" class="control-label">Card No</label>
            <div class="controls">
              <input type="text" name="wno" id="wno" class='text' value="<?php echo $data['wno'] ?>">
            </div>
          </div>
          <div class="control-group">
            <label for="datemask" class="control-label">Workshop Name</label>
            <div class="controls">
              <input type="text" name="name" id="name" class='text' value="<?php echo $data['name'] ?>">
            </div>
          </div>
          <div class="control-group">
            <label for="phonemask" class="control-label">Phone</label>
            <div class="controls">
              <input type="text" name="phone" id="phonemask" class='text' value="<?php echo $data['mobile'] ?>">
            </div>
          </div>
		  <div class="control-group">
            <label for="address" class="control-label">Address</label>
            <div class="controls">
              <textarea name="address" id="address" class='required'><?php echo $data['address'] ?></textarea>
            </div>
          </div>
          <div class="control-group">
            <label for="points" class="control-label">Opening Points</label>
            <div class="controls">
              <input type="text" name="points" id="points" class='required' value="<?php echo $data['opening'] ?>">
            </div>
          </div>		  
          <div class="control-group">
            <label for="points" class="control-label">Available Points Points</label>
            <div class="controls">
              <input type="text" name="available_points" id="points" readonly class='required' value="<?php echo $data['available_point'] ?>">
            </div>
          </div>
          <div class="form-actions">
            <input type="submit" class='btn btn-blue4' value="SUBMIT">
          </div>
        </form>
      </div>
    </div>
	
	      <?php
			$sql = "select * from workshop as pr where pr.`is`=1 ;";
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
													<th>Card No</th>
													<th>Name</th>
													<th>Phone</th>
													<th>Points</th>
													<th>Address</th>
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
													<td><?php echo ++$sno ?></td> 	
													<td><?php echo $r['wno'] ?></td>
													<td><?php echo $r['name'] ?></td>
													<td><?php echo $r['mobile'] ?></td>
													<td><?php echo $r['available_point'] ?></td>
													<td><?php echo $r['address'] ?></td>
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