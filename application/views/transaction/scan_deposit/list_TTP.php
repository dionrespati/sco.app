
<?php
$linkTTP = "All.ajaxShowDetailonNextForm('scan/list/detail/ttp/$deposit')";
echo "<div class='form-group'>";
if($status == "1") {
echo "<input type='button' value='TTP Baru' onclick=\"All.ajaxShowDetailonNextForm2('scan/ttp/input/$deposit')\" class='btn btn-primary'>";
}
echo "&nbsp;<input type='button' class='btn btn-sm btn-warning' title='Refresh Halaman ini' value='Refresh' onclick=$linkTTP />";
echo "</div>";


if($list['total_vch'] != $list['total_deposit_out'] || $list['stt_balance'] == "1" || $user == "BID06") {
    $total_vch = number_format($list['total_vch'], 0, ".", ".");
    $total_deposit_out = number_format($list['total_deposit_out'], 0, ".", ".");
    echo "<div class='alert alert-danger'>Deposit Voucher tidak balance, klik tombol <input type='button' class='btn btn-mini btn-primary' value='Koreksi Deposit Voucher' onclick=\"Stockist.recalculateDeposit('$deposit')\" /></div>";
}

/* if($user == "BID06") {
    echo "Total Vch : ".$list['total_vch'];
    echo "<br />";
    echo "Total Deposit Out : ".$list['total_deposit_out'];

} */

/* echo "<pre>";
print_r($list);
echo "</pre>"; */
?>
<table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable"
    align="center">
    <thead>
        <tr>
            <th rowspan="2" width="4%">No</th>
            <th rowspan="2" width="11%">No Trx</th>
            <th rowspan="2" width="12%">No TTP</th>
            <th rowspan="2">ID Member</th>
            <th rowspan="2" width="8%">Bonus</th>
            <th rowspan="2" width="11%">No SSR</th>
            <th rowspan="2" width="7%">Total DP</th>
            <th colspan="2" width="14%">Penggunaan</th>
            <th rowspan="2" width="4%">Aksi</th>
        </tr>
        <tr>
            <th width="7%">Deposit</th>
            <th width="7%">Cash</th>
        </tr>
    </thead>
    <tbody>
        <?php
    $n = 1;
    if(count($list['arrayData']) > 0)
    foreach($list['arrayData'] as $row) {
        $datess = date('d-m-Y', strtotime($row->createdt));
        $statusx = 'Sudah di klaim';
        $action1="View Deposit";
        $viewttp = "All.ajaxShowDetailonNextForm2('scan/ttp/view/$row->transaksi/$deposit/$status')";

        //$action2='<a class="btn btn-sm btn-success" onclick="Stockist.detailTtp(\'scan/ttp/view/'.$row->transaksi.'/'.$deposit.'/'.$status.'\')" title="TTP">Detail TTP</a>';

        $action2="<a class='btn btn-sm btn-success' onclick=$viewttp title='Lihat Detail TTP'><i class='icon icon-white icon-search'></i></a>";
        /* if($row->status == '1') {
            $statusx = 'Aktif';
            $action2='<a class="btn btn-sm btn-primary" href="'.base_url().'transaction/scan_voucher/viewTTP/'.$row->transaksi.'/'.$deposit.'/'.$status.'  " title="TTP">VIEW TTP</a>';
        } */
        $sisa=$row->voucher + $row->cash;

        ?>
        <tr class="record" id="<?php echo $n; ?>">
            <td style="text-align: right"><?php echo $n; ?></td>
            <td style="text-align: center"><?php echo $row->transaksi; ?></td>
            <td style="text-align: center"><?php echo $row->orderno; ?></td>
            <td><?php echo $row->dfno. " / ".substrwords($row->member,20); ?></td>
            <td style="text-align: center"><?php echo $row->bnsperiod; ?></td>
            <td style="text-align: center"><?php echo $row->batchno; ?></td>
            <?php
            $warna = "";
            if($row->tdp != $sisa) {
                echo "<td style='text-align: right'><font color=red>".number_format($row->tdp, 0, ".", ".")."</font></td>";
                echo "<td style='text-align: right'><font color=red>".number_format($row->voucher, 0, ".", ".")."</font></td>";
                $kurang = $row->tdp - $sisa;
                if($row->cash > 0) {
                    echo "<td style='text-align: right'><font color=red>(".number_format($row->cash, 0, ".", ".").")</font></td>";
                } else {
                    echo "<td style='text-align: right'><font color=red>(".number_format($kurang, 0, ".", ".").")</font></td>";
                }
            } else {
                echo "<td style='text-align: right'>".number_format($row->tdp, 0, ".", ".")."</td>";
                echo "<td style='text-align: right'>".number_format($row->voucher, 0, ".", ".")."</td>";
                echo "<td style='text-align: right'>".number_format($row->cash, 0, ".", ".")."</td>";
            }
            ?>

            <td style="text-align: center"><?php echo $action2 ?></td>
        </tr>
    <?php $n++; } ?>
    </tbody>
</table>
<?php
$selisih_deposit = $list['total_vch'] - $list['total_deposit_in'];
$sisa_cash = $list['total_belanja'] - $list['total_deposit_in']
?>
<table width="80%" class="table table-striped table-bordered bootstrap-datatable">
    <tr>
        <th colspan="4">Rekapitulasi Deposit Voucher</th>
    </tr>
    <tr>
        <td align="right">Total Deposit Voucher</td>
        <td align="right"><?php echo number_format($list['total_deposit_in'], 0, ".", ".") ?></td>
        <td align="right">&nbsp;Total Nilai Pembelanjaan</td>
        <td align="right"><?php echo number_format($list['total_belanja'], 0, ".", ".") ?></td>
    </tr>
    <tr>
        <td align="right">Total Pemakaian Deposit</td>
        <td align="right"><?php echo number_format($list['total_vch'], 0, ".", ".") ?></td>
        <td align="right">&nbsp;Sisa Cash yang seharus nya dibayar</td>
        <td align="right"><?php echo number_format($sisa_cash, 0, ".", ".") ?></td>
    </tr>
    <tr>
        <td align="right">Selisih Pemakaian Deposit</td>
        <td align="right"><?php echo number_format($selisih_deposit, 0, ".", ".") ?></td>
        <td align="right">&nbsp;Sisa Cash yang harus dibayar saat ini</td>
        <td align="right"><?php echo number_format($list['total_cash'], 0, ".", ".") ?></td>
    </tr>
</table>
<input type="button" class="btn btn-warning" name="back" value="<< Kembali"
    onclick="All.back_to_form(' .nextForm1', ' .mainForm')" />

<script>
$(document).ready(function() {
    $(All.get_active_tab() + " .datatable").dataTable( {
        "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
        "sPaginationType": "bootstrap",
        "oLanguage": {
        },
        "bDestroy": true
    });
    $(All.get_active_tab() + " .datatable").removeAttr('style');
});
</script>