<?php
	include "header.php";
?>
<script type="text/javascript">
var GroupTmp;
$(document).ready(function(e) {
	i=1,noEnter=0;
    $(".navi").each(function(index, element) {
		if(i==1)
		{
			$(this).remove();
			i++;
		}
    });
	$(".chzn-container").mouseover(function(e) {
        noEnter =1;
    });
	$(".chzn-container").mouseleave(function(e) {
        noEnter =0;
    });
	$("body").keypress(function(e) {
       if(e.keyCode == 116)
	   {
		   e.preventDefault();
		   alert("Please Dont Fresh");
	   }
	   else if(e.keyCode == 13 && noEnter!=1)
	   {
		   e.preventDefault();
		   alert("U can Submit Form Only By Pressing the button Dont Press Enter Key");
	   }
    });
});
function getMain(id){
	$.ajax({
	  url: 'getMainMenuNames.php',
	  type: 'POST',
	  data:{'id':id,'t':'0'},
	  success: function(data) {
		  $("#mainName3").css("display",'block');
		$("#mainName3").html(data);
	  }
	});
}
function getMain1(id){
	GroupTmp = id;	
	$.ajax({
	  url: 'getMainMenuNames.php',
	  type: 'POST',
	  data:{'id':id,'t':'1'},
	  success: function(data) {
		$("#mainName4").css("display",'block');
		$("#mainName4").html(data);
		$("#groupNameN").val($("#gp"+id).html());
		$("#newGroupName").css("display","block");
	  }
	});
}
function mainNameN(id)
{
	$.ajax({
	  url: 'getSubName.php',
	  type: 'POST',
	  data:{'id':GroupTmp,'main':id,'t':'1'},
	  success: function(data) {
		$("#subMenus").css("display",'block');
		$("#subMenus").html(data);
	  } 
	});
	$("#mainName1").val($("#mn"+id).html());
	$("#newmainName").css("display","block");
}
function SubNameN(id)
{
	$("#newsubMenusN").val($("#mn"+id).html());
	$("#newsubMenus").css("display","block");
}
function setIcon(cl)
{
	$("#menuIcon").val(cl);
}
</script>
<div style="background-color:#000; height:auto">
</div><br />
<br />
<center>
  <div class="box" style="width:65%; margin-left:50px; height:650px;">
    <div class="box-head tabs-main tabs">
      <ul class="nav nav-tabs nav-tabs-main">
        <li class='active'> <a href="#1" data-toggle="tab">New Group</a> </li>
        <li> <a href="#2" data-toggle="tab">Main Menu</a> </li>
        <li> <a href="#3" data-toggle="tab">Sub Menu</a> </li>
        <li> <a href="#4" data-toggle="tab">Edit</a> </li>
      </ul>
    </div>
    <div class="box-content" style="height:300px;">
      <div class="tab-content">
        <!--New Group Entry-->
        <div class="tab-pane active" id="1">
          <table>
            <tr>
              <td><form action="menuEdit.php#2" method="post" class='validate form-horizontal'>
                  <div class="control-group">
                    <label for="req" class="control-label">Group Name</label>
                    <div class="controls">
                      <input type="text" name="groupName" id="groupName" class='required'>
                    </div>
                  </div>
                  <div class="control-group">
                  <label for="req" class="control-label">Privilege Name</label>
                  <div class="controls">           
                  <select name="inchargecat[]"  style="width:600px;"  id="inchargecat1" class="cho span6" multiple >
                    </select>
                      
                    </div>
                  </div>
                  <center>
                    <input type="submit" name="group" class='btn btn-primary'>
                  </center>
                </form></td>
            </tr>
          </table>
        </div>
        <!--Main Group Entry-->
        <div class="tab-pane" id="2">
        <table>
            <tr>
              <td><form action="menuEdit.php" method="post" class='validate form-horizontal'>
                  <div class="control-group">
                    <label for="req" class="control-label">Group Name</label>
                    <div class="controls">                    
                      <select class="cho" name="gropuId">
                      <?php
					foreach($groupName as $id=> $gN)
					{
					?>
                    <option value="<?php echo $groupId[$id]; ?>"><?php echo $gN; ?></option>
                    <?php
					}
					?>
                      </select>
                    </div>
                  </div>
                  <div class="control-group">
                    <label for="req" class="control-label">Menu Name</label>
                    <div class="controls">
                      <input type="text" name="mainName" id="mainName" class='required'>
                      <input type="checkbox" name="subMenuTrue" value="1" />Drop Down Icon
                    </div>
                  </div>
                  <div class="control-group">
                    <label for="req" class="control-label">Menu Icon</label>
                    <div class="controls">
                      <input type="text" name="menuIcon" id="menuIcon" class='required' placeholder="Choose Icon Below" value="icon-th-list" readonly="readonly">
                      <div style="height:85px; overflow:auto; width:300px; cursor:pointer;">
                      <table border="1" width="100%">
                      <?php 
					  $fp = fopen("css/menuIconStyle.dat","r");
					  for($i=0;$i<14;$i++)
					 	{
					  ?>
                      <tr>
                      <?php for($j=0;$j<10;$j++) { ?>
                      <td><div class="<?php echo fgets($fp); ?>" onclick="setIcon($(this).attr('class'))"></div></td>
                      <?php } ?>
                      </tr>
                      <?php
						}
					  ?>
                      </table>
                      </div>
                    </div>
                  </div>
                  <div class="control-group">
                    <label for="req" class="control-label">Link</label>
                    <div class="controls">                    
                      <input type="text" name="itemLink" />
                    </div>
                  </div>
                  <div class="control-group">
                    <label for="req" class="control-label">Active/non Active</label>
                    <div class="controls">                    
                      <select class="cho" name="active">
                      <option value="">Non Active</option>
                    <option value="active">Active</option>
                      </select>
                    </div>
                  </div>
                  <div class="control-group">
                  <label for="req" class="control-label">Privilege Name</label>
                  <div class="controls">           
                  <select name="inchargecat[]"  style="width:600px;"  id="inchargecat" class="cho span6" multiple >
                    <option value="Select" >Select</option>
                    <? 
                    $sql_inc="select * from incharge_category where status='1' order by category";
					$DBObject->qarg(0,$sql_inc);
                    $res_inc=$DBObject->execute_return(0);
                    while($r_inc=mysqli_fetch_array($res_inc[0]))
                    {
                        echo "<option value=',".$r_inc[category]."'>".$r_inc['category']."</option>";	
                    }
                    ?>
                    </select>
                      
                    </div>
                  </div>
                  <center>
                    <input type="submit" name="main" class='btn btn-primary'>
                  </center>
                </form></td>
            </tr>
          </table>
        </div>
        <!--Sub Menu Entry-->
        <div class="tab-pane" id="3">
        <table>
            <tr>
              <td><form action="menuEdit.php" method="post" class='validate form-horizontal'>
                  <div class="control-group">
                    <label for="req" class="control-label">Group Name</label>
                    <div class="controls">                    
                      <select name="groupId" onchange="getMain(this.value)">
                      <option value="no">Select Group</option>
                       <?php
						foreach($groupName as $id=> $gN)
						{
						?>
						<option value="<?php echo $groupId[$id]; ?>"><?php echo $gN; ?></option>
						<?php
						}
						?>
                      </select>
                    </div>
                  </div>
                  <div class="control-group" id="mainName3" style="display:none;">
                    
                  </div>
                  <div class="control-group">
                    <label for="req" class="control-label">Item Name</label>
                    <div class="controls">                    
                      <input type="text" name="itemName" />
                    </div>
                  </div>
                  <div class="control-group">
                    <label for="req" class="control-label">Link</label>
                    <div class="controls">                    
                      <input type="text" name="itemLink" />
                    </div>
                  </div>
                  <div class="control-group">
                  <label for="req" class="control-label">Privilege Name</label>
                  <div class="controls">           
                  <select name="inchargecat[]"  style="width:600px;"  id="inchargecat2" class="cho span6" multiple >
                    <option value="Select" >Select</option>
                    <? 
                    $sql_inc="select * from incharge_category where status='1' order by category";
					$DBObject->qarg(0,$sql_inc);
                    $res_inc=$DBObject->execute_return(0);
                    while($r_inc=mysqli_fetch_array($res_inc[0]))
                    {
                        echo "<option value=',".$r_inc[category]."'>".$r_inc['category']."</option>";	
                    }
                    ?>
                    </select>
                      
                    </div>
                  </div>
                  <center>
                    <input type="submit" name="subMenu" class='btn btn-primary'>
                  </center>
                </form></td>
            </tr>
          </table>
        </div>
        <!--Menu Editing-->
        <div class="tab-pane" id="4">
        <table>
            <tr>
              <td><form action="menuEdit.php" method="post" class='validate form-horizontal'>
                  <div class="control-group">
                    <label for="req" class="control-label">Group:</label>
                    <div class="controls">                    
                      <select name="groupId" onchange="getMain1(this.value)">
                      <option value="no">Select Group</option>
                       <?php
						foreach($groupName as $id=> $gN)
						{
						?>
						<option value="<?php echo $groupId[$id]; ?>" id="gp<?php echo $groupId[$id]; ?>"><?php echo $gN; ?></option>
						<?php
						}
						?>
                      </select>
                    </div>
                  </div>
                  <div class="control-group" id="newGroupName" style="display:none">
                    <label for="req" class="control-label">New Group Name</label>
                    <div class="controls">                    
                    <input type="text" name="groupName" id="groupNameN" class='required' >
                    </div>
                  </div>
                  <div class="control-group" id="mainName4" style="display:none;">
                    
                  </div>
                  <div class="control-group" id="newmainName" style="display:none">
                    <label for="req" class="control-label">New Menu Name</label>
                    <div class="controls">
                      <input type="text" name="mainName" id="mainName1" class='required'>
                    </div>
                  </div>
                  <div class="control-group" id="subMenus" style="display:none;">
                    
                  </div>
                  <div class="control-group" id="newsubMenus" style="display:none">
                    <label for="req" class="control-label">New Sub Menu Name</label>
                    <div class="controls">
                      <input type="text" name="subName" id="newsubMenusN" class='required'>
                    </div>
                  </div>
                  <div class="control-group">
                    <label for="req" class="control-label">Item Name</label>
                    <div class="controls">                    
                      <input type="text" name="itemName" />
                    </div>
                  </div>
                  <div class="control-group">
                    <label for="req" class="control-label">Link</label>
                    <div class="controls">                    
                      <input type="text" name="itemLink" />
                    </div>
                  </div>
                  <div class="control-group">
                  <label for="req" class="control-label">Privilege Name</label>
                  <div class="controls">           
                  <select name="inchargecat[]"  style="width:600px;"  id="inchargecat3" class="cho span6" multiple >
                    <option value="Select" >Select</option>
                    <? 
                    $sql_inc="select * from incharge_category where status='1' order by category";
					$DBObject->qarg(0,$sql_inc);
                    $res_inc=$DBObject->execute_return(0);
                    while($r_inc=mysqli_fetch_array($res_inc[0]))
                    {
                        echo "<option value=',".$r_inc[category]."'>".$r_inc['category']."</option>";	
                    }
                    ?>
                    </select>
                      
                    </div>
                  </div>
                  <center>
                    <input type="submit" name="subMenu" class='btn btn-primary'>
                  </center>
                </form></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</center>
<?php
include "footer.php";
?>
