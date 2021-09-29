<?php
if($result == null) {
	echo setErrorMessage("No Result Found..");
} else {	
?>
<form>
<table align="center" width="100%" class="table table-striped table-bordered bootstrap-datatable datatable">
	<thead>
		<?php
		//if($form['searchby'] == "sc_dfno" ||) {
		?>	
			<tr bgcolor=#f4f4f4>
				<th colspan="7">DELIVERY ORDER STOCKIST ( <?php echo $loccd; ?> )</th>
			</tr>
			<tr bgcolor=#f4f4f4>
				<th width="5%">No</th>
				<th>No.DO</th>
				<th width="15%">Tgl DO</th>
                <th width="30%">Ship By</th>
				<th width="15%">Create By</th>
				<th width="15%">No Resi</th>
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
				    <input type=hidden id=\"batchno$i\" value=\"$dta->trcd\" />
					$dta->trcd
				</td>
				<td align=\"center\">
				    $dta->do_date
				</td>
				<td align=\"center\">
				    $dta->shipby
				</td>
				<td align=\"center\">
				    $dta->createnm
				</td>
				<td align=\"center\">
				    $dta->no_resi
				</td>
				<td align=\"center\">
                <a class='btn btn-mini btn-success' id=$dta->trcd onclick=\"javascript:All.ajaxShowDetailonNextForm('do/stk/gdo/$dta->trcd')\">List SSR</a>
				</td>
			    </tr>";
			$i++;
		}

   // }
			
	?>	
	</tbody>
</table>
</form>
<?php
setDatatable();
}
?>