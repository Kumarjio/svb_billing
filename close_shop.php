<?php include"header.php" ?>
    <?php
$date = date('Y-m-d');
?>
    <div class="box">
      <div class="box-head">
        <h3>Shop Closing</h3>
      </div>
      <?php
	  if(isset($_POST['close_shop']))
	  {
		  $date = $_POST['date'];
		  $sql = "select b.opening,date_format(b.opening_time,'%h:%i %p')'opening_time' from shop_day_close as b where b.open=1 and b.date='".$date."' and b.`is`=1 LIMIT 1;
				  select t.name,e.amount from income as e,income_type as t where t.id=e.e_id and e.`is`=1 and e.date='".$date."';
				  select t.name,e.amount from expences as e,expences_type as t where t.id=e.e_id and e.`is`=1 and e.date='".$date."';";
		  $sql .= "select b.bill_no from bill as b where b.date='".$date."' and b.`is`=1 order by b.bill_no;
				   select b.bill_no from purchase as b where b.date='".$date."' and b.`is`=1 order by b.bill_no;";
		  $sql .= "select p.bill_no,p.recieved-p.returned'amnt' from bill_payment as p where p.date='".$date."' and p.`is`=1 order by p.bill_no;
				   select p.bill_no,p.recieved-p.returned'amnt' from purchase_payment as p where p.date='".$date."' and p.`is`=1 order by p.bill_no;";
		  $rst = $mysql->execute($sql);
		  $opening = 0;
		  $allow=1;
		  $open_time = '';
		  if(mysqli_num_rows($rst[0])==0)
		  {
			  $allow = 0;
		  }
		  $r = mysqli_fetch_array($rst[0]);
		  $opening = $r['opening'];
		  $open_time = $r['opening_time'];
		  $expence = array();
		  $income = array();
		  while($r = mysqli_fetch_array($rst[1]))
		  {
			  $income['name'][] = $r['name'];
			  $income['amount'][] = $r['amount'];
		  }
		  while($r = mysqli_fetch_array($rst[2]))
		  {
			  $expence['name'][] = $r['name'];
			  $expence['amount'][] = $r['amount'];
		  }
		  
		  $bill = array();
		  $purchase = array();
		  while($r = mysqli_fetch_array($rst[3]))
		  {
			  $bill[] = $r['bill_no'];
		  }
		  while($r = mysqli_fetch_array($rst[4]))
		  {
			  $purchase[] = $r['bill_no'];
		  }
		  $bill_pay = array();
		  $par_pay = array();
		  while($r = mysqli_fetch_array($rst[5]))
		  {
			  $bill_pay['no'][] = $r['bill_no'];
			  $bill_pay['amount'][] = $r['amnt'];
		  }
		  while($r = mysqli_fetch_array($rst[6]))
		  {
			  $par_pay['no'][] = $r['bill_no'];
			  $par_pay['amount'][] = $r['amnt'];
		  }
		  $day_income = array_sum($bill_pay['amount'])+array_sum($income['amount']);
		  $day_expence =  array_sum($par_pay['amount'])+array_sum($expence['amount']);
	      $profit = $day_income+$opening;
		  $sql = "UPDATE 
		  			`shop_day_close` 
		  		  SET 
				  	`closing`='".$profit."',`close`=1, `closing_time`=time(NOW()), 
					`expences`='".$day_expence."', `income`='".$day_income."' 
				  WHERE  
				  	`date`='".$date."' LIMIT 1;";
		  foreach($_POST['cash'] as $id=>$cash)
		  {
			$note = $_POST['note'][$id];
			$sql .= "INSERT INTO `cash` (`from`,`date`, `amount`, `no`) VALUES (1,'".$date."', '".$note."', '".$cash."');";
		  }
		  $sql .= "INSERT INTO `stock_closing` 
						(`date`,`r_id`, `par_id`, `p_date`, `pid`, `par_price`, `type`, 
						 `qty`, `available`, `unit_price`, `actual_amount`, `bill_amount`, `vat`)
				   (select '".$date."',`id`, `par_id`, `p_date`, `pid`, `par_price`, `type`, 
						 `qty`, `available`, `unit_price`, `actual_amount`, `bill_amount`, `vat` 
				    from stock as s 
					where 
						s.`is`=1 and s.qty>0);";
		  $mysql->execute($sql);
		  ?>
          <center>
            <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
              <h3>Shop Closed Successfully</h3>
            </div>
          </center>
          <?php
	  }
	  ?>
      <style type="text/css">
.cash{
	text-align:right;
}
.tot{
	text-align:right
}
</style>
      <?php
				$sql = "select b.opening,date_format(b.opening_time,'%h:%i %p')'opening_time',`close`,date_format(b.closing_time,'%h:%i %p')'closing_time' from shop_day_close as b where b.open=1 and b.date='".$date."' and b.`is`=1 LIMIT 1;
						select t.name,e.amount from income as e,income_type as t where t.id=e.e_id and e.`is`=1 and e.date='".$date."';
						select t.name,e.amount from expences as e,expences_type as t where t.id=e.e_id and e.`is`=1 and e.date='".$date."';";
				$sql .= "select b.bill_no from bill as b where b.date='".$date."' and b.`is`=1 order by b.bill_no;
						select b.bill_no from purchase as b where b.date='".$date."' and b.`is`=1 order by b.bill_no;";
				$sql .= "select p.bill_no,p.recieved-p.returned'amnt' from bill_payment as p where p.date='".$date."' and p.`is`=1 order by p.bill_no;
						select p.bill_no,p.recieved-p.returned'amnt' from purchase_payment as p where p.date='".$date."' and p.`is`=1 order by p.bill_no;";
				$rst = $mysql->execute($sql);
				$opening = 0;
				$allow=1;
				$open_time = '';
				if(mysqli_num_rows($rst[0])==0)
				{
					$allow = 0;
				}
				$r = mysqli_fetch_array($rst[0]);
				$opening = $r['opening'];
				$open_time = $r['opening_time'];
				$close = $r['close'];
				$close_time = $r['closing_time'];
				$expence = array();
				$income = array();
				while($r = mysqli_fetch_array($rst[1]))
				{
					$income['name'][] = $r['name'];
					$income['amount'][] = $r['amount'];
				}
				while($r = mysqli_fetch_array($rst[2]))
				{
					$expence['name'][] = $r['name'];
					$expence['amount'][] = $r['amount'];
				}
				
				$bill = array();
				$purchase = array();
				while($r = mysqli_fetch_array($rst[3]))
				{
					$bill[] = $r['bill_no'];
				}
				while($r = mysqli_fetch_array($rst[4]))
				{
					$purchase[] = $r['bill_no'];
				}
				$bill_pay = array();
				$par_pay = array();
				while($r = mysqli_fetch_array($rst[5]))
				{
					$bill_pay['no'][] = $r['bill_no'];
					$bill_pay['amount'][] = $r['amnt'];
				}
				while($r = mysqli_fetch_array($rst[6]))
				{
					$par_pay['no'][] = $r['bill_no'];
					$par_pay['amount'][] = $r['amnt'];
				}
if($allow)
{
?>
      <center>
        <div class="alert alert-block  alert-info" style="width:35%">
          <h3>Shop Opened at <?php echo $open_time ?><br>
          <?php
		  if($close)
		  {
			  ?>
              Shop Closed at <?php echo $close_time ?>
              <?php
		  }
		  ?>
          </h3>
        </div>
      </center>
      <form action="#" method="post">
      <div class="row-fluid">
        <div class="span4">
          <table width="100%" class="table table-bordered table-striped" cellpadding="5" style="font-weight:bold">
            <thead>
              <tr>
                <th colspan="4">INCOME / EXPENCE</th>
              </tr>
              <tr>
                <th width="10%">Sno</th>
                <th>TYPE</th>
                <th>INCOME</th>
                <th>EXPENCE</th>
              </tr>
            </thead>
            <tbody>
              <?php
				$sno =0;
				?>
              <tr>
                <td style="text-align:center"><?php echo ++$sno; ?></td>
                <td>Opening Balance</td>
                <td style="text-align:right"><?php 	
						echo $mysql->currency($opening);				
					?></td>
                <td style="text-align:center">-</td>
              </tr>
              <?php
				$max = count($income['name']);
				for($i=0;$i<$max;$i++)
				{
					if($income['amount'][$i]!='')
					{
						?>
					  <tr>
						<td style="text-align:center"><?php echo ++$sno; ?></td>
						<td><?php 	
								echo $income['name'][$i];				
							?></td>
						<td style="text-align:right"><?php 	
								echo $mysql->currency($income['amount'][$i]);				
							?></td>
						<td style="text-align:center">-</td>
					  </tr>
					  <?php
					}
				}
				$max = count($expence['name']);
				for($i=0;$i<$max;$i++)
				{
					if($expence['amount'][$i]!='')
					{
					?>
              <tr>
                <td style="text-align:center"><?php echo ++$sno; ?></td>
                <td><?php 	
						echo $expence['name'][$i];				
					?></td>
                <td style="text-align:center">-</td>
                <td style="text-align:right"><?php 	
						echo $mysql->currency($expence['amount'][$i]);				
					?></td>
              </tr>
              <?php
					}
				}
				?>
            </tbody>
            <tfoot>
              <tr>
                <th></th>
                <th style="text-align:right">Net</th>
                <th style="text-align:right"><?php echo $mysql->currency(array_sum($income['amount'])+$opening) ?></th>
                <th style="text-align:right"><?php echo $mysql->currency(array_sum($expence['amount'])) ?></th>
              </tr>
            </tfoot>
          </table>
          <table width="100%" class="table table-bordered table-striped" cellpadding="5" style="font-weight:bold;">
            <thead>
              <tr>
                <th colspan="4">Payment on Bills / Purchase </th>
              </tr>
              <tr>
                <th width="10%">Sno</th>
                <th>No</th>
                <th>Bills</th>
                <th>Purchase</th>
              </tr>
            </thead>
            <tbody>
              <?php
			  
				$sno =0;
				$max = count($bill_pay['no']);
				for($i=0;$i<$max;$i++)
				{
					if($bill_pay['amount'][$i]!='')
					{
					?>
              <tr>
                <td style="text-align:center"><?php echo ++$sno; ?></td>
                <td style="text-align:center"><a href="bill_detail.php?billno=<?php echo $bill_pay['no'][$i]; ?>">
                  <?php 	
						echo $bill_pay['no'][$i];				
					?>
                  </a></td>
                <td style="text-align:right"><?php 	
						echo $mysql->currency($bill_pay['amount'][$i]);				
					?></td>
                <td style="text-align:center">-</td>
              </tr>
              <?php
					}
				}
				$max = count($par_pay['no']);
				for($i=0;$i<$max;$i++)
				{
					if($par_pay['amount'][$i]!='')
					{
					?>
              <tr>
                <td style="text-align:center"><?php echo ++$sno; ?></td>
                <td style="text-align:center"><a href="purchase_detail.php?billno=<?php echo $par_pay['no'][$i]; ?>">
                  <?php 	
						echo $par_pay['no'][$i];				
					?>
                  </a></td>
                <td style="text-align:center">-</td>
                <td style="text-align:right"><?php 	
						echo $mysql->currency($par_pay['amount'][$i]);				
					?></td>
              </tr>
              <?php
					}
				}
				?>
            </tbody>
            <tfoot>
              <tr>
                <th></th>
                <th style="text-align:right">Net</th>
                <th style="text-align:right"><?php echo $mysql->currency(array_sum($bill_pay['amount'])) ?></th>
                <th style="text-align:right"><?php echo $mysql->currency(array_sum($par_pay['amount'])) ?></th>
              </tr>
            </tfoot>
          </table>
        </div>
        <div class="span4">
          <?php include "classes/cash.php"; ?>
        </div>
        <div class="span4">
          <table width="100%" class="table table-bordered table-striped" cellpadding="5" style="font-weight:bold">
            <thead>
              <tr>
                <th colspan="3">BILLS / PURCHASE</th>
              </tr>
            <th width="10%">Sno</th>
              <th>BILLS</th>
              <th>PURCHASE</th>
                </thead>
            <tbody>
              <?php 
			if(count($bill)>count($purchase))
				$max = count($bill);
			else
				$max = count($purchase);
			$sno =0;
			for($i=0;$i<$max;$i++)
			{
				if($bill[$i]!='' || $purchase[$i]!='')
				{
				?>
              <tr>
                <td style="text-align:center"><?php echo ++$sno; ?></td>
                <td style="text-align:center"><?php 
				if($bill[$i]!='')
				{
					?>
                  <a href="bill_detail.php?billno=<?php echo $bill[$i]; ?>"><?php echo $bill[$i];  ?></a>
                  <?php
				}
				else
					echo '-';
				?></td>
                <td style="text-align:center"><?php 
				if($purchase[$i]!='')
				{
					?>
                  <a href="purchase_detail.php?billno=<?php echo $purchase[$i]; ?>"><?php echo $purchase[$i];  ?></a>
                  <?php
				}
				else
					echo '-';
				?></td>
              </tr>
              <?php
				}
			}
			?>
            </tbody>
            <tfoot>
              <tr>
                <th align="right">Tot</th>
                <th style="text-align:center"><?php echo count($bill) ?></th>
                <th style="text-align:center"><?php echo count($purchase) ?></th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <center>
        <table class="table table-striped table-detail" style="width:400px; font-weight:bold">
          <tr>
            <th>Date </th>
            <td style="text-align:right"><?php echo $date ?></td>
          </tr>
          <tr>
            <th>Opened at </th>
            <td style="text-align:right"><?php echo $open_time ?></td>
          </tr>
          <tr>
            <th>1.Opening Balance </th>
            <td style="text-align:right"><?php echo $mysql->currency($opening) ?></td>
          </tr>
          <tr>
            <th>2.Bill Payments </th>
            <td style="text-align:right"><?php echo $mysql->currency(array_sum($bill_pay['amount'])) ?></td>
          </tr>
          <tr>
            <th>3.Other Incomes </th>
            <td style="text-align:right"><?php echo $mysql->currency(array_sum($income['amount'])) ?></td>
          </tr>
          <tr>
            <th>4.Purchase Payments </th>
            <td style="text-align:right"><?php echo $mysql->currency(array_sum($par_pay['amount'])) ?></td>
          </tr>
          <tr>
            <th>5.Expences </th>
            <td style="text-align:right"><?php echo $mysql->currency(array_sum($expence['amount'])) ?></td>
          </tr>
          <tr style=" font-size:18px;">
            <th>Income(2+3)</th>
            <td style="text-align:right"><?php 
		  $day_income = array_sum($bill_pay['amount'])+array_sum($income['amount']);
		  echo $mysql->currency($day_income) 
		  ?></td>
          </tr>
          <tr style=" font-size:18px;">
            <th>Expence(4+5)</th>
            <td style="text-align:right"><?php
		  $day_expence =  array_sum($par_pay['amount'])+array_sum($expence['amount']);
		  echo $mysql->currency($day_expence) 
		  ?></td>
          </tr>
          <tr style=" font-size:18px;">
            <th>Cash on Shop</th>
            <td style="text-align:right"><?php
		  $profit = $day_income+$opening;
		  if($profit<0)
		  {
			  $profit = 0;
		  }
		  echo $mysql->currency($profit);
		  ?></td>
          </tr>
          <tr style=" font-size:18px;">
            <th>Today Profit:</th>
            <th style="text-align:right"> <span class="label label-success" style="font-size:20px;">
              <?php
		  $profit = $day_income-$day_expence;
		  if($profit<0)
		  {
			  $profit = 0;
		  }
		  echo $mysql->currency($profit);
		  ?>
              </span></th>
          </tr>
        </table>
        <?php
		  if($close==0)
		  {
	      ?>
          <input type="hidden" name="date" value="<?php echo $date  ?>">
          <button type="submit" name="close_shop" class="btn btn-blue4 btn-large">Close Shop</button>
          <?php
		  }
		  ?>
      </center>
      </form>
      <?php
}
else
{
	?>
      <center>
        <div class="alert alert-block  alert-danger" style="width:35%">
          <h3>Shop Not Opened</h3>
          <br>
          <a href="open_shop.php">Click here to open</a> </div>
      </center>
      <?php
}
?>
    </div>
    <?php include"footer.php" ?>