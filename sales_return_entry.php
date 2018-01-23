<?php
include"header.php";
?>
<?php include"classes/billing.php"; ?>
      <div class="box">
        <div class="box-head"><h3>Sales Return</h3></div>
        <br> 
		<form action="" method="post">
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
		</form>
        <?php
if(isset($_REQUEST['billno']))
{
	if(isset($_REQUEST['return_qty'])){
		foreach($_REQUEST['return_qty'] as $pid=>$qty){
			if(intval($qty)>0){
				$unit_price 	= $_REQUEST['unit_price'][$pid];
				$discount 		= $_REQUEST['discount'][$pid];
				$recieved_price = ($unit_price*$qty)-((($unit_price*$qty)/100)*$discount);
				$tax_amount    = ($recieved_price/100)*$billing->getTax();
				$sql = "INSERT INTO `bill_sale_return` (`bill_no`, `pid`, `qty`,`unit_price`,`discount`,`recieved_price`,`tax_amount`,`net_amount`) 
							VALUES 
						('".$_REQUEST['billno']."', '".$pid."', '".$qty."','".$unit_price."','".$discount."', '".$recieved_price."','".$tax_amount."','".($recieved_price+$tax_amount)."');";
				$sql .= "UPDATE `product` SET `available`=`available`+".$qty." WHERE  `id`=".$pid.";";
				$mysql->execute($sql);
			}
		}
	}
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
				kot,
				oid
			from 
				bill_orders as bo 
			where 
				bo.bill_no='".$billno."'
			order by
				bo.kot asc";
	$rst = $mysql->execute($sql);
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
			$orders[$r['product_id']]['oid']= $r['oid'];
		}
		$rsql = "select r.pid,r.qty from bill_sale_return as r where r.bill_no='".$billno."' and `is`=1";
		$rrst = $mysql->execute($rsql);
		$sale_return = array();
		while($rr = mysqli_fetch_array($rrst[0])){
			$sale_return[$rr['pid']] += $rr['qty'];
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
		$rst = $mysql->execute($sql);
		$cust = mysqli_fetch_array($rst[0]);
		$biller = mysqli_fetch_array($rst[2]);
		?>
        <div class="cl">
          <div class="pull-left">
            <h3>&nbsp;&nbsp;&nbsp;Bill &amp; Customer Detail</h3>
		  </div>
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
            </table>
          </div>
        </div>
        <center><h3>Orders</h3></center>
		<form action="#" method="post">
		<input type="hidden" name="billno" class="text" value="<?php echo $billno ?>">
        <table class='table table-striped table-bordered'>
          <thead>
            <tr>
              <th>Sno</th>
              <th>Code</th>
              <th>Name</th>
              <th>Qty(s)</th>
			  <th>Returned</th>
              <th>Returned Qty</th>
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
              <td style="text-align:center"><?php echo $data['qty']." ".$data['type'] ?></td>
			  <td style="text-align:center"><?php 
				if(isset($sale_return[$data['id']])){
					echo $sale_return[$data['id']]." ".$data['type'];
				}else{
					echo '-';
				}
			  ?></td>
			  <td style="text-align:center"><?php
			  if($data['qty']-$sale_return[$data['id']] > 0){ ?>
			    <input type="hidden" name="unit_price[<?php echo $data['id'] ?>]" value="<?php echo $data['unit_price'] ?>"/>
				<input type="hidden" name="discount[<?php echo $data['id'] ?>]" value="<?php echo $data['discount'] ?>"/>
				<input type="text" data-qty="<?php echo $data['qty']-$sale_return[$data['id']] ?>" name="return_qty[<?php echo $data['id'] ?>]" class="form-control input-small sale_return" /><?php
			  }else{
				  echo '-';
			  }?>
			  </td>
            </tr>
            <?php
		  }
		  ?>
          </tbody>
        </table>
		<center><button type="submit" name="stock_return" class="btn btn-blue4">Submit</button></center>
		</form>
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
	<br><br>
      </div>

<script type="text/javascript">
$(document).ready(function(){
	$(".sale_return").on("keyup blur",function(){
		if(parseInt($(this).attr("data-qty")) < $(this).val()){
			$(this).val('');
			alert("Max Qty Exceeds");
		}
	});
});
</script>	
	
    <?php
include"footer.php";
?>