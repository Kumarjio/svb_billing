<?php
include"header.php";
?>
    <form action="" method="post">
      <div class="box">
        <div class="box-head">
          <h3>Stock Entry Lock Release</h3>
        </div>
        <br>
        <table align="center" border="0">
          <tr>
            <th>Stock Entry No </th>
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
	$sql = "update purchase set `lock` = 1	where bill_no='".$_POST['bill_no']."'";
	$rst = $mysql->execute($sql);
	?>
        <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
          <h4 class="alert-heading">Successfully Locked!</h4>
        </div>
        <?php
}
if(isset($_POST['bunlock']))
{
	$sql = "update purchase set `lock` = 0	where bill_no='".$_POST['bill_no']."'";
	$rst = $mysql->execute($sql);
	?>
        <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
          <h4 class="alert-heading">Successfully UnLocked!</h4>
        </div>
        <?php
}
if(isset($_POST['tlock']))
{
	$sql = "update purchase set `lock` = 1,`trans_close` = 1	where bill_no='".$_POST['bill_no']."'";
	$rst = $mysql->execute($sql);
	?>
        <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
          <h4 class="alert-heading">Successfully Purchase & Payment Locked!</h4>
        </div>
        <?php
}
if(isset($_POST['tunlock']))
{
	$sql = "update purchase set `trans_close` = 0	where bill_no='".$_POST['bill_no']."'";
	$rst = $mysql->execute($sql);
	?>
        <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
          <h4 class="alert-heading">Successfully Purchase & Payment UnLocked!</h4>
        </div>
        <?php
}
if(isset($_POST['open']))
{
	$billno = $_POST['billno'];
	$sql = "select 
				date_format(date,'%d-%m-%Y')'date',
				`lock`,
				trans_close,
				bill_amount,
				`status`
			from 
				purchase as bo 
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
              <th>Stock Entry No</th>
              <th>Date</th>
              <th>Entry Lock</th>
            </tr>
          </thead>
          <tbody>
            <?php
			  while($p = mysqli_fetch_array($rst[0]))
			  {
				  ?>
            <tr>
              <td style="text-align:center"><input type="hidden" name="bill_no" value="<?php echo $billno ?>">
                <?php echo ++$sno; ?></td>
              <td style="text-align:center"><?php echo $billno ?></td>
              <td style="text-align:center"><?php echo $p['date'] ?></td>
              <td style="text-align:center"><input type="submit" class="btn btn-<?php if($p['lock']) echo 'blue'; else echo 'red'; ?>4"
				  name="b<?php if($p['lock']) echo 'unlock'; else echo 'lock'; ?>" 
                  value="<?php if($p['lock']) echo 'unlock'; else echo 'lock'; ?>"></td>
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