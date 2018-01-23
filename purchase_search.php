<link href="css/search.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
var products = [];
</script>
<script type="text/javascript">
<?php
$sql = "select 
			p.id,
			p.pid,
			p.name,
			p.photo,
			p.par_price,
			p.available,
			p.`type`,
			p.vat
		from 
			product as p
		where 
			p.`is`=1			
		order by
			p.pid
		;";

$rst = $mysql->execute($sql);
$products = array();
$avail = array();

while($r = mysqli_fetch_array($rst[0]))
{
//		$avail[$r['id']] += ;
	if(!file_exists($r['photo']))
	{
		$r['photo'] = "img/icons/essen/32/shipping.png";
	}
	if($billing->getTaxMode()==0)
		$pvat = $r['vat'];
	else
		$pvat = 0.0;
	$stock = 0;
	if(isset($r['available']))
	{
		$stock = $r['available'];
	}
	$billing->loadProduct($r['id'],$r['pid'],htmlspecialchars_decode($r['name']),$r['par_price'],$r['type'],$r['available'],$r['p_date'],$pvat,$r['photo'],$stock);
	?>
    products.push({ id:"<?php echo $r['id'] ?>",pid:"<?php echo $r['pid'] ?>",photo:"<?php echo $r['photo'] ?>" , name:'<?php 
	echo htmlspecialchars_decode(addslashes($r['name'])) ?>' , amnt:"<?php echo $r['par_price'] ?>",type:"<?php echo $r['type'] ?>",stock:"<?php echo $stock ?>",available:"<?php echo $r['available'] ?>",date:"<?php echo $r['p_date'] ?>",vat:"<?php echo $pvat ?>" });
  <?php
}
?>
</script>
<div class="searchBox"></div>
