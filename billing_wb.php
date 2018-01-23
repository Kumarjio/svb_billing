<?php include"header.php"; ?>
<link rel='stylesheet' type='text/css' href='css/bill_style.css' />
<link rel='stylesheet' type='text/css' href='css/billing.css' />
<script type="text/javascript" src="js/billing.js"></script>
<?php include"classes/billing_wb.php"; ?>
<?php include"itemSearch.php";?>
<?php include"billing_mode_wb.php"; ?>
<div class="billing_datas" >
<script type="text/javascript">
    var tax = <?php 
		if($billing->getTaxMode())
			echo $billing->getTax(); 
		else
			echo 0.0; 
	?>;
	$(document).ready(function(e) {
        $(document).keydown(function(e){
		if (!e) var e = window.event;
		var keyCode = e.keyCode || e.which;
		if (keyCode == 13 || keyCode == 18) {  // Enter & ALT Key Blocking
			e.preventDefault();
	  	}
		else if(keyCode == 112) // Bill Print F1
		{
			e.preventDefault();
			$("#bill_print").click();
		}
		else if(keyCode == 113) // Bill Save F2
		{
			e.preventDefault();
			$("#bill_save").click()();
		
		}
		else if(keyCode == 114) // Bill Cancel F3
		{
			e.preventDefault();
			$("#bill_cancel").click();
		}
		else if(keyCode == 115) // Bill Discount F4
		{
			e.preventDefault();
			$("#bill_discount").click();
		}
		else if(keyCode == 116) // Bill Payment F5
		{
			e.preventDefault();
			$("#bill_payment").click();
		}
		else if(keyCode == 117) // Bill Cash F6
		{
			e.preventDefault();
			$("#bill_cash").click();
		}		
	});
    });
</script> 
  <form action="billing_wb.php" method="post">
    <div class="row-fluid">
      <div class="span4">
        <div class="box">
          <div class="box-head">
            <h3>WB Bill Details</h3>
          </div>
          <table class="table table-striped table-detail">
            <tr>
              <th>WB Bill No:</th>
              <td><span class="label label-important" style="font-size:20px;"><?php echo  strtoupper($billing->getCurrentBill()); ?></span></td>
              <th>Date: </th>
              <td><strong><?php echo date('d-m-Y') ?></strong></td>
            </tr>
            <tr>
              <?php 
			  $billing->setUnclosed();
			  $lockPending = $billing->getUnLocked();
			  $unPaid = $billing->getUnPaid();
			   ?>
              <td><select name="unClosedBills" class="cho" style="width:100px;">
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
              <td colspan="2">
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
                  <strong>Pending</strong> : <span class="label label-inverse" style="font-size:15px;"><?php echo $lockCount; ?></span>
                  <?php
			  }
			  ?>
              <span class="tooltip"><?php 
				foreach($lockPending as $bill=>$lp)
				{
					if($bill!='')
					{
					?>
                  <a href="bill_detail.php?billno=<?php echo $bill ?>"><?php echo $bill ?></a><br>
                  <?php
					}
				}
				if($lockCount == 0)
					echo 'All Bills are Locked';
				?></span>
              </span>
                &nbsp;
              <span>
              <!--<strong>Unpaid</strong> : <span class="label label-inverse" style="font-size:15px;"><?php echo $unPaidCount; ?></span>
              <span class="tooltip"><?php 
				foreach($unPaid as $bill=>$lp)
				{
					if($bill!='')
					{
					?>
                  <a href="bill_detail.php?billno=<?php echo $bill ?>"><?php echo $bill ?></a><br>
                  <?php
					}
				}
				if($unPaidCount==0)
					echo 'All Payments Completed';
				?></span>
              </span>--></td>
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
            </tr>
              </td>
              </tr>
            
          </table>
        </div>
      </div>
      <?php
	  $sql = "select * from shop_charges as sh where sh.`is`=1";
	  $addi = $mysql->execute($sql);
	  $count = 1;
	  if(mysqli_num_rows($addi[0])>0)
	  {?>
      <div class="span5">
        <div class="box">
          <div class="box-head">
            <h3>Additional</h3>
          </div>
                <table class="table table-striped" border="0">
                <?php
				while($ar = mysqli_fetch_array($addi[0]))
				{
					if($count == 3)
					{
						?><tr><?php
						$count = 1;
					}
					?>
                    <th style="text-align:right"><?php echo $ar['name'] ?></th>
                           <th style="text-align:left !important;"><?php
					switch($ar['type'])
					{
						case 'input':
							?><input type="text" name="charges<?php echo $ar['id'] ?>" value="<?php echo intval($charges[$ar['id']]) ?>" class="charges" style="width:150px;"><?php
							break;
						case 'checkbox':
							?> <input type="checkbox" name="charges<?php echo $ar['id'] ?>" <?php if($charges[$ar['id']]) echo 'checked' ?> class="charges checkbox" style="width:150px;"><?php
							break;
						case 'select':
							?><select name="charges<?php echo $ar['id'] ?>" class="cho charges"><?php
							if($ar['type_source'] == 'employees list')
							{
								$sql = 'select p.id,p.name from profile as p where p.`is`=1;';
								$profile = $mysql->execute($sql);
								while($pr = mysqli_fetch_array($profile[0]))
								{
									?><option <?php if($charges[$ar['id']]== $pr['id']) echo 'selected'; ?> value="<?php echo $pr['id'] ?>"><?php echo $pr['name'] ?></option><?php
								}
							}
							else
							{
								$sql = "select s.id,s.value,s.name from shop_charges_list as s where s.att_id='".$ar['id']."' and s.`is`=1";
							    $custom = $mysql->execute($sql);
							    while($cus = mysqli_fetch_array($custom[0]))
								{
									?><option <?php if($charges[$ar['id']]== $cus['value']) echo 'selected'; ?> value="<?php echo $cus['value'] ?>">
									<?php echo $cus['name'] ?></option><?php
								}
							}
							?></select><?php
							break;	
					}
					?>
                      <input type="hidden" class="charges_rate" value="<?php echo $ar['rate'] ?>" >
                      <input type="hidden" class="charges_rate_type" value="<?php echo $ar['rate_type'] ?>" >
                      <input type="hidden" class="charges_rate_status" value="<?php echo $ar['rate_status'] ?>" >
                           </th>
					<?php
					if($count == 3)
					{
						?></tr><?php
						$count = 0;
					}
					$count++;
				}
				?>
                <tr><th>Tax</th><td><input type="text" name="cus_tax" value="<?php echo '0'//$billing->getTax() ?>" style="width:150px" /></td></tr>
                </table>
                
        </div>
      </div>
      <?php
	  } ?>
      <div class="span3">
        <div class="box">
          <div class="box-head">
            <h3>Customer Details&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h3>
              <?php
			  if(!isset($_POST['loadBill']))
			  {?>
              <a onClick="addCustomer()"><img src="img/icons/essen/16/plus.png">New</a>&nbsp;&nbsp;&nbsp;<a onClick="loadCustomer()">Load</a>
              </th>
              <?php }
			  else{?>
              <a onClick="addCustomer()"><img src="img/icons/essen/16/plus.png">New</a>&nbsp;&nbsp;&nbsp;<a onClick="loadCustomer()">Replace</a>
              </th>
              <?php  }?>
            
          </div>
          <?php 
			$customer = $billing->getCustomer();
			?>
          <input type="hidden" name="customerId" id="customerId" value="<?php echo $customer[0] ?>"/>
          <table class="table table-striped table-detail">
            <tr>
              <th>Customer Name:</th>
              <td style="vertical-align:top" id="customerName"><?php echo $customer[1] ?></td>
            </tr>
            <tr>
              <th>Phone: </th>
              <td style="vertical-align:top" id="customerPhone"><?php echo $customer[2] ?></td>
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
            <th width="53%" onClick="$('#MitemName').focus()"><input id="MitemName" type="text" class="billText MitemName"  autocomplete="off" placeholder="Item">
              </td>
            <th width="5%" onClick="$('#MitemQty').focus()"> <input id="MitemQty" type="text" class="billText tCenter MitemQty" placeholder="Qty" autocomplete="off">
              </td>
            <th width="10%"> <select id="MitemType" style="width:100%; height:25px">
                
              </select>
            </th>
            <th width="12%" onClick="$('#MitemAmnt').focus()"> <input id="MnetAmnt" type="hidden" class="MnetAmnt">
              <input id="MitemAmnt" type="text" class="billTextRight tRight MitemAmnt" placeholder="Amount" readonly autocomplete="off" >
            </th>
            <th width="12%"> <input id="MitemDis" type="text" class="billTextRight tRight MitemDis" placeholder="Additional Discount"  autocomplete="off" >
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
              <th colspan="3" rowspan="6"> 
             <span style="font-size:15px;">
              <font color="#FF0000">F1</font> - Print, 
              <font color="#FF0000">F2</font> - Save, 
              <font color="#FF0000">F3</font> - Cancel, 
              <font color="#FF0000">F4</font> - Discount, 
              <font color="#FF0000">F5</font> - Payment, 
              <font color="#FF0000">F6</font> - Cash Entry
            </span><hr>
              Tax Mode: <?php 
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
			?>,
            Round Off Mode: <?php 
					if($billing->getRoundoffMode()==0)
					{
						?>
                        <font color="#FF0000">Lower Round</font>
                        <?php
					}
					else if($billing->getRoundoffMode()==1)
					{
						?>
                       <font color="#FF0000">Upper Round</font>
                        <?php
					}
					else
					{
						?>
						<font color="#FF0000">Auto Round</font>
                        <?php
					}
			?><hr>
<?php
					if(!isset($_POST['loadBill']))
					{
						?>
                <button type="submit" name="Save" id="bill_save" class="btn btn-green2 btn-small" onClick="saveBill()">
                <h4><img src="img/icons/essen/16/billing.png"> Save </h4>
                </button>
                &nbsp;&nbsp;&nbsp;
                <button type="submit" name="Print" id="bill_print" class="btn btn-blue4 btn-small">
                <h4><img src="img/icons/essen/16/print.png"> Print </h4>
                </button>
                &nbsp;&nbsp;&nbsp;
                <button type="submit" name="Cancel" id="bill_cancel"  class="btn btn-red4 btn-small">
                <h4><img src="img/icons/essen/16/order-192.png"> Cancel </h4>
                </button>
                &nbsp;&nbsp;&nbsp; <a class="btn btn-warning  btn-small" id="bill_discount"  data-toggle="modal" href="#Discount">
                <h4><img src="img/icons/essen/16/bestseller.png"> Discount F4</h4>
                </a>
                <div class="modal hide" id="Discount">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3>Discount</h3>
                  </div>
                  <div class="modal-body">
                    <p>
                    <table class="table table-striped userprofile">
                      <tr>
                        <td>% Value</td>
                        <td><input type="text" class="text" name="discountPerc" id="discountPerc" onKeyUp="calc_discount_amnt(this.value)">
                        <script type="text/javascript">
						function calc_discount_amnt(val)
						{
							calcItemNet();
						}
						</script>
                        </td>
                      </tr>
                      <tr>
                        <td>Amount</td>
                        <td><input type="text" class="text" name="discountAmnt" id="discountAmnt"></td>
                      </tr>
                      <tr>
                        <td>Reason</td>
                        <td><textarea name="discountReason" id="discountReason"></textarea></td>
                      </tr>
                    </table>
                    </p>
                  </div>
                  <div class="modal-footer"> <a href="#" class="btn" data-dismiss="modal" onClick="clearDiscount()">Clear</a> <a href="#" class="btn btn-blue4" data-dismiss="modal">Save changes</a> </div>
                </div>
                <?php
					}
					else
					{
						?>
                <input type="hidden" name="loadedBillNo" value="<?php echo  $billing->getCurrentBill(); ?>" />
                <button type="submit" name="SaveL" id="bill_save" class="btn btn-green2 btn-small" onClick="saveBill()">
                <h4><img src="img/icons/essen/16/billing.png"> Save </h4>
                </button>
                &nbsp;&nbsp;&nbsp;
                <button type="submit" name="PrintL" id="bill_print" class="btn btn-blue4 btn-small">
                <h4><img src="img/icons/essen/16/print.png"> Print </h4>
                </button>
                &nbsp;&nbsp;&nbsp;
                 <a   class="btn btn-red4 btn-small" data-toggle="modal" href="#cancelReasonModel" id="cancelMe bill_cancel"><h4><img src="img/icons/essen/16/order-192.png"> Cancel </h4></a>
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
          
          &nbsp;&nbsp;&nbsp; <a class="btn btn-warning  btn-small" id="bill_discount"  data-toggle="modal" href="#DiscountL">
          <h4><img src="img/icons/essen/16/bestseller.png"> Discount</h4>
          </a>
          <div class="modal hide" id="DiscountL">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">×</button>
              <h3>Discount</h3>
            </div>
            <div class="modal-body">
              <p>
                <?php 
					$discount = $billing->getDiscount();
					
				?>
              <table class="table table-striped userprofile">
             	 <tr>
                        <td>% Value</td>
                        <td><input type="text" class="text" name="discountPerc" id="discountPerc" onKeyUp="calc_discount_amnt(this.value)"  value="<?php echo $discount[2] ?>">
                       <script type="text/javascript">
						function calc_discount_amnt(val)
						{
							calcItemNet();
						}
						</script>
                        </td>
                      </tr>
                <tr>
                  <td>Amount</td>
                  <td><input type="text" class="text" name="discountAmnt" id="discountAmnt" value="<?php echo $discount[0] ?>"></td>
                </tr>
                <tr>
                  <td>Reason</td>
                  <td><textarea name="discountReason" id="discountReason"><?php echo $discount[1] ?></textarea></td>
                </tr>
              </table>
                </p>
              
            </div>
            <div class="modal-footer"> <a href="#" class="btn" data-dismiss="modal" onClick="clearDiscount()">Clear</a> <a href="#" class="btn btn-blue4" data-dismiss="modal">Save changes</a> </div>
          </div>
          <?php						
					}
					?>
              &nbsp;&nbsp;&nbsp;
             <button type="button" onClick="openPayment()" id="bill_payment"  class="btn btn-red4 btn-small">
                <h4><img src="img/icons/essen/16/credit-card.png"> Payment </h4>
           	 </button> &nbsp;&nbsp;&nbsp;
             <button   class="btn btn-blue4 btn-small" id="bill_cash"  type="button"  data-toggle="modal" data-backdrop="static" href="#cash_entry">
             <h4><img src="img/icons/essen/16/credit-card.png"> Cash </h4>
             </button>
             <div class="modal hide fade" id="cash_entry">
              <div class="modal-header">
                <button class="close" data-dismiss="modal">X</button>
                <h3>Cash</h3>
              </div>
              <div class="modal-body" style="min-height:500px;">
                <?php 
                include"classes/cash.php";
                ?>
              </div>
            
            </div>
            </th>
            <th align="right" class="borserlbr bgcolor">Discount</th>
            <th class="borserlbr bgcolor"><input id="other_discount" type="text" class="billTextRight tRight amnt" readonly></th>
            <th><input id="netdiscount" type="text" class="billTextRight tRight"  readonly></th>
          </tr>
          <tr>
            <th align="right" class="borserlbr bgcolor">Total</th>
            <th class="borserlbr bgcolor"><input name="total" id="total" type="text" class="billTextRight tRight"  readonly></th>
            <th></th>
          </tr>
          <tr>
            <th align="right" class="borserlbr bgcolor">Tax</th>
            <th class="borserlbr bgcolor"><input name="tax" id="tax" type="text" class="billTextRight tRight amnt" readonly></th>
            <th></th>
          </tr>
          <tr>
            <th align="right" class="borserlbr bgcolor" style="vertical-align:top; height:10px;">Net</th>
            <th class="borserlbr bgcolor" style="vertical-align:top; font-weight:bold" align="right"> <input name="net" id="net" type="text" class="billTextRight tRight amnt" autocomplete="off"  />
            </th>
            <th></th>
          <tr>
            <th align="right" class="borserlbr bgcolor">Recieved</th>
            <th class="borserlbr bgcolor">
            <input name="recieved" id="recieved" type="text" class="billTextRight tRight amnt" value="<?php echo floatval($billing->getRecieved()) ?>" autocomplete="off" />
            </th>
            <th></th>
          </tr>
          <tr>
            <th align="right" class="borserlbr bgcolor">Returned</th>
            <th class="borserlbr bgcolor">
            <input name="returned" id="returned" type="text" class="billTextRight tRight amnt" value="<?php echo floatval($billing->getReturned()); ?>"  autocomplete="off" />
            </th>
            <th></th>
          </tr>
        </table>
      </div>
    </div>
    <div class="span2"></div>
   	<!--<div class="span11">
      <div class="box">
        <div class="itemsList">
          <table border="0" width="100%" cellspacing="5" cellpadding="3" class="table table-striped ">
            <tr>
              <th>
			  <?php 
			  //	$products = $billing->getProductList();
			  	//echo count($products) 
			  ?>PID</th>
              <th></th>
              <th>Prcs Date</th>
              <th>Name</th>
              <th>Stock</th>
              <th>Vat(%)</th>
              <th>Amnt <b><del>&#2352;</del></b></th>
              <th>VAT Amnt <b><del>&#2352;</del></b></th>
              <th></th>
            </tr>
            <?php
				/*foreach($products as $key=>$data)
				{
					?>
            <tr class="searchResult1" style="border-bottom:#CCC 1px dashed;" onClick="addFromTray('<?php echo $key ?>')" >
              <td align="center"><?php echo $data['pid'] ?></td>
              <td align="center"><img src="<?php echo $data['photo'] ?>" width="30"></td>
              <td align="center"><?php echo $data['p_date'] ?></td>
              <td align="left" style="padding-left:10px; word-break:break-all; width:150px;"><b><?php echo $data['name'] ?></b></td>
              <td align="right" style="padding-right:10px;"><?php echo $data['available']." ".$data['type'] ?></td>
              <td align="right" style="padding-right:10px;"><?php echo $data['vat'] ?></td>
              <td align="right" style="padding-right:10px;"><?php echo $mysql->currency($data['unit_price']); ?></td>
              <td align="right" style="padding-right:10px;"><?php 
			  echo $mysql->currency($data['unit_price']+($data['unit_price']*$data['vat']/100)); ?></td>
              <td><a href="product_view.php?id=<?php echo $data['pd_id'] ?>">view</a></td>
            </tr>
            <?php
				}*/
				?>
          </table>
        </div>
      </div>
    </div>-->
    </div>
    
  </form>
</div>
  <?php include"footer.php"; ?>