<?php
include_once"header.php";
?>
    <title> Employee Details </title>
    <div class="container-fluid">
      <div class="content">
        <center>
        <div class="box">
          <div class="box-head">
            <h3>Employee Relieve</h3>
          </div>
          <?php
		if(isset($_POST['relieve_conf']))
		{
			$sql = "update profile as pr set pr.`is`=2 where pr.`id`=".$_POST['user_id'].";
					UPDATE `authendication` SET `is`=0 WHERE  `user_id`=".$_POST['user_id'].";";
			$sql .= "INSERT INTO `relieve` (`date`, `user_id`, `reason`) VALUES ('".$_POST['date']."', ".$_POST['user_id'].", '".$_POST['reason']."');";
			$rst = $mysql->execute($sql);
			?>
          <script type="text/javascript">
            alert('Relieved Succesfully');
            </script>
          <?php
		}
		if(isset($_POST['relieve']))
		{
			?>
          <form action="#" method="post">
            <input type="hidden" name="user_id" value="<?php echo $_POST['id'] ?>" >
            <table class="table table-striped table-bordered" align="center">
              <tr>
                <th>Date OF Relieve</th>
                <td><input type="text" class="datepick text" name="date"></td>
              </tr>
              <tr>
                <th>Reason</th>
                <td><textarea name="reason"></textarea></td>
              </tr>
              <tr>
                <th align="center"><input  type="submit" name="relieve_conf" value="Relieve" class="btn btn-red4" ></th>
                <td></td>
              </tr>
            </table>
          </form>
          <?php
		}
		else
		{
			$sql = "select * from profile as pr where pr.`is`=1 ;";
			$rst = $mysql->execute($sql);
			?>
			  <table class='table table-striped table-bordered '>
				<thead>
				  <tr>
					<th>Name</th>
					<th>Phone</th>
					<th>E-Mail</th>
					<th>Address</th>
					<th>Designation</th>
					<th>Relieve</th>
				  </tr>
				</thead>
				<tbody>
				  <?php
											while($r = mysqli_fetch_array($rst[0]))
											{
											?>
				  <tr>
					<td><?php echo $r['name'] ?></td>
					<td><?php echo $r['phone_number'] ?></td>
					<td><?php echo $r['e_mail'] ?></td>
					<td><?php echo $r['address'] ?></td>
					<td><?php echo $r['type'] ?></td>
					<td><form action="#" method="post">
						<input type="hidden" name="id" value="<?php echo $r['id'] ?>">
						<button type="submit" name="relieve" class="btn btn-red4">Relieve</button>
					  </form></td>
				  </tr>
				  <?php
											}
											?>
				</tbody>
			  </table>
			  <?php
		}
		?>
        </div>
      </div>
    </div>
  </div>
  <?php
include_once"footer.php";
?>