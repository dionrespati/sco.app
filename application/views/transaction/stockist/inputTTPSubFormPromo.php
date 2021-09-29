
<?php
//print_r($header);
if ($ins == "2") {
	$sc_dfno = $header[0] -> sc_dfno;
	$sc_dfnonm = $header[0] -> sc_dfnonm;
	$sc_co = $header[0] -> sc_co;
	$sc_conm = $header[0] -> sc_conm;
	$loccd = $header[0] -> loccd;
	$loccdnm = $header[0] -> loccdnm;
	$trcd = $header[0] -> trcd;
	$etdt = $header[0] -> tglinput;
	$dfno = $header[0] -> dfno;
	$fullnm = $header[0] -> distnm;
	$orderno = $header[0] -> orderno;
	$remarks = $header[0] -> remarks;
	$readonly = "readonly=readonly";

} else {
	/* $sc_dfno = $stockist;
	$sc_dfnonm = $stockistnm;
	$sc_co = $stockist;
	$sc_conm = $stockistnm;
	$loccd = $stockist;
	$loccdnm = $stockistnm; */
	$trcd = "";
	$etdt = date("Y-m-d");
	$dfno = "";
	$fullnm = "";
	$orderno = "";
	$remarks = "";
	$readonly = "";
    //$sctype = $sctype;
	//$co_sctype = $co_sctype;
}
		
?>
<form id="salesSubStockist" class="formSales">
	<div id="firstForm" style="display:block;">
		<table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable">
			<tr>
				<th colspan="5"><?php echo strtoupper($head_form); ?></th>
			</tr>
      <tr>
				<td width="13%">&nbsp;Pilih Promo</td>
				<td colspan="4">
          <select id="jenis_promo" name="jenis_promo" style="width: 400px;">
             <option value="PRK">Pre Order K-ion Nano Premium 8</option> 
          </select>
				</td>
			</tr>
			<tr>
				<td width="13%">&nbsp;Stockist</td>
				<td width="10%">
				<input tabindex="1" type="text" <?php echo $sc_dfno_readonly; ?> class="typeahead span20" id="sc_dfno"  name="sc_dfno" value="<?php echo $sc_dfno; ?>" onchange="Stockist.getStkScType(this)" />
				</td>
				<td width="35%">
				<input readonly="readonly" type="text" class="span20 typeahead" id="sc_name"  name="sc_name" value="<?php echo $sc_dfnonm; ?>" />
				<input type="hidden" id="sctype" name="sctype" value="<?php echo $sctype; ?>" />
				</td>
				<td width="13%">&nbsp;No Trx</td>
				<td>
				<input  readonly="readonly" type="text" class="span12 typeahead" id="trcd" placeholder="<< Auto Transaction Number >>"  name="trcd" value="<?php echo $trcd; ?>" />
				</td>
			</tr>
			<tr>
				<td>&nbsp;C/O Stockist </td>
				<td>
				<input  tabindex="2" type="text" <?php echo $sc_co_readonly; ?> class="typeahead span20" id="sc_co"  name="sc_co" value="<?php echo $sc_co; ?>"  onchange="Stockist.getStkScType(this)" />
				</td>
				<td>
				<input readonly="readonly" type="text" class="span20 typeahead" id="sc_co_name"  name="sc_co_name" value="<?php echo $sc_conm; ?>" />
				<input type="hidden" id="co_sctype" name="co_sctype" value="<?php echo $co_sctype; ?>" />
				</td>

				<td>&nbsp;Tgl Trx</td>
				<td>
				<input readonly="readonly" type="text" class="span12 typeahead" id="trxdate"  name="trxdate" value="<?php echo $etdt; ?>" />
				</td>
			</tr>
			<tr>
					
				<td> &nbsp;Main Stockist </td>
				<td>
				<input readonly="readonly" type="text" class="typeahead span20" id="loccd"  name="loccd" value="<?php echo $loccd; ?>" onchange="Stockist.getStkScType(this)" />
				<input type="hidden" id="loccd_sctype" name="loccd_sctype" value="<?php echo $co_sctype; ?>" />
				</td>
				<td>
				<input readonly="readonly" type="text" class="span20 typeahead" typeahead" id="loccdnm"  name="loccdnm" value="<?php echo $loccdnm; ?>" />
				</td>
				<td>&nbsp;Pricecode</td>
				<td>
				<input readonly="readonly" type="text" class="span12 typeahead" id="pricecode"  name="pricecode" value="<?php echo $pricecode; ?>" />
				</td>
			</tr>
			<tr>

				<td> &nbsp;ID Member</td>
				<td colspan="2">
									<input
											tabindex="3"
											type="text"
											placeholder="Tidak boleh kosong, untuk ID Luar Negeri diisi kode 0000999"
											class="span12 typeahead"
											id="dfno"
											name="dfno"
											onchange="All.getFullNameByID(this.value,'api/member/check','#fullnm')"
											onkeyup="All.AlphaNumOnly(this)"
											value="<?php echo $dfno; ?>" />
				</td>

				<td>&nbsp;Periode Bonus</td>
				<td>
				<select tabindex="4" id="bnsperiod"  name="bnsperiod" class="span8 typeahead">
					<?php
						echo showBnsPeriodV2($stockist, $currentperiod);
					?>
				</select></td>
			</tr>
			<tr>
				<td>&nbsp;Nama Member </td>
				<td colspan="2">
				<input readonly="readonly" type="text" class="span12 typeahead" id="fullnm"  name="fullnm" value="<?php echo $fullnm; ?>" />
				</td>
				<td>&nbsp;No TTP</td>
				<td>
				<input tabindex="5" type="text" placeholder="Tidak boleh kosong" class="span12 typeahead" id="orderno"  name="orderno" value="<?php echo $orderno; ?>" onchange="All.checkDoubleInput('db2/get/orderno/from/sc_newtrh/','orderno',this.value)" />
				</td>
			</tr>
			<tr>
				<td>&nbsp;Keterangan</td>
				<td colspan="6">
				<input tabindex="6" type="text" placeholder="Untuk ID Luar Negeri, kolom ini diisi dengan format : ID Member/Nama Member" class="span12 typeahead" id="remarks"  name="remarks" value="<?php echo $remarks; ?>"  />
				</td>
				<!--<td>&nbsp;</td>
				<td>
				&nbsp;
				</td>-->

			</tr>


		</table>
