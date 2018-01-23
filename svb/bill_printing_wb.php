<?php
include"header.php";
include"classes/billing_wb.php";
$sql = "select b.`lock` from bill as b where b.bill_no='".$_GET['bill_no']."';";
$rst = $mysqlwb->execute($sql);
$r = mysqli_fetch_array($rst[0]);
if($r['lock'])
{
	$billing->print_output($_GET['bill_no'],intval($_GET['dup']));?>
	<link rel='stylesheet' type='text/css' href='css/bill_style_wb.css' />
	<script type="text/javascript">
	window.print();
//	window.close();
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
include"footer.php";
?>