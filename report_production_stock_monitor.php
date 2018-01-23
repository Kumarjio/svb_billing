<?php include"header.php"; ?>
    <div class="box" style="min-height:500px">
      <div class="box-head">
        <h3>STOCK - MONITOR</h3>
      </div>
      <center><?php
		$sql = "select 
					pc.name	,
					if(c.name is null,p.name,c.name)'category',
					m.min,
					m.max,
					if(s.stock<m.min,
						concat('-',s.stock),
						if(s.stock > m.max,
							concat('+',s.stock),
							0
						)
					)'stock'
				from 
					production_process as pc
					join production_stock_monitor as m
						on pc.id=m.`process`
					left join production_process_category as s
						on m.`process`=s.`process` and m.category=s.category and m.pid=s.pid
					left join production_category as c
						on m.category=c.id
					left join product as p
						on m.pid=p.id
				where
					s.stock<m.min or s.stock>m.max or s.stock is null
				order by
					pc.`type`,
					pc.`order`,
					c.name,
					p.name
					";
		$rst = $mysql->execute($sql);
		$category = array();
		$monitorDate = array();
		while($r = mysqli_fetch_array($rst[0])){
			$category[$r['category']] = $r['category'];
			$monitorDate[$r['name']][$r['category']] = $r;
		}
		?>
		<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th rowspan="2">PROCESS/CATEGORY</th><?php
				foreach($category as $cat){ ?>
				   <th colspan="3" style="text-align:center"><?php echo $cat ?></th><?php
				}?>
			</tr>
			<tr><?php
				foreach($category as $cat){ ?>
				   <th>MIN</th>
				   <th>MAX</th>
				   <th>STOCK</th><?php
				}?>
			</tr>
		</thead>
		<tbody><?php
		foreach($monitorDate as $process=>$data){?>
			<tr>
				<th><?php echo $process ?></th><?php
				foreach($category as $cat){ ?>
				   <td style="text-align:center"><?php echo $data[$cat]['min'] ?></td>
				   <td style="text-align:center"><?php echo $data[$cat]['max'] ?></td>
				   <td style="text-align:center"><?php echo $data[$cat]['stock'] ?></td><?php
				}?>
			</tr><?php
		}
		?>
		</tbody>
		</table>
		

      </center>
    </div>
<?php include"footer.php"; ?>