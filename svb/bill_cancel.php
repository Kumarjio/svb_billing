<?php
include"header.php";
?>
    <div class="box">
      <div class="box-head tabs">
        <h3>Bill Cancellation</h3>
      </div>
      <div class="box-content box-nomargin">
      <?php
		if(isset($_POST['cancel']))
		{
			$sql = "UPDATE `bill` SET `status`='c',`flow`=concat(`flow`,'c'),`cancel`=1,`lock`=1,`trans_close`=1 WHERE  `bill_no`='".$_POST['billno']."';
					UPDATE `bill_cancel_request` SET `responce`='accepted' WHERE `bill_no`='".$_POST['billno']."';";
			$rst = $mysql->execute($sql);
			$sql ="select od.product_id,od.stock_reduction from orders as od where od.bill_no='".$_POST['billno']."' and od.`is`=1;";
			$rst = $mysql->execute($sql);
			$sql = '';
			while($r = mysqli_fetch_array($rst[0]))
			{
				$sql .= "UPDATE `stock` SET `available`=`available`+".$r['stock_reduction']." WHERE  `id`=".$r['product_id']." LIMIT 1;";
			}
			$rst = $mysql->execute($sql);
			?>
			<div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
			  <h4 class="alert-heading">Successfully Cancelled!</h4>
			</div>
			<?php
		}
		else if(isset($_POST['cancel_r']))
		{
			$sql = "UPDATE `bill_cancel_request` SET `responce`='rejected' WHERE `bill_no`='".$_POST['billno']."'";
			$mysql->execute($sql);
			?>
			<div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
			  <h4 class="alert-heading">Request Rejected!</h4>
			</div>
			<?php
		}
		?>
        <div class="tab-content">
          <div class="tab-pane active" id="current">
            <table class='table table-striped dataTable table-bordered'>
              <thead>
                <tr>
                  <th>Bill no</th>
                  <th>Entered Time</th>
                  <th>Bill Amnt</th>
                  <th>Cancel Reason</th>
                  <th>Detail</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php
								$query="select 
											bc.bill_no, 
											bl.bill_amount, 
											bl.recieved_amount, 
											bl.returned_amount, 
											bc.reason, 
											bl.it'billTime', 
											bc.it'rTime' 
										from
											bill_cancel_request as bc, 
											bill_orders as bl 
										where 
											bc.responce='pending' 
											and 
											bl.bill_no=bc.bill_no 
											and
											bc.is=1
										group by 
											bl.bill_no; ";
											$result=$mysql->execute($query);							
											
											while($currentbill=mysqli_fetch_array($result[0]))
											{
												?>
                <tr>
                  <td style="text-align:center"><?php echo $currentbill['bill_no'] ?></td>
                  <td style="text-align:center"><?php echo $currentbill['billTime'] ?></td>
                  <td style="text-align:center"><?php echo $mysql->currency($currentbill['bill_amount']) ?></td>
                  <td style="text-align:center"><?php echo $currentbill['reason'] ?></td>
                  <td style="text-align:center"><a href="bill_detail.php?billno=<?php echo $currentbill['bill_no'] ?>">View Detail</a>
                    </td>
                  <td><form action="bill_cancel.php" method="post">
                      <input type="hidden" name="billno" value='<?php echo $currentbill['bill_no'] ?>'>
                      <center>
                        <input type="submit" name="cancel" class='btn btn-primary' value="Cancel Bill" />
                        <input type="submit" name="cancel_r" class='btn btn-red4' value="Cancel Request" />
                      </center>
                    </form></td>
                </tr>
               <?php
				}
			?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <?php
include"footer.php";
?>