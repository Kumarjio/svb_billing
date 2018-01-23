<?php include"header.php" ?>
<div class="box">
<div class="box-head"><h3>KOT Detail</h3></div>

	<form action="#" method="post">
        <table class="table table-bordered table-striped" style="width:5px;overflow:visible" cellspacing="5" cellpadding="5" align="center">
          <thead>
            <tr>
              <th><input type="text" name="kot"></th>
              <th><input type="submit" name="submit" value="Open" class="btn btn-blue4"></th>
            </tr>
          </thead>
        </table>
      </form>
      <hr>
      <?php 
	  if(isset($_REQUEST['kot']))
	  {
		$sql = "select * from bill_kot where kot = '".$_REQUEST['kot']."' and `is`=1";
		$rst = $mysql->execute($sql);
		if(mysqli_num_rows($rst[0])>0)
		{ ?>
			  <table align="center" class="table table-striped table-bordered" style="width:5px; overflow:visible" cellpadding="5" cellspacing="5">
						<thead>
						  <tr>
							<th colspan="5" style="text-align:center"><h3>KOT NO : <?php echo $_REQUEST['kot'] ?></h3></th>
						  </tr>
						</thead>
						<thead>
						  <tr>
							<th>SNO</th>
							<th>Bill No</th>
							<th>Name</th>
							<th>QTY</th>
							<th>Status</th>
						  </tr>
						</thead>
               <?php
				while($r = mysqli_fetch_array($rst[0]))
				{ ?>
                   <tr>
                      <td style="text-align:center"><?php echo ++$sno; ?></td>
                      <td style="text-align:center">
					  <a href="bill_detail.php?billno=<?php echo $r['bill_no'] ?>"><?php echo $r['bill_no']; ?></a></td>
                      <td style="white-space:nowrap">
					  <a href="product_view.php?id=<?php echo $r['product_id'] ?>"><?php echo $r['product_name']; ?></a></td>
                      <td style="white-space:nowrap"><?php echo $r['qty']." ".$r['type']; ?></td>
                      <td style="white-space:nowrap"><?php if($r['status']==1) 
					  				echo 'Delivered'; 
								else if($r['status']==0) 
									echo 'in Progress';
						 ?></td>
                    </tr> <?php
				}
				?></table><?php 
			}
		}
	  ?>
      <hr>
</div>
<?php include"footer.php" ?>