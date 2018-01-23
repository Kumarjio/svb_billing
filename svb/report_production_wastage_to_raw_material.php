<?php include"header.php"; ?>
<?php
if(isset($_POST['from'])){
	$from = $_POST['from'];
	$to   = $_POST['to'];
}else{
	$from = date('Y-m-d');
	$to   = date('Y-m-d');
}
?>
    <div class="box" style="min-height:500px">
	    <form action="#" method="post">
      <div class="box-head">
        <h3>WASTAGE TO PRODUCTION</h3> 
        &nbsp;&nbsp;
        <input type="text" name="from" class="input-small datepick" value="<?php echo $from ?>" onChange="$(this).closest('form').submit()" />
        TO
        <input type="text" name="to" class="input-small datepick" value="<?php echo $to ?>" onChange="$(this).closest('form').submit()" />
      </div>
          </form>
      <center><?php
$sql=  "select 
			w.date,
			r1.name'from',
			r2.name'to',
			w.qty 
		from 
			production_wastage_to_production as w,
			production_raw_material as r1,
			production_raw_material as r2
		where
			w.category=r1.id and w.production_category=r2.id  and w.date  between '".$from."' and '".$to."'
		order by w.date";
$rst = $mysql->execute($sql);
$data = array();
while($r = mysqli_fetch_array($rst[0])){
	$data[] = $r;
}
?>
<table class="table table-bordered table-striped">
<thead>
	<tr>
    	<th>DATE</th>
        <th>FROM</th>
        <th>TO</th>
        <th>QTY</th>
    </tr>
</thead>
    <tr><?php 
		foreach($data as $dat){ 
			echo '<td>'.$dat['date'].'</td>';
			echo '<td>'.$dat['from'].'</td>';
			echo '<td>'.$dat['to'].'</td>';
			echo '<td>'.$dat['qty'].'</td>';
		}?>
    </tr>
</table>
      </center>
    </div>
    <?php include"footer.php"; ?>