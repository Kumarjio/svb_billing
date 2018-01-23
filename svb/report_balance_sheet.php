<?php include"header.php"; ?>
    <div class="box">
      <div class="box-head">
        <h3>Balance Sheet Report</h3>
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
            <th>Opening</th>
            <th>Opening Time</th>
            <th>Closing</th>
            <th>Closing Time</th>
          </tr>
        </thead>
        <?php
$sql = "select
			date_format(s.date,'%d-%m-%Y')'date',
			s.opening,
			TIME_FORMAT(s.opening_time,'%r')'opening_time',
			s.closing,
			TIME_FORMAT(s.closing_time,'%r')'closing_time'
		from 
			shop_day_close as s
		where
			s.date between '".$from."' and '".$to."' 
			and
			s.`is`=1
			and
			s.close=1;";
$rst = $mysql->execute($sql);
$opening = 0;
$closing = 0;
while($r = mysqli_fetch_array($rst[0]))
{
	?>
        <tr>
          <td style="text-align:center"><?php echo ++$sno; ?></td>
          <td style="text-align:center"><?php echo $r['date']?></td>
          <td style="text-align:right"><?php echo $mysql->currency($r['opening']); ?></td>
          <td style="text-align:center"><?php echo $r['opening_time']; ?></td>
          <td style="text-align:right"><?php echo $mysql->currency($r['closing']); ?></td>
          <td style="text-align:center"><?php echo $r['closing_time']; ?></td>
        </tr>
        <?php
	$opening += $r['opening'];
	$closing += $r['closing'];
}
?>
        <tfoot>
          <tr>
            <th colspan="2" style="text-align:right">Net</th>
            <th style="text-align:right"><?php echo $mysql->currency($opening) ?></th>
            <th></th>
            <th style="text-align:right"><?php echo $mysql->currency($closing) ?></th>
            <th></th>
        </tfoot>
      </table>
    </div>
    <?php include"footer.php"; ?>