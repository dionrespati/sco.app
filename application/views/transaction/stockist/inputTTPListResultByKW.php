<<<<<<< HEAD
<table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable">
    <thead>
        <tr><th colspan="2">Transaksi <?php echo $result[0]->receiptno; ?></th></tr>
        <tr>
            <td width="20%">Kode Stockist</td>
            <td><?php echo $result[0]->loccd; ?></td>
        </tr>
        <tr>
            <td>No DO</td>
            <td>
            <?php
                if($result[0]->GDO == null || $result[0]->GDO == "") {
                    echo "DO Belum diproses";
                } else {
                    echo $result[0]->GDO. " by ".$result[0]->gdo_createnm. " @".$result[0]->gdo_dt;
                }
            ?>
            </td>
        </tr>
    </thead>
</table>
<table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable">
    <thead>
        <tr><th colspan="6">List SSR/MSR berdasarkan no <?php echo $result[0]->receiptno; ?></th></tr>
        <tr>
           <th>No</th>
           <th>No SSR/MSR</th>
           <th>Kode Stockist</th>
           <th>Nama Stockist</th>
           <th>Total DP</th>
           <th>Total BV</th>
        </tr>            
    </thead>
    <tbody>
     <?php
        $i=1;
        foreach($result as $dta) {
            //sales/reportstk/
            echo "<tr>";
            echo "<td align=right>$i</td>";
            echo "<td align=center><a onclick=\"javascript:All.ajaxShowDetailonNextForm('sales/reportstk/batchno/$dta->batchscno')\">".$dta->batchscno."</a></td>";
            echo "<td align=center>".$dta->dfno."</td>";
            echo "<td>".$dta->dfno_name."</td>";
            echo "<td align=right>".number_format($dta->tdp, 0, ",", ".")."</td>";
            echo "<td align=right>".number_format($dta->tbv, 0, ",", ".")."</td>";
            echo "</tr>";
            $i++;
        }
     ?>
    </tbody>            
=======
<table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable">
    <thead>
        <tr><th colspan="2">Transaksi <?php echo $result[0]->receiptno; ?></th></tr>
        <tr>
            <td width="20%">Kode Stockist</td>
            <td><?php echo $result[0]->loccd; ?></td>
        </tr>
        <tr>
            <td>No DO</td>
            <td>
            <?php
                if($result[0]->GDO == null || $result[0]->GDO == "") {
                    echo "DO Belum diproses";
                } else {
                    echo $result[0]->GDO. " by ".$result[0]->gdo_createnm. " @".$result[0]->gdo_dt;
                }
            ?>
            </td>
        </tr>
    </thead>
</table>
<table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable">
    <thead>
        <tr><th colspan="6">List SSR/MSR berdasarkan no <?php echo $result[0]->receiptno; ?></th></tr>
        <tr>
           <th>No</th>
           <th>No SSR/MSR</th>
           <th>Kode Stockist</th>
           <th>Nama Stockist</th>
           <th>Total DP</th>
           <th>Total BV</th>
        </tr>            
    </thead>
    <tbody>
     <?php
        $i=1;
        foreach($result as $dta) {
            //sales/reportstk/
            echo "<tr>";
            echo "<td align=right>$i</td>";
            echo "<td align=center><a onclick=\"javascript:All.ajaxShowDetailonNextForm('sales/reportstk/batchno/$dta->batchscno')\">".$dta->batchscno."</a></td>";
            echo "<td align=center>".$dta->dfno."</td>";
            echo "<td>".$dta->dfno_name."</td>";
            echo "<td align=right>".number_format($dta->tdp, 0, ",", ".")."</td>";
            echo "<td align=right>".number_format($dta->tbv, 0, ",", ".")."</td>";
            echo "</tr>";
            $i++;
        }
     ?>
    </tbody>            
>>>>>>> devel
</table>