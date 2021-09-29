<?php
    if(empty($arr)){
        echo setErrorMessage();
    } else {
        ?>
        <input type="hidden" name="type" value="trx">
        <input type="submit" tabindex="3" class="btn btn-success "name="export" value="Export Excel"/>
        <?php
        echo "<table width=\"100%\" border = \"1\">";
        echo "<thead>";
        echo "<tr bgcolor=#f4f4f4>";
        echo "<th align=\"left\" colspan=1 width=10%>No. KW:</th>";
        echo "<th align=\"left\" colspan=4>".$arr[0]->KWno." - ".$arr[0]->nmmember."</th>";
        echo "</tr>";
        echo "<tr bgcolor=#f4f4f4>";
        echo "<th align=\"left\" colspan=1 width=10%>No. Order:</th>";
        echo "<th align=\"left\" colspan=5>".$arr[0]->orderno."</th>";
        echo "</tr>";
        echo "</thead>";

        echo "<tbody>";
        echo "<tr bgcolor=#f4f4f4>";
        echo "<th>Kode Produk</th>";
        echo "<th>Nama Produk</th>";
        echo "<th width=10%>Qty</th>";
        echo "</tr>";
        $init_val = $arr[0]->KWno;
        foreach($arr as $list) {
            if ($list->KWno == $init_val) {
                echo "<tr>";
                echo "<td>".$list->prdcd."</td>";
                echo "<td>".$list->prdnm."</td>";
                echo "<td align=\"right\">".$list->qty."</td>";
                echo "</tr>";
                echo "</tbody>";
            } else {
                echo "<table width=\"100%\" border = \"1\">";
                echo "<thead>";
                echo "<tr bgcolor=#f4f4f4>";
                echo "<th align=\"left\" colspan=1 width=10%>No. KW:</th>";
                echo "<th align=\"left\" colspan=4>".$list->KWno." - ".$list->nmmember."</th>";
                echo "</tr>";
                echo "<tr>";
                echo "<th align=\"left\" colspan=1 width=10%>No. Order:</th>";
                echo "<th align=\"left\" colspan=5>".$list->orderno."</th>";
                echo "</tr>";
                echo "</thead>";

                echo "<tbody>";
                echo "<tr bgcolor=#f4f4f4>";
                echo "<th>Kode Produk</th>";
                echo "<th>Nama Produk</th>";
                echo "<th width=10%>Qty</th>";
                echo "</tr>";

                echo "<tr>";
                echo "<td>".$list->prdcd."</td>";
                echo "<td>".$list->prdnm."</td>";
                echo "<td align=\"right\">".$list->qty."</td>";
                echo "</tr>";
                echo "</tbody>";
                $init_val = $list->KWno;
            }
        }
        echo "</table>";
    }
?>