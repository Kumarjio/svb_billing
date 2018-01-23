<?php include"header.php"; ?>
    <div class="box">
      <div class="box-head">
        <h3>Discount Report</h3>
      </div>
      <br>
      <?php
$from = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-30,date('Y'),0));
$to = date('Y-m-d');
if(isset($_POST['from']))
	$from = $_POST['from'];
if(isset($_POST['to']))
	$to = $_POST['to'];
?>
      <form action="#" method="post">
        <table class="table table-bordered table-striped" style="width:5px; overflow:visible; white-space:nowrap" align="center" cellpadding="10" cellspacing="10">
          <thead>
            <tr style="vertical-align:middle">
              <th>Filter : </th>
              <th><input type="text" name="from" value="<?php echo $from ?>" class="input-small datepick"></th>
              <th> TO </th>
              <th><input type="text" name="to" value="<?php echo $to ?>" class="input-small datepick"></th>
              <th><button type="submit" name="filter" class="btn btn-blue4"><i class="icon-globe icon-white"></i> Filter</button></th>
            </tr>
          </thead>
        </table>
      </form>
      <hr>
      <table class="table table-striped dataTable table-bordered dataTable-tools">
        <thead>
          <tr>
            <th>Sno</th>
            <th>Bill No</th>
            <th>Customer</th>
            <th>Date</th>
            <th>OverAll Discount</th>
            <th>Discount Reason</th>
            <th>Product Discount</th>
            <th>Total</th>
            <th>Actual Amount</th>
            <th>Bill Amount</th>
          </tr>
        </thead>
        <?php
$sql = "select 
			b.bill_no,
			b.customer_id,
			date_format(b.date,'%d-%m-%Y')'date',
			b.actual_amount,
			b.bill_amount,
			b.discount,
			b.discount_reason,
			sum(b.odiscount)'odiscount'
		from 
			bill_orders as b 
		where 
			b.date between '".$from."' and '".$to."' 
			and 
			(
				b.discount!=0
				or
				b.odiscount!=0
			)
		group by 
			b.bill_no;
		select * from customers; ";
$rst = $mysql->execute($sql);
$discount=0;
$odiscount=0;
$actual_amount = 0;
$bill_amount = 0;
$customer = array();
while($r = mysqli_fetch_array($rst[1]))
	$customer[$r['id']] = $r['name'];
while($r = mysqli_fetch_array($rst[0]))
{
	?>
        <tr>
          <td style="text-align:center"><?php echo ++$sno; ?></td>
          <td style="text-align:center"><a href="bill_detail.php?billno=<?php echo $r['bill_no']; ?>"><?php echo $r['bill_no']; ?></a></td>
          <td><a href="customer_profile.php?id=<?php echo $r['customer_id']; ?>"><?php echo $customer[$r['customer_id']]; ?></a></td>
          <td style="text-align:center"><?php echo $r['date']; ?></td>
          <td style="text-align:right"><?php echo $mysql->currency($r['discount']); ?></td>
          <td><?php echo $r['discount_reason']; ?></td>
          <td style="text-align:right"><?php echo $mysql->currency($r['odiscount']); ?></td>
          <td style="text-align:right"><?php echo $mysql->currency($r['discount']+$r['odiscount']); ?></td>
          <td style="text-align:right"><?php echo $mysql->currency($r['actual_amount']); ?></td>
          <td style="text-align:right"><?php echo $mysql->currency($r['bill_amount']); ?></td>
        </tr>
        <?php
	$discount += $r['discount'];
	$odiscount += $r['odiscount'];
	$actual_amount += $r['actual_amount'];
	$bill_amount += $r['bill_amount'];
}
?>
        <tfoot>
          <tr>
            <th style="text-align:right" colspan="3">Net</th>
            <th style="text-align:right"><?php echo $mysql->currency($discount) ?></th>
            <th></th>
            <th style="text-align:right"><?php echo $mysql->currency($odiscount) ?></th>
            <th style="text-align:right"><?php echo $mysql->currency($odiscount+$discount) ?></th>
            <th style="text-align:right"><?php echo $mysql->currency($actual_amount) ?></th>
            <th style="text-align:right"><?php echo $mysql->currency($bill_amount) ?></th>
        </tfoot>
      </table>
    </div>
    <?php include"footer.php"; ?>