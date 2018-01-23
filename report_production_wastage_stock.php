<?php include"header.php"; ?>
    <div class="box" style="min-height:500px">
	    <form action="#" method="post">
      <div class="box-head"><h3>WATAGE STOCK</h3></div>
          </form>
      <center><?php
$sql = "select p.id,p.name,w.stock from production_wastage as w,production_raw_material as p where w.category=p.id and p.`is`=1";
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