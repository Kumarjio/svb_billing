<?php include"header.php"; ?>
    <div class="box">
      <div class="box-head">
        <h3>Expences Report</h3>
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
            <th>Date</th>
            <th>Type</th>
            <th>Amount</th>
          </tr>
        </thead>
        <?php
$sql = "select 
			date_format(e.date,'%d-%m-%Y')'date',
			t.name,
			e.person,
			sum(e.amount)'amount' 
		from 
			expences as e,
			expences_type as t
		where
			t.id=e.e_id
			and
			e.date between '".$from."' and '".$to."' 
			and
			e.`is`=1
		group by 
			t.id;
		select 
			p.bill_no,date_format(p.date,'%d-%m-%Y')'date',sum(p.recieved-p.returned)'amount' 
		from 
			purchase_payment as p
		where 
			p.date between '".$from."' and '".$to."' 
			and
			p.`is`=1
		group by 
			p.bill_no;";
$rst = $mysql->execute($sql);
$tot_amount = 0;
while($r = mysqli_fetch_array($rst[0]))
{
	?>
        <tr>
          <td style="text-align:center"><?php echo ++$sno; ?></td>
          <td style="text-align:center"><?php echo $r['date']; ?></td>
          <td style="text-align:center"><?php echo strtoupper($r['name']); 
		  if($r['person']!='person')
		  	echo ' to '.strtoupper($r['person']);
		  ?></td>
          <td style="text-align:right"><?php echo $mysql->currency($r['amount']); ?></td>
        </tr>
        <?php
	$tot_amount += $r['amount'];
}
while($r = mysqli_fetch_array($rst[1]))
{
	?>
        <tr>
          <td style="text-align:center"><?php echo ++$sno; ?></td>
          <td style="text-align:center"><?php echo $r['date']; ?></td>
          <td style="text-align:center"><a href="bill_detail.php?billno=<?php echo $r['bill_no']; ?>"><?php echo $r['bill_no']; ?></a></td>
          <td style="text-align:right"><?php echo $mysql->currency($r['amount']); ?></td>
        </tr>
        <?php
	$tot_amount += $r['amount'];
}
?>
        <tfoot>
          <tr>
            <th colspan="3" style="text-align:right">Net</th>
            <th style="text-align:right"><?php echo $mysql->currency($tot_amount) ?></th>
        </tfoot>
      </table>
    </div>
    <?php include"footer.php"; ?>