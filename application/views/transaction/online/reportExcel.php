<?php
    if(empty($arr)){
        echo setErrorMessage();
    } else {
        $filenm = "$kodestk-trx.xls";
        header("Content-type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename='.$filenm.'');
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Pragma: public");

        echo "<table width=\"100%\" border = \"1\">";
        echo "<thead>";
        echo "<tr></tr>";
        echo "<tr>";
        echo "<th align=\"left\" colspan=1>No. KW:</th>";
        echo "<th align=\"left\" colspan=2>".$arr[0]->KWno." - ".$arr[0]->nmmember."</th>";
        echo "</tr>";
        echo "<tr>";
        echo "<th align=\"left\" colspan=1>No. Order:</th>";
        echo "<th align=\"left\" colspan=2>".$arr[0]->orderno."</th>";
        echo "</tr>";
        echo "</thead>";

        echo "<tbody>";
        echo "<tr>";
        echo "<th>Kode Produk</th>";
        echo "<th>Nama Produk</th>";
        echo "<th>Qty</th>";
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
                echo "<tr></tr>";
                echo "<tr>";
                echo "<th align=\"left\" colspan=1>No. KW:</th>";
                echo "<th align=\"left\" colspan=2>".$list->KWno." - ".$list->nmmember."</th>";
                echo "</tr>";
                echo "<tr>";
                echo "<th align=\"left\" colspan=1>No. Order:</th>";
                echo "<th align=\"left\" colspan=2>".$list->orderno."</th>";
                echo "</tr>";
                echo "</thead>";

                echo "<tbody>";
                echo "<tr>";
                echo "<th>Kode Produk</th>";
                echo "<th>Nama Produk</th>";
                echo "<th>Qty</th>";
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