var $ = jQuery;

var Stockist = {
	isPrdpromo: 0,
	getStockistInfo : function(nilai) {
		All.set_disable_button();
		$.ajax({
            url: All.get_url("stockist/id/" +nilai) ,
            type: 'GET',
			dataType: 'json',
            success:
            function(data){
                All.set_enable_button();
				if(data.response == "true") {
					$(All.get_active_tab() + " #fullnm").val(data.arrayData[0].fullnm);
					$(All.get_active_tab() + " #addr1").val(data.arrayData[0].addr1);
					$(All.get_active_tab() + " #addr2").val(data.arrayData[0].addr2);
					$(All.get_active_tab() + " #addr3").val(data.arrayData[0].addr3);
					$(All.get_active_tab() + " #tel_hp").val(data.arrayData[0].tel_hp);
					$(All.get_active_tab() + " #tel_of").val(data.arrayData[0].tel_of);
					$(All.get_active_tab() + " #tel_hm").val(data.arrayData[0].tel_hm);
					$(All.get_active_tab() + " #lastkitno").val(data.arrayData[0].lastkitno);
					$(All.get_active_tab() + " #arkit").val(data.arrayData[0].arkit);
					$(All.get_active_tab() + " #limitkit").val(data.arrayData[0].limitkit);
					$(All.get_active_tab() + " #sisa_kuota").val(data.arrayData[0].sisa_kuota);
				} else {
					alert(data.message);
					All.reset_all_input();
				}
            },
            error: function (xhr, ajaxOptions, thrownError) {
                 alert(thrownError + ':' +xhr.status);
				 All.set_enable_button();
            }
        });
	},

	getStkScType : function(param) {
		var kodestk = $(param).val();
		console.log("pram : " +param.id);
		All.set_disable_button();
		$.ajax({
            url: All.get_url("sales/stk/info/" +kodestk) ,
            type: 'GET',
			dataType: 'json',
            success:
            function(data){
                All.set_enable_button();
				if(data.response == "true") {
					if(param.id == "sc_dfno") {
						var sctype = parseInt(data.arrayData[0].sctype);
						var co_sctype = parseInt($(All.get_active_tab() + " #co_sctype").val());
						//console.log("co_sctype : " +co_sctype);
						//console.log("sctype : " +sctype);
						if(co_sctype > sctype) {
							alert("C/O Stockist tidak boleh lebih rendah dari Stockist..");
							$(All.get_active_tab() + " #sc_name").val(null);
				    		$(All.get_active_tab() + " #sctype").val(null);
						} else {

							$(All.get_active_tab() + " #sc_name").val(data.arrayData[0].fullnm);
				    		$(All.get_active_tab() + " #sctype").val(data.arrayData[0].sctype);
						}


					} else if(param.id == "sc_co") {

						var co_sctype = parseInt(data.arrayData[0].sctype);
						var sctype = parseInt($(All.get_active_tab() + " #sctype").val());
					    //console.log("co_sctype : " +co_sctype);
						//console.log("sctype : " +sctype);
						if(co_sctype > sctype) {
							alert("C/O Stockist tidak boleh lebih rendah dari Stockist..");
							$(All.get_active_tab() + " #sc_co_name").val(null);
				    		$(All.get_active_tab() + " #co_sctype").val(null);
						} else {
							$(All.get_active_tab() + " #sc_co_name").val(data.arrayData[0].fullnm);
				    		$(All.get_active_tab() + " #co_sctype").val(data.arrayData[0].sctype);
						}

					}

				} else {
					alert(data.message);
					//All.reset_all_input();
				}
            },
            error: function (xhr, ajaxOptions, thrownError) {
                 alert(thrownError + ':' +xhr.status);
				 All.set_enable_button();
            }
        });
	},

	getProductPrice : function(param) {
		var prdcd = $(All.get_active_tab() + " #prdcd" +param).val();
		var pricecode = $(All.get_active_tab() + " #pricecode").val();
		var prd_voucher = $(All.get_active_tab() + " #prd_voucher").val();
		All.set_disable_button();
		$.ajax({
            url: All.get_url("product/id/" +prdcd+ "/" +pricecode) ,
            type: 'GET',
			dataType: 'json',
            success:
            function(data){
                All.set_enable_button();
				if(data.response == "true") {
					$(All.get_active_tab() + " #prdcd" +param).val(data.arrayData[0].prdcd);
					$(All.get_active_tab() + " #prdnm" +param).val(data.arrayData[0].prdnm);
					if(prd_voucher == "1") {
						$(All.get_active_tab() + " #poin" +param).val(0);
						$(All.get_active_tab() + " #sub_tot_bv" +param).val(0);
					} else {
						$(All.get_active_tab() + " #poin" +param).val(All.num(parseInt(data.arrayData[0].bv)));
						$(All.get_active_tab() + " #sub_tot_bv" +param).val(All.num(parseInt(data.arrayData[0].bv)));
					}

					$(All.get_active_tab() + " #harga" +param).val(All.num(parseInt(data.arrayData[0].dp)));

					$(All.get_active_tab() + " #sub_tot_dp" +param).val(All.num(parseInt(data.arrayData[0].dp)));
					Stockist.calculateAllPrice();
				} else {
					alert(data.message);
					$(All.get_active_tab() + " #prdcd" +param).val("");
					$(All.get_active_tab() + " #prdnm" +param).val("");
					$(All.get_active_tab() + " #jum" +param).val("");
					$(All.get_active_tab() + " #poin" +param).val(0);
					$(All.get_active_tab() + " #harga" +param).val(0);
					$(All.get_active_tab() + " #sub_tot_bv" +param).val(0);
					$(All.get_active_tab() + " #sub_tot_dp" +param).val(0);
					Stockist.calculateAllPrice();
				}
            },
            error: function (xhr, ajaxOptions, thrownError) {
                 alert(thrownError + ':' +xhr.status);
				 All.set_enable_button();
            }
        });
	},

	calculateProduct : function(param) {
		var qty = $(All.get_active_tab() + " #jum" +param).val();
		var nm =  /^[0-9]+$/;
		if(qty == '0' || qty == '' || !qty.match(nm)) {
			$(All.get_active_tab() + " #jum" +param).val(1);
			$(All.get_active_tab() + " #jum" +param).select();
			Stockist.calculateAllPrice();
			Stockist.hitungTotalBayar();
			//console.log("MASUK SINI");
		} else {
			Stockist.calculateAllPrice();
			Stockist.hitungTotalBayar();
		}
	},

	nextFocus : function() {
		$(All.get_active_tab() + " #addRow").focus();
	},

	calculateAllPrice : function() {
		var rec = parseInt($(All.get_active_tab() + " #rec").val());
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
			for(var i = 1; i <= rec; i++) {
				var prdcd = $(All.get_active_tab() + " #prdcd" +i).val();
				if(typeof(prdcd)  !== "undefined")  {
					//console.log("prdcd : " +prdcd);
					var jum = parseInt($(All.get_active_tab() + " #jum" +i).val());
					if(jum == "") {
						jum = 1;
						//$(All.get_active_tab() + " #jum" +i).val(1)
					}

					var poin = parseInt(All.num_normal($(All.get_active_tab() + " #poin" +i).val()));
					var harga = parseInt(All.num_normal($(All.get_active_tab() + " #harga" +i).val()));

					var sub_harga = jum * harga;
					var sub_poin = jum * poin;
					console.log("sub_harga : " +sub_harga);
		            total_bv += sub_poin;
		            total_dp += sub_harga;

		            $(All.get_active_tab() + " #sub_tot_bv" +i).val(All.num(sub_poin));
		            $(All.get_active_tab() + " #sub_tot_dp" +i).val(All.num(sub_harga));

		            $(All.get_active_tab() + " #total_all_bv").val(All.num(total_bv));
		            $(All.get_active_tab() + " #total_all_dp").val(All.num(total_dp));
		             $(All.get_active_tab() + " #total_all_bv_real").val(total_bv);
		            $(All.get_active_tab() + " #total_all_dp_real").val(total_dp);
		            $(All.get_active_tab() + " #payValue").val(All.num(total_dp));
		            $(All.get_active_tab() + " #total_cost").val(All.num(total_dp));
		            //$(All.get_active_tab() + " #payValue_real").val(total_dp);

		            //totQtyWest += parseInt($("#qty" +i).val());
		            //totQtyEast += parseInt($("#qty" +i).val());
		        }  else {
					console.log("undefiend ");
		        	count_rec++
		        }
	        }

	        if(count_rec == rec) {
	        	$(All.get_active_tab() + " #total_all_bv").val(All.num(0));
		        $(All.get_active_tab() + " #total_all_dp").val(All.num(0));
		        $(All.get_active_tab() + " #total_cost").val(All.num(0));
	        }
	},

	addNewRecordPrd : function() {
		var amount =  parseInt($(All.get_active_tab() + " #rec").val());
        var tabidx = parseInt($(All.get_active_tab() + " #tabidx").val());
        var j = tabidx + 1;
        var z = amount + 1;
		console.log("add idx : " +j);

		var rowhtml = "<tr>";
		rowhtml += "<td><input onchange=Stockist.getProductPrice("+z+") tabindex="+j+" type='text' class='span12 typeahead' id=prdcd"+z+"  name=prdcd[] value=''/></td>";
		rowhtml += "<td><input readonly=readonly type='text' class='span12 typeahead' id=prdnm"+ z +"  name=prdnm[] value=''/></td>";
		j++;
		rowhtml += "<td><input onkeyup=Stockist.calculateProduct("+z+") tabindex="+j+" style='text-align:right;' type='text' class='span12 typeahead jumlah' id=jum"+z+"  name=jum[] value='' /></td>";
		rowhtml += "<td><input readonly=readonly style='text-align:right' type='text' class='span12 typeahead jumlah' id=poin"+z+"  name=poin[] value='' /></td>";
		rowhtml += "<td><input readonly=readonly style='text-align:right' type='text' class='span12 typeahead jumlah' id=harga"+z+"  name=harga[] value='' /></td>";
		rowhtml += "<td><input readonly=readonly style='text-align:right' type='text' class='span12 typeahead' id=sub_tot_bv"+z+"  name=sub_tot_bv[] value='' /></td>";
		rowhtml += "<td><input readonly=readonly style='text-align:right' type='text' class='span12 typeahead' id=sub_tot_dp"+z+"  name=sub_tot_dp[] value='' /></td>";
		rowhtml += "<td align=center><a onclick=javascript:Stockist.delPayment(this) class='btn btn-mini btn-danger'><i class='icon-trash icon-white'></i></a></td>";
		rowhtml += "</tr>";
		var y = j + 1;
		//console.log("next tab idx : " +y);
		var new_rec = amount + 1;
		$(All.get_active_tab() + " #addPrd").append(rowhtml);
		$(All.get_active_tab() + " #addRow").removeAttr("tabindex");
		$(All.get_active_tab() + " #addRow").attr("tabindex", y);
		$(All.get_active_tab() + " #rec").val(new_rec);
		$(All.get_active_tab() + " #tabidx").val(j);
		$("#prdcd" +z).focus();
	},

	addPayment : function() {
		var paytype = $(All.get_active_tab() + " #paytype").val();
		var paytypeTxt = $(All.get_active_tab() + " #paytype option:selected").text();
		var total_all_dp = parseInt(All.num_normal($(All.get_active_tab() + " #total_all_dp").val()));
		var pay = parseInt(All.num_normal($(All.get_active_tab() + " #pay").val()));
		var prd_voucher = $(All.get_active_tab() + " #prd_voucher").val();
		if(paytype == "01") {
			var repeat = 0;
			var vchno = $(All.get_active_tab() + " #payValue").val();
			$(All.get_active_tab() + ".checkReff").each(function(){
                if (this.value == 'CASH') {
                   repeat++;
                }
            });
            if(repeat > 0) {
            	alert("Pembayaran Cash tidak dapat digunakan dua kali..")
            } else {

            	$(All.get_active_tab() + " #jenis_bayar").val("id");
				var payValue = parseInt(All.num_normal($(All.get_active_tab() + " #payValue").val()));
				pay = pay + payValue;
				var rowhtml = "<tr>";
				rowhtml += "<td align=center>"+paytypeTxt+"<input type=hidden name=payChooseType[] value="+paytype+" /></td>";
				rowhtml += "<td align=center>&nbsp;<input class='checkReff' type=hidden name=payReff[] value='CASH' /></td>";
				rowhtml += "<td align=right><input onchange=Stockist.hitungTotalBayar() class='forSum' style='text-align:right; width:160px;' type=text name=payChooseValue[] value="+All.num(payValue)+" /></td>";
				rowhtml += "<td align=center><a onclick=javascript:Stockist.delPayment(this) class='btn btn-mini btn-danger'><i class='icon-trash icon-white'></i></a></td>";
				rowhtml += "</tr>";
				$(All.get_active_tab() + " #payChoose").append(rowhtml);

			    Stockist.hitungTotalBayar();
		    }
		} else {


			var repeat = 0;
			var idmember = $(All.get_active_tab() + " #dfno").val();
			var vchno = $(All.get_active_tab() + " #payValue").val();
			//var payValue = $(All.get_active_tab() + " #payValue").val();
			if(idmember == "") {
				alert("ID Member harus diisi..");
			} else {
				$(All.get_active_tab() + ".checkReff").each(function(){
	                if (this.value == vchno) {
	                   repeat++;
	                }
	            });

	            if(repeat > 0) {
	            	alert("Voucher yang sama tidak dapat digunakan dua kali..")
	            } else {
	            	if(paytype == "10") {

	            		$(All.get_active_tab() + " #jenis_bayar").val("pv");
	            		$(All.get_active_tab() + " #prd_voucher").val(1);
	            	} else {
	            		$(All.get_active_tab() + " #jenis_bayar").val("cv");
	            		$(All.get_active_tab() + " #prd_voucher").val(0);
	            	}

	            	All.set_disable_button();
					$.ajax({
			            url: All.get_url("sales/vc/check/" +idmember+ "/" +vchno+ "/" +paytype) ,
			            type: 'GET',
						dataType: 'json',
			            success:
			            function(data){
			                All.set_enable_button();
							if(data.response == "true") {
								//Tambah data produk bila tipe voucher adalah XPP / XPV / ZVO
								var detProd = data.detProd;
								if(detProd != null) {
									$(All.get_active_tab() + " #addPrd").html(null);
									var rowPrd = "";
									var prd_subtotaldp = 0;
									var prd_subtotalbv = 0;
									var prdx = 1;
									$.each(detProd,function(key, value) {
										rowPrd += "<tr>";
										rowPrd += "<td><input id=prdcd"+prdx+" readonly=readonly  type='text' class='span12 typeahead' name=prdcd[] value='"+value.prdcd+"'/></td>";
										rowPrd += "<td><input id=prdnm"+prdx+" readonly=readonly type='text' class='span12 typeahead' name=prdnm[] value='"+value.prdnm+"'/></td>";
										rowPrd += "<td><input id=jum"+prdx+" readonly=readonly style='text-align:right;' type='text' class='span12 typeahead jumlah' name=jum[] value='"+value.qtyord+"' /></td>";
										rowPrd += "<td><input id=poin"+prdx+" readonly=readonly style='text-align:right' type='text' class='span12 typeahead jumlah' name=poin[] value='"+All.num(parseInt(value.bv))+"' /></td>";
										rowPrd += "<td><input id=harga"+prdx+" readonly=readonly style='text-align:right' type='text' class='span12 typeahead jumlah' name=harga[] value='"+All.num(parseInt(value.dp))+"' /></td>";
										rowPrd += "<td><input id=sub_tot_bv"+prdx+" readonly=readonly style='text-align:right' type='text' class='span12 typeahead'  name=sub_tot_bv[] value='"+All.num(parseInt(value.qtyord*value.bv))+"' /></td>";
										rowPrd += "<td><input id=sub_tot_dp"+prdx+" readonly=readonly style='text-align:right' type='text' class='span12 typeahead' name=sub_tot_dp[] value='"+All.num(parseInt(value.qtyord*value.dp))+"' /></td>";
										rowPrd += "<td align=center><a class='btn btn-mini btn-danger'><i class='icon-trash icon-white'></i></a></td>";
										rowPrd += "</tr>";
										prd_subtotaldp += value.qtyord * value.dp;
										prd_subtotalbv += value.qtyord * value.bv;
										prdx++;
									});
									$(All.get_active_tab() + " #addPrd").append(rowPrd);
								}

								pay = pay + payValue;
								var rowhtml = "<tr>";
									rowhtml += "<td align=center>"+paytypeTxt+"<input type=hidden name=payChooseType[] value="+paytype+" /></td>";
									rowhtml += "<td align=center>"+data.arrayData[0].VoucherNo+"<input class='checkReff' type=hidden name=payReff[] value="+data.arrayData[0].VoucherNo+" /></td>";
									rowhtml += "<td align=right>";
									rowhtml += "<input class='forSum' readonly=readonly style='text-align:right; width:160px;' type=text name=payChooseValue[] value="+All.num(parseInt(data.arrayData[0].VoucherAmt))+" /></td>";
									rowhtml += "<td align=center><a onclick=javascript:Stockist.delPayment(this) class='btn btn-mini btn-danger'><i class='icon-trash icon-white'></i></a></td>";
									rowhtml += "</tr>";

								var xhdydro = vchno.substr(0, 3);
								console.log(xhdydro);
								if(xhdydro == "XHD") {
									rowhtml += "<tr>";
									rowhtml += "<td align=center>Cash<input type=hidden name=payChooseType[] value='01' /></td>";
									rowhtml += "<td align=center><input class='checkReff' type=hidden name=payReff[] value='' /></td>";
									rowhtml += "<td align=right>";
									rowhtml += "<input class='forSum' readonly=readonly style='text-align:right; width:160px;' type=text name=payChooseValue[] value="+All.num(parseInt(prd_subtotaldp))+" /></td>";
									rowhtml += "<td align=center><a onclick=javascript:Stockist.delPayment(this) class='btn btn-mini btn-danger'><i class='icon-trash icon-white'></i></a></td>";
									rowhtml += "</tr>";

									$(All.get_active_tab() + " #total_all_dp").val(All.num(parseInt(prd_subtotaldp)));
									//$(All.get_active_tab() + " #change").val(0);

								}

									$(All.get_active_tab() + " #payChoose").append(rowhtml);
									Stockist.calculateAllPrice();
									Stockist.hitungTotalBayar();
							} else {
								alert(data.message);
							}
			            },
			            error: function (xhr, ajaxOptions, thrownError) {
			                 alert(thrownError + ':' +xhr.status);
							 All.set_enable_button();
			            }
			        });
		       }
		}
	  }
	},

	hitungTotalBayar : function() {
		var vchno = $(All.get_active_tab() + " #payValue").val();
		var total_bayar = 0;
		//var repeat = 0;
		var total_all_dp = parseInt(All.num_normal($(All.get_active_tab() + " #total_all_dp").val()));
		$(All.get_active_tab() + ".forSum").each(function(){

            total_bayar += parseInt(All.num_normal(this.value));
            this.value = All.num(this.value);
        });
        //console.log("Total bayar : " +total_bayar);
       // console.log("Total Cost : " +total_all_dp);
        $(All.get_active_tab() + " #pay").val(All.num(total_bayar));
        var payValue = total_all_dp - total_bayar;
        if(payValue > 0) {
        	var sx = All.num(payValue);
        	$(All.get_active_tab() + " #change").val("(-) "+sx);
        } else {
        	$(All.get_active_tab() + " #change").val(All.num(payValue));
        }

        //return total_bayar;
	},

	delPayment : function(idx) {
		$(idx).closest('tr').remove();
		Stockist.calculateAllPrice();
		Stockist.hitungTotalBayar();
		//$(All.get_active_tab() + " #jenis_bayar").val("cv");
	},

	checkVoucherCash : function(idmember, vchno) {

	},

	saveTrxStockist : function(url, formid) {
		All.set_disable_button();
		All.get_wait_message();
		$.post(All.get_url(url) , $(All.get_active_tab() + "#"+ formid).serialize(), function(data)
        {
             All.set_enable_button();
             if(data.response == "true") {
             	$(All.get_active_tab() + ".nextForm1").hide();
                All.clear_div_in_boxcontent(".nextForm2");

                var res = data.data;
				var urlx = "";
				var rowhtml = "<table class='table table-striped table-bordered' width='70%' id='resSales'>";
					rowhtml += "<thead>";
					rowhtml += "<th colspan=2>Member Sales Transaction</th>";
					rowhtml += "</tr>";
					rowhtml += "</thead><tbody >";

					rowhtml += "<tr>";
					rowhtml += "<td width=25%>No Trx</td>";
					rowhtml += "<td>&nbsp;"+res.trcd+"</td>";
				    rowhtml += "</tr>";
				    rowhtml += "<tr>";
					rowhtml += "<td>No TTP</td>";
					rowhtml += "<td>&nbsp;"+res.orderno+"</td>";
				    rowhtml += "</tr>";
				    rowhtml += "<tr>";
					rowhtml += "<td>ID Member</td>";
					rowhtml += "<td>&nbsp;"+res.dfno+" - "+res.fullnm+"</td>";
				    rowhtml += "</tr>";
				    rowhtml += "<tr>";
					rowhtml += "<td>Total DP</td>";
					rowhtml += "<td>&nbsp;"+All.num(res.totalDP)+"</td>";
				    rowhtml += "</tr>";
				    rowhtml += "<tr>";
					rowhtml += "<td>Total BV</td>";
					rowhtml += "<td>&nbsp;"+All.num(res.totalBV)+"</td>";
				    rowhtml += "</tr>";
		            if(res.pref_trcd == "PV") {
		            	urlx = "sales/pvr/input/form";
		            } else {
		            	urlx = "sales/sub/input/form";
		            }
		            rowhtml += "<tr>";
					rowhtml += "<td colspan=2><input type=button class='btn btn-warning' value='Input New Sales' onclick=Stockist.newFormSales('"+urlx+"') /></td>";
				    rowhtml += "</tr>";
				   rowhtml += "</tbody></table>";
				$(All.get_active_tab() + " .nextForm2").html(rowhtml);

             } else {
             	alert(data.message);
             }

        },"json").fail(function() {
            alert("Error requesting page");
            All.set_enable_button();
        });
	},

	newFormSales : function(url) {
		All.clear_div_in_boxcontent(".nextForm2");
		$(All.get_active_tab() + ".nextForm1").show();
		All.ajaxShowDetailonNextForm(url);

  },

  get_group_preview : function() {
        All.set_disable_button();
        $(All.get_active_tab() + ".mainForm").hide();
        $(All.get_active_tab() + ".nextForm1").hide();
        $.post(All.get_url("sales/generate/preview"),$(All.get_active_tab() + "#generateSaless").serialize(), function(hasil)
        {
            All.set_enable_button();
            $(All.get_active_tab() + ".nextForm2").show();
            $(All.get_active_tab() + ".nextForm2").html(hasil);

        }).fail(function() {
            All.set_enable_button();
            alert("Error requesting page");
            $(All.get_active_tab() + ".nextForm2").html(null)
        });
    },

  generate_sales_sco : function(){
    All.set_disable_button();
    $(".mainForm").hide();
    $(".mainForm").html();
    $(".nextForm1").hide();
    $(".nextForm1").html();
    $(".nextForm2").hide();
    $.post(All.get_url("sales/generate/preview"),$("#generateSaless").serialize(), function(hasil)
    {
        All.set_enable_button();
        $(".nextForm3").show();
        $(".nextForm3").html(null);
        $(".nextForm3").html(hasil);
    }).fail(function() {
        alert("Error requesting page");
        $("#nextForm3").html(null)
    });
    },

  back_to_form_gen : function ()
    {
        All.set_enable_button();
        $(All.get_active_tab() + ".mainForm").show();
        $(All.get_active_tab() + ".nextForm1").show();
        $(All.get_active_tab() + ".nextForm2").hide();
    },

    generate_sales_sco2 : function(){
        All.set_disable_button();
        $(".mainForm").hide();
        $(".mainForm").html();
        $(".nextForm1").hide();
        $(".nextForm1").html();
        $(".nextForm2").hide();
        $.post(All.get_url("sales/generate/sales"),$("#generatesubs").serialize(), function(hasil)
        {
            All.set_enable_button();
            $(".nextForm3").show();
            $(".nextForm3").html(null);
            $(".nextForm3").html(hasil);
        }).fail(function() {
            alert("Error requesting page");
            $("#nextForm3").html(null)
        });
    },

    back_frm_generate_awal : function (){
        All.set_enable_button();
        $(".mainForm").show();
        $(".mainForm").html();
        $(".nextForm1").hide();
        $(".nextForm1").html(null);
        $(".nextForm2").hide();
        $(".nextForm2").html(null);
        $(".nextForm3").hide();
        $(".nextForm3").html(null);
    },

    detailVoucher : function(url) {
    	All.set_disable_button();
		//All.get_image_load();
		$.ajax({
            url: All.get_url(url) ,
            type: 'GET',
            success:
            function(data){
                All.set_enable_button();
				$(All.get_active_tab() + ".mainForm").hide();
                $(All.get_active_tab() + ".nextForm1").html(data);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                 alert(thrownError + ':' +xhr.status);
				 All.set_enable_button();
            }
        });
	},

	get_pvr_info: function () {
		$(All.get_active_tab() + " #save").attr("disabled", "disabled");
		var distributorcode = $(All.get_active_tab() + " #distributorcode").val();
		var vchno = $(All.get_active_tab() + " #vchno").val();
		var registerno = $(All.get_active_tab() + " #registerno").val();


		if (distributorcode == '0' || distributorcode == '' || vchno == '0' || vchno == '') {
		  $(All.get_active_tab() + " #distributorcode").focus();
		  alert('Voucher No dan Distributor Code tidak boleh kosong');
		  $(All.get_active_tab() + " #save").attr("disabled", "disabled");

		} else {
		  //$(All.get_active_tab() + " #save").removeAttr("disabled");
		  var paramx = distributorcode + "/" + vchno + "/" + "10";
		  vchdpn = vchno.substring(0, 3);
		  $.ajax({
			type: 'GET',
			url: All.get_url("sales/vc/check/") + paramx,
			dataType: 'json',
			success: function (data) {
				$(All.get_active_tab() + " #save").removeAttr("disabled");

				if (data.response == 'false') {
					alert(data.message);
					$(All.get_active_tab() + " #save").attr("disabled", "disabled");
				} else if (data.message == 'Session expired' && data.response == 'false') {
					alert(data.message);
					console.log(data.message);
				} else {

					var exist = 0;
					var tipe_promo = 0;
					var jum_vch = 0;
					if(vchdpn == "XPV" || vchdpn == "ZVO" || vchdpn == "XPP" || vchdpn == "XHD") {
						//Stockist.isPrdpromo = 1;
						$(All.get_active_tab() + " #isPrdpromo").val("1");
					}
					$(All.get_active_tab() + " .vch_amt").each(function(event) {
						jum_vch++;
						x = $(this).val();
						if(vchno == x) {
							exist++;
							alert("Voucher sudah ada di dalam list pembayaran..");
							//return false;
						}

					});

				/* 	console.log("double : " +exist);
					console.log("jum vch : " +jum_vch);
					console.log("ISPRDprOMO : " +Stockist.isPrdpromo); */
					if(exist == 0) {
						$(All.get_active_tab() + " #paynominal").val(parseInt(data.arrayData[0].VoucherAmt));
						$(All.get_active_tab() + " #amt").val(parseInt(data.arrayData[0].VoucherAmt));
						$(All.get_active_tab() + " #save").removeAttr("disabled");
						var arrayData = data.arrayData;
						var detProd = data.detProd;


						if(vchdpn == "XPV" || vchdpn == "ZVO" || vchdpn == "XPP" || vchdpn == "XHD") {
							console.log("tipe : " +vchdpn);
							if(jum_vch > 0) {
								alert("Voucher promo ini hanya dapat digunakan dalam 1 TTP pembelanjaan, semua voucher dan produk yang sudah diinput akan di reset");
							}

							$(All.get_active_tab() + " #new_record").attr("disabled", "disabled");
							$(All.get_active_tab() + " #dataVch").html(null);
							Stockist.add_voucher_row(arrayData);
							$(All.get_active_tab() + " #dataPrd").html(null);
							Stockist.addProductPromo(detProd," #dataPrd");
							Stockist.sum_amount();
							Stockist.hitungSisaPembayaran();
							/* var detProd = data.detProd;
							if(detProd != null) {
								$(All.get_active_tab() + " #addPrd").html(null);
							}	 */
						} else {

							var isPrdpromo = $(All.get_active_tab() + " #isPrdpromo").val("1");
							if(isPrdpromo == 1) {
								alert("Sudah ada voucher product tipe promo/hadiah ini di dalam TTP ini..");
							} else {
								$(All.get_active_tab() + " #new_record").removeAttr("disabled");
								Stockist.add_voucher_row(arrayData);
							}
						}
					}
				}
			}
		  });
		}

	  },

	  addProductPromo : function(detProd, setTo) {
		if(detProd != null) {
			$(All.get_active_tab() + setTo).html(null);
			var rowPrd = "";
			var prd_subtotaldp = 0;
			var prd_subtotalbv = 0;
			var prdx = 1;

			$.each(detProd,function(key, value) {
				rowPrd += "<tr  id=" + prdx + ">";
				rowPrd += "<td><input id=productcode"+prdx+" readonly=readonly  type='text' class='span12 typeahead' name=productcode[] value='"+value.prdcd+"'/></td>";
				rowPrd += "<td><input id=productname"+prdx+" readonly=readonly type='text' class='span12 typeahead' name=productname[] value='"+value.prdnm+"'/></td>";
				rowPrd += "<td>";
				rowPrd += "<input id=qty"+prdx+" readonly=readonly style='text-align:right;' type='text' class='span12 typeahead jumlah' name=qty[] value='"+value.qtyord+"' />";
				rowPrd += "<input id=qty_real"+prdx+" readonly=readonly style='text-align:right;' type='hidden' class='span12 typeahead jumlah' name=qty_real[] value='"+parseInt(value.qtyord)+"' />";
				rowPrd += "</td>";
				rowPrd += "<td>";
				rowPrd += "<input id=dp"+prdx+" readonly=readonly style='text-align:right' type='text' class='span12 typeahead jumlah' name=dp[] value='"+All.num(parseInt(value.dp))+"' />";
				rowPrd += "<input id=dp_real"+prdx+" readonly=readonly style='text-align:right' type='hidden' class='span12 typeahead jumlah' name=dp_real[] value='"+parseInt(value.dp)+"' />";
				rowPrd += "</td>";
				rowPrd += "<td>";
				prd_subtotaldp += value.qtyord * value.dp;
				rowPrd += "<input id=total_dp"+prdx+" readonly=readonly style='text-align:right' type='text' class='span12 typeahead jumlah' name=total_dp[] value='"+All.num(parseInt(prd_subtotaldp))+"' />";
				rowPrd += "<input id=total_dp_real"+prdx+" readonly=readonly style='text-align:right' type='hidden' attr=prd class='span12 typeahead jumlah' name=total_dp_real[] value='"+parseInt(prd_subtotaldp)+"' />";
				rowPrd += "</td>";
				rowPrd += "<td align=center>&nbsp;</td>";
				rowPrd += "</tr>";

				prd_subtotalbv += value.qtyord * value.bv;
				prdx++;
			});
			$(All.get_active_tab() + setTo).append(rowPrd);

			$(All.get_active_tab() + " #total_all").val(All.num(prd_subtotaldp));
			$(All.get_active_tab() + " #total_all_real").val(prd_subtotaldp);


			$(All.get_active_tab() + " #totals_all_bv").val(All.num(0));
			$(All.get_active_tab() + " #total_all_real_bv").val(0);
			$(All.get_active_tab() + ' #total_bv').val(All.num(0));
		}
	  },

	  add_voucher_row: function(hasil) {
		var distributorcode = hasil[0].DistributorCode,
			distributorname = $(All.get_active_tab() + ' #distributorname').val(),
			vchno = hasil[0].VoucherNo,
			amount = parseInt(hasil[0].VoucherAmt);


		var rowshtml = "<tr id=" + vchno + ">";
		rowshtml += "<td><input value=" + distributorcode + " type=text class=span20 id=dfno" + vchno + " name=distcode[] readonly /></td>";

		rowshtml += "<td><input readonly=yes type=text class=span12 value='" + distributorname + "' name=distname[] /></td>";
		rowshtml += "<td><input style=text-align:right type=text class='span20 vch_amt' value=" + vchno + " name=vchno[] id=vchID"+ vchno +" class='span12 input-qty' readonly/></td>";
		rowshtml += "<td><input style=text-align:right; readonly=yes type=text class='kanan' value=" + All.num(amount) + "  name=amount[]/>";
		rowshtml += "<input type=hidden id=vch_amt_real" + amount + " name=vch_amt_real[] attr=amt value=" + amount + " /></td>";
		/* rowshtml += "<td><input style=text-align:right; readonly=yes type=text id=bv" + amount + "  name=bv[] class=span12 />";
		rowshtml += "<input type=hidden id=bv_real" + amount + " name=bv_real[] /></td>";
		rowshtml += "<input type=hidden id=tot_bv_real" + amount + " name=tot_bv_real[] /></td>"; */
		// rowshtml += "<td><input readonly=yes style=text-align:right; type=text class=kanan value=" + amount + " name=sub_total[] /></td>";
		// rowshtml += "<input style=text-align:right; type=hidden class=kanan id=total_dp_real" + amount + "  name=total_dp_real[] /></td>";
		rowshtml += "<td align=center><button class='btn btn-mini btn-danger' href=# id=" + amount + " onclick=Stockist.remove_vch_row('" + vchno + "')><i class='icon-trash icon-white'></i> </button>";

		rowshtml += "</tr>";
		$(All.get_active_tab() + " #dataVch").append(rowshtml);
		Stockist.sum_amount();

		Stockist.hitungSisaPembayaran();
		$(All.get_active_tab() + " .kanan").removeClass().addClass("span12 text-right");
		$(All.get_active_tab() + " .tombol").removeClass().addClass("btn btn-small btn-danger");
		$(All.get_active_tab() + " .ikon").removeClass().addClass("icon-trash icon-white");
	  },

	  hitungSisaPembayaran: function() {
		var nilaiVch = 0;
		$(All.get_active_tab() + " input[attr=amt]").each(function(){
		  nilai = $(this).val();

		  if(isNaN(nilai)) {
			nilai = 0;
			nilaiVch += nilai;
		  } else {
			nilaiVch += parseInt(nilai);
		  }

		  //nilaiVch += parseInt($(this).val());
		  //console.log("nilaiVch : " +nilaiVch);
		});

		var nilaiPrd = 0;
		$(All.get_active_tab() + " input[attr=prd]").each(function(){
		  nprd = $(this).val();
		  //console.log("nilaiVch : " +nilaiVch);

		  if(isNaN(nilai)) {
			nprd = 0;
			nilaiPrd += nprd;
		  } else {
			nilaiPrd += parseInt(nprd);
		  }
		  console.log("nilaiPrd : " +nilaiPrd);
		});

		var kurang_bayar = nilaiVch - nilaiPrd;
		//console.log("total vch : " +nilaiVch);
		//console.log("total prd : " +nilaiPrd);

		$(All.get_active_tab() + " #tot_all_payment").val(All.num(nilaiVch));
		$(All.get_active_tab() + " #tot_all_payment_real").val(nilaiVch);

		var sisa_cash = 0;
		if(kurang_bayar < 0) {
		  sisa_cash = kurang_bayar * -1;
		}

		$(All.get_active_tab() + " #sisa_cash").val(All.num(sisa_cash));
		$(All.get_active_tab() + " #sisa_cash_real").val(sisa_cash);

	  },

	  remove_vch_row: function (param) {
		var prefixVch = param.substr(0, 3);
		if(prefixVch == "XPV" || prefixVch == "XPP" || prefixVch == "ZVO" || prefixVch == "XHD") {
			$(All.get_active_tab() + " #dataPrd").html(null);
			$(All.get_active_tab() + " #new_record").removeAttr("disabled");
			$(All.get_active_tab() + " #isPrdpromo").val("0");
		}
		$(All.get_active_tab() + " tr#" + param).remove();
		Stockist.sum_amount();
		Stockist.hitungSisaPembayaran();
	  },

	  sum_amount: function () {
		var sum = 0;
		$(All.get_active_tab() + " #dataVch tr").each(function(){
			sum += parseInt($(this).find('[attr=amt]').val());
			console.log("isi : " +$(this).find('[attr=amt]').val());
		});
		$(All.get_active_tab() + " #total_voucher").val(All.num(sum));
		$(All.get_active_tab() + " #pay_nominal").val(All.num(sum));
		$(All.get_active_tab() + " #pay_nominal_real").val(sum);
	  },

	  sum_product: function () {
		var sum = 0;
		$(All.get_active_tab() + " #dataPrd tr").each(function() {
		  sum += parseInt($(this).find('[attr=prd]').val());
		});
		$(All.get_active_tab() + " #total_all").val(All.num(sum));
		$(All.get_active_tab() + " #total_all_real").val(sum);
		let change_over_cash = $(All.get_active_tab() + " #total_all_real").val(sum) - $(All.get_active_tab() + " #pay_nominal_real").val(sum);
		$(All.get_active_tab() + " #sisa_payment_real").val(change_over_cash);
	  },

	  add_new_sales_row: function () {
		var amount = parseInt($(All.get_active_tab() + " #amount").val());
		var tabidx = parseInt($(All.get_active_tab() + " #tabidx").val());
		var j = tabidx;
		var z = parseInt($(All.get_active_tab() + " #amt_record").val()) + 1;
		$(All.get_active_tab() + " #amt_record").val(z);

		var koma = ",";

		var rowshtml = "<tr id=" + amount + ">";
		rowshtml += "<td><input onchange=Stockist.helper_show_prod_by_id(" + amount + ") tabindex=" + j + " type=text class=span12 id=productcode" + amount + " name=productcode[] /></td>";
		rowshtml += "<td><input readonly=yes type=text class=span12 id=productname" + amount + "  name=productname[] /></td>";
		j++;
		//rowshtml += "<td><input tabindex="+ j +" style=text-align:right type=text id=qty"+ amount +" name=qty[] class='span12 input-qty' onkeypress='if(event.keyCode==9) {Sales.sales_qty_format("+ amount +");}' />";
		rowshtml += "<td><input tabindex=" + j + " style=text-align:right type=text id=qty" + amount + " name=qty[] class='span12 input-qty' onblur=Stockist.count_qty_product(" + amount + ") />";
		rowshtml += "<input style=text-align:right;  type=hidden class=span3 id=qty_real" + amount + " name=qty_real[] /></td>";
		j++;
		rowshtml += "<td><input style=text-align:right; readonly=yes type=text class=kanan id=dp" + amount + "  name=dp[]/>";
		rowshtml += "<input type=hidden id=dp_real" + amount + " name=dp_real[] /></td>";
		/* rowshtml += "<td><input style=text-align:right; readonly=yes type=text id=bv" + amount + "  name=bv[] class=span12 />";
		rowshtml += "<input type=hidden id=bv_real" + amount + " name=bv_real[] /></td>";
		rowshtml += "<input type=hidden id=tot_bv_real" + amount + " name=tot_bv_real[] /></td>"; */
		rowshtml += "<td><input readonly=yes style=text-align:right; type=text class=kanan id=total_dp" + amount + "  name=total_dp[] />";
		rowshtml += "<input style=text-align:right; type=hidden class='kanan' attr=prd id=total_dp_real" + amount + "  name=total_dp_real[] /></td>";
		rowshtml += "<td align=center><button class='btn btn-mini btn-danger' href=# id=" + amount + " onclick=Stockist.delete_row('" + amount + "')><i class='icon-trash icon-white'></i> </button>";

		rowshtml += "</tr>";
		var x = amount + 1;
		var y = tabidx + 2;


		$(All.get_active_tab() + " #amount").val(x);
		$(All.get_active_tab() + " #tabidx").val(y);
		$(All.get_active_tab() + " #dataPrd").append(rowshtml);
		$(All.get_active_tab() + " .kanan").removeClass().addClass("span12 text-right");
		$(All.get_active_tab() + " .tombol").removeClass().addClass("btn btn-small btn-danger");
		$(All.get_active_tab() + " .ikon").removeClass().addClass("icon-trash icon-white");
		$(All.get_active_tab() + " #productcode" + amount).focus();

		//$("#new_record").focus();
	  },

	  helper_show_prod_by_id: function (frm) {
		var pay_type = $(All.get_active_tab() + " #payn_type").val();
		var counter = 0;
		for (i = 0; i < document.getElementById('dataPrd').getElementsByTagName("tr").length + 1; i++) {
		  var nama = $(All.get_active_tab() + " #productcode" + i).val();
		  console.log("nama=" + nama);
		  console.log("i=" + i);

		  if ($(All.get_active_tab() + " #productcode" + frm).val() == nama && i != frm) {
			counter++;
		  }
		}

		if (counter > 0) {
		  $(All.get_active_tab() + " #productcode" + frm).val('')
		  alert('Produk yang anda masukkan sama (duplikat)');

		} else {
		  $.ajax({
			dataType: 'json',
			url: All.get_url("sales/product/pvr/check"),
			type: 'POST',
			data: {
			  productcode: $(All.get_active_tab() + " #productcode" + frm).val(),
			  pricecode: $(All.get_active_tab() + " #pricecode").val()
			},
			success: function (data) {
			  if (data.response == 'true') {

				$(All.get_active_tab() + " #productname" + frm).val(data.arraydata[0].prdnm);
				var bv = parseInt(data.arraydata[0].bv);
				var dp = parseInt(data.arraydata[0].dp);
				var qty = parseInt($(All.get_active_tab() + " #qty" + frm).val());
				if (isNaN(qty)) {
				  qty = 1;
				}
				var tot_bv = bv * qty;
				var tot_dp = dp * qty;
				var tot_all_price = 0;
				var tot_all_bv = 0;

				//alert('tot_bv' +tot_bv);
				$(All.get_active_tab() + " #qty" + frm).val('1');
				$(All.get_active_tab() + " #dp" + frm).val(All.num(dp));
				//$("#bv" +frm).val(parseInt(0));
				$(All.get_active_tab() + " #bv" + frm).val(All.num(bv));

				$(All.get_active_tab() + " #dp_real" + frm).val(parseInt(dp));
				$(All.get_active_tab() + " #bv_real" + frm).val(parseInt(bv));
				$(All.get_active_tab() + " #total_dp" + frm).val(All.num((tot_dp)));
				$(All.get_active_tab() + " #total_dp_real" + frm).val(tot_dp);
				$(All.get_active_tab() + " #new_record" + frm).focus();
				if (pay_type == '10') {
				  $(All.get_active_tab() + ' #tot_bv_real' + frm).val(0);
				  $(All.get_active_tab() + ' #total_bv' + frm).val(All.num(parseInt(0)));
				} else {
				  $(All.get_active_tab() + ' #tot_bv_real' + frm).val(0);
				  $(All.get_active_tab() + ' #total_bv' + frm).val(All.num(parseInt(0)));
				}

				//}

				var jum_trx = parseInt($(All.get_active_tab() + " #amt_record").val());
				for (i = 1; i <= jum_trx; i++) {
				  var ss = $(All.get_active_tab() + " #qty" + i).val();
				  if (ss === undefined) {
					tot_all_price = tot_all_price + 0;
					tot_all_bv = tot_all_bv + 0;
				  } else {
					tot_all_price += parseInt($(All.get_active_tab() + " #total_dp_real" + i).val());
					tot_all_bv += parseInt($(All.get_active_tab() + " #tot_bv_real" + i).val())
				  }
				}
				$(All.get_active_tab() + " #new_record" + frm).focus();
				$(All.get_active_tab() + " #total_all").val(All.num(tot_all_price));
				$(All.get_active_tab() + " #total_all_real").val(tot_all_price);


				$(All.get_active_tab() + " #totals_all_bv").val(All.num(0));
				$(All.get_active_tab() + " #total_all_real_bv").val(0);
				$(All.get_active_tab() + ' #total_bv').val(All.num(0));

				$(All.get_active_tab() + " #new_record" + frm).focus();

				$(All.get_active_tab() + " #totalDp").val(All.num(tot_all_price));
				$(All.get_active_tab() + " #totalDp_real").val(tot_all_price);
				$(All.get_active_tab() + " #new_record" + frm).focus();

				//Stockist.hitung();
				Stockist.hitungSisaPembayaran();
			  } else {
				alert(data.message);
				$(All.get_active_tab() + " #productname" + frm).val(null);
				$(All.get_active_tab() + " #qty" + frm).val(null);
				$(All.get_active_tab() + " #qty_real" + frm).val(null);
				$(All.get_active_tab() + " #dp" + frm).val(null);
				$(All.get_active_tab() + " #dp_real" + frm).val(null);
				$(All.get_active_tab() + " #bv" + frm).val(null);
				$(All.get_active_tab() + " #bv_real" + frm).val(null);
				$(All.get_active_tab() + " #total_dp" + frm).val(null);
				$(All.get_active_tab() + " #total_dp_real" + frm).val(null);
				$(All.get_active_tab() + " #productcode" + frm).focus();
			  }
			},
			error: function (xhr, ajaxOptions, thrownError) {
			  alert(thrownError + ':' + xhr.status);
			}
		  });

		}

		//alert('isi : ' +data.dp);
	  },

	  count_qty_product: function (param) {
		var qty = $(All.get_active_tab() + " #qty" + param).val();
		var price = $(All.get_active_tab() + " #dp_real" + param).val();
		sub_total = price * qty;
		$(All.get_active_tab() + " #total_dp" + param).val(All.num(sub_total));
		$(All.get_active_tab() + " #total_dp_real" + param).val(sub_total);
		this.sum_product();
		Stockist.hitungSisaPembayaran();
	  },

	  delete_row: function (frm) {
		$("tr#" + frm).remove();

		Stockist.sum_amount();
		Stockist.sum_product();
		Stockist.hitungSisaPembayaran();
		// Sales.hitung();
		// Sales.getchangepvr();
      },

      detailTtp : function(url) {
    	All.set_disable_button();
		//All.get_image_load();
		$.ajax({
            url: All.get_url(url) ,
            type: 'GET',
            success:
            function(data){
                All.set_enable_button();
				$(All.get_active_tab() + ".mainForm").hide();
                $(All.get_active_tab() + ".nextForm1").html(data);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                 alert(thrownError + ':' +xhr.status);
				 All.set_enable_button();
            }
        });
	},
}

