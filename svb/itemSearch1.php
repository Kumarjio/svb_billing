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
			p.price,
			p.`type`,
			p.vat,
			p.par_price
		from 
			product as p
		where
			p.`is`=1;
		;";
$rst = $mysql->execute($sql);
$products = array();
while($r = mysqli_fetch_array($rst[0]))
{
	$products[$r['id']]['name']=$r['name'];
	$products[$r['id']]['unit_price']=$r['price'];
	$products[$r['id']]['type']=$r['type'];
	$products[$r['id']]['vat']=$r['vat'];
	$products[$r['id']]['par_price']=$r['par_price'];
	$products[$r['id']]['pid']=$r['pid'];
	$products[$r['id']]['p_date']='';
	$products[$r['id']]['available']=1;
	?>
    products.push({ id:"<?php echo $r['id'] ?>" , name:"<?php echo $r['name'] ?>" , amnt:"<?php echo $r['price'] ?>",type:"<?php echo $r['type'] ?>",vat:"<?php echo $r['vat'] ?>",par_price:"<?php echo $r['par_price'] ?>",pid:"<?php echo $r['pid'] ?>",available:"<?php echo '1'; ?>",date:"<?php echo ''; ?>" });
  
  <?php
}
?>
</script>
<div class="searchBox"></div>
