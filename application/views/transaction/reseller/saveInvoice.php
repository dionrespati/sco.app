<?php
  $invoiceno = $header[0]->invoiceno;
?>
<form id="saveInv" method="GET" action="<?php echo base_url('print/invreseller/').$invoiceno; ?>">
  <table width="100%" class="table table-striped table-bordered">
    <tr>
      <th colspan="4">Transaksi Invoice</th>
    </tr>
    <tr>
      <td>No Register</td>
      <td align="left"><?php echo $header[0]->registerno; ?></td>
      <td>ID Member</td>
      <td align="left"><?php echo $header[0]->dfno; ?></td>
    </tr>
    <tr>
      <td>No Invoice</td>
      <td align="left"><?php echo $header[0]->invoiceno; ?></td>
      <td>Nama Member</td>
      <td align="left"><?php echo $header[0]->fullnm; ?></td>
    </tr>
    
      <td>Total DP</td>
      <td align="left"><?php echo number_format($header[0]->tdp, 0, ",","."); ?></td>
      <td>Total Pembayaran</td>
      <td align="left"><?php echo number_format($header[0]->totpay, 0, ",","."); ?></td>
    </tr>
    <tr>
      <td>Total BV</td>
      <td align="left"><?php echo number_format($header[0]->tbv, 0, ",","."); ?></td>
      <td>&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
  </table>
  <?php //echo "<input value=\"<< Kembali\" type=\"button\" class=\"btn btn-warning span20\" onclick=\"All.back_to_form(' .nextForm2',' .nextForm1')\"/>"; ?>
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
        echo "<td align=center>$dtaprd->prdcd</td>";
        echo "<td align=left>$dtaprd->prdnm</td>";
        echo "<td align=right>".number_format($dtaprd->qtyord, 0, ",",".")."</td>";
        echo "<td align=right>".number_format($dtaprd->bv, 0, ",",".")."</td>";
        echo "<td align=right>".number_format($dtaprd->dp, 0, ",",".")."</td>";
        $sub_bv = $dtaprd->qtyord * $dtaprd->bv;
        $sub_dp = $dtaprd->qtyord * $dtaprd->dp;
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
      /* $tot_bv = 0;
      $tot_dp = 0; */
      foreach($payment as $pay) {
        echo "<tr>";
        echo "<td align=right>$no</td>";
        echo "<td align=center>$pay->paytype</td>";
        echo "<td align=center>$pay->pay_desc</td>";
        echo "<td align=center>$pay->docno</td>";
        echo "<td align=right>".number_format($pay->payamt, 0, ",",".")."</td>";
        $no++;
      }
    ?>
  </table>
  <input type="submit" class="btn btn-mini btn-primary span20" value="Print Invoice" />  
</form>  