<table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable">
	<thead>
		<tr bgcolor=#f4f4f4>
			<th colspan="9">List Incoming Payment</th>
		</tr>
		<tr bgcolor=#f4f4f4>
      <!-- <th><input type="checkbox" name="checkall" onclick="checkAll(this);" /></th> -->
      <th>Tgl Mutasi</th> 
			<th>Inc Pay No</th>
			<th>Refno</th>
      <th>Stokist/Member</th>
      <th>Bank</th>
			<th>Amount</th>
      <th>Sisa</th>
      <th>Status</th>
      <th>Act</th>
		</tr>
	</thead>
	<tbody>
    <?php
	    $i = 1;
	    foreach($result as $dta) {
        echo "<tr>";
        /* echo "<td align=center><input type=checkbox name=cek[] class=update-bonus value=\"".$dta->trcd."\" /></td>"; */
        echo "<td align=center>$dta->trdt</td>";
        echo "<td align=center>$dta->trcd</td>";
        echo "<td align=center>$dta->refno</td>";      
        echo "<td align=center>$dta->cust</td>";
        echo "<td align=center>$dta->bankdesc</td>";
        echo "<td align=right>".number_format($dta->amount, 0, ",",".")."</td>";
        echo "<td align=right>".number_format($dta->balamt, 0, ",",".")."</td>";
        echo "<td align=center>$dta->status</td>";
        echo "<td><a class='btn btn-mini btn-success' onclick=\"javascript:detailIp('$dta->trcd')\">View</a></td>";
        echo "</tr>";
        $i++;
      }
    ?>
  </tbody>
</table>        
<?php
setDatatable();
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

function detailIp(trcd) {
  All.set_disable_button();
		$.ajax({
      url: All.get_url('inc/pay/id'),
      type: 'POST',
      data: {trcd: trcd},
      success:
      function(data){
        All.set_enable_button();
        $(All.get_active_tab() + ".mainForm").hide();
          All.clear_div_in_boxcontent(".nextForm1");
          $(All.get_active_tab() + ".nextForm1").html(data);
      },
      error: function(jqXHR, textStatus, errorThrown) {
          All.set_enable_button();
      }
    });
}
</script>