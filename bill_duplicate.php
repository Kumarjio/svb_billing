<?php
include"header.php";
include"classes/billing.php";
?>
<form action="" method="post" class="noprint">
      <div class="box noprint">
        <div class="box-head"><h3>Bill Details</h3></div>
        <table align="center" border="0" class="noprint">
          <tr>
            <th>Bill No </th>
            <td><input type="text" name="billno" class="text"></td>
          </tr>
          <tr>
            <th></th>
            <th><input type="submit" name="open" value="Open" class="btn btn-blue4"></th>
          </tr>
        </table>
      </div>
    </form>
<div class="print_data">
<?php
if(isset($_POST['open']))
{
	$sql = "select b.`lock` from bill as b where b.bill_no='".$_POST['billno']."';";
	$rst = $mysql->execute($sql);
	$r = mysqli_fetch_array($rst[0]);
	if($r['lock'])
	{
		$billing->printBill($_POST['billno'],1);
	}
	else if(mysqli_num_rows($rst[0])==0)
	{
		?>
		<script type="text/javascript">
        alert('No Bill with This NO.');
        </script>
        <?php
	}
	else
	{
		?>
	<script type="text/javascript">
	alert('Bill Not Locked. Please Lock and Print');
	</script>
	<?php
	}
}
?>
</div>
<link rel='stylesheet' type='text/css' href='css/bill_style.css' />
<?php
include"footer.php";
?>