<?php 
include"header.php";
?>
<?php
if(isset($_POST['convert'])){
	$del = $_POST['from'];
	$del++;
	$sql = "update 
				bill
			set
				bill_no='".$_POST['to']."' ,
				`date` = '".$_POST['date']."',
				`tmp`=100,
				`lock`=1
			where
				bill_no='".$_POST['from']."';
			update 
				bill_order
			set
				bill_no='".$_POST['to']."' 
			where
				bill_no='".$_POST['from']."';
			DELETE FROM `bill_que` WHERE bill_no= '".($del)."';
			UPDATE `bill_que` SET `ok`=0 WHERE  bill_no='".($_POST['from'])."';
			";
	$mysql->execute($sql);
}
?>
<form action="#" method="post">
<table class="table" style="width:350px;">
<thead>
	<th>From</th>
	<th><input type="text" name="from" class="input-small" ></th>
    <th>To</th>
    <th><input type="text" name="to" class="input-small" ></th>
    <th>Bill Date</th>
    <th><input type="text" name="date" class="datepick input-small" ></th>
    <th><input type="submit" name="convert" value="Convert" class="btn btn-blue4" ></th>
</thead>
</table>
</form>
<div style="width:100%; overflow:auto">
<table class="table table-bordered table-striped">
<thead>
<th>Sno</th>
<th>Bill no</th>
<th>Customer</th>
<th>Discount</th>
<th>Bill Date</th><?php
for($i=1;$i<=10;$i++){ ?>
<th>Item<?php echo $i ?> ID</th>
<th>Item<?php echo $i ?> Name</th>
<th>Item<?php echo $i ?> Price</th>
<th>Item<?php echo $i ?> Qty</th>
<th>Item<?php echo $i ?> Total</th><?php
}?>
<th>Total</th>
<th>Roundup</th>
<th>Vat</th>
<th>Vat Total</th>
<th>Packing Chrg</th>
<th>Grand Tot</th>
<th>Balance</th>
<th>Tin</th>
<th>Cst</th>
<th>Cst Date</th>
<th>Paid</th>
<th>Detail</th>
<th>No.of Packings</th>
<th>Words</th>
<th>Vat</th><?php
for($i=1;$i<=10;$i++){ ?>
<th>Dis<?php echo $i ?></th><?php
}?>
</thead>
<?php
$dwt = array('M.PALANISWAMY-TRIUHY','SWAMY AUTO PARTS','ASHOK  TRANSPROT');
$sql = "select 
			*
		from
			movedb.bill as b
		where	
			b.billdate>='2013-04-01' and
			b.billno not in(
				select bill_no from billing.bill where tmp!=0
			) and b.customername not in ('".implode("','",$dwt)."') ";
$rst = $mysql->execute($sql);
while($r = mysqli_fetch_array($rst[0])){ ?>
	<tr>
    <td><?php echo ++$sno ?></td><?php 
	$tmp = 1;
	foreach($r as $r1){ 
		if((++$tmp)%2==0) continue;?>
    	<td><?php echo $r1; ?></td><?php
	} ?>
    </tr><?php
}
?>
</table></div>
<?php 
include"footer.php";
?>