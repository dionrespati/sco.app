<style>
	.formSales input {
		min-height: 23px;
		margin-bottom: 1px;
	}
</style>
<div class="mainForm">
	<form class="form-horizontal" id="formTrxRegister">
		<fieldset>
			<div class="control-group">
				<label class="control-label" for="typeahead">Tgl Register</label>
				<div class="controls">
					<input type="text" class="dtpicker" id="from_cnreg" name="from" placeholder="From"
						value="<?php echo $from; ?>" />
					&nbsp;<input type="text" class="dtpicker" id="to_cnreg" name="to" placeholder="To"
						value="<?php echo $to; ?>" />
				</div>
				<label class="control-label" for="typeahead">Pencarian</label>
				<div class="controls">
					<select id="searchby" class="input" name="searchby">
						<option value="">--Pilih disini--</option>
						<option value="registerno">No Register</option>
            <option value="invoiceno">No CN/MS</option>
						<option value="receiptno">No KW</option>
						<option value="batchscno">No SSR</option>
						<option value="loccd">Kode Stockist</option>
					</select>
				</div>
        <label class="control-label" for="typeahead">Tipe</label>
				<div class="controls">
					<select id="online" class="input" name="online">
						<option value="">--Pilih disini--</option>
						<option value="ol">Trx Online (K-net/Kmart)</option>
            <option value="stk" selected="selected">Trx Stockist</option>
					</select>
				</div>

				<label class="control-label" for="typeahead">Value</label>
				<div class="controls">
					<input type="text" id="paramValue" name="paramValue" class="span4" value="" />
				</div>
				<label class="control-label" for="typeahead">&nbsp;</label>
				<div class="controls" id="inp_btn">
					<input tabindex="5" type="button" id="btn_input_user" class="btn btn-success" name="save" value="Cari"
						onclick="All.ajaxFormPost(this.form.id,'bo/cnmsn/list')" />
					<input tabindex="6" type="reset" class="btn btn-reset" value="Reset" />
					<input tabindex="7" type="button" class="btn btn-primary" value="New Register"
						onclick="javascript:All.ajaxShowDetailonNextForm('bo/cnmsn/newregister')" />
				</div>
			</div> <!-- end control-group -->
		</fieldset>


	</form>
	<div class="result"></div>
</div>
<!--/end mainForm-->
<?php setDatePicker(); ?>
<script>
	function saveRegister() {
    All.set_disable_button();
		$.post(All.get_url('bo/cnmsn/register/save') , $(All.get_active_tab() + "#formSalesCN").serialize(), function(data)
    {
      All.set_enable_button();
      const {response, arrayData, message} = data;
      alert(message);
      console.log({response, arrayData, message});
      if (response == "true") {
        const noregister = arrayData.registerno;
        const urlUpdInv = "bo/cnmsn/updateInv/" + noregister;
        All.ajaxShowDetailonNextForm(urlUpdInv);
      } 

    },"json").fail(function() {
        alert("Error requesting page");
        All.set_enable_button();
    });
  }
</script>
