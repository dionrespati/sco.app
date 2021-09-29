<?php
    if(empty($arr)){
        echo setErrorMessage();
    } else {
        ?>
        <input type="hidden" name="type" value="product">
        <input type="submit" tabindex="3" class="btn btn-success "name="export" value="Export Excel"/>
        <?php
        echo "<table width=\"100%\" border = \"1\">";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Kode Produk</th>";
        echo "<th>Nama Produk</th>";
        echo "<th>Qty</th>";
        echo "</tr>";
        echo "</thead>";

        echo "<tbody>";
        foreach($arr as $list) {
            echo "<tr>";
            echo "<td>".$list->prdcd."</td>";
            echo "<td>".$list->prdnm."</td>";
            echo "<td>".$list->qty."</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    }
?>