<div class="mainForm">
	<form class="form-horizontal" enctype="multipart/form-data" id="formSkPromo">
		<fieldset>
			<div class="control-group">
				
				<label class="control-label" for="typeahead">ID Member</label>
				<div class="controls">
					<input type="text" class="TabOnEnter" style="width: 250px;" id="dfno" name="dfno" onchange="checkIDMember(this.value)" />
                    <input type="button" class="btn btn-mini btn-primary" value="Check ID" onclick="checkIDMember(this.value)" />
				</div>
                <label class="control-label" for="typeahead">Nama Member</label>
				<div class="controls">
                <input type="text" placeholder="Nama Member" readonly="readonly" class="TabOnEnter" style="width: 450px;" id="fullnm" name="fullnm" />
                    
				</div>
                
				<label class="control-label" for="typeahead">No HP</label>
				<div class="controls">
                    <input type="text" style="width: 250px;" id="no_hp" name="no_hp" />
				</div>
				<label class="control-label" for="typeahead">Pilih Jenis Voucher SK</label>
				<div class="controls">
					<select id="product" name="product" style="width:450px">
                        <?php 
                        if($listSK !== null) {
                            foreach($listSK as $dta) {
                                echo "<option value=\"$dta->prdcd\">$dta->prdnm, Harga $dta->dp</option>";
                            }
                        }
                        ?>
					</select>
				</div>
                <label class="control-label" for="typeahead">Qty</label>
				<div class="controls">
					<input type="text" style="width:40px;" id="qty" name="qty" />
                    <input type="button" class="btn btn-mini btn-primary" value="Tambah" onclick="addRow()" />
                    <input type="submit" formmethod="POST" formaction="<?php echo base_url().$form_action ?>" formtarget="_BLANK" class="btn btn-mini btn-primary" value="Simpan Transaksi" onclick="simpan()" />
				</div>
				<!-- <label class="control-label" for="typeahead">&nbsp</label>
				<div class="controls"  id="inp_btn">
					<input type="button" id="btn_input_user" class="btn btn-primary .submit" name="save" value="Submit" onclick="All.ajaxFormPost(this.form.id, '<?php echo $form_action; ?>')" />
					<input type="reset" class="btn btn-reset" value="Reset" />
				</div> -->
			</div>
		</fieldset>
		<div class="result">
            <table width="100%" class="table table-striped table-bordered">
               <thead>
                <tr>
                   <th colspan="6">Data Pembelian Starterkit</th>     
                </tr>
                <tr>
                   <th width="15%">Kode Produk</th>     
                   <th>Nama</th>
                   <th width="10%">Qty</th>
                   <th width="15%">Harga</th>
                   <th width="15%">Sub Total</th>
                   <th width="5%">Act</th>
                </tr>  
                </thead>
                <tbody id="addPrdSK">
                
                </tbody>       
            </table>                        
        </div>
	</form>
</div>
<script>
    function checkIDMember(param) {
        All.set_disable_button();
		$.ajax({
            url: All.get_url('member/checkID/') +param,
            type: 'GET',
            dataType: 'json',
            success:
            function(data){
                All.set_enable_button();
            	if(data.response == "true") {
                    var arrayData = data.arrayData;
                    //console.log(arrayData);
                    $(All.get_active_tab() + " #fullnm").val(arrayData[0].fullnm);
                    $(All.get_active_tab() + " #no_hp").val(arrayData[0].tel_hp);
                } else {
                    console.log("SDSD");
                    alert(data.message);
                }
            },
		    error: function(jqXHR, textStatus, errorThrown) {
		       All.set_enable_button();
		    }
        });
    }

    function addRow() { 
        var product = $(All.get_active_tab() + " #product").val();
        var qty = $(All.get_active_tab() + " #qty").val();

        All.set_disable_button();
		$.ajax({
            url: All.get_url('member/checkProduct'),
            type: 'POST',
            dataType: 'json',
            data: {product: product, qty: qty},
            success:
            function(data){
                All.set_enable_button();
            	if(data.response == "true") {
                    var arrayData = data.arrayData;
                    var rowPrd = "";
                    var prdx = 1;
                    var prd_subtotaldp = 0;
                    $.each(arrayData,function(key, value) {
                        rowPrd += "<tr>";
                        rowPrd += "<td><input id=prdcd"+prdx+" readonly=readonly  type='text' class='span12 typeahead' name=prdcd[] value='"+value.prdcd+"'/></td>";
                        rowPrd += "<td><input id=prdnm"+prdx+" readonly=readonly type='text' class='span12 typeahead' name=prdnm[] value='"+value.prdnm+"'/></td>";
                        rowPrd += "<td><input id=jum"+prdx+" readonly=readonly style='text-align:right;' type='text' class='span12 typeahead jumlah' name=jum[] value='"+value.qty+"' /></td>";
                        rowPrd += "<td><input id=harga"+prdx+" readonly=readonly style='text-align:right' type='text' class='span12 typeahead jumlah' name=harga[] value='"+parseInt(value.dp)+"' /></td>";
                        rowPrd += "<td><input id=sub_harga"+prdx+" readonly=readonly style='text-align:right' type='text' class='span12 typeahead jumlah' name=sub_harga[] value='"+parseInt(value.total_dp)+"' /></td>";
                        rowPrd += "</td>";
                        rowPrd += "<td align=center><a class='btn btn-mini btn-danger'><i class='icon-trash icon-white'></i></a></td>";
                        rowPrd += "</tr>";
                        prd_subtotaldp += value.total_dp;
                        
                        prdx++;
                    });
                
                    $(All.get_active_tab() + " #addPrdSK").append(rowPrd);
                } else {
                    //console.log("SDSD");
                    alert(data.message);
                }
            },
		    error: function(jqXHR, textStatus, errorThrown) {
		       All.set_enable_button();
		    }
        });
    }

    function simpan() {
        document.getElementById("formSkPromo").submit(); 
    }
</script>