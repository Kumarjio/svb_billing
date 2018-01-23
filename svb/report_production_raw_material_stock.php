<?php include"header.php"; ?>
    <div class="box" style="min-height:500px">
	    <form action="#" method="post">
      <div class="box-head"><h3>RAW MATERIAL STOCK</h3></div>
          </form>
      <center><?php
$sql = "select p.id,p.name,p.stock from production_raw_material as p where p.`is`=1 order by p.name";
$rst = $mysql->execute($sql);
$data = array();
while($r = mysqli_fetch_array($rst[0])){
	$data[$r['name']] = $r['stock'];
}
$sum = array();
?>
<table class="table table-bordered table-striped">
<thead>
	<tr>
    	<th>SNO</th>
    	<th>CATEGORY</th>
        <th style="width:100px">QTY</th>
    </tr>
</thead>
<tbody><?php
	foreach($data as $name=>$qty){   ?>
		<tr>
        	<td style="text-align:center"><?php echo ++$sno ?></td>
            <td><?php echo $name ?></td>
			<td style="text-align:center"><?php echo $qty ?></td>
		</tr><?php
	} ?>
</tbody>
</table>
      </center>
    </div>
    <?php include"footer.php"; ?>