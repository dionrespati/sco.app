<?php
if($result['response'] == "false") {
	echo setErrorMessage("No Result Found..");
} else {	
    $vch = $result['voucher'][0];
?>
<table width="70%" class="table table-striped table-bordered bootstrap-datatable datatable">
    <thead>
	   <tr>
        <th colspan="2">
        <?php 
        if($kategori == "vc_umr") {
            echo "Data Voucher Umroh";
        } else if($kategori == "vc_c") {
            echo "Data Voucher Cash";
        } else if($kategori == "vc_prd") {
            echo "Data Voucher Produk";
        } else if($kategori == "vc_prm") {
            echo "Data Voucher Promo";
        }
        ?>
        </th>
       </tr>
	</thead>
	<tbody>
	   <tr>
        <td width="25%">No Voucher</td>
        <td><?php echo $vch->voucherno; ?></td> 
       </tr>
       <tr>
        <td width="25%">Nilai Voucher</td>
        <td><?php echo $vch->nilaivoucher; ?></td> 
       </tr>
       <tr>
        <td>ID Member</td>
        <td><?php echo $vch->DistributorCode." / ".$vch->fullnm; ?></td> 
       </tr>
       <tr>
        <td>Tgl Expire</td>
        <td><?php echo $vch->ExpireDate; ?></td> 
       </tr>
       <tr>
        <td>Status Klaim</td>
        <td><?php echo $vch->status_claim; ?></td> 
       </tr>
	</tbody>
</table>

<?php
if($result['trx'] != null) {

?>
<table width="70%" class="table table-striped table-bordered bootstrap-datatable datatable">
    <thead>
	   <tr>
          <th colspan="4">Transaksi Voucher</th>  
       <tr>
       <tr>
          <th>No Trx / Vch Deposit</th>  
          <th>ID Member</th>  
          <th>Tgl Input</th>  
          <th>Kode Stk</th>  
       </tr>
    </thead>
    <tbody>
    <?php
    foreach($result['trx'] as $dtaTrx) {
        echo "<tr>";
        echo "<td align=center>".$dtaTrx->trcd."</td>";
        echo "<td align=center>".$dtaTrx->dfno."</td>";
        echo "<td align=center>".$dtaTrx->createdt."</td>";
        echo "<td align=center>".$dtaTrx->loccd." - ".$dtaTrx->loccd_name."</td>";
        echo "</tr>";
    }
    ?>
    </tbody>
</table>       
<?php
}
}
?>