<form class="form-horizontal" id="formSaveIncPay" action="sales/report/export" method="post">
	<fieldset>
		<div class="control-group">
			<label class="control-label" for="typeahead">Bank</label>
			<div class="controls">
				<select id="kodebank" style="width:300px;" name="kodebank">
				<?php
          if($listBank !== null) {
            foreach($listBank as $dtabank) {
              echo "<option value=\"$dtabank->bankacccd\">$dtabank->bankdesc</option>";
            }
          }
        ?>
				</select>
			</div>
      <label class="control-label" for="typeahead">No Manual/Referensi</label>
      <div class="controls">
        <input type="text" id="inc_refno" name="inc_refno" class="span3">
			</div>
      <label class="control-label" for="typeahead">Amount</label>
      <div class="controls">
        <input type="text" id="amount" name="amount" class="span3">
			</div>
      <label class="control-label" for="typeahead">Remarks</label>
      <div class="controls">
        <input type="text" id="inc_remark" name="inc_remark" class="span8">
			</div>
      <label class="control-label" for="typeahead">Tgl Mutasi Rek</label>
      <div class="controls">
          <input type="text" class="dtpicker" id="mut_from" name="tgl_mutasi" placeholder="From"
              value="<?php echo $from; ?>" />
      </div>
      <label class="control-label" for="typeahead">Tgl Input</label>
      <div class="controls">
          <input type="text" class="dtpicker" id="inp_from" name="tgl_input" placeholder="From"
              value="<?php echo $from; ?>" />
      </div>
      <label class="control-label" for="typeahead">Status Incoming</label>
      <div class="controls">
				<select id="inc_status" style="width:300px;" name="inc_status">
					<option value="O">Open</option>
          <option value="H">Hold</option>
				</select>
			</div>
      <label class="control-label" for="typeahead">Customer Type</label>
      <div class="controls">
				<select id="customer_type" style="width:300px;" name="customer_type">
					<option value="S">Stockist</option>
          <option value="M">Member</option>
          <option value="O">Other</option>
				</select>
			</div>
      <label class="control-label" for="typeahead">ID Member / Stockist</label>
      <div class="controls">
				<input type="text" id="dfno" style="width:250px;" name="dfno" onchange="getFullName()">
        <input type="button" id="findFn" class="btn btn-mini btn-primary" onclick="getFullName()" value="Cari">
			</div>
      <label class="control-label" for="typeahead">Cust Name</label>
      <div class="controls">
        <input type="text" id="inc_fullnm" name="inc_fullnm" class="span6" readonly="readonly">
			</div>
      
      
      <label class="control-label" for="typeahead">&nbsp;</label>
      <div class="controls">
        <input tabindex="5" type="button" id="btn_input_user" class="btn btn-warning" name="back" value="<< Kembali" onclick="All.back_to_form(' .nextForm1',' .mainForm')" />
        <input tabindex="7" type="button" class="btn btn-primary" value="Simpan" onclick="simpanIncoming()" />      
      </div>
	</fieldset>
  <div class="hasil2"></div>
</form>
<?php setDatePicker(); ?>
<script>
  function getFullName() {
    let customer_type = $(All.get_active_tab() + " #customer_type").val();
    let dfno = $(All.get_active_tab() + " #dfno").val().toUpperCase();
    if(customer_type === "M") {
      All.getFullNameByID(dfno,'api/member/check','#inc_fullnm');
    } else if(customer_type === "S") {
      All.getFullNameByID(dfno,'db2/get/fullnm/from/mssc/loccd/','#inc_fullnm')
    }
  }

  function simpanIncoming() {
    All.set_disable_button();
		$.post(All.get_url('inc/pay/save') , $(All.get_active_tab() + "#formSaveIncPay").serialize(), function(data)
    {
      All.set_enable_button();
      All.clear_div_in_boxcontent(".nextForm1 .hasil2");
      
      let msg = "";
      if(data.response === "false") {
        msg = "<div class='alert alert-error'><p align='center'>"+data.message+"</p></div>";        
      } else {
        $(All.get_active_tab() + " #inc_refno").val(null)
        $(All.get_active_tab() + " #amount").val(null)
        $(All.get_active_tab() + " #inc_remark").val(null)
        $(All.get_active_tab() + " #dfno").val(null)
        $(All.get_active_tab() + " #inc_fullnm").val(null)
        
        msg = "<div class='alert alert-success'><p align='center'>"+data.message+"</p></div>";        
      }
      $(All.get_box_content() + ".nextForm1 .hasil2").html(msg);
    },"json").fail(function() {
        alert("Error requesting page");
        All.set_enable_button();
    });
  }
</script>