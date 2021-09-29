<div class="mainForm">
  <form class="form-horizontal" id="formGeneratedSales">
    <fieldset>	      
      <div class="control-group">
	    
      	<label class="control-label" for="typeahead">Periode Bonus</label>     
		  
      	<div class="controls">
				<select id="month_bns" name="month_bns" style="width:200px">
				<?php
				//$opts = 2;
                //print_r($curr_period);
				////Array of months
				
				$m = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
				$m1 = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
				$opts = 12;
				for ($i = 0; $i < $opts; $i++) {
					echo "<option value='" . $m1[$i] . "'>" . $m[$i] . "</option>\n";
				}	
				
				
				?>
			    </select>
				<input type="text" id="year_bns" name="year_bns" style="width:80px" value="<?php echo date("Y"); ?>" />
      	</div>
		 
      	<label class="control-label" for="typeahead">Tipe Transaksi</label> 
      	<div class="controls">
      		<!--<select id="sctype" tabindex="2" name="sctype" class="span4">
      			<option value="">All</option>
				<option value="1">- Stockist Sales Report (SSR) -</option>
				<option value="2">- Sub Stockist Sales Report (SSSR) -</option>
				<option value="3">- Mobile Stockist Sales Report (MSR) -</option>
      		</select>-->
			  <select class="span4 typeahead" id="searchs"  name="searchs" tabindex="3">
				<option value="ALL">ALL</option>
				<option value="MSR">MSR</option>
				<option value="SSR">SSR</option>
				<option value="SSSR">SSSR</option>
				<option value="MM">Aplikasi Member</option>
				<option value="PVR">PVR</option>
				<?php
				//if($main_stk == "BID06" || $main_stk == "IDBSS01") {
					echo "<option value=\"ECOMMERCE\">K-NET</option>";
				//}
				?>
				<!--<option value="apl">Application</option>-->
				<!--<option value="pvr">Product Voucher</option>-->
			</select>  
			<input type="hidden" id="statuses" name="statuses"  value="ALL" />
			<input type="hidden" id="idstkk" name="idstkk" style="width:150px" value="" />
      	</div><!--
		  <label class="control-label" for="typeahead">Status</label>
			<div class="controls">
				<select class="span3 typeahead" id="statuses"  name="statuses" tabindex="3">
					<option value="ALL">ALL</option>
					<option value="0">Inputed</option>
					<option value="1">Generated</option>
					<option value="2">Approved</option>

					<option value="apl">Application</option>
					<option value="pvr">Product Voucher</option>
				</select>
			</div>	-->
      	<label class="control-label" for="typeahead">Main Stockist</label>                             
        <div class="controls">
        	<input tabindex="3" <?php echo $readonly_stk; ?> type="text" id="main_stk" name="main_stk" style="width:150px" value="<?php echo $main_stk; ?>" onchange="All.getFullNameByID(this.value,'db2/get/fullnm/from/mssc/loccd','#main_stk_name')" />
			<input readonly="readonly" type="text" id="main_stk_name" name="main_stk_name" style="width:400px" />
        </div>
		<!--
		<label class="control-label" for="typeahead">Stockist</label>                             
        <div class="controls">
        	<input tabindex="3" type="text" id="idstkk" name="idstkk" style="width:150px" value="" onchange="All.getFullNameByID(this.value,'db2/get/fullnm/from/mssc/loccd','#idstkk_name')" />
			<input readonly="readonly" type="text" id="idstkk_name" name="idstkk_name" style="width:400px" />
        </div>-->
		
      	<label class="control-label" for="typeahead">Tgl Generate</label>
      		<div class="controls">
      			<input tabindex="4" type="text" class="dtpicker" id="from_stk_r_trx" name="from" placeholder="From" value="<?php echo $from; ?>" /> 
      			&nbsp;<input tabindex="5" type="text" class="dtpicker" id="to_stk_r_trx" name="to" placeholder="To" value="<?php echo $to; ?>" />
        		
     		</div>
      	<label class="control-label" for="typeahead">&nbsp;</label>                             
        <div class="controls"  id="inp_btn">
            <input tabindex="6" type="button" id="btn_input_user" class="btn btn-primary" name="save" value="Submit" onclick="All.ajaxFormPost(this.form.id,'<?php echo $form_action ?>')" />
            <input tabindex="7"  type="reset" class="btn btn-reset" value="Reset" />
            <button type="submit" class="btn btn-success"formmethod="POST" formtarget="_blank"formaction="<?php echo $exportExcell; ?>" >Excell</button>
         </div>
        </div> <!-- end control-group -->
     </fieldset>
    
    
  </form> 
  <div class="result"></div>
</div><!--/end mainForm-->
<?php setDatePicker(); ?>
