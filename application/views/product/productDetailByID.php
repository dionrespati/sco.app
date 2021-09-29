<?php
	if($result == null) {
		echo setErrorMessage();
	} else {
	$title = "width: 20%";
?>
<table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable">
   <thead>
	 <tr>
		<th colspan="7">Daftar Produk</th>
	 </tr>	
   	 <tr>
		<th rowspan="2" width="5%">No</th>
		<th rowspan="2" width="12%">Kode</th>
		<th rowspan="2" width="45%">Nama Produk</th>
		<th colspan="2" width="15%">Harga Distributor</th>

		<!--<th colspan="2">Harga Customer</th>-->
		<th rowspan="2" width="10%">BV</th>
		<th rowspan="2" width="15%">Stockist Status Input</th>

	 </tr>
	 <tr>
		<th width="8%">12W</th>
        <th width="8%">12E</th>
        <!--<th width="8%">12W</th>
        <th width="8%">12E</th>-->
	</tr>

   </thead>
   <tbody>
    <?php
     $no = 1;
      foreach($result as $data) {
		if($data->scstatus == "1" && $data->webstatus == "1" && $data->status == "1") {
			$knet = "Ya";
			//$wrn = "";
		} else {
			$knet = "Tidak";
			//$wrn = "bgcolor=lightblue";
		}

      	echo "<tr>";
		echo "<td align=right>$no</td>";
		echo "<td align=center>";
		cetak($data->prdcd);
		echo "</td>";
		echo "<td align=left>&nbsp;";
		cetak($data->prdnm); 
		echo"</td>";
		echo "<td align=right>";
		cetak(number_format($data->price_w, 0, "", ".")); 
		echo"</td>";
		echo "<td align=right>";
		cetak(number_format($data->price_e, 0, "", "."));
		echo "</td>";

		//echo "<td align=right>".number_format($data->price_cw, 0, "", ".")."</td>";
		//echo "<td align=right>".number_format($data->price_ce, 0, "", ".")."</td>";
		echo "<td align=right>";
		cetak(number_format($data->bv, 0, "", ".")); 
		echo"</td>";
		
		echo "<td align=center>$knet</td>";

		/* if($data->is_discontinue == "1") {
			$ind = "Y";
		} else {
			$ind = "N";
		}
		echo "<td align=center>$ind</td>"; */
		echo "</tr>";
		$no++;
      }
    ?>
   </tbody>
</table>

<?php
setDatatable();
	}
?>