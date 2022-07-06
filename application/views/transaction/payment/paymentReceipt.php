<style>
	.formSales input {
		min-height: 23px;
		margin-bottom: 1px;
	}
</style>
<div class="mainForm">
	<form class="form-horizontal" id="formPaymentReceipt">
		<fieldset>
			<div class="control-group">
				<!-- <label class="control-label" for="typeahead">Tgl Transaksi</label>
				<div class="controls">
					<input type="text" class="dtpicker" id="from_pay_trx" name="from" placeholder="From"
						value="<?php echo $from; ?>" />
					&nbsp;<input type="text" class="dtpicker" id="to_pay_trx" name="to" placeholder="To"
						value="<?php echo $to; ?>" />
				</div> -->
        <label class="control-label" for="typeahead">Tipe Transaksi</label>
				<div class="controls">
					<select id="tipe_trx" name="tipe_trx">
            <option value="1">Invoice Reseller</option>
            <option value="2">Invoice Member</option>
            <option value="3">Stockist</option>
          </select>
				</div>
				<!-- <label class="control-label" for="typeahead">Pencarian</label>
				<div class="controls">
					<select id="searchby" class="input" name="searchby">
						<option value="">--Pilih disini--</option>
						<option value="registerno">No Register</option>
						<option value="sc_dfno">ID Member / Stockist</option>
						<option value="kode_reseller">Kode Reseller</option>
					</select>
				</div> -->

				<label class="control-label" for="typeahead">No Register</label>
				<div class="controls">
					<input type="text" id="paramValue" name="paramValue" class="span4" value="" />
					<input type="hidden" id="registerno" name="registerno" class="span4" value="" />
				</div>
				<label class="control-label" for="typeahead">&nbsp;</label>
				<div class="controls" id="inp_btn">
					<input tabindex="5" type="button" id="btn_input_user" class="btn btn-success" name="save" value="Search"
						onclick="All.ajaxFormPost(this.form.id,'payment/receipt/findregister')" />
					<input tabindex="6" type="reset" class="btn btn-reset" value="Reset" />
					
				</div>
			</div> <!-- end control-group -->
		</fieldset>


	</form>
	<div class="result"></div>
</div>
<!--/end mainForm-->
<?php setDatePicker(); ?>
