<div class="mainForm">
	<form class="form-horizontal" id="formTrxSearchSub" action="sales/report/export" method="post">
		<fieldset>
			<div class="control-group">
				<label class="control-label" for="typeahead">Tgl Transaksi</label>
				<div class="controls">
						<input type="text" class="dtpicker" id="from_sub_trx" name="from" placeholder="From"
								value="<?php echo $from; ?>" />
						&nbsp;<input type="text" class="dtpicker" id="to_sub_trx" name="to" placeholder="To"
								value="<?php echo $to; ?>" />
				</div>
				<label class="control-label" for="typeahead">Status Transaksi</label>
				<div class="controls">
						<select id="flag_batch" class="input" name="flag_batch">
								<option value="0" selected="selected">Belum di generate</option>
								<option value="1">Sudah di generate</option>
								<option value="2">Sudah di Approve</option>
						</select>
				</div>
				<label class="control-label" for="typeahead">Pencarian</label>
				<div class="controls">
						<select id="searchby" class="input" name="searchby">
								<option selected="selected" value="">[Select One]</option>
								<option value="trcd">No Trx</option>
								<option value="sc_dfno">Kode Stockist</option>
								<option value="dfno">ID Member</option>
								<option value="batchno">No SSR / MSR</option>
								<option value="orderno">No TTP</option>
								<option value="receiptno">No KW</option>
								<option value="prdcd">Kode Produk</option>
								<option value="prdnm">Nama Produk</option>
						</select>
				</div>
				<label class="control-label" for="typeahead">Parameter/Nilai</label>
				<div class="controls">
						<input type="text" id="paramValue" name="paramValue" class="span4" value="" />
						<input type="hidden" id="loccd" name="loccd" class="span4" value="<?php echo $sc_dfno; ?>" />
				</div>
				<label class="control-label" for="typeahead">&nbsp;</label>
				<div class="controls" id="inp_btn">
					<input tabindex="5" type="button" id="btn_input_user" class="btn btn-success" name="save"
								value="Cari" onclick="All.ajaxFormPost(this.form.id,'sales/stk/input/list')" />
					<?php if($username == "BID06") { ?>
						Pilih Jenis Inputan :&nbsp;
					<select id="tipe_btn_input" class="input" name="tipe_btn_input" id="tipe_btn_input">
							<!--<option selected="selected" value="">--Pilih Disini--</option>-->
							<option selected="selected" value="ttp">Input TTP</option>
							<option value="vc">Input Voucher Cash</option>
							<option value="promo">Input TTP Produk Promo</option>
					</select>
					<input type="button" value="Pilih" class="btn btn-mini btn-primary" onclick="pilihJenisInputan()" />
					<?php } else { ?>
					<!--<input tabindex="6" type="reset" class="btn btn-reset" value="Reset" />-->
					
					<input tabindex="7" type="button" class="btn btn-primary" value="Input TTP Baru"
							onclick="javascript:All.ajaxShowDetailonNextForm('sales/sub/input/form')" />
					<!-- <input tabindex="7" type="button" class="btn btn-primary" value="Input Voucher Cash"
							onclick="javascript:All.ajaxShowDetailonNextForm('sales/sub/input/vcash')" /> -->
					<?php } ?>	
						<!--<input tabindex="5" type="button" id="btn_input_user" class="btn btn-success" name="report" value="Report Excel" onclick="All.ajaxFormPost(this.form.id,'sales/stk/input/report')" />-->
				</div>
				<div class="controls" id="inp_btn">
				</div>
			</div> <!-- end control-group -->
		</fieldset>
		<div class="result"></div>
	</form>
</div>
<!--/end mainForm-->
<?php setDatePicker(); ?>
<script>
	var total_prd_dihitung = 0;
	var cur_klaim = 0;
	var sisa_klaim = 0;
	var prdTerpilih = [];

	function pilihJenisInputan() {
		var inp_type = $(All.get_active_tab() + " #tipe_btn_input").val();
		console.log({inp_type});
		if(inp_type === "ttp") {
			All.ajaxShowDetailonNextForm('sales/sub/input/form');
		} else if(inp_type === "vc") {
			All.ajaxShowDetailonNextForm('sales/sub/input/vcash');
		} else if(inp_type === "promo") {
			All.ajaxShowDetailonNextForm('sales/input/promo');
		}	

	}

	function deleteTrx(trcd) {
		//var x = All.get_active_tab();
		$.ajax({
			url: All.get_url('sales/stk/delete/trcd') + trcd,
			type: 'GET',
			dataType: "json",
			success: function (data) {
				if (data.response == "true") {
					alert(data.message);
					$(All.get_active_tab() + "tr#ttp-" + trcd).remove();
				} else {

				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + ':' + xhr.status);
				All.set_enable_button();
			}
		});
	}

	function kembaliKeForm() {
		$(All.get_active_tab() + " #firstForm").css("display", "block");
		$(All.get_active_tab() + " #secondForm").css("display", "none");
		$(All.get_active_tab() + " #secondForm").html(null);
	}

	function pilihProdukPromo() {
		cur_klaim = 0;
		sisa_klaim = 0;
		prdTerpilih = [];
		$(All.get_active_tab() + " #firstForm").css("display", "none");
		$(All.get_active_tab() + " #secondForm").css("display", "block");
		var nilaiPrd = 0;
		$(All.get_active_tab() + " input[attr=jqty]").each(function (){
			nprd = $(this).val();
			console.log({nprd});

			if (isNaN(nprd)) {
				nprd = 0;
				nilaiPrd += nprd;
			} else {
				nilaiPrd += parseInt(nprd);
			}

		});

		total_prd_dihitung = nilaiPrd;
		console.log({total_prd_dihitung, cur_klaim, sisa_klaim, prdTerpilih});

		var jenis_promo = $(All.get_active_tab() + " #jenis_promo").val();

		$.ajax({
				url: All.get_url("sales/promo/check"),
				type: 'POST',
				data: {qty: nilaiPrd, jenis_promo: jenis_promo},
				success: function (data) {
					$("#secondForm").html(null);
					$("#secondForm").html(data);
				},
				error: function (xhr, ajaxOptions, thrownError) {
						alert(thrownError + ':' + xhr.status);
				}
		});

	}

	function tambahPrd(param) {
		var free_prd = $(All.get_active_tab() + " #free_prd" + param).val();
        console.log({
            free_prd
        });
        if (free_prd === "" || free_prd === null) {
            alert("Pilih Free Produk dahulu sebelum ditambah..");
            return;
        }
        var slicing = free_prd.split("|");
        var prdcd = slicing[0];
        var skema = parseInt(slicing[1]);
        var prdnm = slicing[3];

        var qtyx = parseInt($(All.get_active_tab() + " #qtyx" + param).val());
        var klaim = (skema * qtyx);
        var temp_cur_klaim = cur_klaim + klaim;
        var temp_sisa_klaim = total_prd_dihitung - temp_cur_klaim;
				var jenis_promo = $(All.get_active_tab() + " #jenis_promo").val();
        if (temp_sisa_klaim < 0) {
            alert("Kuota Habis..");
        } else {
					cur_klaim = temp_cur_klaim;
					sisa_klaim = temp_sisa_klaim;
					prdTerpilih.push(prdcd);

					console.log({
							total_prd_dihitung,
							sisa_klaim,
							cur_klaim,
							klaim
					});

					var xhtml = "<tr>";
					xhtml += "<td>" + prdcd + "<input style='width:70px;' type='hidden' readonly='readonly' name='kode_produk[]' value='" + prdcd + "' /></td>";
					xhtml += "<td>" + prdnm + "<input style='width:200px;' type='hidden' readonly='readonly' name='nama_produk[]' value='" + prdnm + "' /></td>";
					xhtml += "<td>" + qtyx + "<input style='width:30px;' type='hidden' readonly='readonly' name='qty_produk[]' value='" + qtyx + "' />";
					xhtml += "<input style='width:30px;' type='hidden' readonly='readonly' name='skema[]' value='" + skema + "' />";
					xhtml += "</td>";
					xhtml += "</tr>";
					$(All.get_active_tab() + " #pilihProduk").append(xhtml);

					$(All.get_active_tab() + " input[type=button]").attr("disabled", "disabled");
					//console.log(get_urlx);
					$.ajax({
						url: All.get_url('sales/promo/checksisa'),
						type: 'POST',
						dataType: 'json',
						data: {
							sisa_klaim: sisa_klaim,
							excludePrd: prdTerpilih,
							jenis_promo: jenis_promo
						},
						success: function (data) {
							$(All.get_active_tab() + " input[type=button]").removeAttr("disabled");
							if (data.response == "true") {
								renderProduk(data.arrayData);
							} else {
								$(All.get_active_tab() + " #showPrd").html(null);
							}
						},
						error: function (xhr, ajaxOptions, thrownError) {
								alert(thrownError + ':' + xhr.status);
								$(All.get_active_tab() + " input[type=button]").removeAttr("disabled");
						}
					});
			}
	}

	function renderProduk(datax) {
        var resp = "";
        $(All.get_active_tab() + " #showPrd").html(null);
        var urut = 1;
        $.each(datax, function (key, value) {
            resp += "<tr>";
            resp += "<td>Free Produk " + value.skema + "</td>";
            resp += "<td><select style='width: 400px;' data-live-search='true' name='free_prd[]' id=free_prd" + (urut) + ">";
            $.each(value.listPrd, function (i, x) {
                var skm = x.skema;
                var kodeprd = x.prdcd + "|" + skm + "|" + x.max_qty + "|" + x.prdnm;
                resp += "<option value='" + kodeprd + "'>" + x.prdnm + "</option>";
            });
            resp += "</select></td>";
            resp += "<td>&nbsp;Qty :&nbsp;</td>";
            resp += "<td><select id=qtyx" + (urut) + " style='width:50px;' name='qty[]'>";
            var max = value.max_qty;
            for (var i = max; i >= 1; i--) {
                resp += "<option value='" + i + "'>" + i + "</option>";
            }
            resp += "</select>&nbsp;<input type='hidden' id=max_qty" + (urut) + " value='" + max + "'/><input type='hidden' id=cur_qty" + (urut) + " value='0'/>";
            resp += "<button type='button' id=btnTmbh" + (urut) + " class='btn btn-mini btn-success' onclick='tambahPrd(" + urut + ")'>Tambah</button>";

            resp += "</tr>";
            urut++;
        });
        $(All.get_active_tab() + " #showPrd").append(resp);
    }

		function ulangiInput() {
        //var input_total = parseInt($(All.get_active_tab() + " #input_total").val());
        //console.log({input_total});
        cur_klaim = 0;
        sisa_klaim = 0;
        prdTerpilih = [];
				var jenis_promo = $(All.get_active_tab() + " #jenis_promo").val();

        $(All.get_active_tab() + " input[type=button]").attr("disabled", "disabled");
        $.ajax({
            url: All.get_url('sales/promo/resetpromo') ,
            type: 'POST',
            dataType:'json',
            data: {sisa_klaim: total_prd_dihitung, jenis_promo: jenis_promo},
            success: function(data){
							$(All.get_active_tab() + " input[type=button]").removeAttr("disabled");
							if(data.response == "true") {
								$(All.get_active_tab() + " #showPrd").html(null);
								$(All.get_active_tab() + " #pilihProduk").html(null);
								renderProduk(data.arrayData);
							} else {

							}
            },
            error: function (xhr, ajaxOptions, thrownError) {
							alert(thrownError + ':' +xhr.status);
							$("input[type=button]").removeAttr("disabled");
            }
        });
    }
</script>
