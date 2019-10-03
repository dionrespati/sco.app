<?php if(empty($idstk)){
        echo "<div align=center class='alert alert-error'>No Record found..!!</div>";
    }else{
?>
<script>
    function f1(objButton){
            //sales/search/list/detail
            All.set_disable_button();
            $.ajax({
                type : "POST",
                url  : "<?php echo site_url('sales/search/list/checkSelisih');?>",
                data : {
                  ID_KW:objButton.value,
                  from: $('#from').val(),
                  to: $('#to').val(),
                  bnsperiod: $('#bnsperiod').val(),
                  idstkk: $('#idstkk').val()
                },
                success: function(data){

                    All.set_enable_button();
                    $(All.get_active_tab() + ".mainForm").hide();
                    All.clear_div_in_boxcontent(".nextForm1");
                    $(All.get_active_tab() + ".nextForm1").html(data);

                }
            });
    }

    function f2(objButton){
            All.set_disable_button();
            $.ajax({
                type : "POST",
                url  : "<?php echo site_url('sales/search/list/checkSelisih');?>",
                data : {
                  ID_KW:objButton.value,
                  from: $('#from').val(),
                  to: $('#to').val(),
                  bnsperiod: $('#bnsperiod').val(),
                  idstkk: $('#idstkk').val()
                },
                success: function(data){

                    All.set_enable_button();
                    $(All.get_active_tab() + ".mainForm").hide();
                    All.clear_div_in_boxcontent(".nextForm1");
                    $(All.get_active_tab() + ".nextForm1").html(data);

                }
            });
    }
</script>
<div class="row-fluid">

    <div class="span12">



        <form id="generateSaless" method="post" >
            <input type="hidden" id="from" name="from" value="<?php echo $from ?>">
            <input type="hidden" id="to" name="to" value="<?php echo $to ?>">
            <input type="hidden" id="idstkk" name="idstkk" value="<?php echo $idstkk ?>">
            <input type="hidden" id="ke" name="ke" value="<?php echo $to ?>">
            <input type="hidden" id="bnsperiod" name="bnsperiod" value="<?php echo $bnsperiod ?>">
            <table style="width: 95%;" class="table table-striped table-bordered bootstrap-datatable datatable" align="center">
                <thead>
                    <tr bgcolor="#f4f4f4">
                        <th><input type="checkbox" name="checkall" onclick="checkAll(this);"/></th>
                        <th>Tipe</th>
                        <th>Stockist</th>
                        <th>Periode Bonus</th>
                        <th>Total Cash</th>
                        <th>Total Vch</th>
                        <th>Total Pay</th>
                        <th>Total BV</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>
                <?php
                $totalDp = 0;$totalbv=0; $i=1;
                foreach($idstk as $row)
                {
//                    $trxdate = date('d/m/Y', strtotime($row->etdt));
//                    <input type=\"button\" class=\"btn btn-warning\" onClick=\"Sales_sco.getdetailCahyono($i);\" name=\"submit\" value=\"Detail\"/>
                    $bnsperiod = date("Y-m-d", strtotime($row->bnsperiod));

                    if($row->tipe != "PVR") {
                        $total_pay = $row->vcash + $row->cash;
                        $readonly = "";
                        $font_err_depan = "";
                        $font_err_blkg = "";
                        $btn_list_ttp = "f1(this)";
                        $btn_value = "List TTP";
                        $btn_class = "btn btn-mini btn-success";
                        if($row->totpay != $total_pay) {
                            $readonly="disabled=disabled";
                            $font_err_depan = "<font color=red>";
                            $font_err_blkg = "</font>";
                            $btn_list_ttp = "f2(this)";
                            $btn_value = "Check";
                            $btn_class = "btn btn-mini btn-primary";
                        }

                        echo "
                        <tr>
                            <td align=\"center\">
                                <input $readonly type=checkbox id=cek[] name=cek[] value=\"".$row->tipe."|".$row->sc_dfno."\" />
                            </td>
                        <td align=\"center\"><input readonly=yes type=hidden  class=span12 id=tipe".$i."  name=tipe[] value='$row->tipe' />$font_err_depan $row->tipe $font_err_blkg</td>
                        <td>$font_err_depan $row->sc_dfno - $row->scdfno $font_err_blkg</td>
                        <td align=\"center\">$font_err_depan $bnsperiod $font_err_blkg</td>
                        <td><div align=right>$font_err_depan ".number_format($row->cash,0,".",".")." $font_err_blkg</div></td>";
                        echo "<td><div align=right>$font_err_depan".number_format($row->vcash,0,".",".")." $font_err_blkg</div></td>";
                        echo "  
                       <td><div align=right>$font_err_depan".number_format($row->totpay,0,".",".")." $font_err_blkg</div></td>
                       <td><div align=right>$font_err_depan".number_format($row->tbv,0,".",".")." $font_err_blkg</div></td>
                       <td align=\"center\">
                            <button type=\"button\" class=\"$btn_class\" onclick=\"$btn_list_ttp\"  value='$row->tipe|$row->sc_dfno|$row->sc_co' >  $btn_value </button>
                       </td>
                       </tr>";
                    }  else if($row->tipe == "PVR") {

                        $total_pay = $row->pcash + $row->cash;
                        $readonly = "";
                        $font_err_depan = "";
                        $font_err_blkg = "";
                        $btn_list_ttp = "f1(this)";
                        /* echo "total_pay : ".$total_pay;
                        echo "<br />totpay : ".$row->totpay; */
                        $btn_value = "List TTP";
                        $btn_class = "btn btn-mini btn-success";
                        if($total_pay < $row->totpay) {
                            $readonly="disabled=disabled";
                            $font_err_depan = "<font color=red>";
                            $font_err_blkg = "</font>";
                            $btn_list_ttp = "f2(this)";
                            $btn_value = "Check";
                            $btn_class = "btn btn-mini btn-primary";
                        }

                        echo "
                        <tr>
                            <td align=\"center\">
                                <input $readonly type=checkbox id=cek[] name=cek[] value=\"".$row->tipe."|".$row->sc_dfno."\" />
                            </td>
                        <td align=\"center\"><input readonly=yes type=hidden  class=span12 id=tipe".$i."  name=tipe[] value='$row->tipe' />$font_err_depan $row->tipe $font_err_blkg</td>
                        <td>$font_err_depan $row->sc_dfno - $row->scdfno $font_err_blkg</td>
                        <td align=\"center\">$font_err_depan $bnsperiod $font_err_blkg</td>
                        <td><div align=right>$font_err_depan".number_format($row->cash,0,".",".")."$font_err_blkg</div></td>";
                        echo "<td><div align=right>$font_err_depan".number_format($row->pcash,0,".",".")."$font_err_blkg</div></td>";
                        echo "  
                       <td><div align=right>$font_err_depan".number_format($row->totpay,0,".",".")."$font_err_blkg</div></td>
                       <td><div align=right>$font_err_depan".number_format($row->tbv,0,".",".")."$font_err_blkg</div></td>
                       <td align=\"center\">
                            <button type=\"button\" class=\"$btn_class\" onclick=\"$btn_list_ttp\"  value='$row->tipe|$row->sc_dfno|$row->sc_co' > $btn_value </button>
                       </td>
                       </tr>";    
                    }  /* else {
                        echo "
                        <tr>
                            <td align=\"center\">
                                <input type=checkbox id=cek[] name=cek[] value=\"".$row->tipe."|".$row->sc_dfno."\" />
                            </td>
                        <td align=\"center\"><input readonly=yes type=hidden  class=span12 id=tipe".$i."  name=tipe[] value='$row->tipe' />$row->tipe</td>
                        <td>$row->sc_dfno - $row->scdfno</td>
                        <td align=\"center\">$bnsperiod</td>
                        <td><div align=right>".number_format($row->cash,0,".",".")."</div></td>";
                        echo "<td><div align=right>".number_format("0",0,".",".")."</div></td>";
                        echo "  
                       <td><div align=right>".number_format($row->totpay,0,".",".")."</div></td>
                       <td><div align=right>".number_format($row->tbv,0,".",".")."</div></td>
                       <td align=\"center\">
                            <button type=\"button\" class=\"btn btn-mini btn-success\" onclick=\"f1(this)\"  value='$row->tipe|$row->sc_dfno|$row->sc_co' > List TTP </button>
                       </td>
                       </tr>";
                    } */
                    

                     /*   if($row->tipe == "Voucher Cash (Deposit)") {
                         echo "<td><div align=right>".number_format($row->vcash,0,".",".")."</div></td>";
                       } else if($row->tipe == "PVR") {
                         echo "<td><div align=right>".number_format($row->pcash,0,".",".")."</div></td>";
                       } else {
                        echo "<td><div align=right>".number_format("0",0,".",".")."</div></td>";  
                       } */
                       
                    $totalDp += $row->totpay;
                    $totalbv += $row->tbv;
                }
                ?>
                    <tr>
                        <td colspan="6" style="text-align: right;"><strong>TOTAL</strong></td>
                        <td style="text-align: right;"><?php echo number_format($totalDp,0,".",".");?></td>
                        <td style="text-align: right;"><?php echo number_format($totalbv,0,".",".");?></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="9">
                            <input type="button" class="btn btn-primary" onclick='Stockist.get_group_preview()' name="submit" value="Proses" id="checkss"/>
                        </td>
                    </tr>
                </tbody>
            </table>
            <input type="hidden" name="typee" value="<?php echo $tipe;?>"/>
            <input type="hidden" name="bnsperiod" value="<?php echo $bnsperiod;?>"/>
            <input type="hidden" name="scDfno" value="<?php echo $stockist;?>"/>
        </form>
    </div>

<!--</div>
    <div class="modal fade bs-example-modal-lg" role="dialog" aria-hidden="true" style="left: 30%; width: 80%; max-height: 80%;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">

                    <h4 class="modal-title" id="myModalLabel">Detail Transaksi </h4>
                </div>
                <div class="modal-body" style="max-height: 200px;">
                    <table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable"  id="box-table-b" name="box-table-b" align="center">

                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div> -->

<?php }?>
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
</script>