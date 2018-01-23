<?php include"header.php"; ?>
    <div class="box">
      <div class="box-head">
        <h3>Purchase status Report</h3>
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
            <th>Purchase No</th>
            <th>Date</th>
            <th>Purchase Amount</th>
            <th>Lock Status</th>
            <th>Transaction Status</th>
            <th>Current Status</th>
          </tr>
        </thead>
        <?php
$sql = "select 
			b.bill_no,
			date_format(b.date,'%d-%m-%Y')'date',
			b.bill_amount,
			b.`status`,
			b.`lock`,
			b.trans_close
		from 
			purchase_orders as b 
		where 
			b.date between '".$from."' and '".$to."' 
		group by 
			b.bill_no";
$rst = $mysql->execute($sql);
$bill_amount = 0;
$lock = 0;
$trans = 0;
$save = 0;
$print = 0;
$cancel = 0;
$lock_noprint = 0;
while($r = mysqli_fetch_array($rst[0]))
{
	?>
        <tr>
          <td style="text-align:center"><?php echo ++$sno; ?></td>
          <td style="text-align:center"><a href="bill_detail.php?billno=<?php echo $r['bill_no']; ?>"><?php echo $r['bill_no']; ?></a></td>
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
				}else if($r['status']=='l') {	
					echo'Locked'; 
					$locked++;
				}else if($r['status']=='c') {
					echo 'Cancelled' ;
					$cancel++;
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
            <th></th>
            <th style="text-align:right">Net</th>
            <th style="text-align:right"><?php echo $mysql->currency($bill_amount) ?></th>
            <th>Locked : <?php echo intval($lock) ?><br>Pending : <?php echo ($sno-$lock) ?></th>
            <th>Closed : <?php echo intval($trans) ?><br>Pending : <?php echo ($sno-$trans) ?></th>
            <th>Save Mode : <?php echo $save ?><br>
            	Locked : <?php echo $locked ?><br>
                Cancelled : <?php echo $cancel ?></th>
        </tfoot>
      </table>
    </div>
    <?php include"footer.php"; ?>