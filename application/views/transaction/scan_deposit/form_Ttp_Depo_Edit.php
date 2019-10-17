<!--<html>
<body onLoad="document.form.value.focus()">

<body>

    <div class="row-fluid">
        <div class="span12">
            <span id="formawal">-->
            adadasd
                <form class="form-horizontal" method="post" id="frm_ttp" name="frm_ttp">
                    <fieldset>
                        <div class="control-group">
                            <?php
                      foreach($stk as $data) {
                          $dtsubname = $data->fullnm;
                          $dtuplinesub = $data->uplinesub;
                          $dtprice = $data->pricecode;
                      }
                      ?>
                            <table width="100%">
                                <tr>
                                    <td width="50%">
                                        <table width="100%">
                                            <tr>
                                                <td class="form_title_left">Sub Stockists Code&nbsp;</td>
                                                <td>
                                                    <input type="text" class="span12 typeahead" id="substockistcode"
                                                        name="substockistcode" value="<?php echo $sc_dfno; ?>"
                                                        onchange="Sales.get_sc_info()" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="form_title_left">Sub Stockist Name&nbsp;</td>
                                                <td><input type="text" readonly="yes" class="span12 typeahead"
                                                        id="substkname" name="substkname"
                                                        value="<?php echo $nama_penuh; ?>" /></td>
                                            </tr>
                                            <tr>
                                                <td class="form_title_left">C/O Sub Stockist Code&nbsp;</td>
                                                <td>
                                                    <input type="text" readonly="yes" class="span12 typeahead"
                                                        id="uplinesub" name="uplinesub" value="<?php echo $user; ?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="form_title_left">C/O Sub Stockist Name&nbsp;</td>
                                                <td>
                                                    <input type="text" readonly="yes" class="span12 typeahead"
                                                        id="uplinesubnm" name="uplinesubnm"
                                                        value="<?php echo $dtsubname; ?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="form_title_left">Member Code&nbsp;</td>
                                                <td>
                                                    <input type="text" readonly="yes" class="span12 typeahead"
                                                        id="distributorcode" name="distributorcode"
                                                        value="<?php echo $dfno; ?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="form_title_left">Member Name&nbsp;</td>
                                                <td>
                                                    <input type="text" readonly="yes" class="span12 typeahead"
                                                        id="distributorname" name="distributorname"
                                                        value="<?php echo $fullnm; ?>" />
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="50%">
                                        <table width="100%">
                                            <tr>
                                                <td class="form_title">Trx No.&nbsp;</td>
                                                <td>
                                                    <input type="text" readonly="yes" class="span12 typeahead"
                                                        id="trxno" name="trxno" value="<?php echo $trcd; ?>" />
                                                    <input type="hidden" readonly="yes" class="span12 typeahead"
                                                        id="depost" name="depost" value="<?php echo $deposit; ?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="form_title">Trx Date.&nbsp;</td>
                                                <td>
                                                    <input type="text" readonly="yes" class="span12 typeahead"
                                                        id="trxdate" name="trxdate" value="<?php echo $trdt;?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="form_title">Price Code&nbsp;</td>
                                                <td>
                                                    <input type="text" readonly="yes" class="span4 typeahead"
                                                        id="pricecode" name="pricecode"
                                                        value="<?php echo $dtprice; ?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="form_title">Bonus Period&nbsp;</td>
                                                <td>
                                                    <input type="text" readonly="yes" class="span12 typeahead" id="bns"
                                                        name="bns" value="<?php echo $bns;?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="form_title">Order No&nbsp;</td>
                                                <td>
                                                    <input type="text" class="span12 typeahead" id="orderno"
                                                        name="orderno" readonly="yes" value="<?php echo $orderno; ?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="form_title">Remarks&nbsp;</td>
                                                <td>
                                                    <input type="text" class="span12 typeahead" id="remark"
                                                        name="remark" />
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>


                            <br />
                            <!--<span id="show_form"></span> -->
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr bgcolor="#f4f4f4">
                                        <th width="13%">Code</th>
                                        <th width="35%">Product Name</th>
                                        <th width="10%">Qty</th>
                                        <th width="15%">Price</th>
                                        <th width="15%">BV</th>
                                        <th width="28%">Total</th>

                                        <th>Act</th>
                                    </tr>
                                </thead>
                                <tbody id="dataPrd">
                                    <?php
                          $no=0;
                          if(count($LIST_DETAIL) > 0 and $LIST_DETAIL!="woop"):
                              foreach($LIST_DETAIL as $row):
                                  $no++;?>
                                    <tr id="<?php echo $no;?>">
                                        <td><input type=text readonly class=span12 value="<?php echo $row->prdcd;?>">
                                        </td>
                                        <td><input readonly=yes type=text class=span12
                                                value="<?php echo $row->prdnm;?>"> </td>
                                        <td><input readonly=yes type=text class=span12
                                                value="<?php echo number_format($row->qtyord);?>"></td>
                                        <td><input readonly=yes type=text class=span12
                                                value="<?php echo number_format($row->dp);?>"></td>
                                        <td><input readonly=yes type=text class=span12
                                                value="<?php echo number_format($row->bv);?>"></td>
                                        <td><input readonly=yes style="text-align:right;" type=text class=span12
                                                value="<?php echo number_format($row->dp*$row->qtyord);?>"></td>
                                        <td>-</td>

                                    </tr>
                                    <?php endforeach;
                          endif;?>

                                </tbody>
                                <tbody id="SS">
                                    <tr>
                                        <td align="right"></td>
                                        <td colspan="3" align="right">T O T A L</td>
                                        <td>&nbsp;
                                        </td>
                                        <td>
                                            <input readonly="yes" type="text" style="text-align:right;"
                                                class="span12 typeahead" id="total_all" name="total_all"
                                                value="<?php echo number_format($tdp); ?>" />
                                            <input type="hidden" id="total_all_real" name="total_all_real" />
                                            <input type="hidden" id="total_all_real_bv" name="total_all_real_bv" />
                                            <input type="hidden" id="totals_all_bv" name="totals_all_bv" />
                                        </td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <input type="hidden" id="action" name="action"
                                        value="<?php echo $form_action; ?>" />
                                    <input type="hidden" name="amount" id="amount" value="1" />
                                    <input type="hidden" name="tabidx" id="tabidx" value="1" />
                                    <input type="hidden" name="amt_record" id="amt_record" value="0" />
                                    <input type="hidden" name="jenis" value="pv" />
                                </tbody>
                            </table>


                            <table width="100%" align="left" class="table table-striped table-bordered">
                                <thead>
                                    <tr bgcolor="#f4f4f4">
                                        <th width="30%">Payment Type</th>
                                        <th width="30%">Ref No</th>

                                        <th width="20%">Nominal</th>
                                        <th width="20%">Act</th>
                                    </tr>
                                </thead>
                                <tbody id="list_pay">

                                    <?php
                          $no=0;$sum=0;
                          if(count($LIST_PAYMENT) > 0 and $LIST_PAYMENT!="woop"):
                              foreach($LIST_PAYMENT as $row):
                                  $no++;
                                  if($row->paytype=='08')
                                  {
                                      $desc='Voucher Cash';
                                  }
                                  else if($row->paytype=='01')
                                  {
                                      $desc='Cash';
                                  }
                                  else if($row->paytype=='10')
                                  {
                                      $desc='Product Voucher';
                                  }

                                    $sum=$sum+$row->payamt;
                                  ?>
                                    <tr id="<?php echo $no;?>">
                                        <td><?php echo isset($desc) ? $desc :'' ?></td>
                                        <td><?php echo isset($row->docno) ? $row->docno :'' ?></td>
                                        <td><input readonly=yes style="text-align:right;" type=text class=span12
                                                value="<?php echo number_format($row->payamt);?>"></td>
                                        <td>-</td>

                                    </tr>
                                    <?php endforeach;
                          endif;?>



                                </tbody>
                                <tbody>
                                    <tr>
                                        <td colspan="2" align="right">TOTAL PAYMENT&nbsp;&nbsp;</td>
                                        <td>
                                            <input readonly="yes" style="text-align:right;" type="text"
                                                id="tot_all_payment" name="tot_all_payment"
                                                value="<?php echo number_format($sum);?>" class="span12" />
                                            <input type="hidden" id="tot_all_payment_real" name="tot_all_payment_real"
                                                value="0" class="span12" />
                                        </td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="right">CHANGE&nbsp;&nbsp;</td>
                                        <td>
                                            <input readonly="yes" style="text-align:right;" type="text" id="change"
                                                name="change" class="span12"
                                                value="<?php echo number_format($sum - $tdp);?>" />
                                            <input type="hidden" id="change_real" name="change_real" value="0"
                                                class="span12" />
                                        </td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td valign="top" align="right" colspan="2">
                                            NOTES
                                        </td>
                                        <td colspan="2" valign="top">
                                            <textarea id="notes" name="notes" class="span16"></textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <br />
                            <!-- <input type="button" class="btn btn-success span14" name="submit" value="Save" id="save"/>
                            <input type="button" class="btn btn-warning span6" name="back" value="Kembali"
                                onclick="All.back_to_form(' .nextForm1', ' .mainForm')" />
                            <?php if($status==1)
                                echo'<input type="button" class="btn btn-warning span6" name="back" value="Kembali"
                                    onclick="All.back_to_form(\' .nextForm2\',\' .nextForm1\')" />';
                                    if($status==1) {
                                        echo "<input type='button' class='btn btn-danger span6' name='hapus' onclick=\"Stockist.hapusTTPvchDeposit('$trcd')\" value='Hapus Transaksi' />";
                                    }
                            ?>
                            <!-- end control-group -->
                    </fieldset>
                </form><!--
            </span>
            <div id="result"></div>
        </div>
    </div>
    </div>
</body>
</html>-->
