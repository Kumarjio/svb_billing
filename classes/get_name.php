<?php
class getName extends mysql
{
	public function employee($id)
	{
		$sql = "select id,name from profile where id='".$id."'";
		$result = $this->execute($sql);
		$result = mysqli_fetch_array($result[0]);
		echo $return ='<a href="profile.php?id='.$id.'">'.$result['name'].'</a>'; 		
		//return $return;
	}
	public function customer($id)
	{
		$sql = "select id,name from customers where id='".$id."'";
		$result = $this->execute($sql);
		$result = mysqli_fetch_array($result[0]);
		return '<a href="customer_profile.php?id="'.$result['id'].'">'.$result['name'].'</a>'; 		
	}
	public function dealer($id)
	{
		$sql = "select id,name from dealer_name where id='".$id."'";
		$result = $this->execute($sql);
		$result = mysqli_fetch_array($result[0]);
		return '<a href="dealer_profile.php?id="'.$result[0].'">'.$result[1].'</a>'; 			
	}
}
$name = new getName;
?>