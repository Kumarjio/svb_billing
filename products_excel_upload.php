<?php include"header.php"; ?>
    <div class="box">
      <div class="box-head">
        <h3>Product Excel Upload</h3>
      </div>
      <?php
  if(isset($_POST['upload']))
  {
	  if($_FILES['file']['error']<=0)
	  {
			$ext=explode(".",$_FILES["file"]["name"]);
			$ext = $ext[1];
			if($ext == 'xls')
			{
				$sql = "INSERT INTO `product_excel_upload` (`file_name`,`drop`) VALUES ('".$_FILES["file"]["name"]."','".intval($_POST['truncate'])."');
						SELECT LAST_INSERT_ID();";
				$rst = $mysql->execute($sql);
				$r = mysqli_fetch_array($rst[0]);
				move_uploaded_file($_FILES["file"]["tmp_name"],"uploads/product_excel/".$r[0].".".$ext);
				$excel_id = $r[0];
				require_once 'Excel/reader.php';
				$data = new Spreadsheet_Excel_Reader();
				$data->read("uploads/product_excel/".$r[0].".".$ext);
				$File='';
				error_reporting(E_ALL ^ E_NOTICE);
				$a = array();
				$rows=$data->sheets[0]['numRows'];
				$cols=$data->sheets[0]['numCols'];
				for($j=0;$j<$rows;$j++)
				{	
					for($i=1;$i<=$cols; $i++)
					{
						$a[$j][$i]= str_replace("'", "",trim($data->sheets[0]['cells'][$j+1][$i]));		
					}
				}
				array_shift($a);
				$brand = array();
				$category = array();
				$group = array();
				if($_POST['truncate']==1)
				{
					$sql = "update product set `is`=".$excel_id." where `is`=1;";
					$mysql->execute($sql);
				}
				$sql = "select 
							id,name 
						from 
							product_brand
						where 
							`is`=1;
						select 
							id,name 
						from 
							product_category 
						where 
							`is`=1;
						select 
							id,name 
						from 
							product_group
						where 
							`is`=1";
				$rst = $mysql->execute($sql);
				while($r = mysqli_fetch_array($rst[0]))
					$brand[$r['id']] = trim($r['name']);
				while($r = mysqli_fetch_array($rst[1]))
					$category[$r['id']] = trim($r['name']);
				while($r = mysqli_fetch_array($rst[2]))
					$group[$r['id']] = trim($r['name']);
				$product_sql = "SET @lst='';";
				$id = 0;
				foreach($a as $id=>$trs)
				{
					if(trim($a[$id][5])=='')
						continue;
					if(!in_array($a[$id][1],$brand) && $a[$id][1]!='')
					{
						$sql = "INSERT INTO `product_brand` 
									(`name`, `desc`, `link`) 
								VALUES 
									('".trim($a[$id][1])."', '".trim($a[$id][1])."', '#');
								SELECT LAST_INSERT_ID();";
						$rst = $mysql->execute($sql);
						$r = mysqli_fetch_array($rst[0]);
						$brand[$r[0]] = trim($a[$id][1]);
					}
					if(!in_array($a[$id][2],$category) && $a[$id][2]!='')
					{
						$sql = "INSERT INTO `product_category` 
									(`name`, `desc`) 
								VALUES 
									('".trim($a[$id][2])."', '".trim($a[$id][2])."');
								SELECT LAST_INSERT_ID();";
						$rst = $mysql->execute($sql);
						$r = mysqli_fetch_array($rst[0]);
						$category[$r[0]] = trim($a[$id][2]);
					}
					if(!in_array($a[$id][3],$group) && $a[$id][3]!='')
					{
						$sql = "INSERT INTO `product_group` 
									(`name`, `desc`) 
								VALUES 
									('".trim($a[$id][3])."', '".trim($a[$id][3])."');
								SELECT LAST_INSERT_ID();";
						$rst = $mysql->execute($sql);
						$r = mysqli_fetch_array($rst[0]);
						$group[$r[0]] = trim($a[$id][3]);
					}
					$product_brand = array_search(trim($a[$id][1]),$brand);
					$product_category = array_search(trim($a[$id][2]),$category);
					$product_group = array_search(trim($a[$id][3]),$group);
					$item_code = htmlentities(trim($a[$id][4]));	
					$product_name = htmlentities(trim($a[$id][5]));
					$qty = ucfirst(trim($a[$id][6]));
					$vat = $a[$id][7];
					$mrp = $a[$id][8];
					$price = $a[$id][9];
					$parchase_price = $a[$id][10];
					if($price==0)
						$price = $mrp;
					if($parchase_price==0)
						$parchase_price = $mrp;
					$product_sql .= "INSERT INTO `product` 
										(`excel_id`,`pid`, `bid`, `cid`, `gid`, 
										 `name`, 
										 `type`, `mrp`, `par_price`, `price`, `vat`) 
									VALUES 
										('".$excel_id."','".$item_code."', '".$product_brand."', '".$product_category."', '".$product_group."', 
										 '".addslashes($product_name)."', 
										 '".$qty."', '".$mrp."', '".$parchase_price."', '".$price."', '".$vat."');
									 SELECT LAST_INSERT_ID()  into @lst;";
					$pointer = 10;
					while(1)
					{
						$pointer++;
						$prop = trim($a[$id][$pointer]);
						$pointer++;
						$prop_value = trim($a[$id][$pointer]);
						if($prop =='')
							break;	
						if($prop_value!='')
							$product_sql .= "INSERT INTO `product_property` (`pid`, `name`, `value`) VALUES (@lst, '".$prop."', '".$prop_value."');";
					}
					if($id == 100)
					{
						$mysql->execute($product_sql);
						$product_sql = '';
						$id = 0;
					}
					$id++;
				}
				if($id != 0)
					$mysql->execute($product_sql);
				?>
		  <div class="alert alert-success" >
			<h3>Successfully uploaded</h3>
			<?php
			if($_POST['truncate']==1)
				echo 'Previous Prodcts are Removed from database';
			?>
		  </div>
		  <?php
		 }
		 else
		 {
			?>
		  <div class="alert alert-danger" >
			<h3>.xls Format Excel file Only Allowed to uploaded</h3>
		  </div>
		  <?php 
		 }
	  }
  }
?>
      <br>
      <br>
      <center>
        <form action="#" method="post" enctype="multipart/form-data">
          <table class="table table-striped" style="width:500px">
            <tr>
              <th style="white-space:nowrap">Choose the .xls(Excel) file to Upload :</th>
              <td><input type="file" name="file" ></td>
            </tr>
            <tr><th style="white-space:nowrap" colspan="2" style="text-align:center">
              Empty Previous Products :
              <input type="checkbox" name="truncate" value="1" >
                </td>
            </tr>
            <tr>
              <td colspan="2" style="text-align:center"><input type="submit" name="upload" value="Upload" class="btn btn-blue4" ></td>
            </tr>
          </table>
        </form>
      </center>
    </div>
    <?php include"footer.php"; ?>