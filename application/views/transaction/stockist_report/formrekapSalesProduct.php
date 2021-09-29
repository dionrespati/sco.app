<?php
$stk = $this -> session -> userdata("stockist");
$stkname = $this -> session -> userdata("stockistnm");
//print_r($curr_period);
?>
<div class="mainForm">
	<form class="form-horizontal" enctype="multipart/form-data" id="formRekapProduct">
		<fieldset>
			<div class="control-group">
				<?php
				if($stk == "BID06") {
				?>
				<label class="control-label" for="typeahead">Main Stockist</label>
				<div class="controls">
					<input <?php echo $mainstk_read; ?> type="text" name="mainstk" id="mainstk" value="<?php echo $stk; ?>" onchange="All.getFullNameByID(this.value,'db2/get/fullnm/from/mssc/loccd','#mainstknm')" />
					<input type="text" class="fullnm_width" readonly="yes" name="mainstknm" id="mainstknm" value="<?php echo $stkname; ?>" />
					<input type="hidden" id="idstkk" name="idstkk" placeholder="ID Stockist" class="" value="" />
				</div>
				<div class="clearfix"></div>
				<?php
		        } else {
				?>
				<input <?php echo $mainstk_read; ?> type="hidden" name="mainstk" id="mainstk" value="<?php echo $stk; ?>" onchange="All.getFullNameByID(this.value,'db2/get/fullnm/from/mssc/loccd','#mainstknm')" />
			    <input type="hidden" class="fullnm_width" readonly="yes" name="mainstknm" id="mainstknm" value="<?php echo $stkname; ?>" />
				<input type="hidden" id="idstkk" name="idstkk" placeholder="ID Stockist" class="" value="" />
				<?php
				}
				?>
				<!--
				<div class="clearfix"></div>
				<label class="control-label" for="typeahead">Periode Bonus</label>
				<div class="controls">
					<input type="text" id="monthperiod" name="monthperiod" value="<?php echo date("m") ?>" />
					<input type="text" id="yearperiod" name="yearperiod" value="<?php echo date("Y") ?>" />
				</div>
				-->
				<div class="clearfix"></div>
				<label class="control-label" for="typeahead">Tipe Transaksi</label>
				<div class="controls">
					<select class="span4 typeahead" id="searchs"  name="searchs" tabindex="3" onchange="pilihStkSsr()">
						<!--<option value="all">Semua Transaksi (Termasuk K-Net)</option>-->
						<option value="allwk">Semua Transaksi (Tanpa K-Net)</option>
						<option value="knet">Semua Transaksi dari K-Net</option>
						<option value="apl">Aplikasi Member</option>
						<option value="ms">MSR - Mobile Stockist Sales Report</option>
						<option value="stock">SSR - Stockist Sales Report</option>
						<option value="sub">SSSR - Sub Stockist Sales Report</option>
						<option value="pvr">PVR - Product Voucher</option>
						<!--<option value="apl">Application</option>-->
						<!--<option value="pvr">Product Voucher</option>-->
					</select>
				</div>
				<div class="clearfix"></div>
				<label class="control-label" for="typeahead">Tgl Generate SSR/MSR</label>
				<div class="controls">
					<input type="text" tabindex="4" class="dtpicker" id="btfrom" name="from" placeholder="Date From" value="<?php  echo $btfrom; ?>" onchange="pilihStkSsr()" />
					 s/d
					<input type="text" tabindex="5" class="dtpicker" id="btto" name="to" placeholder="Date To" value="<?php  echo $btto; ?>" onchange="pilihStkSsr()" />
					<!--<input type="button" tabindex="6" class="btn btn-primary" onclick="All.ajaxFormPost(this.form.id,'sales/search/list')" name="submit" value="Cari"/>-->
				</div>
				<label class="control-label" for="typeahead">Kode Stockist/SSR</label>
				<div class="controls">
					<select id="stkssr" name="stkssr" style="width:150px;" onchange="pilihStkSsr()">
					   <option value="">--Pilih--</option>
					   <option value="stk">Kode Stockist</option>
					   <option value="ssr">SSR/SSSR/MSR</option>
					</select>
					<select id="parValue" name="parValue" style="width:450px;">
						<option value="">--Pilih--</option>
					</select>
				</div>
				<label class="control-label" for="typeahead">Bundling</label>
				<div class="controls">
					<select id="bundling" name="bundling" style="width:250px;">
					   <option value="0">Tampilkan Produk Bundling</option>
					   <option value="1">Break Down Produk Bundling</option>
					</select>
				</div>
				<label class="control-label" for="typeahead">&nbsp;</label>                             
				<div class="controls"  id="inp_btn">
					<input tabindex="6" type="button" id="btn_input_user" class="btn btn-primary" name="save" value="Submit" onclick="All.ajaxFormPost(this.form.id,'sales/report/product/list')" />
					<button type="submit" class="btn btn-success"formmethod="POST" formtarget="_blank"formaction="<?php echo base_url('sales/report/product/excell'); ?>" >Ekspor ke Excell</button>
				</div>
				</div>
			<!-- end control-group -->
          </div><!-- end control-group -->
		</fieldset>
	  </form>
	<div class="result"></div>
</div><!--/end mainForm-->
<?php setDatePicker(); ?>
<script>
function pilihStkSsr() {
	var mainstk = $(All.get_active_tab() + " #mainstk").val();
	var stkssr = $(All.get_active_tab() + " #stkssr").val();
	var btfrom = $(All.get_active_tab() + " #btfrom").val();
	var btto = $(All.get_active_tab() + " #btto").val();
	var searchs = $(All.get_active_tab() + " #searchs").val();

	if(stkssr !== null && stkssr !== "" && btfrom !== "" && btfrom !== "" && 
	    btto !== "" && btto !== "" && searchs !== "" && searchs !== null && 
		mainstk !== "" && mainstk !== null) {
		$.ajax({
				url: All.get_url('sales/report/stkssr') ,
				type: 'POST',
				dataType: 'json',
				data: {from:btfrom, to:btto, tipe:stkssr, mainstk:mainstk, tipessr: searchs},
				success:
				function(data){
					//alert(data.message);
					
					 if(data.response == "true") {
						var arraydata = data.arrayData;
						var htmlx = "";
						//console.log(arraydata);
						$(All.get_active_tab() + " #parValue").html(null);
						$.each(arraydata,function(key, value) {
							htmlx += "<option value='"+value.select_key+"'>"+value.select_value+"</option>";
							//Sconsole.log("osos " +value.select_value); 
						});
						//console.log("osos " +htmlx); 
						$(All.get_active_tab() + " #parValue").html(htmlx);
					} else {
						$(All.get_active_tab() + " #parValue").html(null);
					} 

					
				},
				error: function (xhr, ajaxOptions, thrownError) {
					 alert(thrownError + ':' +xhr.status);
					 All.set_enable_button();
				}
		});
	} else {
		$(All.get_active_tab() + " #parValue").html(null);
	}
}
</script>