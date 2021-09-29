<div class="mainForm">
    <form class="form-horizontal" method="post" id="fromScanSearch" name="fromScanSearch">
        <fieldset>
            <div class="control-group">
                <label class="control-label" for="typeahead">Tipe Pencarian</label>
                <div class="controls">
                    <!--<span class="span2" style="line-height:30px;">Tipe Pencarian</span>-->
                    <select id="pencarian" class="control" onchange="Stkbarcode.cari()">
                        <option value="1">NO TTP</option>
                        <option value="2">Tanggal Transaksi</option>
                    </select>

                </div>
                <div class="pencarian_ttp">
                    <label class="control-label" for="typeahead">No TTP</label>
                    <div class="controls">
                        <input type="text" class="span2" autocomplete="off" id="from1" name="no_ttp" />
                        <input type="button" id="submits" class="btn btn-primary" onclick="Stkbarcode.searchTrx()"
                            name="submit" value="Search" />
                    </div>
                </div>
                <div class="pencarian_range" style="display:none">
                  <?php 
                  if($username=='BID06'){ ?>
                        <label class="control-label" for="typeahead">Kode Stockist x</label>
                        <div class="controls">
                            <input type="text" id="kd_stockist" name="loccd" value="<?php echo $username; ?>"/>
                        </div>
                 <?php  } ?>

                    <label class="control-label" for="typeahead">Tgl Transaksi</label>
                    <div class="controls">
                        <input type="text" class="dtpicker" id="barcode_from" name="from" value="<?php echo $from1; ?>" />
                        <input type="text" class="dtpicker" id="barcode_to" name="to" value="<?php echo $to1; ?>" />
                        <input type="button" id="submits" class="btn btn-primary"
                            onclick="Stkbarcode.searchTrxBarcode()" name="submit" value="Search" />
                    </div>               
                </div>
            </div>
        </fieldset>
    </form>
      
<div class="result"></div>

</div>
<div clas="load"></div>
<?php setDatePicker(); ?>