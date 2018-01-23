<?php
include"header.php";
?>
<form action="" method="post">
<div class="box">
<div class="box-head"><h3>Quatation Lock Release</h3></div>
<br>
        <table align="center" border="0">
          <tr>
            <th>Quatation No </th>
            <td><input type="text" name="billno" class="text"></td>
          </tr>
          <tr>
            <th></th>
            <th><input type="submit" name="open" value="Open" class="btn btn-blue4"></th>
          </tr>
        </table><br>
<br>
<?php
if(isset($_POST['block']))
{
	$sql = "update quatation set `lock` = 1	where bill_no='".$_POST['billno']."'";
	$rst = $mysql->execute($sql);
	?>
        <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
          <h4 class="alert-heading">Successfully Locked!</h4>
        </div>
        <?php
}
if(isset($_POST['bunlock']))
{
	$sql = "update quatation set `lock` = 0	where bill_no='".$_POST['billno']."'";
	$rst = $mysql->execute($sql);
	?>
        <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
          <h4 class="alert-heading">Successfully UnLocked!</h4>
        </div>
        <?php
}
if(isset($_POST['tlock']))
{
	$sql = "update quatation set `lock` = 1,`trans_close` = 1	where bill_no='".$_POST['billno']."'";
	$rst = $mysql->execute($sql);
	?>
        <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
          <h4 class="alert-heading">Successfully quatation & Payment Locked!</h4>
        </div>
        <?php
}
if(isset($_POST['tunlock']))
{
	$sql = "update quatation set `trans_close` = 0	where bill_no='".$_POST['billno']."'";
	$rst = $mysql->execute($sql);
	?>
        <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
          <h4 class="alert-heading">Successfully quatation & Payment UnLocked!</h4>
        </div>
        <?php
}
if(isset($_POST['change']))
{
	$sql = "update quatation set `date` = '".$_POST['date']."'	where bill_no='".$_POST['billno']."'";
	$rst = $mysql->execute($sql);
	?>
        <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
          <h4 class="alert-heading">Date Successfully Updated!</h4>
        </div>
        <?php
}
if(isset($_POST['cancel']))
{
	$sql = "update quatation set `status` = 's',`cancel`=0	where bill_no='".$_POST['billno']."'";
	$rst = $mysql->execute($sql);
	?>
        <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
          <h4 class="alert-heading">Retieved Successfully!</h4>
        </div>
        <?php
}
if(isset($_POST['open']) || isset($_POST['billno']))
{
	$billno = $_POST['billno'];
	$sql = "select 
				date_format(date,'%d-%m-%Y')'date',
				`lock`,
				trans_close,
				bill_amount,
				confirmed_bill_no,
				confirmed_time,
				`status`,
				`cancel`
			from 
				quatation as bo 
			where 
				bo.bill_no='".$billno."'";
	$rst = $mysql->execute($sql);
	if(mysqli_num_rows($rst[0])>0)
	{
		?>		
        <table class='table table-striped table-bordered'>
          <thead>
            <tr>
              <th>Sno</th>
              <th>quatation No</th>
              <th>Date</th>
              <th>quatation Amount</th>
              <th>Confirmation Status</th>
              <th>quatation Lock</th>
              <th>Transaction Lock</th>
              <th>Date Correction</th>
              <th>Cancel Retrive</th>
            </tr>
          </thead>
          <tbody>
              <?php
			  while($p = mysqli_fetch_array($rst[0]))
			  {
				  ?>
				  <tr>
				  <td style="text-align:center">
				  <input type="hidden" name="billno" value="<?php echo $billno ?>">
				  <?php echo ++$sno; ?></td>
                   <td style="text-align:center"><?php echo $billno ?></td>
                  <td style="text-align:center"><?php echo $p['date'] ?></td>
                  <td style="text-align:right"><?php echo $mysql->currency($p['bill_amount']) ?></td>
                  
                  <td style="text-align:center"><?php if($p['confirmed_bill_no']!='') echo 'Bill No: '.$p['confirmed_bill_no']; else 'Not Yet Confirmed'; ?></td>
                  <td style="text-align:center">
                  <input type="submit" class="btn btn-<?php if($p['lock']) echo 'blue'; else echo 'red'; ?>4"
				  name="b<?php if($p['lock']) echo 'unlock'; else echo 'lock'; ?>" 
                  value="<?php if($p['lock']) echo 'unlock'; else echo 'lock'; ?>"></td>
                  <td style="text-align:center">
                  <input type="submit" class="btn btn-<?php if($p['trans_close']) echo 'blue'; else echo 'red'; ?>4"
				  name="t<?php if($p['trans_close']) echo 'unlock'; else echo 'lock'; ?>" 
                  value="<?php if($p['trans_close']) echo 'unlock'; else echo 'lock'; ?>"></td>
                  <td style="text-align:center">
                  <input type="text" name="date" class="datepick input-small" value="<?php echo $mysql->date_format($p['date']) ?>"  />
                  <input type="submit" class="btn btn-green4"
				  name="change" value="Change"></td>
                  <td style="text-align:center">
                  <?php
				  if($p['cancel'])
				  {
				  ?>
                  <input type="submit" class="btn btn-green4"
				  name="cancel" value="Retrieve">
                  <?php
				  }?>
                  </td>
				  </tr>
				  <?php
			  }
			  ?>
          </tbody>
        </table>
        <?php
	}
	else
	{
		?>
        No Bills With Tthis No
        <?php
	}
}
?>
</div>
</form>
<?php
include"footer.php";
?>