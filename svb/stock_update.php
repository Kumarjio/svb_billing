<?php
include"header.php";
?>
    <div class="box" style="min-height:450px;">
      <div class="box-head">
        <h3>Product Custom Edit</h3>
      </div>
      <?php
	  if(isset($_POST['update']))
	  {
		 
		  foreach($_POST['id'] as $id=>$name)
		  {
			  $update = array();
			  $sql  = "UPDATE product set ";
			  foreach($_POST as $key=>$value)
			  {
				  if($key != 'id' && $key != 'update')
				  {
					  if($key == 'min_status' && isset($value[$id]))
					  $value[$id] = 1;
					  else
					  $value[$id] = 0;
					 $update[]= " `".$key."`='".$_POST[$key][$id]."'";
				  }
			  }
			  $sql .= implode(' , ',$update)." where `id` = '".$name."';";
			  $mysql->execute($sql);			  
		  }
		  ?>
           <center><div class="alert alert-block alert-success" style="width:400px;"> <a class="close" data-dismiss="alert" href="#">×</a>
                  <h4 class="alert-heading">Successfully Updated!</h4>
                </div>
                </center>
                <?php
	  }
	  ?>
      
    <?php
		
			?>
            <form action="#" method="post">
            <table class="table table-striped table-bordered" align="center" style="width:1px; overflow:visible">
            <thead><tr><td>Sno</td><td>Prdct ID</td><td>Name</td><th>Stock</th></tr></thead>
            <?php
			$sql = "SELECT `id`,`pid`,`name`,`name1`,`name2`,`name_default`,`available` from product where `is`=1 order by `pid`;";
			$sql .= "select * from product_brand where `is`=1 order by id;";
			$sql .= "select * from product_category where `is`=1 order by id;";
			$sql .= "select * from product_group where `is`=1 order by id;";
			$sql .= "select c.name from conversion as c where c.`is`=1 and c.`using`=1";
			$products = $mysql->execute($sql);
			while($b = mysqli_fetch_array($products[1]))
			{
				$brand[$b['id']] = $b['name'];
			}
			while($b = mysqli_fetch_array($products[2]))
			{
				$cat[$b['id']] = $b['name'];
			}
			while($b = mysqli_fetch_array($products[3]))
			{
				$group[$b['id']] = $b['name'];
			}
			while($b = mysqli_fetch_array($products[4]))
			{
				$conv[] = $b['name'];
			}
			while($p= mysqli_fetch_array($products[0]))
			{
				?>
                <tr>
               	<td style="text-align:center"><?php echo ++$sno; ?><input type="hidden" name="id[]" value="<?php echo $p['id'] ?>" ></td>
                <td style="text-align:center"><?php echo $p['pid'] ?></td>

                <td style="min-width:200px; white-space:nowrap"><a href="product_view.php?id=<?php echo $p['id'] ?>"><?php 
				if($p['name_default']==0)
				 	echo $p['name'];
				else if($p['name_default']==1)
				 	echo $p['name1'];
				else
				 	echo $p['name2'];   
					?></a></td>
                <td><input type="text" name="available[]" value="<?php echo $p['available']; ?>" ></td>
                </tr>
                <?php	
			}
			?>
            <tr><td style="text-align:center" colspan="4">
            <input type="submit" name="update" value="Update" class="btn btn-blue4"></td></tr>
            </table>
            </form>
            <?php
		
	?>
    </div>
    <?php
include"footer.php";
?>


