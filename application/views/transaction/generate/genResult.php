<div class="row-fluid">
    <div class="span12">
        <table style="width: 95%;" class="table table-striped table-bordered bootstrap-datatable datatable" align="center">
            <thead>
                <tr>
                    <th>No</th>
                    <th><?php echo $head;?> No</th>
                    <th><?php echo $head;?> Date</th>
                    <th>Stk</th>
                    <th>Stk C/O</th>
                    <th>BNS Prd</th>
                    <th>Total DP</th>
                    <th>Total BV</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $no = 1;$tbv=0;$tdp=0;
            if(isset($generateRes4))
                foreach($generateRes4 as $list){

                    ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $no.".";?></td>
                        <td style="text-align: center;"><?php echo $list->batchno;?></td>
                        <td style="text-align: center;"><?php echo $list->updatedt;?></td>
                        <td><?php echo $list->sc_dfno;?></td>
                        <td><?php echo $list->sc_co;?></td>
                        <td><?php echo $list->bnsperiod;?></td>
                        <td style="text-align: right"><?php echo number_format($list->tdp,0,".",".");?></td>
                        <td style="text-align: right"><?php echo number_format($list->tbv,0,".",".");?></td>
                    </tr>
                    <?php
                    $no++;
                    $tbv=$tbv+$list->tbv;
                    $tdp=$tdp+$list->tdp;
                }
            ?>

            <tr>
                <td colspan="6">TOTAL</td>
                <td><?php echo "<div align=right>".number_format($tdp,0,".",".")."</div>";?></td>
                <td><?php echo "<div align=right>".number_format($tbv,0,".",".")."</div>";?></td>

            </tr>
                <tr>
                    <td colspan="8"><input type="button" class="btn btn-warning" name="back" value="Kembali" onclick="Stockist.back_frm_generate_awal()"  /></td>
                </tr>
            </tbody>
        </table>
    <?php
        //echo "Generate sukses<BR/><BR/>";
        //echo "".$head." : $new_id <BR/><BR/>";
        //echo "<input type=\"button\" class=\"btn btn-success\" name=\"back\" value=\"Back\" onclick=\"Sales_sco.back_frm_generate_awal()\"  />&nbsp;&nbsp;";
    ?>
</div>
	</div>
