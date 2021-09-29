<html>
<head>
    <script>
        function addCommas(nStr) {
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        }
    </script>

    <script type="text/javascript">
        var nox = 0;

        function loaddatavalid() {
            var idmemb = $('#idmemb').val();
            var str = $('#scan').val();
            var kaet = $('#kategori2').val();
            var i = 0;
            var counter = 0;
            var total_all = parseInt($("#total_all").val());
            for (i = 0; i < document.getElementById('dataPrd').getElementsByTagName("tr").length + 1; i++) {
                var nama = $("tr#" + i).find('input').val();
                console.log("nama=" + nama);
                console.log("i=" + i);
                if ($('#scan').val() == nama) {
                    counter++;
                }
            }
            console.log("counter = " + counter);
            if (idmemb != '') {
                if (counter > 0) {
                    $('#scan').val('');
                    alert('Voucher yang anda masukkan sama (duplikat)');
                } else {
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url();?>transaction/scan_voucher/getVch",
                        dataType: 'json',
                        data: {
                            scan: str,
                            kat: kaet,
                            idmemb: idmemb
                        },
                        success: function (data) {
                            if (data.response == 'true') {
                                var dfno = data.arraydata.dfno;
                                var fullnm = data.arraydata.fullnm;
                                if (data.arraydata.dfno == null) {
                                    var dfno = '-';
                                    var fullnm = '-';
                                }
                                var rowCount = document.getElementById('dataPrd').getElementsByTagName("tr").length;
                                var no = rowCount + 1;
                                nox++;
                                $("<tr data-status=\"I\" id='" + nox + "'>" +
                                    "<td><input type=\"hidden\" name=\"idpendaftar[]\" id=\"idpendaftar\" value=\"" +
                                    data.scan + "\" />" + no + "</td>" +
                                    "<td><input type=\"hidden\" name=\"row[]\" id=\"row\" value=\"" +
                                    no + "\" />" + data.scan + "</td>" +
                                    "<td><input type=\"hidden\" name=\"jv[]\" id=\"jv\" value=\"" + data
                                    .arraydata.category + "\" />" + data.arraydata.category + "</td>" +
                                    "<td><input type=\"hidden\" name=\"memvc[]\" id=\"memvc\" value=\"" +
                                    dfno + "\" />" + dfno + "</td>" +
                                    "<td>" + fullnm + "</td>" +
                                    "<td><input type='hidden' id='amt" + nox + "' name='amt[]' value=" +
                                    parseInt(data.arraydata.VoucherAmt) +
                                    "><input readonly=\"yes\" type=\"text\" style=\"text-align:right;\" class=\"span12 typeahead\" id=\"tampilan\"  name=\"tampilan\" value=" +
                                    addCommas(parseInt(data.arraydata.VoucherAmt)) + " /></td>" +
                                    "<td><input type=\"button\" name=\"ibtnDel\" id=\"ibtnDel\" value=\"Delete\" onclick='deletes(" +
                                    nox + ")'  /></td>" +
                                    " </tr>").appendTo("#dataPrd");
                                console.log(rowCount);
                                total_all = total_all + parseInt(data.arraydata.VoucherAmt);
                                var gd = addCommas(total_all);
                                $("#total_all").val(total_all)
                                $("#total_all_show").val(gd)
                            } else if (data.response == 'expired') {
                                alert('Voucher sudah di Expired pada tanggal ' + data.expiri);
                                console.log(data.arraydata);
                            } else if (data.response == 'CPC') {
                                alert('Voucher produk tidak boleh dimasukkan ke kategori voucher Cash');
                                console.log(data.arraydata);
                            } else if (data.response == 'false') {
                                alert('Voucher tidak ditemukan');
                                console.log(data.arraydata);
                            } else if (data.response == 'kosong') {
                                alert('Mohon isi Kategori deposit');
                                console.log(data.arraydata);
                            } else if (data.response == 'claimed') {
                                alert('Voucher sudah diklaim pada tanggal ' + data.tgl + ', di stockies ' +
                                    data.oleh);
                                console.log(data.arraydata);
                            }
                            $('#scan').val('');
                            $('#idmemb').val('');
                            $("#idmemb").focus();
                        }
                    });
                }
            } else {
                $('#scan').val('');
                alert('Data member harus diisi');
            }
        }

        function deletes(frm) {
            var total_all = parseInt($("#total_all").val());
            var dp = $("#amt" + frm).val();
            total_all = total_all - parseInt(dp);
            var gd = addCommas(total_all);
            $("#total_all").val(total_all);
            $("#total_all_show").val(gd);
            $("tr#" + frm).remove();
        }

        function locker() {
            $("#kategori").val($("#kategori2").val());
            document.getElementById("kategori2").disabled = true;
        }
    </script>

    <script>
        function simpandata(ix) {
            $("#save").attr("disabled", "disabled");
            var masalah = 0;
            if (ix == 0) {
                $.post(('<?php echo base_url();?>transaction/scan_voucher/simpanScan'), $("#frm_ttp").serialize(), function (data) {
                    if (data == true) {
                        alert("Berhasil disimpan!");
                    }
                }, "json").fail(function () {
                    alert("Error requesting page");
                });
            } else {
                if ($('#kategori').val() == '') {
                    masalah++;
                    alert("Mohon isi jenis voucher");
                    $("#kategori").focus();
                }
                if ($('#substockistcode').val() == '') {
                    masalah++;
                    alert("Mohon isi data Stockies");
                    $("#substockistcode").focus();
                }
                if ($('#distributorcode').val() == '') {
                    masalah++;
                    alert("Mohon isi data member");
                    $("#distributorcode").focus();
                }
                if ($('#total_all').val() == '0') {
                    masalah++;
                    alert("Mohon isi Voucher");
                    $("#scan").focus();
                }
                if (masalah == 0) {
                    $.post(('<?php echo base_url();?>transaction/scan_voucher/simpanScan2/<?php echo $id ?>'), $("#frm_ttp")
                            .serialize(),
                            function (data) {
                                if (data != false) {
                                    alert("Berhasil disimpan dengan kode transaksi " + data);
                                    // window.location.replace('<?php echo site_url();?>c_sales_pvr/getDepositList');
                                    All.ajaxFormPost('formInputList','scan/list');
                                } else {
                                    alert("Voucher sudah di klaim");
                                    // window.location.replace('<?php echo site_url();?>c_sales_pvr/getDepositList');
                                }
                            }, "json")
                        .fail(function () {
                            alert("Error requesting page");
                        });
                }
            }
        }
    </script>
    <title>Scan dan Klaim Voucher</title>

</head>

<body>
    <div class="row-fluid">
        <div class="span12">
            <span id="formawal">
                <form class="form-horizontal" method="post" id="frm_ttp" name="frm_ttp">
                    <fieldset>
                        <div class="control-group">
                            <?php
                      foreach($stk as $data)
                      {
                          $dtsubname = $data->fullnm;
                          $dtuplinesub = $data->uplinesub;
                          $dtprice = $data->pricecode;
                      }
                      ?>
                            <table width="100%" border="1">
                                <tr>
                                    <td width="50%">
                                        <table width="100%">
                                            <tr>
                                                <td class="form_title_left">Sub Stockist Code&nbsp;</td>
                                                <td>
                                                    <input type="text" class="span12 typeahead" id="substockistcode"
                                                        <?php echo isset($edit) ? $edit :'' ?> name="substockistcode"
                                                        value="<?php echo $user; ?>" onchange="Sales.get_sc_info()" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="form_title_left">Sub Stockist Name&nbsp;</td>
                                                <td><input type="text" readonly="yes" class="span12 typeahead"
                                                        id="substkname" name="substkname"
                                                        value="<?php echo $dtsubname; ?>" /></td>
                                            </tr>
                                            <tr hidden>
                                                <td class="form_title_left">C/O Sub Stockist Code&nbsp;</td>
                                                <td>
                                                    <input type="text" readonly="yes" class="span12 typeahead"
                                                        id="uplinesub" name="uplinesub" value="<?php echo $user; ?>" />
                                                </td>
                                            </tr>
                                            <tr hidden>
                                                <td class="form_title_left">C/O Sub Stockist Name&nbsp;</td>
                                                <td>
                                                    <input type="text" readonly="yes" class="span12 typeahead"
                                                        id="uplinesubnm" name="uplinesubnm"
                                                        value="<?php echo $dtsubname; ?>" />
                                                </td>
                                            </tr>
                                            <?php echo isset($member) ? $member : ''; ?>
                                            <tr>
                                                <td class="form_title_left">Member Code&nbsp;</td>
                                                <td><input type="text" class="span12 typeahead" id="idmemb"
                                                        name="idmemb" style="text-transform:uppercase" /></td>
                                            </tr>
                                            <tr>
                                                <td class="form_title_left">Scan&nbsp;</td>
                                                <td>
                                                    <input type="text" class="span12 typeahead" id="scan" name="scan"
                                                        <?php echo isset($status) ? $status :'' ?>
                                                        onchange="loaddatavalid()">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="50%">
                                        <table width="100%">
                                            <?php echo isset($kategori) ? $kategori : ''; ?>
                                            <tr>
                                                <td class="form_title">Trx No.&nbsp;</td>
                                                <td>
                                                    <input type="text" readonly="yes" class="span12 typeahead"
                                                        id="trxno" name="trxno"
                                                        value="<?php echo isset($no_trx) ? $no_trx :'Auto' ?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="form_title">Trx Date.&nbsp;</td>
                                                <td>
                                                    <input type="text" readonly="yes" class="span12 typeahead"
                                                        id="trxdate" name="trxdate"
                                                        value="<?php echo isset($createdt) ? $createdt :$dateNow ?>" />
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <hr>
                            <br />
                            <!--<span id="show_form"></span> -->
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr bgcolor="#f4f4f4">
                                        <th>No</th>
                                        <th width="10%">Scan</th>
                                        <th width="10%">Kategori</th>
                                        <th width="25%">Kode Member</th>
                                        <th width="25%">Nama Member</th>
                                        <th width="25%">Voucher Amount</th>
                                        <th width="25%">Act</th>
                                    </tr>
                                </thead>
                                <tbody id="dataPrd">
                                    <?php
                          $no=0;
                          if(isset($LIST_DETAIL)):
                              foreach($LIST_DETAIL as $row):
                                  $no++;?>
                                    <tr>
                                        <td><?php echo $no;?></td>
                                        <td><?php echo $row->voucher_scan;?></td>
                                        <td><?php echo $row->kategori;?></td>
                                        <td><?php echo $row->dfno;?></td>
                                        <td><?php echo $row->fullnm;?></td>
                                        <td><input readonly="yes" type="text" style="text-align:right;"
                                                class="span12 typeahead"
                                                value="<?php echo number_format($row->nominal);?>"></td>
                                        <td>-</td>
                                    </tr>
                                    <?php endforeach;
                          endif;?>
                                </tbody>
                                <tbody id="SS">
                                    <tr>
                                        <td colspan="5" align="right">T O T A L</td>
                                        <td>
                                            <input type="hidden" style="text-align:right;" class="span12 typeahead"
                                                id="total_all" name="total_all"
                                                value="<?php echo isset($sisa) ? $sisa :'0' ?>" />
                                            <input readonly="yes" type="text" style="text-align:right;"
                                                class="span12 typeahead" id="total_all_show" name="total_all_show"
                                                value="<?php echo isset($sisa) ? number_format($sisa) :'0' ?>" />
                                        </td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <input type="hidden" id="action" name="action"
                                        value="<?php echo $form_action; ?>" />
                                    <input type="hidden" name="amount" id="amount" value="1" />
                                    <input type="hidden" name="tabidx" id="tabidx" value="1" />
                                    <input type="hidden" name="amt_record" id="amt_record" value="0" />
                                    <!--                          <input type="hidden" name="jenis" value="pv" />-->
                                </tbody>
                            </table>
                            <?php echo isset($label) ? $label : ''; ?>
                            <br />
                            <table width="100%">
                                <tr>
                                <?php if (isset($no_trx)) { ?>
                                    <td>
                                        <input type="button" class="btn btn-warning" name="back" value="Kembali"
                                            onclick="All.back_to_form(' .nextForm1', ' .mainForm')" />
                                    </td>
                                <?php } else { ?>
                                    <td>
                                        <input type="button" class="btn btn-warning" name="back" value="Batal"
                                            onclick="All.back_to_form(' .result', ' .mainForm')" />
                                    </td>
                                <?php } ?>
                                    <?php
                              if(isset($nosave)){
                                  echo'';
                              } else {
                              ?>
                                    <td align="right">
                                        <input type="button" class="btn btn-success span7" name="submit" value="Save"
                                            id="save" onclick="simpandata(<?php echo $parameter?>)" />
                                    </td>
                                    <?php }?>
                                </tr>
                            </table>
                            <!-- <input type="button" class="btn btn-success span14" name="submit" value="Save" id="save"/> -->
                            <!--</div> -->
                            <!-- end control-group -->
                    </fieldset>
                </form>
            </span>
            <div id="result"></div>
        </div>
    </div>
</body>

</html>