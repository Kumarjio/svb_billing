<?php 
include"header.php";
?>
    <form action="#" method="post">
    <div class="box">
    <div class="box-head">
      <h3>Products Group</h3>
    </div>
    <?php
if(isset($_POST['create']))
{
	$sql = "INSERT INTO `product_group` (`name`, `desc`) VALUES ('".$_POST['name']."', '".$_POST['desc']."');";
	$mysql->execute($sql);
	?>
    <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
      <h4 class="alert-heading">Added Successfully!</h4>
    </div>
    <?php
}
if(isset($_POST['remove']))
{
	$sql = "UPDATE `product_group` set `is`=0 where `id`='".$_POST['id']."'";
	$mysql->execute($sql);
	?>
    <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
      <h4 class="alert-heading">Removed Successfully!</h4>
    </div>
    <?php
}
?>
    <table align="center" cellpadding="5" cellspacing="5" >
      <thead>
        <tr>
          <th>Name</th>
          <th><input type="text" name="name" ></th>
        </tr>
        <tr>
          <th>Description</th>
          <th><textarea name="desc"></textarea></th>
        </tr>
        <tr>
          <th colspan="2" align="center"><input type="submit" name="create" value="Create" class="btn btn-blue4" ></th>
        </tr>
      </thead>
    </table>
    <table align="center" class="table table-striped table-bordered" cellpadding="5" cellspacing="5" >
    <thead>
      <tr>
        <th>Sno</th>
        <th>Name</th>
        <th>Description</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php
$sql = "select * from product_group where `is`=1 order by id";
$rst = $mysql->execute($sql);
while($r= mysqli_fetch_array($rst[0]))
{
?>
      <tr>
        <td style="text-align:center"><?php echo ++$cnt; ?></td>
        <td><?php echo $r['name']; ?></td>
        <td><?php echo $r['desc']; ?></td>
        <td>
    <form action="#" method="post">
      <input type="hidden" name="id" value="<?php echo $r['id'] ?>">
      <button type="submit" name="remove" class="btn btn-red4" onClick="cancelSubmit(event)">Remove</button>
    </form>
    </td>
    </tr>
    <?php
}
?>
    </tbody>
    </table>
  </div>
  </form>
  <?php 
include"footer.php";
?>