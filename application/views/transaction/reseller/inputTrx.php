<style>
	.formSales input {
		min-height: 23px;
		margin-bottom: 1px;
	}
</style>
<div class="mainForm">
	<form class="form-horizontal" id="formTrxSubSearch">
		<fieldset>
			<div class="control-group">
				<label class="control-label" for="typeahead">Tgl Transaksi</label>
				<div class="controls">
					<input type="text" class="dtpicker" id="from_stk_trx" name="from" placeholder="From"
						value="<?php echo $from; ?>" />
					&nbsp;<input type="text" class="dtpicker" id="to_stk_trx" name="to" placeholder="To"
						value="<?php echo $to; ?>" />
				</div>
				<label class="control-label" for="typeahead">Pencarian</label>
				<div class="controls">
					<select id="searchby" class="input" name="searchby">
						<option value="">--Pilih disini--</option>
						<option value="registerno">No Register</option>
						<option value="sc_dfno">No Invoice</option>
						<option value="kode_reseller">Kode Reseller</option>
						<option value="dfno">ID Member</option>
					</select>
				</div>

				<label class="control-label" for="typeahead">Value</label>
				<div class="controls">
					<input type="text" id="paramValue" name="paramValue" class="span4" value="" />
					<input type="hidden" id="loccd" name="loccd" class="span4" value="<?php echo $sc_dfno; ?>" />
				</div>
				<label class="control-label" for="typeahead">&nbsp;</label>
				<div class="controls" id="inp_btn">
					<input tabindex="5" type="button" id="btn_input_user" class="btn btn-success" name="save" value="Search"
						onclick="All.ajaxFormPost(this.form.id,'reseller/search')" />
					<input tabindex="6" type="reset" class="btn btn-reset" value="Reset" />
					<input tabindex="7" type="button" class="btn btn-primary" value="New Register"
						onclick="javascript:All.ajaxShowDetailonNextForm('reseller/newregister')" />
				</div>
			</div> <!-- end control-group -->
		</fieldset>


	</form>
	<div class="result"></div>
</div>
<!--/end mainForm-->
<?php setDatePicker(); ?>
