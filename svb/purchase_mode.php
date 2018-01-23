<?php
$billing->loadCurrentBill();
$billing->setConvertion();
if(isset($_POST['Save']))
{
	$billno = $billing->getCurrentBill();
	if($_POST['customerId'] == '')
		$customerId = 0;
	else
		$customerId = $_POST['customerId'];
	$billing->setCustomer($customerId);
	$billing->setMode('s');
	foreach($_POST['itemId'] as $id=>$pId)
	{
		if($pId!="")
		{
			$billing->setOrders($pId,$_POST['qty'][$id],$_POST['itemType'][$id],$_POST['itemDis'][$id]);
		}
	}
	$billing->setDiscount($_POST['discountAmnt'],$_POST['discountReason']);
	$billing->setPayAmount($_POST['recieved'],$_POST['returned']);
	$billing->processNewOrders();
	$billing->savemodeOptions($billno);
	$billing->closeBill($billno);
	$sql = "select p.id,p.qty-p.recieved_qty'qty',product_id from pur_order as p where p.bill_no='".$billno."' and `recieved_qty`<`qty` and p.`is`=1 and p.`is`=1";
	$rst = $mysql->execute($sql);
	$sql = '';
	while($r = mysqli_fetch_array($rst[0]))
	{
		$qty = $r['qty'];
		$tsql = "select id from stock as s where s.p_order_id='".$r['id']."';";
		$rst1 = $mysql->execute($tsql);
		$sql .= "update product set available=available+".$qty." where id=".$r['product_id'].";";
		$sql .= "update pur_order set recieved_qty=recieved_qty+'".$qty."' where id='".$r['id']."';";
		if(mysqli_num_rows($rst1[0])>0)
		{
			$row = mysqli_fetch_array($rst1[0]);
			$sql .="UPDATE `stock` SET `qty`=`qty`+'".$qty."', `available`=`available`+'".$qty."' WHERE  `id`='".$row[0]."' LIMIT 1;";
		}else
		{
			$sql.= "INSERT INTO `stock` (`par_id`,`p_order_id`, `p_date`, `pid`, `par_price`, `type`, `qty`, `available`, `unit_price`, `vat`) 
				(select bid,oid,date,product_id,orecieved,`type`,'".$qty."','".$qty."',
				unit_price,vat from purchase_orders where oid ='".$r['id']."' and `recieved_qty`>`completed`);";
		}
		$sql .="INSERT INTO `purchase_order_recieving` 
				(`pid`, `oid`, `recieved`, `date`) 
			VALUES 
				((select bid from purchase_orders where bill_no ='".$billno."' limit 1), '".$r['id']."', '".$qty."', NOW());";	
	}
	$sql .= "update pur_order set `received`=1 , `recieved_qty`=`qty` where bill_no='".$billno."';";
	$sql .= "update purchase set `lock`=1 where bill_no='".$billno."';";
	$rst = $mysql->execute($sql);
}
else if(isset($_POST['SaveL']))
{
	$billing->setBillNo($_POST['loadedBillNo']);
	$billing->setMode('s');
	if($_POST['customerId'] == '')
		$customerId = 0;
	else
		$customerId = $_POST['customerId'];
	$billing->setCustomer($customerId);
	if(count($_POST['itemId'])>0)
	{
		foreach($_POST['itemId'] as $id=>$pId)
		{
			if($pId!="")
			{
				if(isset($_POST['itemRowId'][$id]))
				{
					$billing->updateorders($_POST['itemRowId'][$id],$pId,$_POST['qty'][$id],$_POST['itemType'][$id],$_POST['itemDis'][$id]);
				}
				else
				{
					$billing->setOrders($pId,$_POST['qty'][$id],$_POST['itemType'][$id],$_POST['itemDis'][$id]);
				}
			}
		}
	}
	$billing->setDiscount($_POST['discountAmnt'],$_POST['discountReason']);
	$billing->setPayAmount($_POST['recieved'],$_POST['returned']);
	$billing->processOldOrders();
	$billing->savemodeOptions($_POST['loadedBillNo']);
}
else if(isset($_POST['CancelL']))
{
	$billing->setMode('c');
	$billing->cancelBill($_POST['loadedBillNo'],$_POST['cancelReason']);
}
else if(isset($_POST['Print']))
{
	$billno = $billing->getCurrentBill();
	if($_POST['customerId'] == '')
		$customerId = 0;
	else
		$customerId = $_POST['customerId'];
	$billing->setCustomer($customerId);
	$billing->setMode('s');
	foreach($_POST['itemId'] as $id=>$pId)
	{
		if($pId!="")
		{
			$billing->setOrders($pId,$_POST['qty'][$id],$_POST['itemType'][$id],$_POST['itemDis'][$id]);
		}
	}
	$billing->setDiscount($_POST['discountAmnt'],$_POST['discountReason']);
	$billing->setPayAmount($_POST['recieved'],$_POST['returned']);
	$billing->processNewOrders();
	$billing->printBill($billno);
}
else if(isset($_POST['PrintL']))
{
	$billing->setBillNo($_POST['loadedBillNo']);
	$billing->setMode('p');
	if($_POST['customerId'] == '')
		$customerId = 0;
	else
		$customerId = $_POST['customerId'];
	$billing->setCustomer($customerId);
	if(count($_POST['itemId'])>0)
	{
		foreach($_POST['itemId'] as $id=>$pId)
		{
			if($pId!="")
			{
				if(isset($_POST['itemRowId'][$id]))
				{
					$billing->updateorders($_POST['itemRowId'][$id],$pId,$_POST['qty'][$id],$_POST['itemType'][$id],$_POST['itemDis'][$id]);
				}
				else
				{
					$billing->setOrders($pId,$_POST['qty'][$id],$_POST['itemType'][$id],$_POST['itemDis'][$id]);
				}
			}
		}
	}
	$billing->setDiscount($_POST['discountAmnt'],$_POST['discountReason']);
	$billing->setPayAmount($_POST['recieved'],$_POST['returned']);
	$billing->processOldOrders();
	$billing->printBill($_POST['loadedBillNo']);
}
else if(isset($_POST['CloseBill']))
{
	$billing->closeBill($_POST['LastbillNo']);
}
else if(isset($_POST['CompletePay']))
{
	$billing->closePayment($_POST['LastbillNo']);

}
else if(isset($_POST['lockBill']))
{
	$billing->closeBill($_POST['unClosedBills']);

}
else if(isset($_POST['closePayment']))
{
	$billing->closePayment($_POST['unClosedBills']);

}
else if(isset($_POST['loadBill']))
{
	$billing->setBillNo($_POST['unClosedBills']);
	$billing->getOrders($_POST['unClosedBills']);
	$billing->loadDiscount($_POST['unClosedBills']);
	$billing->loadCustomer($_POST['unClosedBills']);
}
?>
