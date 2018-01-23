<?php 
include"classes/mysql.php";
$sql = "select					
			  sd.billing_src
		  from 
			  shop_detail as sd
		  ;";
$rst = $mysql->execute($sql);
$rst = mysqli_fetch_array($rst[0]);
$billing_src = $rst['billing_src'];
if($_REQUEST['cid']=='')
{
echo 0;
exit();
}
if($_POST['src']=='P')
	$billing_src = 1;
if(!$billing_src)
{
$sql = "select 
			s.id,
			c.discount_value
		from 
			stock as s,
			product as p,
			customer_product_discount as c
		where 
			p.id=s.pid
			and
			c.pid = p.id
			and
			c.cid = '".intval($_REQUEST['cid'])."'
			and
			s.id='".intval($_REQUEST['pid'])."'
		limit 1
		;";
}
else
{
$sql = "select 
			p.id,
			c.discount_value
		from 
			product as p,
			customer_product_discount as c
		where 
			p.id = '".intval($_REQUEST['pid'])."'
			and
			c.cid = '".intval($_REQUEST['cid'])."'
			and
			c.pid = p.id
		limit 1
		;";
}
$rst = $mysql->execute($sql);
$r = mysqli_fetch_array($rst[0]);
echo floatval($r['discount_value']);
?>
