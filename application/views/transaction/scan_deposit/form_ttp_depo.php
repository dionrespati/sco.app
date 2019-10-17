<form class="form-horizontal" method="post" id="frm_ttp" name="frm_ttp">
    <fieldset>
        <div class="control-group">
            <?php
                    foreach($stk as $data)
                    {
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
                                <td class="form_title_left">Sub Stockist Code&nbsp;</td>
                                <td>
                                    <input type="text" class="span12 typeahead" id="substockistcode"
                                        name="substockistcode" value="<?php echo $user; ?>"
                                        onchange="Sales.get_sc_info()" />
                                </td>
                            </tr>
                            <tr>
                                <td class="form_title_left">Sub Stockist Name&nbsp;</td>
                                <td><input type="text" readonly="yes" class="span12 typeahead" id="substkname"
                                        name="substkname" value="<?php echo $dtsubname; ?>" /></td>
                            </tr>
                            <tr>
                                <td class="form_title_left">C/O Sub Stockist Code&nbsp;</td>
                                <td>
                                    <input type="text" readonly="yes" class="span12 typeahead" id="uplinesub"
                                        name="uplinesub" value="<?php echo $user; ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td class="form_title_left">C/O Sub Stockist Name&nbsp;</td>
                                <td>
                                    <input type="text" readonly="yes" class="span12 typeahead" id="uplinesubnm"
                                        name="uplinesubnm" value="<?php echo $dtsubname; ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td class="form_title_left">Member Code&nbsp;</td>
                                <td>
                                    <input type="text" class="span12 typeahead" id="distributorcode"
<<<<<<< HEAD
                                        name="distributorcode" onchange="Sales.get_distributor_info()" />
=======
                                        name="distributorcode" onchange="All.getFullNameByID(this.value,'api/member/check','#distributorname')" />
>>>>>>> c62b4866924da804e9d15866a8e5f233ab009db3
                                </td>
                            </tr>
                            <tr>
                                <td class="form_title_left">Member Name&nbsp;</td>
                                <td>
                                    <input type="text" readonly="yes" class="span12 typeahead" id="distributorname"
                                        name="distributorname" />
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="50%">
                        <table width="100%">
                            <tr>
                                <td class="form_title">Trx No.&nbsp;</td>
                                <td>
                                    <input type="text" readonly="yes" class="span12 typeahead" id="trxno"
                                        name="trxno" value=">> Auto <<" />
                                </td>
                            </tr>
                            <tr>
                                <td class="form_title">Trx Date.&nbsp;</td>
                                <td>
                                    <input type="text" readonly="yes" class="span12 typeahead" id="trxdate"
                                        name="trxdate" value="<?php echo $dateNow;?>" />
                                </td>
                            </tr>
                            <tr>
                                <td class="form_title">Price Code&nbsp;</td>
                                <td>
                                    <input type="text" readonly="yes" class="span4 typeahead" id="pricecode"
                                        name="pricecode" value="<?php echo $dtprice; ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td class="form_title">Bonus Period&nbsp;</td>
                                <td>
                                    <select id="bnsperiod" name="bnsperiod" class="span6 typeahead">
                                        <?php
                                                $opts = 2;

                                                ////Array of months
                                                $m = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");

                                                ////Get starting year and month
                                                $sm = date('n', strtotime("-1 Months"));
                                                $sy = date('Y', strtotime("-1 Months"));
                                                foreach($currentperiod as $dt)
                                                {
                                                    $lastmonth = date('n', strtotime($dt->lastperiod));
                                                    $lastyear = date('Y', strtotime($dt->lastperiod));
                                                    $nextmonth = date('n', strtotime($dt->nextperiod));
                                                    $nextyear = date('Y', strtotime($dt->nextperiod));
                                                    for($i=0;$i < $opts;$i++)
                                                    {
                                                        $test = $sm - 1;
                                                        ////Check for current month and year so we can select it
                                                        if($lastmonth == $sm)
                                                        {
                                                            echo "<option value='".$lastmonth."/".$lastyear."' selected='selected'>".$m[$lastmonth - 1]." ".$lastyear."</option>\n";
                                                        }
                                                        else
                                                        {
                                                            echo "<option value='".$nextmonth."/".$nextyear."' >".$m[$nextmonth - 1]." ".$nextyear."</option>\n";
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
                                <td class="form_title">Order No&nbsp;</td>
                                <td>
                                    <input type="text" class="span12 typeahead" id="orderno" name="orderno"
                                        placeholder="Wajib Di isi" onchange="Sales.get_orderno_info()" />
                                </td>
                            </tr>
                            <tr>
                                <td class="form_title">Remarks&nbsp;</td>
                                <td>
                                    <input type="text" class="span12 typeahead" id="remark" name="remark" />
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

                </tbody>
                <tbody id="SS">
                    <tr>
                        <td align="right"><input type="button" class="btn btn-danger" name="new_record"
                                id="new_record" value="Add 1 row" onclick="Sales.add_new_sales_row()" /></td>
                        <td colspan="3" align="right">T O T A L</td>
                        <td>&nbsp;
                        </td>
                        <td>
                            <input readonly="yes" type="text" style="text-align:right;" class="span12 typeahead"
                                id="total_all" name="total_all" />
                            <input type="hidden" id="total_all_real" name="total_all_real" />
                            <input type="hidden" id="total_all_real_bv" name="total_all_real_bv" />
                            <input type="hidden" id="totals_all_bv" name="totals_all_bv" />
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    <input type="hidden" id="action" name="action" value="<?php echo $form_action; ?>" />
                    <input type="hidden" name="amount" id="amount" value="1" />
                    <input type="hidden" name="tabidx" id="tabidx" value="1" />
                    <input type="hidden" name="amt_record" id="amt_record" value="0" />
                    <input type="hidden" name="jenis" value="<?php echo $jenis; ?>" />
                </tbody>
            </table>
            <table align="center" class="table table-striped table-bordered" width="100%">
                <tr>
                    <th colspan="4">TRANSACTION AMOUNT and PAYMENT DETAIL </td>
                </tr>
                <tr>
                    <td width="13%">Total DP</td>
                    <td width="25%">
                        <input readonly="yes" style="text-align:right;" type="text" class="span8" id="totalDp"
                            name="totalDp" />
                        <input type="hidden" id="totalDp_real" name="totalDp_real" />
                    </td>
                    <td width="13%">Payment&nbsp;</td>
                    <td width="25%">
                        <select id="payn_type" name="payn_type" class="span8" onchange="Sales.getchangepvr()">
                            <option value="">--Select Here--</option>
                            <?php
                                    foreach($listype as $payments)
                                    {
                                        if($payments->description == 'Cash')
                                        {
                                            echo "
                                            <option value=\"$payments->id\" selected=\"selected\">$payments->description
                                            </option>";
                                        }

                                    }
                                    ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Total BV</td>
                    <td>
                        <input readonly="yes" style="text-align:right;" type="text" class="span8" id="total_bv"
                            name="total_bv" />
                        <input type="hidden" id="total_bv_real" name="total_bv_real" />
                    </td>
                    <td>Nominal&nbsp;&nbsp;</td>
                    <td>
                        <input style="text-align:right;" type="text" class="span8" id="paynominal" name="paynominal"
                            value="0" />
                    </td>
                </tr>

                <tr>
                    <td>Sisa Pembayaran </td>
                    <td>
                        <input readonly="yes" style="text-align:right;" type="text" class="span8" id="sisa_payment"
                            name="sisa_payment" value="0" />
                    </td>
                    <td>Voucher No</td>
                    <td>
                        <input type="text" style="text-align:right;" class="span8 typeahead" id="vchnoo"
                            name="vchnoo" onchange="Sales.get_pvrr_info()" disabled="yes" />
                        <input type="hidden" style="text-align:right;" class="span8 typeahead" id="vchnoo1"
                            name="vchnoo1" disabled="yes" />
                    </td>
                </tr>
                <!--<tr id="vchno" style="visibility: collapse;">
                        <td colspan="2"></td>

                            <td >Voucher No</td>
                            <td >
                            <input type="text" style="text-align:right;" class="span8 typeahead" id="vchnoo"  name="vchnoo" onblur="Sales.get_pvrr_info()"/>
                            </td>
                        </tr>-->

                <tr>
                    <td colspan="3" class="form_title1"></td>
                    <td>
                        <input type="hidden" class="span12 typeahead" id="paynominal_real" name="paynominal_real" />
                        <input type="button" class="btn btn-success" value="Add Payment"
                            onclick="Sales.add_payment_input_sales()" id="xx" name="xx" disabled />
                        <input type="hidden" id="pay_record" name="pay_record" value="0" />
                        <input type="hidden" id="restrict_pay" name="restrict_pay" value="" />
                        <input type="hidden" id="cash_sum" name="cash_sum" value="0" />
                        <input type="hidden" id="noncash_sum" name="noncash_sum" value="0" />
                        <input type="hidden" id="change_over_cash" name="change_over_cash" value="0" />
                    </td>
                </tr>
            </table>
            <table width="100%" align="left" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>
                            Deposit no.
                        </th>
                        <th>
                            <input type=hidden name=nodeposit value="<?php echo isset($nodepo) ? $nodepo :'' ?>" />
                            <input type=hidden name=ktg value="<?php echo isset($kategori) ? $kategori :'' ?>" />
                            <input type=hidden name=iddeposit value="<?php echo isset($id) ? $id :'' ?>" />
                            <?php echo isset($nodepo) ? $nodepo :'' ?>
                        </th>
                    </tr>
                    <tr>
                        <th>
                            Sisa Deposit
                        </th>
                        <th>
                            <input readonly="yes" style="text-align:right;" type="text" id="tottersedia"
                                name="tottersedia[]" value="<?php echo isset($tersedia) ? $tersedia :'' ?>"
                                class="span12">
                        </th>
                    </tr>
                </thead>
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
                    <tr>
                        <td align=center class=tipebayar><input type=hidden class=payt name=pay_type[]
                                value="<?php echo isset($kategori) ? $kategori :'' ?>" /><input readonly=yes
                                type=hidden name=description[]
                                value="<?php echo isset($desc) ? $desc :'' ?>" /><?php echo isset($desc) ? $desc :'' ?>
                        </td>
                        <td class=noreff><input type=hidden name=refno[]
                                value="<?php echo isset($nodepo) ? $nodepo :'' ?>" /><?php echo isset($nodepo) ? $nodepo :'' ?>
                        </td>

                        <td class=jmlbayar>
                            <div align="right">
                                <input readonly="yes" style="text-align:right;" type="text"
                                    value="<?php echo isset($tersedia) ? $tersedia :'' ?>" class="span12">

                                <input readonly="yes" style="text-align:right;" type="hidden" id="tersedia"
                                    name="paynominal[]" value="<?php echo isset($tersedia) ? $tersedia :'' ?>"
                                    class="span12">
                            </div>
                        </td>


                        <td align=center></td>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <td colspan="2" align="right">TOTAL PAYMENT&nbsp;&nbsp;</td>
                        <td>
                            <input readonly="yes" style="text-align:right;" type="text" id="tot_all_payment"
                                name="tot_all_payment" value="0" class="span12" />
                            <input type="hidden" id="tot_all_payment_real" name="tot_all_payment_real" value="0"
                                class="span12" />
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right">CHANGE&nbsp;&nbsp;</td>
                        <td>
                            <input readonly="yes" style="text-align:right;" type="text" id="change" name="change"
                                class="span12" value="0" />
                            <input type="hidden" id="change_real" name="change_real" value="0" class="span12" />
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td valign="top" align="right" colspan="2">
                            NOTES&nbsp;&nbsp;
                        </td>
                        <td colspan="2" valign="top">
                            <textarea id="notes" name="notes" class="span16"></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
            <br />
            <!-- <input type="button" class="btn btn-success span14" name="submit" value="Save" id="save"/> -->
            <input type="button" class="btn btn-warning span6" value="Back" onclick="All.back_to_form('.nextForm2', '.nextForm1')">

            <input type="button" class="btn btn-success span6" name="submit" value="Save" id="save"
                onclick="Sales.save_deposit_sales()" />
            <!--</div> -->
        </div> <!-- end control-group -->
    </fieldset>
</form>