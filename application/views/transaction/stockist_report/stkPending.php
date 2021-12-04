<?php
$stk = $this -> session -> userdata("stockist");
$stkname = $this -> session -> userdata("stockistnm");
//print_r($curr_period);
?>
<div class="mainForm">
	<form class="form-horizontal" enctype="multipart/form-data" id="formPendingStk">
		<fieldset>
			<div class="control-group">
				<label class="control-label" for="typeahead">Tgl Generate SSR/MSR</label>
				<div class="controls">
					<input type="text" tabindex="1" class="dtpicker" id="btfrom" name="from" placeholder="Date From" value="<?php  echo $from; ?>" />
					 s/d
					<input type="text" tabindex="2" class="dtpicker" id="btto" name="to" placeholder="Date To" value="<?php  echo $to; ?>" />
					<!--<input type="button" tabindex="6" class="btn btn-primary" onclick="All.ajaxFormPost(this.form.id,'sales/search/list')" name="submit" value="Cari"/>-->
				</div>
        <label class="control-label" for="typeahead">Bonus Period</label>
        <div class="controls">
          <input type="text" tabindex="3" id="bns" name="bns" value="<?php echo $bns; ?>" />
        </div>
				<label class="control-label" for="typeahead">&nbsp;</label>                             
				<div class="controls"  id="inp_btn">
					<input tabindex="6" type="button" id="btn_input_user" class="btn btn-primary" name="save" value="Submit" onclick="All.ajaxFormPost(this.form.id,'sales/pending/report/list')" />
					<!--<button type="submit" class="btn btn-success"formmethod="POST" formtarget="_blank"formaction="<?php echo base_url('sales/report/product/excell'); ?>" >Ekspor ke Excell</button>-->
				</div>
				</div>
			<!-- end control-group -->
          </div><!-- end control-group -->
		</fieldset>
	  </form>
	<div class="result"></div>
</div><!--/end mainForm-->
<?php setDatePicker(); ?>
