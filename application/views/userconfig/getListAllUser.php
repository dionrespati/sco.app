<<<<<<< HEAD
<?php
  if($listUser == null) {
  	 echo emptyResultDiv();
  } else {
  	 echo "<form id=listUserGroup><table width=\"100%\" class=\"table table-striped table-bordered bootstrap-datatable datatable\">";
       echo "<thead><tr><th colspan=7>DAFTAR USER</th></tr>";
       echo "<tr bgcolor=#f4f4f4>";
       echo "<th width=10%>No</th>";
       echo "<th>User Name</th>";
       echo "<th>Password</th>";
       echo "<th width=15%>Member Prefix</th>";
	   echo "<th width=15%>Last Kit</th>";
       echo "<th width=15%>Create Dt</th>";
       echo "<th width=15%>&nbsp;</th></thead></tr>";
       echo "<tbody>";
       $i = 1;
	   
       foreach($listUser as $list) {
                echo "<tr id=\"$i\">";
		        echo "<td><div align=right>$i<div></td>";
                echo "<td><div align=center><input type=\"hidden\" id=\"usrname$i\" value=\"$list->username\" />$list->username</div></td>";
                echo "<td><div align=center>$list->password</div></td>";
                echo "<td><div align=center>$list->memberprefix</div></td>";
				echo "<td><div align=center>$list->lastkitno</div></td>";  
                echo "<td><div align=center>$list->createdt</div></td>";
                /*echo "<td><div align=\"center\">";
                echo "<a class=\"btn btn-mini btn-info\" onclick=\"Gallery.getUpdateGallery($i)\"><i class=\"icon-edit icon-white\"></i></a>";
                echo "&nbsp;<a class=\"btn btn-mini btn-danger\" onclick=\"Gallery.deleteGallery($i)\"><i class=\"icon-trash icon-white\"></i></a>";
                echo "</div></td>";*/
                $ondelete = "All.deleteRecord('user/delete/', '$list->username', $i)";
                $arr = array(
				    "update" => "Userconfig.getUpdateUser($i)",
				    "delete" => $ondelete
				);
                echo btnUpdateDelete($arr);
                echo "</tr>";
              $i++; 
        }
    echo "</tbody></tr>";
    echo "</table></form>";
	?>
	<!--<script>
      $( document ).ready(function() {
       All.set_datatable();
        });
 </script>-->
	<?php
	setDatatable();
  }
?>
=======
<?php
  if($listUser == null) {
  	 echo emptyResultDiv();
  } else {
  	 echo "<form id=listUserGroup><table width=\"100%\" class=\"table table-striped table-bordered bootstrap-datatable datatable\">";
       echo "<thead><tr><th colspan=7>DAFTAR USER</th></tr>";
       echo "<tr bgcolor=#f4f4f4>";
       echo "<th width=10%>No</th>";
       echo "<th>User Name</th>";
       echo "<th>Password</th>";
       echo "<th width=15%>Member Prefix</th>";
	   echo "<th width=15%>Last Kit</th>";
       echo "<th width=15%>Create Dt</th>";
       echo "<th width=15%>&nbsp;</th></thead></tr>";
       echo "<tbody>";
       $i = 1;
	   
       foreach($listUser as $list) {
                echo "<tr id=\"$i\">";
		        echo "<td><div align=right>$i<div></td>";
                echo "<td><div align=center><input type=\"hidden\" id=\"usrname$i\" value=\"$list->username\" />$list->username</div></td>";
                echo "<td><div align=center>$list->password</div></td>";
                echo "<td><div align=center>$list->memberprefix</div></td>";
				echo "<td><div align=center>$list->lastkitno</div></td>";  
                echo "<td><div align=center>$list->createdt</div></td>";
                /*echo "<td><div align=\"center\">";
                echo "<a class=\"btn btn-mini btn-info\" onclick=\"Gallery.getUpdateGallery($i)\"><i class=\"icon-edit icon-white\"></i></a>";
                echo "&nbsp;<a class=\"btn btn-mini btn-danger\" onclick=\"Gallery.deleteGallery($i)\"><i class=\"icon-trash icon-white\"></i></a>";
                echo "</div></td>";*/
                $ondelete = "All.deleteRecord('user/delete/', '$list->username', $i)";
                $arr = array(
				    "update" => "Userconfig.getUpdateUser($i)",
				    "delete" => $ondelete
				);
                echo btnUpdateDelete($arr);
                echo "</tr>";
              $i++; 
        }
    echo "</tbody></tr>";
    echo "</table></form>";
	?>
	<!--<script>
      $( document ).ready(function() {
       All.set_datatable();
        });
 </script>-->
	<?php
	setDatatable();
  }
?>
>>>>>>> devel
