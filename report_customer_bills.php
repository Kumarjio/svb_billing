<?php include"header.php"; ?>
<?php include"classes/get_name.php"; ?>
    <div class="box">
      <div class="box-head">
        <h3>Customer Report</h3>
      </div>
      <?php
$from = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-30,date('Y'),0));
$to = date('Y-m-d');
if(isset($_POST['from']))
	$from = $_POST['from'];
if(isset($_POST['to']))
	$to = $_POST['to'];
?>
      <form action="#" method="post" class="noprint">
        <table class="table table-bordered table-striped" style="width:5px; overflow:visible; white-space:nowrap" align="center" cellpadding="10" cellspacing="10">
          <thead>
            <tr style="vertical-align:middle">
              <th>Filter : </th>
              <th><input type="text" name="from" value="<?php echo $from ?>" class="input-small datepick"></th>
              <th> TO </th>
              <th><input type="text" name="to" value="<?php echo $to ?>" class="input-small datepick"></th>
              <th>Customer</th>
              <th>
              <select name="customer" class="cho">
              <?php
			  $sql = "select id,name from customers ";
			  $rst = $mysql->execute($sql);
			  $customer = array();
			  while($r = mysqli_fetch_array($rst[0]))
			  {
				  $customer[$r['id']] = $r['name'];
				  ?><option <?php if($_POST['customer']==$r['id']) echo 'selected' ?> value="<?php echo $r['id'] ?>"><?php echo $r['name'] ?></option><?php
			  }
			  ?>
              </select>
              </th>
              <th><button type="submit" name="filter" class="btn btn-blue4"><i class="icon-globe icon-white"></i> Filter</button></th>
            </tr>
          </thead>
        </table>
      </form>
      <hr class="noprint">
      <?php 
if(isset($_POST['filter'])){
	str_replace("-","/",$from);
	$tmp = explode("/",$from);
	$acc_from  = date('Y-m-d',mktime(0,0,0,$tmp[1],$tmp[2]-1,$tmp[0],0));
 	$pre_balance = $mysql->accountBalance($_POST['customer'],$acc_from);
	$sql = "select 
				b.bill_no,
				b.date,
				b.bill_amount			
			from 
				bill_orders as b,bill as bl
			where 
				bl.bill_no=b.bill_no and bl.cancel in(0) and
				b.date between '".$from."' and '".$to."' 
				and
				b.customer_id='".$_POST['customer']."'
			group by 
				b.bill_no
			order by
				b.date;";
	$sql .= "select
				p.date,
				p.bill_no,
				p.type,
				p.type_id,
				p.cur_balance,
				p.recieved-p.returned'amount'
			from
				bill_payment as p,bill as b 
			where 
				b.bill_no=p.bill_no and p.is=1 and 
				p.date between '".$from."' and '".$to."' 
				and
				b.customer_id='".$_POST['customer']."' and b.cancel=0
			order by
				p.date;";
	$sql .= "select id,name,address,phone from customers where id='".$_POST['customer']."'";
	if($_SESSION['production_table'] != "production_process_stock_wb")
		$rst = $mysql->execute($sql);
	else
		$rst = $mysqlwb->execute($sql);
	$discount=0;
	$actual_amount = 0;
	$bill_amount = 0;
	$tax_amount = 0;
	$charges_amount = 0;
	$round_off = 0;
	$customer = mysqli_fetch_array($rst[2]);
	$particular = array();
	$balance = 0;
	$date = array();
	$bills = array();
	while($r = mysqli_fetch_array($rst[0])){
		$bills[$r['bill_no']] = $r['bill_amount'];
		$date[$r['date']] = $r['date'];
		$particular[$r['date']]['name'][] = $r['bill_no'];
		$particular[$r['date']]['credit'][] = $r['bill_amount'];
		$particular[$r['date']]['debit'][] = 0;
		$particular[$r['date']]['type'][] = $r['type'];
		$particular[$r['date']]['chequee'][] = $chs['cheque_no'];
	}
/*	while($r = mysqli_fetch_array($rst[1])){
		if(isset($bills[$r['bill_no']]))
		$bills[$r['bill_no']] -= $r['amount'] ;
		$date[$r['date']] = $r['date'];
		$particular[$r['date']]['name'][] = $r['bill_no'];
		$particular[$r['date']]['credit'][] = 0;
		$particular[$r['date']]['debit'][] = $r['amount'];
		$particular[$r['date']]['type'][] = $r['type'];
		if($r['type']==1){
		$sql = "SELECT `recieved_date`,  `cheque_no`,  `amount`,  `bank_name`,  `branch` FROM `billing`.`chequee` WHERE `for`='".$r['bill_no']."' ;";
		$chrst = $mysql->execute($sql);
		if(mysqli_num_rows($chrst[0])>0){
			$chs = mysqli_fetch_array($chrst[0]);
			$particular[$r['date']]['chequee'][] = $chs['cheque_no'];
		}
		}else if($r['type']==2){
		$sql = "SELECT `recieved_date`,  `dd_no`,  `amount`,  `bank_name`,  `branch` FROM `billing`.`demand_draft` WHERE `for`='".$r['bill_no']."' ;";
		$chrst = $mysql->execute($sql);
		if(mysqli_num_rows($chrst[0])>0){
			$chs = mysqli_fetch_array($chrst[0]);
			$particular[$r['date']]['chequee'][] = $chs['dd_no'];
		}
		}
		
	}*/
	sort($date);
	$credit = 0;
	$debit = 0;
	?>
      <table width="100%" border="0" cellspacing="5" cellpadding="5">
        <tbody>
		  <tr><td colspan="4"><center><b><font size="3">SVB AUTO PRODUCTS</font><br><font size="2">Karur</font></b></center></td></tr>
          <tr>
          <th style="text-align:left" colspan="2"><?php 
			 if($customer[1]!='')
				echo $customer[1].","; 
			?></span><br />
              <span style="font-size:14px;"><?php 
			  	$add = explode(",",$customer[2]);
			  	echo end($add);
                    ?>
		</th>
		<th colspan="2" style="vertical-align:top; text-align:right">Date:<?php echo date('d-m-Y') ?></th>
          </tr>
          <tr style="height:50px;"><th colspan="7"><center>BILL STATEMENT : <?php echo $mysql->date_format($_POST['from']) ?> TO <?php echo $mysql->date_format($_POST['to']) ?></center></th></tr>
          <tr style="border-top:#000 dashed 1px;border-bottom:#000 dashed 1px;">
            <th>Sno</th>
	    <th>Date</th>
            <th>Bill No</th>
            <th style="text-align:right">Bill Amount</th>
          </tr>
        </tbody>
        <?php
        foreach($date as $dat1=>$dat)
        {
            foreach($particular[$dat]['name'] as $id=>$name){
            ?>
                <tr>
                  <td style="text-align:center"><?php echo ++$sno; ?></td>
                  <td style="text-align:center"><?php echo $mysql->date_format($dat); ?></td>
                  <td style="text-align:center"><a href="bill_detail.php?billno=<?php echo $name; ?>"><?php echo $name; ?></a></td>
                  <td style="text-align:right"><?php echo $mysql->currency($particular[$dat]['credit'][$id]);  $cre +=  $particular[$dat]['credit'][$id] ?></td>
                </tr>
                <?php
            }
        }
        ?>
      <tr  style="border-top:#000 dashed 1px;border-bottom:#000 dashed 1px;" >
            <th></th>
	    <th></th>
            <th>Total</th>
            <th style="text-align:right"><?php echo $mysql->currency($cre) ?></th>
          </tr>
      </table>
      <center>
		<button type="button" onclick="window.print()" class="btn btn-large btn-blue4 noprint" ><i class="icon-print icon-white"></i>&nbsp;&nbsp;Print</button>
	</center><?php
}
?>
</div>
    <?php include"footer.php"; ?>
