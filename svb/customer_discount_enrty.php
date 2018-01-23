<?php include"header.php"; ?>
    <div class="box">
      <div class="box-head">
        <h3>Customer Discount Entry</h3>
      </div>
      <center>
        <form action="#" method="post">
        <table class="table table-bordered" style="width:500px; overflow:visible">
        <thead><th>Customer</th>
        <th><select name="customer" class="cho">
        <?php
		$sql = "select * from customers where `is`=1";
		$rst = $mysql->execute($sql);
		$customer = array();
		while($r = mysqli_fetch_array($rst[0]))
		{
			$customer[$r['id']] = $r['name'];
			?>
            <option value="<?php echo $r['id']?>" <?php if($_POST['customer']==$r['id']) echo'selected'; ?> ><?php echo $r['name'] ?></option>
            <?php
		}
		?>
        </select></th>
        <th>Products</th>
        <th><select name="products[]" multiple class="cho">
        <option value="all">All</option>
        <?php
		$sql = "select * from product where `is`=1 order by `pid`";
		$rst = $mysql->execute($sql);
		$products = array();
		while($r = mysqli_fetch_array($rst[0]))
		{
			$products[$r['id']]['code'] = $r['pid'];
			$products[$r['id']]['name'] = $r['name'];
			?>
            <option <?php if(in_array($r['id'],$_POST['products'])) echo 'selected' ?>  value="<?php echo $r['id']?>"><?php echo $r['pid']." ".$r['name'] ?></option>
            <?php
		}
		?>
        </select></th>
        <th><input type="submit" name="submit" value="Open" class="btn btn-blue4" >
        </thead>
        </table>
        </form>
        <?php
		if(isset($_POST['update']))
		{
			$sql = '';
			foreach($_POST['id'] as $id=>$row)
			{
				$value = $_POST['value'][$id];
				$sql .= "update `customer_product_discount` 
						set 
							`discount_value`='".floatval($value)."' 
						where
							`id`='".intval($row)."';";
			}
			$mysql->execute($sql);
		}
		if(isset($_POST['submit']))
		{
			$sql = "select * from `customer_product_discount` where `cid`='".$_POST['customer']."'";
			$rst = $mysql->execute($sql);
			$discount = array();
			$sql = '';
			while($r = mysqli_fetch_array($rst[0]))
			{
				$discount[$r['pid']] = $r['discount_value'];
			}
			foreach($products as $id=>$name)
			{
				if(!isset($discount[$id]))
				{
					$sql .= "INSERT INTO `customer_product_discount` 
							(`cid`, `pid`, `discount_value`) 
							VALUES 
							(".$_POST['customer'].", ".intval($id).", 0);";
				}
			}			
			if($sql!='')
			$rst = $mysql->execute($sql);
			$sql = "select d.id,d.pid,d.discount_value from `customer_product_discount` as d,product as p where d.`cid`='".$_POST['customer']."'";
			if(count($_POST['products'])>0)
			{
				$sql .=" and d.`pid` IN (".implode(",",$_POST['products']).") ";
			}
			$sql .=" and p.id=d.pid and p.is=1 order by p.pid";
			$rst = $mysql->execute($sql);
			$discount = array();
			while($r = mysqli_fetch_array($rst[0]))
			{
				$discount[$r['id']]['pid'] = $r['pid'];
				$discount[$r['id']]['value'] = $r['discount_value'];
			}
			?>
            <center><strong>Copy this to all Products:</strong> <input type="text" name="copy_to_all" id="tot_value" class="input-small"   /> 
            <input type="button" name="copy" value="Copy to All" class="btn btn-red4" onclick="copy_to_all()"  />
            <hr />
            <script type="text/javascript">
			function copy_to_all(){
				val = $("#tot_value").val();
				$(".paste_val").each(function(e){
					$(this).val(val);	
				});
			}
			</script>
            </center>
            <form action="#" method="post">
            <table class="table table-bordered table-striped" style="width:50px; overflow:visible">
            <thead><th>Sno</th><th style="white-space:nowrap">Product Code</th><th>Product Name</th><th>Discount%</th></thead>
            <tbody>
            <?php
			foreach($discount as $id=>$dis)
			{
				?>
                <tr>
                <td style="text-align:center"><?php echo ++$sno ?></td>
                <td style="text-align:center"><a href="product_view.php?id=<?php echo $dis['pid'] ?>"><?php echo $products[$dis['pid']]['code'] ?></a></td>
                <td style="white-space:nowrap"><?php echo $products[$dis['pid']]['name'] ?></td>
                <td style="text-align:center">
				<input type="hidden" name="id[]" value="<?php echo $id ?>">
                <input type="text" class="input-small paste_val" style="text-align:right" name="value[]" value="<?php echo $dis['value'] ?>">
                </td>
                </tr>
                <?php
			}
			?>
            </tbody>
            <tfoot>
            <th colspan="4" style="text-align:center">
            <input type="submit" name="update" value="Update" class="btn btn-blue4" >
            </th>
            </tfoot>
            </table>
            <?php
		}
		?>
      </center>
    </div>
<?php include"footer.php"; ?>