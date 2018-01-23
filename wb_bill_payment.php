<?php include"header.php"; ?>
   <div class="box" style="min-height:300px;">
      <div class="box-head">
        <h3>Bill Payment</h3>
      </div>
    <?php
if(isset($_POST['pay']))
{
	if($_POST['recieved']!=0)
	{
		$paid = 0;
		$sql ='';
		if($_POST['balance']<=($_POST['recieved']-$_POST['returned']))
		{
			$paid = 1;
			$sql .= "UPDATE `bill` SET `lock`=1, `trans_close`=1 WHERE  `bill_no`='".$_POST['bill']."' LIMIT 1;";
		}
		$sql .="INSERT INTO `bill_payment` 
					(`bill_no`, `date`, `bill_amount`,`cur_balance` , `recieved`, `returned`,`paid`) 
				VALUES 
					('".strtoupper($_POST['bill'])."', '".$_POST['date']."', ".$_POST['billnet'].", ".$_POST['balance'].", ".$_POST['recieved'].", 
					".$_POST['returned'].",".$paid.");";
		$rst = $mysqlwb->execute($sql);
		$sql = "select * from bill_payment where bill_no = '".$_POST['bill']."' order by id desc limit 1;";
		$rst = $mysqlwb->execute($sql);
		$balance = mysqli_fetch_array($rst[0]);
		$balance = $balance['cur_balance']-$_POST['recieved']-$_POST['returned'];
		?>
		<div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
				  <h4 class="alert-heading">
				  Rs: <?php echo $mysqlwb->currency($_POST['recieved']-$_POST['returned']) ?> Paid Successfully for Bill 
				  <a href="bill_duplicate_wb_details.php?billno=<?php echo strtoupper($_POST['bill'])  ?>"><?php echo strtoupper($_POST['bill'])  ?></a> !
				  <br><br> Balance Amount To Be Pay: Rs: <?php echo $mysqlwb->currency($balance) ?></h4>
				</div>
		<?php
		if($_GET['doy'])
		{
			?>
		<script type="text/javascript">
			window.opener.completePayment();
			</script>
		<?php
		}
		unset($sql);
	}
	else
	{
		?>
		<div class="alert alert-block alert-danger"> <a class="close" data-dismiss="alert" href="#">×</a>
				  <h4 class="alert-heading">
				  Recieved Amount cannot be empty</h4>
				</div>
		<?php
	}
}
if(isset($_POST['close']))
{
	if($_GET['doy'])
	{
		?>
    <script type="text/javascript">
        window.opener.completePayment();
        </script>
    <?php
	}
	unset($sql);
}
?>
        <center>
          <table align="center" cellpadding="10" cellspacing="10" class=""  >
            <tr>
            <form method="post" id="form1">
              <th align="right">Bill No</th>
              <td>
              <?php
			  $sql = "select b.bill_no,c.name from bill as b,billing.customers as c where c.id=b.customer_id and b.lock=1 and b.trans_close=0 and b.is=1";
			  $rst = $mysqlwb->execute($sql);
			  ?>
              <select name="billno" class="cho" onchange="$('#form1').submit()">
              <?php while($r = mysqli_fetch_array($rst[0]))
			  {?>
			  <option <?php if($_REQUEST['billno']==$r['bill_no']) echo 'selected' ?> value="<?php echo $r['bill_no']?>" ><?php echo $r['bill_no']." ".$r['name']; ?></option>
              <?php } ?>
              </select>
              </td>
              <td><button type="submit" name="load" class="btn btn-blue4">Open Bill</button></td>
            </form>
            </tr>
            <?php 
			if(isset($_POST['billno']))
			{
				$sql = "select bl.bill_amount,bl.`lock` from bill as bl where bl.bill_no='".$_POST['billno']."' LIMIT 1;
						select py.recieved,py.paid from bill_payment as py where py.bill_no='".$_POST['billno']."' and py.`is`=1 and py.cancel=0 order by id";
				$rst = $mysqlwb->execute($sql);
				$payment = mysqli_fetch_array($rst[0]);
				$recieved = 0;
				$paid = 0;
				$lock = $r['lock'];
				while($r=mysqli_fetch_array($rst[1]))
				{
					$recieved += $r['recieved'];
					$paid = $r['paid'];
				}
				$balance = $payment['bill_amount']-$recieved;
			?>
            <form method="post">
            <input type="hidden" name="bill" value="<?php echo $_POST['billno'] ?>" >   
            <tr>
              <th align="right">Date</th>
              <td><input type="text" name="date" class="datepick" value="<?php echo date('Y-m-d') ?>" /></td>
            </tr>
            <tr>
              <th align="right">Bill Amount</th>
              <td><strong><b><del>&#2352;</del></b>&nbsp;<?php echo $mysqlwb->currency($payment['bill_amount']) ?></strong>
              <input type="hidden" name="billnet" value="<?php echo $payment['bill_amount'] ?>"/></td>
            <tr>
              <th align="right">Bill Balance</th>
              <td><strong><b><del>&#2352;</del></b>&nbsp;<?php echo $mysqlwb->currency($balance) ?></strong>
              <input type="hidden" id="balance" name="balance" value="<?php echo $balance ?>" /></td>
            </tr>
            <tr>
              <th align="right">Recieved Amount</th>
              <td><div class="input-prepend"> <span class="add-on"><i class=""><b><del>&#2352;</del></b></i></span>
                  <input 
              type="text" name="recieved" id="recieved" class="input-square text" value="" onKeyUp="calcPayment()" autocomplete="off" />
                </div></td>
            </tr>
            <tr style="visibility:hidden">
              <th align="right">Returned Amount</th>
              <td><div class="input-prepend"> <span class="add-on"><i class=""><b><del>&#2352;</del></b></i></span>
                  <input 
              type="text" name="returned" id="returned" class="input-square text" value="0" autocomplete="off">
                </div></td>
            </tr>
            <tr>
              <td></td>
              <td><?php 
			if($paid==0 && $payment['lock']==1)
			{
			?>
                <button type="submit" name="pay" class="btn btn-blue4">Pay</button>
                <?php 
			}
			else if($payment['lock']==0)
			{
				?>
                Please Lock the Bill And Continue Payment!
                <?php 
				if($_GET['doy'])
				{
				?>
                <button type="submit" name="close" class="btn btn-blue4">close window</button>
                <?php 
				}
			}
			else 
			{
				?>
                Payment Completed!
                <?php 
				if($_GET['doy'])
				{
				?>
                <button type="submit" name="close" class="btn btn-blue4">close window</button>
                <?php 
				}
			}
			?></td>
            </tr>
            <script type="text/javascript">
            function calcPayment()
			{
				net = $("#balance").val();
				rvd = $("#recieved").val();
				tot = parseInt(rvd-net);
				if(tot<0)
					tot=0
				tot = parseInt(tot);
				$("#returned").val(tot);
			}
            </script>
            <?php
			}
			
			?>
          </table>
        </center>
      </form>
    </div>
    <?php include"footer.php"; ?>