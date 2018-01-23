<?php
include_once"header.php";
?>
<?php
if(isset($_POST['close']))
{
	$closeAmnt = str_replace(",","",$_POST['closeAmnt']);
	$id = $_POST['id'];
	$sql = "update `balance` set `closing` = ".$closeAmnt." where id=".$id.";";
	$result = $mysql->execute($sql);
?>
    <center>
      <br>
      <a class="btn btn-primary" data-toggle="modal" href="#myModal" id="view_detail" style="visibility:hidden" >View Last Inserted User Name</a>
      <div class="modal hide" id="myModal">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h3>
            <?php $name ?>
          </h3>
        </div>
        <div class="modal-body">
        <h1>Branch Closed Successfully</h1>
          <p>
          <h3>Todays Total Closing Balance <?php echo $closeAmnt ?></h3>
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
include_once"footer.php";
?>