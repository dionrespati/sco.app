<html>
<?php if ($list == NULL) {
    echo "<div align=center class='alert alert-error'>No Record found..!!</div>";
 } else { ?>
<head>
    <script>
        function deleter() {
            var txt;
            var r = confirm("Press a button!");
            if (r == true) {
                txt = "You pressed OK!";
            } else {
                txt = "You pressed Cancel!";
            }
            document.getElementById("demo").innerHTML = txt;
        }
    </script>
    <script>
        function myFunction(el) {
            $.ajax({
                type: 'POST',
                url: All.get_url('scan/list/delete'),
                dataType: 'json',
                data: {
                    id: el.value
                },
                success: function(data) {
                    All.set_enable_button();
                    result = (data.res == 'true') ? data.message : 'Gagal dihapus';
                    alert(result);
                    All.ajaxFormPost('formInputList','scan/list');
                }
            });
        }
    </script>

    <title>List Deposit</title>
    <style>
        .sss {
            cursorpointer;
            color: #000;
            font-family: Verdana, helvetica, sans-serif;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="row-fluid" id="div_isi">
        <div class="span12">
            <span id="view_list_invent">
                <!-- form -->
                <table width="100%" class="table table-striped table-bordered bootstrap-datatable datatable"
                    align="center">
                    <thead>
                        <tr bgcolor="#f4f4f4">
                            <th>No</th>
                            <th>No Deposit</th>
                            <th>Tanggal Deposit</th>
                            <th>Created By</th>
                            <th>Stokis</th>
                            <th>Total Deposit</th>
                            <th>Sisa</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php $n = 1;
    if(count($list) > 0)
    foreach($list as $row) {
        $sisa=$row->total_deposit - $row->total_keluar;
        $dd="'$row->id'";
        $datess = $row->createdt;

        $linkDetVch = "All.ajaxShowDetailonNextForm('scan/list/detail/voucher/$row->id')";
        $linkTTP = "All.ajaxShowDetailonNextForm('scan/list/detail/ttp/$row->id')";

        //set awal, kondisi = status sudah digenerate
        $statusx = 'Sudah di Generate';
        //$action1='<a class="btn btn-sm btn-danger" onclick="Stockist.detailVoucher(\'scan/list/detail/voucher/'.$row->id.'\')" title="Edit">View Voucher</a>';
        $action1="<a class='btn btn-sm btn-danger' onclick=$linkDetVch title='Edit'>View Voucher</a>";
        //$action2='<a class="btn btn-sm btn-success" onclick="$linkViewTTP" title="TTP">View TTP</a>';
        $action2="<a class='btn btn-sm btn-success' onclick=$linkTTP title='TTP'>View TTP</a>";
        $action3='';
        $action4='';
        if($row->status == '1') {
            $statusx = 'Aktif';
            /* $action1='<button class="btn btn-sm btn-warning" title="Tambah Voucher"  onclick="Stockist.detailVoucher(\'scan/list/detail/voucher/'.$row->id.'\')"><i class="icon-briefcase icon-white"></i></button>';
            $action2='<button class="btn btn-sm btn-primary" title="Tambah TTP" onclick="Stockist.detailTtp(\'scan/list/detail/ttp/'.$row->id.'\')"><i class="icon-shopping-cart icon-white"></i></button>'; */

            $action1="<button class='btn btn-sm btn-warning' title='Tambah Voucher'  onclick=$linkDetVch><i class='icon-briefcase icon-white'></i></button>";
            $action2="<button class='btn btn-sm btn-primary' title='Tambah TTP' onclick=$linkTTP><i class='icon-shopping-cart icon-white'></i></button>";
            if($row->total_keluar==0) {
                $action3="<button class='btn btn-sm btn-danger' onClick='myFunction(this)' value='$row->id' title='Hapus TTP'><i class='icon-trash icon-white'></i></button>";
            }
            $action4="<button title='Re-calculate' class='btn btn-sm btn-info' href=".base_url()."scan/reCalculate/".$row->id." title='TTP'><i class='icon-wrench icon-white'></i></button>";
        } 
        echo "
        <tr class =\"record\" id=\"$n\">
            <td>$n</td>
            <td align=center>&nbsp;$row->no_trx</td>
            <td align=center>".$row->createdt."</td>
            <td align=center>$row->createnm</td>
            <td align=center>$row->loccd</td>
            <td style=\"text-align:right\">".number_format($row->total_deposit,0,"",".")."</td>
            <td style=\"text-align:right\">".number_format($sisa,0,"",".")."</td>
            <td align=center>$statusx</td>
            <td style=\"text-align:center\">$action1 $action2 $action3 $action4</td>
        </tr>";
        $n++;
    } ?>
                    </tbody>
                </table>
            </span>
            <!-- /form -->
        </div>
    </div>
<?php } ?>
</body>

</html>
<script type="text/javascript">
$(document).ready(function()
{
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

<script>
    function confirmDialog() {
        return confirm('Apakah Anda yakin akan menghapus data ini?')
    }
</script>