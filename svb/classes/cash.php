<script type="text/javascript">
$(document).ready(function(e) {
	calcNet();
    $(".cash").keyup(function(e) {
        calcNet();
    });
	$(".cash").click(function(e) {
        $(this).select();
    });
});
function calcNet()
{
	no = 0;
	net = 0
    $(".cash").each(function(index, element) {
        tot = parseFloat($(this).val()*$(".note").eq(index).val());
		if(tot!='')
		{
		$(".tot").eq(index).val(tot);
		no += parseFloat($(this).val()); 
		net += parseFloat(tot); 
		}
		else
		{
			$(".tot").eq(index).val(0);
		}
    });
	$("#netcash").val(no);
	$("#nettot").val(net);
}
</script>
<style type="text/css">
.cash{
	text-align:right;
}
.tot{
	text-align:right;
}
</style>
<table width="100%" class="table table-bordered table-striped" style="font-weight:bold" cellpadding="10">
  <thead>
    <tr>
      <th colspan="5">Cash Entry</th>
    </tr>
    <tr>
      <th width="10%">Sno</th>
      <th width="39%">Cash</th>
      <th></th>
      <th width="9%">No</th>
      <th width="40%">Total</th>
    </tr>
  </thead>
  <tbody>
    <?php $sno = 0; ?>
    <tr>
      <td style="text-align:center"><?php echo ++$sno ?></td>
      <td style="text-align:right">1000</td>
      <td>X</td>
      <td style="text-align:center"><input type="text" name="cash[]" autocomplete="off" id="cash" class="input-mini input-square cash" value="<?php echo $cash[1000] ?>">
        <input type="hidden" class="note" name="note[]" value="1000"></td>
      <td style="text-align:right"><input type="text" id="tot" disabled class="input-mini input-square tot" value="<?php echo $cash[1000]*1000 ?>"></td>
    </tr>
    <tr>
      <td style="text-align:center"><?php echo ++$sno ?></td>
      <td style="text-align:right">500</td>
      <td>X</td>
      <td style="text-align:center"><input type="text" name="cash[]" autocomplete="off" id="cash" class="input-mini input-square cash" value="<?php echo $cash[500] ?>">
        <input type="hidden" class="note" name="note[]" value="500"></td>
      <td style="text-align:right"><input type="text" id="tot" disabled class="input-mini input-square tot" value="<?php echo $cash[500]*500 ?>"></td>
    </tr>
    <tr>
      <td style="text-align:center"><?php echo ++$sno ?></td>
      <td style="text-align:right">100</td>
      <td>X</td>
      <td style="text-align:center"><input type="text" name="cash[]" autocomplete="off" id="cash" class="input-mini input-square cash" value="<?php echo $cash[100] ?>">
        <input type="hidden" class="note" name="note[]" value="100"></td>
      <td style="text-align:right"><input type="text" id="tot" disabled class="input-mini input-square tot" value="<?php echo $cash[100]*100 ?>"></td>
    </tr>
    <tr>
      <td style="text-align:center"><?php echo ++$sno ?></td>
      <td style="text-align:right">50</td>
      <td>X</td>
      <td style="text-align:center"><input type="text" name="cash[]" autocomplete="off" id="cash" class="input-mini input-square cash" value="<?php echo $cash[50] ?>">
        <input type="hidden" class="note" name="note[]" value="50"></td>
      <td style="text-align:right"><input type="text" id="tot" disabled class="input-mini input-square tot" value="<?php echo $cash[50]*50 ?>"></td>
    </tr>
    <tr>
      <td style="text-align:center"><?php echo ++$sno ?></td>
      <td style="text-align:right">20</td>
      <td>X</td>
      <td style="text-align:center"><input type="text" name="cash[]" autocomplete="off" id="cash" class="input-mini input-square cash" value="<?php echo $cash[20] ?>">
        <input type="hidden" class="note" name="note[]" value="20"></td>
      <td style="text-align:right"><input type="text" id="tot" disabled class="input-mini input-square tot" value="<?php echo $cash[20]*20 ?>"></td>
    </tr>
    <tr>
      <td style="text-align:center"><?php echo ++$sno ?></td>
      <td style="text-align:right">10</td>
      <td>X</td>
      <td style="text-align:center"><input type="text" name="cash[]" autocomplete="off" id="cash" class="input-mini input-square cash" value="<?php echo $cash[10] ?>">
        <input type="hidden" class="note" name="note[]" value="10"></td>
      <td style="text-align:right"><input type="text" id="tot" disabled class="input-mini input-square tot" value="<?php echo $cash[10]*10 ?>"></td>
    </tr>
    <tr>
      <td style="text-align:center"><?php echo ++$sno ?></td>
      <td style="text-align:right">5</td>
      <td>X</td>
      <td style="text-align:center"><input type="text" name="cash[]" autocomplete="off" id="cash" class="input-mini input-square cash" value="<?php echo $cash[5] ?>">
        <input type="hidden" class="note" name="note[]" value="5"></td>
      <td style="text-align:right"><input type="text" id="tot" disabled class="input-mini input-square tot" value="<?php echo $cash[5]*5 ?>"></td>
    </tr>
    <tr>
      <td style="text-align:center"><?php echo ++$sno ?></td>
      <td style="text-align:right">2</td>
      <td>X</td>
      <td style="text-align:center"><input type="text" name="cash[]" autocomplete="off" id="cash" class="input-mini input-square cash" value="<?php echo $cash[2] ?>">
        <input type="hidden" class="note" name="note[]" value="2"></td>
      <td style="text-align:right"><input type="text" id="tot" disabled class="input-mini input-square tot" value="<?php echo $cash[2]*2 ?>"></td>
    </tr>
    <tr>
      <td style="text-align:center"><?php echo ++$sno ?></td>
      <td style="text-align:right">1</td>
      <td>X</td>
      <td style="text-align:center"><input type="text" name="cash[]" autocomplete="off" id="cash" class="input-mini input-square cash" value="<?php echo $cash[1] ?>">
        <input type="hidden" class="note" name="note[]" value="1"></td>
      <td style="text-align:right"><input type="text" id="tot" disabled class="input-mini input-square tot" value="<?php echo $cash[1] ?>"></td>
    </tr>
    <tr>
      <td style="text-align:center"><?php echo ++$sno ?></td>
      <td style="text-align:right">.50</td>
      <td>X</td>
      <td style="text-align:center"><input type="text" name="cash[]" autocomplete="off" id="cash" class="input-mini input-square cash" value="<?php echo $cash[0.5] ?>">
        <input type="hidden" class="note" name="note[]" value="0.5"></td>
      <td style="text-align:right"><input type="text" id="tot" disabled class="input-mini input-square tot" value="<?php echo $cash[0.5]*0.5 ?>"></td>
    </tr>
  <tfoot>
    <tr>
      <th colspan="3" style="text-align:right">Net Total</th>
      <th style="text-align:center"><input type="text" id="netcash" style="text-align:right" disabled class="input-mini input-square"></th>
      <th style="text-align:right"><input type="text" id="nettot" style="text-align:right" disabled class="input-mini input-square"></th>
    </tr>
  </tfoot>
    </tbody>
  
</table>
