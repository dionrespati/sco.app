<<<<<<< HEAD
<?php
if($result == null) {
	echo setErrorMessage("No Result Found..");
} else {	
?>
<table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable">
	<thead>
		<?php
		//if($form['searchby'] == "sc_dfno" ||) {
		?>	
			<tr bgcolor=#f4f4f4>
				<th colspan="8">List PVR Transaction By Stockist <?php echo $form['paramValue']; ?></th>
			</tr>
			<tr bgcolor=#f4f4f4>
				<th width="5%">No</th>
				<th width="13%">Trx No</th>
				<th width="13%">Order No</th>
				<th width="10%">Trx Date</th>
				<th>Member Name</th>
				<th width="10%">Payment</th>
				<th width="6%">BV</th>
				<th width="10%">Action</th>
			</tr>
		</thead>
		<tbody>
	    <?php
		    $i = 1;
		    foreach($result as $dta) {
			echo "<tr id=\"pvr-$dta->trcd\">
				<td align=right>$i</td>
				<td align=\"center\">
				    <input type=hidden id=\"trcd$i\" value=\"$dta->trcd\" />
					<a id=$dta->trcd onclick=\"javascript:All.ajaxShowDetailonNextForm('member/trx/detail/trcd/$dta->trcd')\">$dta->trcd</a>
				</td>
				<td align=\"center\">
				    $dta->orderno
				</td>
				<td align=\"center\">
				    $dta->etdt
				</td>
				<td>
				    $dta->fullnm
				</td>
				<td align=\"right\">
				   ".number_format($dta->tdp, 0, ",", ".")."
				</td>
				<td align=\"right\">
				   ".number_format($dta->tbv, 0, ",", ".")."
				</td>";
				if($dta->flag_batch == "0") {
					echo "<td align=\"center\">
						
						<a class='btn btn-mini btn-danger' onclick=\"javascript:deleteTrx('$dta->trcd')\"><i class=\"icon-white icon-trash\"></i></a>
						</td>";
				}	else {
				   echo "<td align=\"center\">
				     &nbsp;
				   </td>";
				}
				
				
			echo "</tr>";
			$i++;
		}

   // }
			
	?>	
	</tbody>
</table>

<?php
setDatatable();
}
?>
<script>
function deleteTrx(trcd) {
	//var x = All.get_active_tab();

	var r = confirm("Anda yakin ingin menghapus transaksi "+trcd);
	if (r == true) {
		$.ajax({
		url: All.get_url('sales/stk/delete/trcd/') +trcd ,
		type: 'GET',
		dataType: "json",
		success:
		function(data){
			if(data.response == "true") {
				alert(data.message);
				$(All.get_active_tab() + "tr#pvr-" +trcd).remove();
			} else {

			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + ':' +xhr.status);
				All.set_enable_button();
		}
    }); 
	} 

	
}
=======
<?php
if($result == null) {
	echo setErrorMessage("No Result Found..");
} else {	
?>
<table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable">
	<thead>
		<?php
		//if($form['searchby'] == "sc_dfno" ||) {
		?>	
			<tr bgcolor=#f4f4f4>
				<th colspan="8">List PVR Transaction By Stockist <?php echo $form['paramValue']; ?></th>
			</tr>
			<tr bgcolor=#f4f4f4>
				<th width="5%">No</th>
				<th width="13%">Trx No</th>
				<th width="13%">Order No</th>
				<th width="10%">Trx Date</th>
				<th>Member Name</th>
				<th width="10%">Payment</th>
				<th width="6%">BV</th>
				<th width="10%">Action</th>
			</tr>
		</thead>
		<tbody>
	    <?php
		    $i = 1;
		    foreach($result as $dta) {
			echo "<tr id=\"pvr-$dta->trcd\">
				<td align=right>$i</td>
				<td align=\"center\">
				    <input type=hidden id=\"trcd$i\" value=\"$dta->trcd\" />
					<a id=$dta->trcd onclick=\"javascript:All.ajaxShowDetailonNextForm('member/trx/detail/trcd/$dta->trcd')\">$dta->trcd</a>
				</td>
				<td align=\"center\">
				    $dta->orderno
				</td>
				<td align=\"center\">
				    $dta->etdt
				</td>
				<td>
				    $dta->fullnm
				</td>
				<td align=\"right\">
				   ".number_format($dta->tdp, 0, ",", ".")."
				</td>
				<td align=\"right\">
				   ".number_format($dta->tbv, 0, ",", ".")."
				</td>";
				if($dta->flag_batch == "0") {
					echo "<td align=\"center\">
						
						<a class='btn btn-mini btn-danger' onclick=\"javascript:deleteTrx('$dta->trcd')\"><i class=\"icon-white icon-trash\"></i></a>
						</td>";
				}	else {
				   echo "<td align=\"center\">
				     &nbsp;
				   </td>";
				}
				
				
			echo "</tr>";
			$i++;
		}

   // }
			
	?>	
	</tbody>
</table>

<?php
setDatatable();
}
?>
<script>
function deleteTrx(trcd) {
	//var x = All.get_active_tab();

	var r = confirm("Anda yakin ingin menghapus transaksi "+trcd);
	if (r == true) {
		$.ajax({
		url: All.get_url('sales/stk/delete/trcd/') +trcd ,
		type: 'GET',
		dataType: "json",
		success:
		function(data){
			if(data.response == "true") {
				alert(data.message);
				$(All.get_active_tab() + "tr#pvr-" +trcd).remove();
			} else {

			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + ':' +xhr.status);
				All.set_enable_button();
		}
    }); 
	} 

	
}
>>>>>>> devel
</script>