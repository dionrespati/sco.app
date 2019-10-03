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
				<th colspan="10">List Transaksi Stockist <?php echo $form['paramValue']; ?></th>
			</tr>
			<tr bgcolor=#f4f4f4>
				<th width="5%">No</th>
				<th width="13%">Trx No</th>
				<th width="8%">Stockist</th>
				<th width="13%">No TTP</th>
				<th width="10%">Tgl Trx</th>
				<th>Nama Member</th>
				<th width="10%">DP</th>
				<th width="6%">BV</th>
				<th width="6%">Status</th>
				<?php
				if($form['flag_batch'] == "0") {
					echo "<th width=\"10%\">Action</th>";
				} else if($form['flag_batch'] == "1") {
					echo "<th width=\"10%\">No SSR</th>";
				} else {
					echo "<th width=\"10%\">Action</th>";
				}	
				?>
				
			</tr>
		</thead>
		<tbody>
	    <?php
		    $i = 1;
		    foreach($result as $dta) {
			echo "<tr id=\"ttp-$dta->trcd\">
				<td align=right>$i</td>
				<td align=\"center\">
				    <input type=hidden id=\"trcd$i\" value=\"$dta->trcd\" />
					<a id=$dta->trcd onclick=\"javascript:All.ajaxShowDetailonNextForm('member/trx/detail/trcd/$dta->trcd')\">$dta->trcd</a>
				</td>
				<td align=\"center\">
				    $dta->sc_dfno
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
				echo "<td align=center>".$dta->flag_batch_stt."</td>";
				$pref_trcd = substr($dta->trcd, 0, 2);
				if($dta->flag_batch == "0") {
					if($dta->no_deposit == null || $dta->no_deposit == "") {
						//<a class='btn btn-mini btn-primary' id=$dta->trcd onclick=\"javascript:All.ajaxShowDetailonNextForm('sales/stk/update/trcd/$dta->trcd')\"><i class=\"icon-white icon-edit\"></i></a>
						echo "<td align=\"center\">";
						if($pref_trcd == "ID") {
						echo "<a class='btn btn-mini btn-primary' id=$dta->trcd onclick=\"javascript:All.ajaxShowDetailonNextForm('sales/stk/update/trcd/$dta->trcd')\"><i class=\"icon-white icon-edit\"></i></a>&nbsp;";
						}
						echo "<a class='btn btn-mini btn-danger' onclick=\"javascript:deleteTrx('$dta->trcd')\"><i class=\"icon-white icon-trash\"></i></a>
						</td>";
					} else {
						echo "<td align=\"center\">VC Deposit</td>";
					}	
				} else if($dta->flag_batch == "1") {
					echo "<td align=\"center\">
					  $dta->batchno
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
					$(All.get_active_tab() + "tr#ttp-" +trcd).remove();
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
</script>