<?php
	class mysql extends utilities
	{
		private $host = "localhost";
		private $user = "root";
		private $pass = "abarpirs";
		private $db = "billing";
		private $mysql;
		private $ret = array() ,$results,$row;
		public function __construct($db)
		{
			if($db!= '')
				$this->db = $db;
			$this->mysql = new mysqli($this->host,$this->user,$this->pass,$this->db, ini_get("mysqli.default_port"));
			
		}
		public function execute($sql)
		{
			
			try
			{
					if (mysqli_multi_query($this->mysql,$sql)) 
					{
						$this->ret = array();
						  do {
								if ($this->results = mysqli_store_result($this->mysql)) {
									array_push($this->ret,$this->results);
								}else{
									array_push($this->ret,mysqli_insert_id($this->mysql));
								}
								
							} 
							while (mysqli_next_result($this->mysql));
							return $this->ret;
					}
					else
					{
						throw new Exception;
					}
			}
			catch(Exception $e)
			{
				$file = explode("/",$_SERVER["SCRIPT_NAME"]);
				$curFile = $file[count($file)-1];
				$error = "INSERT INTO `exception` (`error`, `file`,`ip`) VALUES ('".addslashes($sql)."', '".$curFile."', '".$_SESSION['ip']."');";
				$this->execute($error);
				?>
				<script type="text/javascript">
					//window.location='index.php';
                </script>
                <?php
			}
		}
	}
	class utilities
	{
		public function date_format($date)
		{		
			$date = str_replace("/","-",$date);	
			$date = explode("-",$date);
			return $date[2].'-'.$date[1].'-'.$date[0];
		}
		public function currency($num)
		{
			return number_format($num,2);
		}
		public function no_to_words($x) 
		{
	$nwords = array("zero", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine", "ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen", "eighteen", "nineteen", "twenty", 30 => "thirty", 40 => "forty", 50 => "fifty", 60 => "sixty", 70 => "seventy", 80 => "eighty", 90 => "ninety" );
        
           if(!is_numeric($x))
           {
               $w = '#';
           }else if(fmod($x, 1) != 0)
           {
               $w = '#';
           }else{
               if($x < 0)
               {
                   $w = 'minus ';
                   $x = -$x;
               }else{
                   $w = '';
               }
               if($x < 21)
               {
                   $w .= $nwords[$x];
               }else if($x < 100)
               {
                   $w .= $nwords[10 * floor($x/10)];
                   $r = fmod($x, 10);
                   if($r > 0)
                   {
                       $w .= '-'. $nwords[$r];
                   }
               } else if($x < 1000)
               {
                   $w .= $nwords[floor($x/100)] .' hundred';
                   $r = fmod($x, 100);
                   if($r > 0)
                   {
                       $w .= ' and '. $this->no_to_words($r);
                   }
               } else if($x < 100000)
               {
                   $w .= $this->no_to_words(floor($x/1000)) .' thousand';
                   $r = fmod($x, 1000);
                   if($r > 0)
                   {
                       $w .= ' ';
                       if($r < 100)
                       {
                           $w .= 'and ';
                       }
                       $w .= $this->no_to_words($r);
                   }
               } else {
                  
				  if(floor($x/100000)>1)
				  $lak=" lakhs";
				  else
				  $lak=" lakh";
				  
				  
				   $w .= $this->no_to_words(floor($x/100000)) . $lak;
                   $r = fmod($x, 100000);
                   if($r > 0)
                   {
                       $w .= ' ';
                       if($r < 100)
                       {
                           $word .= 'and ';
                       }
                       $w .= $this->no_to_words($r);
                   }
               }
           }
           $w=$w." ";
		   return $w;
       }
	   function accountBalance($customer,$to){
		    
			$from  = '2011-04-01';
			$sql = "select 
						b.bill_no,
						b.date,
						b.bill_amount			
					from 
						bill_orders as b,bill as bl
					where 
						bl.bill_no=b.bill_no and bl.cancel in(0) and
						b.date between '".$from."' and '".$to."' 
						and
						b.customer_id='".$customer."'
					group by 
						b.bill_no
					order by
						b.date;";
			$sql .= "select
						p.date,
						p.bill_no,
						p.type,
						p.type_id,
						p.cur_balance,
						p.recieved-p.returned'amount'
					from
						bill_payment as p,bill as b 
					where 
						b.bill_no=p.bill_no and p.is=1 and 
						p.date between '".$from."' and '".$to."' 
						and
						b.customer_id='".$customer."' and b.cancel=0
					order by
						p.date;";
			$sql .= "select id,name,address,phone from customers where id='".$customer."'";
			$rst = $this->execute($sql);
			$discount=0;
			$actual_amount = 0;
			$bill_amount = 0;
			$tax_amount = 0;
			$charges_amount = 0;
			$round_off = 0;
			$customer = mysqli_fetch_array($rst[2]);
			$particular = array();
			$balance = 0;
			$date = array();
			$bills = array();
			while($r = mysqli_fetch_array($rst[0])){
				$bills[$r['bill_no']] = $r['bill_amount'];
				$date[$r['date']] = $r['date'];
				$particular[$r['date']]['name'][] = $r['bill_no'];
				$particular[$r['date']]['credit'][] = $r['bill_amount'];
				$particular[$r['date']]['debit'][] = 0;
				$particular[$r['date']]['type'][] = $r['type'];
				$particular[$r['date']]['chequee'][] = $chs['cheque_no'];
			}
			while($r = mysqli_fetch_array($rst[1])){
				$bills[$r['bill_no']] -= $r['amount'] ;
				$date[$r['date']] = $r['date'];
				$particular[$r['date']]['name'][] = $r['bill_no'];
				$particular[$r['date']]['credit'][] = 0;
				$particular[$r['date']]['debit'][] = $r['amount'];
				$particular[$r['date']]['type'][] = $r['type'];
				if($r['type']==1){
				$sql = "SELECT `recieved_date`,  `cheque_no`,  `amount`,  `bank_name`,  `branch` FROM `billing`.`chequee` WHERE `for`='".$r['bill_no']."' ;";
				$chrst = $this->execute($sql);
				if(mysqli_num_rows($chrst[0])>0){
					$chs = mysqli_fetch_array($chrst[0]);
					$particular[$r['date']]['chequee'][] = $chs['cheque_no'];
				}
				}else if($r['type']==2){
				$sql = "SELECT `recieved_date`,  `dd_no`,  `amount`,  `bank_name`,  `branch` FROM `billing`.`demand_draft` WHERE `for`='".$r['bill_no']."' ;";
				$chrst = $this->execute($sql);
				if(mysqli_num_rows($chrst[0])>0){
					$chs = mysqli_fetch_array($chrst[0]);
					$particular[$r['date']]['chequee'][] = $chs['dd_no'];
				}
				}
				
			}
			sort($date);
			$cre = 0;
			$deb = 0;
			foreach($date as $dat1=>$dat)
			{
				foreach($particular[$dat]['name'] as $id=>$name){
					$cre +=  $particular[$dat]['credit'][$id] ;
					$deb +=  $particular[$dat]['debit'][$id]; 
					$credit = $credit+$particular[$dat]['credit'][$id]-$particular[$dat]['debit'][$id];  
				}
			}
			return ($cre-$deb);
		}
	}
$mysql = new mysql("billing");
$mysqlwb = new mysql("billing_wb");
?>