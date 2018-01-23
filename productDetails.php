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
			$sql="update product set `is` = 0 where id='".$id."';";
			$mysql->execute($sql);
			}
		 ?>
		 <?php
		   $i=1;
		  $sql="select 
					p.id,
					p.pid,
					p.bid,
					p.cid,
					p.gid,
					p.name, 
					p.name1, 
					p.name2,
					p.name_default, 
					p.`type`,
					p.price,
					p.price_src,
					p.par_price,
					p.mrp,
					p.photo,
					p.vat,
					p.`desc`,
					p.min_status,
					p.min_value,
					p.gst
				from 
					product as p
				where 
					p.`is`=1
				order by
					cast(p.pid as UNSIGNED),p.pid;
				select 
					id,name 
				from 
					product_brand
				where 
					`is`=1;
				select 
					id,name 
				from 
					product_category 
				where 
					`is`=1;
				select 
					id,name 
				from 
					product_group
				where 
					`is`=1";
				$rst = $mysql->execute($sql);
				$brand = array();
				$category = array();
				$group = array();
				while($r = mysqli_fetch_array($rst[1]))
					$brand[$r['id']] = trim($r['name']);
				while($r = mysqli_fetch_array($rst[2]))
					$category[$r['id']] = trim($r['name']);
				while($r = mysqli_fetch_array($rst[3]))
					$group[$r['id']] = trim($r['name']);
		  $rst=$mysql->execute($sql);
		  
		 ?>
		 <div class="box">
				<div class="box-content box-nomargin">
				   <div class="tab-content">
						<div class="tab-pane active" id="basic">
							<table class='table table-striped  table-bordered'>
								<thead>
								  <tr>
									<th>ID</th>
                                    <th>PRODUCT ID</th>
									<th>HSM CODE</th>
									<th>NAME</th>
                                    <th>TYPE</th>
                                    <th>MRP</th>
                                    <th>PUR PRICE</th>
									<th>SEL PRICE</th>
                                    <th>PRICE SRC</th>
									<th>GST</th>
                                    <th>SEL PRICE(VAT)</th>
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
									 <td> <div align="center"><?php echo $r['pid'] ?></div> </td>
									 <td> <div align="center"><?php echo $r['hsm_code'] ?></div> </td>
                                     <td style="white-space:nowrap"> <div align="left">
									 <a href="product_view.php?id=<?php echo $r['id'] ?>">
									 <?php echo $r['name'] ?><br><?php echo $r['name1'] ?><br><?php echo $r['name2'] ?></a> </div> </td>
									 <td> <div align="center"><?php echo $r['type'] ?> </div> </td>
									 <td> <div align="center"> <?php echo $mysql->currency($r['mrp']) ?> </div> </td>
                                     <td> <div align="center"> <?php echo $mysql->currency($r['par_price']) ?> </div> </td>
                                     <td> <div align="center"> <?php echo $mysql->currency($r['price']) ?> </div> </td>
                                     <td><div align="center"> <?php 
									 							if($r['price_src']==0) 
																	echo 'Purchased Rate';
									 							else
																	echo 'Current Rate'; 
															?> 
                                         </div>
                                     </td>
                                     <td> <div align="center"><?php echo $r['gst'] ?> </div> </td>
                                     <td> <div align="center"> <?php echo $mysql->currency($r['price']+($r['price']*$r['vat']/100)) ?> </div> </td>
									 <td>
									  </br><form action="productEdit.php" method="post">
									 <input type="hidden" name="id" value="<?php echo $r['id'] ?>">
									 <div align="center">
									 <button type="submit" style="border:#FFF 0px solid"><i class=icon-pencil style="cursor:pointer">
                                                        </i></button>
									 </div>
									 </form>
									  <form action="productDetails.php" method="post" onSubmit="confirmDel(event)">
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