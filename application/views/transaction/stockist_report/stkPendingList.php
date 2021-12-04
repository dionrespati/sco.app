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
      }
    ?>
  </tbody>
</table>
<?php
setDatatable();
?>