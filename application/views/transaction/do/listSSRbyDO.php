<?php

if($result == null) {
    echo setErrorMessage("No Result Found..");
    
} else {	
?>
<form>
<?php

?>
<table align="center" width="100%" class="table table-striped table-bordered bootstrap-datatable datatable">
	<thead>
		<?php
		//if($form['searchby'] == "sc_dfno" ||) {
		?>	
			<tr bgcolor=#f4f4f4>
				<th colspan="9">LIST SSS/MSR ( <?php echo $no_do; ?> )</th>
			</tr>
			<tr bgcolor=#f4f4f4>
				<th width="5%">No</th>
				<th  width="15%">No KW</th>
				<th  width="15%">No SSR</th>
                <th>Stockist</th>
				<th width="8%">Tgl SSR</th>
                <th width="8%">Jml TTP</th>
				<th width="10%">Total DP</th>
				<th width="8%">Total BV</th>
                <th width="8%">Action</th>
				<!--<th width="10%">Action</th>-->
			</tr>
		</thead>
		<tbody>
	    <?php
		    $i = 1;
		    foreach($result as $dta) {
		   
			echo "<tr id=\"$i\">
				<td align=right>$i</td>
				<td align=\"center\">
				    $dta->applyto
				</td>
				<td align=\"center\">
				    <input type=hidden id=\"batchno$i\" value=\"$dta->batchscno\" />
					<a id=$dta->batchscno onclick=\"javascript:All.ajaxShowDetailonNextForm2('do/stk/trx/batchno/$dta->batchscno')\">$dta->batchscno</a>
				</td>
				<td align=\"center\">
				    $dta->fullnm
				</td>
				<td align=\"center\">
				    $dta->etdt
				</td>
				
				<td align=\"center\">
				    $dta->jumlah_ttp
                </td>
                <td align=\"right\">
                ".number_format($dta->total_dp,0,"",".")."
                </td>
                <td align=\"right\">
                ".number_format($dta->total_bv,0,"",".")."
				</td>
				<td align=\"center\">
                <a class='btn btn-mini btn-success' id=$dta->batchscno onclick=\"javascript:All.ajaxShowDetailonNextForm2('do/stk/trx/batchno/$dta->batchscno')\">List TTP</a>
				</td>
			    </tr>";
			$i++;
		}

   // }
			
	?>	
	</tbody>
</table>
<?php
echo "<input type=\"button\" value=\"&lt;&lt; Kembali\" 
onclick=\"$back_button\" 
class=\"btn btn-mini btn-warning span3\">";
?>
</form>
<?php

setDatatable();
}
?>