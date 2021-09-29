<h3>List Produk Free yang dapat dipilih</h3>
<h5><font color="red">* Untuk Free Sunglasses, varian warna dapat dipilih</font></h5>
<div class="control-group">
    <?php
    $urut = 1;
    echo "<table width='90%' class='table main table-bordered table-striped' id='showPrd'>";

    foreach($res as $dataprd) {
        echo "<tr>";
        echo "<td>Free Kelipatan $dataprd[skema]</td>";
        echo "<td><select style='width: 400px;' data-live-search='true' name='free_prd[]' id='free_prd$urut'>";
        foreach($dataprd['listPrd'] as $prd) {
            $skm = $dataprd['skema'];
            $val = $prd['prdcd']."|".$skm."|".$prd['max_qty']."|".$prd['prdnm'];
            echo "<option value=\"$val\">".$prd['prdnm']."</option>";
            //$max_qty = $prd['max_qty'];
        }
        echo "</select></td>";
        echo "<td>&nbsp;Qty :&nbsp;</td>";
        echo "<td><select id='qtyx$urut' style='width:50px;' name='qty[]'>";
        $max = $prd['max_qty'];
        for($i = $max; $i >= 1; $i--) {
            echo "<option value=\"$i\">$i</option>";
        }
        echo "</select>&nbsp;
      <input type='hidden' id='max_qty$urut' value='$max'/><input type='hidden' id='cur_qty$urut' value='0'/>";
        echo "<button type='button' id='btnTmbh$urut' class='btn btn-success' onclick='tambahPrd($urut)'>Tambah</button>";
        $urut++;
        echo "<input type='hidden' id='input_total' name='input_total' value='$max_qty' /></td";
        echo "</tr>";
    }
    echo "</table>";
    ?>
    <table width="90%" class="table main table-bordered table-striped">
        <thead>
        <tr>
            <th width="30%">Kode Produk</th>
            <th>Nama Produk</th>
            <th width="10%">Qty</th>
            <!--<th width="10%">Action</th>-->
        </tr>
        </thead>
        <tbody id="pilihProduk">

        </tbody>
    </table>
</div>

<?php
$urlx = "kembaliKeForm()";
echo "<table width='90%' class='table main table-bordered table-striped'>";
echo "<tr>";
echo "<td><input type='button' onclick=\"$urlx\" class='btn btn-warning' id='backTo' value='<< Kembali' />&nbsp;&nbsp;";
echo "<input type='button' onclick='ulangiInput()' class='btn btn-success' id='redo' value='Ulangi Pilih Produk Free' />";
echo "&nbsp;&nbsp;<input type='button' onclick='simpanTrx()' class='btn btn-primary' id='saveTrx' value='Simpan Transaksi' /></td>";
echo "</tr>";
echo "</table>"
?>