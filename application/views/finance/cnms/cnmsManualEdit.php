<?php
  $detail = $resEdit['detail'];
  $header = $resEdit['header'];
  $cnno = $header[0]->trcd2;
  $sc_dfno = $header[0]->sc_dfno;
  $sc_co = $header[0]->sc_co;
  $loccd = $header[0]->loccd;
  $tdp = number_format($header[0]->tdp, 0, ",", ".");
  $tbv = number_format($header[0]->tbv, 0, ",", ".");
	$dfno = $header[0]->dfno;
	$fullnm = $header[0]->fullnm;
	$orderno = $header[0]->orderno;
	$trcd = $header[0]->trcd;
	$pricecode = $header[0]->pricecode;
	$bnsperiod = $header[0]->bnsperiod;
?>
<div class="cnManualDiv">
<form id="cnManualStkEdit" class="formSales">
	<table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable">
		<tr>
			<th colspan="4">Update TTP CN/MS Manual</th>
		</tr>
		<tr>
			<td width="15%">No CN</td>
			<td width="45%">
			<input tabindex="1" readonly="readonly" type="text" style="width:250px;" id="xcnno"  name="cnno" value="<?php echo $cnno; ?>" />
			<!-- <input type="button" class="btn btn-mini btn-primary" value="Check No CN" onclick="getDataCN(this.form.cnno.value)" /> -->
			</td>
			<td width="15%">Total DP</td>
			<td>
			<input  readonly="readonly" type="text" style="text-align: right;" class="span12 typeahead inputan" id="xtdp_cn" name="tdp_cn" value="<?php echo $tdp; ?>" />
			</td>
		</tr>
		<tr>
			<td>Kode Stockist </td>
			<td>
			<input readonly="readonly" type="text" class="span12 typeahead inputan" id="xsc_dfno"  name="sc_dfno" value="<?php echo $sc_dfno; ?>" />
			
			</td>
			<td>Total BV</td>
			<td>
			<input  readonly="readonly" type="text" style="text-align: right;" class="span12 typeahead inputan" id="xtdp_ttp" name="tdp_ttp" value="<?php echo $tbv; ?>" />
			</td>
		</tr>
		<tr>
			<td> C/O Stockist </td>
			<td>
			<input readonly="readonly" type="text" class="span12 typeahead inputan" id="xsc_co"  name="sc_co" value="<?php echo $sc_co; ?>" />
			</td>
			<td>Periode Bonus</td>
			<td>
			<input readonly="readonly" type="text" class="span12 typeahead inputan" id="xbnsperiod"  name="bnsperiod" value="<?php echo $bnsperiod; ?>" />
			</td>
		</tr>
		<tr>
			<td>Main Stockist </td>
			<td>
			<input readonly="readonly" type="text" class="span12 typeahead inputan" id="xloccd"  name="loccd" value="<?php echo $loccd; ?>" />
			</td>
			<td>Status</td>
			<td>
      <input readonly="readonly" type="text" class="span12 typeahead inputan" id="xstatus"  name="status" value="" />
			</td>
		</tr>
		
		<tr>
			<td> ID Member </td>
			<td colspan="3">
			 <input tabindex="2" style="width:250px;" type="text" id="xdfno"  name="dfno" onchange="All.getFullNameByID(this.value,'api/member/check','#xfullnm')" value="<?php echo $dfno; ?>" />
		   <input readonly="readonly" style="width:500px;" type="text" id="xfullnm"  name="fullnm" value="<?php echo $fullnm; ?>" />	
       </td>
		</tr>
    <tr>
			<td> No TTP </td>
			<td colspan="3">
			 <input tabindex="2" style="width:250px;" type="text" id="xorderno"  name="orderno" onchange="All.checkDoubleInput('db2/get/orderno/from/newtrh/','orderno',this.value)" value="<?php echo $orderno; ?>" />
			 <input style="width:250px;" type="hidden" id="xtrcd"  name="trcd"  value="<?php echo $trcd; ?>" />
       <!-- <input type="button" class="btn btn-mini btn-success" value="View List TTP" onclick="getListTTP()" />  -->
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
			<tbody id="xaddPrd">
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
							echo "<td><input onchange=\"getProductPriceEdit($i)\" tabindex=\"$tabindex\" type=\"text\" class=\"span12 typeahead\" id=\"xprdcd$i\"  name=\"prdcd[]\" value=\"$prd->prdcd\"/></td>";
							echo "<td><input readonly=readonly type=\"text\" class=\"span12 typeahead\" id=\"xprdnm$i\"  name=\"prdnm[]\" value=\"$prd->prdnm\"/></td>";
							$tabindex++;
							echo "<td><input onkeyup=\"calculateProduct($i)\" tabindex=\"$tabindex\" style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead jumlah\" id=\"xjum$i\"  name=\"jum[]\" value=\"$qty\"/></td>";
							echo "<td><input readonly=readonly style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead jumlah\" id=\"xpoin$i\"  name=\"poin[]\" value=\"$bv_display\"/></td>";
							echo "<td><input readonly=readonly style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead jumlah\" id=\"xharga$i\"  name=\"harga[]\" value=\"$dp_display\"/></td>";
							echo "<td><input readonly=readonly style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead\" id=\"xsub_tot_bv$i\"  name=\"sub_tot_bv[]\" value=\"$sub_tot_bv\"/></td>";
							echo "<td><input readonly=readonly style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead\" id=\"xsub_tot_dp$i\"  name=\"sub_tot_dp[]\" value=\"$sub_tot_dp\"/></td>";
							echo "<td align=center><a onclick=javascript:delPaymentEdit(this) class='btn btn-mini btn-danger'><i class='icon-trash icon-white'></i></a></td>";
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
					echo "<td><input onchange=\"getProductPriceEdit($i)\" tabindex=\"$tabindex\" type=\"text\" class=\"span12 typeahead\" id=\"prdcd$i\"  name=\"prdcd[]\" value=\"\"/></td>";
					echo "<td><input readonly=readonly type=\"text\" class=\"span12 typeahead\" id=\"prdnm$i\"  name=\"prdnm[]\" value=\"\"/></td>";
					$qty_index = $tabindex + 1;
					echo "<td><input onkeyup=\"Stockist.calculateProduct($i)\" tabindex=\"$qty_index\" style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead jumlah\" id=\"jum$i\"  name=\"jum[]\" value=\"\"/></td>";
					echo "<td><input readonly=readonly style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead jumlah\" id=\"poin$i\"  name=\"poin[]\" value=\"\"/></td>";
					echo "<td><input readonly=readonly style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead jumlah\" id=\"harga$i\"  name=\"harga[]\" value=\"\"/></td>";
					echo "<td><input readonly=readonly style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead\" id=\"sub_tot_bv$i\"  name=\"sub_tot_bv[]\" value=\"\"/></td>";
					echo "<td><input readonly=readonly style=\"text-align:right;\" type=\"text\" class=\"span12 typeahead\" id=\"sub_tot_dp$i\"  name=\"sub_tot_dp[]\" value=\"\"/></td>";
					echo "<td align=center><a onclick=javascript:Stockist.delPaymentEdit(this) class='btn btn-mini btn-danger'><i class='icon-trash icon-white'></i></a></td>";
					echo "</tr>";

					$btnAddProdukIndex = $tabindex + 2;
				}
				?>
			</tbody>
			<tbody id="subTotPrd">
				<tr>
					<td>
					<input type="button" tabindex="<?php echo $btnAddProdukIndex; ?>" id="addRow" class="span 20 btn btn-mini btn-info" value="Tambah Produk"  onclick="addNewRecordPrd()" />
					</td>
								
					<td colspan="4" align="center"><b>T O T A L</b></td>
					<td>
					<input type="text" readonly="readonly" style="text-align:right;" class="span12 typeahead" id="xtotal_all_bv"  name="total_all_bv" value="<?php echo number_format($tot_bv, 0, ",", "."); ?>" />
					</td>
					<td>
					<input type="text" readonly="readonly" style="text-align:right;" class="span12 typeahead" id="xtotal_all_dp"  name="total_all_dp" value="<?php echo number_format($tot_dp, 0, ",", "."); ?>" />
					</td>
					<td>&nbsp;</td>
				</tr>
							<tr>
								<td><input value="<< Kembali" type="button" class="btn btn-warning span20" onclick="All.back_to_form(' .nextForm2',' .nextForm1')"/></td>
								
								<td colspan="6">
								 <input tabindex="29" value="<?php echo $submit_value; ?>" type="button" class="btn btn-primary"  onclick="updateCnManual()"/>
								 <!-- <input tabindex="30" value="Simpan dengan Produk yang sama" type="button" class="btn btn-primary" onclick="simpanCnManualV2()"/></td> -->
							</tr>
			</tbody>
		</table>
			
		<input value="<?php echo $ins; ?>" type="hidden" id="xins" name="ins" />
		<input value="<?php echo $jum_rec; ?>" type="hidden" id="xrec" name="rec" />
		<input value="<?php echo $tabindex + 1; ?>" type="hidden" id="xtabidx" name="tabidx" />
		<input value="<?php echo $jenis_bayar; ?>" type="hidden" id="xjenis_bayar" name="jenis_bayar" />
		<input type="hidden" id="xprd_voucher" name="prd_voucher" value="<?php echo $prd_voucher; ?>" />
		<input type="hidden" id="xjenis_promo" name="jenis_promo" value="<?php echo $jenis_promo; ?>" />
		<input type="hidden" id="xpricecode" name="pricecode" value="<?php echo $pricecode; ?>" />
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

		function delPaymentEdit(idx) {
        $(idx).closest('tr').remove();
        calculateAllPriceEdit();
        hitungTotalBayarEdit();
        //$(All.get_active_tab() + " #jenis_bayar").val("cv");
    }

		function calculateProduct(param) {
        var qty = $(All.get_active_tab() + " #jum" + param).val();
        var nm = /^[0-9]+$/;
        if (qty == '0' || qty == '' || !qty.match(nm)) {
            $(All.get_active_tab() + " #jum" + param).val(1);
            $(All.get_active_tab() + " #jum" + param).select();
            calculateAllPriceEdit();
            hitungTotalBayarEdit();
            //hitungSisaPembayaranVcash();
            //console.log("MASUK SINI");
        } else {
            calculateAllPriceEdit();
            hitungTotalBayarEdit();
            //hitungSisaPembayaranVcash();
        }
    }

		function addNewRecordPrd() {
        var amount = parseInt($(All.get_active_tab() + " #xrec").val());
        var tabidx = parseInt($(All.get_active_tab() + " #xtabidx").val());
        var j = tabidx + 1;
        var z = amount + 1;
        console.log("add idx : " + j);
        console.log("z : " + z);
        var rowhtml = "<tr>";
        rowhtml += "<td><input onchange=getProductPriceEdit(" + z + ") tabindex=" + j + " type='text' class='span12 typeahead' id=xprdcd" + z + "  name=prdcd[] value=''/></td>";
        rowhtml += "<td><input readonly=readonly type='text' class='span12 typeahead' id=xprdnm" + z + "  name=prdnm[] value=''/></td>";
        j++;
        rowhtml += "<td><input attr='jqty' onkeyup=calculateProduct(" + z + ") tabindex=" + j + " style='text-align:right;' type='text' class='span12 typeahead jumlah' id=xjum" + z + "  name=jum[] value='' /></td>";
        rowhtml += "<td><input readonly=readonly style='text-align:right' type='text' class='span12 typeahead jumlah' id=xpoin" + z + "  name=poin[] value='' /></td>";
        rowhtml += "<td><input readonly=readonly style='text-align:right' type='text' class='span12 typeahead jumlah' id=xharga" + z + "  name=harga[] value='' /></td>";
        rowhtml += "<td><input readonly=readonly style='text-align:right' type='text' class='span12 typeahead' id=xsub_tot_bv" + z + "  name=sub_tot_bv[] value='' /></td>";
        rowhtml += "<td><input readonly=readonly style='text-align:right' type='text' class='span12 typeahead' id=xsub_tot_dp" + z + "  name=sub_tot_dp[] value='' /><input readonly=readonly style='text-align:right' type='hidden' class='span12 typeahead' id=sub_tot_dp_real" + z + " attr=prd  name=sub_tot_dp_real[] value='' /></td>";
        rowhtml += "<td align=center><a onclick=javascript:delPaymentEdit(this) class='btn btn-mini btn-danger'><i class='icon-trash icon-white'></i></a></td>";
        rowhtml += "</tr>";
        var y = j + 1;
        //console.log("next tab idx : " +y);
        console.log("amount : " + amount);
        var new_rec = amount + 1;
        $(All.get_active_tab() + " #xaddPrd").append(rowhtml);
        $(All.get_active_tab() + " #xaddRow").removeAttr("tabindex");
        $(All.get_active_tab() + " #xaddRow").attr("tabindex", y);
        $(All.get_active_tab() + " #xrec").val(new_rec);
        console.log("rec : " + new_rec);
        $(All.get_active_tab() + " #xtabidx").val(j);
        $(All.get_active_tab() + " #xprdcd" + z).focus();
    }

		function hitungTotalBayarEdit() {
        var vchno = $(All.get_active_tab() + " #xpayValue").val();
        var total_bayar = 0;
        //var repeat = 0;
        var total_all_dp = parseInt(All.num_normal($(All.get_active_tab() + " #xtotal_all_dp").val()));
        $(All.get_active_tab() + ".forSum").each(function () {

            total_bayar += parseInt(All.num_normal(this.value));
            this.value = All.num(this.value);
        });
        //console.log("Total bayar : " +total_bayar);
        // console.log("Total Cost : " +total_all_dp);
        $(All.get_active_tab() + " #xpay").val(All.num(total_bayar));
        var payValue = total_all_dp - total_bayar;
        if (payValue > 0) {
            var sx = All.num(payValue);
            $(All.get_active_tab() + " #xchange").val("(-) " + sx);
        } else {
            $(All.get_active_tab() + " #xchange").val(All.num(payValue));
        }

        //return total_bayar;
    }


		function getListTTP() {
			const cn_no = $(All.get_active_tab() + " #cnno").val();
			let url = "bo/cnmsn/edit/" + cn_no;
			All.ajaxShowDetailonNextForm(url);
		}

		function simpanCnManualV2() {
			All.set_disable_button();
			$.post(All.get_url('bo/cnmsn/manual/save') , $(All.get_active_tab() + "#cnManualStkEdit").serialize(), function(data)
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

		function updateCnManual() {
			All.set_disable_button();
			$.post(All.get_url('bo/cnmsn/manual/update') , $(All.get_active_tab() + "#cnManualStkEdit").serialize(), function(data)
			{
				All.set_enable_button();
				if(data.response === "false") {
					alert(data.message);
					return;
				}

				alert(data.message);

				$(All.get_active_tab() + " .mainForm").css('display', 'block');
				$(All.get_active_tab() + " .nextForm1").html(null);
				$(All.get_active_tab() + " .nextForm2").html(null);

				const {cnno} = data.arrayData;
				getDataCN(cnno);

				/* $(All.get_active_tab() + " #xaddPrd").html(null);
				$(All.get_active_tab() + " #xrec").val(1);
				$(All.get_active_tab() + " #xtabidx").val(5);
				$(All.get_active_tab() + " #xdfno").val(null);
				$(All.get_active_tab() + " #xfullnm").val(null);
				$(All.get_active_tab() + " #xorderno").val(null);

				var rowhtml = "<tr>";
        rowhtml += "<td><input onchange=getProductPriceEdit(1) tabindex=4 type='text' class='span12 typeahead' id=prdcd1  name=prdcd[] value=''/></td>";
        rowhtml += "<td><input readonly=readonly type='text' class='span12 typeahead' id=prdnm1  name=prdnm[] value=''/></td>";
        rowhtml += "<td><input onkeyup=calculateProduct(1) tabindex=4 style='text-align:right;' type='text' class='span12 typeahead jumlah' id=jum1  name=jum[] value='' /></td>";
        rowhtml += "<td><input readonly=readonly style='text-align:right' type='text' class='span12 typeahead jumlah' id=poin1  name=poin[] value='' /></td>";
        rowhtml += "<td><input readonly=readonly style='text-align:right' type='text' class='span12 typeahead jumlah' id=harga1  name=harga[] value='' /></td>";
        rowhtml += "<td><input readonly=readonly style='text-align:right' type='text' class='span12 typeahead' id=sub_tot_bv1  name=sub_tot_bv[] value='' /></td>";
        rowhtml += "<td><input readonly=readonly style='text-align:right' type='text' class='span12 typeahead' id=sub_tot_dp1 name=sub_tot_dp[] value='' /><input readonly=readonly style='text-align:right' type='hidden' class='span12 typeahead' id=sub_tot_dp_real1 attr=prd name=sub_tot_dp_real[] value='' /></td>";
        rowhtml += "<td align=center><a onclick=javascript:delPaymentEdit(this) class='btn btn-mini btn-danger'><i class='icon-trash icon-white'></i></a></td>";
        rowhtml += "</tr>";

				$(All.get_active_tab() + " #addPrd").append(rowhtml);

				const {cnno} = data.arrayData;
				getDataCN(cnno); */
				
			},"json").fail(function() {
				alert("Error requesting page");
				All.set_enable_button();
			});
		}

		function getProductPriceEdit(param) {
        var prdcd = $(All.get_active_tab() + " #xprdcd" + param).val();
        var pricecode = $(All.get_active_tab() + " #xpricecode").val();
        var prd_voucher = $(All.get_active_tab() + " #xprd_voucher").val();
        var jenis_trx = $(All.get_active_tab() + " #xjenis_bayar").val();
				var jenis_promo = $(All.get_active_tab() + " #xjenis_promo").val();
				var jum = $(All.get_active_tab() + " #xjum" + param).val();
				var cnno = $(All.get_active_tab() + " #xcnno").val();
        
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
                    $(All.get_active_tab() + " #xprdcd" + param).val(arraydata[0].prdcd);
                    $(All.get_active_tab() + " #xprdnm" + param).val(arraydata[0].prdnm);
                    if (prd_voucher == "1") {
                        $(All.get_active_tab() + " #xpoin" + param).val(0);
                        $(All.get_active_tab() + " #xsub_tot_bv" + param).val(0);
                    } else {
                        $(All.get_active_tab() + " #xpoin" + param).val(All.num(parseInt(arraydata[0].bv)));
                        $(All.get_active_tab() + " #xsub_tot_bv" + param).val(All.num(parseInt(arraydata[0].bv)));
                    }

                    $(All.get_active_tab() + " #xharga" + param).val(All.num(parseInt(arraydata[0].dp)));

                    $(All.get_active_tab() + " #xsub_tot_dp" + param).val(All.num(parseInt(arraydata[0].dp)));
                    $(All.get_active_tab() + " #xsub_tot_dp_real" + param).val(parseInt(arraydata[0].dp));
                    calculateAllPriceEdit();
                    //hitungSisaPembayaranVcash();
                } else {
                    alert(data.message);
                    $(All.get_active_tab() + " #xprdcd" + param).val("");
                    $(All.get_active_tab() + " #xprdnm" + param).val("");
                    $(All.get_active_tab() + " #xjum" + param).val("");
                    $(All.get_active_tab() + " #xpoin" + param).val(0);
                    $(All.get_active_tab() + " #xharga" + param).val(0);
                    $(All.get_active_tab() + " #xsub_tot_bv" + param).val(0);
                    $(All.get_active_tab() + " #xsub_tot_dp" + param).val(0);
                    calculateAllPriceEdit();
                    //hitungSisaPembayaranVcash();
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + ':' + xhr.status);
                All.set_enable_button();
            }
        });
    }

		function calculateAllPriceEdit() {
        var rec = parseInt($(All.get_active_tab() + " #xrec").val());
        var ins = $(All.get_active_tab() + " #xins").val();
        var jenis_bayar = $(All.get_active_tab() + " #xjenis_bayar").val();
        var total_dp = 0;
        var total_bv = 0;
        /*$(All.get_active_tab() + ' input[name^="jum"]').each(function() {

        	var dp = $(this).parents('td').find('input[name^="harga"]').val();
        	var bv = $(this).parents('td').find('input[name^="poin"]').val();
        	console.log("dp : " +dp);
        	//total_dp += parseInt($(this).val()) * parseInt(All.num_normal(dp));
        	//total_bv += parseInt($(this).val()) * parseInt(All.num_normal(bv));
        });*/
        //console.log("Tot DP : " +total_dp+ " Tot BV : " +total_bv);

        var count_rec = 0;
        for (var i = 1; i <= rec; i++) {
            var prdcd = $(All.get_active_tab() + " #xprdcd" + i).val();
            console.log("prdcd : " + prdcd);
            if (typeof (prdcd) !== "undefined") {
                //console.log("prdcd : " +prdcd);
                var jum = parseInt($(All.get_active_tab() + " #xjum" + i).val());
                if (jum == "") {
                    jum = 1;
                    //$(All.get_active_tab() + " #jum" +i).val(1)
                }

                var poin = parseInt(All.num_normal($(All.get_active_tab() + " #xpoin" + i).val()));
                var harga = parseInt(All.num_normal($(All.get_active_tab() + " #xharga" + i).val()));

                var sub_harga = jum * harga;
                var sub_poin = jum * poin;
                console.log("sub_harga : " + sub_harga);
                total_bv += sub_poin;
                total_dp += sub_harga;

                $(All.get_active_tab() + " #xsub_tot_bv" + i).val(All.num(sub_poin));
                $(All.get_active_tab() + " #xsub_tot_dp" + i).val(All.num(sub_harga));
                $(All.get_active_tab() + " #xsub_tot_dp_real" + i).val(sub_harga);
                //$(All.get_active_tab() + " #sub_tot_dp" +i).val(All.num(sub_harga));

                $(All.get_active_tab() + " #xtotal_all_bv").val(All.num(total_bv));
                $(All.get_active_tab() + " #xtotal_all_dp").val(All.num(total_dp));
                $(All.get_active_tab() + " #xtotal_all_bv_real").val(total_bv);
                $(All.get_active_tab() + " #xtotal_all_dp_real").val(total_dp);
                $(All.get_active_tab() + " #xpayValue").val(All.num(total_dp));
                $(All.get_active_tab() + " #xtotal_cost").val(All.num(total_dp));
                //$(All.get_active_tab() + " #payValue_real").val(total_dp);

                //totQtyWest += parseInt($("#qty" +i).val());
                //totQtyEast += parseInt($("#qty" +i).val());
            } else {
                console.log("undefiend ");
                count_rec++
            }
        }

        if (ins == "2" && jenis_bayar == "id") {
            var tipe_byr = $(All.get_active_tab() + " #xpayChoose input[name^=payChooseType]").val();
            if (tipe_byr == "01") {
                $(All.get_active_tab() + " #xpayChoose input[name^=payChooseValue]").val(All.num(total_dp));
            }
        }

        if (count_rec == rec) {
            $(All.get_active_tab() + " #xtotal_all_bv").val(All.num(0));
            $(All.get_active_tab() + " #xtotal_all_dp").val(All.num(0));
            $(All.get_active_tab() + " #xtotal_cost").val(All.num(0));
        }
    }
  </script>
	