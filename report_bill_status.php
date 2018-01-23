<?php include"header.php"; ?>
    <div class="box">
      <div class="box-head">
        <h3>Bill status Report</h3>
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
            <th>Bill Amount</th>
            <th>Lock Status</th>
            <th>Transaction Status</th>
            <th>Current Status</th>
          </tr>
        </thead>
        <?php
$sql = "select 
			b.bill_no,
			b.customer_id,
			date_format(b.date,'%d-%m-%Y')'date',
			b.bill_amount,
			b.`status`,
			b.`lock`,
			b.trans_close
		from 
			bill_orders as b 
		where 
			b.date between '".$from."' and '".$to."' 
		group by 
			b.bill_no;
		select * from customers;";
$rst = $mysql->execute($sql);
$bill_amount = 0;
$lock = 0;
$trans = 0;
$save = 0;
$print = 0;
$cancel = 0;
$lock_noprint = 0;
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
          <td style="text-align:right"><?php echo $mysql->currency($r['bill_amount']); ?></td>
          <td style="text-align:center"><?php 
		  	if($r['lock']){
				echo 'Locked'; 
				$lock++;
			}else {
				echo 'Pending'; 
			} ?></td>
          <td style="text-align:center"><?php 
		  	if($r['trans_close']) {
				echo 'Locked'; 
				$trans++;
			}else {
				echo 'Pending'; 
			} ?></td>
          <td style="text-align:center">
		  	<?php 
				if($r['status']=='s') {
					echo 'Save Mode'; 
					$save++;
				}else if($r['status']=='p') {	
					echo'Printed'; 
					$print++;
				}else if($r['status']=='c') {
					echo 'Cancelled' ;
					$cancel++;
				}else if($r['status']=='l') {
					echo 'Locked without Printing' ;
					$lock_noprint++;
				}?>
          </td>
        </tr>
        <?php
	$bill_amount += $r['bill_amount'];
}
?>
        <tfoot>
          <tr>
          	<th style="text-align:center"><?php echo $sno; ?></th>
            <th style="text-align:right" colspan="3">Net</th>
            <th style="text-align:right"><?php echo $mysql->currency($bill_amount) ?></th>
            <th>Locked : <?php echo intval($lock) ?><br>Pending : <?php echo ($sno-$lock) ?></th>
            <th>Closed : <?php echo intval($trans) ?><br>Pending : <?php echo ($sno-$trans) ?></th>
            <th>Save Mode : <?php echo $save ?><br>
            	Printed : <?php echo $print ?><br>
                Cancelled : <?php echo $cancel ?><br>
                Locked without Printing : <?php echo $lock_noprint ?></th>
        </tfoot>
      </table>
    </div>
    <?php include"footer.php"; ?>