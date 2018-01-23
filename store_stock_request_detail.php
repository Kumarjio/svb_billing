<?php
include"header.php";
?>
    <form action="" method="post">
      <div class="box">
        <div class="box-head"><h3>Store Use Request Details</h3></div>
        <br>
        <table align="center" border="0">
          <tr>
            <th>Bill No </th>
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
				s.bill_no,
				s.customer_id,
				s.date,
				s.bill_amount,
				s.actual_amount,
				s.recieved_amount,
				s.returned_amount,
				s.`lock`,
				s.trans_close,
				s.status,
				s.product_id,
				s.product_name,
				s.qty,
				s.`type`,
				s.oactual,
				s.orecieved
			from
				store_request_orders as s
			where
				s.bill_no='".$billno."'";
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
			$lock = $r['lock'];
			$trns = $r['trans_close'];
			$customer = $r['customer_id'];
			$bill_amount = $r['bill_amount'];
			$status = $r['status'];
			$orders[$r['product_id']]['id']= $r['pid'];
			$orders[$r['product_id']]['name']= $r['product_name'];
			$orders[$r['product_id']]['qty']= $r['qty'];
			$orders[$r['product_id']]['type']= $r['type'];
			$orders[$r['product_id']]['unit_price']= $r['unit_price'];
			$orders[$r['product_id']]['actual']= $r['oactual'];
			$orders[$r['product_id']]['recieved']= $r['orecieved'];
			$orders[$r['product_id']]['discount']= $r['odiscount'];
			$orders[$r['product_id']]['kot']= $r['kot'];
		}
		$sql = "select * from dealer as c where c.id=".$customer." limit 1;";
		$rst = $mysql->execute($sql);
		$cust = mysqli_fetch_array($rst[0]);
		?>
        <div class="cl">
          <div class="pull-left">
            <h3>&nbsp;&nbsp;&nbsp;Order Detail</h3>
            <img src="<?php echo $cust['photo']; ?>" width="150" > </div>
          <div class="details pull-left userprofile">
            <table class="table table-striped table-detail">
              <tr>
                <th>Order No: </th>
                <th><span class="label label-important" style="font-size:20px;"><?php echo strtoupper($billno) ?></span></th>
              </tr>
              <tr>
                <th>Order Current Status:</th>
                <th><?php 
				if($lock)
					echo "Locked";
				else
					echo "Not Locked";
				if($trns)
					echo " and Payment Closed"; 
				else
					echo " and Payment Not Closed"; 
				?></th>
              </tr>
              <tr>
                <th>Order Status:</th>
                <th><?php 
				if($status=='s')
					echo "Pending";
				else if($status=='p')
					echo "Printed";
				else if($status=='c')
					echo "Cancel";
				?></th>
              </tr>
            </table>
          </div>
        </div>
        <center><h3>Orders</h3></center>
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
		  $o_actual = 0 ;
		  $o_discount = 0;
		  $o_recieved= 0;
		  foreach($orders as $id=>$data)
		  {
		  ?>
            <tr>
              <td style="text-align:center"><?php echo ++$sno; ?></td>
              <td><a href="stock_product_view.php?id=<?php echo $id ?>"><?php echo $data['name'] ?></a></td>
              <td style="text-align:center"><?php echo $data['qty']." ".$data['type'] ?></td>
              <td style="text-align:right"><?php 
			  	echo $mysql->currency($data['actual']); 
			  	$o_actual += $data['actual']; ?></td>
              <td style="text-align:right"><?php 
			  	echo $mysql->currency($data['discount']); 
			  	$o_discount += $data['discount']; ?></td>
              <td style="text-align:right"><?php 
			  	echo $mysql->currency($data['recieved']); 
			  	$o_recieved += $data['recieved']; ?></td>
            </tr>
            <?php
		  }
		  ?>
          </tbody>
          <tfoot>
          <tr>
              <th style="text-align:right" colspan="3">Net</th>
              <th style="text-align:right"><?php echo $mysql->currency($o_actual) ?></th>
              <th style="text-align:right"><?php echo $mysql->currency($o_discount) ?></th>
              <th style="text-align:right"><?php echo $mysql->currency($o_recieved) ?></th>
            </tr>
          </tfoot>
        </table>
        <center><br><span class="label label-important" style="font-size:20px;"><?php echo $billno ?></span><br><br>
        <table class="table table-striped table-detail" style="width:400px; font-weight:bold">
									<tr>
										<th>Actual amount </th>
										<td style="text-align:right"><?php echo $mysql->currency($actual)  ?></td>
									</tr>
                                    <tr>
										<th>Round Off </th>
										<td style="text-align:right"><?php echo $mysql->currency($round)  ?></td>
									</tr>
                                    <tr style="font-size:18px">
										<th>Net Amount</th>
										<td style="text-align:right"><?php echo $mysql->currency($bill_amount);  ?></td>
									</tr>
								</table></center>
        <?php
	}
	else
	{
		?>
        No Bills With This No
        <?php
	}
}
?>
      </div>
    </form>
    <?php
include"footer.php";
?>