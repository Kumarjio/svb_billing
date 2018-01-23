<?php include"header.php"; ?>
    <div class="box">
      <div class="box-head">
        <h3>Stock Details</h3>
      </div>
	  <?php
		$from 	= date('Y-m-d');
		$to 	= date('Y-m-d');
		if(isset($_POST['from'])){
			$from 	= $_POST['from'];
			$to 	= $_POST['to'];
		}
	  ?>
      <form action="#" method="post">
        <table class="table table-bordered table-striped" style="width:5px; overflow:visible; white-space:nowrap" align="center" cellpadding="10" cellspacing="10">
          <thead>
            <tr style="vertical-align:middle">
              <th><input type="text" name="from" class="datepick input-small" value="<?php echo $from ?>" /></th>
			  <th>To</th>
			  <th><input type="text" name="to" class="datepick input-small" value="<?php echo $to ?>" /></th>
              <th>Filter : </th>
              <th><?php
              $sql= "select 
						p.id,
						p.pid,
						p.name 
					from 
						product as p 
					where 
						p.`is`=1";
			  $rst = $mysql->execute($sql);
			  ?>
              <select name="products[]" multiple class="cho">
              <?php
			  while($r = mysqli_fetch_array($rst[0]))
			  {
				  ?><option value="<?php echo $r['id'] ?>"><?php echo $r['pid'] ?> <?php echo $r['name'] ?> </option>
                  <?php
			  }
			  ?>
              </select></th>
              <th><button type="submit" name="filter" class="btn btn-blue4"><i class="icon-globe icon-white"></i> Filter</button></th>
            </tr>
          </thead>
        </table>
      </form>
      <hr><?php
	  $sql = "select 
					p.id,
					p.pid,
					p.name,
					p.photo,
					p.`type`,
					p.available,
					d.date,
					d.stock'daycloseStock',
					p.`type`'stype' 
				from 
					product as p
					left join product_daywise as d on d.pid=p.id and d.date between '".$from."' and '".$to."'
				where
					p.`is`=1
				";
		if(count($_POST['products'])>0)
		{
			$sql .= " and p.id IN('".implode("','",$_POST['products'])."') ";
		}
		$sql .= " order by d.date;";
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
		$date = array();
		while($r = mysqli_fetch_array($rst[0]))
		{
			$date[$r['date']] = $r['date'];
			$stock[$r['id']]['name'] = $r['name'];
			$stock[$r['id']]['qty'] += $r['available']/$type[$r['stype']][$r['type']];
			if($r['daycloseStock']!='')
				$stock[$r['id']][$r['date']]['dqty'] += $r['daycloseStock']/$type[$r['stype']][$r['type']];
			$stock[$r['id']]['pid'] = $r['pid'];
			$stock[$r['id']]['type'] = $r['type'];
			$stock[$r['id']]['photo'] = $r['photo'];
		}
	  ?>
      <table class="table table-striped table-bordered" style="width:250px; overflow:visible" align="center">
        <thead>
        <th>Sno</th>
        <th style="white-space:nowrap">Code</th>
        <th style="white-space:nowrap">Product</th>
        <th style="white-space:nowrap">Current Stock</th><?php
		foreach($date as $dat){?>
			<th style="white-space:nowrap"><?php echo $dat ?></th><?php
		}
		?>
        </thead>
        <?php
		
		foreach($stock as $stock1)
		{
		?>
        <tr>
        <td><?php echo ++$sno ?></td>
        <td style="white-space:nowrap"><?php echo $stock1['pid'] ?></td>
        
        <td style="white-space:nowrap"><?php echo $stock1['name'] ?></td>
        <td style="white-space:nowrap; text-align:right"><?php echo $stock1['qty'] ?></td><?php
		foreach($date as $dat){?>
			<td><?php echo $stock1[$dat]['dqty'] ?></td><?php
		}
		?>
        </tr>
        <?php
        }
		?>
      </table>
    </div>
    <?php include"footer.php"; ?>