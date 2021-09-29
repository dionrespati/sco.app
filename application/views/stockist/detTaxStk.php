<?php

    if(empty($result)){

        echo setErrorMessage();
    }else{
?>

        <table style="width: 100%" class="table table-striped table-bordered bootstrap-datatable datatable">
            <thead>
                <tr>
                    <th>No. Bukti Potong</th>
                    <th>Kode Pajak</th>
                    <th>NPWP Pemotong</th>
                    <th>Nama Pemotong</th>
                    <th>Tgl Potong</th>
                    <th>Bulan Bonus</th>
                    <th>Cetak</th>
                </tr>
            </thead>  
            
            <tbody>
            <?php
                foreach($result as $dt){

                    echo "<tr>";
                    echo "<td>".$dt->Nomor_Bukti_Potong."</td>";
                    echo "<td>".$dt->Kode_Pajak."</td>";
                    echo "<td>".$dt->NPWP_Pemotong."</td>";
                    echo "<td>".$dt->Nama_Pemotong."</td>";
                    echo "<td>".$dt->Tanggal_Bukti_Potong."</td>";
                    echo "<td>".$dt->Bulan_Bonus."</td>";
                    echo "<td>
                            <form role=\"form\" id=\"demo-form2\" method=\"post\" action=\"http://www.k-linkmember.co.id/sco.app/tax/stk/print\" target=\"_blank\">
                            <button type=\"submit\" class=\"btn btn-success\">Cetak PDF</button>
                            <input type=\"hidden\" id=\"idStk\" name=\"idStk\" value=\"$dt->IDMEMBER\">
                            <input type=\"hidden\" id=\"idStk\" name=\"idStk\" value=\"$dt->KDSTOCKIST\">
                            <input type=\"hidden\" id=\"bln_bns\" name=\"bln_bns\" value=\"$dt->Bulan_Bonus\">
                            <input type=\"hidden\" id=\"no_bukti_potong\" name=\"no_bukti_potong\" value=\"$dt->Nomor_Bukti_Potong\">
                            </form>
                            </td>";
                    echo "</tr>";
                }
            ?>
            </tbody>
        </table>
<?php
    }
?>