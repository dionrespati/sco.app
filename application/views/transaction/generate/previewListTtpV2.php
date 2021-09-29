<?php
if($result == null) {
    echo setErrorMessage("Data tidak ditemukan..");
    backToMainForm();
} else {
   //$header = $result['header'];
?>
<form id="listPvr">
  <?php 
    $colsHeader = "10";
    $colsFootTotal = "5";
    if ($stockist === 'BID06') { 
    $colsHeader = "11";
    $colsFootTotal = "6";
    ?>
    <div class="form-group">
      <label for="bonus-period">Bonus Period</label>
      <input type="text" class="datepicker-bonus" id="datepicker-bonus" name="bonusperiod" placeholder="Bonus Period" />
      <input type="button" value="Change Bonus" class="btn btn-primary" onclick="updateBonusPeriod()" />
    </div>
  <?php } ?>
  <table style="width: 100%;" class="table table-striped table-bordered bootstrap-datatable datatable" align="center">
    <thead>
      <tr>
        <th colspan="<?php echo $colsHeader; ?>">Data List Transaksi <?php echo $tipe; ?></th>
      </tr>
      <tr>
      <?php if ($stockist === 'BID06') { ?>
        <th><input type="checkbox" name="checkall" onclick="checkAll(this);" /></th>
      <?php } ?>  
        <th width="5%">No</th>
        <?php
              $headNs = "No TTP";
              if($tipe === "PVR") {
                  $headNs = "No Trx";
              }
          ?>
        <th width="10%"><?php echo $headNs; ?></th>
        <th>Member</th>
        <th width="10%">Tgl Trx</th>
        <th width="9%">Periode Bonus</th>
        <th width="7%">BV</th>
        <th width="9%">DP</th>
        <th width="9%">Total Voucher</th>
        <th width="9%">Total Cash</th>
        <th width="4%">&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $i = 1;
        $sub_totpay = 0;
        $sub_totbv = 0;
        $tot_cash = 0;
        $tot_vch_byr = 0;
        foreach($result as $dtahead) {
          $total_bayar = $dtahead->cash;
              $font_depan = "";
              $font_blkg = "";
              $tot_vch = 0;
              $prefix = substr($dtahead->trcd, 0, 2);
              if($tipe == "PVR") {
                  $tot_vch = $dtahead->pcash;
                  $total_bayar = $tot_vch + $dtahead->cash;
                  if($total_bayar < $dtahead->totpay) {
                      $font_depan = "<font color=red>";
                      $font_blkg = "</font>";
                  }

              } else if($tipe == "Voucher Cash (Deposit)") {
                  $tot_vch = $dtahead->vcash;
                  $total_bayar = $tot_vch + $dtahead->cash;
                  if($total_bayar != $dtahead->totpay) {
                      $font_depan = "<font color=red>";
                      $font_blkg = "</font>";
                  }

              } else if(($tipe == "SSR" || $tipe == "SSSR" || $tipe == "MSR") && $prefix == "CV")  {
                  $tot_vch = $dtahead->vcash;
                  $total_bayar = $tot_vch + $dtahead->cash;
                  if($total_bayar != $dtahead->totpay || $tot_vch == 0) {
                      $font_depan = "<font color=red>";
                      $font_blkg = "</font>";

                  }
              }

              if($dtahead->sc_dfno === null || $dtahead->sc_dfno === ""
                  || $dtahead->sc_co === null || $dtahead->sc_co === ""
                  || $dtahead->loccd === null || $dtahead->loccd === ""
                  || $dtahead->loccd !== $dtahead->createnm) {
                      $font_depan = "<font color=red>";
                      $font_blkg = "</font>";
                  }

            echo "<tr>";
            if ($stockist === 'BID06') {
            echo "<td align=\"center\">
                    <input type=checkbox name=cek[] class=update-bonus value=\"".$dtahead->trcd."\" />
                    </td>";
            }        
            echo "<td align=right>$font_depan $i $font_blkg</td>";

            $notrxTampil = $dtahead->orderno;
            if($tipe === "PVR") {
              $notrxTampil = $dtahead->trcd;
            }
            echo "<td align=center>$font_depan".$notrxTampil."$font_blkg</td>";
            echo "<td>$font_depan".$dtahead->dfno." / ".substrwords($dtahead->fullnm,20)."$font_blkg</td>";
            echo "<td align=center>$font_depan".$dtahead->etdt."$font_blkg</td>";
            echo "<td align=center>$font_depan".$dtahead->bnsperiod."$font_blkg</td>";
            echo "<td align=right>$font_depan".number_format($dtahead->tbv,0,".",".")."$font_blkg</td>";
            echo "<td align=right>$font_depan".number_format($dtahead->totpay,0,".",".")."$font_blkg</td>";
            echo "<td align=right>$font_depan".number_format($tot_vch,0,".",".")."$font_blkg</td>";
            echo "<td align=right>$font_depan".number_format($dtahead->cash,0,".",".")."$font_blkg</td>";
            echo "<td align=center><a class='btn btn-mini btn-success' onclick=\"javascript:All.ajaxShowDetailonNextForm2('sales/preview/trcd/$dtahead->trcd')\"><i class='icon icon-white icon-search'></i></a></td>";
            echo "</tr>";
            $sub_totpay += $dtahead->totpay;
            $sub_totbv += $dtahead->tbv;
            $tot_cash += $dtahead->cash;
            $tot_vch_byr += $tot_vch;
            $i++;
        }
        ?>
      <tr>
        <th colspan="<?php echo $colsFootTotal; ?>" align="center">T O T A L</th>
        <td align="right"><?php echo number_format($sub_totbv,0,".","."); ?></td>
        <td align="right"><?php echo number_format($sub_totpay,0,".","."); ?></td>
        <td align="right"><?php echo number_format($tot_vch_byr,0,".","."); ?></td>
        <td align="right"><?php echo number_format($tot_cash,0,".","."); ?></td>
        <td align="right">&nbsp;</td>
      </tr>
    </tbody>
  </table>
</form>
<?php
if($rekapProduk !== null) {
   echo "<table style='width: 100%;' class='table table-striped table-bordered bootstrap-datatable datatable' align='center'>";
   echo "<thead>";
   echo "<tr><th colspan=8>List Rekap Produk<th></tr>";
   echo "<tr><th>No</th>";
   echo "<th>Kode Produk</th>";
   echo "<th>Nama Produk</th>";
   echo "<th>Qty</th>";
   echo "<th>BV</th>";
   echo "<th>DP</th>";
   echo "<th>Total BV</th>";
   echo "<th>Total DP</th></tr>";
   echo "</thead><tbody>";

   $no = 1;
   $total_qty = 0;
   $total_bv = 0;
   $total_dp = 0;

   foreach($rekapProduk as $dtaPrdx) {
     echo "<tr>";
     echo "<td align=right>$no</td>";
     echo "<td align=center>$dtaPrdx->prdcd</td>";
     echo "<td>$dtaPrdx->prdnm</td>";
     echo "<td align=right>".number_format($dtaPrdx->total_qty,0,".",".")."</td>";
     if($tipe === "PVR") {
        $bv = "0";
        $totbv = "0";
     } else {
        $bv = $dtaPrdx->bv;
        $totbv = $dtaPrdx->total_bv;
     }
     echo "<td align=right>".number_format($bv,0,".",".")."</td>";
     echo "<td align=right>".number_format($dtaPrdx->dp,0,".",".")."</td>";
     echo "<td align=right>".number_format($totbv,0,".",".")."</td>";
     echo "<td align=right>".number_format($dtaPrdx->total_dp,0,".",".")."</td>";
     echo "</tr>";
     $total_qty += $dtaPrdx->total_qty;
     $total_bv += $totbv;
     $total_dp += $dtaPrdx->total_dp; 
     $no++;
   }

   echo "<tr>";
   echo "<td colspan=3>TOTAL</td>";
   echo "<td align=right>".number_format($total_qty,0,".",".")."</td>";
   echo "<td colspan=2>&nbsp;</td>";
   echo "<td align=right>".number_format($total_bv,0,".",".")."</td>";
   echo "<td align=right>".number_format($total_dp,0,".",".")."</td>";
   echo "</tr>";
   echo "</tbody></table>";
}

}
/* echo "<pre>";
print_r($result);
echo "</pre>"; */
backToMainForm();

setDatePicker('.datepicker-bonus');
?>

<script type="text/javascript">
function checkAll(theElement)
{
    var theForm = theElement.form;
    for(z=0; z<theForm.length;z++)
    {
        if(theForm[z].type == 'checkbox' && theForm[z].name != 'checkall')
        {
            theForm[z].checked = theElement.checked;
        }

    }
}

function updateBonusPeriod() {
  let trcd = $("#listPvr").serialize();

  $.ajax({
    url: All.get_url('sales/generate/update-bonus-period'),
    type: 'POST',
    dataType: 'json',
    data: trcd,
    success: function(data) {
      alert(data.message);
    }
  })
}
</script>