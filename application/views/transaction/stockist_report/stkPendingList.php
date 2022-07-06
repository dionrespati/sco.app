<table class="table table-striped table-bordered" width="100%">
  <thead>
    <tr>
        <th colspan="10">SSR/MSR/PVR Stockist yang belum di approve</th>
    </tr>
      <tr>
          <th>NO</th>
          <th>No SSR</th>
          <th>Tgl SSR</th>
          <th>Stockist</th>
          <th>Total DP</th>
          <th>Total BV</th>
      </tr>
  </thead>
  <tbody>
    <?php
      $i = 1;
      $total_dp = 0;
      $total_bv = 0;
      foreach($res as $dta) {
        echo "<tr>";
        echo "<td align=right>$i</td>";
        echo "<td align=center>$dta->batchno</td>";
        echo "<td align=center>$dta->batchdt</td>";
        echo "<td>$dta->loccd - $dta->fullnm</td>";
        echo "<td align=right>".number_format($dta->total_dp, 0, ",",".")."</td>";
        echo "<td align=right>".number_format($dta->total_bv, 0, ",",".")."</td>";
        echo "</tr>";
        $i++;
        $total_dp += $dta->total_dp;
        $total_bv += $dta->total_bv;
      }
    ?>
    <tr>
      <td colspan="4">Total</td>
      <?php
      echo "<td align=right>".number_format($total_dp, 0, ",",".")."</td>";
      echo "<td align=right>".number_format($total_bv, 0, ",",".")."</td>";
      ?>
    </tr>
  </tbody>
</table>
<?php
setDatatable();
?>