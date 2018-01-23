<?php include"header.php"; ?>
    <div class="box">
      <div class="box-head">
        <h3>Current Day Stock Details</h3>
      </div>
	  <?php
	  if(isset($_POST['close'])){
		  $sql = "delete from product_daywise where `date`=date(now());
			      insert into product_daywise(`date`,pid,stock) (select date(now()), p.id,p.available from product as p)";
		  $mysql->execute($sql);
			?>
				<center>
					<div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
						Updated Successfully
					</div>
				</center><?php	
	  }
	  $sql = "select * from product_daywise as d where d.date=date(now()) limit 1";
	  $rst = $mysql->execute($sql);
	  ?>
      <form action="#" method="post">
        <center>
			<button type="submit" name="close" class="btn btn-blue4">Close Day</button>
			<?php if(mysqli_num_rows($rst[0]) >0 ){ ?>
				<br><span class="label">* Already Day Closed</span><?php
			} ?>
		</center>
      </form>
      <hr>
      <table class="table table-striped table-bordered" style="width:250px; overflow:visible" align="center">
        <thead>
        <th>Sno</th>
        <th>Code</th>
        
        <th>Product</th>
        <th>Stock</th>
        <th>Type</th>
        </thead>
        <?php
		$sql = "select 
					p.id,
					p.pid,
					p.name,
					p.photo,
					p.`type`,
					p.available,
					p.`type`'stype' 
				from 
					product as p
				where
					p.`is`=1;";
		$sql .= "select c.`group`,c.name,c.`using` from conversion as c where c.`is`=1 and c.`using`=1 order by c.`group`;
				select c.`group`,c.name,c.val,c.`using` from conversion as c where c.`is`=1 order by c.`group`,c.`grade`;";
		$rst = $mysql->execute($sql);
		$grade = array();
		$type = array();
		$stock = array();
		while($r = mysqli_fetch_array($rst[1]))
		{
			$grade[$r['group']] = $r['name'];
		}
		while($r = mysqli_fetch_array($rst[2]))
		{
			$type[$r['name']][$grade[$r['group']]] = $r['val'];
		}
		while($r = mysqli_fetch_array($rst[0]))
		{
			$stock[$r['id']]['name'] = $r['name'];
			$stock[$r['id']]['qty'] += $r['available']/$type[$r['stype']][$r['type']];
			$stock[$r['id']]['pid'] = $r['pid'];
			$stock[$r['id']]['type'] = $r['type'];
			$stock[$r['id']]['photo'] = $r['photo'];
		}
		foreach($stock as $stock1)
		{
		?>
        <tr>
        <td><?php echo ++$sno ?></td>
        <td style="white-space:nowrap"><?php echo $stock1['pid'] ?></td>
        
        <td style="white-space:nowrap"><?php echo $stock1['name'] ?></td>
        <td style="white-space:nowrap; text-align:right"><?php echo $stock1['qty'] ?></td>
        <td style="white-space:nowrap"><?php echo $stock1['type'] ?></td>
        </tr>
        <?php
        }
		?>
      </table>
    </div>
    <?php include"footer.php"; ?>