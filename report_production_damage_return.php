<?php include"header.php"; ?>
<?php
if(isset($_POST['from'])){
	$from = $_POST['from'];
	$to   = $_POST['to'];
}else{
	$from = date('Y-m-d');
	$to   = date('Y-m-d');
}
?>
    <div class="box" style="min-height:500px">
	    <form action="#" method="post">
      <div class="box-head">
        <h3>CUSTOMER DAMAGE RETURN</h3> 
        &nbsp;&nbsp;
        <input type="text" name="from" class="input-small datepick" value="<?php echo $from ?>" onChange="$(this).closest('form').submit()" />
        TO
        <input type="text" name="to" class="input-small datepick" value="<?php echo $to ?>" onChange="$(this).closest('form').submit()" />
      </div>
          </form>
      <center><?php
$sql = "select
			s.date,
			pf.name'staff',
			p.name,
			s.damage,
			if(c.name is null,pr.pid,c.name)'category',
			s.qty
		from
			production_process_stock as s 
			join production_process as p
				on s.`process`=p.id and s.damage=1 and s.date between '".$from."' and '".$to."'
			join profile as pf 
				on s.staff=pf.id
			left join production_category as c
				on s.category=c.id
			left join product as pr
				on s.pid=pr.id
		order by
			pf.name,
			p.`type`,
			p.`order`,
			c.name,
			pr.name";
$sql = "select 
			p.date,
			c.name'cname',
			pd.name,
			p.qty 
		from 
			production_damage as p,
			bill as b,
			customers as c,
			product as pd
		where
			p.bill_no=b.bill_no and p.pid=pd.id and b.customer_id=c.id and p.date between '".$from."' and '".$to."'";
$rst = $mysql->execute($sql);
?>
<table class="table table-bordered table-striped">
<thead>
	<tr>
    	<th>SNO</th><th>CUSTOMER</th><th>PRODUCT</th><th>QTY</th>
    </tr>
</thead>
<tbody><?php
	while($r = mysqli_fetch_array($rst[0])){?>
    	<tr>
        	<td style="text-align:center"><?php echo ++$sno ?></td>
            <td><?php echo $r['cname'] ?></td>
            <td><?php echo $r['name'] ?></td>
            <td style="text-align:center"><?php echo $r['qty'] ?></td>
        </tr><?php
	} ?>
</tbody>
</table>
      </center>
    </div>
    <?php include"footer.php"; ?>