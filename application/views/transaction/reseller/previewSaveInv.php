<form id="saveInv">
  <table width="100%" class="table table-striped table-bordered">
    <tr>
      <th colspan="4">Register Invoice</th>
    </tr>
    <tr>
      <td>No Register</td>
      <td align="left"><?php echo $header['registerno']; ?></td>
      <td>Tgl Register</td>
      <td align="left"><?php echo $header['registerdt']; ?></td>
    </tr>
    <tr>
      <td>Kode Reseller</td>
      <td align="left"><?php echo $header['kode_reseller']." / ".$header['nama_reseller']; ?></td>
      <td>Stockist</td>
      <td align="left"><?php echo $header['loccd']; ?></td>
    </tr>
    <tr>
      <td>ID Member</td>
      <td align="left"><?php echo $header['dfno']." / ".$header['nama_member']; ?></td>
      <td>Delivery Option</td>
      <td align="left"><?php echo $header['ship_desc']; ?></td>
    </tr>
    <tr>
      <td>Total DP</td>
      <td align="left"><?php echo number_format($header['total_dp_dist'], 0, ",","."); ?></td>
      <td>Warehouse</td>
      <td align="left"><?php echo $header['whcd']." / ".$header['whnm']; ?></td>
    </tr>
    <tr>
      <td>Total BV</td>
      <td align="left"><?php echo number_format($header['total_bv'], 0, ",","."); ?></td>
      <td>Total Pembayaran</td>
      <td align="left"><?php echo number_format($header['total_payment'], 0, ",","."); ?></td>
    </tr>
    <tr>
      <td>Reseller Fee</td>
      <td align="left"><?php echo number_format($header['reseller_fee'], 0, ",","."); ?></td>
      <td>Create By</td>
      <td align="left"><?php echo $header['createnm']; ?></td>
    </tr>
  </table>
  <?php echo "<input value=\"<< Kembali\" type=\"button\" class=\"btn btn-warning span20\" onclick=\"All.back_to_form(' .nextForm2',' .nextForm1')\"/>"; ?>
  <table width="100%" class="table table-striped table-bordered">
    <tr>
      <th colspan="8">Data Produk</th>
    </tr>
    <tr>
      <th>No</th>
      <th>Kode Produk</th>
      <th>Nama Produk</th>
      <th>Qty</th>
      <th>BV</th>
      <th>DP</th>
      <th>Sub Tot BV</th>
      <th>Sub Tot DP</th>
    </tr>
    <?php
      $no = 1;
      $tot_bv = 0;
      $tot_dp = 0;
      foreach($produk as $dtaprd) {
        echo "<tr>";
        echo "<td align=right>$no</td>";
        echo "<td align=center>$dtaprd[prdcd]</td>";
        echo "<td align=left>$dtaprd[prdnm]</td>";
        echo "<td align=right>".number_format($dtaprd['qtyord'], 0, ",",".")."</td>";
        echo "<td align=right>".number_format($dtaprd['bv'], 0, ",",".")."</td>";
        echo "<td align=right>".number_format($dtaprd['dp'], 0, ",",".")."</td>";
        $sub_bv = $dtaprd['qtyord'] * $dtaprd['bv'];
        $sub_dp = $dtaprd['qtyord'] * $dtaprd['dp'];
        echo "<td align=right>".number_format($sub_bv, 0, ",",".")."</td>";
        echo "<td align=right>".number_format($sub_dp, 0, ",",".")."</td>";
        echo "</tr>";
        $tot_bv += $sub_bv;
        $tot_dp += $sub_dp;
        $no++;
      }
    ?>
    <tr>
      <td colspan="6" align="center">TOTAL</td>
      <td align="right"><?php echo number_format($tot_bv, 0, ",","."); ?></td>
      <td align="right"><?php echo number_format($tot_dp, 0, ",","."); ?></td>
    </tr>
  </table>
  <table width="100%" class="table table-striped table-bordered">
    <tr>
      <th colspan="5">Pembayaran</th>
    </tr>
    <tr>
      <th>No</th>
      <th>Type</th>
      <th>Payment</th>
      <th>Ref No</th>
      <th>Amount</th>
    </tr>
    <?php
      $no = 1;
      $tot_bv = 0;
      $tot_dp = 0;
      foreach($new_payment as $pay) {
        echo "<tr>";
        echo "<td align=right>$no</td>";
        echo "<td align=center>$pay[paytype]</td>";
        echo "<td align=center>$pay[payTypeName]</td>";
        echo "<td align=center>$pay[docno]</td>";
        echo "<td align=right>".number_format($pay['payamt'], 0, ",",".")."</td>";
        $no++;
      }
    ?>
  </table>
  <input type="hidden" id="inputData" name="inputData" value="<?php json_encode($inputData); ?>" />  
  <input type="button" class="btn btn-mini btn-primary span20" value="Simpan Transaksi" onclick="Reseller.simpanTrx()" />  
</form>  