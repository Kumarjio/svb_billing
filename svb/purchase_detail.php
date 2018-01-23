<?php
include"header.php";
?>
    <form action="" method="post">
      <div class="box">
        <div class="box-head"><h3>Stock Details</h3></div>
        <br>
        <table align="center" border="0">
          <tr>
            <th>Stock Entry No </th>
            <td><input type="text" name="billno" class="text"></td>
          </tr>
          <tr>
            <th></th>
            <th><input type="submit" name="open" value="Open" class="btn btn-blue4"></th>
          </tr>
        </table>
        <?php
if(isset($_REQUEST['billno']))
{
	$billno = $_REQUEST['billno'];
	$sql = "select 
				biller_id,
				pid,
				pur_bill,
				date_format(bill_date,'%d-%m-%Y')'bill_date',
				date_format(date,'%d-%m-%Y')'date',
				actual_amount,
				recieved_amount,
				returned_amount,
				round_off,
				discount,
				discount_reason,
				`lock`,
				trans_close,
				customer_id,
				bill_amount,
				`status`,
				product_name,
				qty,
				`type`,
				unit_price,
				oactual,
				orecieved,
				odiscount,
				product_id
			from 
				purchase_orders as bo 
			where 
				bo.bill_no='".$billno."'";
	$rst = $mysql->execute($sql);
	if(mysqli_num_rows($rst[0])>0)
	{
		$orders = array();
		while($r = mysqli_fetch_array($rst[0]))
		{
			$biller = $r['biller_id'];
			$date = $r['date'];
			$bill_date = $r['bill_date'];
			$pur_bill = $r['pur_bill'];
			$actual = $r['actual_amount'];
			$recieved = $r['recieved_amount'];
			$returned = $r['returned_amount'];
			$round = $r['round_off'];
			$dis = $r['discount'];
			$dis_reason = $r['discount_reason'];
			$lock = $r['lock'];
			$trns = $r['trans_close'];
			$customer = $r['customer_id'];
			$bill_amount = $r['bill_amount'];
			$status = $r['status'];
			$orders[$r['product_id']]['name']= $r['product_name'];
			$orders[$r['product_id']]['code']= $r['pid'];
			$orders[$r['product_id']]['qty']= $r['qty'];
			$orders[$r['product_id']]['type']= $r['type'];
			$orders[$r['product_id']]['unit_price']= $r['unit_price'];
			$orders[$r['product_id']]['actual']= $r['oactual'];
			$orders[$r['product_id']]['recieved']= $r['orecieved'];
			$orders[$r['product_id']]['staus']= $r['completed'];
			$orders[$r['product_id']]['discount']= $r['odiscount'];
		}
		$sql = "select * from customers as c where c.id=".$customer." limit 1;
				select date_format(py.date,'%d-%m-%Y')'date',py.recieved,py.returned,py.paid from purchase_payment as py where py.bill_no='".$billno."' and py.`recieved`-py.`returned`!=0 and `is`=1;
				select * from profile where id='".$biller."' limit 1;  ";
		$rst = $mysql->execute($sql);
		$cust = mysqli_fetch_array($rst[0]);
		$biller = mysqli_fetch_array($rst[2]);
		?>
        <div class="cl">
          <div class="pull-left">
            <h3>Stock Entry Detail</h3>
            <img src="<?php echo $cust['photo']; ?>" width="150" > </div>
          <div class="details pull-left userprofile">
            <table class="table table-striped table-detail">
              <tr>
                <th>Stock Entry No: </th>
                <td><span class="label label-important" style="font-size:20px;"><?php echo strtoupper($billno) ?></span></td>
              </tr>
              <tr>
                <th>Biller Name: </th>
                <td><a href="profile.php?id=<?php echo $biller['id'] ?>"><?php echo $biller['name'] ?></a></td>
              </tr>
              <tr>
                <th>Bill Current Status:</th>
                <td><?php 
				if($lock)
					echo "Locked";
				else
					echo "Not Locked";
				?></td>
              </tr>
              <tr>
                <th>Stock Status:</th>
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
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php
		  $sno = 0;
		  $order_actual = 0;
		  $order_discount = 0;
		  $order_recieved = 0;
		  foreach($orders as $id=>$data)
		  {
		  ?>
            <tr>
              <td style="text-align:center"><?php echo ++$sno; ?></td>
              <td><a href="product_view.php?id=<?php echo $id ?>"><?php echo $data['code'] ?></a></td>
              <td><a href="product_view.php?id=<?php echo $id ?>"><?php echo $data['name'] ?></a></td>
              <td style="text-align:center"><?php echo $data['qty']." ".$data['type'] ?></td>
              <td style="text-align:center"><?php if($data['status']) echo'Updated'; else echo 'Pending'; ?></td>
            </tr>
            <?php
			$order_actual += $data['actual'];
			$order_discount += $data['discount'];
			$order_recieved += $data['recieved'];
		  }
		  ?>
          </tbody>
        </table>
        <?php
	}
	else
	{
		?>
        No Purchase With This No
        <?php
	}
}
?>
      </div>
    </form>
    <?php
include"footer.php";
?>