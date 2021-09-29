<div class="mainForm">
<form class="form-horizontal" role="form" method="post" id="formSearchTrx" name="formSearchTrx">
    <fieldset>
    <div class="clearfix">
        <span class="span2" style="line-height:30px;">Barcode</span>
        <input type="text" class="span4" autocomplete="off" id="from1" name="tr_barcode" onkeypress="Stkbarcode.keycari(event)"/>
        <input type="text" disabled  maxlength="2" class="span1" autocomplete="off" id="form2"/>
        <input type="button" id="submits" class="btn btn-primary" onclick="Stkbarcode.TraceBarcode()" name="submit" value="Search"/>
    </div>
</form>
</div>
<div class="result"></div>
<div class="ListData"></div>
<div class="detailScan"></div>
<?php setDatePicker(); ?>