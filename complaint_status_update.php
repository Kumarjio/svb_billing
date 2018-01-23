<?php
include"header.php";
?>
    <div class="box">
      <div class="box-head tabs">
        <h3>Comlaint Status</h3>
      </div>
      <?php
if(isset($_POST['update']))
{
	$sql = "UPDATE `product_complaint` SET `status`=".$_POST['status']." WHERE  `id`=".$_POST['id']." LIMIT 1;";
	$mysql->execute($sql);
	?>
			<div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
			  <h4 class="alert-heading">Successfully Updated!</h4>
			</div>
			<?php
}
if(isset($_POST['remove']))
{
	$sql = "UPDATE `product_complaint` SET `is`=0 WHERE  `id`=".$_POST['id']." LIMIT 1;";
	$mysql->execute($sql);
	?>
			<div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
			  <h4 class="alert-heading">Successfully Cancelled!</h4>
			</div>
			<?php
}
$from = $to = date('Y-m-d');
if(isset($_POST['from']))
	$from = $_POST['from'];
if(isset($_POST['to']))
	$to = $_POST['to'];
?>
      <form action="#" method="post">
        <table class="table table-bordered table-striped search" align="center">
          <thead>
            <tr>
              <th>From</th>
              <th><input type="text" name="from" class="datepick" value="<?php echo $from ?>"></th>
              <th>To</th>
              <th><input type="text" name="to" class="datepick" value="<?php echo $to ?>"></th>
              <th><input type="submit" name="open" value="Submit" class="btn btn-blue4"></th>
            </tr>
          </thead>
        </table>
      </form>
      <br>
      <br>
      <table class="table table-bordered table-striped" align="center">
        <thead>
          <tr>
            <th>Sno</th>
            <th>Customer</th>
            <th>Date</th>
            <th>Product Name</th>
            <th>Complaint</th>
            <th>Current Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
		 $sql = "select c.cid,c.id,c.name'cname',c.phone,p.name,c.pid,date_format(c.it,'%d-%m-%y %H:%i')'date',c.comp,c.status from product_complaint as c,product as p where c.`is`=1 and c.pid=p.id and  c.it between '".$from." 00:00:00' and '".$to." 23:59:59'";
		$rst = $mysql->execute($sql);
		while($r = mysqli_fetch_array($rst[0]))
		{
			$sql = "select c.id,c.name,c.phone from  customers as c where c.id='".$r['cid']."'";
			$c = $mysql->execute($sql);
			$cust = mysqli_fetch_array($c[0]);
			if(mysqli_num_rows($c[0])>0)
			{
				$cname = $cust['name'];
				$cid = $cust['id'];
			}
			else
			{
				$cname = $r['cname'];
				$cphone = $r['phone'];
			}
		?>
          <tr>
            <td style="text-align:center"><?php echo ++$sno; ?></td>
            <td style="text-align:center"><a href="customer_profile.php?id=<?php echo $r['cid'] ?>"><?php echo $cname."<br>".$cphone?></a></td>
            <td style="text-align:center"><?php echo $r['date'] ?></td>
            <td style="text-align:center"><a href="product_view.php?id=<?php echo $r['pid'] ?>"><?php echo $r['name'] ?></a></td>
            <td style="text-align:center"><?php echo $r['comp'] ?></td>
            <td><?php 
				if($r['status']==0) 
					echo 'Pending'; 
				elseif($r['status']==1) 
					echo 'Intimated to Dealer'; 
				elseif($r['status']==2) 
					echo 'Rectified';
				elseif($r['status']==3) 
					echo 'Rectified & Intimated to customer'; ?></td>
            <td>
            <form action="#" method="post">
            <input type="hidden" name="id" value="<?php echo $r['id'] ?>">
            <select name="status" id="status" class='cho'>
              <option <?php if($r['status']=='0') echo 'selected' ?> value="0" >pending</option>
              <option <?php if($r['status']=='1') echo 'selected' ?> value="1" >Informed to Dealer</option>
              <option <?php if($r['status']=='2') echo 'selected' ?> value="2" >Rectified</option>
              <option <?php if($r['status']=='3') echo 'selected' ?> value="3" >Rectified & Intimated to Customer</option>
              </select>&nbsp;&nbsp;
             <input type="submit" name="update" value="Update" class="btn btn-blue4">
             &nbsp;&nbsp;
             <input type="submit" name="remove" value="Remove" class="btn btn-red4">
            </form>
            </td>
          </tr>
          <?php
		}
		?>
        </tbody>
      </table>
    </div>
    <?php
include"footer.php";
?>