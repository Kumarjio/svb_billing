<?php
include"header.php";
?>
<script type="text/javascript">
$(document).ready(function(e) {
	 <?php if($_REQUEST['from']!=2){ ?>
    $("#for").slideUp('100');
	<?php }else{ ?>
	$("#date").slideUp('100');
	<?php } ?>
});
function dt(val)
{
	if(val == 0 || val ==1)
	{
		$("#for").css("visibility","hidden");
		$("#date").css("visibility","visible");
		$("#for").slideUp('100');
		$("#date").slideDown('100');
	}
	else
	{
		$("#for").css("visibility","visible");
		$("#date").css("visibility","hidden");
		$("#date").slideUp('100');
		$("#for").slideDown('100');
	}
}
</script>
    <div class="box">
      <div class="box-head"><h3>Cash Info</h3></div>
      <form action="#" method="post">
      <table cellpadding="5" cellspacing="5" align="center" border="0">
        <tr>
          <th>From</th>
          <td><select name="from" onChange="dt(this.value)">
              <option value="0" <?php if($_REQUEST['from']==0) echo 'selected' ?> >Opening Cash</option>
              <option value="1" <?php if($_REQUEST['from']==1) echo 'selected' ?> >Closing Cash</option>
              <option value="2" <?php if($_REQUEST['from']==2) echo 'selected' ?> >Bill</option>
            </select></td>
        </tr>
        <tr id="for" <?php if($_REQUEST['from']!=2){ ?> style="visibility:hidden;" <?php } ?> >
          <th>For</th>
          <td><input type="text" name="for" value="<?php echo $_REQUEST['for'] ?>"></td>
        </tr>
        <tr id="date" <?php if($_REQUEST['from']==2){ ?> style="visibility:hidden;" <?php } ?> >
          <th>Date</th>
          <td><input type="text" name="date" class="datepick" value="<?php echo $_REQUEST['date'] ?>"></td>
        </tr>
        <tr><th colspan="2" align="center"><input type="submit" name="submit" value="Check" class="btn btn-blue4" ></th></tr>
      </table>
      </form>
      <center>
        <div style="width:500px">
        <?php
		if(isset($_REQUEST['from']))
		{
			$for = $_REQUEST['for'];
			if($_REQUEST['date']!='' && $_REQUEST['from']!=2)
			$for = str_replace("/","-",$_REQUEST['date']);
			$from = $_REQUEST['from'];
			$sql = "select * from cash where (`for`='".$for."' or `date`='".$for."') and `from`='".$from."' and `is`=1;";
			$rst = $mysql->execute($sql);
			$cash = array();
			while($r= mysqli_fetch_array($rst[0]))
			{
				$cash[$r['amount']] = $r['no'];
			}
			if(count($cash)>0)
			include"classes/cash.php";
			else
			{
				?>
				<div class="alert alert-block alert-danger">
								  <a class="close" data-dismiss="alert" href="#">×</a>
								  <h4 class="alert-heading">No Data Found!</h4>
								</div>
				<?php
			}
		}
		?>
        </span>
      </center>
    </div>
    <?php
include"footer.php";
?>