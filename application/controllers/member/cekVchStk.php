<?php
if($result['response'] == "false") {
    echo "<div class='alert alert-error'>".$result['message']."</div>";
} 

if($result['arraydata'] !== null) {
    $arr = $result['arraydata'][0];
?>
<table class='table table-bordered' width="50%">
	<thead>
		<th colspan="2">Data Voucher</th>
	</thead>
	<tbody>
		<tr>
			<td width="30%">Voucher No</td>
			<td><?php echo $arr->formno; ?></td>
		</tr>
		<tr>
			<td>Voucher Key</td>
			<td><?php echo $arr->vchkey; ?></td>
		</tr>
		<tr>
			<td>Status</td>
			<td><?php echo $arr->ket_status; ?></td>
		</tr>
		<tr>
			<td>Activate ID Member</td>
			<td><?php echo $arr->activate_dfno; ?></td>
		</tr>
	</tbody>
</table>


<?php
	if($result['response'] == "true") {
		echo "<input type='button' class='btn btn-mini btn-primary' value='Release Voucher' name='releasebtn' onclick=releaseVch('$arr->formno','$arr->vchkey','$user') />";
	}    
}
?>