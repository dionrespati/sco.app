<?php
	foreach ($result["header"] as $data) {
		$trcd = $data->trcd;
		$orderno = $data->orderno;
		$batchno = $data->batchno;
		//$invoiceno = $data->invoiceno;
		$do = $data->do_no;
		$do_dt = $data->do_dt;
		$do_createnm = $data->do_createnm;

		$tampilan_do = "DO belum di proses";
		if($do !== null && $do != "") {
			$tampilan_do = $do." by ".$do_createnm." ( @".$do_dt." )";
		}

		$tampilan_ssr = "Belum di generate";
		if($batchno !== null && $batchno != "") {
			$tampilan_ssr = $batchno;
		}
		//$trtype = $data->trtype;
		//$ttptype = $data->ttptype;
		$etdt = $data->etdt;
		$etdt = date("d-m-Y", strtotime($etdt));
		$batchdt = $data->batchdt;
		//$remarks = $data->remarks;
		//$updatedt = $data->updatedt;
		//$updatedt = date("d-m-Y", strtotime($updatedt));
		//$updatenm = $data->updatenm;
		//$createdt = $data->createdt;
		$createnm = $data->createnm;
		$dfno = $data->dfno;
		$distnm = $data->distnm;
		$loccd = $data->loccd;
		$loccdnm = $data->loccd_name;
		$sc_co = $data->sc_co;
		$sc_conm = $data->sc_co_name;
		$sc_dfno = $data->sc_dfno;
		$sc_dfnonm = $data->sc_dfno_name;
		$tdp = $data->tdp;
		$tbv = $data->tbv;
		$tdp = number_format($tdp, 0, ",", ".");
		$tbv = number_format($tbv, 0, ",", ".");
		$bnsperiod = $data->bnsperiod;
		$bnsperiod = date("M-Y", strtotime($bnsperiod));
		//$statusTrx = $data->statusTrx;

		/* if($statusTrx == "OP"){ //PENDING
			$descStat = "Stockist Pending";
		}elseif($statusTrx == "OA"){ //OA APPROVED
			$descStat = "Stockist Approved";
		}elseif($statusTrx == "MA"){ //MA APPROVED
			$descStat = "Manual Approved";
		}elseif($statusTrx == "OP"){ //OP PENDING
			$descStat = "Online Pending";
		} elseif($statusTrx == "OR") {
			$descStat = "Online Need To Reconcile";
		} */
			

	}

	echo "<form id=\"formDetailTrxByID\">";
	echo "<table class='table table-striped table-bordered' width='100%'>
			<tr><th >Rekap Transaksi</th></tr></table>";
	echo "<table class='table table-striped table-bordered' width='100%'>";
	echo "<tbody>
			<tr><td width=12% align='right'><strong>Trx No&nbsp;&nbsp;</strong></td>
				<td width=25%><strong>$trcd</strong></td>
				<td width=8% align='right'>No TTP&nbsp;&nbsp;</td>
				<td width=25%>$orderno</td>
			</tr>
			<tr><td width=12% align='right'>Distributor&nbsp;&nbsp;</td>
				<td width=25%>$dfno - $distnm</td>
				<td width=8% align='right'>Periode Bonus&nbsp;&nbsp;</td>
				<td width=25%>$bnsperiod</td>
			</tr>
			<tr><td width=12% align='right'>Stockist&nbsp;&nbsp;</td>
				<td width=25%>$sc_dfno - $sc_dfnonm</td>
				<td width=8% align='right'>SSR No&nbsp;&nbsp;</td>
				<td width=25%>$tampilan_ssr</td>
			</tr>
			<tr><td width=12% align='right'>C/O Stockist&nbsp;&nbsp;</td>
				<td width=25%>$sc_co - $sc_conm</td>
				<td width=8% align='right'>DO No&nbsp;&nbsp;</td>
				<td width=25%>$tampilan_do</td>
			</tr>
			<tr><td width=12% align='right'>Main Stockist&nbsp;&nbsp;</td>
				<td width=25%>$loccd - $loccdnm </td>
				<td width=8% align='right'>Total DP&nbsp;&nbsp;</td>
				<td width=25%>$tdp</td>
			</tr>
			<tr><td width=12% align='right'>Created&nbsp;&nbsp;</td>
				<td width=25%>$createnm @ $etdt</td>
				<td width=8% align='right'>Total BV&nbsp;&nbsp;</td>
				<td width=25%>$tbv</td>
			</tr>
			<tr><td width=12% align='right'>&nbsp;&nbsp;</td>
				<td width=25%>&nbsp;</td>
				<td width=8% align='right'>&nbsp;&nbsp;</td>
				<td width=25%>&nbsp;</td>
			</tr>
			<tr>
				<td><input type=\"button\" value=\"&lt;&lt; Kembali\" 
									   onclick=\"$back_button\" 
									   class=\"btn btn-mini btn-warning span20\"></td>
				<td>&nbsp;</td>
				<td width=8% align='right'>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		  </tbody>
	</table>";
	
	echo "<table class='table table-striped table-bordered' width='100%'>
		  	<thead>
		  		<tr><th colspan=8>Detail Transaksi Produk</th></tr>
		  		<tr><th width=5%>No</th>
		  			<th width=15%>Kode Produk</th>
		  			<th>Nama Produk</th>
		  			<th width=5%>Qty</th>
		  			<th width=10%>DP</th>
		  			<th width=8%>BV</th>
		  			<th width=13%>Tot DP</th>
		  			<th width=10%>Tot BV</th>
		  		</tr>
		  	</thead>
		  <tbody>";
		  
		$i = 1;
		$tdp = 0;
		$tbv = 0;
		$tqty = 0;
		$tot_dp = 0;
		$tot_bv = 0;
		$tot_qty = 0;
		foreach($result["product"] as $data) {
			//$trcd = $data->trcd;
			$prdcd = $data->prdcd;
			$prdnm = $data->prdnm;
			$qtyord = $data->qtyord;
			$bv = $data->bv;
			$dp = $data->dp;
			$TOTBV = $data->qtyord * $data->bv;
			$TOTDP = $data->qtyord * $data->dp;
			
			$qtyord = number_format($qtyord, 0, "", ".");	
			$tdpnom = number_format($dp, 0, ",", ".");	
			$tbvnom = number_format($bv, 0, ",", ".");	
			$TOTDPx = number_format($TOTDP, 0, ",", ".");	
			$TOTBVx = number_format($TOTBV, 0, ",", ".");	
			
			echo "<tr>
					<td align=right>$i</td>
					<td align=center>$prdcd</td>
					<td align=left>$prdnm</td>
					<td align=right>$qtyord</td>
					<td align=right>$tdpnom</td>
					<td align=right>$tbvnom</td>
					<td align=right>$TOTDPx</td>
					<td align=right>$TOTBVx</td>
				 </tr>";
			$tqty += $data->qtyord;
			$tdp += $TOTDP;
			$tbv += $TOTBV;
			$i++;
		}	
		
	$tdp = number_format($tdp, 0, ",", ".");	
	$tbv = number_format($tbv, 0, ",", ".");	
	echo "<tr>
			<td colspan='3' align='right'><strong>Grand Total</strong></td>
			<td align=right><strong>$tqty</strong></td>
			<td colspan='2'>&nbsp;</td>
			<td align=right><strong>$tdp</strong></td>
			<td align=right><strong>$tbv</strong></td>
		 </tr>";
				 
	echo "</tbody></table><br />
		  </form>";
		  
	if($result['payment'] != null) {
		echo "<table class='table table-striped table-bordered' width='70%'>
		  	<thead>
		  		<tr><th colspan=8>DETAIL PAY</th></tr>
		  		<tr><th width=5%>No</th>
		  			<th width=15%>Pay Type</th>
		  			<th>Doc No</th>
					<th>Vch Type</th>
		  			<th width=15%>Pay amount</th>
		  			
		  		</tr>
		  	</thead>
		  <tbody>";
		  $i=1;
		  $totalPay = 0;
		  foreach($result["payment"] as $dataPay) {
			  $payAmount = number_format($dataPay->payamt, 0, ",", ".");	
			  echo "<tr>
					<td align=right>$i</td>
					<td align=center>$dataPay->description</td>
					<td align=center>$dataPay->docno</td>
					<td align=center>$dataPay->vchtype</td>
					<td align=right>$payAmount</td>
					
				 </tr>";
				 $totalPay += $dataPay->payamt;
				 $i++;
		  }
		  echo "<tr><td colspan=4 align=right><b>T O T A L</b></td><td align=right>".number_format($totalPay, 0, ",", ".")."</td></tr>";	
		  echo "</tbody>";
		  echo "</table>";
	}	  
?>