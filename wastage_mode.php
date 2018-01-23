<?php
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
else if(isset($_POST['CloseBill']))
{
	$billing->closeBill($_POST['LastbillNo']);
}
else if(isset($_POST['lockBill']))
{
	$billing->closeBill($_POST['unClosedBills']);
}
else if(isset($_POST['complete']))
{
	$billing->closeBill($_POST['unClosedBills']);
}
else if(isset($_POST['loadBill']))
{
	$billing->setBillNo($_POST['unClosedBills']);
	$billing->getOrders($_POST['unClosedBills']);
	$billing->loadDiscount($_POST['unClosedBills']);
	$billing->loadCustomer($_POST['unClosedBills']);
}
?>