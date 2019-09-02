<?php
if($result['header'] == null) {
    echo setErrorMessage("Data tidak ditemukan..");
    backToMainForm();
} else {
   $header = $result['header']; 
?>
<table style="width: 100%;" class="table table-striped table-bordered bootstrap-datatable datatable" align="center">
    <thead>
       <tr><th colspan="6">Data List Transaksi Stokist <?php echo $header[0]->sc_dfno; ?></th></tr>
       <tr>
        <th width="5%">No</th>
        <th width="10%">No Trx</th>
        <th>Member</th>
        <th width="10%">Tgl Trx</th>
        <th width="15%">DP</th>
        <th width="15%">BV</th>
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
           echo "<td align=right>".number_format($dtahead->totpay,0,".",".")."</td>";
           echo "<td align=right>".number_format($dtahead->tbv,0,".",".")."</td>";
           echo "</tr>";
           $sub_totpay += $dtahead->totpay;
           $sub_totbv += $dtahead->tbv;
           $i++;
       }
       ?>
       <tr>
        <td colspan="4" align="center">T O T A L</td>
        <td align="right"><?php echo number_format($sub_totpay,0,".","."); ?></td>
        <td align="right"><?php echo number_format($sub_totbv,0,".","."); ?></td>
       </tr>
    </tbody>
</table>
<?php
backToMainForm();
}
?>
