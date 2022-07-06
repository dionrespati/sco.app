<table width="100%" class="table table-striped table-bordered">
		<thead>
			<tr>
				<th colspan="8">DETAIL PRODUK</th>
			</tr>
			<tr>
				<th width="12%">Kode Produk</th>
				<th>Nama produk</th>
				<th width="5%">Qty</th>
				<th width="5%">BV</th>
				<th width="12%">Harga Reseller</th>
				<th width="10%">Sub Total BV</th>
				<th width="14%">Sub Total Harga</th>
				<th width="2%">&nbsp;</th>
			</tr>
		</thead>
		<tbody id="addPrd">
			<?php
			/* $submit_value = $submit_value;
			$tot_dp = 0;
			$tot_bv = 0; */
			$i = 1;
			//$jum_rec = 1;
			$tabindex = $start_tabidx;
			$btnAddProdukIndex = 0;
			//echo "Tab index : ".$tabindex;
			if ($ins == "2") {
				$tot_dp = 0; 
				$tot_bv = 0;
				$submit_value = "Update Transaksi";
				if($detail !== null) {
					foreach ($detail as $prd) {
						$qty = number_format($prd -> qtyord, 0, ",", ".");
						$bv_display = number_format($prd -> bv, 0, ",", ".");
						$dp_display = number_format($prd -> dp, 0, ",", ".");
						$sub_tot_dp = number_format($prd -> TOTDP, 0, ",", ".");
						$sub_tot_bv = number_format($prd -> TOTBV, 0, ",", ".");
						echo "<tr>";
						echo "<td><input onchange=\"Reseller.getProductPrice($i)\" tabindex=\"$tabindex\" type=\"text\" class=\"span12 typeahead\" id=\"prdcd$i\"  name=\"prdcd[]\" value=\"$prd->prdcd\"/></td>";
						echo "<td><input readonly=readonly type=\"text\" class=\"span12 typeahead\" id=\"prdnm$i\"  name=\"prdnm[]\" value=\"$prd->prdnm\"/></td>";
						$tabindex++;
						echo "<td><input onkeyup=\"Reseller.calculateProduct($i)\" tabindex=\"$tabindex\" style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead jumlah\" id=\"jum$i\"  name=\"jum[]\" value=\"$qty\"/></td>";
						echo "<td><input readonly=readonly style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead jumlah\" id=\"poin$i\"  name=\"poin[]\" value=\"$bv_display\"/></td>";
						echo "<td><input readonly=readonly style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead jumlah\" id=\"harga$i\"  name=\"harga[]\" value=\"$dp_display\"/></td>";
						echo "<td><input readonly=readonly style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead\" id=\"sub_tot_bv$i\"  name=\"sub_tot_bv[]\" value=\"$sub_tot_bv\"/></td>";
						echo "<td><input readonly=readonly style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead\" id=\"sub_tot_dp$i\"  name=\"sub_tot_dp[]\" value=\"$sub_tot_dp\"/></td>";
						echo "<td align=center><a onclick=javascript:Reseller.delPayment(this) class='btn btn-mini btn-danger'><i class='icon-trash icon-white'></i></a></td>";
						echo "</tr>";
						$tabindex++;
						//$jum_rec = $i;
						$i++;
						$tot_dp += $prd -> qtyord * $prd -> dp;
						$tot_bv += $prd -> qtyord * $prd -> bv;
					}
			    }
				$btnAddProdukIndex = $tabindex;
			} else {
				$submit_value = "Simpan Transaksi";
				echo "<tr>";
				echo "<td><input onchange=\"Reseller.getProductPrice($i)\" tabindex=\"$tabindex\" type=\"text\" class=\"span12 typeahead\" id=\"prdcd$i\"  name=\"prdcd[]\" value=\"\"/></td>";
				echo "<td><input readonly=readonly type=\"text\" class=\"span12 typeahead\" id=\"prdnm$i\"  name=\"prdnm[]\" value=\"\"/></td>";
				$qty_index = $tabindex + 1;
				echo "<td><input onkeyup=\"Reseller.calculateProduct($i)\" tabindex=\"$qty_index\" style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead jumlah\" id=\"jum$i\"  name=\"jum[]\" value=\"\"/></td>";
				echo "<td><input readonly=readonly style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead jumlah\" id=\"poin$i\"  name=\"poin[]\" value=\"\"/></td>";
				echo "<td><input readonly=readonly style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead jumlah\" id=\"harga$i\"  name=\"harga[]\" value=\"\"/></td>";
				echo "<td><input readonly=readonly style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead\" id=\"sub_tot_bv$i\"  name=\"sub_tot_bv[]\" value=\"\"/></td>";
				echo "<td><input readonly=readonly style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead\" id=\"sub_tot_dp$i\"  name=\"sub_tot_dp[]\" value=\"\"/></td>";
				echo "<td align=center><a onclick=javascript:Reseller.delPayment(this) class='btn btn-mini btn-danger'><i class='icon-trash icon-white'></i></a></td>";
				echo "</tr>";

				$btnAddProdukIndex = $tabindex + 2;
			}
			?>
		</tbody>
		<tbody id="subTotPrd">
			<tr>
				<td>
				<input type="button" tabindex="<?php echo $btnAddProdukIndex; ?>" id="addRow" class="span 20 btn btn-mini btn-info" value="Tambah Produk"  onclick="Reseller.addNewRecordPrd()" />
				</td>
				<td colspan="4" align="center"><b>T O T A L</b></td>
				<td>
				<input type="text" readonly="readonly" style="text-align:right;" class="span12 typeahead" id="total_all_bv"  name="total_all_bv" value="<?php echo number_format($tot_bv, 0, ",", "."); ?>" />
				</td>
				<td>
				<input type="text" readonly="readonly" style="text-align:right;" class="span12 typeahead" id="total_all_dp"  name="total_all_dp" value="<?php echo number_format($tot_dp, 0, ",", "."); ?>" />
				</td>
				<td>&nbsp;</td>
			</tr>
		</tbody>
	</table>
	<input value="<?php echo $ins; ?>" type="hidden" id="ins" name="ins" />
	<input value="<?php echo $jum_rec; ?>" type="hidden" id="rec" name="rec" />
	<input value="<?php echo $tabindex + 1; ?>" type="hidden" id="tabidx" name="tabidx" />
	<input value="<?php echo $jenis_bayar; ?>" type="hidden" id="jenis_bayar" name="jenis_bayar" />
	<input type="hidden" id="prd_voucher" name="prd_voucher" value="<?php echo $prd_voucher; ?>" />

	<table width="100%" class="table table-striped table-bordered">
		<thead>
			<tr>
				<th colspan="2">Payment Info</th>
			</tr>
			<tr>
				<th width="20%">Tipe Pembayaran</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<select id="payType" name="payType" onchange="Reseller.setPayType()">
						<?php
							foreach($listPayType as $listPay) {
								echo "<option value=\"$listPay->id\">$listPay->description</option>";
							}
						?>
					</select>
					<input type="hidden" id="payTypeName" name="payTypeName" value="" />
					<input type="button" class="btn btn-mini btn-primary" value="Tambah" onclick="Reseller.checkPayType()" />
				</td>
				<td id="infoPay">
		
			  </td>
			</tr>
		</tbody>	
		<tbody>

		</tbody>
	</table>
	<table class="table table-striped table-bordered" width="100%">
		<thead>
			<tr>
				<th colspan="6">Pembayaran</th>
			</tr>
		</thead>
		<thead>
			<tr>
				<th>Pay Type</th>
				<th>Pay Amount</th>
				<th>Ref No</th>
				<th>Ref Amount</th>
				<th>Balance</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody id="isiPembayaran">

		</tbody>
		<tbody id="sisaBayar">
			<tr>
				<td>Sisa Harus dibayar</td>
				<td>
					 <div id="sisaHrsDibayarReal" style="text-align:right;"></div>
				</td>
				<td colspan="4">&nbsp;</td>
			</tr>				
		</tbody>
		<tr>
			<td colspan="5">
			<input type="button" class="btn btn-mini btn-primary span20" value="Preview Pembayaran" onclick="Reseller.previewTransaksi()" />
			</td>
		</tr>
	</table>
	<input type="hidden" id="recBayar" name="recBayar" value="0" />
</form>
<!-- <script>
$(function () {
	$(All.get_active_tab() + " #searchInc").dialog({
		modal: true,
		autoOpen: false,
		title: "Incoming Payment",
		width: 300,
		height: 150 
	});
});	
</script> -->


