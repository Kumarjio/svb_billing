<?php
include_once"header.php";
?>
    <?php
if(isset($_POST['open']))
{
	$open_bal = $_POST['open_bal'];
	$sql = "INSERT INTO `shop_day_close` (`date`, `opening`,`open`,`opening_time`) VALUES ('".date('Y-m-d')."', ".$open_bal.",1, NOW());";
	foreach($_POST['cash'] as $id=>$cash)
	{
		$note = $_POST['note'][$id];
		$sql .= "INSERT INTO `cash` (`from`,`date`, `amount`, `no`) VALUES (0,'".date('Y-m-d')."', '".$note."', '".$cash."');";
	}
	$result = $mysql->execute($sql);
?>
    <center>
      <br>
      <a class="btn btn-primary" data-toggle="modal" href="#myModal" id="view_detail" style="visibility:hidden" >View Last Inserted User Name</a>
      <div class="modal hide" id="myModal">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">
          <a href="home.php" class="btn" >X</a>
          </button>
          <h3>
            <?php $name ?>
          </h3>
        </div>
        <div class="modal-body">
          <h1>Shop Opened Successfully</h1>
          <p>
          <h3>Have a Nice Day</h3>
          </p>
        </div>
        <div class="modal-footer"> <a href="home.php" class="btn" >Close</a></div>
      </div>
    </center>
    <script type="text/javascript">
    $(document).ready(function(e) {
        $("#view_detail").click();
    });
    </script>
    <?php
}
?>
    <?php
$sql = 'select * from shop_day_close as bl where bl.date like "'.date('Y-m-d').'%"';
$result = $mysql->execute($sql);
?>
    <div class="container-fluid">
      <div class="content">
        <center>
        <div class="box" style="width:50%; overflow:visible">
          <div class="box-head">
            <h3>Opening Balance</h3>
          </div>
          <div class="box-content">
            <?php
      if(mysqli_num_rows($result[0])==0)
	  {
	  ?>
            <form action="#" class="form-horizontal" method="post" enctype="multipart/form-data">
              <div class="control-group">
                <label for="twoicons" class="control-label">Amount</label>
                <div class="controls">
                  <div class="input-prepend input-append"> <span class="add-on"><b><del>&#2352;</del></b></span>
                    <input type="text" id="salary" class='input-square' name="open_bal" id="twoicons" />
                    <span class="add-on">.00</span> </div>
                </div>
              </div>
              <div >
                <?php include"classes/cash.php"; ?>
              </div>
              <div class="form-actions">
                <center>
                  <input type="submit" name="open" class='btn btn-blue4 btn-large' value="open">
                </center>
              </div>
            </form>
            <?php
	  }
	  else
	  {
		  echo "<center>Opening Balance is Already given</center>";
	  }
	  ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php
include_once"footer.php";
?>