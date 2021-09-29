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
        <td><?php echo $vch->voucherno; echo " ("; echo "vchno : ".$vch->VoucherNo."/ vchkey :".$vch->voucherkey; echo ")";  ?></td> 
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
        <td>
         <?php 
         
         $tgl = date("Y-m-d");
         if($vch->ExpireDate >= $tgl) {
            echo $vch->ExpireDate; 
         } else {
            echo "<font color=red> * Sudah Expire @".$vch->ExpireDate."</font>"; 
         }
         ?>
         </td> 
       </tr>
       <tr>
        <td>Status Klaim</td>
        <td>
          <?php 
          //echo $vch->status_claim; 
          if($vch->status_claim === "Sudah diklaim") {
            echo "<font color=red>* Sudah diklaim @".$vch->claim_date."</font>"; 
          } else {
            echo "Belum diklaim";
          }
          ?>
        </td> 
       </tr>
       <!-- <tr>
        <td>Tgl Klaim</td>
        <td><?php echo $vch->claim_date; ?></td> 
       </tr> -->
       <tr>
        <td>Stokis/Lokasi Klaim</td>
        <td><?php echo $vch->loccd; ?></td> 
       </tr>
       <tr>
        <td>Aktivasi Voucher</td>
        <td><?php if($vch->status_open !== "1") {
            echo "Belum diaktifkan";
        } else {
            echo "Sudah diaktifkan @";
            echo $vch->openstatus_dt;
        }
        
        ?>
        </td> 
       </tr>
       <tr>
        <td>Remarks</td>
        <td><?php echo $vch->remarks; ?></td> 
       </tr>
	</tbody>
</table>

<?php
if(array_key_exists('vchPromo', $result)) {
    if($result['vchPromo'] !== null) {
        echo "<table width=\"70%\" class=\"table table-striped table-bordered bootstrap-datatable datatable\">";
        echo "<tr><th colspan=4>Voucher Promo Produk</th></tr>";
        echo "<tr><th>No</th><th>Kode Produk</th><th>Nama Produk</th><th>Qty</th></tr>";
        $i = 1;
        foreach($result['vchPromo'] as $prdpromo) {
            echo "<tr>";
                echo "<td align=center>".$i."</td>";
                echo "<td align=center>".$prdpromo->prdcd."</td>";
                echo "<td align=left>".$prdpromo->prdnm."</td>";
                echo "<td align=right>".$prdpromo->qtyord."</td>";
                echo "</tr>";
                $i++;
        }    
        echo "</table>";
    }
}

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