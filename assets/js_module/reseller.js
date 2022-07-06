var $ = jQuery;

var Reseller = {

  tot_pay : 0,
  tot_bayar : 0,
  sisa_bayar : 0,
  list_payment: [],

  getDataReseller : function(param) {
    if(param === "") {
        alert("Tidak boleh kosong..")
    } else {
       All.set_disable_button();
       $.ajax({
           url: All.get_url("reseller/id") + "/" +param,
           type: 'GET',
           dataType: 'json',
           success:
           function(data){

               All.set_enable_button();
               if(data.response == "true") {
                   $(All.get_active_tab() + " #nama_reseller").val(data.arrayData[0].nama_reseller);
                   $(All.get_active_tab() + " #dfno").val(data.arrayData[0].referal_code);
                   $(All.get_active_tab() + " #fullnm").val(data.arrayData[0].referal_name);
               } else {
                   alert(data.message);
                   $(All.get_active_tab() + " #nama_reseller").val(null);
                   $(All.get_active_tab() + " #dfno").val(null);
                   $(All.get_active_tab() + " #fullnm").val(null);
               }
           },
           error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + ':' +xhr.status);
                All.set_enable_button();
           }
       });
    }
  },

  saveRegister: function() {
    All.set_disable_button();
    $.post(All.get_url("reseller/saveregister") , $(All.get_active_tab() + " #invReseller").serialize(), function(data)
    {
      All.set_enable_button();
      const {response, arrayData, message} = data;
      alert(message);
      console.log({response, arrayData, message});
      if (response == "true") {
        const noregister = arrayData.registerno;
        const urlUpdInv = "reseller/updateInv/" + noregister;
        All.ajaxShowDetailonNextForm(urlUpdInv);
      } 

    }, "json").fail(function () {
      alert("Error requesting page");
      All.set_enable_button();
    });
  },

  setPayType: function() {
    let payName = $(All.get_active_tab() + " #payType option:selected").text();
    $(All.get_active_tab() + " #payTypeName").val(payName);
  },

  checkPayType: function() {
    var dfno = $(All.get_active_tab() + " #dfno").val();
    var payType = $(All.get_active_tab() + " #payType").val();
    var sisa_bayarx = parseInt(Reseller.sisa_bayar);
    var list_payment = Reseller.list_payment;

    

    let new_list_payment = "";
    for(let i = 0; i < list_payment.length; i++) {
      new_list_payment += "'" + list_payment[i] + "',";
    }
    //new_list_payment = new_list_payment.substr(0, list_payment.length-1);
    console.log(new_list_payment);
    Reseller.consoleInv();

    if(payType === '03') {
      All.set_disable_button();
       $.ajax({
           url: All.get_url("reseller/listIncPayV2"),
           type: 'POST',
           dataType: 'json',
           data: { 
             incpayment: new_list_payment,
             idmember: dfno
            },
           success:
           function(datax){
               All.set_enable_button();
               const {response, arrayData} = datax;
               if(response == "true") {
                 
                $(All.get_active_tab() + " #infoPay").html(null);
                var htmlx = "";
                htmlx += "<table class='table table-bordered table-striped' width='100%'>";
                htmlx += "<tr><th>No Incoming Payment</th><th>Jumlah</th><th>Sisa</th><th>Pakai</th><th>Tambah</th></tr>";
                
                let prdx = 1;
                $.each(arrayData, function (key, value) {
                  var terpakai = value.balamt;
                  if(sisa_bayarx < value.balamt) {
                    terpakai = sisa_bayarx;
                  } 
                  htmlx += "<tr id='tdInputP"+prdx+"'>";
                  htmlx += "<td><div align='center'><input id=tipepay" + prdx + " readonly=readonly  type='hidden' class='span12 typeahead' name=tipepay[] value='" + payType + "'/><input id=incPay" + prdx + " readonly=readonly  type='hidden' class='span12 typeahead' name=incPay[] value='" + value.trcd + "'/>" + value.trcd + "</div></td>";
                  htmlx += "<td><div align='right'><input id=amount" + prdx + " readonly=readonly  type='hidden' class='span12 typeahead' name=amount[] value='" + value.amount + "'/>" + All.num(parseInt(value.amount)) + "</div></td>";
                  htmlx += "<td><div align='right'><input id=balamt" + prdx + " readonly=readonly  type='hidden' class='span12 typeahead' name=balamt[] value='" + value.balamt + "'/>" + All.num(parseInt(value.balamt)) + "</div></td>";
                  
                  htmlx += "<td><div align='right'><input id=pakai" + prdx + " type='text' style='text-align: right;' class='span20' name='pakai[]' value='"+parseInt(terpakai)+"' /></div></td>";
                  htmlx += "<td><div align='center'><input type='button' class='btn btn-mini btn-info' value='Tambah' onclick='Reseller.addIncPay(" + prdx + ")' /></div></td>";
                  htmlx += "</tr>";
                  prdx++;
                });  

                htmlx += "</table>";
                $(All.get_active_tab() + " #infoPay").html(htmlx); 
               } else {
                   alert(datax.message);
                   $(All.get_active_tab() + " #infoPay").html(null); 
               }
           },
           error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + ':' +xhr.status);
                All.set_enable_button();
           }
       });
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

        var terpakai = Reseller.sisa_bayar; 

        htmlx += "<tr>";
        htmlx += "<td><div align='center'><input id=tipepay" + prdx + " readonly=readonly  type='hidden' class='span12 typeahead' name=tipepay[] value='" + payType + "'/><input id=incPay" + prdx + " readonly=readonly  type='hidden' class='span12 typeahead' name=incPay[] value=''/></div></td>";
        htmlx += "<td><div align='right'><input id=amount" + prdx + " readonly=readonly  type='hidden' class='span12 typeahead' name=amount[] value='" + terpakai + "'/>" + All.num(parseInt(terpakai)) + "</div></td>";
        htmlx += "<td><div align='right'><input id=balamt" + prdx + " readonly=readonly  type='hidden' class='span12 typeahead' name=balamt[] value='" + terpakai + "'/><input id=pakai" + prdx + " type='text' style='text-align: right;' class='span20' name='pakai[]' value='"+parseInt(terpakai)+"' /></div></td>";
        htmlx += "<td><div align='center'><input type='button' class='btn btn-mini btn-info' value='Tambah' onclick='Reseller.addIncPay(" + prdx + ")' /></div></td>";
        htmlx += "</tr>";
        prdx++;
      //});  

      htmlx += "</table>";
      $(All.get_active_tab() + " #infoPay").html(htmlx); 
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
    htmlx += "<td><a onclick='javascript:Reseller.delPayment("+recBayar+")' class='btn btn-mini btn-danger'><i class='icon-trash icon-white'></i></a></a></td>";
    htmlx += "</tr>";
    $(All.get_active_tab() + " #isiPembayaran").append(htmlx);
    Reseller.tot_bayar = Reseller.tot_bayar + pakai;
    const tbay = Reseller.tot_bayar;
    if(tipepay === "03") {
      Reseller.list_payment.push(incPay);
    }
    Reseller.hitungSisa();
    

    $(All.get_active_tab() + " #recBayar").val(recBayar);
    $(All.get_active_tab() + " #tdInputP" +param).remove();
  },

  delPayment : function(param) {
    let byrPayType = $(All.get_active_tab() + " #byrPayType" +param).val();
    let byrAmount = parseInt($(All.get_active_tab() + " #byrAmount" +param).val());
    let byrIncPay = $(All.get_active_tab() + " #byrIncPay" +param).val();
    
    //if(byrPayType === '03') {
    console.log({byrIncPay});  
      const list_pay = Reseller.list_payment;
      const new_list_payment = list_pay.filter(list_pay => list_pay === byrIncPay);
      Reseller.list_payment = new_list_payment;
    //}
    Reseller.tot_bayar = Reseller.tot_bayar - byrAmount;

    $(All.get_active_tab() + " #tdPayId" + param).remove();
    Reseller.hitungSisa();
  },

  hitungSisa: function() {
    Reseller.sisa_bayar = Reseller.tot_pay - Reseller.tot_bayar;
    $(All.get_active_tab() + " #sisaHrsDibayarReal").text("");
    $(All.get_active_tab() + " #sisaHrsDibayarReal").text(All.num(Reseller.sisa_bayar));
    Reseller.consoleInv();
  },

  getProductPrice: function (param) {
    var prdcd = $(All.get_active_tab() + " #prdcd" + param).val();
    var pricecode = $(All.get_active_tab() + " #pricecode").val();
    var prd_voucher = $(All.get_active_tab() + " #prd_voucher").val();
    var jenis_trx = $(All.get_active_tab() + " #jenis_bayar").val();
    var jenis_promo = $(All.get_active_tab() + " #jenis_promo").val();
    All.set_disable_button();
    $.ajax({
        dataType: 'json',
        url: All.get_url("reseller/product/pvr/check"),
        type: 'POST',
        data: {
            productcode: prdcd,
            pricecode: pricecode,
            jenis: jenis_trx,
            jenis_promo: jenis_promo
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
                Reseller.calculateAllPrice();
                Reseller.hitungSisaPembayaranVcash();
            } else {
                alert(data.message);
                $(All.get_active_tab() + " #prdcd" + param).val("");
                $(All.get_active_tab() + " #prdnm" + param).val("");
                $(All.get_active_tab() + " #jum" + param).val("");
                $(All.get_active_tab() + " #poin" + param).val(0);
                $(All.get_active_tab() + " #harga" + param).val(0);
                $(All.get_active_tab() + " #sub_tot_bv" + param).val(0);
                $(All.get_active_tab() + " #sub_tot_dp" + param).val(0);
                Reseller.calculateAllPrice();
                Reseller.hitungSisaPembayaranVcash();
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(thrownError + ':' + xhr.status);
            All.set_enable_button();
        }
    });
  },

  calculateProduct: function (param) {
    var qty = $(All.get_active_tab() + " #jum" + param).val();
    var nm = /^[0-9]+$/;
    if (qty == '0' || qty == '' || !qty.match(nm)) {
        $(All.get_active_tab() + " #jum" + param).val(1);
        $(All.get_active_tab() + " #jum" + param).select();
        Reseller.calculateAllPrice();
        //Stockist.hitungTotalBayar();
        //Stockist.hitungSisaPembayaranVcash();
        //console.log("MASUK SINI");
    } else {
        Reseller.calculateAllPrice();
        //Stockist.hitungTotalBayar();
        //Stockist.hitungSisaPembayaranVcash();
    }
  },

  calculateAllPrice: function () {
    var rec = parseInt($(All.get_active_tab() + " #rec").val());
    var ins = $(All.get_active_tab() + " #ins").val();
    var jenis_bayar = $(All.get_active_tab() + " #jenis_bayar").val();
    var total_dp = 0;
    var total_bv = 0;

    var count_rec = 0;
    for (var i = 1; i <= rec; i++) {
        var prdcd = $(All.get_active_tab() + " #prdcd" + i).val();
        console.log("prdcd : " + prdcd);
        if (typeof (prdcd) !== "undefined") {
            //console.log("prdcd : " +prdcd);
            var jum = parseInt($(All.get_active_tab() + " #jum" + i).val());
            if (jum == "") {
                jum = 1;
                //$(All.get_active_tab() + " #jum" +i).val(1)
            }

            var poin = parseInt(All.num_normal($(All.get_active_tab() + " #poin" + i).val()));
            var harga = parseInt(All.num_normal($(All.get_active_tab() + " #harga" + i).val()));

            var sub_harga = jum * harga;
            var sub_poin = jum * poin;
            console.log("sub_harga : " + sub_harga);
            total_bv += sub_poin;
            total_dp += sub_harga;

            $(All.get_active_tab() + " #sub_tot_bv" + i).val(All.num(sub_poin));
            $(All.get_active_tab() + " #sub_tot_dp" + i).val(All.num(sub_harga));
            $(All.get_active_tab() + " #sub_tot_dp_real" + i).val(sub_harga);
            //$(All.get_active_tab() + " #sub_tot_dp" +i).val(All.num(sub_harga));

            $(All.get_active_tab() + " #total_all_bv").val(All.num(total_bv));
            $(All.get_active_tab() + " #total_all_dp").val(All.num(total_dp));
            $(All.get_active_tab() + " #total_all_bv_real").val(total_bv);
            $(All.get_active_tab() + " #total_all_dp_real").val(total_dp);
            $(All.get_active_tab() + " #payValue").val(All.num(total_dp));
            $(All.get_active_tab() + " #total_cost").val(All.num(total_dp));
            //$(All.get_active_tab() + " #payValue_real").val(total_dp);

            //totQtyWest += parseInt($("#qty" +i).val());
            //totQtyEast += parseInt($("#qty" +i).val());

            Reseller.tot_pay = total_dp;
            const tpay = Reseller.tot_pay;
            Reseller.sisa_bayar = Reseller.tot_pay - Reseller.tot_bayar;
            Reseller.hitungSisa();
        } else {
            console.log("undefiend ");
            count_rec++
        }
    }

    if (ins == "2" && jenis_bayar == "id") {
        var tipe_byr = $(All.get_active_tab() + " #payChoose input[name^=payChooseType]").val();
        if (tipe_byr == "01") {
            $(All.get_active_tab() + " #payChoose input[name^=payChooseValue]").val(All.num(total_dp));
        }
    }

    if (count_rec == rec) {
        $(All.get_active_tab() + " #total_all_bv").val(All.num(0));
        $(All.get_active_tab() + " #total_all_dp").val(All.num(0));
        $(All.get_active_tab() + " #total_cost").val(All.num(0));
    }
  },

  addNewRecordPrd: function () {
    var amount = parseInt($(All.get_active_tab() + " #rec").val());
    var tabidx = parseInt($(All.get_active_tab() + " #tabidx").val());
    var j = tabidx + 1;
    var z = amount + 1;
    console.log("add idx : " + j);
    console.log("z : " + z);
    var rowhtml = "<tr>";
    rowhtml += "<td><input onchange=Reseller.getProductPrice(" + z + ") tabindex=" + j + " type='text' class='span12 typeahead' id=prdcd" + z + "  name=prdcd[] value=''/></td>";
    rowhtml += "<td><input readonly=readonly type='text' class='span12 typeahead' id=prdnm" + z + "  name=prdnm[] value=''/></td>";
    j++;
    rowhtml += "<td><input attr='jqty' onkeyup=Reseller.calculateProduct(" + z + ") tabindex=" + j + " style='text-align:right;' type='text' class='span12 typeahead jumlah' id=jum" + z + "  name=jum[] value='' /></td>";
    rowhtml += "<td><input readonly=readonly style='text-align:right' type='text' class='span12 typeahead jumlah' id=poin" + z + "  name=poin[] value='' /></td>";
    rowhtml += "<td><input readonly=readonly style='text-align:right' type='text' class='span12 typeahead jumlah' id=harga" + z + "  name=harga[] value='' /></td>";
    rowhtml += "<td><input readonly=readonly style='text-align:right' type='text' class='span12 typeahead' id=sub_tot_bv" + z + "  name=sub_tot_bv[] value='' /></td>";
    rowhtml += "<td><input readonly=readonly style='text-align:right' type='text' class='span12 typeahead' id=sub_tot_dp" + z + "  name=sub_tot_dp[] value='' /><input readonly=readonly style='text-align:right' type='hidden' class='span12 typeahead' id=sub_tot_dp_real" + z + " attr=prd  name=sub_tot_dp_real[] value='' /></td>";
    rowhtml += "<td align=center><a onclick=javascript:Reseller.delPayment(this) class='btn btn-mini btn-danger'><i class='icon-trash icon-white'></i></a></td>";
    rowhtml += "</tr>";
    var y = j + 1;
    //console.log("next tab idx : " +y);
    console.log("amount : " + amount);
    var new_rec = amount + 1;
    $(All.get_active_tab() + " #addPrd").append(rowhtml);
    $(All.get_active_tab() + " #addRow").removeAttr("tabindex");
    $(All.get_active_tab() + " #addRow").attr("tabindex", y);
    $(All.get_active_tab() + " #rec").val(new_rec);
    console.log("rec : " + new_rec);
    $(All.get_active_tab() + " #tabidx").val(j);
    $(All.get_active_tab() + " #prdcd" + z).focus();
  },

  hitungSisaPembayaranVcash: function () {
    var total_dp = $(All.get_active_tab() + " #total_all_dp_real").val();


    var nilaiVch = 0;
    $(All.get_active_tab() + " input[attr=amt]").each(function () {
        nilai = $(this).val();

        if (isNaN(nilai)) {
            nilai = 0;
            nilaiVch += nilai;
        } else {
            nilaiVch += parseInt(nilai);
        }

        //nilaiVch += parseInt($(this).val());
        //console.log("nilaiVch : " +nilaiVch);
    });

    var kurang_bayar = nilaiVch - parseInt(total_dp);

    $(All.get_active_tab() + " #tot_all_payment").val(All.num(nilaiVch));
    $(All.get_active_tab() + " #tot_all_payment_real").val(nilaiVch);

    var sisa_cash = 0;
    if (kurang_bayar < 0) {
        sisa_cash = kurang_bayar * -1;
    }

    $(All.get_active_tab() + " #sisa_cash").val(All.num(sisa_cash));
    $(All.get_active_tab() + " #sisa_cash_real").val(sisa_cash);
  }, 

  previewTransaksi: function() {
    All.set_disable_button();
    $.post(All.get_url("reseller/previewInvReseller") , $(All.get_active_tab() + " #formUpdInvReseller").serialize(), function(data)
    {
      All.set_enable_button();
      $(All.get_active_tab() + ".nextForm1").hide();
      if(data.response === "true") {
        alert("OKKKK");
        
        Reseller.resetDataReseller();
        const {header, produk, payment} = data;
        //console.log({header, produk, payment});
        var htmlx = "";
        const urlPrint = "reseller/inv/print";
        const urlx = All.get_url(urlPrint);
        htmlx += "<form id='saveInv' method='POST' action='"+urlx+"' target='_BLANK'>";
        htmlx += "<table width='100%' class='table table-striped table-bordered'>";
        htmlx += "<tr><th colspan='4'>Transaksi Invoice</th></tr>";
        htmlx += "<tr><td>No Register</td><td align='left'>"+header[0].registerno+"</td><td>Reseller</td><td align='left'>"+header[0].kode_reseller+" / " +header[0].reseler_name+ "</td></tr>";
        htmlx += "<tr><td>No Invoice</td><td align='left'>"+header[0].invoiceno+"</td><td>Tgl Invoice</td><td align='left'>"+header[0].invoicedt+"</td></tr>";
        htmlx += "<tr><td>Total DP</td><td align='left'>"+All.num(parseInt(header[0].tdp))+"</td><td>Bonus Period</td><td>"+header[0].bnsperiod+"</td></tr>";
        htmlx += "<tr><td>Total BV</td><td align='left'>"+All.num(parseInt(header[0].tbv))+"</td><td>Ship</td><td>"+header[0].ship_desc+"</td></tr>";
        htmlx += "</table>";
        htmlx += "<input type='hidden' name='cek[]' value='"+header[0].invoiceno+"'>";
        htmlx += "<input type='submit' name='printInv' class='btn btn-mini btn-success' value='Print Invoice Reseller' />";
        htmlx += "<table width='100%' class='table table-striped table-bordered'>";
        htmlx += "<thead><tr><th colspan='8'>Detail Produk</th></tr></thead>";
        htmlx += "<tr><th>No</th><th>Kode Produk</th><th>Nama Produk</th><th>Qty</th><th>BV</th><th>DP</th><th>Sub BV</th><th>Sub DP</th></tr>";
        
        let no = 1;
        let total_bv = 0;
        let total_dp = 0;
        $.each(produk, function (key, value) {
          htmlx += "<tr>";
          htmlx += "<td align='right'>"+no+"</td>";
          htmlx += "<td align='center'>"+value.prdcd+"</td>";
          htmlx += "<td align='left'>"+value.prdnm+"</td>";
          htmlx += "<td align='right'>"+All.num(parseInt(value.qtyord))+"</td>";
          htmlx += "<td align='right'>"+All.num(parseInt(value.bv))+"</td>";
          htmlx += "<td align='right'>"+All.num(parseInt(value.dp))+"</td>";

          let sub_tot_bv = parseInt(value.sub_total_bv);
          let sub_tot_dp = parseInt(value.sub_total_dp);
          htmlx += "<td align='right'>"+All.num(sub_tot_bv)+"</td>";
          htmlx += "<td align='right'>"+All.num(sub_tot_dp)+"</td>";
          htmlx += "</tr>";

          total_bv += sub_tot_bv;
          total_dp += sub_tot_dp;
          no++;
        });   

        htmlx += "<tr><td colspan='6'>T O T A L</td><td>"+All.num(total_bv)+"</td><td>"+All.num(total_dp)+"</td></tr>";
        htmlx += "</table'>";

        

        htmlx += "<table width='100%' class='table table-striped table-bordered'>";
        htmlx += "<tr><th>No</th><th>Tipe</th><th>Payment</th><th>Ref No</th><th>Amount</th></tr>";

        let nox = 1;
        let total_pay = 0;
        $.each(payment, function (key, value) {
          htmlx += "<tr>";
          htmlx += "<td align='right'>"+nox+"</td>";
          htmlx += "<td align='center'>"+value.paytype+"</td>";
          htmlx += "<td align='center'>"+value.pay_desc+"</td>";
          htmlx += "<td align='center'>"+value.docno+"</td>";
          const payamt = parseInt(value.payamt);
          htmlx += "<td align='right'>"+All.num(payamt)+"</td>";
          htmlx += "</tr>";

          total_pay += payamt;
          nox++;
        });   
        htmlx += "</table'>";
        htmlx += "</form>";

        console.log({htmlx});        
        //console.log({header, produk, payment});

        $(All.get_active_tab() + ".nextForm1").html(null);
        $(All.get_active_tab() + ".nextForm1").append(htmlx);
        $(All.get_active_tab() + ".nextForm1").show();
      } else {
        alert(data.message);
      }
    },"json").fail(function () {
      alert("Error requesting page");
      All.set_enable_button();
    });
  },

  simpanTrx: function() {
    All.set_disable_button();
    $.ajax({
        dataType: 'json',
        url: All.get_url("reseller/saveInvReseller"),
        type: 'POST',
        data: {
            inputData: $(All.get_active_tab() + " #inputData").val(),
        },
        success: function (data) {
        
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(thrownError + ':' +xhr.status);
          All.set_enable_button();
        }
    });
  },

  backToMainForm: function() {
    $(All.get_active_tab() + ' .nextForm1').html(null);
    $(All.get_active_tab() + ' .mainForm').show();
    Reseller.resetDataReseller();
    Reseller.consoleInv();
  },

  resetDataReseller: function() {
    Reseller.tot_pay =  0;
    Reseller.tot_bayar = 0;
    Reseller.sisa_bayar = 0;
    Reseller.list_payment = [];
  },

  consoleInv: function() {
    let tot_pay = Reseller.tot_pay;
    let tot_bayar = Reseller.tot_bayar;
    let sisa_bayar = Reseller.sisa_bayar;
    let list_payment = Reseller.list_payment;
    console.log({tot_pay, tot_bayar, sisa_bayar, list_payment});
  }
}