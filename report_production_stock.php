<?php include"header.php"; ?>
    <div class="box" style="min-height:500px">
	    <form action="#" method="post">
      <div class="box-head"><h3>PRODUCTION - STOCK</h3></div>
          </form>
      <center><?php
$sql = "select 
			p.name,
			if(c.name is null,p1.pid,c.name)'category',
			pc.stock		
		from 
			production_process as p
			join production_process_category as pc
				on p.id=pc.`process`
			left join production_category as c 
				on p.`type`=0 and c.id=pc.category
			left join product as p1
				on p.`type`=1 and p1.id=pc.pid
		order by
			p.`type`,
			p.`order`,c.name,p1.pid";
$rst = $mysql->execute($sql);
$process 	 = array();
$category 	 = array();
$processData = array();
while($r = mysqli_fetch_array($rst[0])){
	$processData[$r['name']][$r['category']] = $r['stock'];
	$process[$r['name']][] = $r['category'];
	$category[$r['category']] = $r['category'];
}
$sum = array();
?>
<table class="table table-bordered table-striped">
<thead>
	<tr>
    	<th>SNO</th>
    	<th>CATEGORY/PROCESS</th><?php 
		foreach($process as $pname=>$tmp){ 
			echo '<th>'.$pname.'</th>';
		}?>
    </tr>
</thead>
<tbody><?php
	foreach($category as $cat){   ?>
		<tr>
        	<td style="text-align:center"><?php echo ++$sno ?></td>
			<td><?php echo $cat ?></td><?php
			foreach($process as $pname=>$tmp){
				echo '<td style="text-align:center">'.$processData[$pname][$cat].'</td>';
			}
			?>
		</tr><?php
	} ?>
</tbody>
</table>
      </center>
    </div>
    <?php include"footer.php"; ?>