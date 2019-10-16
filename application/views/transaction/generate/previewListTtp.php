<?php
if($result['header'] == null) {
    echo setErrorMessage("Data tidak ditemukan..");
    backToMainForm();
} else {
   $header = $result['header'];
?>
<table style="width: 100%;" class="table table-striped table-bordered bootstrap-datatable datatable" align="center">
    <thead>
       <tr><th colspan="7">Data List Transaksi Stokist <?php echo $header[0]->sc_dfno; ?></th></tr>
       <tr>
        <th width="5%">No</th>
        <th width="10%">No Trx</th>
        <th>Member</th>
        <th width="10%">Tgl Trx</th>
        <th width="10%">Periode Bonus</th>
        <th width="15%">BV</th>
        <th width="15%">DP</th>
       </tr>
    </thead>
    <tbody>
       <?php
       $i = 1;
       $sub_totpay = 0;
       $sub_totbv = 0;
       foreach($header as $dtahead) {
           echo "<tr>";
           echo "<td align=right>$i</td>";
           echo "<td align=center>".$dtahead->trcd."</td>";
           echo "<td>".$dtahead->dfno." / ".$dtahead->fullnm."</td>";
           echo "<td align=center>".$dtahead->etdt."</td>";
           echo "<td align=center>".$dtahead->bnsperiod."</td>";
           echo "<td align=right>".number_format($dtahead->tbv,0,".",".")."</td>";
           echo "<td align=right>".number_format($dtahead->totpay,0,".",".")."</td>";
           echo "</tr>";
           $sub_totpay += $dtahead->totpay;
           $sub_totbv += $dtahead->tbv;
           $i++;
       }
       ?>
       <tr>
        <th colspan="5" align="center">T O T A L</th>
        <td align="right"><?php echo number_format($sub_totbv,0,".","."); ?></td>
        <td align="right"><?php echo number_format($sub_totpay,0,".","."); ?></td>

       </tr>
    </tbody>
</table>
    <?php
if($result['payment'] != null) {
    $payment = $result['listVch'];
    $totalVch = $result['totalVch'];
    $totalCash = $result['totalCash'];
?>
<table style="width: 100%;" class="table table-striped table-bordered bootstrap-datatable datatable" align="center">
    <thead>
       <tr>
            <th colspan="2">Rekap Pembayaran Voucher dan Cash</th>
       </tr>
       <tr>
            <td align="right">Total Voucher&nbsp;</td>
            <td align="right" width="15%"><?php echo number_format($totalVch,0,".","."); ?></td>
       </tr>
       <tr>
            <td align="right">Total Cash&nbsp;</td>
            <td align="right" width="15%"><?php echo number_format($totalCash,0,".","."); ?></td>
       </tr>
    <thead>
</table>


<table id="salesListVoucher" style="width: 100%;" class="table table-striped table-bordered bootstrap-datatable datatable" align="center">
    <thead>
       <tr><th colspan="7">List Voucher</th></tr>
       <tr>
        <th width="5%">No</th>
        <th width="10%">No Trx</th>
        <th width="10%">Tipe</th>
        <th width="10%">Voucher</th>
        <th>Member</th>
        <th width="15%">Nominal</th>
       </tr>
    </thead>
    <tbody>
    <?php
        $j = 1;
        $total_vch = 0;
        foreach($payment as $dtapay) {
            echo "<tr>";
            echo "<td align=right>$j</td>";
            echo "<td align=center>$dtapay->trcd</td>";
            echo "<td align=center>$dtapay->paytype</td>";
            echo "<td align=center>$dtapay->docno</td>";
            echo "<td align=center>$dtapay->dfno</td>";
            echo "<td align=right>".number_format($dtapay->payamt,0,".",".")."</td>";
            echo "</tr>";
            $total_vch += $dtapay->payamt;
            $j++;
        }
    ?>
    <tr>
            <td align="right" colspan="5">Total Voucher&nbsp;</td>
            <td align="right"><?php echo number_format($total_vch,0,".","."); ?></td>
    </tr>
    </tbody>
</table>
<?php
}
/* echo "<pre>";
print_r($result);
echo "</pre>"; */
backToMainForm();
}
?>
