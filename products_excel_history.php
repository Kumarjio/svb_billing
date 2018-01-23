<?php include"header.php"; ?>
<div class="box">
<div class="box-head"><h3>Product Excel Upload History</h3></div>
<?php
$sql = "select 
			e.id,
			e.file_name,
			e.`drop`,
			date_format(e.it,'%d-%m-%Y<br>%r')'date' 
		from 
			product_excel_upload as e
		where
			e.`is`=1";
$rst = $mysql->execute($sql);
?>
<center>
<table class="table table-bordered table-striped" style="width:150px; overflow:visible">
<thead><th>Sno</th><th>Date</th><th>File Name</th><th style="white-space:nowrap">View Deleted Old Products</th><th>Download</th></thead>
<tbody>
<?php
while($r = mysqli_fetch_array($rst[0]))
{
?>
	<tr>
    <td style="text-align:center"><?php echo ++$sno ?></td>
    <td style="white-space:nowrap"><?php echo $r['date']; ?></td>
    <td style="white-space:nowrap"><?php echo $r['file_name']; ?></td>
    <td style="white-space:nowrap"><?php 
		if($r['drop'])
		{
			$sql1 = "select count(*) from product where `is`='".$r['id']."'";
			$rst1 = $mysql->execute($sql1);
			$r1 = mysqli_fetch_array($rst1[0]);
			echo $r1[0]." ";
			?>
            Products are removed click here
            <a href="products_excel_removed.php?id=<?php echo $r['id'] ?>">click here</a> to view list
            <?php
		}
	?></td>
    <td><a href="uploads/product_excel/<?php echo $r['id']; ?>.xls">Download</a></td>
    </tr>
<?php
}
?>
</tbody>
</table>
</center>
</div>
<?php include"footer.php"; ?>