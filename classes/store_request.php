<?php
class billing extends mysql
{
	private $billno,$tax,$tax_mode,$order = array(),$oldOrders = array(),
			$productsList = array(),$customerId,$discount=0,$round_off=0,$discountReason,
			$lockPending = array(),$paymentPending = array(),$mode,$customerData = array(),$type = array(),
			$recieved,$returned;
	public function loadCurrentBill()
	{
		$sql = "select
					sd.cur_sto_req_no,
					sd.tax,
					sd.tax_mode,
					sd.round_off
				from 
					shop_detail as sd
				;";
		$rst = $this->execute($sql);
		$rst = mysqli_fetch_array($rst[0]);
		$this->billno =  $rst['cur_sto_req_no'];
		$this->tax =  $rst['tax'];
		$this->tax_mode = $rst['tax_mode'];
		$this->round_off = $rst['round_off'];
		?>
        <script type="text/javascript">
        round_off = <?php echo $this->round_off ?>
        </script>
        <?php
		if($this->tax_mode==0)
		{
			$this->tax=0.0;
		}
	}
	public function setBillNo($billno)
	{
		$this->billno = $billno;
	}
	public function loadProduct($rid,$pd_id,$pid,$name,$uprice,$type,$avail,$p_date,$vat,$photo)
	{
		$this->productsList[$rid]['name']=$name;
		$this->productsList[$rid]['pid']=$pid;
		$this->productsList[$rid]['pd_id']=$pd_id;
		$this->productsList[$rid]['unit_price']=$uprice;
		$this->productsList[$rid]['type']=$type;
		$this->productsList[$rid]['available']=$avail;
		$this->productsList[$rid]['p_date']=$p_date;
		$this->productsList[$rid]['vat']=$vat;
		$this->productsList[$rid]['photo']=$photo;
	}
	public function getProductList()
	{
		return $this->productsList;
	}
	public function setConvertion()
	{
		?>
        <script type="text/javascript">
        var productType = [];		
        </script>
        <?php
		$sql = "select c.`group`,c.name,c.`using` from conversion as c where c.`is`=1 and c.`using`=1 order by c.`group`;
				select c.`group`,c.name,c.val,c.`using` from conversion as c where c.`is`=1 order by c.`group`,c.`grade`;";
		$rst = $this->execute($sql);
		$grade = array();
		while($r = mysqli_fetch_array($rst[0]))
		{
			$grade[$r['group']] = $r['name'];
		}
		while($r = mysqli_fetch_array($rst[1]))
		{
			$this->type[$r['name']][$grade[$r['group']]] = $r['val'];
			?>
            <script type="text/javascript">		
            productType.push({ from:"<?php echo $r['name'] ?>" , to:"<?php echo $grade[$r['group']] ?>" , val:"<?php echo $r['val'] ?>"});
			</script>
            <?php
		}
	}
	public function typeConvert($pType,$typ,$qty,$amnt)
	{
		$this->setConvertion();
		if($pType != $typ){
				$amnt =  ($amnt)/$this->type[$typ][$pType];		
		}
		return $amnt;
	}
	public function setUnclosed()
	{
		$sql = "select 	
					GROUP_CONCAT(bl.bill_no)'bills',
					GROUP_CONCAT(bl.`lock`)'lock',
					GROUP_CONCAT(bl.trans_close)'trans'
				from 	
					store_request as bl
				where 
					(
					bl.`lock`=0
					or
					bl.trans_close=0
					)
					and
					bl.`is`=1
					and
					bl.`status`!='c'
				group by
					bl.`is`";
	  	$rst = $this->execute($sql);
		$rst = mysqli_fetch_array($rst[0]);
		$bills = explode(",",$rst['bills']);
		$lock = explode(",",$rst['lock']);
		$trans = explode(",",$rst['trans']);
		foreach($bills as $id=>$bill)
		{
			if($lock[$id]==0)
			$this->lockPending[$bill]['lock'] = $lock[$id];
			if($trans[$id]==0)
			$this->paymentPending[$bill]['trans'] = $trans[$id];
		}
	}
	public function getUnLocked()
	{
		return $this->lockPending;
	}
	public function getUnPaid()
	{
		return $this->paymentPending;
	}
	public function getPayIncomplete()
	{
	}
	public function getTax()
	{
		return $this->tax;
	}
	public function getTaxMode()
	{
		return $this->tax_mode;
	}
	public function getCurrentBill()
	{
		return $this->billno;
	}
	public function setCustomer($cid)
	{
		$this->customerId = $cid;
	}
	public function setPayAmount($recieved,$returned)
	{
		$this->recieved=$recieved;
		$this->returned=$returned;
	}
	public function getRecieved()
	{
		return $this->recieved;
	}
	public function getReturned()
	{
		return $this->returned;
	}
	public function setDiscount($amnt,$reason)
	{
		$this->discount = $amnt;
		$this->discountReason = $reason;
	}
	public function loadDiscount($billno)
	{
		$sql = "select bl.discount,bl.discount_reason from store_request as bl where bl.wastage='".$billno."'";
		$rst = $this->execute($sql);
		$r = mysqli_fetch_array($rst[0]);
		$this->discount = $r['discount'];
		$this->discountReason = $r['discount_reason'];
	}
	public function loadCustomer($billno)
	{
		$sql = "select cs.id,cs.name,cs.phone,cs.address from store_request as bl,dealer as cs where bl.customer_id=cs.id and bl.bill_no='".$billno."'";
		$rst = $this->execute($sql);
		$r = mysqli_fetch_array($rst[0]);
		$this->customerData[] = $r['id'];
		$this->customerData[] = $r['name'];
		$this->customerData[] = $r['phone'];
		$this->customerData[] = $r['address'];
	}
	public function getCustomer()
	{
		return $this->customerData;
	}
	public function getDiscount()
	{
		$tmp = array();
		$tmp[] = $this->discount;
		$tmp[] = $this->discountReason;
		return $tmp;
	}
	public function setOrders($pId,$qty,$type,$discount)
	{			
		$this->order[$pId]['pid'] = $this->productsList[$pId]['pid'];
		$this->order[$pId]['name'] = $this->productsList[$pId]['name'];
		$this->order[$pId]['unit_price'] = $this->productsList[$pId]['unit_price'];
		$this->order[$pId]['vat'] = $this->productsList[$pId]['vat'];
		$this->order[$pId]['qty'] = $qty;
		$this->order[$pId]['type'] = $type;
		$amnt = $qty*$this->typeConvert($this->productsList[$pId]['type'],$type,$qty,$this->productsList[$pId]['unit_price']);
		$tot = $amnt+($amnt/100)*$this->productsList[$pId]['vat'];
		$this->order[$pId]['amnt'] = $tot;
		$this->order[$pId]['discount']=$discount;
	}
	public function updateorders($pRowId,$pId,$qty,$type,$discount)
	{
		$this->oldOrders[$pRowId]['qty'] = $qty;
		$this->oldOrders[$pRowId]['pid'] = $pId;
		$amnt = $qty*$this->typeConvert($this->productsList[$pId]['type'],$type,$qty,$this->productsList[$pId]['unit_price']);
		$tot = $amnt+($amnt/100)*$this->productsList[$pId]['vat'];
		$this->oldOrders[$pRowId]['type'] =$type ;
		$this->oldOrders[$pRowId]['amnt'] =$tot ;
		$this->oldOrders[$pRowId]['discount']=$discount;
	}
	public function getOrders($billNo)
	{
		$sql = "select 
					od.id,
					od.pid,
					od.product_id,
					od.product_name,
					od.qty,
					od.`type`,
					pd.`type`'basetype',
					od.vat,
					od.unit_price,
					od.actual_amnt,
					od.recieved_amnt,
					od.discount 
				from 
					store_request_order as od,
					store_products as pd
				where 
					od.bill_no='".$billNo."' 
					and
					pd.id=od.product_id
					and 
					od.`is`=1;";
		$sql .= "select bl.id,bl.recieved_amount,bl.returned_amount from store_request as bl where bl.bill_no='".$billNo."' LIMIT 1;";
		$rst = $this->execute($sql);
		while($r = mysqli_fetch_array($rst[0]))
		{
			$this->order[$r['pid']]['id'] = $r['id'];
			$this->order[$r['pid']]['pid'] = $r['product_id'];
			$this->order[$r['pid']]['name'] = $r['product_name'];
			$this->order[$r['pid']]['unit_price'] = $r['unit_price'];
			$this->order[$r['pid']]['vat'] = $r['vat'];
			$this->order[$r['pid']]['qty'] = $r['qty'];
			$this->order[$r['pid']]['type'] = $r['type'];
			$this->order[$r['pid']]['basetype'] = $r['basetype'];
			$this->order[$r['pid']]['amnt'] = $r['actual_amnt'];
			$this->order[$r['pid']]['discount']= $r['discount'];
		}
		$r = mysqli_fetch_array($rst[1]);
		$this->recieved=$r['recieved_amount'];
		$this->returned=$r['returned_amount'];
	}
	public function setMode($mode)
	{
		$this->mode = $mode;	
	}
	public function processNewOrders()
	{
		$sql = '';
		$billno = $this->getCurrentBill();
		$nxtBill  = $billno;
		$actualAmnt = $billamnt = $tax_amount =  0.0;
		foreach($this->order as $id=>$id1)
		{
			$stock=0;
			$tax_amount += $this->order[$id]['qty']*$this->order[$id]['unit_price'];
			$actualAmntTmp = $this->order[$id]['amnt'];
			$actualAmnt += $actualAmntTmp;
			$recievedTmp = $this->order[$id]['amnt']-$this->order[$id]['discount'];
			$billamnt += $recievedTmp;
			$stock = $this->order[$id]['qty']/$this->type[$this->order[$id]['type']][$this->productsList[$id]['type']];
			$tempSql[] = "('".$billno."','".$this->order[$id]['pid']."', ".$id.", '".$this->order[$id]['name']."', ".$this->order[$id]['qty'].", '".$this->order[$id]['type']."', ".$this->order[$id]['vat'].", ".$this->order[$id]['unit_price'].", ".$actualAmntTmp." , ".$recievedTmp.", ".$this->order[$id]['discount'].",".$stock.", '1')";
			$orderData = 1;
			$sql .= "UPDATE `store_stock` SET `qty`=`qty`-".$stock." WHERE  `id`=".$id." LIMIT 1;";
			$this->productsList[$id]['available'] = $this->productsList[$id]['available']-$stock;
		}
		$tax_amount = ($tax_amount/100)*$this->tax;
		$actualAmnt += $tax_amount;
		$billamnt -=$this->discount;
		$billamnt += $tax_amount;
		$billamnt_r = $billamnt;
		if($this->round_off==1)
		{
			$billamnt = ceil($billamnt);
		}
		elseif($this->round_off==2)
		{
			$billamnt = floor($billamnt);
		}
		elseif($this->round_off==0)
		{
			$billamnt = round($billamnt);
		}
		$roundoff = $billamnt-$billamnt_r;
		if($orderData)
		{
		$sql .= "INSERT INTO `store_request_order` (`bill_no`, `product_id`,`pid`, `product_name`, `qty`, `type`, `vat`, `unit_price`, `actual_amnt`, `recieved_amnt`, `discount`,`stock_reduction`, `ip`) VALUES ".implode(",",$tempSql).";";
		$sql .= "INSERT 
					INTO `store_request` 
					(`bill_no`, `customer_id`, `date`, `tot_products`, `tax`, `tax_amnt`, `actual_amount`,`bill_amount`,`round_off`, `ip`) 
					VALUES 
					('".$billno."', ".$this->customerId.", '".date('Y-m-d')."', ".count($this->order).", $this->tax, ".$tax_amount.", ".$actualAmnt.", ".$billamnt.",'".$roundoff."', '1');
				UPDATE `store_request` SET `status`='".$this->mode."', `flow`=concat(`flow`,'".$this->mode."') WHERE  `id`=(select LAST_INSERT_ID()) LIMIT 1;
				UPDATE `shop_detail` SET `cur_sto_req_no`='".(++$nxtBill)."';";
		$rst = $this->execute($sql);
		$this->loadCurrentBill();
		}
		else
		{
			unset($_POST);
			?>
<script type="text/javascript">
            alert('Cannot Save With Empty Orders');
            </script>
<?php
		}
	}
	public function processOldOrders()
	{
		$sql = '';
		$oldIds = array();
		$billno = $this->getCurrentBill();
		$nxtBill  = $billno;
		$actualAmnt = $billamnt = $tax_amount =  0.0;
		$sqlTmp = "select GROUP_CONCAT(od.id)'id' from store_request_order as od where od.bill_no='".$billno."' and od.`is`=1 group by od.bill_no";
		$rst = $this->execute($sqlTmp);
		$oldBillIds = mysqli_fetch_array($rst[0]);
		$oldBillIds = explode(",",$oldBillIds['id']);
		foreach($this->order as $id=>$id1)
		{
			$stock=0;
			$tax_amount += $this->order[$id]['qty']*$this->order[$id]['unit_price'];
			$actualAmntTmp = $this->order[$id]['amnt'];
			$actualAmnt += $actualAmntTmp;
			$recievedTmp = $this->order[$id]['amnt']-$this->order[$id]['discount'];
			$billamnt += $recievedTmp;
			$stock = $this->order[$id]['qty']/$this->type[$this->order[$id]['type']][$this->productsList[$id]['type']];
			$tempSql[] = "('".$billno."','".$this->order[$id]['id']."', ".$id.", '".$this->order[$id]['name']."', ".$this->order[$id]['qty'].", '".$this->order[$id]['type']."', ".$this->order[$id]['vat'].", ".$this->order[$id]['unit_price'].", ".$actualAmntTmp." , ".$recievedTmp.", ".$this->order[$id]['discount'].",".$stock.", '1')";		
			$orderData =1;
		}
		foreach($this->oldOrders as $id=>$data)
		{
			$oldIds[] = $id;
			$tax_amount += $this->oldOrders[$id]['qty']*$this->oldOrders[$id]['unit_price'];
			$actualAmntTmp = $this->oldOrders[$id]['amnt'];
			$actualAmnt += $actualAmntTmp;
			$recievedTmp = $this->oldOrders[$id]['amnt']-$this->oldOrders[$id]['discount'];
			$billamnt += $recievedTmp;
			$pid = $this->oldOrders[$id]['pid'];
			$stock = $this->oldOrders[$id]['qty']/$this->type[$this->oldOrders[$id]['type']][$this->productsList[$pid]['type']];
			$sql .= "UPDATE 
					`store_stock` as s,store_request_order as od
					 SET s.`qty`=s.`qty`+od.stock_reduction
					WHERE s.`pid`=od.product_id and od.id='".$id."';";
			$sql .="UPDATE `store_request_order` SET `qty`=".$this->oldOrders[$id]['qty'].", `actual_amnt`=".$actualAmntTmp.", `recieved_amnt`=".$recievedTmp.", `discount`=".$this->oldOrders[$id]['discount'].",`stock_reduction`=".$stock." WHERE  `id`=".$id." LIMIT 1;";
			$sql .= "UPDATE 
					`store_stock` as s,store_request_order as od
					 SET s.`qty`=s.`qty`-od.stock_reduction
					WHERE s.`pid`=od.product_id and od.id='".$id."';";
		}
		$remove = array_diff($oldBillIds,$oldIds);
		$tax_amount = ($tax_amount/100)*$this->tax;
		$actualAmnt += $tax_amount;
		$billamnt -=$this->discount;
		$billamnt += $tax_amount;
		$billamnt_r = $billamnt;
		if($this->round_off==1)
		{
			$billamnt = ceil($billamnt);
		}
		elseif($this->round_off==2)
		{
			$billamnt = floor($billamnt);
		}
		elseif($this->round_off==0)
		{
			$billamnt = round($billamnt);
		}
		$roundoff = $billamnt-$billamnt_r;
		if($orderData)
		{
		$sql .= "INSERT INTO `store_request_order` (`bill_no`, `product_id`,`pid`, `product_name`, `qty`, `type`, `vat`, `unit_price`, `actual_amnt`, `recieved_amnt`, `discount`,`stock_reduction`, `ip`) VALUES ".implode(",",$tempSql).";";
		}
		$sql .="UPDATE `store_request` SET `status`='".$this->mode."', `flow`=concat(`flow`,'".$this->mode."'),`customer_id`=".$this->customerId.",`actual_amount`=".$actualAmnt.",`bill_amount`=".$billamnt.",`round_off`='".$roundoff."' WHERE  `bill_no`='".$billno."' LIMIT 1;";
		if(count($remove)>0)
		{
			foreach($remove as $id)
			{
				$sql .= "UPDATE `store_stock` SET `qty`=`qty`+(select od.stock_reduction from store_request_order as od where od.id=".$id.") WHERE  `id`=(select od.product_id from store_request_orders as od where od.id=".$id.") LIMIT 1;";
			}
			$sql .="UPDATE `store_request_order` SET `is`=0 WHERE  `id` IN (".implode(',',$remove).");";	
		}
		$rst = $this->execute($sql);
		$this->loadCurrentBill();
	}
	public function cancelBill($billno,$reason)
	{
		$sql ="UPDATE `store_request` SET `status`='".$this->mode."', `flow`=concat(`flow`,'".$this->mode."'),`cancel`=1,cancel_reason='".$reason."' WHERE  `bill_no`='".$billno."' LIMIT 1;";
		$sql .="select od.product_id,od.stock_reduction from store_request_order as od where od.bill_no='".$billno."' and od.`is`=1;";
		$rst = $this->execute($sql);
		$sql = '';
		while($r = mysqli_fetch_array($rst[0]))
		{
			$sql .= "UPDATE `store_stock` SET `qty`=`qty`+".$r['stock_reduction']." WHERE  `id`=".$r['product_id']." LIMIT 1;";
		}
		$rst = $this->execute($sql);
	}
	public function savemodeOptions($billno)
	{
		?>
        <center><div class="savemodeOption" style="width:50%">
        <div class="alert alert-block alert-success savemodeOptions"> <a class="close" data-dismiss="alert" href="#">×</a>
          <form action="billing.php" method="post">
            <input type="hidden" name="LastbillNo" value="<?php echo $billno ?>"  />
            <table align="center">
              <tr>
                <td><h4 class="alert-heading"><font size="+1"><?php echo $billno ?></font> Successfully Updated!</h4></td>
                <td><button type="submit" name="CloseBill" class="btn btn-blue4" onClick="saveBill()"> Close Bill
                  <h4>F2</h4>
                  </button>
                  &nbsp;&nbsp;&nbsp;
                  <button type="submit" name="CompletePay" class="btn btn-red4"> Complete Payment
                  <h4>F1</h4>
                  </button></td>
              </tr>
            </table>
          </form>
            </div>
            <div id="saveHide" style="display:none"><a href="#" onclick="openLastBillStatus()">Open Last Bill Status</a></div>
        </div>
        </center>
        <script type="text/javascript">
		tim  = setTimeout(hideSaveOptions,5000);
		opened=1;
		$(".close").click(function(e) {
            $(".saveHide").css("display","none");
			opened=0;
        });
		function hideSaveOptions()
		{
			if(opened)
			{
			$(".savemodeOptions").hide(1000);
			$("#saveHide").css("display","block");
			}
		}
		function openLastBillStatus()
		{
			$("#saveHide").css("display","none");
			$(".savemodeOptions").show(500);
			tim  = setTimeout(hideSaveOptions,5000);
		}
        </script>
     <?php
    }
    public function closeBill($bNo)
    {
        $sql  = "update store_request set `lock`=1,`trans_close`=1 where bill_no='".$bNo."';";
		$sql .= "INSERT INTO `store_stock_using` (`date`,`sid`,`pid`, `name`, `qty`, `type`) 
				(select
					NOW(), 
					s.id,
					s.product_id,
					s.product_name,
					s.qty,
					s.`type` 
				from 
					store_request_order as s 
				where s.bill_no='".$bNo."' and s.`is`=1);";
        $rst = $this->execute($sql);
		?>
        <center><div class="savemodeOption" style="width:50%">
        <div class="alert alert-block alert-success savemodeOptions"> <a class="close" data-dismiss="alert" href="#">×</a>
          <h3><a href="wastage_detail.php?billno=<?php echo $bNo ?>"><?php echo $bNo ?></a> Locked Successfully</h3>
            </div>
            <div id="saveHide" style="display:none"><a href="#" onclick="openLastBillStatus()">Open Last Bill Status</a></div>
        </div>
        </center>
        <script type="text/javascript">
		tim  = setTimeout(hideSaveOptions,5000);
		opened=1;
		$(".close").click(function(e) {
            $(".saveHide").css("display","none");
			opened=0;
        });
		function hideSaveOptions()
		{
			if(opened)
			{
			$(".savemodeOptions").hide(1000);
			$("#saveHide").css("display","block");
			}
		}
		function openLastBillStatus()
		{
			$("#saveHide").css("display","none");
			$(".savemodeOptions").show(500);
			tim  = setTimeout(hideSaveOptions,5000);
		}
        </script>
        <?php
    }
	public function setNewBill()
	{
			for($i=1;$i<=5;$i++)
			{
			?>
				<tr class="itemSet">
				  <td align="center" style="font-weight:bold;" class="Isno"><?php echo $i; ?></td>
				  <td><input id="itemName" name="item[]" type="text" class="billText itemName"  autocomplete="off" readonly></td>
				  <td><input id="itemQty" name="qty[]" type="text" title="qty" class="billText tCenter itemQty" autocomplete="off"></td>
				  <td class="itemType tRight"></td>
				  <td><input id="itemId" name="itemId[]" type="hidden" class="itemId">
						<input id="itemVat" name="itemVat[]" type="hidden" class="itemVat">
						<input id="itemTypeT" name="itemType[]" type="hidden" class="itemTypeT">
						<input id="netAmnt" name="netAmnt[]" type="hidden" class="netAmnt">
						<input id="amnt" name="amount[]" type="text" class="billTextRight tRight amnt" autocomplete="off" readonly ></td>
				  <td><input id="itemDis" name="itemDis[]" type="text" class="billTextRight tRight itemDis" autocomplete="off"></td>
				  <td><i class="icon-trash removeItem" style="cursor:pointer"></i></td>
			</tr>
			<?php
			}
	}
	public function loadBill()
	{
		$i=0;
		foreach($this->order as $pId=>$data)
		{
			if($data['name']['id']!='')
			{
			?>
				<tr class="itemSet">
				  <td align="center" style="font-weight:bold;" class="Isno">
				  <?php echo ++$i; ?>
                  </td>
				  <td><input id="itemName" name="item[]" type="text" class="billText itemName" value="<?php echo $data['name'] ?>"  autocomplete="off" readonly></td>
				  <td><input id="itemQty" name="qty[]" type="text" title="qty" class="billText tCenter itemQty" value="<?php echo $data['qty'] ?>"  autocomplete="off"></td>
				  <td class="itemType tRight"><?php echo $data['type'] ?></td>
				  <td>
                  <input id="itemRowId" name="itemRowId[]" type="hidden" class="itemRowId" value="<?php echo $data['id'] ?>">
                  <input id="itemId" name="itemId[]" type="hidden" class="itemId"  value="<?php echo $pId ?>">
						<input id="itemVat" name="itemVat[]" type="hidden" class="itemVat"  value="<?php echo $data['vat'] ?>">
						<input id="itemTypeT" name="itemType[]" type="hidden" class="itemTypeT"  value="<?php echo $data['type'] ?>">
						<input id="netAmnt" name="netAmnt[]" type="hidden" class="netAmnt"  value="<?php echo $data['unit_price'] ?>">
						<input id="amnt" name="amount[]" type="text" class="billTextRight tRight amnt" autocomplete="off" readonly  
                        value="<?php echo $data['actual_amnt']  ?>"></td>
				  <td><input id="itemDis" name="itemDis[]" type="text" class="billTextRight tRight itemDis" autocomplete="off" 
                  value="<?php echo$data['discount']  ?>"></td>
				  <td><i class="icon-trash removeItem" style="cursor:pointer"></i></td>
			</tr>
			<?php
			}
		}
	}
}
$billing = new billing;
$billing->loadCurrentBill();
$billing->setConvertion();
?>
