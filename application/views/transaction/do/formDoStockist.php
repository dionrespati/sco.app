<style>
	.formSales input {
		min-height: 23px;
		margin-bottom: 1px;
	}
</style>
<div class="mainForm">
  <form class="form-horizontal" id="formTrxDo">
    <fieldset>	      
      <div class="control-group">
      	
      	<label class="control-label" for="typeahead">Tgl DO</label>
      		<div class="controls">
      			<input type="text" class="dtpicker" id="from_do_trx" name="from" placeholder="From" value="<?php echo $from; ?>" /> 
      			&nbsp;<input type="text" class="dtpicker" id="to_do_trx" name="to" placeholder="To" value="<?php echo $to; ?>" />
        		
     		</div>
			 
      	<label class="control-label" for="typeahead">Pencarian</label>                             
        <div class="controls">
        	
        	<select id="searchby" class="input" name="searchby">
				<option value="">[Pilih disini]</option>
				<option value="trcd">No Trx</option>
				<option value="orderno">No. TTP</option>
                <option value="gdo">No. DO</option>
			</select>
        </div>
        
      	<label class="control-label" for="typeahead">Value</label>                             
        <div class="controls">
		<input type="text" id="paramValue" name="paramValue" class="span4" value="" />
			<input type="hidden" id="loccd" name="loccd" class="span4" value="<?php echo $sc_dfno; ?>" />
        </div>
        <label class="control-label" for="typeahead">&nbsp;</label>                             
        <div class="controls"  id="inp_btn">
            <input tabindex="5" type="button" id="btn_input_user" class="btn btn-success" name="save" value="Search" onclick="All.ajaxFormPost(this.form.id,'do/stk/list')" />
            <input tabindex="6"  type="reset" class="btn btn-reset" value="Reset" />
            <!--<input tabindex="7"  type="button" class="btn btn-primary" value="New TTP Sales" onclick="javascript:All.ajaxShowDetailonNextForm('sales/stk/input/form')" />-->
         </div>
        </div> <!-- end control-group -->
     </fieldset>
    
    
  </form> 
  <div class="result"></div>
</div><!--/end mainForm-->
<?php setDatePicker(); ?>
