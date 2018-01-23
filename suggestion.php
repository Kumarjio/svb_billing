<?php
include("\classes\mysql.php");
$flag=1;
$key=$_POST['key'];
$val=$_POST['val'];
if($key=="dealer")
{
$sql="select * from dealer where dealer_name like '".$val."%' or email like '".$val."%' or dealer_phone like '".$val."%' or company_name like '".$val."%' or company_phone like '".$val."%' or company_address like '".$val."%' ;";
$rst=$mysql->execute($sql);
echo '<table class="table table-striped" data-rowlink="a"><tbody>';
if($val!="")
while($row=mysqli_fetch_array($rst[0]))
{
echo '<tr id="sugid'.$flag++.'" onClick="suggested('.$row['id'].',\''.$row['dealer_name'].'\',\''.$row['dealer_phone'].'\',\''.$row['company_name'].'\',\''.$row['company_phone'].'\',\''.$row['company_address'].'\')" ><td><img src="'.$row['photo'].'" height="20" width="20" /></td>
<td>'.$row['dealer_name'].'</td><td>'.$row['dealer_phone'].'</td><td>'.$row['company_name'].'</td></tr>';
}
echo '</tbody></table>';
}
//'+$row['']+'
?>
