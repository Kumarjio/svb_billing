<?php
include_once"header.php";
?>
<div class="container-fluid">
  <div class="content">
    <center>
    <div class="box" style="width:60%;">
      <div class="box-head">
        <h3>Availabel Points</h3>
      </div>
      <div class="box-content">				
	  
	      <?php
			$sql = "select * from workshop as pr where pr.`is`=1 ;";
			$rst = $mysql->execute($sql);
			?>
			<table class='table table-striped dataTable table-bordered dataTable-tools'>
											<thead>
												<tr>
													<th>Id</th>
													<th>Card No</th>
													<th>Name</th>
													<th>Phone</th>
													<th>Points</th>
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
												</tr> 
                                            <?php
											}
											?>
											</tbody>
										</table>
									
      </div>
    </div>
	
<?php
include_once"footer.php";
?>