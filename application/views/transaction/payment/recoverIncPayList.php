
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> -->

<?php
if($listKw !== null) {
?>
<form action="<?php echo base_url('payment/print'); ?>" method="POST" target="_BLANK">
<input type="hidden" name="tipe" id="tipe" value="<?php echo $trxtype; ?>" />
<table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable" align="center">
<!-- <table id="example" class="display" style="width:100%"> -->
  <thead>
    <tr>
      <th colspan="8">List KW</th>
    </tr>
    <tr>
      <th><input type="checkbox" name="checkall" onclick="checkAll(this);" /></th>
      <th>No</th>
      <th>No KW</th>
      <th>No Register</th>
      <th>Stokist/Member</th>
      
      <th>Create By</th>
      <th>Create Date</th>
      <!-- <th>Total DP</th> -->
      <!-- <th>Total BV</th> 
      <th>Action</th>-->
    </tr>
  </thead>
  <tbody>
  <?php
    $ix = 1;

      foreach($listKw as $dtakw) {
      
        if($dtakw->receiptno == ""){
          $color="RED";
          $color2="WHITE";
        }else{
          $color="";
          $color2="";
        }
        //$urlPrint = base_url('payment/print/'.$dtakw->tipe.'/'.$dtakw->receiptno.'');
        echo "<tr>";
        echo "<td align=center><input type=checkbox name=cek[] class=update-bonus value=\"".$dtakw->receiptno."\" /></td>";
        echo "<td align=center>$ix</td>";
        echo "<td align=center><a id=\"$dtakw->receiptno\" href=\"#\" onclick=\"All.ajaxShowDetailonNextForm('payment/receipt/detail/$dtakw->receiptno')\">$dtakw->receiptno</td>";
        echo "<td align=center>$dtakw->registerno</td>";
        echo "<td align=center>$dtakw->sc_dfno</td>";
        echo "<td align=center>$dtakw->createdt</td>";
        echo "<td align=center>$dtakw->createnm</td>";
        /* echo "<td align=center>".number_format($dtakw->tdp, 0, ",",".")."</td>";
        echo "<td align=center>".number_format($dtakw->tbv, 0, ",",".")."</td>"; */
        //echo "<td align=center><input type=\"submit\" formaction=\"\" class=\"btn btn-mini btn-warning\" formtarget=\"_BLANK\" value=\"Detail KW\" /></td>";
        echo "</tr>";
        $ix++;
    }
  ?>
  <tr>
    <td colspan="2">
      <input type="submit" class="btn btn-mini btn-success"  value="Print KW" />
    </td>
    <td colspan="9">&nbsp;</td>
  </tr>
  </tbody>
</table>  
</form>
<?php
}

if($listInv !== null) {
?>
<form method="POST" action="<?php echo base_url('reseller/inv/print'); ?>" target="_BLANK">
<input type="hidden" id="regnox" name="regnox" value="<?php echo $paramValue; ?>" />
<table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable" align="center">
  <thead>
    <tr>
      <th colspan="4">List Transaksi</th>
    </tr>
    <tr>
      <th><input type="checkbox" name="checkall" onclick="checkAll(this);" /></th> 
      <th>No</th>
      <th>No Trx/Invoice</th>
      <th>Total DP</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $no = 1;
      $tdp=0;
    
      foreach($listInv as $dtax) {
        
        //<a onclick=All.ajaxShowDetailonNextForm('".$url."')>$dtax->trcd</a>
        echo "<tr>";
        echo "<td align=center><input type=checkbox name=cek[] class=\"checkInvS\" value=\"".$dtax->invoiceno."\" onchange=\"handleChange()\" /></td>";
        echo "<td align=right>$no</td>";
        echo "<td align=center>$dtax->invoiceno</td>";
        echo "<td align=right>".number_format($dtax->total_dp, 0, ",",".")."</td>";
        echo "</tr>";
        $no++;
        $tdp += $dtax->total_dp;
    }
    ?>
    <tr>
      <td colspan="3" align="center">T O T A L</td>
      <td align="right"><?php echo number_format($tdp, 0, ",","."); ?></td>
    </tr>
  </tbody>
</table>
<input type="button" name="calculate" class="btn btn-mini btn-primary" value="Calculate CN/MS/INV" onclick="calculateIncPay()" />
<input type="button" name="create KW" class="btn btn-mini btn-primary" value="Save" onclick="createKW()" />
<input type="hidden" id="tipe_trx_val" name="tipe_trx_val" value="<?php echo $tipe_trx; ?>">
<table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable" align="center">
  <thead>
    <tr>
      <th colspan="4">List Incoming Payment</th>
    </tr>
    <tr>
      <th>Type</th>
      <th>Payment Type</th>
      <th>Ref. No</th>
      <th>Amount</th>
    </tr>
  </thead>
  <tbody id="listIncPayPil">
    <?php
  if($listIncPay !== null) {
      foreach($listIncPay as $payx) {
          echo "<tr>";
          echo "<td align=right>$payx->paytype</td>";
          echo "<td align=center>$payx->pay_desc</td>";
          echo "<td align=center>$payx->docno</td>";
          echo "<td align=right>".number_format($payx->payamt, 0, ",",".")."</td>";
          echo "</tr>";
          $tdp += $dtax->total_dp;
      }
    }
    ?>
  </tbody>
  </table>  
</form>
<?php
}
?>
<script type="text/javascript">
/* $(document).ready(function() {
    $('#example').DataTable();
} ); */

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
    handleChange()
}

function handleChange() {
  
  yourArray = [];
  myCharKirim = "";
  $("input:checkbox.checkInvS:checked").each(function(){
    isi = $(this).val();
    yourArray.push(isi);

    myCharKirim += "'" + isi + "',";
  });

  //calculateIncPay();

  console.log({myCharKirim});

}

function calculateIncPay() {
  if(myCharKirim.length > 0) {
    let regnox = $(All.get_active_tab() + " #regnox").val();
    console.log({regnox});
    All.set_disable_button();
    $.ajax({
        url: All.get_url('payment/receipt/findIncPayByInv'),
        type: 'POST',
        dataType: "json",
        data: {tipe_trx: 1, listInv: myCharKirim, regnox: regnox},
        success:
        function(data){
            All.set_enable_button();
            const {arrayData, response} = data
            if(response === "true") {
              $(All.get_active_tab() + " #listIncPayPil").html(null);
              let xhtml = "";
              
              $.each(arrayData, function (key, value) {
                xhtml += "<tr>";
                xhtml += "<td align=center>"+value.paytype+"</td>";
                xhtml += "<td align=center>"+value.pay_desc+"</td>";
                xhtml += "<td align=center>"+value.docno+"</td>";
                xhtml += "<td align=right>"+All.num(parseInt(value.payamt))+"</td>";
                xhtml += "</tr>";
              });  

              $(All.get_active_tab() + " #listIncPayPil").append(xhtml);
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(thrownError + ':' +xhr.status);
          All.set_enable_button();
        }
    });
  } else {
    alert("Minimal 1 transaksi harus dipilih..");
  }
}

function createKW() {
    if(myCharKirim.length > 0) {
      let regnox = $(All.get_active_tab() + " #regnox").val();
      console.log({regnox});

      let tipe_val= document.getElementById("tipe_trx_val").value

      All.set_disable_button();
      $.ajax({
          url: All.get_url('payment/receipt/save'),
          type: 'POST',
          dataType: "json",
          data: {tipe_trx: tipe_val, listInv: myCharKirim, regnox: regnox},
          success:
          function(data){
              All.set_enable_button();
              const {arrayData, response} = data
              if(response === "true") {
                All.ajaxFormPost('formPaymentReceipt','payment/receipt/findregister');
              }
              
          },
          error: function (xhr, ajaxOptions, thrownError) {
            alert(thrownError + ':' +xhr.status);
            All.set_enable_button();
          }
      });
    } else {
      alert("Minimal 1 transaksi harus dipilih..");
    }
  }
</script>

	
