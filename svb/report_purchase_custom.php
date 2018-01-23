<?php
include"header.php";
include"classes/get_name.php";
?>
    <div class="box" style="min-height:450px;">
      <div class="box-head">
        <h3>Purchase Custom Report</h3>
      </div>
      <?php
		$from = date('Y-m-d',mktime(0,0,0,date('m'),date('d')-30,date('Y'),0));
		$to = date('Y-m-d');
		if(isset($_POST['from']))
			$from = $_POST['from'];
		if(isset($_POST['to']))
			$to = $_POST['to'];
		?>
      <form action="#" method="post">
      <table class="table table-striped table-bordered" style="width:250px; overflow:visible" align="center">
        <thead>
          <tr>
          	<td style="white-space:nowrap">Filter : </td>
            <td><input type="text" name="from" class="datepick input-small" value="<?php echo $from ?>" ></td>
            <td style="padding-top:15px;">To</td>
            <td><input type="text" name="to" class="datepick input-small" value="<?php echo $to ?>" ></td>
            <td>
			Columns
            <select name="column[]" class="cho" multiple>
            <option value="bill_no" <?php if(in_array('bill_no',$_POST['column'])) echo 'selected'; ?> >Bill No</option>
            <option value="date" <?php if(in_array('date',$_POST['column'])) echo 'selected'; ?> >Date</option>
            <option value="pur_bill" <?php if(in_array('pur_bill',$_POST['column'])) echo 'selected'; ?> >Recieved Bill No</option>
            <option value="bill_date" <?php if(in_array('bill_date',$_POST['column'])) echo 'selected'; ?> >Recieved Bill Date</option>
            <option value="biller_id" <?php if(in_array('biller_id',$_POST['column'])) echo 'selected'; ?> >Biller</option>
            <option value="customer_id" <?php if(in_array('customer_id',$_POST['column'])) echo 'selected'; ?> >Customer</option>
            <option value="tax_amnt" <?php if(in_array('tax_amnt',$_POST['column'])) echo 'selected'; ?> >Tax Amount</option>
            <option value="bill_amount" <?php if(in_array('bill_amount',$_POST['column'])) echo 'selected'; ?> >Bill Amount</option>
            <option value="actual_amount" <?php if(in_array('actual_amount',$_POST['column'])) echo 'selected'; ?> >Actual Amount</option>
            <option value="recieved_amount" <?php if(in_array('recieved_amount',$_POST['column'])) echo 'selected'; ?> >Recieved Amount</option>
            <option value="returned_amount" <?php if(in_array('returned_amount',$_POST['column'])) echo 'selected'; ?> >Returned Amount</option>
            <option value="round_off" <?php if(in_array('round_off',$_POST['column'])) echo 'selected'; ?> >Round Off</option>
            <option value="discount" <?php if(in_array('discount',$_POST['column'])) echo 'selected'; ?> >Discount</option>
            <option value="discount_reason" <?php if(in_array('discount_reason',$_POST['column'])) echo 'selected'; ?> >Discount Reason</option>
            <option value="tot_products" <?php if(in_array('tot_products',$_POST['column'])) echo 'selected'; ?> >Tot No. of Products</option>
            </select></td>
            <td style="white-space:nowrap">
            	<input type="checkbox" name="lock" value="1" <?php if(isset($_POST['lock'])) echo 'checked' ?>   >&nbsp;&nbsp;Locked<br>
	            <input type="checkbox" name="trans_close" value="1" <?php if(isset($_POST['trans_close'])) echo 'checked' ?> >&nbsp;&nbsp;Payment Completed<br><br>
                <u>Bill Status</u><br>
                <div style="padding-left:15px;"><input type="radio" name="status" value="s" <?php if($_POST['status']=='s') echo 'checked' ?> >&nbsp;&nbsp;Pending<br>
                <input type="radio" name="status" value="l" <?php if($_POST['status']=='l') echo 'checked' ?> >&nbsp;&nbsp;Locked<br>
                <input type="radio" name="status" value="c" <?php if($_POST['status']=='c') echo 'checked' ?> >&nbsp;&nbsp;Cancelled<br>
                </div>
            	</td>
            <td><input type="submit" name="submit" value="Load Report" class="btn btn-blue4"></td>
          </tr>
        </thead>
      </table>
      </form>
    <?php
		if(isset($_POST['submit']))
		{
			?>
            <table class="table table-striped table-bordered" align="center" style="width:1px; overflow:visible">
            <thead><tr><td>Sno</td>
            <?php
			foreach($_POST['column'] as $c)
			{
				?>
                <th><?php echo $c; ?></th>
                <?php
			}
			?>
            </tr></thead>
            <?php
			$sql = "SELECT `id`,`".implode('`,`',$_POST['column'])."` from purchase where `is`=1 and date between '".$from."' and '".$to."' ";
			if(isset($_POST['lock']))
			{
				$sql.=' and `lock`=1 ';
			}
			if(isset($_POST['trans_close']))
			{
				$sql.=' and `trans_close`=1 ';
			}
			if(isset($_POST['status']))
			{
				$sql.=" and `status`='".$_POST['status']."' ";
			}
			$bill_detail = $mysql->execute($sql);
			while($p= mysqli_fetch_array($bill_detail[0]))
			{
				?>
                <tr>
               	<td style="text-align:center"><strong><?php echo ++$sno; ?></strong></td>
                <?php
				foreach($_POST['column'] as $c)
				{
					?>
					<td style="text-align:right; white-space:nowrap">
					<?php 
					switch($c)
					{
						case 'biller_id':
							echo $name->employee($p[$c]);
							break;
						case 'customer_id':
							echo $name->dealer($p[$c]);
							break;
						case 'date':
							echo $mysql->date_format($p[$c]);
							break;
						default:
							if(strstr($c,'amnt')!='' || strstr($c,'amount')!='')
							{
								echo $mysql->currency($p[$c]);
							}
							else if($c=='bill_no')
							{
								?><a href="purchase_detail.php?billno=<?php echo $p[$c] ?>"><?php echo $p[$c]; ?></a><?php
							}
							else
							{
								echo $p[$c];
							}
							break;
							
					}
					 ?>
                    </td>
					<?php
				}
				?>
                </tr>
                <?php	
			}
			?>
            </table>
            <?php
		}
	?>
    </div>
    <?php
include"footer.php";
?>


