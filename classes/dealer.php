<?php
class Dealer extends mysql
{
	private $name,$mailid,$phone,$co_name,$co_phone,$co_add,$id,$photo;
	
	public function getName()
	{
		return $this->name;
		}
	public function getDealer($id)
	{
		$sql = "SELECT  
		`id`,
		  `dealer_name`,
		    `email`,
			  `dealer_phone`,
			    `photo`,
				  `company_name`,
				    `company_phone`,
					  `company_address` 
		FROM `aravind`.`dealer` WHERE `id`=$id;
				";
		$rst = $this->execute($sql);
		$rst = mysqli_fetch_array($rst[0]);
		$this->id=$rst['id'];
		$this->name=$rst['dealer_name'];
		$this->mailid=$rst['email'];
		$this->phone=$rst['dealer_phone'];
		$this->photo=$rst['photo'];
		$this->co_name=$rst['company_name'];
		$this->co_phone=$rst['company_phone'];
		$this->co_add=$rst['company_address'];
		
	}
}
$Dealer=new Dealer;
?>