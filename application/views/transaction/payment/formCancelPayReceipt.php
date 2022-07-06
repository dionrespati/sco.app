<style>
	.formSales input {
		min-height: 23px;
		margin-bottom: 1px;
	}
</style>
<div class="mainForm">
	<form class="form-horizontal" id="formCancelPayReceipt">
		<fieldset>
			<div class="control-group">
			
				<label class="control-label" for="typeahead">No KW</label>
				<div class="controls">
					<input type="text" id="no_kw2" name="no_kw" class="span4" value="" />
				</div>
				<label class="control-label" for="typeahead">&nbsp;</label>
				<div class="controls" id="inp_btn">
					<input tabindex="5" type="button" id="btn_input_user" class="btn btn-primary" name="save" value="Submit"
						onclick="All.ajaxFormPost(this.form.id,'payment/receipt/cancel/save')" />
					<input tabindex="6" type="reset" class="btn btn-reset" value="Reset" />
					
				</div>
			</div> <!-- end control-group -->
		</fieldset>


	</form>
	<div class="result"></div>
</div>
<!--/end mainForm-->
<?php setDatePicker(); ?>
