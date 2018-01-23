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
		$sql = "select * from product where `is`=1";
		$rst = $mysql->execute($sql);
		$products = array();
		while($r = mysqli_fetch_array($rst[0]))
		{
			$products[$r['id']]['code'] = $r['pid'];
			$products[$r['id']]['name'] = $r['name'];
			?>
            <option <?php if(in_array($r['id'],$_POST['products'])) echo 'selected' ?> value="<?php echo $r['id']?>"><?php echo $r['pid']." ".$r['name'] ?></option>
            <?php
		}
		?>
        </select></th>
        <th><input type="submit" name="submit" value="Open" class="btn btn-blue4" >
        </thead>
        </table>
        </form>
        <?php
		if(isset($_POST['submit']))
		{
			$sql = "select * from `customer_product_discount` where `cid`='".$_POST['customer']."'";
			if(count($_POST['products'])>0)
			{
				$sql .=" and `pid` IN (".implode(",",$_POST['products']).") ";
			}
			$rst = $mysql->execute($sql);
			$discount = array();
			while($r = mysqli_fetch_array($rst[0]))
			{
				$discount[$r['id']]['pid'] = $r['pid'];
				$discount[$r['id']]['value'] = $r['discount_value'];
			}
			?>
            <table class="table table-bordered table-striped" style="width:50px; overflow:visible">
            <thead><th>Sno</th><th style="white-space:nowrap">Product Code</th><th>Product Name</th><th>Discount%</th></thead>
            <tbody>
            <?php
			foreach($discount as $id=>$dis)
			{
				?>
                <tr>
                <td style="text-align:center"><?php echo ++$sno ?></td>
                <td style="text-align:center"><?php echo $products[$dis['pid']]['code'] ?></td>
                <td style="white-space:nowrap"><?php echo $products[$dis['pid']]['name'] ?></td>
                <td style="text-align:center"><?php echo $dis['value'] ?></td>
                </tr>
                <?php
			}
			?>
            </tbody>
            </table>
            <?php
		}
		?>
      </center>
    </div>
<?php include"footer.php"; ?>