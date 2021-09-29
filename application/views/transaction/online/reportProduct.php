<?php
    if(empty($arr)){
        echo setErrorMessage();
    } else {
        $filenm = "$kodestk-prodct.xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filenm.'');
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Pragma: public");

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