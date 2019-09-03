<?php if(empty($cek)){
    echo "<div align=center class='alert-error'>Silahkan Checklist Orderno..!!</div>";
    echo "<input type=\"button\" class=\"btn btn-success\" name=\"back\" value=\"Back\" onclick=\"Sales_sco.back_to_form_gen()\"  />&nbsp;&nbsp;";
}else{

?>
<form id="generatesubs" method="post" >
    <table style="width: 95%;" class="table table-striped table-bordered bootstrap-datatable datatable" align="center">
        <thead>
            <tr bgcolor="#f4f4f4">
                <th>NO</th>
    			      <th>PROD CODE</th>
                <th>PROD NAME</th>
                <th>DP</th>
                <th>QTY</th>

                <th>BV</th>
                <th>TDP</th>
    		  </tr>
        </thead>
        <tbody>
        <?php
            $no = 1;
            $totdp = 0;
            $totbv = 0;
            $totdp1 = 0;
            $totQty = 0;
            foreach($groupitem as $row)
            {
                $tdp=$row->dp*$row->qty;
                $tbv=$row->bv*$row->qty;
//                $totdp = $row->qty * $row->dp;
                //$trxdate = date('d/m/Y', strtotime($row->etdt));
                echo "
                <tr>
                    <td align=\"center\">".$no++."</td>
                   <td align=\"center\">$row->prdcd</td>
                   <td>$row->prdnm</td>
                   <td><div align=right>".number_format($row->dp,0,".",".")."</div></td>

                   <td><div align=right>".number_format($row->qty,0,".",".")."</div></td>
                   <td><div align=right>".number_format($row->bv*$row->qty,0,"",".")."</div></td>
                   <td><div align=right>".number_format($row->dp*$row->qty,0,".",".")."</div></td>

                </tr>";
                $totdp1 += $tdp;
                $totbv += $tbv;
                $totQty += $row->qty;
            }
            ?>
            <tr>
                <td colspan="4">TOTAL</td>
                <td><?php echo "<div align=right>".number_format($totQty,0,".",".")."</div>";?></td>
                <td><?php echo "<div align=right>".number_format($totbv,0,".",".")."</div>";?></td>
                <td><?php echo "<div align=right>".number_format($totdp1,0,".",".")."</div>";?></td>

            </tr>
            <tr><td colspan="7`">
            <input type="hidden" name="bonusperiod" value="<?php echo $bnsperiod;?>"/>
            <input type="hidden" name="tipeSales" value="<?php echo $tipeSales;?>"/>
            <input type="hidden" name="scDfno" value="<?php echo $scDfno;?>"/>

            <?php
            foreach($groupmlm as $row) {
                {
                    echo "<input type=hidden name=scdfnomlm[] value='$row->sc_dfno'>";
                    echo "<input type=hidden name=sccomlm[] value='$row->sc_co'>";
                    echo "<input type=hidden name=tipechmlm[] value='$row->tipe'>";
                }
            }
            ?>


            <?php
            foreach($groupprod as $row) {
                {
                    echo "<input type=hidden name=trcd[] value='$row->trcd'>";
                    echo "<input type=hidden name=scCO[] value='$row->sc_dfno'>";
                    echo "<input type=hidden name=scCOxd[] value='$row->sc_co'>";
                    echo "<input type=hidden name=tipech[] value='$row->tipe'>";
                }
            }
            ?>
            <input type="button" class="btn btn-success" name="back" value="Back" onclick="Stockist.back_to_form_gen()"  />&nbsp;&nbsp;
            <!-- <input type="submit" class="btn btn-success" name="submit" value="Generate"/>-->
            <input type="button" class="btn btn-success" onclick="Stockist.generate_sales_sco2()" name="submit" value="Generate" id="checkss"/>
            </td></tr>
            </tbody>
    </table>
</form>
<?php }?>
