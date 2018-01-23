<?php
include"header.php"; 
?>
<?php
$billno = $_REQUEST['billno'];
$sql = "select 
			 cs.id,cs.name,cs.phone,cs.address,cs.tin_no,cs.cst_no
		from 
			wb as w,
			customers as cs
		where w.billno='".$billno."' and w.customer=cs.id limit 1";
$rst = $mysql->execute($sql);
$r = mysqli_fetch_array($rst[0]);
$customer[] = $r['id'];
$customer[] = $r['name'];
$customer[] = $r['phone'];
$customer[] = $r['address'];
$customer[] = $r['tin_no'];
$customer[] = $r['cst_no'];
?>
<link rel='stylesheet' type='text/css' href='css/bill_style.css' />
<div id="bill_out">
        <div style="clear:both; height:0px;"></div>
        <div id="customer">
          <div id="customer-title"><span style="font-size:16px;">To:</span>
            <p style="padding-left:25px;"><strong><span style="font-size:16px;"><?php 
			 if($customer[1]!='')
				echo $customer[1].","; 
			?></span><br />
              <span style="font-size:14px;"><?php 
					if($customer[3]!='')
					{
						$add = explode(",",$customer[3]);
						if($add[0]!='')
							echo $add[0];						
						if($add[1]!='')
							echo ",<br>".$add[1];
						if($add[2]!='')
							echo ",<br>".$add[2];
						if($add[3]!='')
							echo ",<br>".$add[3];
						if($add[4]!='')
							echo ",<br>".$add[4];
						if($add[5]!='')
							echo ",<br>".$add[5];
						if($add[0]!='')
							echo '.';
					}
					if($customer[2]!='')
						echo '<br>Ph : '.$customer[2];
                    ?></span></strong></p>
          </div>
          <table style="float:right;">
          	<?php
			if($duplicate==1)
			{
				?>
                <tr>
              <td style="border:none;font-size:16px;">Duplicate</td>
              <td style="border:none;"></td>
              <td style="border:none;font-size:16px;"></td>
            </tr>
                <?php
			}
			?>
            <tr>
              <td style="border:none;font-size:16px;">Invoice No.</td>
              <td style="border:none;">:</td>
              <td style="border:none;font-size:16px; text-align:right"><strong><?php echo $billno;  ?></strong></td>
            </tr>
            <tr>
              <td style="border:none;font-size:16px;vertical-align:top">Date</td>
              <td style="border:none;vertical-align:top">:</td>
              <td style="border:none;font-size:16px;"><strong><?php echo date('d-m-Y');  ?></strong></td>
            </tr>
          </table>
        </div>
        <br />
        <p style="font-size:15px;">Customer TIN NO. :<?php echo $customer[4];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Customer CST NO. : </p>
        <table id="items">
          <tr style="height:10px;">
            <th style="border-top:#000 1px dashed;border-bottom:1px dashed #000; border-left:1px solid #000; font-size:12px;">S.No</th>
            <th style="border-top:#000 1px dashed;border-bottom:1px dashed #000;font-size:12px;">P.No</th>
            <th style="border-top:#000 1px dashed;border-bottom:1px dashed #000;font-size:12px;">Product Name</th>
            <th style="border-top:#000 1px dashed;border-bottom:1px dashed #000;font-size:12px;">Qty</th>
            <th style="border-top:#000 1px dashed;border-bottom:1px dashed #000;font-size:12px;">Price</th>
            <th style="border-top:#000 1px dashed;border-bottom:1px dashed #000;font-size:12px;">Net</th>
            <th style="border-top:#000 1px dashed;border-bottom:1px dashed #000; border-right:1px solid #000;font-size:12px;">Ps</th>
          </tr>
          <?php
		  $product_net = 0;
		  $billData = "select 
						bo.bill_no,
						date_format(bo.`date`,'%d-%m-%Y')'date',
						bo.actual_amount,
						bo.recieved_amount,
						bo.discount,
						bo.product_name,
						bo.product_code,
						bo.qty,
						bo.`type`,
						bo.unit_price,
						bo.vat,
						bo.oactual,
						bo.orecieved ,
						bo.odiscount,
						bo.tax_amnt,
						bo.round_off,
						bo.charges_amnt,
						bo.bill_amount
					from 
						bill_orders as bo 
					where 
						bo.bill_no='".$billno."';";
			$rst = $this->execute($billData);
			$this->loadCustomer($billno);
			$customer = $this->getCustomer();
			$porders = array();
			while($r = mysqli_fetch_array($rst[0]))
			{
				$pbillno = $r['bill_no'];
				$pdate = $r['date'];
				$actual_amount = $r['actual_amount'];
				$precieved = $r['recieved_amount'];
				$pdiscount = $r['discount'];
				$tax_amount = $r['tax_amnt'];
				$round_off = $r['round_off'];
				$charges_amount = $r['charges_amnt'];
				$bill_amount = $r['bill_amount'];
				$porders[$r['product_name']]['qty'] = $r['qty'];
				$porders[$r['product_name']]['type'] = $r['type'];
				$porders[$r['product_name']]['vat'] = $r['vat'];
				$porders[$r['product_name']]['product_code'] = $r['product_code'];
				$porders[$r['product_name']]['unit_price'] = $r['unit_price'];
				$porders[$r['product_name']]['actual'] = $r['oactual'];
				$porders[$r['product_name']]['recieved'] = $r['orecieved'];
				$porders[$r['product_name']]['discount'] = $r['odiscount'];
			}
          foreach($porders as $prd=>$ord)
          {
			  $prod_rate = number_format(($porders[$prd]['recieved']),2);
			  $prod_rate = explode(".",$prod_rate);
          ?>
          <tr class="item-row">
            <td class="sno" style="border-right:#000 1px solid; border-left:#000 1px solid"><?php echo ++$sno ?></td>
            <td class="pno" style="border-right:#000 1px solid;">&nbsp;<?php echo $porders[$prd]['product_code']; ?>&nbsp;</td>
            <td class="item-name" style="border-right:#000 1px solid;">&nbsp;<?php echo html_entity_decode($prd); ?>&nbsp;</td>
            <td class="qty" style="border-right:#000 1px solid"><?php echo $porders[$prd]['qty']; ?></td>
            <td class="unit_cost" style="border-right:#000 1px solid"><?php echo number_format(($porders[$prd]['recieved']/$porders[$prd]['qty']),2); ?></td>
            <td class="price" style="border-right:#000 1px solid"><?php echo $prod_rate[0]; ?></td>
            <td class="paise" style="border-right:#000 1px solid"><?php echo $prod_rate[1] ?></td>
          </tr>
          <?php
		  $product_net += $porders[$prd]['recieved'];
          }
          if($sno==18)
          {
          for($i=1;$i<=18;$i++)
          {
          ?>
          <tr class="item-row">
            <td class="sno" style="border-right:#000 1px solid; border-left:#000 1px solid"></td>
            <td class="pno" style="border-right:#000 1px solid;"></td>
            <td class="item-name" style="border-right:#000 1px solid;"></td>
            <td class="qty" style="border-right:#000 1px solid"></td>
            <td class="unit_cost" style="border-right:#000 1px solid"></td>
            <td class="price" style="border-right:#000 1px solid"></td>
            <td class="paise" style="border-right:#000 1px solid"></td>
          </tr>
          <?php
          }
          }
          if($sno<15)
          {
              $tmp =15-$sno;
          for($i=1;$i<=$tmp;$i++)
          {
          ?>
          <tr class="item-row">
            <td class="sno" style="border-right:#000 1px solid; border-left:#000 1px solid"></td>
            <td class="pno" style="border-right:#000 1px solid;"></td>
            <td class="item-name" style="border-right:#000 1px solid;"></td>
            <td class="qty" style="border-right:#000 1px solid"></td>
            <td class="unit_cost" style="border-right:#000 1px solid"></td>
            <td class="price" style="border-right:#000 1px solid"></td>
            <td class="paise" style="border-right:#000 1px solid"></td>
          </tr>
          <?php
          }
          }
          ?>
          <tr class="noboder">
            <td class="sno" style="border-right:#000 1px solid;border-left:#000 1px solid"></td>
            <td class="pno" style="border-right:#000 1px solid;"></td>
            <td class="item-name" style="border-right:#000 1px solid; border-bottom:#000 1px solid"></td>
            <td class="qty" style="border-right:#000 1px solid; border-bottom:#000 1px solid"></td>
            <td class="unit_cost" style="border-right:#000 1px solid; border-bottom:#000 1px solid"></td>
            <td class="price" style="border-right:#000 1px solid; border-bottom:#000 1px solid"></td>
            <td class="paise" style="border-right:#000 1px solid; border-bottom:#000 1px solid"></td>
          </tr>
          <tr style="height:10px;">
            <td colspan="3" class="blank" style="border-top:#000 1px solid; border-left:#000 1px solid"></td>
            <td colspan="2" class="total-line" style="border-left:1px #000000 solid;border-right:#000 1px solid">TOTAL</td>
            <td class="total-value" style="border-right:#000 1px solid;"><?php 
			$product_net = number_format($product_net,2);
			$product_net = explode(".",$product_net);
			echo $product_net[0] ?></td>
            <td class="total-value" style="border-right:1px #000000 solid"><?php 
			echo $product_net[1]
			?></td>
          </tr>
          <tr style="height:10px;">
            <td colspan="3" class="blank" style="border-top:none; border-left:#000 1px solid"></td>
            <td colspan="2" class="total-line" style="border-left:1px #000000 solid;border-right:#000 1px solid">VAT(5%)</td>
            <td class="total-value" style="border-right:#000 1px solid;"><?php 
			$tax_amount = number_format($tax_amount,2);
			$tax_amount = explode(".",$tax_amount);
			echo $tax_amount[0]
			?></td>
            <td class="total-value" style="border-right:1px #000000 solid"><?php echo $tax_amount[1] ?></td>
          </tr>
          <?php 
		  $product_net = str_replace(",","",$product_net[0].'.'.$product_net[1]);
		  $tax_amount = str_replace(",","",$tax_amount[0].'.'.$tax_amount[1]);
		  $total = floatval($tax_amount)+floatval($product_net);
		  $total = number_format($total,2);
		  $total = explode(".",$total);
		  ?>
          <tr style="height:10px;">
            <td colspan="3" class="blank" style="border-top:none; border-left:#000 1px solid"></td>
            <td colspan="2" class="total-line" style="border-left:1px #000000 solid;border-right:#000 1px solid">VAT + TOTAL</td>
            <td class="total-value" style="border-right:#000 1px solid;"><?php echo $total[0]?></td>
            <td class="total-value" style="border-right:1px #000000 solid">
            <?php echo $total[1]?>
            </td>
          </tr>
          <tr style="height:10px;">
            <td colspan="3" class="blank total-line" style="border-top:none; border-left:#000 1px solid;border-right:#000 1px solid"><strong>No. of Bundles</strong>: 
			<?php 
			$sql = "select 
						c.value 
					from 
						bill_charges as c where c.bill_no='".$billno."' and c.c_id=2";
			$rst = $this->execute($sql);
			$r = mysqli_fetch_array($rst[0]);
			echo $r['value'];
			?></td>
            <td colspan="2" class="total-line" style="border-left:1px #000000 solid;border-right:#000 1px solid">ROUND OFF</td>
            <td style="border-left:none;text-align:right;border-right:#000 1px solid;">
            <?php
			if($total[1]<50)
			{
				echo '-';
			}
			else
			{
				echo '+';
				$total[1] = 100-$total[1];
			}
			?>
            </td>
            <td class="total-value" style="border-right:1px #000000 solid"><?php
			$round_tmp = explode(".",$round_off);
			if($round_tmp[1][2]!='')
			{
				if($round_tmp[1][2]>5)
					$round_tmp[1][1]=$round_tmp[1][1]+1;
				$round_tmp[1][0].$round_tmp[1][1];
			}
			else
				$round_tmp[1][0].$round_tmp[1][1];
			echo $total[1];	
			 ?></td>
          </tr>
          <tr style="height:10px;">
            <td colspan="3" class="blank total-line" style="border-top:none; border-left:#000 1px solid"><strong>Amount in Words</strong></td>
            <td colspan="2" class="total-line" style="border-left:1px #000000 solid;border-right:#000 1px solid">PACKING</td>
            <td class="total-value" style="border-right:#000 1px solid;"><?php 
			$charges_amount = number_format($charges_amount,2);
			$charges_amount = explode(".",$charges_amount);
			echo $charges_amount[0] ?></td>
            <td style="border-right:1px #000000 solid; text-align:right"><?php echo $charges_amount[1] ?></td>
          </tr>
          <tr style="height:15px;">
            <td colspan="3" class="blank total-line" style="border-bottom:#000 1px solid; border-left:#000 1px solid; font-size:14px;">
            &nbsp;&nbsp;&nbsp;&nbsp;Rupees <?php echo ucwords($this->no_to_words($bill_amount))?> Only.</td>
            <td colspan="2" class="total-line" style="border-left:1px #000000 solid; border-bottom:1px solid #000;border-right:#000 1px solid">GRAND TOTAL</td>
            <td class="balance" style="border-bottom:1px #000000 solid;border-right:#000 1px solid;"><strong><?php echo number_format($bill_amount)?></strong></td>
            <td style="border-bottom:1px #000000 solid;border-right:1px #000000 solid; text-align:right">00</td>
          </tr>
        </table><br />
        <br /><br />
        
        <div style="float:right"> For,<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SVB<br />
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Auto Products</strong> </div>
        </div>
<?php
include"footer.php";
?>