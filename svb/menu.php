<ul class="nav">
  <?php
foreach($_SESSION['menu'] as $o1=>$oo1)
{
	?>
  <li class="dropdown"> <a data-toggle="dropdown" class="dropdown-toggle" href="#"> <i class="<?php echo $_SESSION['title'][$o1]['titimg']; ?>"></i>
    <?php 
		echo $_SESSION['title'][$o1]['title']; ?>
    <?php if($title[$o1] == 'Billing'){ ?>
    <span class="label label-info" style="text-decoration:blink">
    <?php 
            $sql ="select count(*) from bill as bl where bl.`status`='s' and bl.`is`=1";
            $rst = $mysql->execute($sql);
            $rst = mysqli_fetch_array($rst[0]);
            echo $rst[0];
          ?>
    </span>
    <?php } ?>
    <b class="caret"></b></a>
    <ul class="dropdown-menu">
      <?php
		  foreach($oo1 as $o2=>$oo2)
		  {
			  if($_SESSION['production_table'] != "production_process_stock_wb" && $oo2[1][1][1][1] == 'WB')
			  	continue;
		  ?>
      <li><a href="<?php echo $oo2[1][1][1][2] ?>"><?php if($oo2[1][1][1][3]!='') echo '<i class="'.$oo2[1][1][1][3].'"></i>'; else echo $oo2[1][1][1][4];  ?> 
	  <?php echo $oo2[1][1][1][1] ?> </a>
        <?php
			if(count($oo2)>0 && count($oo2)>1)
			{
			?>
        <b class="caret-right"></b>
        <ul class="dropdown-menu">
          <?php
				foreach($oo2 as $o3=>$oo3)
		  		{
					if($oo2[1][1][1][1]!=$oo3[1][1][1])
					{
				?>
          <li><a href="<?php echo $oo3[1][1][2] ?>"><?php if($oo3[1][1][3]!='') echo '<i class="'.$oo3[1][1][3].'"></i>'; else echo $oo3[1][1][4];  ?> 
          <?php echo $oo3[1][1][1] ?></a>
            <?php
					if(count($oo3)>0  && count($oo3)>1)
					{
					?>
            <b class="caret-right"></b>
            <ul class="dropdown-menu">
              <?php
                        foreach($oo3 as $o4=>$oo4)
                        {
							if($oo3[1][1][1]!=$oo4[1][1])
							{
                        ?>
              <li><a href="<?php echo $oo4[1][2] ?>"><?php if($oo4[1][3]!='') echo '<i class="'.$oo4[1][3].'"></i>'; else echo $oo4[1][4];  ?> 
          		<?php echo $oo4[1][1] ?></a>
                <?php
							if(count($oo4)>0  && count($oo4)>1)
							{
							?>
                <b class="caret-right"></b>
                <ul class="dropdown-menu">
                  <?php
                                 foreach($oo4 as $o5=>$oo5)
                                {
									if($oo4[1][1]!=$oo5[1])
									{
                                ?>
                  <li><a href="<?php echo $oo5[2] ?>"><?php if($oo5[3]!='') echo '<i class="'.$oo5[3].'"></i>'; else echo $oo5[4];  ?> 
          		   <?php echo $oo5[1] ?></a></li>
                  <?php
									}
                                }
                                ?>
                </ul>
                <?php
							}
							?>
              </li>
              <?php
							}
                        }
                        ?>
            </ul>
            <?php
					}
					?>
          </li>
          <?php
					}
				}
				?>
        </ul>
        <?php
			}
			?>
      </li>
      <?php
		  }
		  ?>
    </ul>
  </li>
  <?php
}
?>
</ul>
