<?php
include"header.php";
?>
    <form action="" method="post">
      <div class="box">
        <div class="box-head"><h3>WB Bill Details</h3></div>
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
				tax_amnt,
				actual_amount,
				recieved_amount,
				returned_amount,
				round_off,
				discount,
				discount_reason,
				`lock`,
				trans_close,
				customer_id,
				biller_id,
				bill_amount,
				`status`,
				product_code,
				product_name,
				qty,
				`type`,
				unit_price,
				oactual,
				orecieved,
				odiscount,
				product_id,
				pid,
				charges_amnt,
				kot
			from 
				bill_orders as bo 
			where 
				bo.bill_no='".$billno."'
			order by
				bo.kot asc";
	$rst = $mysqlwb->execute($sql);
	if(mysqli_num_rows($rst[0])>0)
	{
		$orders = array();
		while($r = mysqli_fetch_array($rst[0]))
		{
			$biller = $r['biller_id'];
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
			$charges_amnt = $r['charges_amnt'];
			$tax = $r['tax_amnt'];
			$orders[$r['product_id']]['id']= $r['pid'];
			$orders[$r['product_id']]['product_code']= $r['product_code'];
			$orders[$r['product_id']]['name']= $r['product_name'];
			$orders[$r['product_id']]['qty']= $r['qty'];
			$orders[$r['product_id']]['type']= $r['type'];
			$orders[$r['product_id']]['unit_price']= $r['unit_price'];
			$orders[$r['product_id']]['actual']= $r['oactual'];
			$orders[$r['product_id']]['recieved']= $r['orecieved'];
			$orders[$r['product_id']]['discount']= $r['odiscount'];
			$orders[$r['product_id']]['kot']= $r['kot'];
		}
		$sql = "select * from customers as c where c.id=".$customer." limit 1;
				select date_format(py.date,'%d-%m-%Y')'date',py.recieved,py.returned,py.paid,py.type,py.type_id from bill_payment as py where py.bill_no='".$billno."' and py.`recieved`-py.`returned`!=0 and `is`=1  ;
				select * from profile where id='".$biller."' limit 1;
				select
					s.`id`, 
					s.name,
					s.`type`,
					s.type_source,
					s.rate,
					s.rate_type,
					s.rate_status,
					b.value,
					b.rate'bill_rate'
				from 
					bill_charges as b,
					shop_charges as s 
				where 
					s.id=b.c_id and b.bill_no='".$billno."'
					and
					b.`is`=1";
		$rst = $mysqlwb->execute($sql);
		$cust = mysqli_fetch_array($rst[0]);
		$biller = mysqli_fetch_array($rst[2]);
		?>
        <div class="cl">
          <div class="pull-left">
            <h3>&nbsp;&nbsp;&nbsp;Bill &amp; Customer Detail</h3>
            <?php
													if(!file_exists($cust['photo']))
													{
														if($cust['gender']=='Male')
														$cust['photo'] = 'img/default_prf_m.png';
														else if($cust['gender']=='Female')
														$cust['photo'] = 'img/default_prf_f.png';
														else
														$cust['photo'] = 'img/default_prf.png';
													}
													?>
            <img src="<?php echo $cust['photo']; ?>" width="150" > </div>
          <div class="details pull-left userprofile">
            <table class="table table-striped table-detail">
              <tr>
                <th>Bill No: </th>
                <th><span class="label label-important" style="font-size:20px;"><?php echo strtoupper($billno) ?></span></th>
              </tr>
              <tr>
                <th>Date: </th>
                <th><?php echo $date ?></th>
              </tr>
              <tr>
                <th>Biller Name: </th>
                <th><a href="profile.php?id=<?php echo $biller['id'] ?>"><?php echo $biller['name'] ?></a></th>
              </tr>
              <tr>
                <th>Bill Current Status:</th>
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
                <th>Bill Status:</th>
                <th><?php 
				if($status=='s')
					echo "Pending";
				else if($status=='p')
					echo "Printed";
				else if($status=='c')
					echo "Cancel";
				?></th>
              </tr>
              <tr>
                <th>Name: </th>
                <th><a href="customer_profile.php?id=<?php echo $cust['id'] ?>"><?php echo $cust['name'] ?></a></th>
              </tr>
              <tr>
                <th>Phone:</th>
                <th><?php echo $cust['phone'] ?></th>
              </tr>
              <tr>
                <th>Address:</th>
                <th><?php echo $cust['address'] ?></th>
              </tr>
              <tr>
                <th>Email:</th>
                <th><a href="#"><?php echo $cust['email'] ?></a></th>
              </tr>
              <?php
			  while($ar = mysqli_fetch_array($rst[3]))
			  {
				  ?>
				 <tr>
                 	<th><?php echo $ar['name'] ?>:</th>
                    <th style="text-align:left !important;"><?php
					switch($ar['type'])
					{
						case 'input':
							$value = $ar['value'];
							break;
						case 'checkbox':
							$value = $ar['value'];
							break;
						case 'select':
							if($ar['type_source'] == 'employees list')
							{
								$sql = "select p.id,p.name from profile as p where p.id='".$ar['value']."';";
								$profile = $mysql->execute($sql);
								while($pr = mysqli_fetch_array($profile[0]))
								{
									$value = "<a href='profile.php?id=".($ar['value'])."' >".$pr['name'];
									$value .= '</a>';
								}
							}
							else
							{
								$sql = "select s.id,s.value,s.name from shop_charges_list as s where s.att_id = '".$ar['id']."' and s.value='".$ar['value']."'";
							    $custom = $mysql->execute($sql);
							    while($cus = mysqli_fetch_array($custom[0]))
								{
									$value = $cus['name'];
								}
							}
							break;	
					}
					if($ar['rate_status'])
					{
						echo $ar['bill_rate'];
					}
					else
					{
						echo $value;
					}
					?>
                           </th>
                 </tr>
				  <?php
			  }
			  ?>
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
              <th>KOT</th>
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
              <td style="text-align:center"><a href="product_view.php?id=<?php echo $data['id'] ?>"><?php echo $data['product_code'] ?></a></td>
              <td><a href="product_view.php?id=<?php echo $data['id'] ?>"><?php echo $data['name'] ?></a></td>
              <td style="text-align:center">
			  <a href="kot_detail.php?kot=<?php echo $data['kot'] ?>"><?php echo $data['kot'] ?></a></td>
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
              <th style="text-align:right" colspan="5">Net</th>
              <th style="text-align:right"><?php echo $mysql->currency($o_actual) ?></th>
              <th style="text-align:right"><?php echo $mysql->currency($o_discount) ?></th>
              <th style="text-align:right"><?php echo $mysql->currency($o_recieved) ?></th>
            </tr>
          </tfoot>
        </table>
         <center><h3>Payments</h3> <a href="cash_detail.php?from=2&for=<?php echo $billno ?>">click here to view cash</a></center>
        <table class='table table-striped dataTable table-bordered'>
          <thead>
            <tr>
              <th>Sno</th>
              <th>Date</th>
              <th>Pay Type</th>
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
		  $p_recieved=$p_returned=$p_paid=0
		  ?>
              <?php
			  while($p = mysqli_fetch_array($rst[1]))
			  {
				  ?>
				  <tr>
				  <td style="text-align:center"><?php echo ++$sno; ?></td>
                  <td style="text-align:center"><?php echo $p['date'] ?></td>
                  <td><?php 
				  if($p['type']==0)
				  {
					  echo 'Cash';
				  }
				  else if($p['type']==1)
				  {
					  echo 'Chequee';
				  }
				  else if($p['type']==2)
				  {
					  echo 'Demand Draft';
				  }
				  ?></td>
                  <td style="text-align:right"><?php echo $mysql->currency($p['recieved']);$p_recieved+=$p['recieved'] ?></td>
                  <td style="text-align:right"><?php echo $mysql->currency($p['returned']);$p_returned+=$p['returned'] ?></td>
                  <td style="text-align:right"><?php echo $mysql->currency($p['recieved']-$p['returned']);$p_paid+=$p['recieved']-$p['returned'] ?></td>
				  </tr>
				  <?php
				  if($p['paid'])
				  $payment_date = $p['date'];
				  $p_amount += $p['recieved']-$p['returned'];
			  }
			  ?>
          </tbody>
          <tfoot>
            <tr>
              <th style="text-align:right" colspan="3">Net</th>
              <th style="text-align:right"><?php echo $mysql->currency($p_recieved) ;?></th>
              <th style="text-align:right"><?php echo $mysql->currency($p_returned); ?></th>
              <th style="text-align:right"><?php echo $mysql->currency($p_paid); ?></th>
            </tr>
          </tfoot>
        </table>
        <center><br><span class="label label-important" style="font-size:20px;"><?php echo strtoupper($billno) ?></span><br><br><b>
        <?php if($payment_date!=''){ ?>
        Payment Completed on : <?php echo $payment_date ?>
        <?php }else{ ?>
        Payment Not Yet Completed
        <?php } ?></b>
        <table class="table table-striped table-detail" style="width:400px; font-weight:bold">
									<tr>
										<th>Actual amount </th>
										<td style="text-align:right"><?php echo $mysql->currency($actual-$charges_amnt-$tax)  ?></td>
									</tr>
                                    <tr>
										<th>Charges amount </th>
										<td style="text-align:right"><?php echo $mysql->currency($charges_amnt)  ?></td>
									</tr>
                                    <tr>
										<th>Tax amount </th>
										<td style="text-align:right"><?php echo $mysql->currency($tax)  ?></td>
									</tr>
                                    <tr>
										<th>Net with Charges & Tax </th>
										<td style="text-align:right"><?php echo $mysql->currency($actual)  ?></td>
									</tr>
                                    <tr>
										<th>Round Off </th>
										<td style="text-align:right"><?php echo $mysql->currency($round)  ?></td>
									</tr>
									<tr>
										<th>Discount</th>
										<td style="text-align:right"><?php echo $mysql->currency($dis+$o_discount);  ?></td>
									</tr>
                                    <tr>
										<th>Discount Reason</th>
										<td style="text-align:right"><?php echo $dis_reason  ?></td>
									</tr>
                                    <tr style="font-size:18px">
										<th>Net Amount</th>
										<td style="text-align:right"><?php echo $mysql->currency($bill_amount);  ?></td>
									</tr>
                                    <tr style="font-size:18px">
                                    	<th>Paid</th>
                                        <td style="text-align:right"><?php echo $mysql->currency($p_amount); ?></td>
                                    </tr>
                                    <tr style="font-size:18px">
                                    	<th>Balance</th>
                                        <td style="text-align:right"><?php 
										echo $mysql->currency($bill_amount-$p_amount); ?></td>
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