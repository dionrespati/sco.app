<?php if(empty($idstk)){
        echo "<div align=center class='alert alert-error'>No Record found..!!</div>";
    }else{
?>
<script>
    function f1(val){

        {
            $.ajax({
                type : "POST",
                url  : "<?php echo site_url('sco/sales/generate/getdetailSSR');?>",
                dataType : 'json',
                data : {ID_KW:val },
                success: function(data){

                    $("#box-table-b").html(data.table);

                }
            });
        }
    }
</script>
<div class="row-fluid">
<!--    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" style="left: 40%; width: 900px; height: 350px;">-->
<!--        <div class="modal-dialog modal-lg">-->
<!--            <div class="modal-content">-->
<!---->
<!--                <div class="modal-header">-->
<!---->
<!--                    <h4 class="modal-title" id="myModalLabel">Detail Transaksi </h4>-->
<!--                </div>-->
<!--                <div class="modal-body" style="max-height: 200px;">-->
<!--                    <table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable"  id="box-table-b" name="box-table-b" align="center" >-->
<!---->
<!--                    </table>-->
<!---->
<!--                </div>-->
<!--                <div class="modal-footer">-->
<!--                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
<!--                </div>-->
<!---->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
    <div class="span12">



            <input type="hidden" id="from" name="from" value="<?php echo $from ?>">
            <input type="hidden" id="to" name="to" value="<?php echo $to ?>">
            <input type="hidden" id="idstkk" name="idstkk" value="<?php echo $idstkk ?>">
            <input type="hidden" id="ke" name="ke" value="<?php echo $to ?>">
<!--            <input type="hidden" id="bnsperiod" name="bnsperiod" value="--><?php //echo $bnsperiod ?><!--">-->
            <table style="width: 100%;" class="table table-striped table-bordered bootstrap-datatable datatable" align="center">
                <thead>
                    <tr bgcolor="#f4f4f4">
                        <th width="2%">NO</th>
                        <th>No SSR</th>
                        <th>Tgl SSR</th>
                        <th>Stockist</th>
                        <!--<th>SC CO</th>-->
                        <th>Status</th>
                        <th width="10%">Total Penggunaan Cash</th>
                        <th width="10%">Total Penggunaan Voucher</th>

                        <th width="10%">Total Pay</th>
                        <th width="5%">Total BV</th>
                        <th>No IP</th>


                    </tr>
                </thead>
                <tbody>
                <?php
                $totalDp = 0;$totalbv=0; $i=1;$kas=0; $vkas=0;

                $nox = 1;
                foreach($idstk as $row)
                {
                    /* if(isset($row->batchdt) ){
                        $ro=date("d-m-Y",strtotime($row->batchdt));
                    }else{
                        $ro="-";

                    } */
                    $ro = $row->batchdt;

//                    $trxdate = date('d/m/Y', strtotime($row->etdt));
//                    <input type=\"button\" class=\"btn btn-warning\" onClick=\"Sales_sco.getdetailCahyono($i);\" name=\"submit\" value=\"Detail\"/>
//                    $bnsperiod = date('d/m/Y', strtotime($row->bnsperiod));
//<td>$row->sc_co - $row->fullnm_CO</td>               
                    //$det_url = base_url()."sales/reportstk/batchno/".$row->batchno;
                    echo "
                    <tr>
                       <td align=\"right\">".$nox."</td>
                       <td align=\"center\">
                       <input readonly=yes type=hidden  class=span12 id=tipe".$i."  name=tipe[] value='$row->batchno' />
                       <a onclick=\"javascript:All.ajaxShowDetailonNextForm('sales/reportstk/batchno/$row->batchno')\">$row->batchno</a>
                       </td>
                       <td align=\"center\">".$ro."</td>


                       <td>$row->sc_dfno - $row->fullnm_DFNO</td>
                       
      <td align=\"center\">$row->x_status
                       </td>
                        ";
                    if($row->cash==0 && $row->vcash==0){
                        echo "<td><div align=right>".number_format($row->TOTDP,0,"",",")."</div></td>";
                    }else
                    {
                        echo "<td><div align=right>".number_format($row->cash,0,"",",")."</div></td>";
                    }
                    echo "
                       <td><div align=right>".number_format($row->vcash,0,"",",")."</div></td>

                       <td><div align=right>".number_format($row->TOTDP,0,"",",")."</div></td>
                       <td><div align=right>".number_format($row->TOTBV,0,"",",")."</div></td>
                       <td><div align=center>".($row->trcd2)."</div></td>


                    </tr>";
                    $totalDp += $row->TOTDP;
                    $totalbv += $row->TOTBV;

                    $kas += $row->cash;
                    $vkas += $row->vcash;
                    $nox++;
                }
                ?>
            </table>
            <table width="100%">    
                <tr>
                    <td style="text-align: right;">Total</td>
                    <!--        <td style="text-align: right;">--><?php //echo number_format($kas,0,"",",");?><!--</td>-->
                    <!--        <td style="text-align: right;">--><?php //echo number_format($vkas,0,"",",");?><!--</td>-->
                    <td style="text-align: right;"><?php echo number_format($totalDp,0,"",",");?></td>
                    <td style="text-align: right;"><?php echo number_format($totalbv,0,"",",");?></td>
                    <td>
                    </td>
                </tr>
                    
                </tbody>
            </table>
            <table>
            <tr>
                        <td>
                            <form role="form" id="demo-form2" method="post" action="<?php echo $action1; ?>"   target="_blank">
                                <input type="hidden" id="searchs" name="searchs" value="<?php echo $tipe ?>" >
                                <input type="hidden" id="idstkk" name="idstkk" value="<?php echo $idstkk ?>" >
                                <input type="hidden" id="from" name="from" value="<?php echo $from ?>" >
                                <input type="hidden" id="to" name="to" value="<?php echo $to ?>" >
                                <input type="hidden" id="statuses" name="statuses" value="<?php echo $statuses ?>" >
                                <button type="submit" class="btn btn-warning" >Cetak PDF</button>
                            </form>

                        
                        </td>
                        <td>
                        <form role="form" id="demo-form2" method="post" action="<?php echo $action2; ?>"   target="_blank">
                                <input type="hidden" id="searchs" name="searchs" value="<?php echo $tipe ?>" >
                                <input type="hidden" id="idstkk" name="idstkk" value="<?php echo $idstkk ?>" >
                                <input type="hidden" id="from" name="from" value="<?php echo $from ?>" >
                                <input type="hidden" id="to" name="to" value="<?php echo $to ?>" >
                                <input type="hidden" id="statuses" name="statuses" value="<?php echo $statuses ?>" >
                                <button type="submit" class="btn btn-success" >Cetak EXCEL</button>

                            </form>
                        </td>
                    </tr>
            </table>
            <input type="hidden" name="typee" value="<?php echo $tipe;?>"/>
<!--            <input type="hidden" name="bnsperiod" value="--><?php //echo $bnsperiod;?><!--"/>-->
            <input type="hidden" name="scDfno" value="<?php echo $idstkk;?>"/>
    </div>
</div>
<?php 
setDatatable();
}?>

