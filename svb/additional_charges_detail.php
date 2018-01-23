<?php
include"header.php";
?>
    <div class="box">
      <div class="box-head"><h3>Additional Charges</h3></div>
      <table class="table table-striped table-bordered">
      <thead>
      <tr>
      <th>Sno</th>
      <th>Name</th>
      <th>Type</th>
      <th>Value Source</th>
      <th>Price</th>
      <th>Price Type</th>
      <th>Price Status</th>
      <th>Comments</th>
      </tr>
      </thead>
      <?php
	  $sql = "select 
					* 
				from 
					shop_charges as s 
				where 
					s.`is`=1";
	 $rst = $mysql->execute($sql);
	 while($r = mysqli_fetch_array($rst[0]))
	 {
		 ?>
         <tr>
         <td style="text-align:center"><?php echo ++$sno; ?></td>
         <td><?php echo $r['name'] ?></td>
         <td><?php echo $r['type'] ?></td>
         <td><?php echo $r['type_source'] ?></td>
         <td style="text-align:right"><?php echo $mysql->currency($r['rate']) ?></td>
         <td><?php
		 if($r['rate_type']==0)
		 	echo 'Price';
		else
			echo '% of Net';
		 ?></td>
         <td><?php
		 if($r['rate_status']==0)
		 	echo 'Not in Use';
		else
			echo 'In Use';
		 ?></td>
         <td><?php echo $r['comments'] ?></td>
         </tr>
         <?php
	 }
	 ?>
     </table>
    </div>
    <?php
include"footer.php";
?>