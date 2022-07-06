<?php
  if($result === null) {
    echo setErrorMessage("Data Register tidak ditemukan..");
    echo "<input value=\"<< Kembali\" type=\"button\" class=\"btn btn-warning span20\" onclick=\"All.back_to_form(' .nextForm1',' .mainForm')\"/>";
  } else {
    /* echo "<pre>";
    print_r($result);
    echo "</pre>"; */
    echo "<form id=\"formUpdInvReseller\">";
    echo "<table width='100%' class='table table-bordered table-striped'>";
    echo "<tr><th colspan='4'>Invoice Reseller</th><tr>";
    echo "<tr><td width=15%><b>No Register</b></td><td>".$result[0]->trcd."</td><td width=15%><b>Tgl Register</b></td><td>".$result[0]->registerdt."</td></tr>";
    echo "<tr><td width=15%><b>Reseller</b></td><td>".$result[0]->kode_reseller." / ".$result[0]->nama_reseller."</td><td width=15%><b>Pricecode</b></td><td>".$result[0]->pricecode." (".$result[0]->pricecode_desc.")</td></tr>";
    echo "<tr><td width=15%><b>Member</b></td><td>".$result[0]->dfno." / ".$result[0]->nama_member."</td><td width=15%><b>Total DP</b></td><td>".$result[0]->tdp."</td></tr>";
    echo "<tr><td width=15%><b>Delivery Option</b></td><td>".$result[0]->ship_desc."</td><td width=15%><b>Total BV</b></td><td>".$result[0]->tbv."</td></tr>";
    echo "<tr><td width=15%><b>Warehouse</b></td><td>".$result[0]->whnm."</td><td width=15%><b>Bonus Period</b></td><td>".$result[0]->bnsperiod."</td>";
    echo "<input type='hidden' id='pricecode' name='pricecode' value='".$result[0]->pricecode."' />";
    echo "<input type='hidden' id='pricecode_desc' name='pricecode_desc' value='".$result[0]->pricecode_desc."' />";
    echo "<input type='hidden' id='jenis' name='jenis' value='' />";
    echo "<input type='hidden' id='dfno' name='dfno' value='".$result[0]->dfno."' />";
    echo "<input type='hidden' id='nama_member' name='nama_member' value='".$result[0]->nama_member."' />";
    echo "<input type='hidden' id='kode_reseller' name='kode_reseller' value='".$result[0]->kode_reseller."' />";
    echo "<input type='hidden' id='nama_reseller' name='nama_reseller' value='".$result[0]->nama_reseller."' />";
    echo "<input type='hidden' id='whcd' name='whcd' value='".$result[0]->whcd."' />";
    echo "<input type='hidden' id='whnm' name='whnm' value='".$result[0]->whnm."' />";
    echo "<input type='hidden' id='ship' name='ship' value='".$result[0]->ship."' />";
    echo "<input type='hidden' id='ship_desc' name='ship_desc' value='".$result[0]->ship_desc."' />";
    echo "<input type='hidden' id='registerdt' name='registerdt' value='".$result[0]->registerdt."' />";
    echo "<input type='hidden' id='registerno' name='registerno' value='".$result[0]->trcd."' />";
    echo "<input type='hidden' id='createnm' name='createnm' value='".$result[0]->createnm."' />";
    echo "<input type='hidden' id='bnsperiod' name='bnsperiod' value='".$result[0]->bnsperiod."' />";
    echo "</tr>";
    echo "</table>";
   /*  echo "<table width='100%' class='table table-bordered table-striped'>>";
    echo "<tr>";
    echo "<td><input value=\"<< Kembali\" type=\"button\" class=\"btn btn-warning span20\" onclick=\"All.back_to_form(' .nextForm1',' .mainForm')\"/></td>";
    echo "<td><input value=\"<< Kembali\" type=\"button\" class=\"btn btn-warning span20\" onclick=\"All.back_to_form(' .nextForm1',' .mainForm')\"/></td>";
    echo "</tr>";
    echo "</table>"; */
    echo "<input value=\"<< Kembali\" type=\"button\" class=\"btn btn-warning span20\" onclick=\"Reseller.backToMainForm()\"/></td>";
?>

<?php
  }
?>

