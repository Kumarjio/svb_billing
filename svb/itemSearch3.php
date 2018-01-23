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
			p.mrp,
			p.`type`,
			p.pur_price
		from 
			store_products as p
		where
			p.`is`=1;
		;";
$rst = $mysql->execute($sql);
$products = array();
while($r = mysqli_fetch_array($rst[0]))
{
	$name = $r['name'];
	$price = $r['pur_price'];
	if(!file_exists($r['photo']))
	{
		$r['photo'] = "img/icons/essen/32/shipping.png";
	}
	$pvat = 0.0;
	$billing->loadProduct($r['id'],$r['pd_id'],$r['pid'],$name,$price,$r['type'],$r['available'],$r['p_date'],$pvat,$r['photo']);
	?>
    products.push({ id:"<?php echo $r['id'] ?>",pid:"<?php echo $r['pid'] ?>",photo:"<?php echo $r['photo'] ?>" , name:"<?php echo $name ?>" , amnt:"<?php echo $price ?>",type:"<?php echo $r['type'] ?>",available:"<?php echo $r['available'] ?>",date:"<?php echo $r['p_date'] ?>",vat:"<?php echo $pvat ?>" });  
  <?php
}
?>
</script>
<div class="searchBox"></div>
