<?php
include"header.php";
?>
    <form action="" method="post">
      <div class="box">
        <div class="box-head"><h3>Bill Payment Details</h3></div>
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
				bill_orders as bo 
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
				select date_format(py.date,'%d-%m-%Y')'date',py.recieved,py.returned,py.paid from bill_payment as py where py.bill_no='".$billno."' and py.`recieved`-py.`returned`!=0 and `is`=1  ";
		$rst = $mysql->execute($sql);
		$cust = mysqli_fetch_array($rst[0]);
		?>
        <div class="cl">
          <div class="pull-left">
            <h3>Customer Detail</h3>
            <img src="<?php echo $cust['photo']; ?>" width="150" > </div>
          <div class="details pull-left userprofile">
            <table class="table table-striped table-detail">
              <tr>
                <th>Bill No: </th>
                <td><span class="label label-important" style="font-size:20px;"><?php echo $billno ?></span></td>
              </tr>
              <tr>
                <th>Bill Current Status:</th>
                <td><?php 
				if($lock)
					echo "Locked";
				else
					echo "Not Locked";
				if($trns)
					echo " and Payment Closed"; 
				else
					echo " and Payment Not Closed"; 
				?></td>
              </tr>
              <tr>
                <th>Bill Status:</th>
                <td><?php 
				if($status=='s')
					echo "Pending";
				else if($status=='p')
					echo "Printed";
				else if($status=='c')
					echo "Cancel";
				?></td>
              </tr>
              <tr>
                <th>Name: </th>
                <td><?php echo $cust['name'] ?></td>
              </tr>
              <tr>
                <th>Phone:</th>
                <td><?php echo $cust['phone'] ?></td>
              </tr>
              <tr>
                <th>Address:</th>
                <td><?php echo $cust['address'] ?></td>
              </tr>
              <tr>
                <th>Email:</th>
                <td><a href="#"><?php echo $cust['email'] ?></a></td>
              </tr>
            </table>
          </div>
        </div>
        <table class='table table-striped table-bordered'>
          <thead>
            <tr>
              <th>Sno</th>
              <th>Date</th>
              <th>Recieved</th>
              <th>Returned</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
          <?php
		  $sno = 0;
		  $payment_date = '';
		  $p_amount = 0.0;
		  ?>
              <?php
			  while($p = mysqli_fetch_array($rst[1]))
			  {
				  ?>
				  <tr>
				  <td style="text-align:center"><?php echo ++$sno; ?></td>
                  <td style="text-align:center"><?php echo $p['date'] ?></td>
                  <td style="text-align:right"><?php echo $mysql->currency($p['recieved']) ?></td>
                  <td style="text-align:right"><?php echo $mysql->currency($p['returned']) ?></td>
                  <td style="text-align:right"><?php echo $mysql->currency($p['recieved']-$p['returned']) ?></td>
				  </tr>
				  <?php
				  if($p['paid'])
				  $payment_date = $p['date'];
				  $p_amount += $p['recieved']-$p['returned'];
			  }
			  ?>
          </tbody>
        </table>
        <center><br><span class="label label-important" style="font-size:20px;"><?php echo $billno ?></span><br><br><b>
        <?php if($payment_date!=''){ ?>
        Payment Completed on : <?php echo $payment_date ?>
        <?php }else{ ?>
        Payment Not Yet Completed
        <?php } ?></b>
        <table class="table table-striped table-detail" style="width:400px; font-weight:bold">
									<tr>
										<th>Actual_amount </th>
										<td style="text-align:right"><?php echo $mysql->currency($actual)  ?></td>
									</tr>
                                    <tr>
										<th>Round Off </th>
										<td style="text-align:right"><?php echo $mysql->currency($round)  ?></td>
									</tr>
									<tr>
										<th>Discount</th>
										<td style="text-align:right"><?php echo $mysql->currency($dis)  ?></td>
									</tr>
                                    <tr>
										<th>Discount Reason</th>
										<td style="text-align:right"><?php echo $dis_reason  ?></td>
									</tr>
                                    <tr style="font-size:18px">
										<th>Net Amount</th>
										<td style="text-align:right"><?php echo $mysql->currency($bill_amount)  ?></td>
									</tr>
                                    <tr style="font-size:18px">
                                    	<th>Paid</th>
                                        <td style="text-align:right"><?php echo $mysql->currency($p_amount); ?></td>
                                    </tr>
                                    <tr style="font-size:18px">
                                    	<th>Balance</th>
                                        <td style="text-align:right"><?php echo $mysql->currency($bill_amount-$p_amount); ?></td>
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