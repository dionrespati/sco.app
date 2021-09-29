 <?php
    if(empty($idstk)){
        echo setErrorMessage();
    }else{
        $filenm = "StkSalesReport.xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filenm.'');
        header("Expires: 0");
	    header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Pragma: public");
       
?> 
 <table border="1">
     <thead>
        <tr>
            <th colspan="10">Rekap Sales Stockist Report</th>
        </tr>
         <tr>
             <th width="2%">NO</th>
             <th width="10%">No SSR</th>
             <th width="10%">Tgl SSR</th>
             <th>Stockist</th>
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
                       <td align=\"center\">$row->batchno</td>
                       <td align=\"center\">".$ro."</td>
                       <td>$row->sc_dfno - ".$row->fullnm_DFNO."</td>
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
<br />
<br />
 <table border="1">
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
<?php
    }
?>