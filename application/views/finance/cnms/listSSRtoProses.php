<?php
    $reg = $result[0];
?>
<form>
<table width="100%" class="table table-striped table-bordered bootstrap-datatable" align="center">
  <tr>
    <th colspan="4">Register</th>
  </tr>
  <tr>
    <td>No Register</td>
    <td><?php echo $reg->trcd; ?></td>
    <td>Total DP</td>
    <td><?php echo number_format($reg->tdp,0,".",".");?></td>
  </tr>
  <tr>
    <td>Register Date</td>
    <td><?php echo $reg->registerdt; ?></td>
    <td>Total BV</td>
    <td><?php echo number_format($reg->tbv,0,".",".");?></td>
  </tr>
  <tr>
    <td>Stockist</td>
    <td><?php echo $reg->dfno. " / ".$reg->fullnm; ?></td>
    <td>Price Code</td>
    <td><?php echo $reg->pricecode_desc;?></td>
  </tr>
  <tr>
    <td>Bonus Period</td>
    <td><?php echo $reg->bnsperiod; ?></td>
    <td>Delivery Option</td>
    <td><?php echo $reg->ship_desc;?></td>
  </tr>
  <tr>
    <td>Create By</td>
    <td><?php echo $reg->createnm; ?></td>
    <td>Warehouse</td>
    <td><?php echo $reg->whcd. " / ".$reg->whnm;?></td>
  </tr>
</table>
<?php
  if($listCN !== null)  {
?>
<table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable" align="center">
  <tr>
    <th colspan="8">List CN / MS</th>
  </tr>
  <tr>
    <th><input type="checkbox" name="checkall" onclick="checkAll(this);" /></th> 
    <th>No CN</th>
    <th>Tgl</th>
    <th>Periode Bonus</th>
    <th>DP</th>
    <th>Stockist</th>
    <th>No SSR/MSR</th>
    <th>No KW</th>
    <th>Act</th>
  </tr>
  <?php
  foreach($listCN as $cnx) {

    //$urlviewInv = "";
    $urlviewInv = "javascript:All.ajaxShowDetailonNextForm2('bo/cnmsn/id/$cnx->invoiceno')";
    $printInv = "bo/cnmsn/print";
    $printx = "bo/cnmsn/printv/".$cnx->invoiceno;

    echo "<tr>";
    echo "<td align=center><input type=checkbox name=cekx[] class=update-bonus value=\"".$cnx->invoiceno."\" /></td>";
    echo "<td align=center>$cnx->invoiceno</td>";
    echo "<td align=center>$cnx->invoicedt</td>";
    echo "<td align=center>$cnx->bnsperiod</td>";
    echo "<td align=right>".number_format($cnx->tdp,0,".",".")."</td>";
    echo "<td align=center>$cnx->dfno</td>";
    echo "<td align=center>$cnx->batchscno</td>";
    echo "<td align=center>$cnx->receiptno</td>";
    echo "<td align=center><a class='btn btn-mini btn-success' onclick=\"$urlviewInv\">View</a>";
    //if($username === "DION" || $username === "BID06") {
    echo "&nbsp;<button class='btn btn-mini btn-success' type='submit' formtarget='_BLANK' formaction='".base_url($printx)."'>Print</button>";
    //}
    echo "</td>";
    echo "</tr>";
  }
  echo "<tr><td colspan=2><button class='btn btn-mini btn-success' type='submit' formmethod='POST' formtarget='_BLANK' formaction='".base_url($printInv)."'>Print</button></td><td colspan=7>&nbsp;</td></tr>"
  ?>
</table>
</form>
<?php
  }
backToMainForm();
if($listSSR !== null) {
?>
<table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable" align="center">
  <tr>
    <th colspan="7">List SSR / MSR</th>
  </tr>
  <tr>
    <th>SSR/MSR No</th>
    <th>Stockist</th>
    <th>DP</th>
    <th>BV</th>
    <th>Voucher Cash</th>
    <th>Vch Cash IP</th>
    <th>Action</th>
  </tr>
  <?php
  foreach($listSSR as $ssr) {

    
    $urlviewInv = "javascript:All.ajaxShowDetailonNextForm2('bo/cnmsn/rekapcn/$reg->trcd/$ssr->batchno')";

    echo "<tr>";
    echo "<td align=center>$ssr->batchno</td>";
    echo "<td>".$ssr->sc_dfno." / ".$ssr->nama_stk."</td>";
    echo "<td align=right>".number_format($ssr->DP,0,".",".")."</td>";
    echo "<td align=right>".number_format($ssr->BV,0,".",".")."</td>";
    echo "<td align=right>".number_format($ssr->vch_cash,0,".",".")."</td>";
    echo "<td align=center>$ssr->incpay_vchcash</td>";
    echo "<td align=center><a class='btn btn-mini btn-primary' onclick=\"$urlviewInv\">Proses</a></td>";
    echo "</tr>";
  }
  ?>
</table>
<?php
}
?>
<script>
  let yourArray = [];
let myCharKirim = "";
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
    //handleChange()
}

</script>
