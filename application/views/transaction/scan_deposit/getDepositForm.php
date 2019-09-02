<?php
$stk = $this->session->userdata("stockist");
$stkname = $this->session->userdata("stockistnm");
//print_r($curr_period);
?>
<div class="mainForm">
    <form class="form-horizontal" enctype="multipart/form-data" id="formInputList">
        <fieldset>
            <div class="control-group">
                <label class="control-label" for="typeahead">Tipe Status</label>
                <div class="controls">
                    <select class="span4 typeahead" id="search" name="search" tabindex="3">
                        <option value="">--Pilih disini--</option>
                        <option value="generated">Sudah di-generate</option>
                        <option value="ungenerated">Belum generate</option>
                    </select>
                </div>
                <div class="clearfix"></div>
                <div class="controls">
                    <input type="button" tabindex="6" class="btn btn-success" onclick="All.ajaxFormGet('transaction/scan_voucher/getListScan')" name="submit" value="Deposit Baru"/>
                    <input type="button" tabindex="6" class="btn btn-primary" onclick="All.ajaxFormPost(this.form.id,'scan/list')" name="submit" value="Cari"/>
                </div>
            <!-- end control-group -->
          </div><!-- end control-group -->
        </fieldset>
      </form>
    <div class="result"></div>
</div><!--/end mainForm-->
<?php setDatePicker(); ?>