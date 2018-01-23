<?php
include_once"header.php";
?>
    <center>
      <div class="box">
        <div class="box-head">
          <h3>Access Rights</h3>
        </div>
        <div class="box-content">
          <?php
		if(isset($_POST['apply']))
		{
			foreach($_POST['user_id'] as $id=> $id1)
			{
				$rid = 'rights'.$id1;
				$rgts = implode(",",$_POST[$rid]);
				$sql .= "UPDATE `authendication` SET `access_rights`='".$rgts."' WHERE  
							user_id = ".$id1." LIMIT 1;";
			}
			$mysql->execute($sql);
			?>
          <div class="alert alert-block alert-success"> <a class="close" data-dismiss="alert" href="#">×</a>
            <h4 class="alert-heading">Successfully Updated!</h4>
          </div>
          <?php
		}
			$sql = "select 
						* 
					from 
						profile as pr,
						authendication as ar
					where 
						pr.`is`=1
						and 
						ar.user_id = pr.id ;";
			$rst = $mysql->execute($sql);
			?>
          <div class="box-content box-nomargin">
            <div class="tab-content">
              <div class="tab-pane active" id="basic">
                <form action="access_rights.php" method="post">
                  <table class='table table-striped dataTable table-bordered'>
                    <thead>
                      <tr>
                        <th>Image</th>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Edit</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
											while($r = mysqli_fetch_array($rst[0]))
											{
											?>
                      <tr>
                        <td><a href="<?php echo $r['image_source']; ?>" class='preview fancy'><img src="<?php echo $r['image_source']; ?>" width="100px" alt="" title="Image title"></a></td>
                        <td><?php echo $r['user_id'] ?></td>
                        <td><?php echo $r['name'] ?></td>
                        <td><?php echo $r['type'] ?></td>
                        <td><input type="hidden" name="user_id[]" value="<?php echo $r['user_id'] ?>">
                          <?php 
														$sl = $mysql->execute("select rt.id,rt.name from rights as rt;");
														?>
                          <select name="rights<?php echo $r['user_id'] ?>[]" class="cho" multiple>
                            <?php 
														$acc = array();
														$acc = explode(",",$r['access_rights']);
														while( $rs = mysqli_fetch_array($sl[0])){ 
														?>
                            <option 
                                                        	value="<?php echo $rs['id']; ?>" <?php if(in_array($rs['id'],$acc)) echo "selected" ?> > <?php echo $rs['name']; ?></option>
                            <?php 
														} 
														?>
                          </select></td>
                      </tr>
                      <?php
											}
											?>
                    </tbody>
                  </table>
                  <button type="submit" name="apply" class="btn btn-red4" style="border:#FFF 0px solid">Apply</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </center>
    <?php
include_once"footer.php";
?>