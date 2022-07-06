<div class="mainForm">
<form id="cnManualStk" class="formSales">
	<table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable">
		<tr>
			<th colspan="4">DATA CN / MS Manual</th>
		</tr>
		<tr>
			<td width="15%">No CN</td>
			<td width="45%">
			<input tabindex="1" type="text" style="width:250px;" id="cnno"  name="cnno" value="" onchange="getDataCN(this.value)" />
			<!-- <input type="button" class="btn btn-mini btn-primary" value="Check No CN" onclick="getDataCN(this.form.cnno.value)" /> -->
			</td>
			<td width="15%">Total DP CN</td>
			<td>
			<input  readonly="readonly" type="text" style="text-align: right;" class="span12 typeahead inputan" id="tdp_cn" name="tdp_cn" value="" />
			</td>
		</tr>
		<tr>
			<td>Kode Stockist </td>
			<td>
			<input readonly="readonly" type="text" class="span12 typeahead inputan" id="sc_dfno"  name="sc_dfno" value="" />
			
			</td>
			<td>Total DP TTP</td>
			<td>
			<input  readonly="readonly" type="text" style="text-align: right;" class="span12 typeahead inputan" id="tdp_ttp" name="tdp_ttp" value="" />
			</td>
		</tr>
		<tr>
			<td> C/O Stockist </td>
			<td>
			<input readonly="readonly" type="text" class="span12 typeahead inputan" id="sc_co"  name="sc_co" value="" />
			</td>
			<td>Periode Bonus</td>
			<td>
			<input readonly="readonly" type="text" class="span12 typeahead inputan" id="bnsperiod"  name="bnsperiod" value="" />
			</td>
		</tr>
		<tr>
			<td>Main Stockist </td>
			<td>
			<input readonly="readonly" type="text" class="span12 typeahead inputan" id="loccd"  name="loccd" value="" />
			</td>
			<td>Status</td>
			<td>
      <input readonly="readonly" type="text" class="span12 typeahead inputan" id="status"  name="status" value="" />
			</td>
		</tr>
		
		<tr>
			<td> ID Member </td>
			<td colspan="3">
			 <input tabindex="2" style="width:250px;" type="text" id="dfno"  name="dfno" onchange="All.getFullNameByID(this.value,'api/member/check','#fullnm')" value="" />
		   <input readonly="readonly" style="width:500px;" type="text" id="fullnm"  name="fullnm" value="" />	
       </td>
		</tr>
    <tr>
			<td> No TTP </td>
			<td colspan="3">
			 <input tabindex="2" style="width:250px;" type="text" id="orderno"  name="orderno" onchange="All.checkDoubleInput('db2/get/orderno/from/newtrh/','orderno',this.value)" value="" />
       <input type="button" class="btn btn-mini btn-success" value="View List TTP" onclick="getListTTP()" /> 
			</td>
		</tr>
	
	</table>

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
					<th width="12%">Harga</th>
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
							$qty = number_format($prd->qtyord, 0, ",", ".");
							$bv_display = number_format($prd->bv, 0, ",", ".");
							$dp_display = number_format($prd->dp, 0, ",", ".");
							$sub_tot_dp = number_format($prd->TOTDP, 0, ",", ".");
							$sub_tot_bv = number_format($prd->TOTBV, 0, ",", ".");
							echo "<tr>";
							echo "<td><input onchange=\"getProductPrice($i)\" tabindex=\"$tabindex\" type=\"text\" class=\"span12 typeahead\" id=\"prdcd$i\"  name=\"prdcd[]\" value=\"$prd->prdcd\"/></td>";
							echo "<td><input readonly=readonly type=\"text\" class=\"span12 typeahead\" id=\"prdnm$i\"  name=\"prdnm[]\" value=\"$prd->prdnm\"/></td>";
							$tabindex++;
							echo "<td><input onkeyup=\"Stockist.calculateProduct($i)\" tabindex=\"$tabindex\" style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead jumlah\" id=\"jum$i\"  name=\"jum[]\" value=\"$qty\"/></td>";
							echo "<td><input readonly=readonly style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead jumlah\" id=\"poin$i\"  name=\"poin[]\" value=\"$bv_display\"/></td>";
							echo "<td><input readonly=readonly style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead jumlah\" id=\"harga$i\"  name=\"harga[]\" value=\"$dp_display\"/></td>";
							echo "<td><input readonly=readonly style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead\" id=\"sub_tot_bv$i\"  name=\"sub_tot_bv[]\" value=\"$sub_tot_bv\"/></td>";
							echo "<td><input readonly=readonly style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead\" id=\"sub_tot_dp$i\"  name=\"sub_tot_dp[]\" value=\"$sub_tot_dp\"/></td>";
							echo "<td align=center><a onclick=javascript:Stockist.delPayment(this) class='btn btn-mini btn-danger'><i class='icon-trash icon-white'></i></a></td>";
							echo "</tr>";
							$tabindex++;
							//$jum_rec = $i;
							$i++;
							$tot_dp += $prd->qtyord * $prd->dp;
							$tot_bv += $prd->qtyord * $prd->bv;
						}
						}
					$btnAddProdukIndex = $tabindex;
				} else {
					$submit_value = "Simpan Transaksi";
					echo "<tr>";
					echo "<td><input onchange=\"getProductPrice($i)\" tabindex=\"$tabindex\" type=\"text\" class=\"span12 typeahead\" id=\"prdcd$i\"  name=\"prdcd[]\" value=\"\"/></td>";
					echo "<td><input readonly=readonly type=\"text\" class=\"span12 typeahead\" id=\"prdnm$i\"  name=\"prdnm[]\" value=\"\"/></td>";
					$qty_index = $tabindex + 1;
					echo "<td><input onkeyup=\"Stockist.calculateProduct($i)\" tabindex=\"$qty_index\" style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead jumlah\" id=\"jum$i\"  name=\"jum[]\" value=\"\"/></td>";
					echo "<td><input readonly=readonly style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead jumlah\" id=\"poin$i\"  name=\"poin[]\" value=\"\"/></td>";
					echo "<td><input readonly=readonly style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead jumlah\" id=\"harga$i\"  name=\"harga[]\" value=\"\"/></td>";
					echo "<td><input readonly=readonly style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead\" id=\"sub_tot_bv$i\"  name=\"sub_tot_bv[]\" value=\"\"/></td>";
					echo "<td><input readonly=readonly style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead\" id=\"sub_tot_dp$i\"  name=\"sub_tot_dp[]\" value=\"\"/></td>";
					echo "<td align=center><a onclick=javascript:Stockist.delPayment(this) class='btn btn-mini btn-danger'><i class='icon-trash icon-white'></i></a></td>";
					echo "</tr>";

					$btnAddProdukIndex = $tabindex + 2;
				}
				?>
			</tbody>
			<tbody id="subTotPrd">
				<tr>
					<td>
					<input type="button" tabindex="<?php echo $btnAddProdukIndex; ?>" id="addRow" class="span 20 btn btn-mini btn-info" value="Tambah Produk"  onclick="Stockist.addNewRecordPrd()" />
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
							<tr>
								<!-- <td><input value="<< Kembali" type="button" class="btn btn-warning span20" onclick="All.back_to_form(' .nextForm1',' .mainForm')"/></td> -->
								
								<td colspan="7">
								 <input tabindex="29" value="<?php echo $submit_value; ?>" type="button" class="btn btn-primary"  onclick="simpanCnManual()"/>
								 <input tabindex="30" value="Simpan dengan Produk yang sama" type="button" class="btn btn-primary" onclick="simpanCnManualV2()"/></td>
							</tr>
			</tbody>
		</table>
			
		<input value="<?php echo $ins; ?>" type="hidden" id="ins" name="ins" />
		<input value="<?php echo $jum_rec; ?>" type="hidden" id="rec" name="rec" />
		<input value="<?php echo $tabindex + 1; ?>" type="hidden" id="tabidx" name="tabidx" />
		<input value="<?php echo $jenis_bayar; ?>" type="hidden" id="jenis_bayar" name="jenis_bayar" />
		<input type="hidden" id="prd_voucher" name="prd_voucher" value="<?php echo $prd_voucher; ?>" />
		<input type="hidden" id="jenis_promo" name="jenis_promo" value="<?php echo $jenis_promo; ?>" />
		<input type="hidden" id="pricecode" name="pricecode" value="" />
	</div> <!-- END form awal -->
	</form> 
</div>	
  <script>
    function getDataCN(param) {
      All.set_disable_button();
      $.ajax({
        url: All.get_url('bo/cnmsn/manual/check/') +param,
        type: 'GET',
        dataType: 'json',
        success:
        function(data){
          All.set_enable_button();
          const {response, message, arrayData} = data;
          if(response === "false") {
            alert(message);
						$(All.get_active_tab() + " .inputan").val(null);
						
            return;
          }

          const {sc_dfno, sc_co, loccd, dp_balance, pricecode, bnsperiod, tdp, total_dp_ttp} = arrayData[0];

          $(All.get_active_tab() + " #sc_dfno").val(sc_dfno);
          $(All.get_active_tab() + " #sc_co").val(sc_co);
          $(All.get_active_tab() + " #loccd").val(loccd);
          $(All.get_active_tab() + " #bnsperiod").val(bnsperiod);
          let status = dp_balance === "1" ? "Balance" : "Unbalance";
          $(All.get_active_tab() + " #status").val(status);
          $(All.get_active_tab() + " #tdp_cn").val(All.num(parseInt(tdp)));
          $(All.get_active_tab() + " #tdp_ttp").val(All.num(parseInt(total_dp_ttp)));
          $(All.get_active_tab() + " #pricecode").val(pricecode); 
        },
        error: function(jqXHR, textStatus, errorThrown) {
          All.set_enable_button();
        }
      });
    }


		function getListTTP() {
			const cn_no = $(All.get_active_tab() + " #cnno").val();
			let url = "bo/cnmsn/edit/" + cn_no;
			All.ajaxShowDetailonNextForm(url);
		}

		function simpanCnManualV2() {
			All.set_disable_button();
			$.post(All.get_url('bo/cnmsn/manual/save') , $(All.get_active_tab() + "#cnManualStk").serialize(), function(data)
			{
				All.set_enable_button();
				if(data.response === "false") {
					alert(data.message);
					return;
				}

				alert(data.message);
				$(All.get_active_tab() + " #dfno").val(null);
				$(All.get_active_tab() + " #fullnm").val(null);

				const {cnno} = data.arrayData;
				getDataCN(cnno);
				
			},"json").fail(function() {
				alert("Error requesting page");
				All.set_enable_button();
			});
		}

		function simpanCnManual() {
			All.set_disable_button();
			$.post(All.get_url('bo/cnmsn/manual/save') , $(All.get_active_tab() + "#cnManualStk").serialize(), function(data)
			{
				All.set_enable_button();
				if(data.response === "false") {
					alert(data.message);
					return;
				}

				alert(data.message);

				$(All.get_active_tab() + " #addPrd").html(null);
				$(All.get_active_tab() + " #rec").val(1);
				$(All.get_active_tab() + " #tabidx").val(5);
				$(All.get_active_tab() + " #dfno").val(null);
				$(All.get_active_tab() + " #fullnm").val(null);
				$(All.get_active_tab() + " #orderno").val(null);

				var rowhtml = "<tr>";
        rowhtml += "<td><input onchange=getProductPrice(1) tabindex=4 type='text' class='span12 typeahead' id=prdcd1  name=prdcd[] value=''/></td>";
        rowhtml += "<td><input readonly=readonly type='text' class='span12 typeahead' id=prdnm1  name=prdnm[] value=''/></td>";
        rowhtml += "<td><input onkeyup=Stockist.calculateProduct(1) tabindex=4 style='text-align:right;' type='text' class='span12 typeahead jumlah' id=jum1  name=jum[] value='' /></td>";
        rowhtml += "<td><input readonly=readonly style='text-align:right' type='text' class='span12 typeahead jumlah' id=poin1  name=poin[] value='' /></td>";
        rowhtml += "<td><input readonly=readonly style='text-align:right' type='text' class='span12 typeahead jumlah' id=harga1  name=harga[] value='' /></td>";
        rowhtml += "<td><input readonly=readonly style='text-align:right' type='text' class='span12 typeahead' id=sub_tot_bv1  name=sub_tot_bv[] value='' /></td>";
        rowhtml += "<td><input readonly=readonly style='text-align:right' type='text' class='span12 typeahead' id=sub_tot_dp1 name=sub_tot_dp[] value='' /><input readonly=readonly style='text-align:right' type='hidden' class='span12 typeahead' id=sub_tot_dp_real1 attr=prd name=sub_tot_dp_real[] value='' /></td>";
        rowhtml += "<td align=center><a onclick=javascript:Stockist.delPayment(this) class='btn btn-mini btn-danger'><i class='icon-trash icon-white'></i></a></td>";
        rowhtml += "</tr>";

				$(All.get_active_tab() + " #addPrd").append(rowhtml);

				const {cnno} = data.arrayData;
				getDataCN(cnno);
				
			},"json").fail(function() {
				alert("Error requesting page");
				All.set_enable_button();
			});
		}

		function getProductPrice(param) {
        var prdcd = $(All.get_active_tab() + " #prdcd" + param).val();
        var pricecode = $(All.get_active_tab() + " #pricecode").val();
        var prd_voucher = $(All.get_active_tab() + " #prd_voucher").val();
        var jenis_trx = $(All.get_active_tab() + " #jenis_bayar").val();
				var jenis_promo = $(All.get_active_tab() + " #jenis_promo").val();
				var jum = $(All.get_active_tab() + " #jum" + param).val();
				var cnno = $(All.get_active_tab() + " #cnno").val();
        
				All.set_disable_button();
        
        $.ajax({
            dataType: 'json',
            url: All.get_url("bo/cnms/product/check"),
            type: 'POST',
            data: {
                productcode: prdcd,
                pricecode: pricecode,
                jenis: jenis_trx,
								jenis_promo: jenis_promo,
								cnno: cnno,
								qty: jum
            },
            success: function (data) {
                All.set_enable_button();
                if (data.response == "true") {
                    arraydata = data.arraydata;
                    $(All.get_active_tab() + " #prdcd" + param).val(arraydata[0].prdcd);
                    $(All.get_active_tab() + " #prdnm" + param).val(arraydata[0].prdnm);
                    if (prd_voucher == "1") {
                        $(All.get_active_tab() + " #poin" + param).val(0);
                        $(All.get_active_tab() + " #sub_tot_bv" + param).val(0);
                    } else {
                        $(All.get_active_tab() + " #poin" + param).val(All.num(parseInt(arraydata[0].bv)));
                        $(All.get_active_tab() + " #sub_tot_bv" + param).val(All.num(parseInt(arraydata[0].bv)));
                    }

                    $(All.get_active_tab() + " #harga" + param).val(All.num(parseInt(arraydata[0].dp)));

                    $(All.get_active_tab() + " #sub_tot_dp" + param).val(All.num(parseInt(arraydata[0].dp)));
                    $(All.get_active_tab() + " #sub_tot_dp_real" + param).val(parseInt(arraydata[0].dp));
                    Stockist.calculateAllPrice();
                    Stockist.hitungSisaPembayaranVcash();
                } else {
                    alert(data.message);
                    $(All.get_active_tab() + " #prdcd" + param).val("");
                    $(All.get_active_tab() + " #prdnm" + param).val("");
                    $(All.get_active_tab() + " #jum" + param).val("");
                    $(All.get_active_tab() + " #poin" + param).val(0);
                    $(All.get_active_tab() + " #harga" + param).val(0);
                    $(All.get_active_tab() + " #sub_tot_bv" + param).val(0);
                    $(All.get_active_tab() + " #sub_tot_dp" + param).val(0);
                    Stockist.calculateAllPrice();
                    Stockist.hitungSisaPembayaranVcash();
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + ':' + xhr.status);
                All.set_enable_button();
            }
        });
    }
  </script>
	