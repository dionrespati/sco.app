<?php
$stk = $this -> session -> userdata("stockist");
$stkname = $this -> session -> userdata("stockistnm");
//print_r($bnsmonth);
?>
<div class="mainForm">
	<form class="form-horizontal" enctype="multipart/form-data" id="formInputList" action="sales/ol/redemp/export-excel" method="post">
		<fieldset>
			<div class="control-group">
				<?php
				if($stk == "BID06") {
				?>
				<label class="control-label" for="typeahead">Kode Stockist</label>
				<div class="controls">
					<input type="text" name="kodestk" id="kodestk" value="<?php echo $stk; ?>" onchange="All.getFullNameByID(this.value,'db2/get/fullnm/from/mssc/loccd','#kodestknm')" />
					<input type="text" class="fullnm_width" readonly="yes" name="kodestknm" id="kodestknm" value="" />

				</div>
				<?php
				}
				?>
				<label class="control-label" for="typeahead">Pencarian</label>
				<div class="controls">

					<select tabindex="1" class="span3 typeahead" id="searchs" name="searchs">
	                    <option value="xx">All</option>
	                    <option value="1">Approve</option>
	                    <option value="0" selected="selected">Pending</option>
	                </select>
				</div>
				<div class="clearfix"></div>
				<label class="control-label" for="typeahead">Bonus Period</label>
				<div class="controls">
					<select tabindex="2" class="span3 typeahead" id="bnsmonth"  name="bnsmonth">
                        <?php
                            foreach($bnsmonth as $dt)
                            {
                                echo "<option value=$dt->bonusmonth>$dt->bonusmonth</option>";
                            }
                        ?>
                    </select>
				</div>
				<label class="control-label" for="typeahead">&nbsp;</label>
				<div class="controls">
					<input type="button" tabindex="3" class="btn btn-success" onclick="All.ajaxFormPost(this.form.id,'sales/ol/redemp/list')" name="submit" value="Cari"/>
                    <input type="button" tabindex="3" class="btn btn-primary" onclick="All.ajaxFormPost(this.form.id,'sales/ol/redemp/report')" name="report" value="Rekap Produk per Trx"/>
                    <input type="button" tabindex="3" class="btn btn-warning" onclick="All.ajaxFormPost(this.form.id,'sales/ol/redemp/report-product')" name="product" value="Rekap Semua Produk"/>
				</div>
			<!-- end control-group -->
          </div><!-- end control-group -->
		</fieldset>
        <div class="result"></div>
    </form>
</div><!--/end mainForm-->
<div id="histdispcontainer"></div>
