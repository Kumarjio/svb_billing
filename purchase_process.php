<?php
include"header.php";
?>
    
      <div class="box">
        <div class="box-head"><h3>Stock Details</h3></div>
        <br>
        <form action="" method="post">
        <table align="center" border="0">
          <tr>
            <th>Stock Entry No </th>
            <td><input type="text" name="billno" value="<?php echo $_POST['billno'] ?>" class="text"></td>
          </tr>
          <tr>
            <th></th>
            <th><input type="submit" name="open" value="Open" class="btn btn-blue4"></th>
          </tr>
        </table>
   </form>
        <?php
if(isset($_POST['process_single']))
{
	$bill_no = $_POST['bill_no'];
	$qty = $_POST['qty'];
	$tsql = "select id from stock as s where s.p_order_id='".$bill_no."'";
	$rst = $mysql->execute($tsql);
	$sql = "update pur_order set recieved_qty=recieved_qty+'".$qty."' where id='".$bill_no."' and `recieved_qty`<`qty`;";
	
	if(mysqli_num_rows($rst[0])>0)
	{
		$row = mysqli_fetch_array($rst[0]);
		$sql .="UPDATE `stock` SET `qty`=`qty`+'".$qty."', `available`=`available`+'".$qty."' WHERE  `id`='".$row[0]."' LIMIT 1;";
	}else
	{
	$sql.= "INSERT INTO `stock` (`par_id`,`p_order_id`, `p_date`, `pid`, `par_price`, `type`, `qty`, `available`, `unit_price`, `vat`) 
			(select bid,'".$bill_no."',date,product_id,orecieved,`type`,'".$qty."','".$qty."',unit_price,vat from purchase_orders where oid ='".$bill_no."');";
	}
	$sql .="INSERT INTO `purchase_order_recieving` 
				(`pid`, `oid`, `recieved`, `date`) 
			VALUES 
				((select bid from purchase_orders where oid ='".$bill_no."'), '".$bill_no."', '".$qty."', NOW());";
	$sql .= "update pur_order set `received`=1 where id='".$bill_no."' and recieved_qty=qty;";
	$rst = $mysql->execute($sql);
	?>
	<div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
	<h4 class="alert-heading">Stock Successfully Updated!</h4>
	</div>
	<?php
}
if(isset($_POST['process']))
{
	$bill_no = $_POST['bill_no'];
	$sql = "select p.id,p.qty-p.recieved_qty'qty' from pur_order as p where p.bill_no='".$bill_no."' and `recieved_qty`<`qty` and p.`is`=1 and p.`is`=1";
	$rst = $mysql->execute($sql);
	$sql = '';
	while($r = mysqli_fetch_array($rst[0]))
	{
		$qty = $r['qty'];
		$tsql = "select id from stock as s where s.p_order_id='".$r['id']."';";
		$rst1 = $mysql->execute($tsql);
		$sql .= "update pur_order set recieved_qty=recieved_qty+'".$qty."' where id='".$r['id']."';";
		if(mysqli_num_rows($rst1[0])>0)
		{
			$row = mysqli_fetch_array($rst1[0]);
			$sql .="UPDATE `stock` SET `qty`=`qty`+'".$qty."', `available`=`available`+'".$qty."' WHERE  `id`='".$row[0]."' LIMIT 1;";
		}else
		{
			$sql.= "INSERT INTO `stock` (`par_id`,`p_order_id`, `p_date`, `pid`, `par_price`, `type`, `qty`, `available`, `unit_price`, `vat`) 
				(select bid,oid,date,product_id,orecieved,`type`,'".$qty."','".$qty."',
				unit_price,vat from purchase_orders where oid ='".$r['id']."' and `recieved_qty`>`completed`);";
		}
		$sql .="INSERT INTO `purchase_order_recieving` 
				(`pid`, `oid`, `recieved`, `date`) 
			VALUES 
				((select bid from purchase_orders where bill_no ='".$bill_no."' limit 1), '".$r['id']."', '".$qty."', NOW());";	
	}
	$sql .= "update pur_order set `received`=1 and `recieved_qty`=`qty` where bill_no='".$bill_no."';";
	$sql .= "update purchase set `lock`=1 where bill_no='".$bill_no."';";
	$rst = $mysql->execute($sql);
	?>
	<div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
	<h4 class="alert-heading">Stock Successfully Updated!</h4>
	</div>
	<?php
}
if(isset($_POST['billno']))
{
	$billno = $_POST['billno'];
	$sql = "select 
				date_format(date,'%d-%m-%Y')'date',
				actual_amount,
				recieved_amount,
				returned_amount,
				round_off,
				discount,
				discount_reason,
				product_id,
				`lock`,
				trans_close,
				customer_id,
				bill_amount,
				`status`,
				pid,
				product_name,
				qty,
				`type`,
				unit_price,
				oactual,
				orecieved,
				odiscount,
				product_id,
				completed,
				recieved_qty,
				oid
			from 
				purchase_orders as bo 
			where 
				bo.bill_no='".$billno."'
				";
	$rst = $mysql->execute($sql);
	if(mysqli_num_rows($rst[0])>0)
	{
		$orders = array();
		while($r = mysqli_fetch_array($rst[0]))
		{
			$date = $r['date'];
			$actual = $r['actual_amount'];
			$round = $r['round_off'];
			$recieved = $r['recieved_amount'];
			$returned = $r['returned_amount'];
			$dis = $r['discount'];
			$dis_reason = $r['discount_reason'];
			$lock = $r['lock'];
			$trns = $r['trans_close'];
			$customer = $r['customer_id'];
			$bill_amount = $r['bill_amount'];
			$status = $r['status'];
			$orders[$r['product_id']]['oid']= $r['oid'];
			$orders[$r['product_id']]['pid']= $r['pid'];
			$orders[$r['product_id']]['product_id']= $r['product_id'];
			$orders[$r['product_id']]['name']= $r['product_name'];
			$orders[$r['product_id']]['qty']= $r['qty'];
			$orders[$r['product_id']]['type']= $r['type'];
			$orders[$r['product_id']]['unit_price']= $r['unit_price'];
			$orders[$r['product_id']]['actual']= $r['oactual'];
			$orders[$r['product_id']]['recieved']= $r['orecieved'];
			$orders[$r['product_id']]['recieved_qty']= $r['recieved_qty'];
			$orders[$r['product_id']]['discount']= $r['odiscount'];
			$orders[$r['product_id']]['completed']= $r['completed'];
		}
		$sql = "select * from customers as c where c.id=".$customer." limit 1;
				select date_format(py.date,'%d-%m-%Y')'date',py.recieved,py.returned,py.paid from purchase_payment as py where py.bill_no='".$billno."' and py.`recieved`-py.`returned`!=0 ";
		$rst = $mysql->execute($sql);
		$cust = mysqli_fetch_array($rst[0]);
		?>
        <div class="cl">
          <div class="pull-left">
            <h3>Stock Entry Detail</h3>
            <img src="<?php echo $cust['photo']; ?>" width="150" > </div>
          <div class="details pull-left userprofile">
            <table class="table table-striped table-detail">
              <tr>
                <th>Stock Entry No: </th>
                <td><span class="label label-important" style="font-size:20px;"><?php echo $billno ?></span></td>
              </tr>
              <tr>
                <th>Stock Order Current Status:</th>
                <td><?php 
				if($lock)
					echo "Locked";
				else
					echo "Not Locked";
				?></td>
              </tr>
              <tr>
                <th>Stock Entry Status:</th>
                <td><?php 
				if($status=='s')
					echo "Pending";
				else if($status=='p')
					echo "Printed";
				else if($status=='c')
					echo "Cancel";
				?></td>
              </tr>
            </table>
          </div>
        </div>
        <center><h3>Orders</h3></center>
        <table class='table table-striped dataTable table-bordered dataTable-tools'>
          <thead>
            <tr>
              <th>Sno</th>
              <th>Code</th>
              <th>Name</th>
              <th>Qty(s)</th>
              <th>STATUS</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
		  $sno = 0;
		  foreach($orders as $data)
		  {
		  ?>
            <tr>
              <td style="text-align:center"><?php echo ++$sno; ?></td>
              <td style="text-align:center"><a href="product_view.php?id=<?php echo $data['product_id'] ?>"><?php echo $data['pid'] ?></a></td>
              <td><a href="product_view.php?id=<?php echo $data['product_id'] ?>"><?php echo $data['name'] ?></a></td>
              <td><?php echo $data['qty']." ".$data['type'] ?></td>
              <td style="text-align:right"><?php if($data['completed']) echo 'UPDATED'; else echo 'PENDING'; ?></td>
              <td>
              <?php if($data['completed']==0 && $lock==1) { $process = 1;?>
              <form method="post">
                <input type="hidden" name="bill_no" value="<?php echo $data['oid'] ?>">
                <input type="hidden" name="billno" value="<?php echo $_POST['billno'] ?>" class="text">
                <input type="text" name="qty" value="<?php echo $data['qty']- $data['recieved_qty']?>" max="10" class="input-small" style="text-align:right">&nbsp;&nbsp;
                <input type="submit" name="process_single" value="Recieved" class="btn btn-green3">
                </form>
                <?php
		  		}
				else if($lock==0)
				{
				?>
                   Please Lock Purchase and Continue
                <?php
				}
		  		?>
              </td>
            </tr>
            <?php
		  }
		  ?>
          </tbody>
		</table>
        <center><?php
          if($process==1 && $lock==1)
          {
          ?>
          <form method="post">
          <input type="hidden" name="bill_no" value="<?php echo $billno ?>">
          <input type="hidden" name="billno" value="<?php echo $_POST['billno'] ?>" class="text">
          <input type="submit" name="process" value="Complete All Orders" class="btn btn-green3 btn-large">
          </form>
          <?php
          }
          else if($lock==0)
          {
              ?>
              Please Lock Stock Entry and Continue
              <?php
          }
          else
          {
              ?>
             <strong> Update Complete Stock</strong>
              <?php
          }
          ?>
        </center>
    <br>
<br>
    </center>
        <?php
	}
	else
	{
		?>
        No Bills With Tthis No
        <?php
	}
}
?>
      </div>
    </form>
    <?php
include"footer.php";
?>