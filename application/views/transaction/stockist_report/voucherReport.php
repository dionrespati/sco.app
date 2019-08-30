<div class="mainForm">
  <form class="form-horizontal" enctype="multipart/form-data" id="formMemberSearch">
    <fieldset>      
      <div class="control-group">
	   <label class="control-label" for="typeahead">Kategori</label>
      	<div class="controls">
      		<select id="kategori" name="kategori">
      			<option value="vc_prd">Voucher Produk</option>
      			<option value="vc_c">Voucher Cash</option>
				<option value="vc_prm">Voucher Promo</option>
				<option value="vc_umr">Voucher Umroh</option>
      		</select>
      			
      	</div>
		<label class="listM control-label" for="typeahead">No Voucher</label>
      	<div class="listM controls">
      		<input type="text" class="TabOnEnter span4" id="voucherno" name="voucherno" />	
      	</div>
		<label class="listM control-label" for="typeahead">ID Member</label>
      	<div class="listM controls">
      		<input type="text" class="TabOnEnter span4" id="memberid" name="memberid" />	
      	</div>
		 <!-- 
      	<label class="control-label" for="typeahead">Pencarian</label>
      	<div class="controls">
      		<select id="searchBy" name="searchBy">
      			<option value="VoucherNo">No Voucher</option>
      			<option value="DistributorCode">ID Member</option>
      			<option value="vchtype">Tipe Voucher</option>
      		</select>
      			
      	</div>
      	
  		<label class="listM control-label" for="typeahead">Parameter/Nilai</label>
      	<div class="listM controls">
      		<input type="text" class="TabOnEnter span6" id="paramVchValue" name="paramVchValue" />	
      	</div>-->
      	<!--<label class="listByDate control-label" style="display: none;" for="typeahead">ID Stockist</label>
      	<div class="listByDate controls" style="display: none;">
      		<?php
      		  $str = "";
      		  if($this->stockist != "BID06") {
      		  	$str = "readonly=readonly";
			  }	
      		?>
      		<input <?php echo $str; ?> type="text" class="TabOnEnter span6" id="sc_dfno" name="sc_dfno" value="<?php echo $this->stockist; ?>" />	
      	</div>
  	    <label class="listByDate control-label" for="typeahead" style="display: none;">Date</label>
        <div class="listByDate controls" style="display: none;">
           <input type="text" class="dtpicker typeahead" id="mb_from" name="mb_from" >&nbsp;to&nbsp;
		   <input type="text"  class="dtpicker typeahead" id="mb_to" name="mb_to" >
		 </div> 
      	
      	-->
      	<label class="control-label" for="typeahead">&nbsp</label>                             
        <div class="controls"  id="inp_btn">
            <input type="button" id="btn_input_user" class="btn btn-primary .submit" name="save" value="Check" onclick="All.ajaxFormPost(this.form.id, '<?php echo $form_action; ?>')" />
            <input type="reset" class="btn btn-reset" value="Reset" />
            
         </div>
       
      </div><!-- end control-group -->
    </fieldset>
    <div class="result"></div>
  </form>   
  
</div><!--/end mainForm-->
<script>
	$(document).ready(function() { 
		$(All.get_active_tab() + " .dtpicker").datepicker({
			changeMonth: true,
			numberOfMonths: 1,
			dateFormat: 'yy-mm-dd',
		}).datepicker();
		
		
	});	
</script>