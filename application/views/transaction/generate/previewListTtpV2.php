<?php
if($result == null) {
    echo setErrorMessage("Data tidak ditemukan..");
    backToMainForm();
} else {
   //$header = $result['header']; 
?>
<table style="width: 100%;" class="table table-striped table-bordered bootstrap-datatable datatable" align="center">
    <thead>
       <tr><th colspan="10">Data List Transaksi <?php echo $tipe; ?></th></tr>
       <tr>
        <th width="5%">No</th>
        <th width="10%">No Trx</th>
        <th>Member</th>
        <th width="10%">Tgl Trx</th>
        <th width="10%">Periode Bonus</th>
        <th width="8%">BV</th>
        <th width="10%">DP</th>
        <th width="10%">Total Voucher</th>
        <th width="10%">Total Cash</th>
        <th width="4%">&nbsp;</th>
       </tr>  
    </thead>
    <tbody>
       <?php
       $i = 1;
       $sub_totpay = 0;
       $sub_totbv = 0;
       $tot_cash = 0;
       $tot_vch = 0;
       foreach($result as $dtahead) {
            $total_bayar = $dtahead->tot_vch + $dtahead->tot_cash;
            $font_depan = "";
            $font_blkg = "";
            if($tipe == "PVR" && ($total_bayar < $dtahead->totpay || $dtahead->tot_vch == 0)) {
                $font_depan = "<font color=red>";
                $font_blkg = "</font>";
            } else if($tipe == "Voucher Cash (Deposit)" && ($total_bayar != $dtahead->totpay || $dtahead->tot_vch == 0)) {
                $font_depan = "<font color=red>";
                $font_blkg = "</font>";
            }

           echo "<tr>";
           echo "<td align=right>$font_depan $i $font_blkg</td>"; 
           echo "<td align=center>$font_depan".$dtahead->trcd."$font_blkg</td>";
           echo "<td>$font_depan".$dtahead->dfno." / ".$dtahead->fullnm."$font_blkg</td>";
           echo "<td align=center>$font_depan".$dtahead->etdt."$font_blkg</td>";
           echo "<td align=center>$font_depan".$dtahead->bnsperiod."$font_blkg</td>";
           echo "<td align=right>$font_depan".number_format($dtahead->tbv,0,".",".")."$font_blkg</td>";
           echo "<td align=right>$font_depan".number_format($dtahead->totpay,0,".",".")."$font_blkg</td>";
           echo "<td align=right>$font_depan".number_format($dtahead->tot_vch,0,".",".")."$font_blkg</td>";
           echo "<td align=right>$font_depan".number_format($dtahead->tot_cash,0,".",".")."$font_blkg</td>";
           echo "<td align=center><a class='btn btn-mini btn-success' onclick=\"javascript:All.ajaxShowDetailonNextForm2('sales/preview/trcd/$dtahead->trcd')\"><i class='icon icon-white icon-search'></i></a></td>";
           echo "</tr>";
           $sub_totpay += $dtahead->totpay;
           $sub_totbv += $dtahead->tbv;
           $tot_cash += $dtahead->tot_cash;
           $tot_vch += $dtahead->tot_vch;
           $i++;
       }
       ?>
       <tr>
        <th colspan="5" align="center">T O T A L</th>
        <td align="right"><?php echo number_format($sub_totbv,0,".","."); ?></td>
        <td align="right"><?php echo number_format($sub_totpay,0,".","."); ?></td>
        <td align="right"><?php echo number_format($tot_vch,0,".","."); ?></td>
        <td align="right"><?php echo number_format($tot_cash,0,".","."); ?></td>
        <td align="right">&nbsp;</td>
       </tr>
    </tbody>
</table>
<?php
}
/* echo "<pre>";
print_r($result);
echo "</pre>"; */
backToMainForm();

?>