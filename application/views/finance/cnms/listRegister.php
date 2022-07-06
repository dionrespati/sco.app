
<table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable" align="center">
  <thead>
    <tr>
      <th>No</th>
      <th>No Register</th>
      <th>Tgl</th>
      <th>Stockist</th>
      <th>Total DP</th>
      <th>Total BV</th>
      <th>Total Invoice</th>
      <th>Act</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $no = 1;
      $tbv=0;
      $tdp=0;
    
      foreach($hasil as $list) {
        $urlUpdInv = "All.ajaxShowDetailonNextForm('bo/cnmsn/updateInv/$list->trcd')";
        $urlviewInv = "javascript:All.ajaxShowDetailonNextForm('bo/cnmsn/listInv/$list->trcd')";
    ?>
        <tr>
          <td style="text-align: right;"><?php echo $no; ?></td>
          <td style="text-align: center;"><?php echo $list->trcd;?></td>
          <td style="text-align: center;"><?php echo $list->registerdt;?></td>
          <td><?php echo $list->dfno. " / ".$list->nama_stockist;?></td>
          <td style="text-align: right"><?php echo number_format($list->tdp,0,".",".");?></td>
          <td style="text-align: right"><?php echo number_format($list->tbv,0,".",".");?></td>
          
          <?php
            $tot_inv = number_format($list->tot_invoice,0,".",".");
            if($list->kw_no === null) {
              echo "<td><div align=center><input type=\"button\" class=\"btn btn-mini btn-success\" value=\"List Invoice ($tot_inv)\" onclick=\"$urlviewInv\" /></div></td>";
              echo "<td style=\"text-align: center;\"><input type=\"button\" class=\"btn btn-mini btn-primary\" value=\"Tambah CN/MS\" onclick=\"$urlUpdInv\" /></td>";
            } else {  
              echo "<td style=\"text-align: center\">$tot_inv</td>";
              echo "<td style=\"text-align: center;\"><a onclick=\"$urlviewInv\">$list->kw_no</a></td>";
            }
          ?>
          
        </tr>
    <?php
          $no++;
          $tbv += $list->tbv;
          $tdp += $list->tdp;
        }
    ?>

    <!-- <tr>
      <td colspan="5">TOTAL</td>
      <td><?php echo "<div align=right>".number_format($tdp,0,".",".")."</div>";?></td>
      <td><?php echo "<div align=right>".number_format($tbv,0,".",".")."</div>";?></td>

    </tr> -->
  </tbody>
</table>
<?php
setDatatable();
?>
	
