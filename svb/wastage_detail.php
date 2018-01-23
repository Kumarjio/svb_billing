<?php
include"header.php";
?>
    <form action="" method="post">
      <div class="box">
        <div class="box-head"><h3>Wastage Details</h3></div>
        <br>
        <table align="center" border="0">
          <tr>
            <th>Wastage No </th>
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
				wastage_orders as bo 
			where 
				bo.bill_no='".$billno."'";
	$rst = $mysql->execute($sql);
	if(mysqli_num_rows($rst[0])>0)
	{
		$orders = array();
		while($r = mysqli_fetch_array($rst[0]))
		{
			$date = $r['date'];
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
			$orders[$r['product_id']]['qty']= $r['qty'];
			$orders[$r['product_id']]['type']= $r['type'];
			$orders[$r['product_id']]['unit_price']= $r['unit_price'];
			$orders[$r['product_id']]['actual']= $r['oactual'];
			$orders[$r['product_id']]['recieved']= $r['orecieved'];
			$orders[$r['product_id']]['discount']= $r['odiscount'];
		}
		$sql = "select * from customers as c where c.id=".$customer." limit 1;
				select date_format(py.date,'%d-%m-%Y')'date',py.recieved,py.returned,py.paid from payment as py where py.bill_no='".$billno."' and py.`recieved`-py.`returned`!=0 and `is`=1  ";
		$rst = $mysql->execute($sql);
		$cust = mysqli_fetch_array($rst[0]);
		?>
        <div class="cl">
          <div class="pull-left">
            <h3>Wastage Detail</h3>
            <img src="<?php echo $cust['photo']; ?>" width="150" > </div>
          <div class="details pull-left userprofile">
            <table class="table table-striped table-detail">
              <tr>
                <th>Wastage No: </th>
                <td><span class="label label-important" style="font-size:20px;"><?php echo $billno ?></span></td>
              </tr>
              <tr>
                <th>Current Status:</th>
                <td><?php 
				if($lock)
					echo "Locked";
				else
					echo "Not Locked";
				?></td>
              </tr>
              <tr>
                <th>Status:</th>
                <td><?php 
				if($status=='s')
					echo "Pending";
				else if($status=='c')
					echo "Cancel";
				?></td>
              </tr>
            </table>
          </div>
        </div>
        <center><h3>Products</h3></center>
        <table class='table table-striped dataTable table-bordered dataTable-tools'>
          <thead>
            <tr>
              <th>Sno</th>
              <th>Name</th>
              <th>Qty(s)</th>
              <th>Actual Rate</th>
              <th>Discount</th>
              <th>Recieved</th>
            </tr>
          </thead>
          <tbody>
            <?php
		  $sno = 0;
		  foreach($orders as $id=>$data)
		  {
		  ?>
            <tr>
              <td><?php echo ++$sno; ?></td>
              <td><a href="product_view.php?id=<?php echo $id ?>"><?php echo $data['name'] ?></a></td>
              <td><?php echo $data['qty']." ".$data['type'] ?></td>
              <td style="text-align:right"><?php echo $mysql->currency($data['actual']) ?></td>
              <td style="text-align:right"><?php echo $mysql->currency($data['discount']) ?></td>
              <td style="text-align:right"><?php echo $mysql->currency($data['recieved']) ?></td>
            </tr>
            <?php
		  }
		  ?>
          </tbody>
        </table>
        <?php
	}
	else
	{
		?>
        No Wastage With This No
        <?php
	}
}
?>
      </div>
    </form>
    <?php
include"footer.php";
?>