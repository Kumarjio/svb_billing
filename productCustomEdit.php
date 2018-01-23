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
				  if($key == 'gst'){
					 $update[]= " `".$key."`='".addslashes(json_encode($value[$id]))."'"; 
					 continue;
				  }
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
      <form action="#" method="post">
      <table class="table table-striped table-bordered" style="width:250px; overflow:visible" align="center">
        <thead>
          <tr>
            <td>Columns</td>
            <td>
            <select name="column[]" class="cho" multiple>
            <option value="gid">Group</option>
            <option value="cid">Category</option>
            <option value="bid">Brand</option>
            <option value="hsm_code">Hsm Code</option>
			<option value="pid">Code</option>
            <option value="name">Name</option>
            <option value="name1">Name1</option>
            <option value="name2">Name2</option>
            <option value="type">Type</option>
            <option value="gst">GST</option>
            <option value="mrp">MRP</option>
            <option value="price">Purchase Price</option>
            <option value="par_price">Selling Price</option>
            <option value="price_src">Price Source</option>
            <option value="desc">Description</option>
            <option value="photo">Photo</option>
            <option value="min_status">Min On / Off</option>
            <option value="min_value">Min Value</option>
            <option value="available">Stock</option>
            </select></td>
            <td><input type="submit" name="submit" value="Load Products" class="btn btn-blue4"></td>
          </tr>
        </thead>
      </table>
      </form>
    <?php
		if(isset($_POST['submit']))
		{
			?>
            <form action="#" method="post">
            <table class="table table-striped table-bordered" align="center" style="width:1px; overflow:visible">
            <thead><tr><td>Sno</td><td>Prdct ID</td><td>Name</td>
            <?php
			foreach($_POST['column'] as $c)
			{
				?>
                <th><?php echo $c; ?></th>
                <?php
			}
			?>
            </tr></thead>
            <?php
			$sql = "SELECT `id`,`pid`,`name`,`name1`,`name2`,`name_default`,`".implode('`,`',$_POST['column'])."` from product where `is`=1 order by `pid`;";
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
			$id = 0;
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
                <?php
				foreach($_POST['column'] as $c)
				{
					?>
					<th style="text-align:center; white-space:nowrap"><?php 

					if($c == 'gid')
					{
						?>
                         <select name="<?php echo $c ?>[]" id="<?php echo $c ?>">
						 <?php
                         
                         foreach($group as $id=>$name)
                          {
                          ?>
                              <option value="<?php echo $id ?>" <?php if($p[$c]==$id) echo 'selected'; ?>><?php echo  $name ?></option>
                              <?php
                          }
                          ?>
                         </select>
                        <?php
					}
					else if($c == 'cid')
					{
						?>
                         <select name="<?php echo $c ?>[]" id="<?php echo $c ?>">
						 <?php
                         
                         foreach($cat as $id=>$name)
                          {
                          ?>
                              <option value="<?php echo $id ?>" <?php if($p[$c]==$id) echo 'selected'; ?>><?php echo  $name ?></option>
                              <?php
                          }
                          ?>
                         </select>
                        <?php
					}
					else if($c == 'bid')
					{
						?>
                         <select name="<?php echo $c ?>[]" id="<?php echo $c ?>">
						 <?php
                         
                         foreach($brand as $id=>$name)
                          {
                          ?>
                              <option value="<?php echo $id ?>" <?php if($p[$c]==$id) echo 'selected'; ?>><?php echo  $name ?></option>
                              <?php
                          }
                          ?>
                         </select>
                        <?php
					}
					else if($c == 'type')
					{
						?>
                         <select name="<?php echo $c ?>[]" id="<?php echo $c ?>">
						 <?php
                         
                         foreach($conv as $id=>$name)
                          {
                          ?>
                              <option <?php if($p[$c]==$name) echo 'selected'; ?> ><?php echo  $name ?></option>
                              <?php
                          }
                          ?>
                         </select>
                        <?php
					}
					elseif($c == 'desc') 
					{
						?><textarea name="<?php echo $c ?>[]"><?php echo $p[$c]; ?></textarea><?php
						
					}
					elseif($c == 'min_status') 
					{
						?>
                        <input type="checkbox" name="<?php echo $c ?>[]" <?php if($p[$c]) echo 'checked' ?> />
						<?php
						if($p[$c]) echo 'Yes';
						else echo 'No';
					}
					elseif($c == 'price_src') 
					{
						?>
                        <input type="radio" name="<?php echo $c ?>[<?php echo intval($rdn) ?>]" id="p_sel_src" value="0" <?php if(!$p[$c]) echo 'checked' ?>>
                        &nbsp;Purchased Rate
    					<input type="radio" name="<?php echo $c ?>[<?php echo intval($rdn++) ?>]" id="p_sel_src" value="1" <?php if($p[$c]) echo 'checked' ?>>
                        &nbsp;Current Rate
						<?php
					}elseif($c == 'gst'){
						$gst = json_decode($p[$c],true);
						?>
						Cgst: <input type="text" name="gst[<?php echo $id ?>][cgst]" value="<?php echo floatval($gst['cgst']); ?>" >
						Sgst: <input type="text" name="gst[<?php echo $id ?>][sgst]" value="<?php echo floatval($gst['sgst']); ?>" ><?php
					}
					else
					{
						?><input type="text" name="<?php echo $c ?>[]" value="<?php echo $p[$c]; ?>" ><?php
						
					}
					?>
                    </th>
					<?php
				}
				?>
                </tr>
                <?php	
				$id++;
			}
			?>
            <tr><td style="text-align:center" colspan="<?php echo count($_POST['column'])+3 ?>">
            <input type="submit" name="update" value="Update" class="btn btn-blue4"></td></tr>
            </table>
            </form>
            <?php
		}
	?>
    </div>
    <?php
include"footer.php";
?>


