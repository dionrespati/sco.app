<form id="formSalesCN">
	<div id="firstForm" style="display:block;">
		<table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable">
			<tr>
				<th colspan="2"><?php echo strtoupper($head_form); ?></th>
			</tr>
      <tr>
				<td>Stockist</td>
				<td>
          <input style="width: 200px;" type="text" id="stk" name="stk" value="<?php echo $stk; ?>" onchange="All.getFullNameByID(this.value,'db2/get/fullnm/from/mssc/loccd/','#stkname')" />
          <input type="text" style="width: 500px;" readonly="readonly" id="stkname" name="stkname" value="<?php echo $stkname; ?>" />
          <input type="hidden" style="width: 500px;" readonly="readonly" id="pricecode" name="pricecode" value="<?php echo $pricecode; ?>" />
				</td>
      </tr>
      <tr>
				<td>Periode Bonus</td>
				<td>
          <select tabindex="2" id="bonusmonth" name="bonusmonth">
            <?php 
            if($currentperiod !== null) {
              $last = $currentperiod[0]->lastperiod;
              $next = $currentperiod[0]->nextperiod;
              echo "<option value=\"$last\">$last</option>";
              echo "<option value=\"$next\">$next</option>";
            }
            ?>
          </select>
				</td>
      </tr>
      <tr>
				<td>Online Type</td>
				<td>
          <select tabindex="3" id="onlinetype" name="onlinetype">
            <option value="O" selected="">Online</option>
            <option value="M">Manual</option>
          </select>
				</td>
      </tr>
      <tr>
				<td>Delivery Option</td>
				<td>
          <select tabindex="3" id="ship" name="ship">
            <option value="1" selected="">Pick Up</option>
            <option value="2">Ship To</option>
            <option value="3">Hold</option>
            <option value="4">Dont Ship</option>
          </select>
				</td>
      </tr>
      <tr>
				<td>From Warehouse</td>
				<td>
          <select tabindex="4" id="whcd" name="whcd">
            <?php 
             if($listWh !== null) {
               foreach($listWh as $dtax) {
                 echo "<option value=\"$dtax->loccd\">$dtax->fullnm</option>";
               }
             }
            ?>
          </select>
				</td>
      </tr>
      <tr>
       <td><input value="<< Kembali" type="button" class="btn btn-warning span20" onclick="All.back_to_form(' .nextForm1',' .mainForm')"/></td>
       <td><input value="Simpan Register" type="button" class="btn btn-primary span20" onclick="saveRegister()" /></td>
      </tr>
    </table>    
</form>
<script>
  
</script>