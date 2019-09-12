<html>

<!-- <head>
    <title>List Deposit</title>
</head> -->

<body>
    <div class="row-fluid" id="div_isi">
        <div class="span12">
                <span id="view_list_invent">
                    <!-- form -->
                    <div class="form-group">
                        <input type="button" value="TTP Baru" onclick="All.ajaxFormGet('transaction/scan_voucher/getFormTtpDeposit2/<?php echo $deposit ?>')" class="btn btn-primary">
                    </div>
                    <table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable"
                        align="center">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No TTP</th>
                                <th>ID Member</th>
                                <th>Member Name</th>
                                <th>Total Belanja</th>
                                <th>Penggunaan Deposit</th>
                                <th>Penggunaan Cash</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
    $n = 1;
    if(count($list) > 0)
    foreach($list as $row) {
        $datess = date('d-m-Y', strtotime($row->createdt));
        $statusx = 'Sudah di klaim';
        $action1="View Deposit";
        $action2='<a class="btn btn-sm btn-primary" onclick="Stockist.detailTtp(\'transaction/scan_voucher/viewTTP/'.$row->transaksi.'/'.$deposit.'/'.$status.'\')" title="TTP">VIEW TTP</a>';
        /* if($row->status == '1') {
            $statusx = 'Aktif';
            $action2='<a class="btn btn-sm btn-primary" href="'.base_url().'transaction/scan_voucher/viewTTP/'.$row->transaksi.'/'.$deposit.'/'.$status.'  " title="TTP">VIEW TTP</a>';
        } */
        $sisa=$row->voucher + $row->cash; ?>
        <tr class="record" id="<?php echo $n; ?>">
            <td><?php echo $n; ?></td>
            <td><?php echo $row->transaksi; ?></td>
            <td><?php echo $row->dfno; ?></td>
            <td><?php echo $row->member; ?></td>
            <td style="text-align: right"><?php echo number_format($sisa, 0, ".", "."); ?></td>
            <td style="text-align: right"><?php echo number_format($row->voucher, 0, ".", "."); ?></td>
            <td style="text-align: right"><?php echo number_format($row->cash, 0, ".", "."); ?></td>
            <td style="text-align: center"><?php echo $action2 ?></td>
        </tr>
    <?php $n++; } ?>
                        </tbody>
                    </table>
                    <input type="button" class="btn btn-warning" name="back" value="Kembali"
                        onclick="All.back_to_form(' .nextForm1', ' .mainForm')" />
                </span>
                <!-- /form -->
        </div>
    </div>

</body>

</html>
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