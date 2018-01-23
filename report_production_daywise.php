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
    <div class="box" style="min-height:500px; width:100%; overflow:scroll">
	    <form action="#" method="post">
      <div class="box-head">
        <h3>PRODUCTION - DAYWISE</h3> 
        &nbsp;&nbsp;
        <input type="text" name="from" class="input-small datepick" value="<?php echo $from ?>" onChange="$(this).closest('form').submit()" />
        TO
        <input type="text" name="to" class="input-small datepick" value="<?php echo $to ?>" onChange="$(this).closest('form').submit()" />
      </div>
          </form>
      <center><?php
$sql = "select
			s.date,
			pf.name'staff',
			p.name,
			s.damage,
			if(c.name is null,pr.pid,c.name)'category',
			s.qty
		from
			".$_SESSION['production_table']." as s 
			join production_process as p
				on s.`process`=p.id and s.damage=0 and s.date between '".$from."' and '".$to."'
			join profile as pf 
				on s.staff=pf.id
			left join production_category as c
				on s.category=c.id
			left join product as pr
				on s.pid=pr.id
		order by
			pf.name,
			p.`type`,
			p.`order`,
			c.name,
			pr.name";
$rst = $mysql->execute($sql);
$process 	 = array();
$processData = array();
while($r = mysqli_fetch_array($rst[0])){
	$processData[$r['staff']][$r['name']][$r['category']] += $r['qty'];
	$process[$r['name']][$r['category']] = $r['category'];
}
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
$rst = $mysql->execute($sql);
$materials 	 = array();
$distribution = array();
while($r = mysqli_fetch_array($rst[0])){
	$distribution[$r['staff']][$r['name']] += $r['qty'];
	$materials[$r['name']] = $r['name'];
}
$sum  = array();
$msum = array()
?>
<table class="table table-bordered table-striped">
<thead>
	<tr>
    	<th rowspan="2">STAFF/PRODUCTION</th><?php
		if(count($materials)>0){ ?>
		<th colspan="<?php echo count($materials) ?>">RAW MATERIALS</th><?php 
		}
		foreach($process as $pname=>$data){ 
			echo '<th colspan="'.count($data).'">'.$pname.'</th>';
		}?>
    </tr>
    <tr><?php 
		foreach($materials as $mname){ 
				echo '<th>'.$mname.'</th>';
		}
		foreach($process as $pname=>$data){ 
			foreach($data as $category){	
				echo '<th>'.$category.'</th>';
			}
		}?>
    </tr>
</thead>
<tbody><?php
	foreach($processData as $staff=>$pdata){  ?>
	<tr><td><?php echo $staff ?></td><?php 
		foreach($materials as $mname){ 
				echo '<td style="text-align:center">'.$distribution[$staff][$mname].'</td>';
				$msum[$mname] += $distribution[$staff][$mname];
		}
		foreach($process as $pname=>$data){ 
			foreach($data as $category){	
				echo '<td style="text-align:center">'.intval($pdata[$pname][$category]).'</td>';
				$sum[$pname][$category] += $pdata[$pname][$category];
			}
		}?>
    </tr><?php
	} ?>
</tbody>
<tfoot>
	<tr><th>Total</th><?php 
		foreach($materials as $mname){ 
				echo '<th style="text-align:center">'.$msum[$mname].'</th>';
		}
		foreach($process as $pname=>$data){ 
			foreach($data as $category){	
				echo '<th style="text-align:center">'.$sum[$pname][$category].'</th>';
			}
		}?>
    </tr>
</tfoot>
</table>
      </center>
    </div>
    <?php include"footer.php"; ?>