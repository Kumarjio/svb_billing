<?php
session_start();
?>
<nav>
<ul style="list-style: none; margin-left:3px;">
<?php
include"classes/mysql.php";
if($_POST['val']!='')
{
$sql = "SELECT  
						m.`title`,m.`titimg`,m.`pagtit`,  
						m.`name`,  m.`url`,  
						m.`class`,  m.`img`,  
						m.`o1`,  m.`o2`,  
						m.`o3`,  m.`o4`, m.`o5` 
					FROM 
						menu as m,rights as r
					WHERE
						r.id = m.`role`
						and
						r.id IN('".implode("','",$_SESSION['role'])."')
						and
						m.`is`=1
						and
						m.url!='#'
						and
						(
						m.title like('%".$_POST['val']."%')
						or
						m.url like('%".$_POST['val']."%')
						or
						m.name like('%".$_POST['val']."%')
						or
						m.pagtit like('%".$_POST['val']."%')
						)
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
				if($r['o1']!=0 && $r['title']!='')
				{
					?>
                    <li style="border-bottom:#CCC 1px solid"><a href="<?php echo $r['url'] ?>" style="color:#000; font-size:12px; font-weight:bold">&nbsp;&nbsp;
					<?php if($r['class']!='') echo '<i class="'.$r['class'].'"></i>&nbsp;&nbsp;&nbsp;'; 
					else echo $r['img'];;  ?> 
	  				<?php echo $r['title']."<br><span style='padding-left:35px' > - ".$r['name']."<span>"; ?> </a></li>
                    <?php
				}
			}
}
?>
</ul>
</nav>