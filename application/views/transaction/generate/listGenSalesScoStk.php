<?php if(empty($idstk)){
        echo "<div align=center class='alert alert-error'>No Record found..!!</div>";
    }else{
?>
<script>
    function f1(objButton){

        {
            $.ajax({
                type : "POST",
                url  : "<?php echo site_url('sales/search/list/detail');?>",
                dataType : 'json',
                data : {
                  ID_KW:objButton.value,
                  from: $('#from').val(),
                  to: $('#to').val(),
                  bnsperiod: $('#bnsperiod').val(),
                  idstkk: $('#idstkk').val()
                },
                success: function(data){

                    $("#box-table-b").html(data.table);

                }
            });
        }
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
                        <th>TIPE</th>
                        <th>SC DFNO</th>
                        <th>BONUS PERIOD</th>
                        <th>TOTAL CASH</th>
                        <th>TOTAL VOUCHER</th>
                        <th>TOTAL PAY</th>
                        <th>TOTAL BV</th>
                        <th>ACTION</th>

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
                    echo "
                    <tr>
                        <td align=\"center\">
                            <input type=checkbox id=cek[] name=cek[] value=\"".$row->tipe."|".$row->sc_dfno."\" />
                        </td>
                       <td align=\"center\"><input readonly=yes type=hidden  class=span12 id=tipe".$i."  name=tipe[] value='$row->tipe' />$row->tipe</td>
                       <td>$row->sc_dfno - $row->scdfno</td>
                       <td align=\"center\">$bnsperiod</td>
                       <td><div align=right>".number_format($row->cash,0,".",".")."</div></td>
                       <td><div align=right>".number_format($row->vcash,0,".",".")."</div></td>
                       <td><div align=right>".number_format($row->totpay,0,".",".")."</div></td>
                       <td><div align=right>".number_format($row->tbv,0,".",".")."</div></td>
                       <td align=\"center\">
                            <button type=\"button\" class=\"btn btn-primary\" data-toggle=\"modal\" data-target=\".bs-example-modal-lg\" onclick=\"f1(this)\"  value='$row->tipe|$row->sc_dfno|$row->sc_co' > View </button>
                       </td>

                    </tr>";
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
                        <td colspan="10">
                            <input type="button" class="btn btn-success" onclick='Stockist.get_group_preview()' name="submit" value="Process" id="checkss"/>
                        </td>
                    </tr>
                </tbody>
            </table>
            <input type="hidden" name="typee" value="<?php echo $tipe;?>"/>
            <input type="hidden" name="bnsperiod" value="<?php echo $bnsperiod;?>"/>
            <input type="hidden" name="scDfno" value="<?php echo $stockist;?>"/>
        </form>
    </div>

</div>
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
    </div>

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