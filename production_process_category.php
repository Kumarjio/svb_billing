<?php
include"header.php";
?>
<div class="box">
<div class="box-head"><h3>Process Category</h3></div>
	<center>
    <form action="#" method="post">
    <table class="table table-bordered table-striped" style="width:25%">
    	<tr><th>Name</th><td><input type="text" name="name"  /></td><td><input type="submit" name="submit" class="btn btn-blue4" /></td></tr>
    </table>
    </form><?php
		if(isset($_POST['submit'])){
			if($_POST['name'] != ''){
				$sql = "INSERT INTO `production_category` (`name`) VALUES ('".addslashes($_POST['name'])."')";
				$mysql->execute($sql);
			}
		}
		if(isset($_POST['update'])){
			foreach($_POST['name'] as $id=>$name){
				$sql = "update `production_category`
						set `name` = '".addslashes($name)."'
						where `id` =".$id;
				$mysql->execute($sql);
			}
		}
		if(isset($_POST['delete'])){
			foreach($_POST['delete'] as $id=>$name){
				$sql = "update `production_category`
						set `is` = 0
						where `id` =".$id;
				$mysql->execute($sql);
			}	
		}
		?>
    <form action="#" method="post">
    <table class="table table-bordered table-striped" style="width:25%">
    <thead><th>SNO</th><th>NAME</th><th>DEL</th></thead>
    <?php
		$sql = "select * from `production_category` where `is`=1";
		$rst = $mysql->execute($sql);
		while($r = mysqli_fetch_array($rst[0])){?>
        	<tr>
            <td><?php echo ++$sno ?></td>
            <td><input type="text" name="name[<?php echo $r['id'] ?>]" value="<?php echo $r['name'] ?>" /></td>
            <td><input type="submit" name="delete[<?php echo $r['id'] ?>]" value="DELETE" /></td>
            </tr><?php
		}
	?>
    <tr><td colspan="3" style="text-align:center">
    <input type="submit" name="update" class="btn btn-blue4" />
    </td></tr>
    </table>
    </form>
    </center>
</div>
<?php
include"footer.php";
?>