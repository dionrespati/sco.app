<?php
   $trx_ssr = $cn_header;
   $cn_ip;
   $stt_edit = false;
   $colspan = "7";
   if(isset($edit)) {
    $stt_edit = true;
    $colspan = "8";
   }
   if($cn_header != null) {
?>
<form id="recoverForm">
<table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable">
    <tr>
		<th colspan="6">Data CN / MS</th>
	</tr>	
    <tr>
        <td width="15%"><b>Stockist</b></td>
		<td width="20%"><?php echo $trx_ssr[0]->dfno; ?></td>
		<td width="15%"><b>CN No</b></td>
		<td><?php echo $trx_ssr[0]->trcd; ?></td>
		<td width="15%"><b>CN Date</b></td>
        <td><?php echo " ( ".$trx_ssr[0]->createnm." @".$trx_ssr[0]->createdt." )"; ?></td>
	</tr>
    <tr>
    <td><b>Main Stockist</b></td>
		<td><?php echo $trx_ssr[0]->loccd; ?></td>
		<td><b>Register No</b></td>
		<td>
        <?php echo $trx_ssr[0]->registerno; ?>   
		</td>
		<td width="15%"><b>Register Date</b></td>
        <td><?php echo " ( ".$trx_ssr[0]->createnm." @".$trx_ssr[0]->registerdt." )"; ?></td>
	</tr>
	<tr>
        <td width="15%"><b>Total DP</b></td>
		<td><?php echo number_format($trx_ssr[0]->tdp, 0, ",","."); ?></td>
		<td><b>SSR No</b></td>
		<td>
			<?php echo $trx_ssr[0]->batchno; ?>   
		</td>
		<td width="15%"><b>SSR Date</b></td>
        <td><?php echo " ( ".$trx_ssr[0]->loccd." @".$trx_ssr[0]->batchdt." )"; ?></td>
	</tr>
    <tr>
    <td width="15%"><b>Total BV</b></td>
		<td><?php echo number_format($trx_ssr[0]->tbv, 0, ",","."); ?></td>
		<td><b>KW No</b></td>
		<td>
        <?php echo $trx_ssr[0]->no_kw; ?> 
		</td>
        <td width="15%"><b>KW Date</b></td>
        <td><?php echo " ( ".$trx_ssr[0]->kw_createnm." @".$trx_ssr[0]->kwdt." )"; ?></td>
		
	</tr>
    <tr>
    <tr>
        <td width="15%"><b>Bonus Period</b></td>
		<td><?php echo $trx_ssr[0]->bnsperiod ?></td> 
		<td><b>DO No</b></td>
		<td>
			<?php echo $trx_ssr[0]->GDO; ?>   
		</td>
        <td width="15%"><b>DO Date</b></td>
        <td><?php echo " ( ".$trx_ssr[0]->GDO_createnm." @".$trx_ssr[0]->GDOdt." )"; ?></td>
		
	</tr>
    <tr>
	<td width="15%"><b>BN</b></td>
		<td><?php echo $trx_ssr[0]->docno; ?> </td>
		<td><b>Online Type</b></td>
        <td><?php echo $trx_ssr[0]->onlinetype; ?></td>
		<td colspan="2">
        &nbsp;
		</td>
        
	</tr>
    <tr>
     <td width="15%"><b>DO WMS</b></td>
     <td colspan="5"><?php echo $trx_ssr[0]->do_wms. " ( ".$trx_ssr[0]->do_wms_create_by." @".$trx_ssr[0]->do_wms_create_dt." ) Ship From : ".$trx_ssr[0]->sent_from. " to ".$trx_ssr[0]->sent_to; ?></td>
    </tr>
    <tr>
     <td width="15%"><b>Note</b></td>
     <td colspan="5"><font color="red"><?php echo $trx_ssr[0]->note; ?></font></td>
    </tr>
    <tr>
     <td width="15%"><b>Remarks</b></td>
     <td colspan="5"><font color="red"><?php echo $trx_ssr[0]->remarks; ?></font></td>
    </tr>
    
</table>
<?php
if($back_button != "") {
    echo "<input value=\"<< Back \" type=\"button\" class=\"btn btn-mini btn-warning\" onclick=\"$back_button\"/>&nbsp;";
}
/* if($trx_ssr[0]->onlinetype == "O" || $trx_ssr[0]->onlinetype == "M") {
   
    echo $list_ttp;
    
} */
if($list_ttp == null) {
    echo setErrorMessage("Tidak ada TTP di dalam CN ini..");
} else {
?>
<table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable">
  <thead>
   <tr>
    <th colspan="<?php echo $colspan; ?>">List TTP / Transaksi</th>
   </tr>
   <tr>
    <th>No</th>
    <th>No Trx</th>
    <th>No TTP</th>
    <th>Member</th>
    <th>Bns Period</th>
    <th>Total DP</th>
    <th>Total BV</th>
    <?php
    if($stt_edit) {
        echo "<th>&nbsp;</th>";
    }    
    ?>
   </tr>
  </thead>
  <tbody> 
  <?php
    $nottp = 1;
    $total_ttp_dp = 0;
    $total_ttp_bv = 0;
    foreach($list_ttp as $dtattp) {
       echo "<tr>";     
       echo "<td align=right>$nottp</td>"; 
       echo "<td align=center>".$dtattp->trcd."</td>";
       echo "<td align=center>".$dtattp->orderno."</td>";
       echo "<td align=left>".$dtattp->dfno." / ".$dtattp->fullnm."</td>"; 
       echo "<td align=center>".$dtattp->bnsperiod."</td>";
       echo "<td align=right>".number_format($dtattp->tdp, 0, ",",".")."</td>";
       echo "<td align=right>".number_format($dtattp->tbv, 0, ",",".")."</td>";

        if($stt_edit) {
        $edit = "bo/cnmsn/formedit/".$dtattp->trcd;    
        echo "<td>";
        echo "<a class='btn btn-mini btn-primary' onclick=\"javascript:All.ajaxShowDetailonNextForm2('$edit')\">Blm Jadi</a>&nbsp;";
        echo "<a class='btn btn-mini btn-danger'>Blm Jadi</a>";
        echo "</td>";
        }  
        

       echo "</tr>";

       
       $nottp++;
       $total_ttp_dp += $dtattp->tdp;
       $total_ttp_bv += $dtattp->tbv;
    }
  ?>
  <tr>
    <td colspan="5" align="center">T O T A L</td>
    <td align="right"><?php echo number_format($total_ttp_dp, 0, ",",".") ?></td>
    <td align="right"><?php echo number_format($total_ttp_bv, 0, ",",".") ?></td>
    <?php
    if($stt_edit) {
        echo "<th>&nbsp;</th>";
    }    
    ?>
  </tr>
  </tbody>
</table>

<?php
}
?>
</form>
<?php
}
if($cn_prod != null) {
?>
<table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable">
    <tr>
        <th colspan="9">Summary Produk</th>
    </tr>
    <tr>
        <th>No</th>
        <th>Kode Produk</th>
        <th>Nama</th>
        <th>Qty</th>
        <th>Qty Input</th>
        <th>DP</th>
        <th>BV</th>
        <th>Sub Tot DP</th>
        <th>Sub Tot BV</th>
    </tr>
    <?php
    $no=1;
    $totalDP = 0;
    $totalBV = 0;
    foreach($cn_prod as $dtaprod) {
        $subDP = $dtaprod->qtyord * $dtaprod->dp;
        $subBV = $dtaprod->qtyord * $dtaprod->bv;

        $qty = (int) $dtaprod->qtyord;
        $jum_input = (int) $dtaprod->jum_input;

        $font = "";
        $font2 = "";
        if($qty !== $jum_input) {
            $font = "<font color=red>";
            $font2 = "</font>";
        }
        
        echo "<tr>";
        echo "<td align=right>$font".$no."$font2</td>";
        echo "<td align=center>$font".$dtaprod->prdcd."$font2</td>";
        echo "<td>$font".$dtaprod->prdnm."$font2</td>";
        echo "<td>".number_format($dtaprod->qtyord, 0, ",",".")."</td>";
        
        echo "<td>$font".number_format($dtaprod->jum_input, 0, ",",".")."$font2</td>";
        echo "<td align=right>".number_format($dtaprod->dp, 0, ",",".")."</td>";
        echo "<td align=right>".number_format($dtaprod->bv, 0, ",",".")."</td>";
        echo "<td align=right>".number_format($subDP, 0, ",",".")."</td>";
        echo "<td align=right>".number_format($subBV, 0, ",",".")."</td>";
        
        echo "</tr>";
        $totalDP += $subDP;
        $totalBV += $subBV;
        $no++;
    }
    ?>
    <tr>
        <td colspan="7" align="center">T O T A L</td>
        <td align="right"><?php echo number_format($totalDP, 0, ",","."); ?></td>
        <td align="right"><?php echo number_format($totalBV, 0, ",","."); ?></td>
    </tr>
</table>
<?php
}

if($cn_ip != null) {
    //print_r($cn_ip);
?>
<table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable">
    <tr>
        <th colspan="6">List Incoming Payment</th>
    </tr>
    <tr>
        <th>No</th>
        <th>No CN/MS</th>
        <th>Pay Type</th>
        <th>Doc No</th>
        <th>Bank</th>
        <th>Amount</th>
        
    </tr>
    <?php
    $no=1;
    $totalAmt = 0;
    foreach($cn_ip as $ip) {
       
        echo "<tr>";
        echo "<td align=right>$no</td>";
        echo "<td align=center>".$ip->trcd."</td>";
        echo "<td align=center>".$ip->pay_desc."</td>";
        echo "<td align=center>".$ip->docno."</td>";
        echo "<td align=center>".$ip->bankdesc."</td>";
        echo "<td align=right>".number_format($ip->payamt, 0, ",",".")."</td>";
        echo "</tr>";
        $totalAmt += $ip->payamt;
        $no++;
    }
    ?>
    <tr>
        <td colspan="5" align="center">T O T A L</td>
        <td align="right"><?php echo number_format($totalAmt, 0, ",","."); ?></td>
    </tr>
</table>

<?php
}
?>
