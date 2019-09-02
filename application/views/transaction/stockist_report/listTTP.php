<?php
    if(empty($result)){
		echo setErrorMessage("No result found");
		echo "<input type=\"button\" value=\"&lt;&lt; Kembali\" 
onclick=\"$back_button\" 
class=\"btn btn-mini btn-warning span20\">";
        ?>

        

        <?php
    }else{
?>
   <form id="updMember">
	<table style="width: 100%" class="table table-striped table-bordered bootstrap-datatable datatable">
        <thead>
			<tr>
				<th colspan="6">List TTP </th>
			</tr>
			<tr>
			   <td colspan="2">SSR No</td>
			   <td  colspan="4"><?php echo $result[0]->batchno; ?>&nbsp;</td>
			</tr>
            <tr>
			   <td colspan="2">No Voucher Deposit</td>
			   <td colspan="4">
			   <?php 
				echo $result[0]->no_deposit; 
			   ?>
			   &nbsp;</td>
			</tr>
		    <tr>
			   <td colspan="2">Bns Period</td>
			   <td  colspan="4"><?php echo $result[0]->bnsperiod; ?>&nbsp;</td>
			</tr>
			<tr>
			   <td colspan="2">DO No</td>
			   <td  colspan="4"><?php 
			 	if($result[0]->GDO !== null && $result[0]->GDO !== "") {  
			   		echo $result[0]->GDO." by ".$result[0]->GDO_createnm." @".$result[0]->GDOdt; 
				} else {
					echo "DO belum di proses/dibuat";
				}	   
				?>
				&nbsp;</td>
			</tr>				
			<tr>
			    <th width="5%">No</th>
				<th width="15%">No Trx</th>
				<th width="15%">Order No</th>
				<th>Nama Member</th>
				<th width="12%">Total DP</th>	
				<th width="5%">Total BV</th>	
				<!--<th width="10%">Bonus Month</th>	
				<th width="10%">No Deposit Vch</th>-->
			   
			</tr>
		</thead>
		<tbody>
		<?php
		$no=1;
		$total_dp = 0;
		$total_bv = 0;
		foreach($result as $datax) {

			$url = "transklink/trcd/".$datax->trcd;
			echo "<tr>";
			echo "<td align=right>$no</td>";
			echo "<td align=center><a onclick=All.ajaxShowDetailonNextForm2('".$url."')>$datax->trcd</a></td>";
			echo "<td align=center>$datax->orderno</td>";
			echo "<td>$datax->dfno / $datax->fullnm</td>";
			echo "<td align=right>".number_format($datax->totpay, 0, ",",".")."</td>";
			echo "<td align=right>".number_format($datax->tbv, 0, ",",".")."</td>";
			//echo "<td align=center>$datax->bnsperiod</td>";
			//echo "<td align=center>$datax->no_deposit</td>";
			echo "</tr>";
			$total_dp += $datax->totpay;
			$total_bv += $datax->tbv;
			$no++;
		}
		?>
		<tr><td colspan="4" align="center">T O T A L</td><td align="right"><?php echo number_format($total_dp, 0, ",","."); ?></td><td align="right"><?php echo number_format($total_bv, 0, ",","."); ?></td></tr>
		</tbody>
</table>
	<p></p>
	<div>
		 <?php echo "<input type=\"button\" value=\"&lt;&lt; Kembali\" 
onclick=\"$back_button\" 
class=\"btn btn-mini btn-warning span20\">"; ?>
		 <!--<input type="button" value="Save Changes" class="btn btn-small btn-primary" onclick="be_member.updateDataMember()" />-->
    </div>
	<p></p>
	</form>
<?php
    }
?>