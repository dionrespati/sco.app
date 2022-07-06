<?php
if($result['response'] == "true") {
    /* echo "<pre>";
    print_r($result);
    echo "</pre>"; */
    $ordivhdr = $result['ordivhdr'];

    if($result['tipe'] == "invoice") {
        $headRegStk = "<td width=\"15%\">Distributor</td>";
    } else {
        $headRegStk = "<td width=\"15%\">Stockist</td>";
    }
?>
<form method="POST" action="<?php echo base_url('reseller/inv/print'); ?>" target="_BLANK">
<table class="table table-bordered table-striped" width="100%">
    <tr>
        <th colspan="4">Data Register</th>
    </tr>
	<tr>
		<td width="15%">No Register</td>
		<td width="40%"><?php echo $ordivhdr[0]->trcd; ?></td>
		<td width="15%">Total DP</td>
		<td><?php echo number_format($ordivhdr[0]->tdp, 0, ",","."); ?></td>
	</tr>
    <tr>
		<?php echo $headRegStk; ?>
		<td width="40%"><?php echo $ordivhdr[0]->dfno." / ".$ordivhdr[0]->dfno_name; ?></td>
		<td width="15%">Total BV</td>
		<td><?php echo number_format($ordivhdr[0]->tbv, 0, ",","."); ?></td>
	</tr>
    <tr>
		<td width="15%">C/O Stockist</td>
		<td width="40%"><?php echo $ordivhdr[0]->loccd." / ".$ordivhdr[0]->loccd_name; ?></td>
		<td width="15%">Ship</td>
		<td><?php echo $ordivhdr[0]->ship. " (".$ordivhdr[0]->ship_status.")"; ?></td>
	</tr>
    <tr>
		<td width="15%">Branch</td>
		<td width="40%"><?php echo $ordivhdr[0]->branch; ?></td>
		<td width="15%">Create By</td>
		<td><?php echo $ordivhdr[0]->createnm." @".$ordivhdr[0]->createdt; ?></td>
	</tr>
    <tr>
		<td width="15%">Warehouse</td>
		<td width="40%"><?php echo $ordivhdr[0]->whcd; ?></td>
		<td width="15%">Update By</td>
		<td><?php echo $ordivhdr[0]->updatenm." @".$ordivhdr[0]->updatedt; ?></td>
	</tr>
    <tr>
		<td width="15%">Online/Manual</td>
		<td width="40%"><?php echo $ordivhdr[0]->onlinetype; ?></td>
		<td width="15%">Pricecode</td>
		<td><?php echo $ordivhdr[0]->pricecode; ?></td>
	</tr>
    <tr>
		<td width="15%">Bonus Period</td>
		<td width="40%"><?php echo $ordivhdr[0]->bnsperiod; ?></td>
		<td width="15%">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
 </table>   
 <input tabindex="28" value="<< Kembali" type="button" class="btn btn-warning span20" onclick="All.back_to_form(' .nextForm1',' .mainForm')"/>
 <?php
    if($result['ordivtrh'] != null) {
 ?>
 <table class="table table-bordered table-striped" width="100%">
    <tr>
        <th colspan="10">Data CN / MS / Invoice / GR</th>
    </tr>
    <tr>
        <th><input type="checkbox" name="checkall" onclick="checkAll(this);" /></th> 
        <th>No</th>
        <th>Inv No</th>
        <th>Ord Type</th>
        <th>Stockist</th>
        <th>Bns Period</th>
        <th>Create Dt</th>
        <th>Receipt No</th>
        <th>TBV</th>
        <th>TDP</th>
        
    </tr>
    <?php
    $i = 1;
    $total_dp = 0;
    $total_bv = 0;
    foreach($result['ordivtrh'] as $dtax) {
        if($dtax->ordtype == "2" || $dtax->ordtype == "P") {
            $url = "transklink/cn/".$dtax->trcd;    
        } else {
            $url = "";
        }
        //<a onclick=All.ajaxShowDetailonNextForm('".$url."')>$dtax->trcd</a>
        echo "<tr>";
        echo "<td align=center><input type=checkbox name=cek[] class=update-bonus value=\"".$dtax->trcd."\" /></td>";
        echo "<td align=right>$i</td>";
        echo "<td align=center>$dtax->trcd</td>";
        echo "<td align=center>$dtax->ordtype</td>";
        echo "<td align=center>$dtax->dfno</td>";
        echo "<td align=center>$dtax->bnsperiod</td>";
        echo "<td align=center>$dtax->createdt</td>";
        echo "<td align=center>$dtax->receiptno</td>";
        echo "<td align=right>".number_format($dtax->tbv, 0, ",",".")."</td>";
        echo "<td align=right>".number_format($dtax->tdp, 0, ",",".")."</td>";
        echo "</tr>";
        $i++;
        $total_dp += $dtax->tdp;
        $total_bv += $dtax->tbv;
    }
    ?>
    <tr>
        <td colspan="3" align="center"><input type="submit" name="submit" value="Print Invoice" class="btn btn-mini btn-success" /></td> 
        <td colspan="5" align="center">T O T A L</td>
        <td align="right"><?php echo number_format($total_bv, 0, ",","."); ?></td>
        <td align="right"><?php echo number_format($total_dp, 0, ",","."); ?></td>
    </tr>
 <?php
    }

    if(array_key_exists("billivprd", $result)) {
        echo "<table class='table table-bordered table-striped' width='100%'>";
        $no = 1;
        echo "<tr><th>No</th><th>ID Produk</th><th>Nama Produk</th><th>Qty</th></tr>";
        foreach($result['billivprd'] as $prd) {
            echo "<tr>";
            echo "<td>$no</td>";
            echo "<td>$prd->prdcd</td>";
            echo "<td>$prd->prdnm</td>";
            echo "<td>".number_format($prd->qtyord, 0, ",",".")."</td>";
            echo "</tr>";
            $no++;
        }
        echo "</table>";
    }

    if($result['billivhdr'] != null) {
    $bill = $result['billivhdr'];
 ?>
 <table class="table table-bordered table-striped" width="100%">
    <tr>
        <th colspan="4">Data KW</th>
    </tr>
	<tr>
		<td width="15%">No KW</td>
		<td width="40%"><?php echo $bill[0]->trcd; ?></td>
		<td width="15%">Total DP</td>
		<td><?php echo number_format($bill[0]->tdp, 0, ",","."); ?></td>
	</tr>
    <tr>
        <?php echo $headRegStk; ?>
		<td width="40%"><?php echo $bill[0]->dfno ?></td>
		<td width="15%">Total BV</td>
		<td><?php echo number_format($bill[0]->tbv, 0, ",","."); ?></td>
	</tr>
    <tr>
		<td width="15%">Status BO</td>
		<td width="40%"><?php echo $bill[0]->statusbo; ?></td>
		<td width="15%">Total Invoice</td>
		<td><?php echo $bill[0]->totinvoice; ?></td>
	</tr>
    <tr>
		<td width="15%">Flag Ship</td>
		<td width="40%"><?php echo $bill[0]->flag_ship; ?> (0 = belum di buat DO, 1 = sudah ada DO)</td>
		<td width="15%">Create By</td>
		<td><?php echo $bill[0]->createnm." @".$bill[0]->createdt; ?></td>
	</tr>
    <tr>
        <td width="15%">DO</td>
		<td width="40%"><?php echo $bill[0]->do_wms. "  (". $bill[0]->do_wms_create_by ." @ ".$bill[0]->do_wms_create_dt.")"; ?></td>
		<td width="15%">Update By</td>
		<td><?php echo $bill[0]->updatenm." @".$bill[0]->updatedt;; ?></td>
	</tr>
    <!--
    <tr>
		<td width="15%">DO</td>
		<td width="40%"><?php echo $bill[0]->GDO; ?></td>
		<td width="15%">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>-->
 </table>
</form>   
<?php
    }
}
?>
<script type="text/javascript">
function checkAll(theElement)
{
    var theForm = theElement.form;
    for(z=0; z<theForm.length;z++)
    {
        if(theForm[z].type == 'checkbox' && theForm[z].name != 'checkall')
        {
            theForm[z].checked = theElement.checked;
        }

    }
}
</script>