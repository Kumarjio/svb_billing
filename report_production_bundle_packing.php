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
<center>
    <div class="box" style="min-height:500px; width:500px">
	    <form action="#" method="post">
      <div class="box-head">
        <h3>BUNDLE PACKING</h3> 
        &nbsp;&nbsp;
        <input type="text" name="from" class="input-small datepick" value="<?php echo $from ?>" onChange="$(this).closest('form').submit()" />
        TO
        <input type="text" name="to" class="input-small datepick" value="<?php echo $to ?>" onChange="$(this).closest('form').submit()" />
      </div>
          </form>
      <center><?php
$sql = "select
			d.date,
			pf.name'staff',
			m.name,
			d.qty
		from
			production_raw_material_distribution as d
			join production_raw_material as m
				on d.material=m.id and d.damage=0 and d.date  between '".$from."' and '".$to."'
			join profile as pf 
				on d.staff=pf.id
		order by
			pf.name,
			m.name";
$sql = "select 
			b.date,
			p.name,
			b.qty 
		from 
			production_bundle_packing as b,
			profile as p
		where
			b.staff=p.id  and b.date  between '".$from."' and '".$to."'
		order by
			b.date,p.name";
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
        <th>STAFF</th>
        <th>QTY</th>
    </tr>
</thead>
    <tr><?php 
		foreach($data as $dat){ 
			echo '<td>'.$dat['date'].'</td>';
			echo '<td>'.$dat['name'].'</td>';
			echo '<td>'.$dat['qty'].'</td>';
		}?>
    </tr>
</table>
      </center>
    </div>
</center>
    <?php include"footer.php"; ?>