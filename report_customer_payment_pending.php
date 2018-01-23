<?php include"header.php"; ?>
    <div class="box">
      <div class="box-head">
        <h3>Customer Payment Pending Report</h3>
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
              <th>Customer</th>
              <th>
              <select name="customer" class="cho">
              <?php
			  $sql = "select id,name from customers ";
			  $rst = $mysql->execute($sql);
			  $customer = array();
			  while($r = mysqli_fetch_array($rst[0]))
			  {
				  $customer[$r['id']] = $r['name'];
				  ?><option <?php if($_POST['customer']==$r['id']) echo 'selected' ?> value="<?php echo $r['id'] ?>"><?php echo $r['name'] ?></option><?php
			  }
			  ?>
              </select>
              </th>
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
            <th>Actual Amount</th>
            <th>Tax</th>
            <th>Charges</th>
            <th>Round Off</th>
            <th>Discount</th>
            <th>Bill Amount</th>
            <th>Paid</th>
            <th>Balance</th>
          </tr>
        </thead>
        <?php
$sql = "select 
			b.bill_no,
			b.customer_id,
			date_format(b.date,'%d-%m-%Y')'date',
			b.actual_amount,
			b.bill_amount,
			(sum(b.odiscount)+b.discount)'discount',
			b.round_off,
			b.tax_amnt,
			b.charges_amnt			
		from 
			bill_orders as b 
		where 
			b.date between '".$from."' and '".$to."' 
			and
			b.customer_id='".$_POST['customer']."'
		group by 
			b.bill_no;
		select p.bill_no,sum(p.recieved)'amount' from bill_payment as p where p.date between '".$from."' and '".$to."' group by p.bill_no";
$rst = $mysql->execute($sql);
$discount=0;
$actual_amount = 0;
$bill_amount = 0;
$tax_amount = 0;
$charges_amount = 0;
$round_off = 0;
$paid = 0;
$balance = 0;
$payment = array();
while($r = mysqli_fetch_array($rst[1]))
	$payment[$r['bill_no']] = $r['amount'];
while($r = mysqli_fetch_array($rst[0]))
{
	?>
        <tr>
          <td style="text-align:center"><?php echo ++$sno; ?></td>
          <td style="text-align:center"><a href="bill_detail.php?billno=<?php echo $r['bill_no']; ?>"><?php echo $r['bill_no']; ?></a></td>
          <td style="text-align:center"><a href="customer_profile.php?id=<?php echo $r['customer_id']; ?>"><?php echo $customer[$r['customer_id']]; ?></a></td>
          <td style="text-align:center"><?php echo $r['date']; ?></td>
          <td style="text-align:right"><?php echo $mysql->currency($r['actual_amount']); ?></td>
          <td style="text-align:right"><?php echo $mysql->currency($r['tax_amnt']); ?></td>
          <td style="text-align:right"><?php echo $mysql->currency($r['charges_amnt']); ?></td>
          <td style="text-align:right"><?php echo $mysql->currency($r['round_off']); ?></td>
          <td style="text-align:right"><?php echo $mysql->currency($r['discount']); ?></td>
          <td style="text-align:right"><?php echo $mysql->currency($r['bill_amount']); ?></td>
          <td style="text-align:right"><?php echo $mysql->currency($payment[$r['bill_no']]); ?></td>
          <td style="text-align:right"><?php echo $mysql->currency($r['bill_amount']-$payment[$r['bill_no']]); ?></td>
        </tr>
        <?php
	$discount += $r['discount'];
	$actual_amount += $r['actual_amount'];
	$bill_amount += $r['bill_amount'];
	$tax_amount += $r['tax_amnt'];
	$charges_amount += $r['charges_amnt'];
	$round_off += $r['round_off'];
	$paid += $payment[$r['bill_no']];
	$balance += $r['bill_amount']-$payment[$r['bill_no']];
}
?>
        <tfoot>
          <tr>
            <th style="text-align:center" colspan="3">Tot Bills: <?php echo $sno; ?></th>
            <th style="text-align:right">Net</th>
            <th style="text-align:right"><?php echo $mysql->currency($actual_amount) ?></th>
            <th style="text-align:right"><?php echo $mysql->currency($tax_amount) ?></th>
            <th style="text-align:right"><?php echo $mysql->currency($charges_amount) ?></th>
            <th style="text-align:right"><?php echo $mysql->currency($round_off) ?></th>
            <th style="text-align:right"><?php echo $mysql->currency($discount) ?></th>
            <th style="text-align:right"><?php echo $mysql->currency($bill_amount) ?></th>
            <th style="text-align:right"><?php echo $mysql->currency($paid) ?></th>
            <th style="text-align:right"><?php echo $mysql->currency($balance) ?></th>
        </tfoot>
      </table>
    </div>
    <?php include"footer.php"; ?>