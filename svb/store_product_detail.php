<?php
include_once"header.php";
?>

<div class="container-fluid">
	<div class="content">
		<center>                              	
		 
          <?php 
			if(isset($_POST['remove_id']))
			{
			$id=$_POST['remove_id'];
			$sql="update store_products set `is` = 0 where id='".$id."';";
			$mysql->execute($sql);
			}
		 ?>
		 <?php
		   $i=1;
		  $sql="select 
					p.id,
					p.pid,
					p.name, 
					p.`type`,
					p.pur_price,
					p.mrp,
					p.`desc`,
					p.min_status,
					p.min_value
				from 
					store_products as p
				where 
					p.`is`=1
				order by
					p.pid ";
		  $rst=$mysql->execute($sql);
		  
		 ?>
		 <div class="box">
				<div class="box-content box-nomargin">
				   <div class="tab-content">
						<div class="tab-pane active" id="basic">
							<table class='table table-striped dataTable table-bordered dataTable-tools'>
								<thead>
								  <tr>
									<th>ID</th>
                                    <th>PRODUCT ID</th>
									<th>NAME</th>
									<th>TYPE</th>
                                    <th>MRP</th>
                                    <th>PUR PRICE</th>
									<th>EDIT/REMOVE</th>
								  </tr>
								 </thead>
							     <tbody>
								 <?php
								 
								 while($r=mysqli_fetch_array($rst[0]))
								 {
								 
								 ?>
								  <tr>
								     <td> <div align="center"> <?php echo ++$cnt; ?> </div> </td>
									 <td><a href="stock_product_view.php?id=<?php echo $r['id'] ?>">
                                     <div align="center"><?php echo $r['pid'] ?></div></a></td>
                                     <td style="white-space:nowrap"> <div align="left">
									 <a href="stock_product_view.php?id=<?php echo $r['id'] ?>">
									 <?php echo $r['name'] ?></a> </div> </td>
									 <td> <div align="center"><?php echo $r['type'] ?> </div> </td>
									 <td> <div align="center"> <?php echo $mysql->currency($r['mrp']) ?> </div> </td>
                                     <td> <div align="center"> <?php echo $mysql->currency($r['par_price']) ?> </div> </td>
                                     
									 <td>
									  </br><form action="store_product_edit.php" method="post">
									 <input type="hidden" name="id" value="<?php echo $r['id'] ?>">
									 <div align="center">
									 <button type="submit" style="border:#FFF 0px solid"><i class=icon-pencil style="cursor:pointer">
                                                        </i></button>
									 </div>
									 </form>
									  <form action="#" method="post" onSubmit="confirmDel(event)">
									 <input type="hidden" name="remove_id" value="<?php echo $r['id'] ?>">
									 <div align="center">
									 <button type="submit" style="border:#FFF 0px solid"><i class=icon-trash style="cursor:pointer">
                                                        </i></button>
									 </div>
									 </form>
									 </td>
								  </tr>
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
        </center>
      </div>
    </div>
    <?php 	
   include_once"footer.php";
   ?>