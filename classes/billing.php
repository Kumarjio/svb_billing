<?php
class billing extends mysql
{
	private $billno,$tax,$tax_mode,$billing_src,$order = array(),$oldOrders = array(),
			$productsList = array(),$customerId,$discount=0,$discount_per = 0,$round_off=0,$discountReason,
			$lockPending = array(),$paymentPending = array(),$mode,$customerData = array(),$type = array(),
			$recieved,$returned,$cash = array(),$charges = array(),$gst=array();
	public function init()
	{
		$sql = "select
					sd.tax,
					sd.tax_mode,
					sd.round_off,
					sd.billing_src
				from
					shop_detail as sd
				;";
		$rst = $this->execute($sql);
		$rst = mysqli_fetch_array($rst[0]);
		$this->tax =  $rst['tax'];
		$this->tax_mode = $rst['tax_mode'];
		$this->round_off = $rst['round_off'];
		$this->billing_src = $rst['billing_src'];
		?>
<script type="text/javascript">
        round_off = <?php echo $this->round_off ?>
        </script>
<?php
		if($this->tax_mode==0)
		{
			$this->tax=0.0;
		}
		$this->setConvertion();
	}
	public function loadCurrentBill()
	{
		$this->billno = $this->queueBill();
	}
	public function queueBill()
	{
		$lock = 0;
		do
		{
			$sql = "select
						l.`lock`
					from
						locks as l
					where
						l.`for`='billing'";
			$rst = $this->execute($sql);
			$lock = mysqli_fetch_array($rst[0]);
			$lock = $lock['lock'];
		}while($lock == 1);
		$sql = "UPDATE `locks` SET `lock`=1 WHERE `for`='billing';";
		$rst = $this->execute($sql);
		$sql = "
				select
					q.bill_no
				from
					bill_que as q
				where
					q.user = '".$_SESSION['user_id']."'
					and
					q.ok=0
				order by
					q.id asc
				limit 1";
		$rst = $this->execute($sql);
		if(mysqli_num_rows($rst[0])==0)
		{
			$sql = "select
						max(q.bill_no)'bill_no'

					from
						bill_que as q
					where
						`is`=1
					limit 1";
			$rst = $this->execute($sql);
			$rst = mysqli_fetch_array($rst[0]);
			$this->billno =  $rst['bill_no'];
			$sql = "select
						q.bill_no
					from
						bill_que as q
					where
						q.bill_no = '".$this->billno."'
					limit 1";
			$rst = $this->execute($sql);
			if(mysqli_num_rows($rst[0])==1)
			{
				$this->billno++;
			}
			if(intval(substr($this->billno,3,3)) == 0)
				$this->billno++;
			$sql = "INSERT INTO `bill_que` (`user`, `bill_no`) VALUES ('".$_SESSION['user_id']."', '".$this->billno."');";
			$rst = $this->execute($sql);
		}
		else
		{
			$r = mysqli_fetch_array($rst[0]);
			$this->billno = $r['bill_no'];
		}
		$sql = "UPDATE `locks` SET `lock`=0 WHERE `for`='billing'";
		$rst = $this->execute($sql);
		return $this->billno;
	}
	public function updateQueue($billno)
	{
		$sql = "UPDATE `bill_que` set `ok` = 1,`deque_time` = NOW() where `bill_no` = '".$billno."';";
		$rst = $this->execute($sql);
	}
	public function setBillNo($billno)
	{
		$this->billno = $billno;
	}
	public function loadProduct($rid,$pd_id,$pid,$name,$uprice,$type,$avail,$p_date,$vat,$photo,$gst,$hsm_code)
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
		$this->productsList[$rid]['gst']=$gst;
		$this->productsList[$rid]['hsm_code']=$gst;
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
					bill as bl
				where
					bl.`lock`=0
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
	public function getBillingMode()
	{
		return $this->billing_src;
	}
	public function getRoundoffMode()
	{
		return $this->round_off;
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
		$this->recieved=floatval($recieved);
		$this->returned=floatval($returned);
	}
	public function getRecieved()
	{
		return $this->recieved;
	}
	public function getReturned()
	{
		return $this->returned;
	}
	public function setDiscount($amnt,$reason,$percentage)
	{
		$this->discount = $amnt;
		$this->discountReason = $reason;
		$this->discount_per = $percentage;
	}
	public function loadDiscount($billno)
	{
		$sql = "select bl.discount_per,bl.discount,bl.discount_reason from bill as bl where bl.bill_no='".$billno."'";
		$rst = $this->execute($sql);
		$r = mysqli_fetch_array($rst[0]);
		$this->discount = $r['discount'];
		$this->discountReason = $r['discount_reason'];
		$this->discount_per = $r['discount_per'];
	}
	public function loadCustomer($billno)
	{
		$sql = "select cs.id,cs.name,cs.phone,cs.address,cs.tin_no,cs.cst_no,cs.gst_no from bill as bl,customers as cs where bl.customer_id=cs.id and bl.bill_no='".$billno."'";
		$rst = $this->execute($sql);
		$r = mysqli_fetch_array($rst[0]);
		$this->customerData[] = $r['id'];
		$this->customerData[] = $r['name'];
		$this->customerData[] = $r['phone'];
		$this->customerData[] = $r['address'];
		$this->customerData[] = $r['tin_no'];
		$this->customerData[] = $r['cst_no'];
		$this->customerData[] = $r['gst_no'];
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
		$tmp[] = $this->discount_per;
		return $tmp;
	}
	public function setOrders($pId,$qty,$type,$discount)
	{


		$this->order[$pId]['id'] = $this->productsList[$pId]['pd_id'];
		$this->order[$pId]['name'] = addslashes($this->productsList[$pId]['name']);
		$this->order[$pId]['unit_price'] = $this->productsList[$pId]['unit_price'];
		$this->order[$pId]['vat'] = $this->productsList[$pId]['vat'];
		$this->order[$pId]['qty'] = $qty;
		$this->order[$pId]['type'] = $type;
		$amnt = $qty*$this->typeConvert($this->productsList[$pId]['type'],$type,$qty,$this->productsList[$pId]['unit_price']);
		$dis_amnt = $amnt-($qty*$this->typeConvert($this->productsList[$pId]['type'],$type,$qty,$this->productsList[$pId]['unit_price']-(($this->productsList[$pId]['unit_price']/100)*$discount)));
		$this->order[$pId]['gst'] = json_encode($this->productsList[$pId]['gst']);
		$this->order[$pId]['gst_price'] = ((($amnt-$dis_amnt)/100)*array_sum($this->productsList[$pId]['gst']));
		$this->order[$pId]['amnt'] = $amnt+(($amnt/100)*$this->productsList[$pId]['vat'])+((($amnt)/100)*array_sum($this->productsList[$pId]['gst']));
		$this->order[$pId]['discount_amnt'] = $amnt-$dis_amnt+$this->order[$pId]['gst_price'];
		$this->order[$pId]['discount']=$discount;
	}
	public function updateorders($pRowId,$pId,$qty,$type,$discount)
	{
		$this->oldOrders[$pRowId]['qty'] = $qty;
		$this->oldOrders[$pRowId]['pid'] = $pId;
		$this->oldOrders[$pRowId]['type'] =$type ;
		$this->oldOrders[$pRowId]['unit_price'] = $this->productsList[$pId]['unit_price'];
		$amnt = $qty*$this->typeConvert($this->productsList[$pId]['type'],$type,$qty,$this->productsList[$pId]['unit_price']);
		$dis_amnt = $amnt-($qty*$this->typeConvert($this->productsList[$pId]['type'],$type,$qty,$this->productsList[$pId]['unit_price']-(($this->productsList[$pId]['unit_price']/100)*$discount)));
		$this->oldOrders[$pRowId]['gst'] = json_encode($this->productsList[$pId]['gst']);
		$this->oldOrders[$pRowId]['gst_price'] = ((($amnt-$dis_amnt)/100)*array_sum($this->productsList[$pId]['gst']));
		$this->oldOrders[$pRowId]['amnt'] =$amnt+($amnt/100)*$this->productsList[$pId]['vat']+((($amnt)/100)*array_sum($this->productsList[$pId]['gst']));
		$this->oldOrders[$pRowId]['discount_amnt'] =$amnt-$dis_amnt+$this->oldOrders[$pRowId]['gst_price'];
		$this->oldOrders[$pRowId]['discount']=$discount;


	}
	public function getOrders($billNo)
	{
		if(!$this->billing_src)
		{
			$sql = "select
						od.oid, od.product_id,
						od.product_name, od.product_code,
						od.qty, od.`type`,
						pd.`type`'basetype', od.vat, od.gst,od.gst_price,
						od.unit_price, od.oactual,
						od.orecieved, od.odiscount
					from
						bill_orders as od,
						stock as pd
					where
						od.bill_no='".$billNo."'
						and
						pd.id=od.product_id ;";
		}else
		{
			$sql = "select
						od.oid, od.product_id,
						od.product_name, od.product_code,
						od.qty, od.`type`,
						pd.`type`'basetype', od.vat, od.gst,od.gst_price,
						od.unit_price, od.oactual,
						od.orecieved, od.odiscount
				from
					bill_orders as od,
					product as pd
				where
					od.bill_no='".$billNo."'
					and
					pd.id=od.product_id;";
		}
		$sql .= "select bl.id,bl.recieved_amount,bl.returned_amount from bill as bl where bl.bill_no='".$billNo."' LIMIT 1;";
		$rst = $this->execute($sql);
		while($r = mysqli_fetch_array($rst[0]))
		{
			$this->order[$r['product_id']]['id'] = $r['oid'];
			$this->order[$r['product_id']]['code'] = $r['product_code'];
			$this->order[$r['product_id']]['name'] = $r['product_name'];
			$this->order[$r['product_id']]['unit_price'] = $r['unit_price'];
			$this->order[$r['product_id']]['vat'] = $r['vat'];
			$this->order[$r['product_id']]['gst'] = $r['gst'];
			$this->order[$r['product_id']]['gst_price'] = $r['gst_price'];
			$this->order[$r['product_id']]['qty'] = $r['qty'];
			$this->order[$r['product_id']]['type'] = $r['type'];
			$this->order[$r['product_id']]['basetype'] = $r['basetype'];
			$this->order[$r['product_id']]['amnt'] = $r['oactual'];
			$this->order[$r['product_id']]['discount']= $r['odiscount'];
		}
		$r = mysqli_fetch_array($rst[1]);
		$this->recieved=$r['recieved_amount'];
		$this->returned=$r['returned_amount'];
	}
	public function setMode($mode)
	{
		$this->mode = $mode;
	}
	public function getOrderNo($bill_no)
	{
		$sql = "SELECT  max(kot)+1'order_no' from bill_kot where bill_no='".$bill_no."'";
		$rst = $this->execute($sql);
		$order_no = mysqli_fetch_array($rst[0]);
		$order_no = $order_no['order_no'];
		return intval($order_no);
	}
	public function processNewOrders()
	{
		$this->loadCurrentBill();
		$sql = "set @kot = '';
				SELECT  max(kot)+1 into @kot from bill_kot;";
		$billno = $this->getCurrentBill();
		$nxtBill  = $billno;
		$actualAmnt = $billamnt = $tax_amount =  0.0;
		$order_no = $this->getOrderNo($billno);
		foreach($this->order as $id=>$id1)
		{
			$stock=0;
			$actualAmntTmp = $this->order[$id]['amnt'];
			$actualAmnt += $actualAmntTmp;
			$recievedTmp = $this->order[$id]['discount_amnt'];
			$tax_amount += $recievedTmp+$this->order[$id]['gst_price'];
			$billamnt += $recievedTmp;
			$stock = $this->order[$id]['qty']/$this->type[$this->order[$id]['type']][$this->productsList[$id]['type']];
			$tempSql[] = "('".$billno."',@kot,'".$this->order[$id]['id']."', ".$id.", '".$this->order[$id]['name']."', '".$this->order[$id]['qty']."', '".$this->order[$id]['type']."', '".$this->order[$id]['vat']."', '".$this->order[$id]['unit_price']."', '".$actualAmntTmp."' , '".$recievedTmp."', '".$this->order[$id]['discount']."','".$stock."', '1','".addslashes($this->order[$id]['gst'])."','".$this->order[$id]['gst_price']."')";
			$tempKot[] = "('".$billno."',@kot,'".$this->order[$id]['id']."', '".$this->order[$id]['name']."', ".$this->order[$id]['qty'].", '".$this->order[$id]['type']."')";
			$orderData =1;
			if(!$this->getBillingMode())
			{
				$sql .= "UPDATE `stock` SET `available`=`available`-".$stock." WHERE  `id`=".$id." LIMIT 1;";
				$this->productsList[$id]['available'] = $this->productsList[$id]['available']-$stock;
			}else{
				$sql .= "UPDATE `product` SET `available`=`available`-".$stock." WHERE  `id`=".$this->order[$id]['id']." LIMIT 1;";
			}

			?>
<script type="text/javascript">

			 id = <?php echo $id ?>;
			 for(i=0;i<products.length;i++)
				{
					if(products[i].id==id)
					{
						products[i].available = products[i].available-<?php echo $stock ?>;
						break;
					}
				}
			</script>
<?php
		}
		if($orderData)
		{
			$billamnt -= $this->discount;
			$actualAmnt += ($actualAmnt/100)*$this->tax;
			$tax_amount = ($billamnt/100)*$this->tax;
			$billamnt += $tax_amount;
			$charges_amnt = $this->charges_entry($actualAmnt);
			$billamnt += $charges_amnt;
			$actualAmnt += $charges_amnt;
			$billamnt_r = $billamnt;
			if($this->round_off==0)
				$billamnt = floor($billamnt);
			elseif($this->round_off==1)
				$billamnt = ceil($billamnt);
			elseif($this->round_off==2)
				$billamnt = round($billamnt);
			$roundoff = $billamnt-$billamnt_r;
			$sql .= "INSERT INTO `bill_order` (`bill_no`,`kot`,`pid`, `product_id`, `product_name`, `qty`, `type`, `vat`, `unit_price`, `actual_amnt`, `recieved_amnt`, `discount`,`stock_reduction`, `ip`,`gst`,`gst_price`) VALUES ".implode(",",$tempSql).";";
			$sql .= "INSERT INTO `bill_kot` (`bill_no`,`kot`, `product_id`, `product_name`, `qty`, `type`) VALUES ".implode(",",$tempKot).";";
			$sql .= "INSERT
						INTO `bill`
						(`bill_no`, `customer_id`,`biller_id`, `date`, `tot_products`, `tax`, `tax_amnt`,`charges_amnt`, `actual_amount`,`bill_amount`,`round_off`, `recieved_amount`,`returned_amount`,`discount_per`, `discount`, `discount_reason`, `ip`)
						VALUES
						('".$billno."', '".$this->customerId."','".$_SESSION['user_id']."', '".date('Y-m-d')."', ".count($this->order).", '".$this->tax."',
						'".$tax_amount."','".$charges_amnt."',
						'".$actualAmnt."', '".$billamnt."','".$roundoff."','".$this->recieved."',
						'".$this->returned."','".$this->discount_per."', '".$this->discount."', '".$this->discountReason."', '1');
					UPDATE `bill` SET `status`='".$this->mode."', `flow`=concat(`flow`,'".$this->mode."') WHERE  `id`=(select LAST_INSERT_ID()) LIMIT 1;";
			$rst = $this->execute($sql);
			$this->cash_save(0);
			$this->updateQueue($this->billno);
			$this->loadCurrentBill();
			return 1;
		}
		else
		{
			unset($_POST);
			?>
<script type="text/javascript">
                 alert('Cannot Save With Empty Orders');
             </script>
<?php
			return 0;
		}
	}
	public function processOldOrders()
	{
		$sql = "SET GLOBAL max_allowed_packet=99999999999;
				set @kot = '';
				SELECT  max(kot)+1 into @kot from bill_kot;";
		$oldIds = array();
		$billno = $this->getCurrentBill();
		$nxtBill  = $billno;
		$actualAmnt = $billamnt = $tax_amount =  0.0;
		$sqlTmp = "select GROUP_CONCAT(od.id)'id' from bill_order as od where od.bill_no='".$billno."' and od.`is`=1 group by od.bill_no";
		$rst = $this->execute($sqlTmp);
		$oldBillIds = mysqli_fetch_array($rst[0]);
		$oldBillIds = explode(",",$oldBillIds['id']);
		$order_no = $this->getOrderNo($billno);

		foreach($this->order as $id=>$id1)
		{
			$stock=0;
			$actualAmntTmp = $this->order[$id]['amnt'];
			$actualAmnt += $actualAmntTmp;
			$recievedTmp = $this->order[$id]['discount_amnt'];
			$tax_amount += $recievedTmp+$this->order[$id]['gst_price'];
			$billamnt += $recievedTmp;

			$stock = $this->order[$id]['qty']/$this->type[$this->order[$id]['type']][$this->productsList[$id]['type']];
			$tempSql[] ="('".$billno."',@kot,'".$this->order[$id]['id']."', ".$id.", '".$this->order[$id]['name']."', '".$this->order[$id]['qty']."',
						'".$this->order[$id]['type']."', '".$this->order[$id]['vat']."', '".$this->order[$id]['unit_price']."',
						'".$actualAmntTmp."' , '".$recievedTmp."', '".$this->order[$id]['discount']."',
						'".$stock."', '1','".addslashes($this->order[$id]['gst'])."','".$this->order[$id]['gst_price']."')";
			$tempKot[] = "('".$billno."',@kot,'".$this->order[$id]['id']."', '".$this->order[$id]['name']."',
						'".$this->order[$id]['qty']."', '".$this->order[$id]['type']."')";
			$orderData =1;
			if(!$this->getBillingMode())
			{
				$sql .= "UPDATE `stock` SET `available`=`available`-".$stock." WHERE  `id`=".$id." LIMIT 1;";
				$this->productsList[$id]['available'] = $this->productsList[$id]['available']-$stock;
			}else{
				$sql .= "UPDATE `product` SET `available`=`available`-".$stock." WHERE  `id`=".$this->order[$id]['id']." LIMIT 1;";
			}
		}
		foreach($this->oldOrders as $id=>$data)
		{
			$oldIds[] = $id;
			$actualAmntTmp = $this->oldOrders[$id]['amnt'];
			$actualAmnt += $actualAmntTmp;
			$recievedTmp = $this->oldOrders[$id]['discount_amnt'];
			$tax_amount += $recievedTmp+$this->oldOrders[$id]['gst_price'];
			$billamnt += $recievedTmp;

			$pid = $this->oldOrders[$id]['pid'];
			$stock = $this->oldOrders[$id]['qty']/$this->type[$this->oldOrders[$id]['type']][$this->productsList[$pid]['type']];
			$stock = intval($stock);
			if(!$this->getBillingMode())
			{
				$sql .= "UPDATE
							`stock` as s,bill_order as od
						 SET
						 	s.`available`=s.`available`+od.stock_reduction-".$stock."
						 WHERE
						 	s.`id`=od.product_id and od.id='".$id."';";
			}else{
				$sql .= "UPDATE
							`product` as s,bill_order as od
						 SET
						 	s.`available`=(s.`available`+od.stock_reduction)-".$stock."
						 WHERE
						 	od.bill_no='".$billno."' and s.`id`=od.product_id and od.`is`=1 and od.pid='".$this->oldOrders[$id]['pid']."';";

			}
			$sql .="UPDATE `bill_order` SET `qty`=".$this->oldOrders[$id]['qty'].", `actual_amnt`=".$actualAmntTmp.", `recieved_amnt`=".$recievedTmp.", `discount`=".$this->oldOrders[$id]['discount'].",`stock_reduction`=".$stock.",`gst`='".addslashes($this->oldOrders[$id]['gst'])."',`gst_price`='".$this->oldOrders[$id]['gst_price']."' WHERE  `id`=".$id." LIMIT 1;";
		}
		$remove = array_diff($oldBillIds,$oldIds);
		$billamnt -= $this->discount;
		$actualAmnt += ($actualAmnt/100)*$this->tax;
		$tax_amount = ($billamnt/100)*$this->tax;
		$billamnt += $tax_amount;
		$charges_amnt = $this->charges_entry($actualAmnt,'u');
		$billamnt += $charges_amnt;
		$actualAmnt += $charges_amnt;
		$billamnt_r = $billamnt;
		if($this->round_off==0)
			$billamnt = floor($billamnt);
		elseif($this->round_off==1)
			$billamnt = ceil($billamnt);
		elseif($this->round_off==2)
			$billamnt = round($billamnt);
		$roundoff = $billamnt-$billamnt_r;
		if($orderData)
		{
			$sql .= "INSERT INTO `bill_order` (`bill_no`,`kot`,`pid`, `product_id`, `product_name`, `qty`, `type`, `vat`, `unit_price`, `actual_amnt`, `recieved_amnt`, `discount`,`stock_reduction`, `ip`,`gst`,`gst_price`) VALUES ".implode(",",$tempSql).";";
			$sql .= "INSERT INTO `bill_kot` (`bill_no`,`kot`, `product_id`, `product_name`, `qty`, `type`) VALUES ".implode(",",$tempKot).";";
		}
		$rst = $this->execute($sql);
		$sql ="UPDATE `bill` SET `status`='".$this->mode."', `flow`=concat(`flow`,'".$this->mode."'),`customer_id`=".$this->customerId.",`actual_amount`=".$actualAmnt.",`bill_amount`=".$billamnt.",`tax_amnt`='".$tax_amount."',`round_off`='".$roundoff."',`charges_amnt`='".$charges_amnt."', `recieved_amount`=".$this->recieved.", `returned_amount`=".$this->returned.",`discount_per`=".$this->discount_per.",`discount`=".$this->discount.", `discount_reason`='".$this->discountReason."' WHERE  `bill_no`='".$billno."' LIMIT 1;";
		if(count($remove)>0)
		{
			if(!$this->getBillingMode())
			{
			foreach($remove as $id)
			{

				$sql .= "UPDATE `stock` SET `available`=`available`+(select od.stock_reduction from bill_order as od where od.id=".$id." and od.is=1) WHERE  `id`=(select od.product_id from bill_order as od where od.id=".$id." and od.is=1) LIMIT 1;";
			}
			}else{
				foreach($remove as $id)
				{
					$sql .= "UPDATE
							`product` as s,bill_order as od
						 SET
						 	s.`available`=(s.`available`+od.stock_reduction)
						 WHERE
						 	od.bill_no='".$billno."' and s.`id`=od.product_id and od.pid=(select od.product_id from bill_order as od where od.id=".$id.")  and od.is=1;";
				}
			}
			$sql .="UPDATE `bill_order` SET `is`=0 WHERE  `id` IN (".implode(',',$remove).");";
		}
		$rst = $this->execute($sql);
		$this->cash_save(1);
		$this->updateQueue($this->billno);
		$this->loadCurrentBill();
	}
	public function cancelBill($billno,$reason)
	{
		$sql ="UPDATE `bill`
			 	SET
					`status`='".$this->mode."', `flow`=concat(`flow`,'".$this->mode."'),
					`cancel`=1,cancel_reason='".$reason."',
					`lock`=1,`trans_close`=1
				WHERE
					`bill_no`='".$billno."'
				LIMIT 1;";
		$sql .="select od.product_id,od.stock_reduction from bill_order as od where od.bill_no='".$billno."' and od.`is`=1;";
		$rst = $this->execute($sql);
		$sql = '';
		while($r = mysqli_fetch_array($rst[0]))
		{
			$sql .= "UPDATE `stock` SET `available`=`available`+".$r['stock_reduction']." WHERE  `id`=".$r['product_id']." LIMIT 1;";
		}
		$rst = $this->execute($sql);
		$this->updateQueue($billno);
	}
	public function charges_entry($net,$qua='i')
	{
		$tot_rate = 1;
		$sql = "select * from shop_charges as sh where sh.`is`=1";
		$addi = $this->execute($sql);
		$count = 1;
		if(mysqli_num_rows($addi[0])>0)
		{
			while($ar = mysqli_fetch_array($addi[0]))
			{
				$rate = 0;
				if($ar['type']=='checkbox')
				{
					if(strtolower($_POST['charges'.$ar['id']]) =='on')
						$value = 1;
					else
						$value = 0;
				}
				else
				{
					$value = floatval($_POST['charges'.$ar['id']]);
				}
				if($qua=='u')
				{
					$sql = "UPDATE `bill_charges`
							SET `value`='".$value."', `rate_status`='".$ar['rate_status']."', `rate`='".$rate."'
							WHERE  `bill_no`='".$this->billno."' and `c_id`='".$ar['id']."';";
				}
				else
				{
					$sql = "INSERT INTO `bill_charges`
							(`bill_no`, `c_id`, `value`,`rate_status`,`rate`)
							VALUES
							('".$this->billno."', '".$ar['id']."', '".$value."','".$ar['rate_status']."','".$rate."');";
				}

				$this->execute($sql);
					$tot_rate = $value;
			}
		}
		return $tot_rate;
	}
	public function set_charges()
	{
		$sql = "select
					s.`id`,
					s.name,
					s.`type`,
					s.type_source,
					s.rate,
					s.rate_type,
					s.rate_status,
					b.value,
					b.rate'bill_rate'
				from
					bill_charges as b,
					shop_charges as s
				where
					s.id=b.c_id and b.bill_no='".$this->billno."'
					and
					b.`is`=1";
		$rst = $this->execute($sql);
		while($r = mysqli_fetch_array($rst[0]))
		{
			$this->charges[$r['id']] = $r['value'];
		}
		return $this->charges;
	}
	public function cash_save($up)
	{
		$sql = '';
		foreach($_POST['cash'] as $id=>$cash)
		{
			$note = $_POST['note'][$id];
			if($up)
			$sql .="UPDATE `cash` SET  `no`='".$cash."' WHERE  `for`='".$this->billno."' and `amount` = '".$note."' and `from`=2;";
			else
			$sql .= "INSERT INTO `cash` (`from`,`for`, `amount`, `no`) VALUES (2,'".$this->billno."', '".$note."', '".$cash."');";
		}
		if($sql!='')
		{
			$this->execute($sql);
		}
	}
	public function getcash()
	{
		$sql = "select * from cash where `for`='".$this->billno."' and `is`=1 order by `id`";
		$rst = $this->execute($sql);
		while($r = mysqli_fetch_array($rst[0]))
		{
			$this->cash[$r['amount']]=$r['no'];
		}
		return $this->cash;
	}
	public function savemodeOptions($billno)
	{
		?>

<center>
  <div class="savemodeOption" style="width:50%">
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
        $sql = "update bill set `lock`=1,`status`='l',`flow`=concat(`flow`,'l') where bill_no='".$bNo."';";
		$sql .="INSERT INTO `bill_payment`
				(`bill_no`, `date`, `bill_amount`,`cur_balance` , `recieved`, `returned`,`paid`)
				(select
					bl.bill_no,
					bl.date,
					bl.bill_amount,
					bl.bill_amount,
					bl.recieved_amount,
					bl.returned_amount,
					if((bl.recieved_amount-bl.returned_amount)!=bl.bill_amount,0,1)
				from bill as bl where bl.bill_no='".$bNo."' and bl.recieved_amount!=0 LIMIT 1);";
		$sql .="update bill as p set p.trans_close=1 where p.bill_amount<=p.recieved_amount-p.returned_amount and p.bill_no='".$bNo."';";
        $rst = $this->execute($sql);
		?>
<center>
  <div class="savemodeOption noprint" style="width:50%">
    <div class="alert alert-block alert-success savemodeOptions"> <a class="close" data-dismiss="alert" href="#">×</a>
      <h3><a href="bill_detail.php?billno=<?php echo $bNo ?>"><?php echo $bNo ?></a> Locked Successfully</h3>
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
		$this->updateQueue($bNo);
    }
    public function closePayment($bNo)
    {
        $sql = "update bill set `lock`=1,`status`='l',`flow`=concat(`flow`,'l') where bill_no='".$bNo."'";
        $rst = $this->execute($sql);
    }
	public function setNewBill()
	{
		for($i=1;$i<=5;$i++)
		{		?>
<tr class="itemSet">
  <td align="center" style="font-weight:bold;" class="Isno"><?php echo $i; ?></td>
  <td><input id="itemName" name="item[]" type="text" class="billText itemName"  autocomplete="off"  tabindex="-100" readonly></td>
  <td><input id="itemQty" name="qty[]" type="text" title="qty" class="billText tCenter itemQty" autocomplete="off"></td>
  <td class="itemType tRight"></td>
  <td><input id="itemId" name="itemId[]" type="hidden" class="itemId">
    <input id="itemVat" name="itemVat[]" type="hidden" class="itemVat">
    <input id="itemTypeT" name="itemType[]" type="hidden" class="itemTypeT">
    <input id="netAmnt" name="netAmnt[]" type="hidden" class="netAmnt">
    <input id="amnt" name="amount[]" type="text" class="billTextRight tRight amnt" autocomplete="off" tabindex="-100" readonly ></td>
  <td><input id="itemGst" name="itemGst[]" type="text" class="billTextRight tRight itemGst" autocomplete="off"  readonly></td>
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
  <td align="center" style="font-weight:bold;" class="Isno"><?php echo ++$i; ?></td>
  <td><input id="itemName" name="item[]" type="text" class="billText itemName" value="<?php echo $data['code']."-".$data['name'] ?>"  autocomplete="off" tabindex="-100" readonly></td>
  <td><input id="itemQty" name="qty[]" type="text" title="qty" class="billText tCenter itemQty" value="<?php echo $data['qty'] ?>"  autocomplete="off"></td>
  <td class="itemType tRight"><?php echo $data['type'] ?></td>
  <td><input id="itemRowId" name="itemRowId[]" type="hidden" class="itemRowId" value="<?php echo $data['id'] ?>">
    <input id="itemId" name="itemId[]" type="hidden" class="itemId"  value="<?php echo $pId ?>">
    <input id="itemVat" name="itemVat[]" type="hidden" class="itemVat"  value="<?php echo $data['vat'] ?>">
    <input id="itemTypeT" name="itemType[]" type="hidden" class="itemTypeT"  value="<?php echo $data['type'] ?>">
    <input id="netAmnt" name="netAmnt[]" type="hidden" class="netAmnt"  value="<?php echo $data['unit_price'] ?>">
    <input id="amnt" name="amount[]" type="text" class="billTextRight tRight amnt" autocomplete="off" tabindex="-100" readonly
                        value="<?php echo $data['actual_amnt']  ?>"></td>
  <td><input id="itemGst" name="itemGst[]" type="text" class="billTextRight tRight itemGst" autocomplete="off" readonly
		value="<?php echo $data['gst_price']  ?>"></td>
  <td><input id="itemDis" name="itemDis[]" type="text" class="billTextRight tRight itemDis" autocomplete="off"
                  value="<?php echo $data['discount']  ?>"></td>
  <td><i class="icon-trash removeItem" style="cursor:pointer"></i></td>
</tr>
<?php
			}
		}
	}
	public function save_option($billno)
	{
		?>
        <center><div class="savemodeOption noprint" style="width:50%">
                <div class="alert alert-block alert-success savemodeOptions"> <a class="close" data-dismiss="alert" href="#">×</a>
                    <table align="center">
                      <tr>
                        <td><h4 class="alert-heading"><font size="+1"><a href="bill_detail.php?billno=<?php echo $billno ?>">
                        <?php echo $billno ?></a></font> Printed Successfully!</h4></td>
                      </tr>
                    </table>
                    </div>
                </div>
        </center>
		<script type="text/javascript">
		tim  = setTimeout(hideSaveOptions,5000);
		function hideSaveOptions()
		{
			$(".savemodeOptions").hide(1000);
		}
        </script>
        <?php
	}
	public function printBill($bill_no,$duplicate=0)
	{
		?>
        <script type="text/javascript">
		  function print_bill(bill_no,dup)
		  {
			//print_no = prompt("Enter No of Prints You Want to Print Now","1");
			print_no = 1;
			for(i=1;i<=print_no;i++)
			{
			var myWindow=window.open('bill_printing.php?bill_no='+bill_no+'&dup='+dup+'','','width=1,height=1');
			//myWindow.document.close();
			//myWindow.focus();

			}
		  }
		  print_bill('<?php echo $bill_no ?>',<?php echo $duplicate ?>);
		</script>
        <?php
	}
	public function print_gst_output($billno,$duplicate=0)
	{
		$billData = "select
						bo.bill_no,
						date_format(bo.`date`,'%d-%m-%Y')'date',
						bo.actual_amount,
						bo.recieved_amount,
						bo.discount,
						bo.product_name,
						bo.product_code,
						bo.hsm_code,
						bo.gst,
						bo.gst_price,
						bo.qty,
						bo.`type`,
						bo.unit_price,
						bo.vat,
						bo.oactual,
						bo.orecieved ,
						bo.odiscount,
						bo.tax_amnt,
						bo.round_off,
						bo.charges_amnt,
						bo.bill_amount
					from
						bill_orders as bo
					where
						bo.bill_no='".$billno."';";
		$billData .="UPDATE `bill` SET `print_count`=`print_count`+1, `last_time`=NOW(),`status`='p',`flow`=concat(`flow`,'p') WHERE  bill_no='".$billno."' LIMIT 1;";
		$billData .="INSERT INTO `bill_print` (`bill_no`,`type`, `inserted_user`) VALUES ('".$billno."','".$duplicate."', '".$_SESSION['user_id']."');";
		$rst = $this->execute($billData);
		$this->loadCustomer($billno);
		$customer = $this->getCustomer();
		$porders = array();
		while($r = mysqli_fetch_array($rst[0]))
		{
			$pbillno = $r['bill_no'];
			$pdate = $r['date'];
			$actual_amount = $r['actual_amount'];
			$precieved = $r['recieved_amount'];
			$pdiscount = $r['discount'];
			$tax_amount = $r['tax_amnt'];
			$round_off = $r['round_off'];
			$charges_amount = $r['charges_amnt'];
			$bill_amount = $r['bill_amount'];
			$porders[$r['product_name']]['qty'] = $r['qty'];
			$porders[$r['product_name']]['type'] = $r['type'];
			$porders[$r['product_name']]['vat'] = $r['vat'];
			$porders[$r['product_name']]['product_code'] = $r['product_code'];
			$porders[$r['product_name']]['hsm_code'] = $r['hsm_code'];
			$porders[$r['product_name']]['gst'] = json_decode($r['gst'],true);
			$porders[$r['product_name']]['gst_price'] = $r['gst_price'];
			$porders[$r['product_name']]['unit_price'] = $r['unit_price']-(($r['unit_price']/100)*$r['odiscount']);
			$porders[$r['product_name']]['actual'] = $r['oactual'];
			$porders[$r['product_name']]['recieved'] = $r['orecieved'];
			$porders[$r['product_name']]['discount'] = $r['odiscount'];
		}
		?>
        <div id="bill_out">
        <div style="clear:both; height:0px;"></div>
        <div id="customer">
          <div id="customer-title"><span style="font-size:16px;">To:</span>
            <p style="padding-left:25px;"><strong><span style="font-size:16px;"><?php
			 if($customer[1]!='')
				echo $customer[1].",";
			?></span><br />
              <span style="font-size:14px;"><?php
					if($customer[3]!='')
					{
						$add = explode(",",$customer[3]);
						if($add[0]!='')
							echo $add[0];
						if($add[1]!='')
							echo ",<br>".$add[1];
						if($add[2]!='')
							echo ",<br>".$add[2];
						if($add[3]!='')
							echo ",<br>".$add[3];
						if($add[4]!='')
							echo ",<br>".$add[4];
						if($add[5]!='')
							echo ",<br>".$add[5];
						if($add[0]!='')
							echo '.';
					}
					if($customer[2]!='')
						echo '<br>Ph : '.$customer[2];
                    ?></span></strong></p>
          </div>
          <table style="float:right;">
          	<?php
			if($duplicate==1)
			{
				?>
                <tr>
              <td style="border:none;font-size:16px;">Duplicate</td>
              <td style="border:none;"></td>
              <td style="border:none;font-size:16px;"></td>
            </tr>
                <?php
			}
			?>
            <tr>
              <td style="border:none;font-size:16px;">Invoice No.</td>
              <td style="border:none;">:</td>
              <td style="border:none;font-size:16px; text-align:right"><strong><?php echo $pbillno;  ?></strong></td>
            </tr>
            <tr>
              <td style="border:none;font-size:16px;vertical-align:top">Date</td>
              <td style="border:none;vertical-align:top">:</td>
              <td style="border:none;font-size:16px;"><strong><?php echo $pdate;  ?></strong></td>
            </tr>
          </table>
        </div>
        <br />
        <p style="font-size:15px;">GST NO. :<?php echo $customer[6];?><!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Customer CST NO. : --></p>
        <table id="items">
          <tr style="height:10px;">
            <th style="border-top:#000 1px dashed;border-bottom:1px dashed #000; border-left:1px solid #000; font-size:12px;">S.No</th>
            <th style="border-top:#000 1px dashed;border-bottom:1px dashed #000;font-size:12px;">P.No</th>
						<th style="border-top:#000 1px dashed;border-bottom:1px dashed #000;font-size:12px">Product Name</th>
						<th style="border-top:#000 1px dashed;border-bottom:1px dashed #000;font-size:12px;">HSN/SAC</th>
						<th style="border-top:#000 1px dashed;border-bottom:1px dashed #000;font-size:12px;">GST%</th>
            <th style="border-top:#000 1px dashed;border-bottom:1px dashed #000;font-size:12px;">Qty</th>
            <th style="border-top:#000 1px dashed;border-bottom:1px dashed #000;font-size:12px;">Price</th>
            <th style="border-top:#000 1px dashed;border-bottom:1px dashed #000;font-size:12px;">Net</th>
            <th style="border-top:#000 1px dashed;border-bottom:1px dashed #000; border-right:1px solid #000;font-size:12px;">Ps</th>
          </tr>
          <?php
		  $product_net = 0;
		  $gst = array();
		  $tax_sum = array();
          foreach($porders as $prd=>$ord)
          {
			  $prod_rate = number_format($porders[$prd]['unit_price']*$porders[$prd]['qty'],2);
			  $tax_sum['cgst'][$porders[$prd]['gst']['cgst']*2] += $porders[$prd]['gst_price']/2;
			  $tax_sum['sgst'][$porders[$prd]['gst']['sgst']*2] += $porders[$prd]['gst_price']/2;
			  $tax_sum['total'][$porders[$prd]['gst']['sgst']*2] += $porders[$prd]['unit_price']*$porders[$prd]['qty'];
			  $prod_rate = explode(".",$prod_rate);
			  ?>
			  <tr class="item-row">
				<td class="sno" style="border-right:#000 1px solid; border-left:#000 1px solid"><?php echo ++$sno ?></td>
				<td class="pno" style="border-right:#000 1px solid;">&nbsp;<?php echo $porders[$prd]['product_code']; ?>&nbsp;</td>
				<td class="item-name" style="border-right:#000 1px solid;">&nbsp;<?php echo html_entity_decode($prd); ?>&nbsp;</td>
				<td class="hsm" style="border-right:#000 1px solid;">&nbsp;<?php echo $porders[$prd]['hsm_code']; ?>&nbsp;</td>
				<td class="gst" style="border-right:#000 1px solid;"><center>&nbsp;<?php echo array_sum($porders[$prd]['gst']); ?>&nbsp;</center></td>
				<td class="qty" style="border-right:#000 1px solid"><?php echo $porders[$prd]['qty']; ?></td>
				<td class="unit_cost" style="border-right:#000 1px solid"><?php echo number_format($porders[$prd]['unit_price'],2); ?></td>
				<td class="price" style="border-right:#000 1px solid"><?php echo $prod_rate[0]; ?></td>
				<td class="paise" style="border-right:#000 1px solid"><?php echo $prod_rate[1] ?></td>
			  </tr>
			  <?php
			  $product_net += $porders[$prd]['unit_price']*$porders[$prd]['qty'];
			  $ptotal = ($porders[$prd]['unit_price']*$porders[$prd]['qty']);
			  foreach( $porders[$prd]['gst'] as $gstid=>$gvalue){
				  $gst[$gstid] += ($ptotal/100)*$gvalue;
			  }
          }
          if($sno==18)
          {
          for($i=1;$i<=18;$i++)
          {
          ?>
          <tr class="item-row">
            <td class="sno" style="border-right:#000 1px solid; border-left:#000 1px solid"></td>
            <td class="pno" style="border-right:#000 1px solid;"></td>
            <td class="item-name" style="border-right:#000 1px solid;"></td>
						<td class="hsm" style="border-right:#000 1px solid;"></td>
						<td class="gst" style="border-right:#000 1px solid;"></td>
            <td class="qty" style="border-right:#000 1px solid"></td>
            <td class="unit_cost" style="border-right:#000 1px solid"></td>
            <td class="price" style="border-right:#000 1px solid"></td>
            <td class="paise" style="border-right:#000 1px solid"></td>
          </tr>
          <?php
          }
          }
          if($sno<15)
          {
              $tmp =15-$sno;
          for($i=1;$i<=$tmp;$i++)
          {
          ?>
          <tr class="item-row">
            <td class="sno" style="border-right:#000 1px solid; border-left:#000 1px solid"></td>
            <td class="pno" style="border-right:#000 1px solid;"></td>
            <td class="item-name" style="border-right:#000 1px solid;"></td>
						<td class="hsm" style="border-right:#000 1px solid;"></td>
						<td class="gst" style="border-right:#000 1px solid;"></td>
            <td class="qty" style="border-right:#000 1px solid"></td>
            <td class="unit_cost" style="border-right:#000 1px solid"></td>
            <td class="price" style="border-right:#000 1px solid"></td>
            <td class="paise" style="border-right:#000 1px solid"></td>
          </tr>
          <?php
          }
          }
          ?>
          <tr class="noboder">
            <td class="sno" style="border-right:#000 1px solid;border-left:#000 1px solid"></td>
            <td class="pno" style="border-right:#000 1px solid;"></td>
            <td class="item-name" style="border-right:#000 1px solid; border-bottom:#000 1px solid"></td>
						<td class="hsm" style="border-right:#000 1px solid;"></td>
						<td class="gst" style="border-right:#000 1px solid;"></td>
            <td class="qty" style="border-right:#000 1px solid; border-bottom:#000 1px solid"></td>
            <td class="unit_cost" style="border-right:#000 1px solid; border-bottom:#000 1px solid"></td>
            <td class="price" style="border-right:#000 1px solid; border-bottom:#000 1px solid"></td>
            <td class="paise" style="border-right:#000 1px solid; border-bottom:#000 1px solid"></td>
          </tr>
          <tr style="height:10px;">
            <td colspan="5" class="blank" style="border-top:#000 1px solid; border-left:#000 1px solid"></td>
            <td colspan="2" class="total-line" style="border-left:1px #000000 solid;border-right:#000 1px solid">TOTAL</td>
            <td class="total-value" style="border-right:#000 1px solid;text-align:right"><?php
			$product_net = number_format($product_net,2);
			$product_net = explode(".",$product_net);
			echo $product_net[0] ?></td>
            <td class="total-value" style="border-right:1px #000000 solid;text-align:right"><?php
			echo $product_net[1]
			?></td>
          </tr>
          <tr style="height:10px;">
            <td colspan="5" class="blank" style="border-top:none; border-left:#000 1px solid">
				No.of Bundles:<?php
					$csql = "select c.value from bill_charges as c where c.bill_no = '".$billno."' and c.c_id=2";
					$crst = $this->execute($csql);
					$cr   = mysqli_fetch_array($crst[0]);
					echo $cr['value'];?>
			</td>

			<td colspan="2" class="total-line" style="border-left:1px #000000 solid;border-right:#000 1px solid">PACKING</td>
            <td class="total-value" style="border-right:#000 1px solid;text-align:right"><?php
			$charges_amount = number_format($charges_amount,2);
			$charges_amount = explode(".",$charges_amount);
			echo $charges_amount[0] ?></td>
            <td style="border-right:1px #000000 solid; text-align:right"><?php echo $charges_amount[1] ?></td>
          </tr>
		  <?php
		  $product_net = str_replace(",","",$product_net[0].'.'.$product_net[1]);
		  $charges_amount = str_replace(",","",$charges_amount[0].'.'.$charges_amount[1]);
		  $product_net = floatval($product_net)+floatval($charges_amount);
		  $product_net = number_format($product_net,2);
		  $product_net = explode(".",$product_net);
		  $tax_amount = str_replace(",","",$tax_amount);
		  ?>
          <tr style="height:10px;">
            <td colspan="5" class="blank total-line" style="border-top:none; border-left:#000 1px solid"></td>
            <td colspan="2" class="total-line" style="border-left:1px #000000 solid;border-right:#000 1px solid">SGST</td>
            <td class="total-value" style="border-right:#000 1px solid;text-align:right"><?php
			$stax_amount = number_format($gst['sgst'],2);
			$stax_amount = explode(".",$stax_amount);
			echo $stax_amount[0]
			?></td>
            <td class="total-value" style="border-right:1px #000000 solid;text-align:right"><?php echo $stax_amount[1] ?></td>
          </tr>
          <tr style="height:10px;">
            <td colspan="5" class="blank total-line" style="border-top:none; border-left:#000 1px solid"><strong>Amount in Words</strong></td>

            <td colspan="2" class="total-line" style="border-left:1px #000000 solid;border-right:#000 1px solid">CGST</td>
            <td class="total-value" style="border-right:#000 1px solid;text-align:right"><?php
			$stax_amount = number_format($gst['cgst'],2);
			$stax_amount = explode(".",$stax_amount);
			echo $stax_amount[0]
			?></td>
            <td class="total-value" style="border-right:1px #000000 solid;text-align:right"><?php echo $stax_amount[1] ?></td>

          </tr>

		  <?php
		  $product_net = str_replace(",","",$product_net[0].'.'.$product_net[1]);
		  $total = floatval($tax_amount)+floatval($product_net)+floatval($gst['sgst']+$gst['cgst']);
		  $total = number_format($total,2);
		  $total = explode(".",$total);
		  ?>
          <tr style="height:15px;">
            <td colspan="5" class="blank total-line" style="border-bottom:#000 1px solid; border-left:#000 1px solid; font-size:14px;">
            &nbsp;&nbsp;&nbsp;&nbsp;Rupees <?php echo ucwords($this->no_to_words($bill_amount))?> Only.</td>
            <td colspan="2" class="total-line" style="border-top:#000 1px solid; border-left:1px #000000 solid; border-bottom:1px solid #000;border-right:#000 1px solid">GRAND TOTAL</td>
            <td class="balance" style="border-top:#000 1px solid; border-bottom:1px #000000 solid;border-right:#000 1px solid;text-align:right">
				<strong><?php echo number_format($bill_amount)?></strong>
			</td>
            <td style="border-top:#000 1px solid; border-bottom:1px #000000 solid;border-right:1px #000000 solid;text-align:right">00</td>
          </tr>
        </table>
		<br/>
		<table width="100%"><tr><td>
		<table border="1" cellspacing="5" cellpadding="5">
			<tr><th>Tax%</th><th>5%</th><th>12%</th><th>18%</th><th>28%</th><th>Total</th></tr>
			<tr>
				<th>Total</th>
				<th style="text-align:right"><?php echo $this->currency($tax_sum['total'][5]) ?></th>
				<th style="text-align:right"><?php echo $this->currency($tax_sum['total'][12]) ?></th>
				<th style="text-align:right"><?php echo $this->currency($tax_sum['total'][18]) ?></th>
				<th style="text-align:right"><?php echo $this->currency($tax_sum['total'][28]) ?></th>
				<th style="text-align:right"><?php echo $this->currency(array_sum($tax_sum['total'])) ?></th>
			</tr>
			<tr>
				<th>SGST</th>
				<th style="text-align:right"><?php echo $this->currency($tax_sum['sgst'][5]) ?></th>
				<th style="text-align:right"><?php echo $this->currency($tax_sum['sgst'][12]) ?></th>
				<th style="text-align:right"><?php echo $this->currency($tax_sum['sgst'][18]) ?></th>
				<th style="text-align:right"><?php echo $this->currency($tax_sum['sgst'][28]) ?></th>
				<th style="text-align:right"><?php echo $this->currency(array_sum($tax_sum['sgst'])) ?></th>
			</tr>
			<tr>
				<th>CGST</th>
				<th style="text-align:right"><?php echo $this->currency($tax_sum['cgst'][5]) ?></th>
				<th style="text-align:right"><?php echo $this->currency($tax_sum['cgst'][12]) ?></th>
				<th style="text-align:right"><?php echo $this->currency($tax_sum['cgst'][18]) ?></th>
				<th style="text-align:right"><?php echo $this->currency($tax_sum['cgst'][28]) ?></th>
				<th style="text-align:right"><?php echo $this->currency(array_sum($tax_sum['cgst'])) ?></th>
				
			</tr>
		</table>
		</td><td style="vertical-align:top">
		<table style="float:right">
			<tr><td colspan="3" align="center"><u><b>Bank Account Details</b></u></td></tr>
			<tr><td>Bank Name</td> <td>:</td><td> KARUR VYSYA BANK</td></tr>
			<tr><td>Account No.</td> <td>:</td> <td>1152135000002577</td></tr> 
			<tr><td>IFSC</td> <td>:</td> <td>KVBL0001152</td></tr>  
			<tr><td>Branch</td> <td>:</td> <td>KARUR WEST LNS</td></tr> 
		</table><br>
        
		</td></tr></table><br><br>
		<div style="float:right"> For,<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SVB<br />
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Auto Products</strong> </div>
        </div>
        <?php
	}
	public function print_output($billno,$duplicate=0)
	{
		$billData = "select
						bo.bill_no,
						date_format(bo.`date`,'%d-%m-%Y')'date',
						bo.actual_amount,
						bo.recieved_amount,
						bo.discount,
						bo.product_name,
						bo.product_code,
						bo.qty,
						bo.`type`,
						bo.unit_price,
						bo.vat,
						bo.oactual,
						bo.orecieved ,
						bo.odiscount,
						bo.tax_amnt,
						bo.round_off,
						bo.charges_amnt,
						bo.bill_amount
					from
						bill_orders as bo
					where
						bo.bill_no='".$billno."';";
		$billData .="UPDATE `bill` SET `print_count`=`print_count`+1, `last_time`=NOW(),`status`='p',`flow`=concat(`flow`,'p') WHERE  bill_no='".$billno."' LIMIT 1;";
		$billData .="INSERT INTO `bill_print` (`bill_no`,`type`, `inserted_user`) VALUES ('".$billno."','".$duplicate."', '".$_SESSION['user_id']."');";
		$rst = $this->execute($billData);
		$this->loadCustomer($billno);
		$customer = $this->getCustomer();
		$porders = array();
		while($r = mysqli_fetch_array($rst[0]))
		{
			$pbillno = $r['bill_no'];
			$pdate = $r['date'];
			$actual_amount = $r['actual_amount'];
			$precieved = $r['recieved_amount'];
			$pdiscount = $r['discount'];
			$tax_amount = $r['tax_amnt'];
			$round_off = $r['round_off'];
			$charges_amount = $r['charges_amnt'];
			$bill_amount = $r['bill_amount'];
			$porders[$r['product_name']]['qty'] = $r['qty'];
			$porders[$r['product_name']]['type'] = $r['type'];
			$porders[$r['product_name']]['vat'] = $r['vat'];
			$porders[$r['product_name']]['product_code'] = $r['product_code'];
			$porders[$r['product_name']]['unit_price'] = $r['unit_price'];
			$porders[$r['product_name']]['actual'] = $r['oactual'];
			$porders[$r['product_name']]['recieved'] = $r['orecieved'];
			$porders[$r['product_name']]['discount'] = $r['odiscount'];
		}
		?>
        <div id="bill_out">
        <div style="clear:both; height:0px;"></div>
        <div id="customer">
          <div id="customer-title"><span style="font-size:16px;">To:</span>
            <p style="padding-left:25px;"><strong><span style="font-size:16px;"><?php
			 if($customer[1]!='')
				echo $customer[1].",";
			?></span><br />
              <span style="font-size:14px;"><?php
					if($customer[3]!='')
					{
						$add = explode(",",$customer[3]);
						if($add[0]!='')
							echo $add[0];
						if($add[1]!='')
							echo ",<br>".$add[1];
						if($add[2]!='')
							echo ",<br>".$add[2];
						if($add[3]!='')
							echo ",<br>".$add[3];
						if($add[4]!='')
							echo ",<br>".$add[4];
						if($add[5]!='')
							echo ",<br>".$add[5];
						if($add[0]!='')
							echo '.';
					}
					if($customer[2]!='')
						echo '<br>Ph : '.$customer[2];
                    ?></span></strong></p>
          </div>
          <table style="float:right;">
          	<?php
			if($duplicate==1)
			{
				?>
                <tr>
              <td style="border:none;font-size:16px;">Duplicate</td>
              <td style="border:none;"></td>
              <td style="border:none;font-size:16px;"></td>
            </tr>
                <?php
			}
			?>
            <tr>
              <td style="border:none;font-size:16px;">Invoice No.</td>
              <td style="border:none;">:</td>
              <td style="border:none;font-size:16px; text-align:right"><strong><?php echo $pbillno;  ?></strong></td>
            </tr>
            <tr>
              <td style="border:none;font-size:16px;vertical-align:top">Date</td>
              <td style="border:none;vertical-align:top">:</td>
              <td style="border:none;font-size:16px;"><strong><?php echo $pdate;  ?></strong></td>
            </tr>
          </table>
        </div>
        <br />
        <p style="font-size:15px;">Customer TIN NO. :<?php echo $customer[4];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Customer CST NO. : </p>
        <table id="items">
          <tr style="height:10px;">
            <th style="border-top:#000 1px dashed;border-bottom:1px dashed #000; border-left:1px solid #000; font-size:12px;">S.No</th>
            <th style="border-top:#000 1px dashed;border-bottom:1px dashed #000;font-size:12px;">P.No</th>
            <th style="border-top:#000 1px dashed;border-bottom:1px dashed #000;font-size:12px;">Product Name</th>
            <th style="border-top:#000 1px dashed;border-bottom:1px dashed #000;font-size:12px;">Qty</th>
            <th style="border-top:#000 1px dashed;border-bottom:1px dashed #000;font-size:12px;">Price</th>
            <th style="border-top:#000 1px dashed;border-bottom:1px dashed #000;font-size:12px;">Net</th>
            <th style="border-top:#000 1px dashed;border-bottom:1px dashed #000; border-right:1px solid #000;font-size:12px;">Ps</th>
          </tr>
          <?php
		  $product_net = 0;
          foreach($porders as $prd=>$ord)
          {
			  $prod_rate = number_format(($porders[$prd]['recieved']),2);
			  $prod_rate = explode(".",$prod_rate);
          ?>
          <tr class="item-row">
            <td class="sno" style="border-right:#000 1px solid; border-left:#000 1px solid"><?php echo ++$sno ?></td>
            <td class="pno" style="border-right:#000 1px solid;">&nbsp;<?php echo $porders[$prd]['product_code']; ?>&nbsp;</td>
            <td class="item-name" style="border-right:#000 1px solid;">&nbsp;<?php echo html_entity_decode($prd); ?>&nbsp;</td>
            <td class="qty" style="border-right:#000 1px solid"><?php echo $porders[$prd]['qty']; ?></td>
            <td class="unit_cost" style="border-right:#000 1px solid"><?php echo number_format(($porders[$prd]['recieved']/$porders[$prd]['qty']),2); ?></td>
            <td class="price" style="border-right:#000 1px solid"><?php echo $prod_rate[0]; ?></td>
            <td class="paise" style="border-right:#000 1px solid"><?php echo $prod_rate[1] ?></td>
          </tr>
          <?php
		  $product_net += $porders[$prd]['recieved'];
          }
          if($sno==18)
          {
          for($i=1;$i<=18;$i++)
          {
          ?>
          <tr class="item-row">
            <td class="sno" style="border-right:#000 1px solid; border-left:#000 1px solid"></td>
            <td class="pno" style="border-right:#000 1px solid;"></td>
            <td class="item-name" style="border-right:#000 1px solid;"></td>
            <td class="qty" style="border-right:#000 1px solid"></td>
            <td class="unit_cost" style="border-right:#000 1px solid"></td>
            <td class="price" style="border-right:#000 1px solid"></td>
            <td class="paise" style="border-right:#000 1px solid"></td>
          </tr>
          <?php
          }
          }
          if($sno<15)
          {
              $tmp =15-$sno;
          for($i=1;$i<=$tmp;$i++)
          {
          ?>
          <tr class="item-row">
            <td class="sno" style="border-right:#000 1px solid; border-left:#000 1px solid"></td>
            <td class="pno" style="border-right:#000 1px solid;"></td>
            <td class="item-name" style="border-right:#000 1px solid;"></td>
            <td class="qty" style="border-right:#000 1px solid"></td>
            <td class="unit_cost" style="border-right:#000 1px solid"></td>
            <td class="price" style="border-right:#000 1px solid"></td>
            <td class="paise" style="border-right:#000 1px solid"></td>
          </tr>
          <?php
          }
          }
          ?>
          <tr class="noboder">
            <td class="sno" style="border-right:#000 1px solid;border-left:#000 1px solid"></td>
            <td class="pno" style="border-right:#000 1px solid;"></td>
            <td class="item-name" style="border-right:#000 1px solid; border-bottom:#000 1px solid"></td>
            <td class="qty" style="border-right:#000 1px solid; border-bottom:#000 1px solid"></td>
            <td class="unit_cost" style="border-right:#000 1px solid; border-bottom:#000 1px solid"></td>
            <td class="price" style="border-right:#000 1px solid; border-bottom:#000 1px solid"></td>
            <td class="paise" style="border-right:#000 1px solid; border-bottom:#000 1px solid"></td>
          </tr>
          <tr style="height:10px;">
            <td colspan="3" class="blank" style="border-top:#000 1px solid; border-left:#000 1px solid"></td>
            <td colspan="2" class="total-line" style="border-left:1px #000000 solid;border-right:#000 1px solid">TOTAL</td>
            <td class="total-value" style="border-right:#000 1px solid;"><?php
			$product_net = number_format($product_net,2);
			$product_net = explode(".",$product_net);
			echo $product_net[0] ?></td>
            <td class="total-value" style="border-right:1px #000000 solid"><?php
			echo $product_net[1]
			?></td>
          </tr>
          <tr style="height:10px;">
            <td colspan="3" class="blank" style="border-top:none; border-left:#000 1px solid"></td>
            <td colspan="2" class="total-line" style="border-left:1px #000000 solid;border-right:#000 1px solid">VAT(5%)</td>
            <td class="total-value" style="border-right:#000 1px solid;"><?php
			$tax_amount = number_format($tax_amount,2);
			$tax_amount = explode(".",$tax_amount);
			echo $tax_amount[0]
			?></td>
            <td class="total-value" style="border-right:1px #000000 solid"><?php echo $tax_amount[1] ?></td>
          </tr>
          <?php
		  $product_net = str_replace(",","",$product_net[0].'.'.$product_net[1]);
		  $tax_amount = str_replace(",","",$tax_amount[0].'.'.$tax_amount[1]);
		  $total = floatval($tax_amount)+floatval($product_net);
		  $total = number_format($total,2);
		  $total = explode(".",$total);
		  ?>
          <tr style="height:10px;">
            <td colspan="3" class="blank" style="border-top:none; border-left:#000 1px solid"></td>
            <td colspan="2" class="total-line" style="border-left:1px #000000 solid;border-right:#000 1px solid">VAT + TOTAL</td>
            <td class="total-value" style="border-right:#000 1px solid;"><?php echo $total[0]?></td>
            <td class="total-value" style="border-right:1px #000000 solid">
            <?php echo $total[1]?>
            </td>
          </tr>
          <tr style="height:10px;">
            <td colspan="3" class="blank total-line" style="border-top:none; border-left:#000 1px solid;border-right:#000 1px solid"><strong>No. of Bundles</strong>:
			<?php
			$sql = "select
						c.value
					from
						bill_charges as c where c.bill_no='".$billno."' and c.c_id=2";
			$rst = $this->execute($sql);
			$r = mysqli_fetch_array($rst[0]);
			echo $r['value'];
			?></td>
            <td colspan="2" class="total-line" style="border-left:1px #000000 solid;border-right:#000 1px solid">ROUND OFF</td>
            <td style="border-left:none;text-align:right;border-right:#000 1px solid;">
            <?php
			if($total[1]<50)
			{
				echo '-';
			}
			else
			{
				echo '+';
				$total[1] = 100-$total[1];
			}
			?>
            </td>
            <td class="total-value" style="border-right:1px #000000 solid"><?php
			$round_tmp = explode(".",$round_off);
			if($round_tmp[1][2]!='')
			{
				if($round_tmp[1][2]>5)
					$round_tmp[1][1]=$round_tmp[1][1]+1;
				$round_tmp[1][0].$round_tmp[1][1];
			}
			else
				$round_tmp[1][0].$round_tmp[1][1];
			echo $total[1];
			 ?></td>
          </tr>
          <tr style="height:10px;">
            <td colspan="3" class="blank total-line" style="border-top:none; border-left:#000 1px solid"><strong>Amount in Words</strong></td>
            <td colspan="2" class="total-line" style="border-left:1px #000000 solid;border-right:#000 1px solid">PACKING</td>
            <td class="total-value" style="border-right:#000 1px solid;"><?php
			$charges_amount = number_format($charges_amount,2);
			$charges_amount = explode(".",$charges_amount);
			echo $charges_amount[0] ?></td>
            <td style="border-right:1px #000000 solid; text-align:right"><?php echo $charges_amount[1] ?></td>
          </tr>
          <tr style="height:15px;">
            <td colspan="3" class="blank total-line" style="border-bottom:#000 1px solid; border-left:#000 1px solid; font-size:14px;">
            &nbsp;&nbsp;&nbsp;&nbsp;Rupees <?php echo ucwords($this->no_to_words($bill_amount))?> Only.</td>
            <td colspan="2" class="total-line" style="border-left:1px #000000 solid; border-bottom:1px solid #000;border-right:#000 1px solid">GRAND TOTAL</td>
            <td class="balance" style="border-bottom:1px #000000 solid;border-right:#000 1px solid;"><strong><?php echo number_format($bill_amount)?></strong></td>
            <td style="border-bottom:1px #000000 solid;border-right:1px #000000 solid; text-align:right">00</td>
          </tr>
        </table><br />
        <br /><br />

        <div style="float:right"> For,<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SVB<br />
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Auto Products</strong> </div>
        </div>
        <?php
	}
	public function quatationUpdate($bill_no)
	{
		$sql = "UPDATE `quatation` SET `confirmed_bill_no`='".$this->billno."', `confirmed_time`=NOW() WHERE  `bill_no`='".$bill_no."';";
		$this->execute($sql);
	}
}
$billing = new billing;
$billing->init();
?>
