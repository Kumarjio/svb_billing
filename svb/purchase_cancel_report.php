<?php
include"header.php";
?>
    <div class="box">
      <div class="box-head tabs">
        <h3>Stock Cancellation Report</h3>
      </div>
      <div class="box-content box-nomargin">
        <div class="tab-content">
          <div class="tab-pane active" id="current">
            <table class='table table-striped dataTable table-bordered'>
              <thead>
                <tr>
                  <th>Stock Entry no</th>
                  <th>Entered Time</th>
                  <th>Purchase Amnt</th>
                  <th>Cancel Reason</th>
                  <th>Detail</th>
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
											purchase_cancel_request as bc, 
											purchase_orders as bl 
										where 
											bc.responce='accepted' 
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
                  <td style="text-align:center"><a href="purchase_detail.php?billno=<?php echo $currentbill['bill_no'] ?>">View Detail</a>
                    </td>
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