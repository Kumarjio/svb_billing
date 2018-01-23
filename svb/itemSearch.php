<script type="text/javascript">
var products = [];
</script>
<script type="text/javascript">
<?php
if(!$billing->getBillingMode())
{
$sql = "select 
			s.id,
			p.id'pd_id',
			p.pid,
			p.name,
			p.name1,
			p.name2,
			p.name_default,
			p.photo,
			s.unit_price,
			p.price,
			p.price_src,
			p.`type`,
			s.available,
			s.vat,
			date_format(s.p_date,'%d-%m-%Y')'p_date'
		from 
			stock as s,
			product as p
		where 
			p.id=s.pid
			and
			s.available>0
		order by
			s.pid
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
		order by
			p.pid
		;";
}
$rst = $mysql->execute($sql);
$products = array();
while($r = mysqli_fetch_array($rst[0]))
{
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
	if($billing->getTaxMode()==0)
		$pvat = $r['vat'];
	else
		$pvat = 0.0;
	$billing->loadProduct($r['id'],$r['pd_id'],$r['pid'],$name,$price,$r['type'],$r['available'],$r['p_date'],$pvat,$r['photo']);
	?>	
    products.push({ id:"<?php echo $r['id'] ?>",pid:"<?php echo $r['pid'] ?>",photo:"<?php echo $r['photo'] ?>",name:'<?php echo htmlspecialchars_decode(addslashes($name)) ?>',amnt:"<?php echo $price ?>",type:"<?php echo $r['type'] ?>",available:"<?php echo $r['available'] ?>",date:"<?php echo $r['p_date'] ?>",vat:"<?php echo $pvat ?>" });  
  <?php
}
?>
</script>
<link href="css/search.css" rel="stylesheet" type="text/css">
<div class="searchBox"></div>
