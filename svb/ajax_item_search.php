<?php 
include"classes/mysql.php";
$sql = "select
					sd.tax,
					sd.tax_mode,
					sd.round_off,
					sd.billing_src
				from 
					shop_detail as sd
				;";
$rst = $mysql->execute($sql);
$rst = mysqli_fetch_array($rst[0]);
$billing_src = $rst['billing_src'];
$tax_mode = $rst['tax_mode'];
if($_REQUEST['value']=='')
return 'Please Type Value to search...';
if($_POST['src']=='P')
	$billing_src = 1;
$value = htmlspecialchars($_REQUEST['value']);
if(!$billing_src)
{
$sql = "select 
			p.id,
			p.id'pd_id',
			p.pid,
			p.name,
			p.name1,
			p.name2,
			p.name_default,
			p.photo,
			p.price'unit_price',
			p.price,
			p.price_src,
			p.`type`,
			p.available,
			p.vat,
			date_format(s.p_date,'%d-%m-%Y')'p_date'
		from 
			product as p
		where
			(
			p.pid LIKE '%".$value."%'
			or
			p.name LIKE '%".$value."%'
			or
			p.name1 LIKE '%".$value."%'
			or
			p.name2 LIKE '%".$value."%'
			)
			and
			p.available>0
		order by
			p.pid
		;";
}
else
{
$sql = "select 
			p.id,
			p.id'pd_id',
			p.pid,
			p.name,
			p.name1,
			p.name2,
			p.name_default,
			p.photo,
			p.price'unit_price',
			p.price,
			p.price_src,
			p.available,
			p.vat,
			p.`type`,
			date_format(NOW(),'%d-%m-%Y')'p_date'
		from 
			product as p
		where 
			p.`is`=1
			and
			(
			p.pid LIKE '%".$value."%'
			or
			p.name LIKE '%".$value."%'
			or
			p.name1 LIKE '%".$value."%'
			or
			p.name2 LIKE '%".$value."%'
			)			
		order by
			p.pid
		;";
}
$rst = $mysql->execute($sql);
$products = array();
?>
<table border="0" cellspacing="5" cellpadding="3" width="100%" ><thead><th>PID</th><th>Name</th><th align="right">Available</th><th align="right">Price</th></thead>
<?php
$avail = array();
while($r = mysqli_fetch_array($rst[0]))
{
	if($_POST['src']=='P')
	{
	
		$avail[$r['pd_id']] += $r['available'];
	
	}
	if($r['name_default']==0)
		$name = $r['name'];
	else if($r['name_default']==1)
		$name = $r['name1'];
	else
		$name = $r['name2'];
	if($r['price_src']==0)
		$price = $r['unit_price'];
	else
		$price = $r['price'];
	if(!file_exists($r['photo']))
	{
		$r['photo'] = "img/icons/essen/32/shipping.png";
	}
	if($tax_mode==0)
		$pvat = $r['vat'];
	else
		$pvat = 0.0;
	if($_POST['src']=='P')
	{if(isset($avail[$r['pd_id']])){
		  $r['available'] = $avail[$r['pd_id']];
	  }
	  else{
		  $r['available'] = 'No Stock';
	  }
	}
	?>	
     <tbody class="searchResult"  id="sr<?php echo ++$count ?>">  <input type="hidden" class="searchResultId" value="<?php echo $r['id'] ?>" />  <tr>      <td align="center"><?php echo $r['pid'] ?></td> <td style="word-break:break-all; width:100px;"><?php echo htmlspecialchars_decode(addslashes($name)) ?></td><td align="right"><?php echo $r['available'] ?></td>      <td align="right"><?php echo $price ?></td>    </tr></tbody>
  <?php
}
?>
</table>
