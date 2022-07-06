
<div class="mainForm">
  <form class="form-horizontal" enctype="multipart/form-data" method="post" id="recoverVcIncPay" >
    <fieldset>
      <div class="control-group">
         <label class="control-label" for="typeahead">Transaction Type</label>
         <div class="controls">
				<select id="trxtype" name="trxtype">
                    <option value="res">Invoice Reseller</option>
                    <option value="inv">Invoice Member</option>
                    <option value="stk">Stockist</option>
                </select>
		 </div>

         <label class="control-label" for="typeahead">Statement</label>
         <div class="controls">
				<select id="statement" name="statement">
                    <option value="1">ALL</option>
                    <option value="2">PAID</option>
                    <option value="3">UNPAID</option>
                </select>
		 </div>

         <label class="control-label" for="typeahead">Transaction Date </label>
				<div class="controls">
					<input type="text" class="dtpicker" id="from_cnreg" name="from" placeholder="From"
						value="" />
					&nbsp;<input type="text" class="dtpicker" id="to_cnreg" name="to" placeholder="To"
						value="" />
				</div>
		 <!-- <div class="controls" >
			<select id="trxtype" name="trxtype" class="span5 typeahead">
                <option value="registerno">Register No</option>
                <option value="ssr">SSR/MSR</option>
                <option value="csno">CN/MS</option>
                <option value="kw">KW No</option>
                <option value="vcip">Incoming Payment</option>
                <option value="vcd">Voucher Cash Deposit</option>
                <option value="rec_ssr">SSR/IPS(Recover)</option>
                <option value="orderno">Order No/TTP</option>
                <option value="trcd">Trx No / Invoice</option>
                <option value="gdo">DO No</option>
                <option value="dowms">DO WMS No</option>
			</select>
		 </div>
         <label class="control-label" for="typeahead">Parameter Value</label>
         <div class="controls" >
          	<input type="text" id="trxno" name="trxno" class="span5 typeahead" />
         </div> -->


         <label class="control-label" for="typeahead">&nbsp</label>
         <div class="controls"  id="inp_btn">
            <input type="hidden" id="trxno" name="trxno" class="span5 typeahead" value='KW2204000217' />
        	<!-- <input tabindex="5" type="button" id="btn_input_user" class="btn btn-primary .submit" name="save" value="Submit" onclick="All.ajaxFormPost(this.form.id,'payment/receipt/detail')" /> -->
            <input tabindex="5" type="button" id="btn_input_user" class="btn btn-primary .submit" name="save" value="Submit" onclick="All.ajaxFormPost(this.form.id,'payment/receipt/finddata')" />
            <input tabindex="6"  type="reset" class="btn btn-reset" value="Reset" />
			<!-- <input type="button" class="btn btn-success" onclick="get()" value="Recover" disabled> -->
         </div>

        </div> <!-- end control-group -->
     </fieldset>
  </form>
  <div id="editor"></div>
  <div class="result"></div>
</div><!--/end mainForm-->
<?php setDatePicker(); ?>
<script>
    $(document).ready(function() {

	});

	function recoverSSR(formId, urlx) {
	    console.log(formId);
		All.set_disable_button();
		//All.get_image_load();
		console.log($(All.get_active_tab() + " #"+ formId).serialize());
		console.log($(All.get_active_tab() + " #flag_recover").val());

		$.ajax({
            url: All.get_url(urlx) ,
            type: 'POST',
			data: $(All.get_active_tab() + " #"+ formId).serialize(),
            success:
            function(data){
                All.set_enable_button();
				$(All.get_active_tab() + " .result").html(null);
                $(All.get_active_tab() + " .result").html(data);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                 alert(thrownError + ':' +xhr.status);
				 All.set_enable_button();
            }
        });
	}
</script>