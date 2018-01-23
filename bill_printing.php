<?php
include"header.php";
include"classes/billing.php";
$sql = "select b.`lock`,b.date from bill as b where b.bill_no='".$_GET['bill_no']."';";
$rst = $mysql->execute($sql);
$r = mysqli_fetch_array($rst[0]);
if($r['lock'])
{
	if(strtotime($r['date']) < strtotime(date('2017-07-01'))){
		$billing->print_output($_GET['bill_no'],intval($_GET['dup']));
	}else{	
		$billing->print_gst_output($_GET['bill_no'],intval($_GET['dup']));
	}
	?>
	<link rel='stylesheet' type='text/css' href='css/bill_style.css' />
	<script type="text/javascript">
	window.print();
	window.close();
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