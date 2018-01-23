<?php
include"header.php";
?>
<form action="" method="post">
<div class="box">
<div class="box-head"><h3>WB Bill Lock Release</h3></div>
<br>
        <table align="center" border="0">
          <tr>
            <th>Bill No </th>
            <td><input type="text" name="billno" class="text"></td>
          </tr>
          <tr>
            <th></th>
            <th><input type="submit" name="open" value="Open" class="btn btn-blue4"></th>
          </tr>
        </table>
<?php
if(isset($_POST['block']))
{
	$sql = "update bill set `lock` = 1	where bill_no='".$_POST['billno']."'";
	$rst = $mysqlwb->execute($sql);
	?>
        <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
          <h4 class="alert-heading">Successfully Locked!</h4>
        </div>
        <?php
}
if(isset($_POST['bunlock']))
{
	$sql = "update bill set `lock` = 0	where bill_no='".$_POST['billno']."'";
	$rst = $mysqlwb->execute($sql);
	?>
        <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
          <h4 class="alert-heading">Successfully UnLocked!</h4>
        </div>
        <?php
}
if(isset($_POST['tlock']))
{
	$sql = "update bill set `lock` = 1,`trans_close` = 1	where bill_no='".$_POST['billno']."'";
	$rst = $mysqlwb->execute($sql);
	?>
        <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
          <h4 class="alert-heading">Successfully Bill & Payment Locked!</h4>
        </div>
        <?php
}
if(isset($_POST['tunlock']))
{
	$sql = "update bill set `trans_close` = 0	where bill_no='".$_POST['billno']."'";
	$rst = $mysqlwb->execute($sql);
	?>
        <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
          <h4 class="alert-heading">Successfully Bill & Payment UnLocked!</h4>
        </div>
        <?php
}
if(isset($_POST['change']))
{
	$sql = "update bill set `date` = '".$_POST['date']."'	where bill_no='".$_POST['billno']."'";
	$rst = $mysqlwb->execute($sql);
	?>
        <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
          <h4 class="alert-heading">Date Successfully Updated!</h4>
        </div>
        <?php
}
if(isset($_POST['cancel']))
{
	$sql = "update bill set `status` = 's',`cancel`=0	where bill_no='".$_POST['billno']."'";
	$rst = $mysqlwb->execute($sql);
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
				`status`,
				`cancel`
			from 
				bill as bo 
			where 
				bo.bill_no='".$billno."'";
	$rst = $mysqlwb->execute($sql);
	if(mysqli_num_rows($rst[0])>0)
	{
		?>		
        <table class='table table-striped table-bordered'>
          <thead>
            <tr>
              <th>Sno</th>
              <th>Bill No</th>
              <th>Date</th>
              <th>Bill Amount</th>
              <th>Bill Lock</th>
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
                  <td style="text-align:right"><?php echo $mysqlwb->currency($p['bill_amount']) ?></td>
                  <td style="text-align:center">
                  <input type="submit" class="btn btn-<?php if($p['lock']) echo 'blue'; else echo 'red'; ?>4"
				  name="b<?php if($p['lock']) echo 'unlock'; else echo 'lock'; ?>" 
                  value="<?php if($p['lock']) echo 'unlock'; else echo 'lock'; ?>"></td>
                  <td style="text-align:center">
                  <input type="submit" class="btn btn-<?php if($p['trans_close']) echo 'blue'; else echo 'red'; ?>4"
				  name="t<?php if($p['trans_close']) echo 'unlock'; else echo 'lock'; ?>" 
                  value="<?php if($p['trans_close']) echo 'unlock'; else echo 'lock'; ?>"></td>
                  <td style="text-align:center">
                  <input type="text" name="date" class="datepick input-small" value="<?php echo $mysqlwb->date_format($p['date']) ?>"  />
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