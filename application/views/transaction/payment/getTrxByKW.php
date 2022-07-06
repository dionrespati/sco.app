<?php
 $bill = $kw_header;
 if($tipe_trx == "stk") {
    $headRegStk = "Stockist";
 } else {
    $headRegStk = "Distributor";
 }
?>
<form>
<table class="table table-bordered table-striped" width="100%">
    <tr>
        <th colspan="4">Data KW</th>
    </tr>
	<tr>
		<td width="15%">No KW / No Register</td>
		<td width="40%"><?php echo $bill[0]->trcd. " / ".$bill[0]->applyto; ?></td>
		<td width="15%">Total DP</td>
		<td><?php echo number_format($bill[0]->tdp, 0, ",","."); ?></td>
	</tr>
    <tr>
        
        <td width="15%"><?php echo $headRegStk; ?></td>
		<td width="40%"><?php echo $bill[0]->dfno ?></td>
		<td width="15%">Total BV</td>
		<td><?php echo number_format($bill[0]->tbv, 0, ",","."); ?></td>
	</tr>
    <?php
    $textStatus_bo = "";
    if($bill[0]->statusbo == "0") {
        $textStatus_bo = " (Tidak ditarik DO)";
    }
    ?>
    <tr>
		<td width="15%">Status BO</td>
		<td width="40%"><?php echo $bill[0]->statusbo.$textStatus_bo; ?></td>
		<td width="15%">Total Invoice</td>
		<td><?php echo $bill[0]->totinvoice; ?></td>
	</tr>
    <?php
    $tdk_bs_ditarik_do = 0;
    $textDO = $bill[0]->flag_ship."  (0 = belum di buat DO, 1 = sudah ada DO)";
    if(($bill[0]->GDO == null || $bill[0]->GDO == "") && $bill[0]->flag_ship == "1" && $bill[0]->statusbo == "1") {
        $tdk_bs_ditarik_do = 1;
        $textDO = "<font color=red>".$bill[0]->flag_ship."  (0 = belum di buat DO, 1 = sudah ada DO)</font>";
    }
    ?>
    <tr>
		<td width="15%">Flag Ship</td>
		<td width="40%"><?php echo $textDO; ?></td>
		<td width="15%">Create By</td>
		<td><?php echo $bill[0]->createnm." @".$bill[0]->createdt; ?></td>
	</tr>
    <tr>
        <td width="15%">DO Newera</td>
		<td width="40%"><?php echo $bill[0]->GDO. "  (". $bill[0]->GDO_createnm ." @ ".$bill[0]->GDOdt.")"; ?></td>
		<td width="15%">Update By</td>
		<td><?php echo $bill[0]->updatenm." @".$bill[0]->updatedt; ?></td>
	</tr>
    <tr>
        <td width="15%">DO WMS</td>
		<td width="40%"><?php echo $bill[0]->do_wms. "  (". $bill[0]->do_wms_create_by ." @ ".$bill[0]->do_wms_create_dt.")"; ?></td>
		<td width="15%">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
    <tr>
    <td width="15%">Shipping Info</td>
    <td colspan="3"><?php echo "Sent From : ".$bill[0]->sent_from." To : ".$bill[0]->sent_to; ?></td>
    </tr>
    
 </table> 
 <input type="hidden" id="no_kw" name="no_kw" value="<?php echo $bill[0]->trcd; ?>" />
 <input type="hidden" id="no_register" name="no_register" value="<?php echo $bill[0]->applyto; ?>" />
 <input value="<< Back" type="button" class="btn btn-mini btn-danger" onclick="All.back_to_form(' .nextForm1 ',' .mainForm ')"/>
 <input type="button" class='btn btn-mini btn-success' value='List CN' id='btn_cn' onclick="showCN()" />
 <input type="button" class='btn btn-mini btn-success' value='Summary Product' id='btn_prd' onclick="showProd()" />
 <input type="button" class='btn btn-mini btn-success' value='List Payment' id='btn_pay' onclick="showPay()" />
 <input type="button" class='btn btn-mini btn-warning' value='Show All' id='btn_show' onclick="showAll()" />
 <input type="button" class='btn btn-mini btn-primary' value='Description' id='btn_desc' onclick="showDesc()" />
 <input type="hidden" id="hideall" value="1" />
 </form>

 <!-- <div id="tbl-desc" style="display: none;">
     <pre>
     Untuk di buat DO :
        * di table newivtrh, field ship = 2 (untuk transaksi CN/MS)
        * di table billivhdr/billhdr, field statusbo = 1, flag_ship = 0
          nilai awal flag_ship = 0 bila DO belum di buat dan 1 jika DO sudah di buat

    Keterangan table billivhdr/billhdr
        * field trcd = NO KW
        * field applyto = No Register
        * field dfno = Kode Stokis / ID Member
     </pre>
 </div>  -->
 <?php
 if($product != null) {
 ?>
 <div id="tbl-prd" style="display: none;">
<table class="table table-bordered table-striped" width="100%">
    <tr>
        <th colspan="6">Data Summary Produk</th>
    </tr>
    <tr>
        <th>No</th>
        <th>Kode produk</th>
        <th>Nama</th>
        <th>Qty</th>
        <th>DP</th>
        <th>Gross DP</th>
    </tr>
    <?php
    $i = 1;
    $grossdp = 0;
    foreach($product as $dtaprd) {
        echo "<tr>";
        echo "<td align=right>$i</td>";
        echo "<td align=center>$dtaprd->prdcd</td>";
        echo "<td>$dtaprd->prdnm</td>";
        echo "<td align=right>".number_format($dtaprd->qtyord, 0, ",",".")."</td>";
        echo "<td align=right>".number_format($dtaprd->dp, 0, ",",".")."</td>";
        $grossdp += $dtaprd->qtyord * $dtaprd->dp;
        echo "<td align=right>".number_format($grossdp, 0, ",",".")."</td>";
        echo "</tr>";
        $i++;
    }
    ?>
    <tr>
        <td colspan="5" align="center">T O T A L</td>
        <td align="right"><?php echo number_format($grossdp, 0, ",","."); ?></td>
    </tr>
</table>

 <?php
 } else {
     echo setErrorMessage("Data Product Kosong...");
 }
 echo "</div>";

 if($listCn != null) {
?>
<div id="tbl-cn" style="display: none;">
<table class="table table-bordered table-striped" width="100%">
<tr>
        <th colspan="8">Data CN / MS / Invoice / GR</th>
    </tr>
    <tr>
        <th>No</th>
        <th>Inv No</th>
        <th>Ord Type</th>
        <th>Stockist</th>
        <th>Bns Period</th>
        <th>Create Dt</th>
        <th>TDP</th>
        <th>TBV</th>
    </tr>
    <?php
    $i = 1;
    $total_dp = 0;
    $total_bv = 0;
    foreach($listCn as $dtax) {
        
        echo "<tr>";
        echo "<td align=right>$i</td>";
        if($dtax->ordtype == "2" || $dtax->ordtype == "P") {
            $url = "payment/receipt/cn/".$dtax->trcd;   
            echo "<td align=center><a onclick=All.ajaxShowDetailonNextForm('".$url."')>$dtax->trcd</a></td>"; 
        } else {
            $url = "";
            echo "<td align=center>$dtax->trcd</td>";
        }
        
        echo "<td align=center>$dtax->ordtype</td>";
        echo "<td align=center>$dtax->dfno</td>";
        echo "<td align=center>$dtax->bnsperiod</td>";
        echo "<td align=center>$dtax->createdt</td>";
        echo "<td align=right>".number_format($dtax->tdp, 0, ",",".")."</td>";
        echo "<td align=right>".number_format($dtax->tbv, 0, ",",".")."</td>";
        echo "</tr>";
        $i++;
        $total_dp += $dtax->tdp;
        $total_bv += $dtax->tbv;
    }
    ?>
    <tr>
        <td colspan="6" align="center">T O T A L</td>
        <td align="right"><?php echo number_format($total_dp, 0, ",","."); ?></td>
        <td align="right"><?php echo number_format($total_bv, 0, ",","."); ?></td>
        
    </tr>
</table>
<?php
 } else {
    echo setErrorMessage("Data CN Kosong...");
 }
 echo "</div>";
 if($listPay != null) {
 ?>
 <div id="tbl-pay" style="display: none;">
<table  class="table table-bordered table-striped" width="100%">
    <tr>
        <th colspan="5">Data List Payment</th>
    </tr>
    <tr>
        <th>No</th>
        <th>No Incoming</th>
        <th>Pay Type</th>
        <th>Description</th>
        <th>Amount</th>
    </tr>
    <?php
    $i = 1;
    $totalPayment = 0;
    foreach($listPay as $dtapay) {
        echo "<tr>";
        echo "<td align=right>$i</td>";
        echo "<td align=center>$dtapay->docno</td>";
        echo "<td align=center>$dtapay->paytype</td>";
        echo "<td align=center>$dtapay->description</td>";
        echo "<td align=right>".number_format($dtapay->payamt, 0, ",",".")."</td>";
        echo "</tr>";
        $i++;
        $totalPayment += $dtapay->payamt;
    }
    ?>
    <tr>
        <td colspan="4" align="center">T O T A L</td>
        <td align="right"><?php echo number_format($totalPayment, 0, ",","."); ?></td>
    </tr>
</table>
 <?php
 }  else {
    echo setErrorMessage("Data List Incoming Payment Kosong...");
 }
 /*
 if($grossdp > $totalPayment) {
     echo "<font color=red>Payment dan Summary Nilai Produk tidak sama</font>&nbsp;";
     echo "<input type=button class='btn btn-mini btn-primary' value='Recover IP yang kurang' onclick='recoverIP()' />";
 }
 */
 ?>
 </div>
 <script>
 function showCN() {
   $(All.get_active_tab() + " #tbl-cn").css("display", "block");
   $(All.get_active_tab() + " #tbl-prd").css("display", "none");
   $(All.get_active_tab() + " #tbl-pay").css("display", "none");
   $(All.get_active_tab() + " #tbl-desc").css("display", "none");
 }

 function showProd() {
    $(All.get_active_tab() + " #tbl-cn").css("display", "none");
    $(All.get_active_tab() + " #tbl-prd").css("display", "block");
    $(All.get_active_tab() + " #tbl-pay").css("display", "none"); 
    $(All.get_active_tab() + " #tbl-desc").css("display", "none");
 }

 function showPay() {
    $(All.get_active_tab() + " #tbl-cn").css("display", "none");
    $(All.get_active_tab() + " #tbl-prd").css("display", "none");
    $(All.get_active_tab() + " #tbl-desc").css("display", "none");
    $(All.get_active_tab() + " #tbl-pay").css("display", "block");
 }

 function showAll() {
    var hideall = $(All.get_active_tab() + " #hideall").val();
    if(hideall == "1") {
        $(All.get_active_tab() + " #tbl-cn").css("display", "block");
        $(All.get_active_tab() + " #tbl-prd").css("display", "block");
        $(All.get_active_tab() + " #tbl-pay").css("display", "block");
        $(All.get_active_tab() + " #tbl-desc").css("display", "block");
        $(All.get_active_tab() + " #hideall").val("0");
        $(All.get_active_tab() + " #btn_show").val("Hide All");
    } else {
        $(All.get_active_tab() + " #tbl-cn").css("display", "none");
        $(All.get_active_tab() + " #tbl-prd").css("display", "none");
        $(All.get_active_tab() + " #tbl-pay").css("display", "none");
        $(All.get_active_tab() + " #tbl-desc").css("display", "none");
        $(All.get_active_tab() + " #hideall").val("1");
        $(All.get_active_tab() + " #btn_show").val("Show All");
    }
 }

 function showDesc() {
    $(All.get_active_tab() + " #tbl-cn").css("display", "none");
    $(All.get_active_tab() + " #tbl-prd").css("display", "none");
    $(All.get_active_tab() + " #tbl-desc").css("display", "block");
    $(All.get_active_tab() + " #tbl-pay").css("display", "none");
 }

 function recoverIP() {

 }
 </script>