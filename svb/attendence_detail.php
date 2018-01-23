<?php
include"header.php";
?>
<?php 
if(isset($_POST['generate']))
{
	$startDate = $_POST['from'];
	$endDate = $_POST['to'];
}
else
{
	$startDate = date('Y-m-d');
	$endDate = date('Y-m-d');
}
?>
<div class="box">
	<div class="box-head"><h3>Attendence</h3></div><br>
    <center>
    <form action="" method="post">
    <input type="text" name="from" class="text datepick" style="width:120px" value="<?php echo $startDate ?>"> 
    <strong>To </strong>
    <input type="text" name="to" class="text datepick" style="width:120px" value="<?php echo $endDate ?>">
    <input type="submit" name="generate" class="btn btn-blue4" value="Submit">
    </form>
    </center>
    
<?php
$sql = "select 
		att.date,
		att.in,
		att.`out`,
		TIMEDIFF(att.out,att.in)'work',
		att.emp_id ,
		p.name,
		p.bio_id,
		att.manual
		from 
			attendence_data as att ,
			profile as p 
		where 
			att.date between '".$startDate."' and '".$endDate."'
			and
			(
			p.id = att.emp_id
			or
			p.bio_id = att.bio_id
			)
		order by
			att.date;";
$rst = $mysql->execute($sql);
?>
<table class='table table-striped dataTable table-bordered dataTable-tools'>
                                <thead>
                                  <tr>
                                    <th>SNO</th>                                    
                                    <th>EMPLOYEE NAME</th>
                                    <th>BIO ID</th>
                                    <th>DATE</th>
                                    <th>IN</th>
                                    <th>OUT</th>
                                    <th>MANUAL</th>
                                    <th>WORKED HOURS</th>
                                  </tr>
                                </thead>
                                <tbody>
                                <?php 
								while($rst1 = mysqli_fetch_array($rst[0]))
								{
								?>
                                  <tr class='table-unread' >
                                   <td style="text-align:center"><?php  echo ++$i;?></td>
                                    <td><?php echo $rst1['name'];  ?></td>
                                    <td style="text-align:center"><?php echo $rst1['bio_id'];  ?></td>
                                    <td><?php echo $rst1['date']; ?></td>
                                    <td style="text-align:center"><?php if($rst1['in'] != '00:00:00')echo $rst1['in']; else echo "Abscent"; ?></td>
                                    <td style="text-align:center"><?php if($rst1['out'] != '00:00:00')echo $rst1['out']; else echo "Abscent"; ?></td>
                                    <td style="text-align:center"><?php if($rst1['manual'])echo 'YES'; else echo "NO"; ?></td>
                                    <td style="text-align:center"><?php echo $rst1['work']; ?></td>
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