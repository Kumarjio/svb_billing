<?php
include"header.php";
?>
<?php 
$date = date('Y-m-d');
if(isset($_POST['date']))
{
	$date = $_POST['date'];
}
?>
<div class="box">
<div class="box-head"><h3>Income</h3></div>
<?php 
if(isset($_POST['update']))
{
	 $sql ="UPDATE `income` set `date`='".$_POST['date']."', `e_id`=".$_POST['exp'].", `person`='".$_POST['per']."', `amount`=".$_POST['amnt'].", 
			`reason`='".$_POST['rea']."' WHERE `id`=".$_POST['id'].";";
	$mysql->execute($sql);
	?>
    <div class="alert alert-block alert-success">
							  <a class="close" data-dismiss="alert" href="#">×</a>
							  <h4 class="alert-heading">Updated Successfully!</h4>
							</div>
    <?php
}
if(isset($_POST['delete']))
{
	 $sql ="UPDATE `income` set `is`=0 WHERE `id`=".$_POST['id'].";";
	$mysql->execute($sql);
	?>
    <div class="alert alert-block alert-success">
							  <a class="close" data-dismiss="alert" href="#">×</a>
							  <h4 class="alert-heading">Removed Successfully!</h4>
							</div>
    <?php
}
?>
<form action="#" method="post">
<center>
<strong>Choose Date :</strong> <input type="text" name="date" value="<?php echo $date ?>" class="datepick">&nbsp;&nbsp;&nbsp;<input type="submit" name="load" value="Open" class="btn btn-blue4">
</center>
</form>
<table align="center" cellpadding="5" cellspacing="5" class="table table-striped table-bordered">
<thead><th>Sno</th><th>Date</th><th>Income</th><th>Given To</th><th>Reason</th><th>Amount</th><th></th></thead>
<tbody>
<?php
$sql = "select 
			e.id,e.date,t.name,e.person,e.amount,e.reason 
		from 
			income as e,
			income_type as t
		where 
			 e.e_id=t.id and  e.`date` = '".$date."' and e.`is`=1";
$rst = $mysql->execute($sql);
$amnt = 0;
while($r = mysqli_fetch_array($rst[0]))
{
	?>
    <tr>
    <td style="text-align:center"><?php echo ++$cnt; ?></td>
    <td style="text-align:center"><?php echo $r['date']; ?></td>
    <td style="text-align:center"><?php echo $r['name']; ?></td>
    <td style="text-align:center"><?php echo $r['person']; ?></td>
    <td style="text-align:center"><?php echo $r['reason']; ?></td>
    <td style="text-align:right"><?php echo $mysql->currency($r['amount']); $amnt += $r['amount'] ?>&nbsp;&nbsp;</td>
    <td><form action="income_edit.php" method="post"><input type="hidden" name="id" value="<?php echo $r['id'] ?>">
    <input type="submit" name="edit" value="Edit" class="btn btn-blue4"></form></td>
    </tr>
    <?php
}
?>
</tbody>
<tfoot><th></th><th></th><th></th><th></th>
<th style="text-align:right">Net Amount</th><th style="text-align:right"><?php echo $mysql->currency($amnt); ?>&nbsp;&nbsp;&nbsp;</th>
<th></th></tfoot>
</table>
</div>
<?php
include"footer.php";
?>