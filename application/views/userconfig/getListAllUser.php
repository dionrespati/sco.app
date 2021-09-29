<?php
  if($listUser == null) {
  	 echo emptyResultDiv();
  } else {
  	 echo "<form id=listUserGroup><table width=\"100%\" class=\"table table-striped table-bordered bootstrap-datatable datatable\">";
       echo "<thead><tr><th colspan=8>DAFTAR USER</th></tr>";
       echo "<tr bgcolor=#f4f4f4>";
       echo "<th width=10%>No</th>";
       echo "<th>User Name</th>";
       echo "<th>Password</th>";
       echo "<th width=15%>Member Prefix</th>";
	   echo "<th width=15%>Last Kit</th>";
       echo "<th width=15%>Create Dt</th>";
       echo "<th width=10%>Prev Bns Month</th>";
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
                echo "<td><div align=center>"; 
                echo "<select id=\"prev_bnsmonth$i\" onchange=\"ubahPrevBnsMonth('$i')\">";
                if($list->prev_period_bnsmonth == "1") {
                  echo "<option value=0>Tidak</option>";
                  echo "<option value=1 selected=selected>Ya</option>";
                } else {
                  echo "<option value=0 selected=selected>Tidak</option>";
                  echo "<option value=1>Ya</option>";
                }
                echo "</select>";
                echo "</div></td>";
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
	<script>
      function ubahPrevBnsMonth(param) {
        var usr = $(All.get_active_tab() + " #usrname" +param).val();
        var prev_bns = $(All.get_active_tab() + " #prev_bnsmonth" +param).val();
        
        All.set_disable_button();
        $.ajax({
            url: All.get_url("user/prevbns/update"),
            type: 'POST',
            dataType: 'json',
            data: {username: usr, prev_period_bnsmonth: prev_bns},
            success:
            function(data){
                All.set_enable_button();
                alert(data.message);
                if(data.response == "false") {
                  var sebelumnya = "1";
                  if(prev_bns == "1") {
                    sebelumnya = "0";
                  }
                  $(All.get_active_tab() + " #prev_bnsmonth" +param).val(sebelumnya);
                } 
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + ':' +xhr.status);
                All.set_enable_button();
            }
        });
      }
 </script>
	<?php
	setDatatable();
  }
?>
