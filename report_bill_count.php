<?php include"header.php"; ?>
    <div class="box">
      <div class="box-head">
        <h3>Bill Count Report</h3>
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
      <table class="table table-striped table-bordered ">
        <thead>
          <tr>
            <th>Sno</th>
            <th>Date</th>
            <th>Bill Count</th>
            <th>Bill Amount</th>
          </tr>
        </thead>
        <?php
$sql = "select 
			count(*)'count',
			group_concat(b.bill_no)'bill_no',
			date_format(b.date,'%d-%m-%Y')'date',
			sum(b.bill_amount)'bill_amount'
		from 
			bill as b 
		where 
			b.date between '".$from."' and '".$to."' 
		group by 
			b.date";
$rst = $mysql->execute($sql);
$bill_amount = 0;
$count = 0;
while($r = mysqli_fetch_array($rst[0]))
{
	$bill_no = explode(',',$r['bill_no']);
	?>
        <tr>
          <td style="text-align:center"><?php echo ++$sno; ?></td>
          <td style="text-align:center"><?php echo $r['date']; ?></td>
          <td style="text-align:center"><?php echo $r['count']; ?>
          <span class="tooltip">
          <?php
		  foreach($bill_no as $data)
		  {
			  ?>
              <a href="bill_detail.php?billno=<?php echo $data ?>"><?php echo $data ?></a><br>
              <?php
		  }
		  ?>
          </span>
          </td>
          <td style="text-align:right"><?php echo $mysql->currency($r['bill_amount']); ?></td>
        </tr>
        <?php
		$bill_amount += $r['bill_amount'];
		$count += $r['count'];
}
?>
        <tfoot>
          <tr>
          	<th style="text-align:center">No of Days : <?php echo $sno; ?></th>
            <th style="text-align:right">Net</th>
            <th style="text-align:center"><?php echo $count ?></th>
            <th style="text-align:right"><?php echo $mysql->currency($bill_amount) ?></th>
          </tr>
        </tfoot>
      </table>
    </div>
    <?php include"footer.php"; ?>