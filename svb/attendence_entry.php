<?php
include"header.php";
?>
    <div class="box">
      <div class="box-head"><h3>Manual Attendence</h3></div>
    <?php
$sql = '';
if(isset($_POST['add']))
{
	foreach($_POST['emp_code'] as $emp_code)
	{
		$sql .= "INSERT INTO `attendence` (`date`, `emp_id`, `time`,`manual`) VALUES ('".$_POST['date']."', ".$emp_code.", '".$_POST['time']."',1);";
	}
	?>
    <div class="alert alert-block alert-success">
							  <a class="close" data-dismiss="alert" href="#">×</a>
							  <h4 class="alert-heading">Success!</h4>
							</div>
    <?php
}
$sql .= "select pr.id,pr.name from profile as pr where pr.`is`=1;";
$result = $mysql->execute($sql);
?>
      <form action="#" method="post">
        <table class="table table-stripped" style="width:50%" align="center">
          <tr>
            <th>Employee</th>
            <th><select name="emp_code[]" class="cho" multiple>
              <?php
				while($r = mysqli_fetch_array($result[0]))
				{
					?>
							<option value="<?php echo $r['id']; ?>" ><?php echo $r['name'] ?></option>
							<?php
				}
			 ?>
              </select></th>
          </tr>
          <tr>
            <th>Date</th>
            <th><input type="text" name="date" class="datepick text"></th>
          </tr>
          <tr>
          	<th>State</th>
            <td><select class="cho" name="state"><option>IN</option><option>OUT</option></select></td>
          </tr>
          <tr>
            <th>Time</th>
            <th><input type="text" name="time" class="mask_productNumber focus.inputmask" style="width:75px"></th>
          </tr>
          <tr>
            <th colspan="2" align="center"><center>
                <input type="submit" name="add" value="Enter" class="btn btn-blue4" >
              </center></th>
          </tr>
        </table>
      </form>
    </div>
    <?php
include"footer.php";
?>