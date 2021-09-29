<?php
	if($result == null) {
		echo setErrorMessage();
	} else {
		echo "<table width='100%' class='table table-striped table-bordered bootstrap-datatable datatable'>
				  <thead>
					  <tr>
						  <th width='3%'>No</th>
						  <th width='15%'>CN/MSN/MM#</th>
						  <th width='15%'>No KW#</th>
						  <th width='10%'>Tgl CN/MM</th>
						  <th width='10%'>Tgl KW</th>
			  			  <th>Stockist</th>
					  </tr>
				  </thead>
			  <tbody>";
		$i = 1;
		foreach($result as $data) {
			$etdt = date("d-m-Y", strtotime($data->invoicedt));
			$createdt = date("d-m-Y", strtotime($data->createdt));
			echo "<tr>
				  <td align=right>$i</td>";
			/* .category, a.ordtype, a.invoiceno, a.invoicedt, a.dfno, c.fullnm, b.trcd, b.createdt, b.applyto 
			 */
			 $fullnm = str_replace(",", "", $data->fullnm);
			$param = "$data->invoiceno**$data->trcd**$etdt**$createdt**$data->dfno - $fullnm";
			$param2 = htmlentities($param, ENT_QUOTES, 'UTF-8');
			echo "<td align=center><a id='$param' onclick=\"javascript:All.ajaxShowDetailonNextForm('voucher/detail/$param2')\">";
			cetak($data->invoiceno);
			echo"</a></td>
				  <td align=center>";
			cetak($data->trcd);
			echo "</td>
				  <td align=center>";
			cetak($etdt); 
			echo "</td>
				  <td align=center>";
			cetak($createdt);
			echo "</td>
				  <td align=left>";
			cetak($data->dfno." - ".$data->fullnm);
		    echo "</td></tr>";
			$i++;
		}
		echo "</tbody>";
		echo "</table>";
	}
?>

<script type="text/javascript">
$(document).ready(function() 
{
	$(All.get_active_tab() + " .datatable").dataTable( {
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
		"sPaginationType": "bootstrap",
		"oLanguage": {
		},
        "bDestroy": true
	});
    $(All.get_active_tab() + " .datatable").removeAttr('style');
 });

</script>