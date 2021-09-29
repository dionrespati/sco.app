<?php
    if(empty($result)){
		echo setErrorMessage("No result found");
		echo "<input type=\"button\" value=\"&lt;&lt; Kembali\"
onclick=\"$back_button\"
class=\"btn btn-mini btn-warning span20\">";
        ?>

        <?php
    }else{
?>
   <form id="updMember">
	<table style="width: 100%" class="table table-striped table-bordered bootstrap-datatable datatable">
        <thead>
			<tr>
				<th colspan="7">List TTP </th>
			</tr>
			<tr>
			   <td colspan="2">SSR No</td>
			   <td  colspan="5"><?php echo $result[0]->batchno; ?>&nbsp;</td>
			</tr>
            <tr>
			   <td colspan="2">No Voucher Deposit</td>
			   <td colspan="5">
			   <?php
				echo $result[0]->no_deposit;
			   ?>
			   &nbsp;</td>
			</tr>
		    <tr>
			   <td colspan="2">Bns Period</td>
			   <td  colspan="5"><?php echo $result[0]->bnsperiod; ?>&nbsp;</td>
			</tr>
			<tr>
			   <td colspan="2">DO No</td>
			   <td  colspan="5"><?php
			 	if($result[0]->GDO !== null && $result[0]->GDO !== "") {
			   		echo $result[0]->GDO." by ".$result[0]->GDO_createnm." @".$result[0]->GDOdt;
				} else {
					echo "DO belum di proses/dibuat";
				}
				?>
				&nbsp;</td>
			</tr>
			<tr>
			    <th width="5%">No</th>
				<th width="14%">No Trx</th>
				<th width="14%">No TTP/Vch</th>
				<th>Nama Member</th>
				<th width="10%">Tgl Trx</th>
				<th width="10%">Total BV</th>
				<th width="10%">Total DP</th>
				
				<!--<th width="10%">Bonus Month</th>
				<th width="10%">No Deposit Vch</th>-->

			</tr>
		</thead>
		<tbody>
		<?php
		$no=1;
		$total_dp = 0;
		$total_bv = 0;
		foreach($result as $datax) {

			$prefTrcd = substr($datax->trcd, 0, 2);

			$cash = $datax->cash;
			$fontDepan = "";
			$fontBlkg= "";
			$err = 0;
			if($prefTrcd == "CV" && ($datax->tdp > ($cash + $datax->vcash))) {	
				$fontDepan = "<font color=red>";
				$fontBlkg= "</font>";
				$err=1;
			} else if($prefTrcd == "PV" && ($datax->tdp > ($cash + $datax->pcash))) {
				$fontDepan = "<font color=red>";
				$fontBlkg= "</font>";
				$err=1;
			} 

			$url = "sales/reportstk/trcd/".$datax->trcd;
			echo "<tr>";
			echo "<td align=right>$fontDepan $no $fontBlkg</td>";
			if($err == 1) {
				echo "<td align=center>$fontDepan <a class='btn btn-mini btn-success' onclick=All.ajaxShowDetailonNextForm2('".$url."')>$datax->trcd $fontBlkg</a></td>";
			} else {
				echo "<td align=center>$fontDepan <a onclick=All.ajaxShowDetailonNextForm2('".$url."')>$datax->trcd $fontBlkg</a></td>";
			}
			echo "<td align=center>$fontDepan $datax->orderno $fontBlkg</td>";
			echo "<td>$fontDepan $datax->dfno / $datax->fullnm $fontBlkg</td>";
			echo "<td align=center>$fontDepan $datax->etdt $fontBlkg</td>";
			echo "<td align=right>$fontDepan ".number_format($datax->tbv, 0, ",",".")." $fontBlkg</td>";
			echo "<td align=right>$fontDepan ".number_format($datax->totpay, 0, ",",".")." $fontBlkg</td>";
			
			//echo "<td align=center>$datax->bnsperiod</td>";
			//echo "<td align=center>$datax->no_deposit</td>";
			echo "</tr>";
			$total_dp += $datax->totpay;
			$total_bv += $datax->tbv;
			$no++;
		}
		?>
		<tr><td colspan="5" align="center">T O T A L</td>
		<td align="right"><?php echo number_format($total_bv, 0, ",","."); ?></td>
		<td align="right"><?php echo number_format($total_dp, 0, ",","."); ?></td>
		</tr>
		</tbody>
   </table>
	<table>
	   <tr><td>
		 <?php echo "<input type=\"button\" value=\"&lt;&lt; Kembali\"
onclick=\"$back_button\"
class=\"btn btn-mini btn-warning span20\">"; ?>
		 <!--<input type="button" value="Save Changes" class="btn btn-small btn-primary" onclick="be_member.updateDataMember()" />-->
       </td></tr>
	</table>
	<table style="width: 100%" class="table table-striped table-bordered bootstrap-datatable datatable">
        <thead>
			<tr>
				<th colspan="8">Rekap Produk</th>
			</tr>
			<tr>
				<th width="5%">No</th>
				<th width="14%">Kode Produk</th>
				<th>Nama Produk</th>
				<th width="5%">Qty</th>
				<th width="8%">DP</th>
				<th width="8%">BV</th>
				<th width="10%">Sub Total BV</th>
				<th width="10%">Sub Total DP</th>
				
			</tr>
		</thead>
		<tbody>
		<?php
		$noPrd=1;
		$total_dpx = 0;
		$total_bvx = 0;
		foreach($rekapPrd as $datax) {


			echo "<tr>";
			echo "<td align=right>$noPrd</td>";
			echo "<td align=center>$datax->prdcd</td>";
			echo "<td>$datax->prdnm</td>";
			echo "<td align=right>".number_format($datax->total_qty, 0, ",",".")."</td>";
			echo "<td align=right>".number_format($datax->dp, 0, ",",".")."</td>";
			echo "<td align=right>".number_format($datax->bv, 0, ",",".")."</td>";
			echo "<td align=right>".number_format($datax->total_bv, 0, ",",".")."</td>";
			echo "<td align=right>".number_format($datax->total_dp, 0, ",",".")."</td>";
			
			//echo "<td align=center>$datax->bnsperiod</td>";
			//echo "<td align=center>$datax->no_deposit</td>";
			echo "</tr>";
			$total_dpx += $datax->total_dp;
			$total_bvx += $datax->total_bv;
			$noPrd++;
		}
		?>
		<tr><td colspan="6" align="center">T O T A L</td>
		<td align="right"><?php echo number_format($total_bvx, 0, ",","."); ?></td>
		<td align="right"><?php echo number_format($total_dpx, 0, ",","."); ?></td>
		</tr>
		</tbody>
	</table>
	</form>
<?php
    }
?>