<?php
include"header.php";
?>
    <div class="box">
      <div class="box-head tabs">
        <h3>Stock Entry Search</h3>
      </div>
      <?php
$from = $to = date('Y-m-d');
if(isset($_POST['from']))
	$from = $_POST['from'];
if(isset($_POST['to']))
	$to = $_POST['to'];
?>
      <form action="#" method="post">
        <table class="table table-bordered table-striped search" align="center">
          <thead>
            <tr>
              <th>From</th>
              <th><input type="text" name="from" class="datepick"></th>
              <th>To</th>
              <th><input type="text" name="to" class="datepick"></th>
              <th><input type="submit" name="open" value="Submit" class="btn btn-blue4"></th>
            </tr>
          </thead>
        </table>
      </form>
      <br>
      <br>
      <table class="table table-bordered table-striped" align="center">
        <thead>
          <tr>
            <th>Sno</th>
            <th>Stock Entry No</th>
            <th>Date</th>
            <th>Tot Products</th>
            <th>Current Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
		$sql = "select * from purchase as bl where bl.date between '".$from."' and '".$to."'";
		$rst = $mysql->execute($sql);
		while($r = mysqli_fetch_array($rst[0]))
		{
		?>
          <tr>
            <td style="text-align:center"><?php echo ++$sno; ?></td>
            <td style="text-align:center"><a href="purchase_detail.php?billno=<?php echo $r['bill_no'] ?>"><?php echo $r['bill_no'] ?></a></td>
            <td style="text-align:center"><?php echo $r['date'] ?></td>
            <td style="text-align:center"><?php echo $r['tot_products'] ?></td>
            <td><?php if($r['lock']) echo 'Locked'; else echo 'Not Locked' ?>
              <br>
              <?php if($r['trans_close']) echo 'Payment Completed'; else echo 'Payment Pending' ?></td>
          </tr>
          <?php
		}
		?>
        </tbody>
      </table>
    </div>
    <?php
include"footer.php";
?>