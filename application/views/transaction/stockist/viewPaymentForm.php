<?php
if($ins == "1") {
	$tabindex = $start_tabidx;
} else {
	//echo "jum rec : ".$jum_rec;
	$tabindex = $start_tabidx + ($jum_rec * 2) - 2;
	//echo "Tab index : ".$tabindex;
}
?>
<table width="100%" border="0">
		<tr>
			<td width="40%" valign="top">
			<table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable">
				<thead>
				<tr>
					<th colspan="3">PILIH PEMBAYARAN</th>
				</tr>
				<tr>
					<th width="25%">Tipe Bayar</th>
					<th colspan="2">Nominal/Kode Voucher</th>
				</tr>
				</thead>
				<tbody id="infoPay">
					<tr>
						<td>
						<select tabindex="25" id="paytype" name="paytype" style="text-align:left; width:140px;">
							<?php
							foreach ($listPay as $pay) {
								echo "<option value=\"$pay->id\">$pay->description</option>";
							}
							?>
						</select></td>
						<td>
						<input tabindex="26" type="text" id="payValue" class="span20" />
						
						</td>
						<td width="25%">
							<input tabindex="27" type="button" id="payBtn" class="span20 btn btn-success" value="Tambah" onclick="Stockist.addPayment()" />
						</td>
					</tr>
					<tr>
						<td>
							<input tabindex="28" value="<< Kembali" type="button" class="btn btn-warning span20" onclick="All.back_to_form(' .nextForm1',' .mainForm')"/>
						</td>
						<td colspan="2">
							<input tabindex="29" value="<?php echo $submit_value; ?>" type="button" class="btn btn-primary span20" onclick="Stockist.saveTrxStockist('<?php echo $form_action; ?>',this.form.id)"/>
		                </td>
					</tr>
				</tbody>
			</table></td>
			<td valign="top">
			<table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable">
				<thead>
					<tr>
						<th colspan="4">DETAIL PEMBAYARAN</th>
					</tr>
				</thead>
				<thead>
					<tr>
						<th width="25%">Tipe Bayar</th>
						<th >No Voucher</th>
						<th width="15%">Jumlah</th>
						<th width="5%">&nbsp;</th>	
					</tr>
				</thead>
				<tbody id="payChoose">
					<?php
					  $totPay = 0;
					  if($ins == "2") {
							$readonly = "";
							$btn = "";
							if($payment != null) {
							 foreach($payment as $pay) {
							 	$payamt = number_format($pay->payamt, 0, ",", ".");
							 	if($pay->paytype != "01") {
							 		$readonly = "readonly=readonly";
									$de = "<td align=center>$pay->docno&nbsp;<input class='checkReff' type=hidden name=payReff[] value=\"$pay->docno\" /></td>";
							 		$btn = "<td align=center>&nbsp;</td>";
							 	} else {
							 		
							 		$de = "<td align=center>$pay->docno&nbsp;<input class='checkReff' type=hidden name=payReff[] value=\"CASH\" /></td>";
							 		$btn = "<td align=center><a onclick=javascript:Stockist.delPayment(this) class='btn btn-mini btn-danger'><i class='icon-trash icon-white'></i></a></td>";
									
							 	}
								echo "<tr>";
								echo "<td align=center>$pay->description<input type=hidden name=payChooseType[] value=\"$pay->paytype\" /></td>";
								echo $de;
								echo "<td align=right><input $readonly onchange=Stockist.hitungTotalBayar() class='forSum' style='text-align:right; width:160px;' type=text name=payChooseValue[] value=\"$payamt\" /></td>";
								
								echo $btn;
								echo "</tr>";
								$totPay += $pay->paytype;
							 }
							}
					   }
					?>
				</tbody>	
				<tbody id="divTotPay">
					<tr>
						<td colspan="2" align="right">&nbsp;<b>Total Harga</b>&nbsp;</td>
						<td>
						<input readonly="readonly" type="text" id="total_cost" style="text-align:right; width:160px;" value="<?php echo number_format($tot_dp, 0, ",", "."); ?>" />
						</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2" align="right">&nbsp;<b>Total Bayar</b>&nbsp;</td>
						<td>
						<input value="0" readonly="readonly" type="text" id="pay" style="text-align:right; width:160px;" value="<?php echo number_format($totPay, 0, ",", "."); ?>" />
						</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2" align="right">&nbsp;<b>Sisa</b>&nbsp;</td>
						<td>
						<input value="0" readonly="readonly" type="text" id="change" style="text-align:right; width:160px;" />
						</td>
						<td>&nbsp;</td>	
					</tr>
				</tbody>
			</table></td>
		</tr>
	</table>

	<input value="<?php echo $ins; ?>" type="hidden" id="ins" name="ins" />
	<input value="<?php echo $jum_rec; ?>" type="hidden" id="rec" name="rec" />
	<input value="<?php echo $tabindex + 1; ?>" type="hidden" id="tabidx" name="tabidx" />
	<input value="<?php echo $jenis_bayar; ?>" type="hidden" id="jenis_bayar" name="jenis_bayar" />
	<input type="hidden" id="prd_voucher" name="prd_voucher" value="<?php echo $prd_voucher; ?>" />
</form>