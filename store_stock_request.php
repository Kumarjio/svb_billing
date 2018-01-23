<?php include"header.php"; ?>
<script type="text/javascript" src="js/store.js"></script>
<?php include"classes/store_request.php"; ?>
<?php include"itemSearch4.php";?>
<?php include"store_request_mode.php"; ?>
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
      <div class="span12">
        <div class="box">
          <div class="box-head">
            <h3>Store Request</h3>
          </div>
          <table class="table table-striped table-detail" border="0" cellpadding="10">
            <tr>
              <th>Current Bill No:</th>
              <td><span class="label label-important" style="font-size:20px;"><?php echo  $billing->getCurrentBill(); ?></span></td>
              <th>User: <?php echo $_SESSION['name'] ?></th>
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
                </select>
                &nbsp;&nbsp;
              <span>
              <?php
			  if($lockCount>0)
			  {
			  ?>
              <button type="submit" name="lockBill"  class="btn btn-blue4">Lock(<?php echo  $lockCount; ?>)</button>
              <?php
			  }
			  else
			  {
				  ?>
                  <strong>Pending Wastage</strong> : <span class="label label-inverse" style="font-size:15px;"><?php echo $lockCount; ?></span>
                  <?php
			  }
			  ?>
              <span class="tooltip"><?php 
				foreach($lockPending as $bill=>$lp)
				{
					if($bill!='')
					{
					?>
                  <a href="wastage_detail.php?billno=<?php echo $bill ?>"><?php echo $bill ?></a><br>
                  <?php
					}
				}
				if($lockCount == 0)
					echo 'All Bills are Locked';
				?></span>
              </span>
                &nbsp;&nbsp;
              <?php
			  if($lockCount>0)
			  {
			  ?>
              <button type="submit" name="loadBill"  class="btn btn-green2">Load</button>
              <?php
			  }
			  ?>
              </td>
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
              </td>
            <th width="45%" onClick="$('#MitemName').focus()"><input id="MitemName" type="text" class="billText MitemName"  autocomplete="off" placeholder="Item">
              </td>
            <th width="10%" onClick="$('#MitemQty').focus()"> <input id="MitemQty" type="text" class="billText tCenter MitemQty" placeholder="Qty" autocomplete="off">
              </td>
            <th width="15%"> <select id="MitemType" style="width:100%; height:25px">
                
              </select>
            </th>
            <th width="12%" onClick="$('#MitemAmnt').focus()"> <input id="MnetAmnt" type="hidden" class="MnetAmnt">
              <input id="MitemAmnt" type="text" class="billTextRight tRight MitemAmnt" placeholder="Amount" readonly autocomplete="off" >
            </th>
            <th width="10%"> <input id="MitemDis" type="text" class="billTextRight tRight MitemDis" placeholder="Reduction"  autocomplete="off" >
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
              <th colspan="3" rowspan="5"> Tax Mode: <?php 
					if($billing->getTaxMode())
					{
						?>
                        <font color="#FF0000">Overall Tax</font> &nbsp;&nbsp;
                        Tax:<font color="#FF0000"><?php echo $billing->getTax() ?> %</font>
                        <?php
					}
					else
					{
						?>
						<font color="#FF0000">Product Wise Vat</font>
                        <?php
					}
			?><br><br>
<?php
					if(!isset($_POST['loadBill']))
					{
						?>
                <button type="submit" name="Save" class="btn btn-green2 btn-small" onClick="saveBill()">
                <h4><img src="img/icons/essen/16/billing.png"> Save</h4>
                </button>
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
                </button>&nbsp;&nbsp;&nbsp;
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
                &nbsp;&nbsp;&nbsp;
             	<button type="submit" name="complete" class="btn btn-red4 btn-small">
                <h4><img src="img/icons/essen/16/credit-card.png"> Complete Wastage</h4>
           		</button>
          <?php						
					}
					?>
            </th>
          
          
            <th align="right" class="borserlbr bgcolor">Total</th>
            <th class="borserlbr bgcolor"><input name="total" id="total" type="text" class="billTextRight tRight"  readonly></th>
            <th><input id="netdiscount" type="text" class="billTextRight tRight"  readonly></th>
          </tr>
          <tr>
            <th align="right" class="borserlbr bgcolor">Tax</th>
            <th class="borserlbr bgcolor"><input name="tax" id="tax" type="text" class="billTextRight tRight amnt" readonly></th>
            <th></th>
          </tr>

            <input name="recieved" id="recieved" type="hidden" class="billTextRight tRight amnt" value="<?php echo $billing->getRecieved() ?>" autocomplete="off" />
            <input name="returned" id="returned" type="hidden" class="billTextRight tRight amnt" value="<?php echo $billing->getReturned() ?>"  autocomplete="off" />
          <tr>
            <th align="right" rowspan="3" class="borserlbr bgcolor" style="vertical-align:top; height:10px;" >Net</th>
            <th class="borserlbr bgcolor" rowspan="3" style="vertical-align:top; font-weight:bold" align="right"> <input name="net" id="net" type="text" class="billTextRight tRight amnt" autocomplete="off"  />
            </th>
            <th rowspan="3"></th>
        </table>
      </div>
    </div>
    <div class="span2"></div>
    <div class="span8">
      <div class="box">
        <div class="itemsList">
          <table border="1" width="100%" cellspacing="5" cellpadding="3">
            <tr>
              <th>
			  <?php 
			  	$products = $billing->getProductList();
			  	//echo count($products) 
			  ?>PID</th>
              <th></th>
              <th>Name</th>
              <th>QTY</th>
              <th>Amnt <b><del>&#2352;</del></b></th>
              <th>VAT Amnt <b><del>&#2352;</del></b></th>
            </tr>
            <?php
				foreach($products as $key=>$id)
				{
					?>
            <tr class="searchResult" style="border-bottom:#CCC 1px dashed;" onClick="addFromTray('<?php echo $products[$key]['pid'] ?>')" >
              <td align="center"><?php echo $products[$key]['pd_id'] ?></td>
              <td align="center"><img src="<?php echo $products[$key]['photo'] ?>" width="30"></td>
              <td align="left" style="padding-left:10px;"><b><?php echo $products[$key]['name'] ?></b></td>
              <td align="left" style="padding-left:10px;"><b><?php echo $products[$key]['available'] ?></b></td>
              <td align="right" style="padding-right:10px;"><?php echo $mysql->currency($products[$key]['unit_price']); ?></td>
              <td align="right" style="padding-right:10px;"><?php 
			  echo $mysql->currency($products[$key]['unit_price']+($products[$key]['unit_price']*$products[$key]['vat']/100)); ?></td>
            </tr>
            <?php
				}
				?>
          </table>
        </div>
      </div>
    </div>
    </div>
    <link href="css/billing.css" rel="stylesheet" type="text/css">
  </form>
</div>
  <?php include"footer.php"; ?>