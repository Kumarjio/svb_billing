<?php
require_once 'reader.php';
$data = new Spreadsheet_Excel_Reader();

$data->read('test.xls');
$File='';
error_reporting(E_ALL ^ E_NOTICE);


$rows=$data->sheets[0]['numRows'];
$cols=$data->sheets[0]['numCols'];

echo "<table border='1'>";


for ($j = 1; $j <$rows ; $j++)
{
	echo "<tr>";
	for($i=1; $i<=$cols; $i++)
	{
		echo "<td>";
		echo str_replace("'", "", trim($data->sheets[0]['cells'][$j][$i]));
		echo "</td>";
				
	}
}
?>