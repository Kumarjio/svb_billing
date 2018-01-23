<?php include"header.php" ?>
    <div class="box">
      <div class="box-head">
        <h3>KOT in PROGRESSS</h3>
      </div>
      <?php
	  if(isset($_POST['submit']))
	  {
			$sql = "update bill_kot set `status`=1 where kot = '".$_POST['kot']."'";
			$mysql->execute($sql);
			?><center>
            <div class="alert alert-block alert-success" style="width:250px"> <a class="close" data-dismiss="alert" href="#">×</a>
              <h4 class="alert-heading">KOT NO : <?php echo $_POST['kot'] ?> Successfully Updated!</h4>
            </div></center>
            <?php
	  }
	  ?>
      <?php
	  if(isset($_POST['submit_id']))
	  {
			$sql = "update bill_kot set `status`=1 where id = '".$_POST['id']."'";
			$mysql->execute($sql);
			?><center>
            <div class="alert alert-block alert-success" style="width:250px"> <a class="close" data-dismiss="alert" href="#">×</a>
              <h4 class="alert-heading">Single Product with KOT NO : <?php echo $_POST['kot'] ?> Successfully Updated!</h4>
            </div></center>
            <?php
	  }
	  ?>
      <br>
      <form action="#" method="post">
        <table class="table table-bordered table-striped" style="width:5px;overflow:visible" cellspacing="5" cellpadding="5" align="center">
          <thead>
            <tr>
              <th><input type="text" name="kot" style="height:150px;width:250px; font-size:100px;font-weight:bold; text-align:center"></th>
              <th><input type="submit" name="submit" value="Order Ready" class="btn btn-blue4" style="height:50px;width:150px; font-size:20px;font-weight:bold; text-align:center"></th>
            </tr>
          </thead>
        </table>
      </form>
      <hr>
      <?php
	$sql = "select * from bill_kot where status = 0 order by kot asc";
	$rst = $mysql->execute($sql);
	if(mysqli_num_rows($rst[0])>0)
	{
		?>
		<center>
        <h3>Pending KOT</h3>
      	</center>
		<?php
		while($r = mysqli_fetch_array($rst[0]))
		{
			if($kot != $r['kot'])
			{
				$sno = 0;
				if($kot!='')
				{
					?>
                      <tr>
                        <td colspan="5" style="text-align:center"><form action="#" method="post">
                            <input type="hidden" name="kot" value="<?php echo $kot ?>" >
                            <input type="submit" name="submit" value="All Orders Ready" class="btn btn-blue4">
                          </form></td>
                      </tr>
                      </table>
                      <?php 
				}
				?>
                  <table align="center" class="table table-striped table-bordered" style="width:5px; overflow:visible" cellpadding="5" cellspacing="5">
                    <thead>
                      <tr>
                        <th colspan="5" style="text-align:center"><h3><?php echo $r['kot'] ?></h3></th>
                      </tr>
                    </thead>
                    <thead>
                      <tr>
                        <th>SNO</th>
                        <th>Bill No</th>
                        <th>Name</th>
                        <th>QTY</th>
                        <th></th>
                      </tr>
                    </thead>
                    <?php
			}
			?>
            <tr>
              <td style="text-align:center"><?php echo ++$sno; ?></td>
              <td style="text-align:center"><?php echo $r['bill_no']; ?></td>
              <td style="white-space:nowrap"><?php echo $r['product_name']; ?></td>
              <td style="white-space:nowrap"><?php echo $r['qty']." ".$r['type']; ?></td>
              <td><form action="#" method="post">
                  <input type="hidden" name="id" value="<?php echo $r['id'] ?>" >
                  <input type="hidden" name="kot" value="<?php echo $r['kot'] ?>" >
                  <input type="submit" name="submit_id" value="ready" class="btn btn-blue4">
                </form></td>
            </tr>
            <?php
                $kot = $r['kot'];
		}
		?>
        <tr>
          <td colspan="5" style="text-align:center"><form action="#" method="post">
              <input type="hidden" name="kot" value="<?php echo $kot ?>" >
              <input type="submit" name="submit" value="All Orders Ready" class="btn btn-blue4">
            </form></td>
        </tr>
      </table>
      <?php
	}?>
    <hr>
    </div>
    <?php include"footer.php"; ?>