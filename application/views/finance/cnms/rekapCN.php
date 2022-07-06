<?php
  /* echo "<pre>";
  print_r($reg);
  echo "</pre>"; */
?>
<form id="simpanCN">
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
			<tr>
				<td colspan="6" align="center">T O T A L</td>
				<td align="right"><?php echo number_format($total_bvx, 0, ",","."); ?></td>
				<td align="right"><?php echo number_format($total_dpx, 0, ",","."); ?></td>
			</tr>
		</tbody>
	</table>
  <table style="width: 100%" class="table table-striped table-bordered bootstrap-datatable datatable">
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
  </table>

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
			<tr valign="top">
				<td>
					<select id="payType" name="payType" onchange="cnms.setPayType()">
						<?php
              echo "<option value=\"\">--Select--</option>";
							foreach($listPayType as $listPay) {
								echo "<option value=\"$listPay->id\">$listPay->description</option>";
							}
						?>
					</select>
					<input type="hidden" id="payTypeName" name="payTypeName" value="" />
					<!-- <input type="button" class="btn btn-mini btn-primary" value="Tambah" onclick="cnms.checkPayType()" /> -->
				</td>
				<td id="infoPay" valign="top">
		
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
					 <div id="sisaHrsDibayarReal" style="text-align:right;"><?php echo number_format($total_dpx, 0, ",","."); ?></div>
				</td>
				<td colspan="4">&nbsp;</td>
			</tr>				
		</tbody>
		<tr>
      <td><input type="button" id="kembali" class="btn btn-mini btn-warning span20" value="<< Kembali" onclick="cnms.backToNextForm2()" /></td>
			<td colspan="4">
			<input type="button" class="btn btn-mini btn-primary span20" value="Simpan Transaksi" onclick="cnms.previewTransaksi()" />
			</td>
      <td>&nbsp;</td>
		</tr>
	</table>
	<input type="hidden" id="recBayar" name="recBayar" value="0" />
<?php

?>
<input type="hidden" id="registerno" name="registerno" value = "<?php echo $reg[0]->trcd; ?>" />
<input type="hidden" id="stk" name="stk" value = "<?php echo $reg[0]->dfno; ?>" />
<input type="hidden" id="total_ssr" name="total_ssr" value = "<?php echo $total_dpx; ?>" />
<input type="hidden" id="nossr" name="nossr" value = "<?php echo $batchno; ?>" />
<input type="hidden" id="sc_dfno" name="sc_dfno" value = "<?php echo $rekapPrd[0]->sc_dfno; ?>" />
<!-- <input type="hidden" id="tdp" name="tdp" value = "<?php echo $rekapPrd[0]->header_dp; ?>" />
<input type="hidden" id="tbv" name="tbv" value = "<?php echo $rekapPrd[0]->header_bv; ?>" /> -->

</form>
<script>
  var cnms = {
    tot_pay : parseInt($(All.get_active_tab() + " #total_ssr").val()),
    tot_bayar : 0,
    sisa_bayar : parseInt($(All.get_active_tab() + " #total_ssr").val()),
    list_payment: [],

    setPayType: function() {
      let payName = $(All.get_active_tab() + " #payType option:selected").text();
      $(All.get_active_tab() + " #payTypeName").val(payName);
      cnms.checkPayType();
    },

    checkPayType: function() {
      var stk = $(All.get_active_tab() + " #stk").val();
      var payType = $(All.get_active_tab() + " #payType").val();
      var sisa_bayarx = parseInt(cnms.sisa_bayar);
      var list_payment = cnms.list_payment;

      

      let new_list_payment = "";
      for(let i = 0; i < list_payment.length; i++) {
        new_list_payment += "'" + list_payment[i] + "',";
      }
      //new_list_payment = new_list_payment.substr(0, list_payment.length-1);
      console.log(new_list_payment);
      cnms.consoleInv();

      if(payType === '03' || payType === '08') {
        $(All.get_active_tab() + " #infoPay").html(null);
        var htmlx = "";
        htmlx += "<table class='table table-bordered table-striped' width='100%'>";
        htmlx += "<tr><th>No Incoming Payment</th><th>Jumlah</th><th>Sisa</th><th>Tambah</th></tr>";
        
        let prdx = 1;
        //$.each(arrayData, function (key, value) {

          //var terpakai = cnms.sisa_bayar; 
          var terpakai = 0;
          if(cnms.sisa_bayar !== cnms.tot_pay) {
            terpakai = cnms.sisa_bayar;
          }

          htmlx += "<tr>";
          htmlx += "<td><div align='center'><input id=tipepay" + prdx + " readonly=readonly  type='hidden' class='span12 typeahead' name=tipepay[] value='" + payType + "'/>";
          htmlx += "<input onchange='cnms.checkIncPayV2("+prdx+")' id=incPay" + prdx + " type='text' class='span12 typeahead' name=incPay[] value=''/></div></td>";
          htmlx += "<td><div align='right'><input id=amount" + prdx + " readonly=readonly  type='text' class='span12 typeahead' name=amount[] value='" + terpakai + "'/></div></td>";
          htmlx += "<td><div align='right'><input id=balamt" + prdx + " readonly=readonly  type='hidden' class='span12 typeahead' name=balamt[] value='" + terpakai + "'/><input id=pakai" + prdx + " type='text' style='text-align: right;' class='span20' name='pakai[]' value='"+parseInt(terpakai)+"' /></div></td>";
          htmlx += "<td><div align='center'><input type='button' id=tmbhBtnPay"+ prdx +" class='btn btn-mini btn-info' value='Tambah' onclick='cnms.addIncPay(" + prdx + ")' /></div></td>";
          htmlx += "</tr>";
          prdx++;
        //});  

        htmlx += "</table>";
        $(All.get_active_tab() + " #infoPay").html(htmlx);   
        
      } else if(payType === 'RF02') {
        alert("Tipe Pembayaran Reseller Fee tidak perlu diinput manual..");
      } else {
        $(All.get_active_tab() + " #infoPay").html(null);
        $(All.get_active_tab() + " #infoPay").html(null);
        var htmlx = "";
        htmlx += "<table class='table table-bordered table-striped' width='100%'>";
        htmlx += "<tr><th>No Incoming Payment</th><th>Jumlah</th><th>Sisa</th><th>Tambah</th></tr>";
        
        let prdx = 1;
        //$.each(arrayData, function (key, value) {

          var terpakai = cnms.sisa_bayar; 

          htmlx += "<tr>";
          htmlx += "<td><div align='center'><input id=tipepay" + prdx + " readonly=readonly  type='hidden' class='span12 typeahead' name=tipepay[] value='" + payType + "'/><input id=incPay" + prdx + " readonly=readonly  type='hidden' class='span12 typeahead' name=incPay[] value=''/></div></td>";
          htmlx += "<td><div align='right'><input id=amount" + prdx + " readonly=readonly  type='hidden' class='span12 typeahead' name=amount[] value='" + terpakai + "'/>" + All.num(parseInt(terpakai)) + "</div></td>";
          htmlx += "<td><div align='right'><input id=balamt" + prdx + " readonly=readonly  type='hidden' class='span12 typeahead' name=balamt[] value='" + terpakai + "'/><input id=pakai" + prdx + " type='text' style='text-align: right;' class='span20' name='pakai[]' value='"+parseInt(terpakai)+"' /></div></td>";
          htmlx += "<td><div align='center'><input type='button' class='btn btn-mini btn-info' value='Tambah' onclick='cnms.addIncPay(" + prdx + ")' /></div></td>";
          htmlx += "</tr>";
          prdx++;
        //});  

        htmlx += "</table>";
        $(All.get_active_tab() + " #infoPay").html(htmlx); 
      }
    },

    checkIncPayV2: function(param) {
      event.preventDefault(); // Ensure it is only this code that runs
            let incPay = $(All.get_active_tab() + " #incPay" +param).val();
            let dfno = $(All.get_active_tab() + " #stk").val();
            
            console.log({incPay,dfno});

            All.set_disable_button();
            $.ajax({
                    url: All.get_url('bo/cnmsn/incById'),
                    type: 'POST',
                    dataType: 'json',
                    data: {incPay: incPay, dfno: dfno},
                    success:
                    function(data){
                      All.set_enable_button();
                      if(data.response === "true") {
                        const {amount, balamt} = data.arrayData[0];
                        const jum_amount = parseInt(amount);
                        const jum_balamt = parseInt(balamt);
                        console.log({jum_amount, jum_balamt});

                        if(jum_balamt > 0) {
                          $(All.get_active_tab() + " #amount" +param).val(jum_balamt);
                          $(All.get_active_tab() + " #balamt" +param).val(jum_balamt);
                          if(jum_balamt > cnms.sisa_bayar) {
                            $(All.get_active_tab() + " #pakai" +param).val(cnms.sisa_bayar);
                            $(All.get_active_tab() + " #tmbhBtnPay" +param).removeAttr("disabled");
                          } else {
                            $(All.get_active_tab() + " #pakai" +param).val(jum_balamt);
                            $(All.get_active_tab() + " #tmbhBtnPay" +param).removeAttr("disabled");
                          }
                          
                        } else {
                          alert(`Jumlah Sisa Incoming Payment ${incPay} adalah 0`);
                          $(All.get_active_tab() + " #tmbhBtnPay" +param).attr("disabled", "disabled");
                        }
                      } else {
                        alert(data.message);
                        $(All.get_active_tab() + " #amount" +param).val(null);
                        $(All.get_active_tab() + " #balamt" +param).val(null);
                        $(All.get_active_tab() + " #tmbhBtnPay" +param).attr("disabled", "disabled");
                      }
                    },
                error: function(jqXHR, textStatus, errorThrown) {
                  All.set_enable_button();
                }
        });
    },

    checkIncPay : function(e, param) {
      if(e.keyCode === 13 || e.keyCode === 9){
            e.preventDefault(); // Ensure it is only this code that runs
            let incPay = $(All.get_active_tab() + " #incPay" +param).val();
            let dfno = $(All.get_active_tab() + " #stk").val();
            
            console.log({incPay,dfno});

            All.set_disable_button();
            $.ajax({
                    url: All.get_url('bo/cnmsn/incById'),
                    type: 'POST',
                    dataType: 'json',
                    data: {incPay: incPay, dfno: dfno},
                    success:
                    function(data){
                      All.set_enable_button();
                      if(data.response === "true") {
                        const {amount, balamt} = data.arrayData[0];
                        const jum_amount = parseInt(amount);
                        const jum_balamt = parseInt(balamt);
                        console.log({jum_amount, jum_balamt});

                        if(jum_balamt > 0) {
                          $(All.get_active_tab() + " #amount" +param).val(jum_balamt);
                          $(All.get_active_tab() + " #balamt" +param).val(jum_balamt);
                          if(jum_balamt > cnms.sisa_bayar) {
                            $(All.get_active_tab() + " #pakai" +param).val(cnms.sisa_bayar);
                            $(All.get_active_tab() + " #tmbhBtnPay" +param).removeAttr("disabled");
                          } else {
                            $(All.get_active_tab() + " #pakai" +param).val(jum_balamt);
                            $(All.get_active_tab() + " #tmbhBtnPay" +param).removeAttr("disabled");
                          }
                          
                        } else {
                          alert(`Jumlah Sisa Incoming Payment ${incPay} adalah 0`);
                          $(All.get_active_tab() + " #tmbhBtnPay" +param).attr("disabled", "disabled");
                        }
                      } else {
                        alert(data.message);
                        $(All.get_active_tab() + " #amount" +param).val(null);
                        $(All.get_active_tab() + " #balamt" +param).val(null);
                        $(All.get_active_tab() + " #tmbhBtnPay" +param).attr("disabled", "disabled");
                      }
                    },
                error: function(jqXHR, textStatus, errorThrown) {
                  All.set_enable_button();
                }
        });
        }
    },

    addIncPay : function(param) {
      let incPay = $(All.get_active_tab() + " #incPay" +param).val();
      let balamt = parseInt($(All.get_active_tab() + " #balamt" +param).val());
      let pakai = parseInt($(All.get_active_tab() + " #pakai" +param).val());
      let payTypeName = $(All.get_active_tab() + " #payTypeName").val();
      let tipepay = $(All.get_active_tab() + " #tipepay" +param).val();

      let recBayar = parseInt($(All.get_active_tab() + " #recBayar").val());
      recBayar += 1;

      let sisa = balamt - pakai;
      if((tipepay === "03" || payType === '08') && (incPay === "" || incPay === undefined)) {
        alert("Incoming Payment harus diisi..");
        return;
      }

      if(pakai > balamt) {
        alert("Jumlah yang dipakai tidak boleh lebih besar dari Nilai Incoming Payment..");
        return;
      }

      let htmlx = "";
      htmlx += "<tr id='tdPayId"+ recBayar +"'>";
      htmlx += "<td><div align='center'><input id=byrPayType" + recBayar + " readonly=readonly  type='hidden' class='span12 typeahead' name=byrPayType[] value='" + tipepay + "'/>";
      htmlx += "<input id=byrPayName" + recBayar + " readonly=readonly  type='hidden' class='span12 typeahead' name=byrPayName[] value='" + payTypeName + "'/>" + payTypeName + "</div></td>";
      htmlx += "<td><div align='right'><input id=byrAmount" + recBayar + " readonly=readonly  type='hidden' class='span12 typeahead' name=byrAmount[] value='" + pakai + "'/>" + All.num(parseInt(pakai)) + "</div></td>";
      htmlx += "<td><div align='right'><input id=byrIncPay" + recBayar + " readonly=readonly  type='hidden' class='span12 typeahead' name=byrIncPay[] value='" + incPay + "'/>" + incPay + "</div></td>";
      htmlx += "<td><div align='right'><input id=byrBalamt" + recBayar + " readonly=readonly  type='hidden' class='span12 typeahead' name=byrBalamt[] value='" + balamt + "'/></td>";
      htmlx += "<td><input id=byrSisa" + recBayar + " type='text' readonly='readonly' style='text-align: right;' class='span20' name='byrSisa[]' value='"+parseInt(sisa)+"' /></div></td>";
      htmlx += "<td><a onclick='javascript:cnms.delPayment("+recBayar+")' class='btn btn-mini btn-danger'><i class='icon-trash icon-white'></i></a></a></td>";
      htmlx += "</tr>";
      $(All.get_active_tab() + " #isiPembayaran").append(htmlx);
      cnms.tot_bayar = cnms.tot_bayar + pakai;
      const tbay = cnms.tot_bayar;
      if(tipepay === "03") {
        cnms.list_payment.push(incPay);
      }
      cnms.hitungSisa();
      

      $(All.get_active_tab() + " #recBayar").val(recBayar);
      $(All.get_active_tab() + " #tdInputP" +param).remove();

      cnms.checkPayType();
    },

    delPayment : function(param) {
      let byrPayType = $(All.get_active_tab() + " #byrPayType" +param).val();
      let byrAmount = parseInt($(All.get_active_tab() + " #byrAmount" +param).val());
      let byrIncPay = $(All.get_active_tab() + " #byrIncPay" +param).val();
      
      //if(byrPayType === '03') {
      console.log({byrIncPay});  
        const list_pay = cnms.list_payment;
        const new_list_payment = list_pay.filter(list_pay => list_pay === byrIncPay);
        console.log({new_list_payment});
        cnms.list_payment = new_list_payment;
      //}
      cnms.tot_bayar = cnms.tot_bayar - byrAmount;

      $(All.get_active_tab() + " #tdPayId" + param).remove();
      cnms.hitungSisa();
    },

    hitungSisa: function() {
      cnms.sisa_bayar = cnms.tot_pay - cnms.tot_bayar;
      $(All.get_active_tab() + " #sisaHrsDibayarReal").text("");
      $(All.get_active_tab() + " #sisaHrsDibayarReal").text(All.num(cnms.sisa_bayar));
      cnms.consoleInv();
    },
    
    backToNextForm2: function() {
      $(All.get_active_tab() + ' .nextForm2').html(null);
      $(All.get_active_tab() + ' .nextForm1').show();
      cnms.resetDataReseller();
      cnms.consoleInv();
    },

    resetDataReseller: function() {
      cnms.tot_pay =  0;
      cnms.tot_bayar = 0;
      cnms.sisa_bayar = 0;
      cnms.list_payment = [];
    },

    consoleInv: function() {
      let tot_pay = cnms.tot_pay;
      let tot_bayar = cnms.tot_bayar;
      let sisa_bayar = cnms.sisa_bayar;
      let list_payment = cnms.list_payment;
      console.log({tot_pay, tot_bayar, sisa_bayar, list_payment});
    },

    previewTransaksi: function() {
      All.set_disable_button();
      $.post(All.get_url("bo/cnmsn/cn/save") , $(All.get_active_tab() + " #simpanCN").serialize(), function(data)
      {
         All.set_enable_button();
         
         alert(data.message);
         if(data.response === "true") {
           const {response, arrayData, message} = data;
           $(All.get_active_tab() + ' .nextForm2').html(null);
           const {registerno} = arrayData[0];
           const urlUpdInv = "bo/cnmsn/updateInv/" + registerno;
           $(All.get_active_tab() + ".nextForm1").show();
           All.ajaxShowDetailonNextForm(urlUpdInv);
         } 
      },"json").fail(function () {
        alert("Error requesting page");
        All.set_enable_button();
      });
    }
  }
</script>