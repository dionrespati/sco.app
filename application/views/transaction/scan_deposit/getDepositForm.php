<?php
$stk = $this->session->userdata("stockist");
$stkname = $this->session->userdata("stockistnm");
//print_r($curr_period);
?>
<div class="mainForm">
    <form class="form-horizontal" enctype="multipart/form-data" id="formInputList">
        <fieldset>
            <div class="control-group">
                <?php
                if($userlogin == "BID06") {
                ?>
                <label class="control-label" for="typeahead">Stockist</label>
                <div class="controls">
                   <input type="text" id="idstkk_vchdep" name="idstkk_vchdep" value="<?php echo $userlogin; ?>" onchange="All.getFullNameByID(this.value,'db2/get/fullnm/from/mssc/loccd','#nmstk_vchdep')" />
                   <input type="text" style="width: 300px;" readonly="readonly" id="nmstk_vchdep" name="nmstk_vchdep" value=""/>     
                </div>
                <div class="clearfix"></div>
                <?php    
                } else {
                ?>    
                <input type="hidden" id="idstkk_vchdep" name="idstkk_vchdep" value="<?php echo $userlogin; ?>" />    
                <?php    
                }
                ?>
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