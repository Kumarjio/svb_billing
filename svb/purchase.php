<?php include"header.php"; ?>
<script type="text/javascript" src="js/purchase.js"></script>
<?php include"classes/purchase.php"; ?>
<?php include"purchase_search.php";?>
<?php include"purchase_mode.php"; ?>
<div class="billing_datas" >
<script type="text/javascript">
    var tax = <?php 
		if($billing->getTaxMode())
			echo $billing->getTax(); 
		else
			echo 0.0; 
	?>;
    </script> 
  <form action="#" method="post">
    <div class="row-fluid">
      <div class="span7">
        <div class="box">
          <div class="box-head">
            <h3>Stock Entry</h3>
          </div>
          <table class="table table-striped table-detail" style="width:350px; overflow:visible; white-space:nowrap">
            <tr>
              <th>Current Stock Entry No: </th>
              <td><span class="label label-important" style="font-size:20px;"><?php echo strtoupper($billing->getCurrentBill()); ?></span></td>
              <th>User: </th>
              <td><?php echo $_SESSION['name'] ?></td>
            
              <?php 
			  $billing->setUnclosed();
			  $lockPending = $billing->getUnLocked();
			  $unPaid = $billing->getUnPaid();
			   ?>
              <td><select name="unClosedBills" class="cho" style="width:150px;">
                  <?php 
				$lockCount=0;
				foreach($lockPending as $bill=>$lp)
				{
					if($bill!='')
					{
					$lockCount++;
					?>
                  <option value="<?php echo $bill ?>" <?php if($bill==$billing->getCurrentBill()) echo'selected'; ?> ><?php echo $bill ?></option>
                  <?php
					}
				}
				?>
                <?php 
				$unPaidCount=0;
				foreach($unPaid as $bill=>$lp)
				{
					if($bill!='')
					{
					$unPaidCount++;
					}
				}
				?>
                </select></td>
                <td>
              <?php
			  if($lockCount>0)
			  {
			  ?>
              <button type="submit" name="loadBill"  class="btn btn-green2">Load</button>
              <?php
			  }
			  ?>
              </td>
              <td>
              <span>
              <?php
			  if($lockCount>0)
			  {
			  ?>
              <button type="submit" name="lockBill"  class="btn btn-blue4">Lock(<?php echo  $lockCount; ?>)</button>
              <?php
			  }
			  ?>
              <span class="tooltip"><?php 
				foreach($lockPending as $bill=>$lp)
				{
					if($bill!='')
					{
					?>
                  <a href="purchase_detail.php?billno=<?php echo $bill ?>"><?php echo $bill ?></a><br>
                  <?php
					}
				}
				if($lockCount == 0)
					echo 'All Purchase are Locked';
				?></span>
              </span>
                &nbsp;</td>
            </tr>
              </td>
              </tr>
            
          </table>
        </div>
      </div>
    </div>
    <div class="row-fluid">
      <div class="span12">
        <div class="box">
        <table border="1" width="100%" align="center" cellspacing="5" cellpadding="5" class="bgcolor" id="items">
          <tr id="itemsEntry">
            <th width="5%"> <input id="MitemId" type="hidden" class="MitemId">
              <input id="MitemVat" type="hidden" class="MitemVat">
              <input id="MitemCode" type="hidden" class="MitemCode">
              </td>
            <th width="45%" onClick="$('#MitemName').focus()"><input id="MitemName" type="text" class="billText MitemName"  autocomplete="off" placeholder="Item">
              </td>
            <th width="10%" onClick="$('#MitemQty').focus()"> <input id="MitemQty" type="text" class="billText tCenter MitemQty" placeholder="Qty" autocomplete="off">
              </td>
            <th width="15%"> <select id="MitemType" style="width:100%; height:25px">
                
              </select>
            </th>
            <th width="12%" onClick="$('#MitemAmnt').focus()"> <input id="MnetAmnt" type="hidden" class="MnetAmnt">
              <input id="MitemAmnt" type="hidden" class="billTextRight tRight MitemAmnt" placeholder="Amount" readonly autocomplete="off" >
              <input id="MoldStock" name="MoldStock[]" type="text" class="billTextRight tRight MoldStock" autocomplete="off" readonly placeholder="Stock" >
            </th>
            <th width="10%"> <input id="MitemDis" type="hidden" class="billTextRight tRight MitemDis" placeholder="Discount"  autocomplete="off" >
            <input id="MnewStock" name="MoldStock[]" type="text" class="billTextRight tRight MnewStock" autocomplete="off" readonly placeholder="New Stock" >
            </th>
            <th width="3%" align="left"><i class=icon-trash style="cursor:pointer" onClick="billingSettings()" ></i> </th>
          </tr>
          <tbody id="itemSelected">
            <?php
				if(!isset($_POST['loadBill']))
				{
					$billing->setNewBill();
				}
				else
				{
					$billing->loadBill();	
				}
				?>
          </tbody>
          <tbody style="border:0px solid">
            <tr>
              <th colspan="6"> <br><br>
                <?php
					if(!isset($_POST['loadBill']))
					{
						?>
                <button type="submit" name="Save" class="btn btn-green2 btn-small" onClick="saveBill()">
                  <h4><img src="img/icons/essen/16/billing.png"> Save</h4>
                  </button>
                <!--&nbsp;&nbsp;&nbsp;
                <button type="submit" name="Print" class="btn btn-blue4 btn-small">
                <h4><img src="img/icons/essen/16/print.png"> Save & Recieved</h4>
                </button>-->
                &nbsp;&nbsp;&nbsp;
                <button type="submit" name="Cancel"  class="btn btn-red4 btn-small">
                  <h4><img src="img/icons/essen/16/order-192.png"> Cancel</h4>
                  </button>
                
                <?php
					}
					else
					{
						?>
                <input type="hidden" name="loadedBillNo" value="<?php echo  $billing->getCurrentBill(); ?>" />
                <button type="submit" name="SaveL" class="btn btn-green2 btn-small" onClick="saveBill()">
                  <h4><img src="img/icons/essen/16/billing.png"> Save</h4>
                  </button>
                <!--&nbsp;&nbsp;&nbsp;
                <button type="submit" name="PrintL" class="btn btn-blue4 btn-small">
                <h4><img src="img/icons/essen/16/print.png">  Save & Recieved</h4>
                </button>-->
                &nbsp;&nbsp;&nbsp;
                <a   class="btn btn-red4 btn-small" data-toggle="modal" href="#cancelReasonModel" id="cancelMe"><h4><img src="img/icons/essen/16/order-192.png"> Cancel</h4></a>
                <div aria-hidden="true" style="display: none;" class="modal hide" id="cancelReasonModel">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3>Enter Cancellation Reason</h3>
                    </div>
                  <div class="modal-body">
                    <textarea name="cancelReason" id="cancelReason" style="width:90%; height:100%"></textarea>
                    </div>
                  <div class="modal-footer"> 
                    <button type="submit" name="CancelL"  class="btn btn-red4 btn-small">
                      <h4><img src="img/icons/essen/16/order-192.png"> Cancel</h4>
                      </button> <a href="#" class="btn btn-primary" data-dismiss="modal">No</a> </div>
                  </div> 
                <?php						
					}
					?>
                    <span style="display:none">
                Total
                <input name="total" id="total" type="text" class="billTextRight tRight"  readonly>              <input id="netdiscount" type="text" class="billTextRight tRight"  readonly>
                Tax                <input name="tax" id="tax" type="text" class="billTextRight tRight amnt" readonly>
                Paid
                <input name="recieved" id="recieved" type="text" class="billTextRight tRight amnt" value="<?php echo floatval($billing->getRecieved()) ?>" autocomplete="off" />                Returned
                <input name="returned" id="returned" type="text" class="billTextRight tRight amnt" value="<?php echo floatval($billing->getReturned()) ?>"  autocomplete="off" />                Net <input name="net" id="net" type="text" class="billTextRight tRight amnt" autocomplete="off"  />                
                </span></th>
              </tr>
          </table>
      </div>
    </div>
    <link href="css/billing.css" rel="stylesheet" type="text/css">
  </form>
</div>
  <?php include"footer.php"; ?>