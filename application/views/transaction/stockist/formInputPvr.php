<style>
  .form_title {
    width: 14%;
    text-align: right;
  }

  .form_title1 {
    width: 14%;
    text-align: right;
    font-style: italic;

  }

  .form_title_left {
    width: 15%;
    text-align: left;
  }

  .kanan {
    text-align: right;
    margin-bottom: 1px;
  }
</style>


          <form class="form-horizontal" method="post" id="frm_ttp" name="frm_ttp">
            <fieldset>
              <div class="control-group">

                <table width="100%"class="table table-striped table-bordered">
                        <tr>
                            <thead>
                              <th colspan="4"><?php echo $form_head; ?></th>
                            </thead>  
                        </tr>
                        <tr>
                          <td class="form_title_left">&nbsp;ID Member&nbsp;</td>
                          <td>
                            <input tabindex="1" type="text" class="span20 typeahead" id="distributorcode" name="distributorcode"
                            onchange="All.getFullNameByID(this.value,'api/member/check','#distributorname')" />
                              <input type="hidden" readonly="yes" class="span20 typeahead" id="loccd"
                              name="loccd" value="<?php echo $stockist; ?>" />
                              <input type="hidden" readonly="yes" class="span20 typeahead" id="sctype"
                              name="sctype" value="<?php echo $sctype; ?>" />
                              
                              <input type="hidden" readonly="yes" class="span20 typeahead" id="pricecode" name="pricecode"
                              value="<?php echo $pricecode; ?>" />
                          </td>
                          <td class="form_title_left">&nbsp;Bonus Period&nbsp;</td>
                          <td>
                            <select tabindex="2" id="bnsperiod" name="bnsperiod" class="span5 typeahead">
                              <?php
                    					$opts = 2;

                    					////Array of months
                    					$m = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");

                    					////Get starting year and month
                    					$sm = date('n', strtotime("-1 Months"));
                    					$sy = date('Y', strtotime("-1 Months"));
                    					foreach($currentperiod as $dt)
                    					{
                                  $lastmonth = date('n', strtotime($dt->lastperiod));
                                  $last_display_month = date('m', strtotime($dt->lastperiod));
                                  $lastyear = date('Y', strtotime($dt->lastperiod));

                        					$nextmonth = date('n', strtotime($dt->nextperiod));
                                  $next_display_month = date('m', strtotime($dt->nextperiod));
                        					$nextyear = date('Y', strtotime($dt->nextperiod));
                        					for($i=0;$i < $opts;$i++)
                        					{
                            					$test = $sm - 1;
                            					////Check for current month and year so we can select it
                            					if($lastmonth == $sm)
                            					{
                            						echo "<option value='".$last_display_month."/".$lastyear."' selected='selected'>".$m[$lastmonth - 1]." ".$lastyear."</option>\n";
                            					}
                            					else
                            					{
                            						echo "<option value='".$next_display_month."/".$nextyear."' >".$m[$nextmonth - 1]." ".$nextyear."</option>\n";
                            					}
                        					//// Fix counts when we span years
                            					if($sm == 12)
                            					{
                            						$sm = 1;
                            						$sy++;
                            					}
                            					else
                            					{
                            						$sm++;
                            					}
                    					   }
                    					}
                					?>
                            </select>
                          </td>
                        </tr>
                        <tr>
                          <td class="form_title_left">&nbsp;Nama Member&nbsp;</td>
                          <td>
                            <input type="text" readonly="yes" class="span20 typeahead" id="distributorname"
                              name="distributorname" />
                          </td>
                          <td class="form_title_left">&nbsp;No/Kode Voucher&nbsp;</td>
                          <td>
                            <input tabindex="3" type="text" style="width: 200px;" id="vchno" name="vch_no"
                              placeholder="Wajib Di isi" onchange="Stockist.get_pvr_info('10')" />
                              <input type="button" value="Tambah Voucher" onclick="Stockist.get_pvr_info('10')" class="btn btn-mini btn-success">
                          </td>
                        </tr>
                        
                       
                      </table>
                   


                <!--<span id="show_form"></span> -->
                <table width="100%" class="table table-striped table-bordered"> <!-- voucher table -->
                  <thead>
                    <tr bgcolor="#f4f4f4">
                       <th colspan="5">List Penggunaan Voucher</th>       
                    </tr>          
                    <tr bgcolor="#f4f4f4">
                      <th width="15%">ID Member</th>
                      <th>Nama Member</th>
                      <th width="15%">Voucher</th>
                      <!-- <th width="15%">Nilai Voucher</th> -->
                      <!-- <th width="15%">BV</th> -->
                      <th  width="15%">Total</th>

                      <th width="5%">Act</th>
                    </tr>
                  </thead>
                  <tbody id="dataVch">

                  </tbody>
                  <tbody id="SS">
                    <tr>
                      <!-- <td align="right"><input type="button" class="btn btn-success" name="new_record" id="new_record"
                          value="Add Voucher" onclick="" /></td> -->
                      <td colspan="3" align="right">T O T A L</td>
                      <td>
                        <input type="text" style="text-align:right;" class="span12 typeahead"
                          id="total_voucher" readonly name="total_all" onclick='Stockist.sum_amount()' />
                      </td>
                      <td>&nbsp;</td>
                    </tr>
                    <input type="hidden" id="action" name="action" value="<?php echo base_url(); ?>" />
                    <input type="hidden" name="amount" id="amount" value="1" />
                    <input type="hidden" name="tabidx" id="tabidx" value="4" />
                    <input type="hidden" name="amt_record" id="amt_record" value="0" />
                    <input type="hidden" name="jenis_bayar" id="jenis_bayar" value="pv" />
                    <input type="hidden" name="xhd" id="xhd" value="0">
                    <input type="hidden" name="isPrdpromo" id="isPrdpromo" value="0">
                  </tbody>
                </table> <!-- voucher table -->

                <table width="100%" class="table table-striped table-bordered"> <!-- product table -->
                  <thead>
                  <tr bgcolor="#f4f4f4">
                       <th colspan="6">List Pembelanjaan Produk</th>       
                    </tr>
                    <tr bgcolor="#f4f4f4">
                      <th width="15%">Kode Produk</th>
                      <th>Nama Produk</th>
                      <th width="10%">Qty</th>
                      <th width="15%">Harga</th>
                      <!-- <th width="15%">BV</th> -->
                      <th width="15%">Total</th>

                      <th width="5%">Act</th>
                    </tr>
                  </thead>
                  <tbody id="dataPrd">

                  </tbody>
                  <tbody id="SS">
                    <tr>
                      <td align="right"><input type="button" class="btn btn-warning span20" name="new_record" id="new_record"
                          value="Tambah Produk" onclick="Stockist.add_new_sales_row()" /></td>
                      <td colspan="3" align="right">T O T A L</td>
                      <td>
                        <input type="text" style="text-align:right;" class="span12 typeahead"
                          id="total_all" readonly name="total_all" onclick='Stockist.sum_product()' />
                        <input type="hidden" id="total_all_real" name="total_all_real" />
                        <input type="hidden" id="total_all_real_bv" name="total_all_real_bv" />
                        <input type="hidden" id="totals_all_bv" name="totals_all_bv" />
                      </td>
                      <td>&nbsp;</td>
                    </tr>
                    
                  </tbody>
                </table> <!-- product table -->
                <!--
                <table align="center" class="table table-striped table-bordered" width="100%">
                  <tr>
                    <th colspan="4">TRANSACTION AMOUNT and PAYMENT DETAIL </td>
                  </tr>
                  <tr>
                    <td>Sisa Pembayaran </td>
                    <td width="20%">
                      <input readonly="yes" style="text-align:right;" type="text" class="span8" id="sisa_payment"
                        name="sisa_payment" value="0" />
                        <input type="hidden" name="sisa_payment_real" id="sisa_payment_real">
                    </td>
                    <td>Nominal&nbsp;&nbsp;</td>
                    <td>
                      <input style="text-align:right;" type="text" class="span8" id="pay_nominal" name="pay_nominal"
                        value="0" />
                        <input type="hidden" name="pay_nominal_real" id="pay_nominal_real">
                    </td>
                  </tr>

                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>Voucher No</td>
                    <td>
                      <input type="hidden" style="text-align:right;" class="span8" id="vchnoo1"
                        name="vchnoo1" />
                      <input type="text" style="text-align:right;" class="span8" id="vchnoo" name="vchnoo"
                        onchange="SalesPVR.get_pvrr_info()" disabled="yes" />
                    </td>
                  </tr>
                  <tr id="vchno" style="visibility: collapse;">
                          <td colspan="2"></td>

                              <td >Voucher No</td>
                              <td >
                                <input type="text" style="text-align:right;" class="span8 typeahead" id="vchnoo"  name="vchnoo" onblur="SalesPVR.get_pvrr_info()"/>
                              </td>
                          </tr>

                  <tr>
                    <td colspan="3" class="form_title1">
                      <?php echo "<font color=red> *Transaksi ini tidak akan mendapatkan BV</font>";?>
                    </td>
                    <td>
                      <input type="hidden" class="span12 typeahead" id="paynominal_real" name="paynominal_real" />
                      <input type="button" class="btn btn-success" value="Add Payment"
                        onclick="SalesPVR.add_payment_input_sales()" id="xx" />
                      <input type="hidden" id="pay_record" name="pay_record" value="0" />
                      <input type="hidden" id="restrict_pay" name="restrict_pay" value="" />
                      <input type="hidden" id="cash_sum" name="cash_sum" value="0" />
                      <input type="hidden" id="noncash_sum" name="noncash_sum" value="0" />
                      <input type="hidden" id="change_over_cash" name="change_over_cash" value="0" />

                    </td>
                  </tr>
                </table> -->
                <table width="100%" align="left" class="table table-striped table-bordered">
                  <thead>
                    <tr bgcolor="#f4f4f4">
                      <th colspan="2">Pembayaran</th>
                      <th width="15%">Nominal</th>
                      <th width="5%">&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody id="list_pay">
                  </tbody>
                  <tbody>
                    <tr>
                      <td colspan="2" align="right">TOTAL VOUCHER&nbsp;&nbsp;</td>
                      <td>
                        <input readonly="yes" style="text-align:right;" type="text" id="tot_all_payment"
                          name="tot_all_payment" class="span12" />
                        <input type="hidden" id="tot_all_payment_real" name="tot_all_payment_real" value="0"
                          class="span12" />
                      </td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="2" align="right">SISA CASH HARUS DIBAYAR&nbsp;&nbsp;</td>
                      <td>
                        <input readonly="yes" style="text-align:right;" type="text" id="sisa_cash" name="sisa_cash"
                          class="span12" />
                        <input type="hidden" id="sisa_cash_real" name="sisa_cash_real" value="0" class="span12" />
                      </td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td width="15%">
                      <input value="<< Kembali" style="width: 180px; height: 25px" type="button" class="btn btn-warning" onclick="All.back_to_form(' .nextForm1',' .mainForm')"/>
                      </td>
                      <td>
                      <input type="button" style="width: 600px; height: 25px" class="btn btn-primary" name="submit" value="Simpan Transaksi" id="save"
                          onclick="simpanPVRSales()" />
                      </td>
                      <td colspan="2"></td>
                    </tr>
                  </tbody>
                </table>
                <br />
                <!-- <input type="button" class="btn btn-success span14" name="submit" value="Save" id="save"/> -->
                
                <!--</div> -->
              </div> <!-- end control-group -->
            </fieldset>
          </form>
        


<script>
  $(document).ready(function () {
    $("#payn_type").change(function () {
      if ($("#payn_type").val() == "10") {
        $("#vchnoo").removeAttr('disabled');
      } else {
        $("#vchnoo").val('');
        $("#vchnoo").attr('disabled', 'disabled');
      }
    });
  });

  function simpanPVRSales() {

    All.set_disable_button();

      //$("#result").html('<center><img src=http://www.k-linkmember.co.id/substockist/images/ajax-loader.gif ></center>');
      $.post(All.get_url("sales/pvr2/save"), $(All.get_active_tab() +" #frm_ttp").serialize(), function (hasil) {
        All.set_enable_button();
        if(hasil.response == "true") {
          var datax =hasil.data;
          alert("Transaksi PVR berhasil, no transaksi : " +datax.trcd);
          All.back_to_form(" .nextForm1", " .mainForm");
          
        } else {
          alert(hasil.message);
        }
      },"json").fail(function () {
        alert("Error requesting page");
        All.set_enable_button();
        //$("#tessss").html("<i class=icon-edit></i> Save Failed");

      });
  }

  function backToMainForm() {
    $("#divPrevSales").html(null);
    $("#viewList").html(null);
    $("#sales_content").show();
  }

  function new_register() {
    $("input[type=button]").attr("disabled", "disabled");

    //$("#result").html('<center><img src=http://www.k-linkmember.co.id/substockist/images/ajax-loader.gif ></center>');
    $.post(All.get_url("transaction/newRegister/"), $("#frm_ttp").serialize(), function (data) {
      $("input[type=button]").removeAttr("disabled");
      alert(data.message);
      if(data.response == "true") {
        var urlx = All.get_url("transaction/input_pvr");
        window.location.href = urlx;
      }


    },"json").fail(function () {
      alert("Error requesting page");
      $("input[type=button]").removeAttr("disabled");
      //$("#tessss").html("<i class=icon-edit></i> Save Failed");

    });
  }
</script>
