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


          <form class="form-horizontal" method="post" id="frm_ttp_vchcashKhusus" name="frm_ttp_vchcashKhusus">
            <fieldset>
              <div class="control-group">

                <table width="100%"class="table table-striped table-bordered">
                        <tr>
                            <thead>
                              <th colspan="4"><?php echo $form_head; ?></th>
                            </thead>  
                        </tr>
                        <tr>
                          <td class="form_title_left">&nbsp;Tipe Voucher&nbsp;</td>
                          <td>
                            <select tabindex="1" id="tipe_vchpromo" name="tipe_vchpromo" class="span20 typeahead">
                              <!--<option value="V">Voucher Cash Reguler/Biasa</option>-->
                              <?php
                                foreach($tipe_vchpromo as $dtavch) {
                                    echo "<option value=\"$dtavch->prefix_vch\">$dtavch->nama_promo</option>";
                                }
                              ?>
                            </select>  
                              
                              <input type="hidden" readonly="yes" class="span20 typeahead" id="pricecode" name="pricecode"
                              value="<?php echo $pricecode; ?>" />
                          </td>
                          <td colspan="2">&nbsp;</td>
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
                    					  echo showBnsPeriodV2($stockist, $currentperiod);
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
                              placeholder="Wajib Di isi" onchange="Stockist.get_vch_info('08')" />
                              <input type="button" value="Tambah Voucher" onclick="Stockist.get_vch_info('08')" class="btn btn-mini btn-success">
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
                    <input type="hidden" id="jenis_bayar" name="jenis_bayar" value="cv" />
                    <input type="hidden" id="xhd" name="xhd" value="0">
                    <input type="hidden" id="isPrdpromo" name="isPrdpromo" value="0">
                    <input value="<?php echo $jum_rec; ?>" type="hidden" id="rec" name="rec" />
                    <input value="1" type="hidden" id="ins" name="ins" />
                  </tbody>
                </table> <!-- voucher table -->

                <table width="100%" class="table table-striped table-bordered"> <!-- product table -->
                  <thead>
                  <tr bgcolor="#f4f4f4">
                       <th colspan="9">List Pembelanjaan Produk</th>       
                    </tr>
                    <tr bgcolor="#f4f4f4">
                      <!-- <th width="15%">Kode Produk</th>
                      <th>Nama Produk</th>
                      <th width="8%">Qty</th>
                      <th width="15%">Harga</th>
                      <th width="8%">BV</th>
                      <th width="15%">Total</th> -->

                      

                      <th width="12%">Kode Produk</th>
                      <th>Nama produk</th>
                      <th width="5%">Qty</th>
                      <th width="6%">BV</th>
                      <th width="10%">Harga</th>
                      <th width="8%">Sub Total BV</th>
                      <th width="15%">Sub Total Harga</th>
                      <th width="5%">Act</th>
                    </tr>
                  </thead>
                  <tbody id="addPrd">

                  </tbody>
                  <tbody id="SS">
                    <tr>
                      <td align="right"><input type="button" class="btn btn-info span20" name="new_record" id="new_record"
                          value="Tambah Produk" onclick="Stockist.addNewRecordPrdVchCashKhusus()" /></td>
                      <td colspan="4" align="right">T O T A L</td>
                      <td>
                      <input type="text" style="text-align:right;" class="span12 typeahead"
                          id="total_all_bv" readonly name="total_all_bv" value="" />
                        <input type="hidden" id="total_all_dp_real" name="total_all_dp_real" value="" />
                        <input type="hidden" id="total_all_bv_real" name="total_all_bv_real" value="" />
                        
                      </td>
                      <td><input type="text" style="text-align:right;" class="span12 typeahead"
                          id="total_all_dp" readonly name="total_all_dp" value="" /></td>
                    </tr>
                    
                  </tbody>
                </table> 
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
                      <td colspan="2" align="right">TOTAL VOUCHER CASH&nbsp;&nbsp;</td>
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
                          onclick="save_pvr_sales()" />
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

  function save_pvr_sales() {

    All.set_disable_button();

      //$("#result").html('<center><img src=http://www.k-linkmember.co.id/substockist/images/ajax-loader.gif ></center>');
      $.post(All.get_url("sales/vcashKhusus/save"), $(All.get_active_tab() + " #frm_ttp_vchcashKhusus").serialize(), function (hasil) {
        All.set_enable_button();
        if(hasil.response == "true") {
          var datax =hasil.data;
          alert("Transaksi PVR berhasil, no transaksi : " +datax.trcd);
          All.reload_page('sales/sub/input/vcashKhusus');
        } else {
          alert(hasil.message);
        }
      },"json").fail(function () {
        alert("Error requesting page");
        All.set_enable_button();
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
