<?php include"header.php"; ?>
    <div class="box">
      <div class="box-head">
        <h3>Stock Details</h3>
      </div>
	  <?php
		$from 	= date('Y-m-d');
		if(isset($_POST['from'])){
			$from 	= $_POST['from'];
		}
	  ?>
      <form action="#" method="post">
        <table class="table table-bordered table-striped" style="width:5px; overflow:visible; white-space:nowrap" align="center" cellpadding="10" cellspacing="10">
          <thead>
            <tr style="vertical-align:middle">
              <th><input type="text" name="from" class="datepick input-small" value="<?php echo $from ?>" /></th>
              <th><button type="submit" name="filter" class="btn btn-blue4">Open</button></th>
            </tr>
          </thead>
        </table>
      </form>
      <hr><?php
	  if(isset($_POST['update'])){
		  $sql = "UPDATE `production_process_stock` SET `damage`='2' WHERE  `id`=".$_POST['stockid'].";
				  UPDATE `production_process_category` SET `stock`=`stock`-".$_POST['qty']." where `process` = '4' and pid=".$_POST['pid'].";
				  UPDATE `production_process_category` SET `stock`=`stock`+".$_POST['qty']." where `process` = '3' and category=".$_POST['cid'].";";
		  $mysql->execute($sql);
	  }
	  $sql = "SELECT 
					s.date,s.id'stockid',p.id'productidid',c.id'categoryid',
					p.pid,p.name'pname',c.name'cname',s.qty
				FROM 
					`production_process_stock` as s,
					production_category as c,
					production_process as pr,
					product as p
				WHERE 
					`process` = '4' and s.`process`=pr.id 
					and s.preCat=c.id and s.`is`=1 and s.damage=0
					and p.id=s.pid and s.date='".$from."'
				ORDER BY s.`id` DESC;";
	  $rst = $mysql->execute($sql);
	  ?>
      <table class="table table-striped table-bordered" style="width:250px; overflow:visible" align="center">
        <thead>
        <th>Sno</th>
        <th style="white-space:nowrap">Category</th>
        <th style="white-space:nowrap">Product</th>
		<th style="white-space:nowrap">Qty</th>
		<th style="white-space:nowrap">Return Qty</th>
        </thead><?php
        while($r = mysqli_fetch_array($rst[0])){?>
		<tr>
			<td><?php echo ++$sno ?></td>
			<td style="white-space:nowrap"><?php echo $r['cname'] ?></td>
			<td style="white-space:nowrap"><?php echo $r['pid']." - ".$r['pname'] ?></td>
			<td style="white-space:nowrap"><?php echo $r['qty'] ?></td>
			<td style="white-space:nowrap">
			<form action="#" method="post">
				<input type="hidden" name="stockid" value="<?php echo $r['stockid'] ?>">
				<input type="hidden" name="pid" value="<?php echo $r['productidid'] ?>">
				<input type="hidden" name="cid" value="<?php echo $r['categoryid'] ?>">
				<input type="hidden" name="qty" class="form-control input-small" value="<?php echo $r['qty'] ?>">
				<input type="submit" name="update" value="Update" class="btn-blue4 btn" />
			</form>
			
			</td>
		</tr>
		<?php
		}
		
		?>
      </table>
    </div>
    <?php include"footer.php"; ?>