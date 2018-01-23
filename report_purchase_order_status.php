<?php include"header.php"; ?>
    <div class="box">
      <div class="box-head">
        <h3>Purchase Orders Pending Report</h3>
      </div>
      <br>
      <form action="#" method="post">
        <table class="table table-bordered table-striped" style="width:5px; overflow:visible; white-space:nowrap" align="center" cellpadding="10" cellspacing="10">
          <thead>
            <tr style="vertical-align:middle">
              <th>Filter : </th>
              <th><input type="text" name="from" value="<?php echo $_POST['from']?>" class="input-small datepick"></th>
              <th> TO </th>
              <th><input type="text" name="to" value="<?php echo $_POST['to'] ?>" class="input-small datepick"></th>
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
            <th>Date</th>
            <th>Product name</th>
            <th>Actual Amount</th>
            <th>Product Discount</th>
            <th>Total</th>
            <th>Bill Amount</th>
            <th>Qty</th>
            <th>Recieved QTY</th>
            <th>Pending</th>
          </tr>
        </thead>
        <?php
$sql = "select 
			b.bill_no,
			date_format(b.date,'%d-%m-%Y')'date',
			b.bill_amount,
			b.product_id,
			b.product_name,
			b.qty,
			b.recieved_qty,
			b.`type`,
			b.oactual,
			b.orecieved,
			b.odiscount
		from 
			purchase_orders as b 
		where ";
if(isset($_POST['from']) && isset($_POST['to']))
{
			$sql .="b.date between '".$_POST['from']."' and '".$_POST['to']."'
			and ";
}
 	$sql .=" b.lock=1
			and
			b.completed=0 
		order by 
			b.date,
			b.bill_no";
$rst = $mysql->execute($sql);
$odiscount=0;
$oactual_amount = 0;
$orecieved = 0;
$bill_amount = array();
$bill_no = array();
while($r = mysqli_fetch_array($rst[0]))
{
	?>
        <tr>
          <td style="text-align:center"><?php echo ++$sno; ?></td>
          <td style="text-align:center"><a href="purchase_detail.php?billno=<?php echo $r['bill_no']; ?>"><?php echo $r['bill_no']; ?></a></td>
          <td style="text-align:center"><?php if(!isset($bill_amount[$r['bill_no']])) echo $r['date']; ?></td>
          <td><a href="product_view.php?id=<?php echo $r['product_id'] ?>"><?php echo $r['product_name']; ?></a></td>
          <td style="text-align:right"><?php echo $mysql->currency($r['oactual']); ?></td>
          <td style="text-align:right"><?php echo $mysql->currency($r['odiscount']); ?></td>
          <td style="text-align:right"><?php echo $mysql->currency($r['orecieved']); ?></td>
          <td style="text-align:right"><?php if(!isset($bill_amount[$r['bill_no']])) echo $mysql->currency($r['bill_amount']); ?></td>
          <td style="text-align:center"><?php echo $r['qty']." ".$r['type']; ?></td>
          <td style="text-align:center"><?php echo $r['recieved_qty']." ".$r['type']; ?></td>
          <td style="text-align:center"><?php echo ($r['qty']-$r['recieved_qty'])." ".$r['type']; ?></td>
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
          	<th style="text-align:center"></th>
            <th style="text-align:center"><?php echo count($bill_no); ?></th>
            <th></th>
            <th style="text-align:right">Net</th>
            <th style="text-align:right"><?php echo $mysql->currency($oactual_amount) ?></th>
            <th style="text-align:right"><?php echo $mysql->currency($odiscount) ?></th>
            <th style="text-align:right"><?php echo $mysql->currency($orecieved) ?></th>
            <th style="text-align:right"><?php echo $mysql->currency(array_sum($bill_amount)) ?></th>
            <th></th>
            <th></th>
            <th></th>
        </tfoot>
      </table>
    </div>
    <?php include"footer.php"; ?>