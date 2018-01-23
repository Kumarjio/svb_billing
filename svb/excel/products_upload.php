<?php
include"../classes/mysql.php";
  require_once 'Excel/reader.php';
  $data = new Spreadsheet_Excel_Reader();
  $data->read('2.xls');
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
  $products='';
  foreach($a as $id=>$trs)
  {
	  if($a[$id][1]=='' && $a[$id][2]!='')
	  {
	 	 $group = $a[$id][2];	
	 	 $sql ="INSERT INTO `product_group` 
		 			(`name`, `desc`) 
				VALUES 
					('".$group."', '".$group."');
				SELECT LAST_INSERT_ID();";
		$rst = $mysql->execute($sql);
		$tmp  =mysqli_fetch_array($rst[0]);
		$group = $tmp[0];
	  }
	  else
	  {
	  $products = "INSERT INTO `product` 
	  				(`pid`, `gid`, `name`, `type`, `price`, `par_price`, `mrp`, `vat`, `desc`) 
				VALUES 
					('".$a[$id][1]."', '".$group."', '".$a[$id][2]."', '".ucfirst($a[$id][4])."', 
					".$a[$id][5].", ".$a[$id][5].", ".$a[$id][5].", '".str_replace("%","",$a[$id][6])."', '".$a[$id][2]."');";
		$mysql->execute($products);
	  }
	   
  }
