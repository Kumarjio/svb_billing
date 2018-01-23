<?php
include"header.php";
?>
    <?php
$sql = '';
if(isset($_POST['set']))
{
	$sql = "INSERT INTO `attendence` 
				(`date`, `emp_id`, `time`,`manual`) 
			VALUES 
				('".$_POST['date']."', '".$_POST['empid']."', '".$_POST['time']."',1);";
}
if(isset($_POST['del']))
{
	$sql ="UPDATE `attendence` SET `is`=0  WHERE  id=".$_POST['rid'].";";
}
$sql .= "select 		
			a.id,
			a.emp_id,
			a.date,
			a.time,
			p.name
		from 
			attendence as a,
			profile as p
		where
			a.`is`=1
			and
			p.id=a.emp_id
		group by
			a.emp_id,
			a.date
		having
			count(*)<2 
		order by
			a.date,
			a.emp_id;";

$rst = $mysql->execute($sql);
?>
    <div class="box">
      <div class="box-head"><h3>Attendence Not Closed</h3></div>
      <table class="table table-bordered table-striped dataTable">
        <thead>
          <tr>
            <td>Sno</td>
            <td>Employee Name</td>
            <td>Date</td>
            <td>Time</td>
            <td>Needed Time</td>
          </tr>
        </thead>
        
          <?php
$i = 1;
while($r = mysqli_fetch_array($rst[0]))
{
	?>
    <tbody>
    
          <tr>
            <td align="center"><?php echo $i++; ?></td>
            <td align="center"><?php echo $r['name']; ?></td>
            <td align="center"><?php echo $r['date']; ?></td>
            <td align="center"><?php echo $r['time']; ?></td>
            <td align="center">
            <form action="#" method="post">
                <input type="text" name="time" class="mask_productNumber focus.inputmask" style="width:75px">    
           
                <input type="hidden" name="state" value="<?php echo $state; ?>" />
                <input type="hidden" name="rid" value="<?php echo $r['id']; ?>" />
                <input type="hidden" name="date" value="<?php echo $r['date']; ?>" />
                <input type="hidden" name="empid" value="<?php echo $r['emp_id']; ?>" />
                <input type="submit" name="set" value="Insert" class="btn btn-blue4" />
              
                <input type="submit" name="del" value="Delete Record" class="btn btn-red4"  />
                  </form>
              </td>
          </tr>
   
     </tbody>
          <?php
}
?>
        
      </table>
    </div>
    <?php
include"footer.php";
?>