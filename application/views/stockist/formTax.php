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
                    <input type="text" id="idstk" name="idstk"
                        onchange="All.getFullNameByID(this.value,'db2/get/fullnm/from/mssc/loccd','#nmstk')" />
                    <input type="text" style="width: 300px;" readonly="readonly" id="nmstk" name="nmstk"
                        value="" />
                </div>
                <div class="clearfix"></div>
                <?php    
                } else {
                ?>
                <label class="control-label" for="typeahead">Stockist</label>
                <div class="controls">
                    <input type="text" id="idstk" name="idstk" value="<?php echo $userlogin; ?>" readonly="readonly"/>
                </div>
                <div class="clearfix"></div>
                <?php    
                }
                ?>
                <label class="control-label" for="typeahead">Tahun</label>
                <select id="year" name="year">

                </select>
                <div class="clearfix"></div>
                <div class="controls">
                    <input type="button" tabindex="6" class="btn btn-primary"
                        onclick="All.ajaxFormPost('formInputList','tax/print/act')" name="submit"
                        value="Submit" />
                    <input tabindex="6" type="reset" class="btn btn-reset" value="Reset">
                </div>
                <!-- end control-group -->
            </div><!-- end control-group -->
        </fieldset>
    </form>
    <div class="result"></div>
</div>
<!--/end mainForm-->

<script>
    var min = 2017,
        max = new Date().getFullYear(),
        select = document.getElementById('year');

    for (var i = max; i >= min; i--) {
        var opt = document.createElement('option');
        opt.value = i;
        opt.innerHTML = i;
        select.appendChild(opt);
    }
</script>