<?php
include"header.php";
?>
<center>
    <div class="box" style="width:60%">
      <div class="box-head"><h3>Additional Charges Default Values</h3></div>
      <form action="#" method="post">
      <table class="table table-striped table-bordered">
      <?php
	  $sql = "select a.id,a.name from shop_charges as a where a.type_source='custom' and a.`type`='select' and a.`is`=1";
	  $rst = $mysql->execute($sql);
	  while($r = mysqli_fetch_array($rst[0]))
	  {
		  ?>
          <tr><th style="vertical-align:middle; text-align:right"><input type="hidden" name="id" value="<?php echo $r['id'] ?>">
		  <?php echo $r['name'] ?></th>
          <th>
          <?php 
		  $sql = "select s.id,s.value,s.name from shop_charges_list as s where s.att_id='10' and s.`is`=1";
		  $rst1 = $mysql->execute($sql);
		  while($r1 = mysqli_fetch_array($rst1[0]))
		  {
			  ?>
              <input type="hidden" name="aid" value="<?php echo $r1['id']; ?>">
              <input type="text" name="name" value="<?php echo $r1['name']; ?>"> : 
              <input type="text" name="value" value="<?php echo $r1['value']; ?>"><br>
              <?php
		  }
		  ?>
          </th>
          </tr>
          <?php
	  }
	  ?>
      </table>
      </form>
    </div>
</center>
    <?php
include"footer.php";
?>