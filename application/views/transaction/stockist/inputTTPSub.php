<<<<<<< HEAD
<div class="mainForm">
  <form class="form-horizontal" id="formTrxSearchSub">
    <fieldset>      
      <div class="control-group">
      	<label class="control-label" for="typeahead">Tgl Transaksi</label>
      		<div class="controls">
      			<input type="text" class="dtpicker" id="from_sub_trx" name="from" placeholder="From" value="<?php echo $from; ?>" /> 
      			&nbsp;<input type="text" class="dtpicker" id="to_sub_trx" name="to" placeholder="To" value="<?php echo $to; ?>" />
        		
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
				<option value="batchno">No SSR / MSR</option>
				<option value="orderno">No TTP</option>
				<option value="receiptno">No KW</option>
			</select>
        </div>
        
      	<label class="control-label" for="typeahead">Parameter/Nilai</label>                             
        <div class="controls">
        	<input type="text" id="paramValue" name="paramValue" class="span4" value="" />
			<input type="hidden" id="loccd" name="loccd" class="span4" value="<?php echo $sc_dfno; ?>" />
        </div>
       	<label class="control-label" for="typeahead">&nbsp;</label>                             
        <div class="controls"  id="inp_btn">
            <input tabindex="5" type="button" id="btn_input_user" class="btn btn-success" name="save" value="Cari" onclick="All.ajaxFormPost(this.form.id,'sales/stk/input/list')" />
            <input tabindex="6"  type="reset" class="btn btn-reset" value="Reset" />
            <input tabindex="7"  type="button" class="btn btn-primary" value="Input TTP Baru" onclick="javascript:All.ajaxShowDetailonNextForm('sales/sub/input/form')" />
         </div>
        </div> <!-- end control-group -->
     </fieldset>
    
    
  </form> 
  <div class="result"></div>
</div><!--/end mainForm-->
<?php setDatePicker(); ?>
<script>
function deleteTrx(trcd) {
	//var x = All.get_active_tab();
	$.ajax({
		url: All.get_url('sales/stk/delete/trcd') +trcd ,
		type: 'GET',
		dataType: "json",
		success:
		function(data){
			if(data.response == "true") {
				alert(data.message);
				$(All.get_active_tab() + "tr#ttp-" +trcd).remove();
			} else {

			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + ':' +xhr.status);
				All.set_enable_button();
		}
    }); 
}
</script>
=======
<div class="mainForm">
  <form class="form-horizontal" id="formTrxSearchSub">
    <fieldset>      
      <div class="control-group">
      	<label class="control-label" for="typeahead">Tgl Transaksi</label>
      		<div class="controls">
      			<input type="text" class="dtpicker" id="from_sub_trx" name="from" placeholder="From" value="<?php echo $from; ?>" /> 
      			&nbsp;<input type="text" class="dtpicker" id="to_sub_trx" name="to" placeholder="To" value="<?php echo $to; ?>" />
        		
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
				<option value="batchno">No SSR / MSR</option>
				<option value="orderno">No TTP</option>
				<option value="receiptno">No KW</option>
			</select>
        </div>
        
      	<label class="control-label" for="typeahead">Parameter/Nilai</label>                             
        <div class="controls">
        	<input type="text" id="paramValue" name="paramValue" class="span4" value="" />
			<input type="hidden" id="loccd" name="loccd" class="span4" value="<?php echo $sc_dfno; ?>" />
        </div>
       	<label class="control-label" for="typeahead">&nbsp;</label>                             
        <div class="controls"  id="inp_btn">
            <input tabindex="5" type="button" id="btn_input_user" class="btn btn-success" name="save" value="Cari" onclick="All.ajaxFormPost(this.form.id,'sales/stk/input/list')" />
            <input tabindex="6"  type="reset" class="btn btn-reset" value="Reset" />
            <input tabindex="7"  type="button" class="btn btn-primary" value="Input TTP Baru" onclick="javascript:All.ajaxShowDetailonNextForm('sales/sub/input/form')" />
         </div>
        </div> <!-- end control-group -->
     </fieldset>
    
    
  </form> 
  <div class="result"></div>
</div><!--/end mainForm-->
<?php setDatePicker(); ?>
<script>
function deleteTrx(trcd) {
	//var x = All.get_active_tab();
	$.ajax({
		url: All.get_url('sales/stk/delete/trcd') +trcd ,
		type: 'GET',
		dataType: "json",
		success:
		function(data){
			if(data.response == "true") {
				alert(data.message);
				$(All.get_active_tab() + "tr#ttp-" +trcd).remove();
			} else {

			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + ':' +xhr.status);
				All.set_enable_button();
		}
    }); 
}
</script>
>>>>>>> devel
