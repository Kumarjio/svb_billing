<?php include"header.php"; ?>
    <div class="box">
      <div class="box-head">
        <h3>Orders Report</h3>
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
            <th>Product name</th>
            <th>Qty</th>
            <th>Actual Amount</th>
            <th>Product Discount</th>
            <th>Total</th>
            <th>Bill Amount</th>
          </tr>
        </thead>
        <?php
$sql = "select 
			b.bill_no,
			b.customer_id,
			date_format(b.date,'%d-%m-%Y')'date',
			b.bill_amount,
			b.product_id,
			b.product_name,
			b.qty,
			b.`type`,
			b.oactual,
			b.orecieved,
			b.odiscount
		from 
			bill_orders as b 
		where 
			b.date between '".$from."' and '".$to."' 
		order by 
			b.date,
			b.bill_no;
		select * from customers;";
$rst = $mysql->execute($sql);
$odiscount=0;
$oactual_amount = 0;
$orecieved = 0;
$bill_amount = array();
$bill_no = array();
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
          <td style="text-align:center"><?php if(!isset($bill_amount[$r['bill_no']])) echo $r['date']; ?></td>
          <td><a href="product_view.php?id=<?php echo $r['product_id'] ?>"><?php echo $r['product_name']; ?></a></td>
          <td style="text-align:center"><?php echo $r['qty']." ".$r['type']; ?></td>
          <td style="text-align:right"><?php echo $mysql->currency($r['oactual']); ?></td>
          <td style="text-align:right"><?php echo $mysql->currency($r['odiscount']); ?></td>
          <td style="text-align:right"><?php echo $mysql->currency($r['orecieved']); ?></td>
          <td style="text-align:right"><?php if(!isset($bill_amount[$r['bill_no']])) echo $mysql->currency($r['bill_amount']); ?></td>
        </tr>
        <?php
	$odiscount += $r['odiscount'];
	$oactual_amount += $r['oactual'];
	$orecieved += $r['orecieved'];
	$bill_amount[$r['bill_no']] = $r['bill_amount'];
	$bill_no[$r['bill_no']]=1;
}
?>
        <tfoot>
          <tr>
            <th style="text-align:center" colspan="3"><?php echo count($bill_no); ?></th>
            <th></th>
            <th></th>
            <th style="text-align:right">Net</th>
            <th style="text-align:right"><?php echo $mysql->currency($oactual_amount) ?></th>
            <th style="text-align:right"><?php echo $mysql->currency($odiscount) ?></th>
            <th style="text-align:right"><?php echo $mysql->currency($orecieved) ?></th>
            <th style="text-align:right"><?php echo $mysql->currency(array_sum($bill_amount)) ?></th>
        </tfoot>
      </table>
    </div>
    <?php include"footer.php"; ?>