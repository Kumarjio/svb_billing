<?php include"header.php"; ?>
    <div class="box">
      <div class="box-head">
        <h3>Sales Return Report</h3>
      </div>
      <br>
      <?php
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
			  <th>Bill No </th>
			  <td><input type="text" name="billno" value="<?php echo $_REQUEST['billno'] ?>" class="input-small form-control"></td>
              <th><button type="submit" name="filter" class="btn btn-blue4"><i class="icon-globe icon-white"></i> Filter</button></th>
            </tr>
          </thead>
        </table>
      </form>
      <hr>
      <table class="table table-striped rTable table-bordered dataTable-tools">
        <thead>
          <tr>
            <th>Sno</th>
            <th>Bill No</th>
            <th>Date</th>
            <th>Product name</th>
            <th>Qty</th>
            <th>Actual Amount</th>
            <th>Product Discount</th>
            <th>Tax Amount</th>
            <th>Bill Amount</th>
          </tr>
        </thead>
        <?php
$sql = "select 
					date(r.it)'date',
					r.bill_no,
					p.id,
					p.pid,
					p.type,
					p.name,
					r.qty,
					r.unit_price,
					r.discount,
					r.recieved_price,
					r.tax_amount,
					r.net_amount 
				from 
					bill_sale_return as r,
					product as p
				where 
					r.pid=p.id and r.`is`=1 ";
if($_REQUEST['billno']!=''){
		$sql .= " and r.bill_no='".$_REQUEST['billno']."'";
}else{
		$sql .= " and date(r.it) between '".$from."' and '".$to."'";
}
$sql .="  order by 
					date(r.it),
					r.bill_no;
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
          <td style="text-align:center"><?php echo $r['date']; ?></td>
          <td><a href="product_view.php?id=<?php echo $r['id'] ?>"><?php echo $r['name']; ?></a></td>
          <td style="text-align:center"><?php echo $r['qty']." ".$r['type']; ?></td>
          <td style="text-align:right"><?php echo $mysql->currency($r['unit_price']*$r['qty']); ?></td>
          <td style="text-align:right"><?php echo $mysql->currency(($r['unit_price']*$r['qty'])-((($r['unit_price']*$r['qty'])/100)*$r['discount'])); ?></td>
          <td style="text-align:right"><?php echo $mysql->currency($r['tax_amount']); ?></td>
          <td style="text-align:right"><?php echo $mysql->currency($r['net_amount']); ?></td>
        </tr>
        <?php
	$odiscount += ($r['unit_price']*$r['qty'])-((($r['unit_price']*$r['qty'])/100)*$r['discount']);
	$oactual_amount += $r['unit_price']*$r['qty'];
	$orecieved += $r['tax_amount'];
	$bill_amount[$r['bill_no']] += $r['net_amount'];
	$bill_no[$r['bill_no']]=1;
}
?>
        <tfoot>
          <tr>
            <th colspan="4"></th>
            <th style="text-align:right">Net</th>
            <th style="text-align:right"><?php echo $mysql->currency($oactual_amount) ?></th>
            <th style="text-align:right"><?php echo $mysql->currency($odiscount) ?></th>
            <th style="text-align:right"><?php echo $mysql->currency($orecieved) ?></th>
            <th style="text-align:right"><?php echo $mysql->currency(array_sum($bill_amount)) ?></th>
        </tfoot>
      </table>
    </div>
    <?php include"footer.php"; ?>