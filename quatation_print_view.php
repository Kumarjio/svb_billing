<?php
include"header.php";
include"classes/quatation.php";
?>
<div class="box noprint">
        <div class="box-head"><h3>Quatation Print View</h3></div>
<form action="" method="post" class="noprint">
      
        <table align="center" border="0" class="noprint">
          <tr>
            <th>Quatation No </th>
            <td><input type="text" name="billno" class="text"></td>
          </tr>
          <tr>
            <th></th>
            <th><input type="submit" name="open" value="Open" class="btn btn-blue4"></th>
          </tr>
        </table>
    </form>
<div class="print_data">
<?php
if(isset($_POST['open']))
{
	$billing->print_output($_POST['billno']);
}
?>
</div>
<link rel='stylesheet' type='text/css' href='css/bill_style.css' />
</div>
<?php
include"footer.php";
?>