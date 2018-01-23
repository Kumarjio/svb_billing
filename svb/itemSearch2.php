<link href="css/search.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
var products = [];
</script>
<script type="text/javascript">
<?php
$sql = "select 
			s.id,
			s.pid,
			p.name,
			p.photo,
			s.unit_price,
			p.`type`,
			s.available,
			s.vat,
			date_format(s.p_date,'%d-%m-%Y')'p_date'
		from 
			stock as s,
			product as p
		where 
			p.pid=s.pid
			and
			s.available>0
		order by
			s.pid
		;";
$rst = $mysql->execute($sql);
$products = array();
while($r = mysqli_fetch_array($rst[0]))
{
	if(!file_exists($r['photo']))
	{
		$r['photo'] = "img/icons/essen/32/shipping.png";
	}
	if($billing->getTaxMode()==0)
		$pvat = $r['vat'];
	else
		$pvat = 0.0;
	$billing->loadProduct($r['id'],$r['pid'],$r['name'],$r['price'],$r['type'],$r['available'],$r['p_date'],$pvat,$r['photo']);
	?>
    products.push({ id:"<?php echo $r['id'] ?>",pid:"<?php echo $r['pid'] ?>",photo:"<?php echo $r['photo'] ?>" , name:"<?php echo $r['name'] ?>" , amnt:"<?php echo $r['price'] ?>",type:"<?php echo $r['type'] ?>",available:"<?php echo $r['available'] ?>",date:"<?php echo $r['p_date'] ?>",vat:"<?php echo $r['vat'] ?>" });
  
  <?php
}
?>
</script>
<div class="searchBox"></div>
