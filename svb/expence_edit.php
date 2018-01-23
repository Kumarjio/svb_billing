<?php
include"header.php"
?>
<form action="expence_view.php" method="post">
<div class="box">
<div class="box-head"><h3>Expences</h3></div>
<table align="center" cellpadding="10" cellspacing="10">
<thead>
<?php
$sql = "select * from expences where `id`=".$_POST['id'].";";
$rst = $mysql->execute($sql);
$data= mysqli_fetch_array($rst[0]);
?>
<input type="hidden" name="id" value="<?php echo $data['id']; ?>">
<tr><th>Date</th><th><input type="text" class="datepick" name="date"  value="<?php echo $data['date'] ?>" ></th></tr>
<tr><th>Expence</th><td>
<select name="exp" class="cho">
<?php
$sql = "select * from expences_type;";
$rst = $mysql->execute($sql);
while($r= mysqli_fetch_array($rst[0]))
{
?>
<option <?php if($r['id'] == $data['e_id']) echo 'selected'; ?> value="<?php echo $r['id'] ?>"> <?php echo $r['name'] ?></option>
<?php
}
?>
</select>
</td></tr>
<tr><th>Amount</th><th><div class="controls">
              <div class="input-prepend input-append"> <span class="add-on"><b><del>&#2352;</del></b></span>
                <input type="text" class='input-square' value="<?php echo $data['amount'] ?>" name="amnt" id="twoicons" />
                <span class="add-on">.00</span> </div>
            </div></th></tr>
<tr><th>Person</th><th><input type="text" name="per" value="<?php echo $data['person'] ?>" ></th></tr>
<tr><th>Reason</th><th><textarea name="rea"><?php echo $data['reason'] ?></textarea></th></tr>
<tr><th colspan="2" align="center"><input type="submit" name="update" value="Update" class="btn btn-blue4" > 
<input type="submit" name="delete" value="Remove" class="btn btn-red4" ></th></tr>
</thead>
</table>
</div>
</form>
<?php
include"footer.php"
?>