<?php
session_start();
include"classes/mysql.php";
$uname = $_POST['uname'];
$pass = md5($_POST['pass']);
if(!isset($_SESSION['user_id']))
{
	$sql = "select 
				* 
			from 
				authendication as at 
			where 
				at.user_name='".$uname."' and 
				at.password='".$pass."' and 
				at.is=1;";
	$rst = $mysql->execute($sql);
	$allow = mysqli_num_rows($rst[0]);
	if($allow == 1)
	{
			$rst = mysqli_fetch_array($rst[0]);
			if(strtotime($rst['from'])<=strtotime(date('H:i:s')) && strtotime(date('H:i:s'))<=strtotime($rst['to']))
			{
			$_SESSION['user_id'] = $rst['user_id'];
			$sql = "select * from profile as pr where pr.id='".$rst['user_id']."';
					select sh.id,sh.image,sh.file_name,sh.name from shortcuts as sh where sh.user_id='".$rst['user_id']."' and sh.is=1;
					select d.name,d.logo_src from shop_detail as d where d.`is`=1";
			$result = $mysql->execute($sql);
			$prf = mysqli_fetch_array($result[0]);
			$shop_detail = mysqli_fetch_array($result[2]);
			while($shortcuts = mysqli_fetch_array($result[1]))
			{
				$_SESSION['shortcuts']['id'][]=$shortcuts['id'];
				$_SESSION['shortcuts']['image'][]=$shortcuts['image'];
				$_SESSION['shortcuts']['name'][]=$shortcuts['name'];
				$_SESSION['shortcuts']['file_name'][]=$shortcuts['file_name'];
			}
			$_SESSION['name']=$prf['name'];
			$_SESSION['photo']=$prf['image_source'];
			$_SESSION['user_name'] = $rst['user_name'];
			$_SESSION['pass'] = $rst['password'];
			$_SESSION['rights'] = explode(",",$rst['access_rights']);
			$_SESSION['allowed'] = "allowed";
			$_SESSION['cpass']=$_POST['pass'];
			$_SESSION['theme']=$prf['theme'];
			$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
			$_SESSION['menu'] = array();
			$_SESSION['files'] = array();
			$_SESSION['menu'] = array();
			$_SESSION['title'] = array();
			$_SESSION['role'] = array();
			$_SESSION['role'] = explode(',',$rst['access_rights']);
			$_SESSION['shop_name'] = $shop_detail['name'];
			$_SESSION['shop_logo'] = $shop_detail['logo_src'];
			$sql = "SELECT  
						m.`title`,m.`titimg`,m.`pagtit`,  
						m.`name`,  m.`url`,  
						m.`class`,  m.`img`,  
						m.`o1`,  m.`o2`,  
						m.`o3`,  m.`o4`, m.`o5` 
					FROM 
						menu as m,rights as r
					WHERE
						(
							m.`role` like concat('%',r.id,'%')
							or
							m.`role` like '%0%'
						)
						and
						r.id IN('".implode("','",$_SESSION['role'])."')
						and
						m.`is`=1
					GROUP BY m.id
					ORDER BY 
						m.`o1` ASC, 
						m.`o2` ASC, 
						m.`o3` ASC, 
						m.`o4` ASC, 
						m.`o5` ASC";
			$res = $mysql->execute($sql);
			while($r = mysqli_fetch_array($res[0]))
			{
				$_SESSION['files'][] = $r['url'];
				if(!file_exists($r['url']) && $r['url']!='#')
				{
					$data = '<?php include"header.php"; ?>
<?php include"footer.php"; ?>';
					$file = fopen($r['url'],'a');
					fputs($file,$data);
					fclose($file);
				}
				$_SESSION['title'][$r['url']]['page'] = $r['pagtit'];
				if($r['o1']!=0 && $r['title']!='')
				{
					if($r['titimg']!='')
					{
						$_SESSION['title'][$r['o1']]['titimg'] = $r['titimg'];
					}
					$_SESSION['title'][$r['o1']]['title'] = $r['title'];
					$_SESSION['menu'][$r['o1']][$r['o2']][$r['o3']][$r['o4']][$r['o5']][0] = $r['title'];
					$_SESSION['menu'][$r['o1']][$r['o2']][$r['o3']][$r['o4']][$r['o5']][1] = $r['name'];
					$_SESSION['menu'][$r['o1']][$r['o2']][$r['o3']][$r['o4']][$r['o5']][2] = $r['url'];
					$_SESSION['menu'][$r['o1']][$r['o2']][$r['o3']][$r['o4']][$r['o5']][3] = $r['class'];
					$_SESSION['menu'][$r['o1']][$r['o2']][$r['o3']][$r['o4']][$r['o5']][4] = $r['img'];
				}
			}
			$sql = "select count(*)'count' from product_daywise as d where d.date=subdate(date(now()),1)";
			$rst = $mysql->execute($sql);
			$r   = mysqli_fetch_array($rst[0]);
			if($r['count'] == 0){
				$sql = "delete from product_daywise where `date`=date(now());
						insert into product_daywise(`date`,pid,stock) (select subdate(date(now()),1), p.id,p.available from product as p)";
				$mysql->execute($sql);
		
			}
			}
			else
			{
				?>
				<script type="text/javascript">
					window.location="index.php?out=3";
				</script>
				<?php
			}
	}
	else
	{
		?>
		<script type="text/javascript">
			window.location="index.php?out=2";
		</script>
		<?php
	}
}
else if($_SESSION['allowed'] != "allowed")
{
	ob_start();
	header("location:index.php");
	?>
	<script type="text/javascript">
	window.location="index.php";
	</script>
	<?php
	ob_flush();
}
?>