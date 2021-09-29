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



            <!--<input type="hidden" id="from" name="from" value="<?php echo $from ?>">
            <input type="hidden" id="to" name="to" value="<?php echo $to ?>">-->
            <input type="hidden" id="idstkk" name="idstkk" value="<?php echo $idstkk ?>">
            <!--<input type="hidden" id="ke" name="ke" value="<?php echo $to ?>">-->
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
                        <th width="9%">Total Penggunaan Cash</th>
                        <th width="9%">Total Penggunaan Voucher</th>

                        <th width="9%">Total Pay</th>
                        <th width="5%">Total BV</th>
                        <th>No IP</th>


                    </tr>
                </thead>
                <tbody>
                <?php
                $totalDp = 0;$totalbv=0; $i=1;$kas=0; $vkas=0;

                $nox = 1;
                $arr = array();
                $index = 0;
                $arr[$index]['stk'] = $idstk[0]->sc_dfno;
                $arr[$index]['name'] = $idstk[0]->fullnm_DFNO;
                $arr[$index]['tot_dp'] = 0;
                $arr[$index]['tot_bv'] = 0;
                $arr[$index]['total_ssr'] = 0;
                foreach($idstk as $row)
                {
                    if($arr[$index]['stk'] == $row->sc_dfno) {         
                        $arr[$index]['tot_dp'] += $row->TOTDP;
                        $arr[$index]['tot_bv'] += $row->TOTBV;
                        $arr[$index]['total_ssr'] += 1;
                        //echo "isi : ".$arr[$index]['stk']. " - ";
                    } else {
                        $index++;
                        $arr[$index]['stk'] = $row->sc_dfno;
                        $arr[$index]['name'] = $row->fullnm_DFNO;
                        $arr[$index]['tot_dp'] = $row->TOTDP;
                        $arr[$index]['tot_bv'] = $row->TOTBV;    
                        $arr[$index]['total_ssr'] = 1;              
                    }
                    
                    $ro = $row->batchdt;
                    echo "
                    <tr>
                       <td align=\"right\">".$nox."</td>
                       <td align=\"center\">
                       <input readonly=yes type=hidden  class=span12 id=tipe".$i."  name=tipe[] value='$row->batchno' />
                       <a onclick=\"javascript:All.ajaxShowDetailonNextForm('sales/reportstk/batchno/$row->batchno')\">$row->batchno</a>
                       </td>
                       <td align=\"center\">".$ro."</td>


                       <td>$row->sc_dfno - ".substrwords($row->fullnm_DFNO, 20)."</td>
                       
      <td align=\"center\">$row->x_status
                       </td>
                        ";
                    if($row->cash==0 && $row->vcash==0){
                        echo "<td><div align=right>".number_format($row->TOTDP,0,",",".")."</div></td>";
                    }else
                    {
                        echo "<td><div align=right>".number_format($row->cash,0,",",".")."</div></td>";
                    }
                    echo "
                       <td><div align=right>".number_format($row->vcash,0,",",".")."</div></td>

                       <td><div align=right>".number_format($row->TOTDP,0,",",".")."</div></td>
                       <td><div align=right>".number_format($row->TOTBV,0,",",".")."</div></td>
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
            <!-- <table width="100%" class="table table-striped">    
                <tr>
                    <td style="text-align: right;">Total</td>
                    <td style="text-align: right;"><?php echo number_format($totalDp,0,",",".");?></td>
                    <td style="text-align: right;"><?php echo number_format($totalbv,0,",",".");?></td>
                    <td>
                    </td>
                </tr>
                    
                </tbody>
            </table> -->
            
            <table style="width: 100%;" class="table table-striped table-bordered" align="center">
                <tr>
                    <th colspan="6">Rekap Sales Stockist</th>
                </tr>
                <tr>
                    <th>No</th>
                    <th>Kode Stockist</th>
                    <th>Nama Stockist</th>
                    <th>Total SSR/MSR</th>
                    <th>Total DP</th>
                    <th>Total BV</th>
                    
                </tr>
                <?php
                if(isset($arr)) {
                    $i=1;
                    $total_dp_stk = 0;
                    $total_bv_stk = 0;
                    $total_ssr_stk = 0;
                    foreach($arr as $dta) {
                        echo "<tr>";
                        echo "<td align=right>$i</td>";    
                        echo "<td align=center>".$dta['stk']."</td>";
                        echo "<td align=left>".$dta['name']."</td>";
                        echo "<td align=center>".$dta['total_ssr']."</td>";
                        echo "<td align=right>".number_format($dta['tot_dp'],0,",",".")."</td>";
                        echo "<td align=right>".number_format($dta['tot_bv'],0,",",".")."</td>";
                        
                        echo "</tr>";
                        $i++;
                        $total_ssr_stk += $dta['total_ssr'];
                        $total_dp_stk += $dta['tot_dp'];
                        $total_bv_stk += $dta['tot_bv'];
                    }
                }
                ?>
                <tr>
                    <th colspan="3"> T O T A L</th>
                    <td align="center"><?php echo number_format($total_ssr_stk,0,",","."); ?></td>
                    <td align=right><?php echo number_format($total_dp_stk,0,",","."); ?></td>
                    <td align=right><?php echo number_format($total_bv_stk,0,",","."); ?></td>
                    
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

