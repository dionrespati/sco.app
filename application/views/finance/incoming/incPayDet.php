<?php
  $bbhdr = $result['arrayData']['bbhdr'];
  $custpaydet = $result['arrayData']['custpaydet'];
  $ip_bal = $result['arrayData']['ip_bal'];
?>
<table style="width: 100%;" class='table table-striped table-bordered bootstrap-datatable datatable'>
	<thead>
		<tr>
			<th colspan='10' style="height: 35px;vertical-align: middle;font-size: medium;">Incoming Payment</th>
		</tr>
		<tr style="background-color: #f5f4f4;">
			<th>Incoming Payment</th>
			<th>Ref. No.</th>
			<th>Description</th>
			<th>Amount</th>
			<th>Create Date</th>
			<th>Cust</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td style="text-align: center"><?php echo $bbhdr[0]->trcd; ?></td>
			<td style="text-align: center"><?php echo $bbhdr[0]->refno; ?></td>
			<td style="text-align: center"><?php echo $bbhdr[0]->description; ?></td>
			<td style="text-align: right"><?php echo number_format($bbhdr[0]->amount, "0", ".", ",") ;?></td>
			<td style=" text-align: center;"><?php echo $bbhdr[0]->createdt;?></td>
			<td style="text-align: center"><?php echo $bbhdr[0]->cust ?></td>
		</tr>
	</tbody>
</table>
<?php 
echo backToMainForm();
if (!empty($custpaydet)) {
?>
<table style="width: 100%;" class='table table-striped table-bordered bootstrap-datatable datatable'>
	<thead>
		<tr>
			<th colspan='10' style="height: 35px;vertical-align: middle;font-size: medium;">List Transaction
			</th>
		</tr>
		<tr style="background-color: #f5f4f4;">
			<th>No</th>
			<th>TRCD</th>
			<th>Effect</th>
			<th>Df. No.</th>
			<th>Create Name</th>
			<th>Date Created</th>
			<th>TrType</th>
			<th>Apply To</th>
			<th>ID No.</th>
			<th>Amount</th>
		</tr>
	</thead>
	<tbody>
		<?php
    $no = 1;
    $total_effect_plus = 0;
    $total_effect_min = 0;
    foreach($custpaydet as $list) {
        if($list->effect == "+") {
            $total_effect_plus += $list->amount;
        } else if($list->effect == "-") {
            $total_effect_min += $list->amount;
        }    
    ?>
      <tr>
        <td style="text-align: center"><?php echo $no;?></td>
        <td style="text-align: center"><?php echo $list->trcd; ?></td>
        <td style="text-align: center"><?php echo $list->effect; ?></td>
        <td style="text-align: center"><?php echo $list->dfno; ?></td>
        <td style="text-align: center"><?php echo $list->createnm; ?></td>
        <td style="text-align: center"><?php echo $list->createdt; ?></td>
        <td><?php echo $list->trtype;?></td>
        <td><?php echo $list->applyto;?></td>
        <td style=" text-align: center;"><?php echo $list->idno;?></td>
        <td style="text-align: right"><?php echo number_format($list->amount,"0",".", ","); ?></td>
      </tr>
		<?php 
        $no++; 
    }
        
        ?>
		<tr>
			<td colspan="9" style="text-align: center">Balance Amount</td>
			<td style="text-align: right">
				<?php
                $sisa = $total_effect_plus - $total_effect_min;
                echo number_format($sisa,"0",".", ",");
            ?>
			</td>
		</tr>
	</tbody>
</table>
<?php
}
	
if ($ip_bal != null) {
    /*echo "<pre>";
    print_r($ip_bal);
    echo "</pre>";*/
?>
<table style="width: 100%;" class='table table-striped table-bordered bootstrap-datatable datatable'>
	<thead>
		<tr>
			<th colspan='10' style="height: 35px;vertical-align: middle;font-size: medium;">Incoming Payment Balance</th>
		</tr>
		<tr style="background-color: #f5f4f4;">
			<th>No</th>
			<th>IP No</th>
			<th>Cust Type</th>
			<th>Status</th>
			<th>Create Name</th>
			<th>Date Created</th>
			<th>Amount</th>
			<th>Balance</th>
			<th>Update</th>
		</tr>
	</thead>
	<tbody>
		<?php
    $no = 1;
    foreach($ip_bal as $listBal){
    ?>
		<tr>
			<td style="text-align: center"><?php echo $no;?></td>
			<td style="text-align: center"><?php echo $listBal->trcd; ?></td>
			<td style="text-align: center"><?php echo $listBal->custtype; ?></td>
			<td style="text-align: center"><?php echo $listBal->status; ?></td>
			<td style="text-align: center"><?php echo $listBal->createnm; ?></td>
			<td style="text-align: center"><?php echo $listBal->updatedt; ?></td>
			<td style="text-align: right"><?php echo number_format($listBal->amount,"0",".", ","); ?></td>
			<td style="text-align: right"><?php echo number_format($listBal->balamt,"0",".", ","); ?></td>
			<td>
				<?php
            if($listBal->balamt != $sisa) {
                echo "<input type='button' class='btn btn-mini btn-primary' value='Update Balance' />";
            } else {
                echo "&nbsp;";
            }
        ?>
			</td>
		</tr>
		<?php $no++; }?>
	</tbody>
</table>
<?php
} 
?>
