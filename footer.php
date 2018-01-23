<?php
			$sql = "select 
						p.id,
						g.name,
						p.pid,
						p.name,
						p.`type`,
						sum(s.available)'having',
						p.min_value
					from 
						product as p ,
						stock as s,
						product_group as g
					where 
						p.min_status=1
						and
						p.`is`=1
						and
						s.`is`=1
						and
						s.pid=p.id
						and
						g.id=p.gid
					group by
						p.pid
					order by
						p.name;
					select 
						p.id,
						g.name,
						p.pid,
						p.name,
						p.`type`,
						p.min_value
					from 
						product as p ,
						product_group as g
					where 
						p.min_status=1
						and
						p.`is`=1
						and
						g.id=p.gid
					order by
						p.name";
			$rst = $mysql->execute($sql);
			$lack = array();
			$skip = array();
			while($r = mysqli_fetch_array($rst[0]))
			{
				if($r['min_value']>$r['having'])
				{
				$lack[$r['id']]['pid']=$r['pid'];
				$lack[$r['id']]['name']=$r['name'];
				$lack[$r['id']]['type']=$r['type'];
				$lack[$r['id']]['having']=$r['having'];
				$lack[$r['id']]['min_value']=$r['min_value'];
				}
				$skip[$r['id']] = 1;
			}
			while($r = mysqli_fetch_array($rst[1]))
			{
				if(!isset($skip[$r['id']]))
				{
				$lack[$r['id']]['pid']=$r['pid'];
				$lack[$r['id']]['name']=$r['name'];
				$lack[$r['id']]['type']=$r['type'];
				$lack[$r['id']]['having']=0;
				$lack[$r['id']]['min_value']=$r['min_value'];
				}
			}
			?>
<script type="text/javascript">
$("#stock_count").html('<?php echo count($lack); ?>')
</script>

<div class="modal hide fade" id="stocklack">
  <div class="modal-header">
    <button class="close" data-dismiss="modal">X</button>
    <h3>Stocks Lacking</h3>
  </div>
  <div class="modal-body">
    <div class="alert alert-error">The Following <?php echo count($lack); ?> Products are in Lack in Our Shop.</div>
    <table class="table table-condensed table-striped" data-rowlink="a">
      <thead>
        <tr>
          <th>Prd Id</th>
          <th>Name</th>
          <th>Min Value</th>
          <th>Stock Having</th>
        </tr>
      </thead>
      <tbody>
        <?php
				foreach($lack as $id=>$data)
				{ 
				?>
        <tr>
          <td><?php echo $data['pid']; ?></td>
          <td><a href="product_view.php?id=<?php echo $id; ?>"><?php echo $data['name']; ?></a></td>
          <td style="text-align:center"><?php echo $data['min_value'].' '.$data['type'];; ?></td>
          <td style="text-align:center"><?php echo $data['having'].' '.$data['type']; ?></td>
        </tr>
        <?php
				}
				?>
      </tbody>
    </table>
  </div>
  <div class="modal-footer"> </div>
</div>
<?php
	  $sql = "select 	
					bl.bill_no,
					date_format(bl.`date`,'%d-%m-%Y')'date',
					bl.tot_products,
					bl.bill_amount,
					bl.`lock`,
					bl.trans_close
				from 	
					bill_orders as bl
				where 
					(
					bl.`lock`=0
					or
					bl.trans_close=0
					)
					and
					bl.`status`!='c'
				order by `it` desc;
				select 	
					bl.bill_no,
					date_format(bl.`date`,'%d-%m-%Y')'date',
					bl.tot_products,
					bl.bill_amount,
					bl.`lock`,
					bl.trans_close
				from 	
					purchase_orders as bl
				where 
					(
					bl.`lock`=0
					or
					bl.trans_close=0
					)
					and
					bl.`status`!='c'
				order by `it` desc";
	  $rst = $mysql->execute($sql);
	  $pend_ord = array();
	  while($r = mysqli_fetch_array($rst[0]))
	  {
		  $pend_ord[$r['bill_no']]['date'] = $r['date'];
		  $pend_ord[$r['bill_no']]['tot_products'] = $r['tot_products'];
		  $pend_ord[$r['bill_no']]['bill_amount'] = $mysql->currency($r['bill_amount']);
		  $pend_ord[$r['bill_no']]['lock'] = $r['lock'];
		  $pend_ord[$r['bill_no']]['trans_close'] = $r['trans_close'];
	  }
	  $pend_ord1 = array();
	  while($r = mysqli_fetch_array($rst[1]))
	  {
		  $pend_ord1[$r['bill_no']]['date'] = $r['date'];
		  $pend_ord1[$r['bill_no']]['tot_products'] = $r['tot_products'];
		  $pend_ord1[$r['bill_no']]['bill_amount'] = $mysql->currency($r['bill_amount']);
		  $pend_ord1[$r['bill_no']]['lock'] = $r['lock'];
		  $pend_ord1[$r['bill_no']]['trans_close'] = $r['trans_close'];
	  }
	  ?>
<script type="text/javascript">
$("#pendingOrders").html('<?php echo count($pend_ord)+count($pend_ord1); ?>')
</script>
<div class="modal hide fade" id="pendingorders">
  <div class="modal-header">
    <button class="close" data-dismiss="modal">X</button>
    <h3>Pending Orders</h3>
  </div>
  <!--<div class="modal-body">
    <div class="alert alert-info">The Following <?php echo count($pend_ord) ?> Bills Are Pending</div>
    <table class="table table-condensed table-striped" data-rowlink="a">
      <thead>
        <tr>
          <th>Bill No</th>
          <th>Date</th>
          <th>Total Products</th>
          <th>Amount</th>
          <th>Status</th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php 
			  foreach($pend_ord as $bill=>$data)
			  {
			  ?>
        <tr>
          <td style="text-align:center"><a href="bill_detail.php?billno=<?php echo $bill ?>"><?php echo $bill  ?></a></td>
          <td style="text-align:center"><?php echo $data['date'] ?></td>
          <td style="text-align:center"><?php echo $data['tot_products'] ?></td>
          <td style="text-align:right"><?php echo $data['bill_amount'] ?></td>
          <td><?php if($data['lock']) echo 'Locked'; else echo 'Pending';
				  			echo '<br>';
				  			if($data['trans_close']) echo 'Payment Completed'; else echo 'Payment Pending';
					 ?></td>
          <td>
          <?php if($data['lock']==0){ ?><form action="billing.php" method="post">
          <input type="hidden" name="unClosedBills" value="<?php echo $bill ?>"/>          
          <input type="submit" name="loadBill" value="Bill"  class="btn" />
          </form>&nbsp;&nbsp;<?php } ?>
          </td>
          <td style="vertical-align:top">
          <form action="bill_payment.php" method="post">
          <input type="hidden" name="billno" value="<?php echo $bill ?>"/>          
          <input type="submit" name="load" value="Payment"  class="btn" />
          </form>
          </td>
        </tr>
        <?php
			  }
			  ?>
      </tbody>
    </table>
    <div class="alert alert-info">The Following <?php echo count($pend_ord1) ?> Purchase Are Pending</div>
    <table class="table table-condensed table-striped" data-rowlink="a">
      <thead>
        <tr>
          <th>Pur No</th>
          <th>Date</th>
          <th>Total Products</th>
          <th>Amount</th>
          <th>Staus</th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php 
			  foreach($pend_ord1 as $bill=>$data)
			  {
			  ?>
        <tr>
          <td style="text-align:center"><a href="purchase_detail.php?billno=<?php echo $bill ?>"><?php echo $bill  ?></a></td>
          <td style="text-align:center"><?php echo $data['date'] ?></td>
          <td style="text-align:center"><?php echo $data['tot_products'] ?></td>
          <td style="text-align:right"><?php echo $data['bill_amount'] ?></td>
          <td><?php if($data['lock']) echo 'Locked'; else echo 'Pending';
				  			echo '<br>';
				  			if($data['trans_close']) echo 'Payment Completed'; else echo 'Payment Pending';
					 ?></td>
          <td>
          <?php if($data['lock']==0){ ?><form action="purchase.php" method="post">
          <input type="hidden" name="unClosedBills" value="<?php echo $bill ?>"/>          
          <input type="submit" name="loadBill" value="Load Order"  class="btn" />
          </form><?php } ?></td>
          <td style="vertical-align:top">
          <form action="purchase_payment.php" method="post">
          <input type="hidden" name="billno" value="<?php echo $bill ?>"/>          
          <input type="submit" name="load" value="Payment"  class="btn" />
          </form>
          </td>
        </tr>
        <?php
			  }
			  ?>
      </tbody>
    </table>
  </div>-->
  <div class="modal-footer"> </div>
</div>
<div class="modal hide fade" id="notification">
  <div class="modal-header">
    <button class="close" data-dismiss="modal">X</button>
    <h3>Notification</h3>
  </div>
  <div class="modal-body">
    <div class="alert alert-info">You Having Notifications</div>
    <table class="table table-condensed table-striped" data-rowlink="a">
      <thead>
        <tr>
          <th>Sender</th>
          <th>Subject</th>
          <th>Date</th>
          <th>Size</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
  <div class="modal-footer"> </div>
</div>
<script type="text/javascript" src="statistics/js/highcharts.js"></script> 
<script type="text/javascript" src="statistics/js/highcharts-more.js"></script>
</div>
</div>
</body></html>