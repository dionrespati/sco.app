var $ = jQuery;

var Stkbarcode = {
    detail: function (id) {
        // alert('ajax');
        All.set_disable_button();
        $.ajax({
            dataType: 'json',
            url: All.get_url("stock_barcode/detailTransaction2/" + id),
            type: 'GET',
            success:
                function (data) {
                    All.set_enable_button();
                    if (data.response == 'true') {
                        $(All.get_active_tab() + ' .mainForm').hide();
                        //$(".result").hide();
                        $(All.get_active_tab() + ' .nextForm1').html(null);
                        var header = data.header;
                        var detail = data.detail;
                        var rowshtml = "<form><table width='90%' class='table table-striped table-bordered bootstrap-datatable datatable'>";
                        rowshtml += "<thead>";
                        rowshtml += "<tr bgcolor=#f4f4f4><th colspan=5>Detail Transaksi</th></tr>";
                        $.each(detail, function (key, value) {

                            rowshtml += "<tr><th width=20%><div align=left>No Transaksi / TTP</div></th><th colspan=3><div align=left>" + value.orderno + "&nbsp;/&nbsp;" + value.trcd + "</div></th></tr>";

                            rowshtml += "<input type=hidden id=orderno value=" + value.orderno + " />";
                            rowshtml += "<tr><th width=20%><div align=left>Member</div></th><th colspan=3 ><div align=left>" + value.dfno + "&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;" + value.fullnm + "</div></th></tr>";
                            rowshtml += "<tr><th width=20%><div align=left>TGL Transaksi</div></th><th colspan=4 ><div align=left>" + value.etdt + "</div></th></tr>";

                        });
                        rowshtml += "<form><table width='90%' class='table table-striped table-bordered bootstrap-datatable datatable'>";
                        rowshtml += "<thead>";
                        rowshtml += "<tr bgcolor=#f4f4f4>";
                        rowshtml += "<th width=5%>No</th>";
                        rowshtml += "<th width=20%>Kode Produk</th>";
                        rowshtml += "<th width=50%>Nama Produk</th>";
                        rowshtml += "<th width=10%>Jumlah Produk</th>";
                        rowshtml += "<th width=10%>Jumlah Terbarcode</th>";
                        rowshtml += "<th>Masukan Barcode</th>";
                        rowshtml += "</tr></thead><tbody>";
                        $.each(header, function (key, value) {
                            var sisa_scan = value.qtyord - value.jumlah_sdh_dibarcode;
                            var qtyord = parseInt(value.qtyord);
                            rowshtml += "<tr id=" + (key + 1) + ">";
                            rowshtml += "<td><div align=right>" + (key + 1) + "</div></td>";
                            rowshtml += "<td><div align=left><a href='#' id=" + (key + 1) + " onclick='javascript:Stkbarcode.getListProductDetail(this)'>" + value.prdcd + "</a></div></td>";
                            rowshtml += "<input type=hidden id=prdcd" + (key + 1) + " value=" + value.prdcd + " />";
                            rowshtml += "<input type=hidden id=trcd value=" + value.trcd + " />";
                            rowshtml += "<td><div id=prdnm" + (key + 1) + " align=left>" + value.prdnm + "</div></td>";
                            rowshtml += "<input type=hidden id=qty" + (key + 1) + " value=" + sisa_scan + " />";
                            rowshtml += "<input type=hidden id=qtyasli" + (key + 1) + " value=" + value.qtyord + " />";
                            rowshtml += "<input type=hidden id=jumlah_sdh_dibarcode" + (key + 1) + " value=" + value.jumlah_sdh_dibarcode + " />";
                            rowshtml += "<td><div align=right>" + qtyord + "</div></td>";
                            if (value.jumlah_sdh_dibarcode > 0) {
                                rowshtml += "<td><div align=right><a href='#' id=" + (key + 1) + " onclick='javascript:Stkbarcode.getListProductDetail(this)'>" + value.jumlah_sdh_dibarcode + "</a></div></td>";
                            } else {
                                rowshtml += "<td><div align=right>" + value.jumlah_sdh_dibarcode + "</div></td>";
                            }
                            rowshtml += "<td><div id='tombol_scan" + (key + 1) + "'><a class=' btn btn-primary'  id=" + (key + 1) + " onclick='javascript:Stkbarcode.getListProductDetail(this)' href='#'>Scan</a></div></td>";
                            rowshtml += "</tr>";
                        });
                        rowshtml += "</tbody></table>";
                        /* var clear_div = " .nextForm1";
                        var back_div = " .mainForm"; */
                        rowshtml += "<table><tr><td><input class='btn btn-warning' type='button' value='Back' onclick='Stkbarcode.back1()' /></td></tr></table>";
                        rowshtml += "<table><tr><td><a href=''></a></td></tr></table>";
                        rowshtml += "</form>";
                        $(All.get_active_tab() + ' .nextForm1').append(rowshtml);
                    }
                    else {
                        alert("Data not found..!!");
                    }
                },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + ':' + xhr.status);
                All.set_enable_button();
            }
        });
    },
    cari: function () {
        var id = $("#pencarian").val();
        if (id == '1') {
            $(".pencarian_ttp").show();
            $(".pencarian_range").hide();

        } if (id == '2') {
            $(".pencarian_ttp").hide();
            $(".pencarian_range").show();
        }
        if (id == '0') {
            alert('Anda Belum Memilih...!');
        }
    },

    back1: function () {

        $(All.get_active_tab() + ' .nextForm1').html(null);
        $(All.get_active_tab() + ' .mainForm').show();
    },

    searchTrxBarcode: function () {
        All.set_disable_button();
        All.get_image_load2();
        $.post(All.get_url('stock_barcode/detailTransactionRange'), $("#fromScanSearch").serialize(), function (data) {
            All.set_enable_button();
            if (data.response == 'true') {
                $(All.get_active_tab() + ' .result').html(null);
                // $(".result").html(null);
                // $(".load").html(null);
                var arraydata = data.arraydata;
                var rowshtml = "<form><table width='100%' class='table table-striped table-bordered bootstrap-datatable datatable'>";
                rowshtml += "<thead>";
                rowshtml += "<tr bgcolor=#f4f4f4><th colspan=6>List Transaction</th></tr>";
                rowshtml += "<tr bgcolor=#f4f4f4>";
                rowshtml += "<th width=5%>No</th>";
                rowshtml += "<th width=15%>Order No</th>";
                rowshtml += "<th width=15%>Tgl Transaksi</ >";
                rowshtml += "<th width=35%>Id Member</th>";
                rowshtml += "<th width=10%>TBV</th>";
                rowshtml += "<th width=20%>TDP</th>";
                // rowshtml += "<th></th>";
                rowshtml += "</tr></thead><tbody>";
                $.each(arraydata, function (key, value) {
                    rowshtml += "<tr id=" + (key + 1) + ">";
                    rowshtml += "<td><div align=right>" + (key + 1) + "</div></td>";
                    var ntdp = Stkbarcode.number_format(value.tdp);
                    rowshtml += "<td><div align=center><input type=hidden id=orderno" + (key + 1) + " value=" + value.orderno + " /><a href='#' id=" + (key + 1) + " onclick='javascript:Stkbarcode.new(this)'>" + value.orderno + "</a></div></td>";
                    rowshtml += "<td><div align=center><input type=hidden id=trcd" + (key + 1) + " value=" + value.trcd + " />" + value.etdt + "</a></div></td>";
                    rowshtml += "<td><div align=center><input type=hidden id=dfno" + (key + 1) + " value=" + value.dfno + " />" + value.dfno + " / " + value.fullnm + "</div></td>";
                    rowshtml += "<td><div align=center><input type=hidden id=fullnm" + (key + 1) + " value=" + value.tbv + " />" + parseInt(value.tbv) + "</div></td>";
                    rowshtml += "<td><div align=right><input type=hidden id=fullnm" + (key + 1) + " value=" + value.tdp + " />" + ntdp + "</div></td>";
                    //  rowshtml += "<td><div id='tombol_scan"+(key+1)+"'><a class=' btn btn-primary'  id="+(key+1)+" onclick='javascript:Stkbarcode.getListProductDetail(this)' href='#'>Detail</a></div>";
                    rowshtml += "</tr>";
                });
                rowshtml += "</tbody></table></form>";
                $(All.get_active_tab() + ' .result').append(rowshtml);
                // $(".result").append(rowshtml);
                // $(".result").html(null);
                All.set_datatable();
            } else {
                // $(".result").html(null);
                $(All.get_active_tab() + ' .result').html(null);
                var param = "No result found";
                var err = "<div class='alert alert-error' align=center>" + param + "</div>";
                $(All.get_active_tab() + ' .result').append(err);
                // All.set_error_message("result");
            }
        }, "json").fail(function () {
            alert("Error requesting page");
            $(".result").html(null);
            All.set_enable_button();
        });
    },

    searchTrx: function () {
        localStorage.clear();

        All.set_disable_button();
        All.get_image_load2();

        $.post(All.get_url('stock_barcode/detailTransaction'), $("#fromScanSearch").serialize(), function (data) {
            All.set_enable_button();
            $(All.get_active_tab() + ' .result').html(null);
            if (data.response == 'true') {
                $(All.get_active_tab() + ' .nextForm1').html(null);
                var header = data.header;
                var detail = data.detail;
                var rowshtml = "<form><table width='90%' class='table table-striped table-bordered bootstrap-datatable datatable'>";
                rowshtml += "<thead>";
                rowshtml += "<tr bgcolor=#f4f4f4><th colspan=5>Detail Transaksi</th></tr>";
                $.each(detail, function (key, value) {

                    rowshtml += "<tr><th width=20%><div align=left>No Transaksi / TTP</div></th><th colspan=3><div align=left>" + value.orderno + "&nbsp;/&nbsp;" + value.trcd + "</div></th></tr>";
                    rowshtml += "<tr><th width=20%><div align=left>Member</div></th><th colspan=3 ><div align=left>" + value.dfno + "&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;" + value.fullnm + "</div></th></tr>";
                    rowshtml += "<tr><th width=20%><div align=left>TGL Transaksi</div></th><th colspan=4 ><div align=left>" + value.etdt + "</div></th></tr>";

                });
                rowshtml += "<form><table width='90%' class='table table-striped table-bordered bootstrap-datatable datatable'>";
                rowshtml += "<thead>";
                rowshtml += "<tr bgcolor=#f4f4f4>";
                rowshtml += "<th width=5%>No</th>";
                rowshtml += "<th width=20%>Kode Produk</th>";
                rowshtml += "<th width=50%>Nama Produk</th>";
                rowshtml += "<th width=10%>Jumlah Produk</th>";
                rowshtml += "<th width=10%>Jumlah Terbarcode</th>";
                rowshtml += "<th>Input Barcode</th>";
                rowshtml += "</tr></thead><tbody>";
                $.each(header, function (key, value) {
                    var sisa_scan = value.qtyord - value.jumlah_sdh_dibarcode;
                    var qtyord = parseInt(value.qtyord);
                    rowshtml += "<tr id=" + (key + 1) + ">";
                    rowshtml += "<td><div align=right>" + (key + 1) + "</div></td>";
                    rowshtml += "<td><div align=left><a href='#' id=" + (key + 1) + " onclick='javascript:Stkbarcode.getListProductDetail(this)'>" + value.prdcd + "</a></div></td>";
                    rowshtml += "<input type=hidden id=prdcd" + (key + 1) + " value=" + value.prdcd + " />";
                    rowshtml += "<input type=hidden id=trcd value=" + value.trcd + " />";
                    rowshtml += "<td><div id=prdnm" + (key + 1) + " align=left>" + value.prdnm + "</div></td>";
                    rowshtml += "<input type=hidden id=qty" + (key + 1) + " value=" + sisa_scan + " />";
                    rowshtml += "<input type=hidden id=qtyasli" + (key + 1) + " value=" + value.qtyord + " />";
                    rowshtml += "<input type=hidden id=jumlah_sdh_dibarcode" + (key + 1) + " value=" + value.jumlah_sdh_dibarcode + " />";
                    rowshtml += "<td><div align=right>" + qtyord + "</div></td>";
                    if (value.jumlah_sdh_dibarcode > 0) {
                        rowshtml += "<td><div align=right><a href='#' id=" + (key + 1) + " onclick='javascript:Stkbarcode.getListProductDetail(this)'>" + value.jumlah_sdh_dibarcode + "</a></div></td>";
                    } else {
                        rowshtml += "<td><div align=right>" + value.jumlah_sdh_dibarcode + "</div></td>";
                    }
                    rowshtml += "<td><div id='tombol_scan" + (key + 1) + "'><a class=' btn btn-primary'  id=" + (key + 1) + " onclick='javascript:Stkbarcode.getListProductDetail(this)' href='#'>Scan</a></div></td>";
                    rowshtml += "</tr>";
                });
                rowshtml += "</tbody></table>";
                rowshtml += "<table><tr><td><input class='btn btn-warning' type=button value=Back onclick=All.reload_page('stk/barcode') /></td></tr></table>";
                rowshtml += "</form>";
                $(All.get_active_tab() + ' .nextForm1').append(rowshtml);
            } else {
                $(All.get_active_tab() + ' .result').html(null);
                var param = "No result found";
                var err = "<div class='alert alert-error' align=center>" + param + "</div>";
                $(All.get_active_tab() + ' .result').append(err);
            }
        }, "json").fail(function () {
            alert("Error requesting page");
            $(All.get_active_tab() + ' .result').html(null);
            All.set_enable_button();
        });
    },
    number_format: function (number, decimals, decPoint, thousandsSep) {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
        const n = !isFinite(+number) ? 0 : +number
        const prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
        const sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
        const dec = (typeof decPoint === 'undefined') ? '.' : decPoint
        let s = ''
        const toFixedFix = function (n, prec) {
            if (('' + n).indexOf('e') === -1) {
                return +(Math.round(n + 'e+' + prec) + 'e-' + prec)
            } else {
                const arr = ('' + n).split('e')
                let sig = ''
                if (+arr[1] + prec > 0) {
                    sig = '+'
                }
                return (+(Math.round(+arr[0] + 'e' + sig + (+arr[1] + prec)) + 'e-' + prec)).toFixed(prec)
            }
        }
        // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec).toString() : '' + Math.round(n)).split('.')
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || ''
            s[1] += new Array(prec - s[1].length + 1).join('0')
        }
        return s.join(dec)
    },

    new: function (theLink) {
        var param = theLink.id;
        var trcd = $(All.get_active_tab() + ' #trcd' + param).val();
        // var trcd = $("#trcd" + param).val();
        Stkbarcode.detail(trcd);
    },

    backForm: function () {
        var trcd = $(All.get_active_tab() + " #trcd").val();
        //$(".nextForm2").html(null);

        $(All.get_active_tab() + ' .nextForm2').html(null);
        $(All.get_active_tab() + ' .nextForm1').html(null);
        $(All.get_active_tab() + ' .nextForm1').show();

        Stkbarcode.detail(trcd);
        // console.log(trcd);
    },

    getListProductDetail: function (theLink) {
        var param = theLink.id;
        var prdcd = $(All.get_active_tab() + '#prdcd' + param).val();
        var prdnm = $(All.get_active_tab() + '#prdnm' + param).text();
        var orderno = $(All.get_active_tab() + ' #orderno').val();
        var trcd = $(All.get_active_tab() + ' #trcd').val();
        var qty = parseInt($(All.get_active_tab() + ' #qty' + param).val());
        var id = trcd + '-' + prdcd;
        var qtyasli = parseInt($(All.get_active_tab() + ' #qtyasli' + param).val());
        var jumlah_sdh_dibarcode = parseInt($(All.get_active_tab() + ' #jumlah_sdh_dibarcode' + param).val());

        $(All.get_active_tab() + ' .nextForm1').hide();
        $(All.get_active_tab() + ' .nextForm2').html(null);
        $(All.get_active_tab() + ' .nextForm1').html(null);
        $(All.get_active_tab() + ' .mainForm').hide();


        All.set_disable_button();
        $.ajax({
            dataType: 'json',
            url: All.get_url("stock_barcode/detailScaProduk/" + id),
            type: 'GET',
            success:
                function (data) {
                    All.set_enable_button();
                    if (data.response == 'true') {
                        var detail = data.detail;
                        var rowshtml = "<form id=saveBarcode><table width='80%' class='table table-striped table-bordered'>";
                        rowshtml += "<thead><tr>";
                        rowshtml += "<td width=17%>No Transaksi / TTP</td><td colspan=2>" + orderno + "&nbsp;/&nbsp;" + trcd + "</td></tr>";
                        rowshtml += "<tr><td>Kode Produk</td><td colspan=2>" + prdcd + "</td></tr>";
                        rowshtml += "<tr><td>Nama Produk</td><td colspan=2>" + prdnm + "</td></tr>";
                        rowshtml += "<input type=hidden id=jumlah_sdh_dibarcode value=" + jumlah_sdh_dibarcode + ">";
                        rowshtml += "<tr><td>Jumlah</td><td colspan=2>" + qtyasli + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;<font color=red>Total Qty Scanned : <span id=showQtyScan>" + jumlah_sdh_dibarcode + "</span></font>&nbsp;)</td></tr>";
                        rowshtml += "<tr><td>Tipe Input Barcode</td>";
                        rowshtml += "<td colspan=2><select id=barcodeType class=nospacing>";
                        rowshtml += "<option value=1>Single/Multiple Barcode</option>";
                        rowshtml += "</select>&nbsp;<input type=button id=addBarType value=Tambah class='btn btn-small btn-primary' onclick=Stkbarcode.addBarcodeInput() />&nbsp;<input id=clearBtn class='btn btn-small btn-danger' type=button value=Bersihkan onclick=Stkbarcode.clearbarcode() /></td></tr>";
                        rowshtml += "</thead>";
                        // rowshtml += "<div id=rowBarcode>";

                        var multiSum = 0;
                        var mul = 0;
                        $.each(detail, function (key, value) {

                            rowshtml += "<tr id=parame" + key + ">";
                            rowshtml += "<td>Barcode :&nbsp;</td>";
                            rowshtml += "<td width='50%'><input readonly=readonly type=text id=multi" + key + " name=multi[] class='span12 oldmulti multiinp nospacing' value=" + value.barcode + " onkeypress='return Stkbarcode.countMultiScan(this.value, event, " + key + ")' /></td>";
                            rowshtml += "<td>Qty Scanned :<input readonly=readonly value=1 type=text id=countScanMultiple" + key + " class=' nospacing' />";
                            rowshtml += "<a class='btn btn-mini btn-danger' onclick='Stkbarcode.deleteBarcode(" + key + ")'> <i class='icon-trash icon-white'></i></a>";
                            // rowshtml += "&nbsp;<input class='btn btn-small btn-danger' disabled type='button' value='Delete' onclick='Stkbarcode.deleteBarcode(" + key + ")'>";
                            rowshtml += "</td></tr>";

                            // $("#devRows").append(rowshtml);
                            $(".nospacing").css('margin-bottom', '1px');
                            $("#multiSum").val(key);
                            $("#multi" + key).attr('readonly', 'readonly');
                            $("#multi" + key).focus();
                            mul = mul + 1;
                        });
                        mul = mul - 1;
                        rowshtml += "</thead><tbody id=rowBarcode>";
                        rowshtml += "</tbody>";

                        rowshtml += "</table>";
                        rowshtml += "<table><tr><td id=buttonSave>";
                        rowshtml += "<input class='btn btn-warning' type=button value=Back onclick='javascript:Stkbarcode.backForm()'/>";

                        // rowshtml += "<div id=buttonSave></div>";
                        // rowshtml += "&nbsp;<input id=saveBtn class='btn btn-primary' type=button value=Save onclick=Stkbarcode.savebarcode() />";

                        rowshtml += "</td>";
                        rowshtml += "<input type=hidden id=trcd name=trcd value=" + trcd + " />";
                        rowshtml += "<input type=hidden id=prdcd name=prdcd value=" + prdcd + " />";
                        rowshtml += "<input type=hidden id=qtysum name=qtysum value=" + qtyasli + " />";
                        rowshtml += "<input type=hidden id=multiSum name=multiSum value=" + mul + " />";
                        rowshtml += "<input type=hidden id=defaultmultiSum name=defaultmultiSum value=" + mul + " />";
                        rowshtml += "<div id=realInput></div>";
                        rowshtml += "</tr></table>";

                        rowshtml += "</form>";
                        $(All.get_active_tab() + ' .nextForm2').append(rowshtml);
                        $(".nospacing").css('margin-bottom', '1px');
                        $("#multi" + mul).focus();

                    }
                    if (data.response == 'false') {
                        var mul = 0;
                        var detail = data.detail;
                        var rowshtml = "<form id=saveBarcode><table width='80%' class='table table-striped table-bordered'>";
                        rowshtml += "<thead><tr>";
                        rowshtml += "<td width=17%>No Transaksi / TTP</td><td colspan=2>" + trcd + "&nbsp;/&nbsp;" + orderno + "</td></tr>";
                        rowshtml += "<tr><td>Kode Produk</td><td colspan=2>" + prdcd + "</td></tr>";
                        rowshtml += "<tr><td>Nama Produk</td><td colspan=2>" + prdnm + "</td></tr>";
                        rowshtml += "<input type=hidden id=jumlah_sdh_dibarcode value=" + jumlah_sdh_dibarcode + ">";
                        rowshtml += "<tr><td>Jumlah</td><td colspan=2>" + qtyasli + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;<font color=red>Total Qty Scanned : <span id=showQtyScan>" + jumlah_sdh_dibarcode + "</span></font>&nbsp;)</td></tr>";
                        rowshtml += "<tr><td>Tipe Input Barcode</td>";
                        rowshtml += "<td colspan=2><select id=barcodeType class=nospacing>";
                        rowshtml += "<option value=1>Single/Multiple Barcode</option>";
                        rowshtml += "</select>&nbsp;<input type=button id=addBarType value=Tambah class='btn btn-small btn-primary' onclick=Stkbarcode.addBarcodeInput() />&nbsp;<input id=clearBtn class='btn btn-small btn-danger' type=button value=Bersihkan onclick=Stkbarcode.clearbarcode() /></td></tr>";
                        rowshtml += "</thead><tbody id=rowBarcode>";
                        rowshtml += "<tr id=parame0>";
                        rowshtml += "<td>Barcode :&nbsp;</td>";
                        rowshtml += "<td width='50%'><input type=text id=multi0 name=multi[] class='span12 multiinp' onkeypress='return Stkbarcode.countMultiScan(this.value, event, 0)' /></td>";
                        rowshtml += "<td>Qty Scanned :<input readonly=readonly type=text id=countScanMultiple0 class='nospacing' />";
                        rowshtml += "<a class='btn btn-mini btn-danger' onclick='Stkbarcode.deleteBarcode(0)'> <i class='icon-trash icon-white'></i></a>";
                        // <a onclick="javascript:Stockist.delPayment(this)" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i></a>
                        // rowshtml += "&nbsp;<input class='btn btn-small btn-danger' type='button' value='Delete' onclick='Stkbarcode.deleteBarcode(0)'>";
                        rowshtml += "</td></tr>";
                        rowshtml += "</tbody>";
                        rowshtml += "</table>";
                        rowshtml += "<table><tr ><td id=saveBtn>";
                        rowshtml += "<input class='btn btn-warning' type=button value=Back onclick='javascript:Stkbarcode.backForm()' />";
                        // rowshtml += "<div id=buttonSave></div>";
                        rowshtml += "&nbsp;<input id=saveBtn class='btn btn-primary' type=button value=Save onclick=Stkbarcode.savebarcode() />";

                        rowshtml += "</td>";
                        rowshtml += "<input type=hidden id=trcd name=trcd value=" + trcd + " />";
                        rowshtml += "<input type=hidden id=prdcd name=prdcd value=" + prdcd + " />";
                        rowshtml += "<input type=hidden id=qtysum name=qtysum value=" + qtyasli + " />";
                        rowshtml += "<input type=hidden id=multiSum name=multiSum value=" + mul + " />";
                        rowshtml += "<input type=hidden id=defaultmultiSum name=defaultmultiSum value=" + mul + " />";
                        rowshtml += "<div id=realInput></div>";
                        rowshtml += "</tr></table>";

                        rowshtml += "</form>";

                        // var buttonSave = "&nbsp;<input id=saveBtn class='butonSave btn btn-primary' type=button value=Save onclick=Stkbarcode.savebarcode() />";
                        // $("#buttonSave").append(buttonSave);
                        $(All.get_active_tab() + ' .nextForm2').append(rowshtml);
                        $(".nospacing").css('margin-bottom', '1px');
                        $("#multi1").focus();

                    }

                },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + ':' + xhr.status);
                All.set_enable_button();
            }
        });
    },
    showSendTo: function (nilai, setto) {

        if (nilai == "stock_move") {
            $(All.get_active_tab() + " #pilGenPl").html(null);
            Stkbarcode.showListWH(setto);
            //$(All.get_active_tab() + " #choose_dest").attr("disabled");
        } else if (nilai == "gen_pl") {
            Stkbarcode.check_gen_pl();
            //Stkbarcode.check_gen_pl();
            //$(All.get_active_tab() + " #choose_dest").removeAttr("disabled");
        } else if (nilai == "sales_sc") {
            //$(All.get_active_tab() + " #choose_dest").attr("disabled");
            $(All.get_active_tab() + " #pilGenPl").html(null);
            $(All.get_active_tab() + setto).html(null);
            var rowhtml = "<label class='control-label' for='typeahead'>Send to Stockist</label>";
            rowhtml += "<div class='controls' >";
            rowhtml += "<input type=text id=sendTo name=sendTo placeholder='ID Stockist' onchange=All.getFullNameByID(this.value,'db2/get/fullnm/from/mssc/loccd','#info') />";
            rowhtml += "<input readonly=readonly type=text id=info name=info style='width: 350px' />";
            rowhtml += "</div><div class='clearfix'></div>";
            $(All.get_active_tab() + setto).append(rowhtml);
        } else if (nilai == "bc_track") {
            $(All.get_active_tab() + " #pilGenPl").html(null);
            $(All.get_active_tab() + setto).html(null);
            var rowhtml = "<label class='control-label' for='typeahead'>Barcode</label>";
            rowhtml += "<div class='controls' >";
            rowhtml += "<input type=text id=sendTo name=sendTo placeholder='Kode Barcode' class='span5' />";
            rowhtml += "</div><div class='clearfix'></div>";
            $(All.get_active_tab() + setto).append(rowhtml);
        } else {
            //$(All.get_active_tab() + " #choose_dest").attr("disabled");
            $(All.get_active_tab() + " #pilGenPl").html(null);
            $(All.get_active_tab() + setto).html(null);
            var rowhtml = "<label class='control-label' for='typeahead'>Send to Distributor</label>";
            rowhtml += "<div class='controls' >";
            rowhtml += "<input type=text id=sendTo name=sendTo placeholder='ID Member' onchange=All.getFullNameByID(this.value,'db2/get/fullnm/from/msmemb/dfno','#info') />";
            rowhtml += "<input readonly=readonly type=text id=info name=info style='width: 350px' />";
            rowhtml += "</div><div class='clearfix'></div>";
            $(All.get_active_tab() + setto).append(rowhtml);
        }
    },

    check_gen_pl: function () {
        $(All.get_active_tab() + "#divSendTo").html(null);
        $(All.get_active_tab() + "#pilGenPl").html(null);
        var rowhtml = "<label class='control-label' for='typeahead'>Destination</label>";
        rowhtml += "<div class='controls' >";
        rowhtml += "<select class='span5' id='choose_dest' name='choose_dest' onchange=Stkbarcode.showSendToGPL(this.value,'#divSendTo')>";
        rowhtml += "<option value=''>--Select Here--</option>";
        rowhtml += "<option value='sales_sc'>Stockist</option>";
        rowhtml += "<option value='sales_inv'>Distributor</option>";
        rowhtml += "<option value='stock_move'>Warehouse</option>";
        rowhtml += "</select></div>";
        rowhtml += "<input type='hidden' id=info name=info />";
        rowhtml += "<div class='clearfix'></div>";
        $(All.get_active_tab() + "#pilGenPl").append(rowhtml);
    },

    showSendToGPL: function (nilai, setto) {
        if (nilai == "sales_sc") {
            //$(All.get_active_tab() + " #choose_dest").attr("disabled");
            //$(All.get_active_tab() + " #pilGenPl").html(null);
            $(All.get_active_tab() + setto).html(null);
            var rowhtml = "<label class='control-label' for='typeahead'>Send to Stockist</label>";
            rowhtml += "<div class='controls' >";
            rowhtml += "<input type=text id=sendTo name=sendTo placeholder='ID Stockist' onchange=All.getFullNameByID(this.value,'db2/get/fullnm/from/mssc/loccd','#info') />";
            rowhtml += "<input readonly=readonly type=text id=info name=info style='width: 350px' />";
            rowhtml += "</div><div class='clearfix'></div>";
            $(All.get_active_tab() + setto).append(rowhtml);
        } else if (nilai == "stock_move") {
            //$(All.get_active_tab() + " #pilGenPl").html(null);
            Stkbarcode.showListWH(setto);
            //$(All.get_active_tab() + " #choose_dest").attr("disabled");
        } else {
            // $(All.get_active_tab() + " #pilGenPl").html(null);
            $(All.get_active_tab() + setto).html(null);
            var rowhtml = "<label class='control-label' for='typeahead'>Send to Distributor</label>";
            rowhtml += "<div class='controls' >";
            rowhtml += "<input type=text id=sendTo name=sendTo placeholder='ID Member' onchange=All.getFullNameByID(this.value,'db2/get/fullnm/from/msmemb/dfno','#info') />";
            rowhtml += "<input readonly=readonly type=text id=info name=info style='width: 350px' />";
            rowhtml += "</div><div class='clearfix'></div>";
            $(All.get_active_tab() + setto).append(rowhtml);
        }
    },

    showListWH: function (setto) {
        $.ajax({
            url: All.get_url("stk/barcode/wh/list"),
            type: 'GET',
            dataType: 'json',
            success:
                function (data) {
                    All.set_enable_button();
                    if (data.response == 'true') {
                        var arraydata = data.arrayData;
                        $(All.get_active_tab() + setto).html(null);
                        var rowhtml = "<label class='control-label' for='typeahead'>Send to Warehouse</label>";
                        rowhtml += "<div class='controls' >";
                        rowhtml += "<select class='span5' id='sendTo' name='sendTo' onchange='Stkbarcode.setWHinfo()'>";
                        rowhtml += "<option value=''>--Select Here--</option>";
                        $.each(arraydata, function (key, value) {
                            rowhtml += "<option value='" + value.code + "'>" + value.description + "</option>";
                        });

                        rowhtml += "</select></div>";
                        rowhtml += "<input type='hidden' id=info name=info />";
                        rowhtml += "<div class='clearfix'></div>";
                        $(All.get_active_tab() + setto).append(rowhtml);
                        //console.log("isi :" +setto);
                    } else {
                        alert("Data not found..!!");
                    }
                },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + ':' + xhr.status);
                All.set_enable_button();
            }
        });
    },

    setWHinfo: function () {
        var x = $(All.get_active_tab() + "#sendTo option:selected").text();
        $(All.get_active_tab() + "#info").val(x);
    },

    getListProductBarcode: function (param) {
        //var param = theLink.id;
        var prdcd = $(All.get_active_tab() + "#prdcd" + param).val();
        var prdnm = $(All.get_active_tab() + "#prdnm" + param).val();
        var trcd = $(All.get_active_tab() + "#trcd").val();
        var qty = parseInt($(All.get_active_tab() + "#qty" + param).val());

        $.ajax({
            dataType: 'json',
            url: All.get_url("stk/barcode/process/" + trcd + "/" + prdcd),
            type: 'GET',
            success:
                function (data) {
                    All.set_enable_button();
                    if (data.response == 'true') {

                        $(All.get_active_tab() + ".nextForm1").hide();
                        $(All.get_active_tab() + ".nextForm2").html(null);
                        var arraydata = data.arrayData;
                        var rowshtml = "<form id=saveBarcode><table width='60%' class='table table-striped table-bordered bootstrap-datatable datatable'>";
                        rowshtml += "<thead><tr bgcolor=#f4f4f4>";
                        rowshtml += "<th width=10%>No</th>";
                        rowshtml += "<th width=25%>Trx No</th>";
                        rowshtml += "<th width=35%>Product Code</th>";
                        rowshtml += "<th>Barcode</th>";
                        rowshtml += "</tr></thead><tbody>";
                        $.each(arraydata, function (key, value) {
                            rowshtml += "<tr id=" + (key + 1) + ">";
                            rowshtml += "<td><div align=right>" + (key + 1) + "</div></td>";
                            rowshtml += "<td><div align=center>" + value.trcd + "</div></td>";
                            rowshtml += "<td><div align=center>" + value.prdcd + "</div></td>";
                            rowshtml += "<td><div align=center>" + value.prdcd_bc + "</div></td>";
                            rowshtml += "</tr>";
                        });
                        rowshtml += "</tbody></table>";
                        rowshtml += "<table><tr><td>";
                        rowshtml += "<input class='btn btn-warning' type=button value=Back onclick=All.back_to_form('.nextForm2','.nextForm1') />";
                        rowshtml += "&nbsp;";
                        rowshtml += "<input type=hidden id=trcd name=trcd value=" + trcd + " />";
                        rowshtml += "<input type=hidden id=qtysum name=qtysum value=" + qty + " />";
                        rowshtml += "</td></tr></table>";
                        rowshtml += "</form>";
                        $(All.get_active_tab() + ".nextForm2").append(rowshtml);
                        $(All.get_active_tab() + ".nospacing").css('margin-bottom', '1px');
                    }
                    else {
                        alert("Data not found..!!");
                    }
                },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + ':' + xhr.status);
                All.set_enable_button();
            }
        });
    },

    getListProduct: function (theLink) {
        var param = theLink.id;
        var prdcd = $(All.get_active_tab() + "#prdcd" + param).val();
        var prdnm = $(All.get_active_tab() + "#prdnm" + param).val();
        var trcd = $(All.get_active_tab() + "#trcd").val();
        var qty = parseInt($(All.get_active_tab() + "#qty" + param).val());

        $(All.get_active_tab() + ".nextForm1").hide();
        $(All.get_active_tab() + ".nextForm2").html(null);
        var rowshtml = "<form id=saveBarcode><table width='80%' class='table table-striped table-bordered'>";
        rowshtml += "<thead><tr>";
        rowshtml += "<td width=15%>Trx No</td><td>" + trcd + "</td></tr>";
        rowshtml += "<tr><td>Product Code</td><td>" + prdcd + "</td></tr>";
        rowshtml += "<tr><td>Product Name</td><td>" + prdnm + "</td></tr>";
        rowshtml += "<tr><td>Qty</td><td colspan=2>" + qty + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;<font color=red>Total Qty Scanned : <span id=showQtyScan>0</span></font>&nbsp;)</td></tr>";
        rowshtml += "<tr><td>Barcode Input Type</td>";
        rowshtml += "<td><select id=barcodeType class=nospacing>";
        rowshtml += "<option value=1>Single/Multiple Barcode</option>";
        //rowshtml += "<option value=2>Single Barcode</option>";
        rowshtml += "</select>&nbsp;<input type=button id=addBarType value=Add class='btn btn-primary' onclick=Stkbarcode.addBarcodeInput() />&nbsp;<input id=clearBtn class='btn btn-danger' type=button value=Clear onclick=Stkbarcode.clearbarcode() /></td></tr>";
        //rowshtml += "<table width=80%>";
        rowshtml += "</thead><tbody id=rowBarcode></tbody>";
        rowshtml += "</table>";
        rowshtml += "<table><tr><td>";
        rowshtml += "<input class='btn btn-warning' type=button value=Back onclick=All.back_to_form('.nextForm2','.nextForm1') />";
        rowshtml += "&nbsp;<input id=saveBtn class='btn btn-primary' type=button value=Save onclick=Stkbarcode.savebarcode() />";

        rowshtml += "<input type=hidden id=trcd name=trcd value=" + trcd + " />";
        rowshtml += "<input type=hidden id=prdcd name=prdcd value=" + prdcd + " />";
        rowshtml += "<input type=hidden id=qtysum name=qtysum value=" + qty + " />";
        rowshtml += "<input type=hidden id=multiSum name=multiSum value=0 />";
        //rowshtml += "<input type=hidden id=singleSum name=singleSum value=0 />";
        rowshtml += "<div id=realInput></div>";
        rowshtml += "</td></tr></table>";
        rowshtml += "</form>";
        $(All.get_active_tab() + ".nextForm2").append(rowshtml);
        $(All.get_active_tab() + ".nospacing").css('margin-bottom', '1px');
    },

    addBarcodeInput: function () {
        var rowshtml = "";
        var butSave = $("#saveBtn").val();
        var buttonSave = "&nbsp;<input id=saveBtn class='butonSave btn btn-primary' type=button value=Save onclick=Stkbarcode.savebarcode() />";
        // alert(butSave);

        var tipe = $("#barcodeType").text();
        var multiSum = parseInt($("#multiSum").val());
        var jum = parseInt($("#showQtyScan").text());
        var qty = parseInt($("#qtysum").val());
        if (jum < qty) {
            var mul = multiSum + 1;
            rowshtml += "<tr id=parame" + mul + ">";
            rowshtml += "<td>Barcode :&nbsp;</td>";
            rowshtml += "<td width='50%'><input type=text id=multi" + mul + " name=multi[] class='span12 multiinp nospacing' onkeypress='return Stkbarcode.countMultiScan(this.value, event, " + mul + ")' /></td>";
            rowshtml += "<td>Qty Scanned :<input readonly=readonly type=text id=countScanMultiple" + mul + " class='nospacing' />";
            rowshtml += "&nbsp;<input class='btn btn-small btn-danger' type='button' value='Delete' onclick='Stkbarcode.deleteBarcode(" + mul + ")'>";
            rowshtml += "</td></tr>";
            $("#rowBarcode").append(rowshtml);

            $(".nospacing").css('margin-bottom', '1px');
            $("#multiSum").val(mul);
            $("#multi" + multiSum).attr('readonly', 'readonly');
            $("#multi" + mul).focus();

            if (butSave != "Save") {
                $("#buttonSave").append(buttonSave);
            }
        }
        else {
            alert("Maximum barcode scan reached..!! Check Qty Product..!!");
            $("#multi" + multiSum).attr('readonly', 'readonly');
        }
    },

    deleteBarcode: function (param) {
        var x = $(All.get_active_tab() + "#multi" + param).val();
        var showQtyScan = parseInt($(All.get_active_tab() + "#showQtyScan").text());
        var res = x.split('|');
        var jumQty = res.length;
        var xx = 0;
        for (i = 0; i < res.length; i++) {
            $("input[class=forInput][type=hidden][value='" + res[i] + "']").remove();
            xx++;
        }
        var selisih = showQtyScan - xx;
        //$(All.get_active_tab() + "#qtysum").val(selisih);
        $(All.get_active_tab() + "#showQtyScan").html(selisih);
        $("tr#parame" + param).remove();
    },

    clearbarcode: function () {
        $(All.get_active_tab() + "#rowBarcode").html(null);
        $(All.get_active_tab() + "#multiSum").val(0);
        $(All.get_active_tab() + "#showQtyScan").text(0);
        $(All.get_active_tab() + "#realInput").html(null);
        //$(All.get_active_tab() + "#singleSum").val(0);
    },

    countMultiScan: function (h, evt, param) {
        //console.log("isi h :" +h);
        evt = (evt) ? evt : event;
        var charCode = (evt.charCode) ? evt.charCode : ((evt.which) ? evt.which : evt.keyCode);
        if (charCode == 13 || charCode == 3 || charCode == 9) {
            evt.preventDefault();
            if (h == "" || h == " ") {
                alert("Barcode harus diisi..!!");
            }
            else {

                var res = h.split('|');

                var jum = parseInt($("#showQtyScan").text());
                var afterjum = jum + res.length;
                var qty = parseInt($("#qtysum").val());
                var TerScan = [];
                var a = document.querySelectorAll('input[name="multi[]"]').length - 1;
                var b = document.querySelectorAll('input[name="multi[]"]').length;
                var c = $(".oldmulti").length;
                // oldmulti
                //    console.log(c);
                if (a > 0) {
                    // $("#realInput").html(null)multi;
                    for (x = 0; x < a;) {
                        var datScanBarcode = $("#multi" + x).val();
                        var dataA = datScanBarcode.split('|');
                        var lenA = dataA.length;
                        if (lenA > 1) {
                            for (y = 0; y < lenA; y++) {
                                var xy = dataA[y];
                                TerScan.push(xy);
                            }
                        }
                        TerScan.push(datScanBarcode);
                        x++;
                    }
                }
                if (c > 0) {
                    if (b > 0) {
                        $("#realInput").html(null);
                        for (x = c; x < b;) {
                            var datScanBarcode = $("#multi" + x).val();
                            rowshtml = "<input class=forInput type=hidden name=newmulti[] value='" + datScanBarcode + "' />";
                            $("#realInput").append(rowshtml);
                            x++;
                        }

                    }

                } else {
                    if (b > 0) {
                        $("#realInput").html(null);
                        for (x = 0; x < b;) {
                            var datScanBarcode = $("#multi" + x).val();
                            rowshtml = "<input class=forInput type=hidden name=newmulti[] value='" + datScanBarcode + "' />";
                            $("#realInput").append(rowshtml);
                            x++;
                        }

                    }
                }


                if (afterjum <= qty) {
                    var timeRepeated = 0;
                    for (i = 0; i < res.length; i++) {

                        let findDuplicates = arr => arr.filter((item, index) => arr.indexOf(item) != index)
                        TerScan.push(res[i]);
                        var duplicates = [...new Set(findDuplicates(TerScan))];
                        if (duplicates == "") {

                        } else {
                            alert("Ada isi barcode yg sama..!!");
                            return false;
                        }
                        rowshtml = "<input class=forInput type=hidden name=barcode[] value='" + res[i] + "' />";
                        $("#realInput").append(rowshtml);
                    }
                    if (timeRepeated <= 0) {
                        $("#countScanMultiple" + param).val(res.length);
                        Stkbarcode.setTotScannedQty(res.length);
                        Stkbarcode.addBarcodeInput();
                        return false;
                    }
                }
                else {
                    alert("Jumlah barcode lebih dari :" + qty);
                }
            }
        }
        return true;
    },

    setTotScannedQty: function (jumlah) {
        var jum = parseInt($(All.get_active_tab() + "#showQtyScan").text());
        var x = jum + jumlah;
        $(All.get_active_tab() + "#showQtyScan").text(x);
    },


    setNextTo: function (e) {
        if (e.keyCode == 13 || e.keyCode == 9) {
            var x = parseInt(document.activeElement.tabIndex);
            var next = x + 1;

            var nilai = $(All.get_active_tab() + "#barcode" + x).val();
            var timeRepeated = 0;
            $("input[type='text']").each(function () {
                //Inside each() check the 'valueOfChangedInput' with all other existing input
                if ($(this).val() == nilai) {
                    timeRepeated++; //this will be executed at least 1 time because of the input, which is changed just now
                }
            });

            if (timeRepeated > 1) {
                alert("Nilai barcode sudah ada, barcode tidak boleh sama..!!");
                $(All.get_active_tab() + "#barcode" + x).val(null);
                All.set_disable_button();
            }
            else {
                $(All.get_active_tab() + "#barcode" + next).focus();
                All.set_enable_button();
            }
        }
    },

    savebarcode_bak: function () {
        All.set_disable_button();
        var kosong = 0;
        var qtysum = parseInt($(All.get_active_tab() + "#qtysum").val());
        var trcd = $(All.get_active_tab() + "#trcd").val();
        if (qtysum >= 1) {
            $(All.get_active_tab() + ".forInput").each(function () {
                if (this.value == "") {
                    kosong++;
                }
            });
            var isix = parseInt(kosong);
            var xx = isix - 1;
            if (kosong === 0) {
                //All.set_enable_button();
                $.post(All.get_url('stk/barcode/save'), $(All.get_active_tab() + "#saveBarcode").serialize(), function (data) {
                    All.set_enable_button();
                    if (data.response == 'true') {
                        alert(data.message);
                        $(All.get_active_tab() + ".nextForm2").html(null);
                        //$(All.get_active_tab() + ".nextForm1").html(null);
                        $(All.get_active_tab() + ".nextForm1").show();
                        //Stkbarcode.getDetailProdByTTPVersi2(data.trcd);
                        All.ajaxShowDetailonNextForm('stk/barcode/trx/id/' + trcd);
                    } else {
                        alert(data.message);
                    }
                }, "json").fail(function () {
                    alert("Error requesting page");
                    All.set_enable_button();
                });
                //alert('trcd :' +trcd);

            }
            else {

                alert("Masih ada inputan barcode yang kosong..!!!, kosong : " + kosong);
                All.set_enable_button();

            }
        } else {
            alert("OK ");
            All.set_enable_button();

        }
    },

    savebarcode: function () {
        All.set_disable_button();
        var kosong = 0;
        var qtysum = parseInt($(All.get_active_tab() + ' #qtysum').val());
        var trcd = $(All.get_active_tab() + ' #trcd').val();
        if (qtysum >= 1) {
            $(".forInput").each(function () {
                if (this.value == "") {
                    kosong++;
                }
            });
            var isix = parseInt(kosong);
            var xx = isix - 1;
            if (kosong === 0) {
                $.post(All.get_url('stock_barcode/saveBarcode'), $("#saveBarcode").serialize(), function (data) {
                    All.set_enable_button();
                    if (data.response == 'true') {
                        alert(data.message);

                        $(All.get_active_tab() + ' .nextForm2').html(null);
                        $(All.get_active_tab() + ' .nextForm1').html(null);
                        $(All.get_active_tab() + ' .nextForm1').show();
                        Stkbarcode.detail(data.trcd);
                    } else {
                        alert(data.message);
                    }
                }, "json").fail(function () {
                    alert("Error requesting page");
                    All.set_enable_button();
                });

            }
            else {

                alert("Masih ada inputan barcode yang kosong..!!!, kosong : " + kosong);
                All.set_enable_button();

            }
        } else {
            alert("OK ");
            All.set_enable_button();

        }
    },



    scanToBarcodePrdStk: function (selisih, prdcd, stk, qtyOrd, dono, prdnm) {
        //stk/barcode/save
        /*console.log(prdcd);
        console.log(selisih);
        console.log(stk);
        console.log(qtyOrd);*/

        // alert("Isi : "+prdcd+ " prdnm :" +prdnm+ "trdcdGroup :" +trcdGroup);
        $(All.get_active_tab() + ".nextForm1").hide();
        $(All.get_active_tab() + ".nextForm2").html(null);
        var rowshtml = "<form id=saveBarcodeWhtoStk><table width='80%' class='table table-striped table-bordered'>";
        rowshtml += "<thead><tr>";
        rowshtml += "<td width=15%>DO No</td><td>" + dono + "</td></tr>";
        rowshtml += "<tr><td>Product Code</td><td>" + prdcd + "</td></tr>";
        rowshtml += "<tr><td>Product Name</td><td>" + prdnm + "</td></tr>";
        rowshtml += "<tr><td>Qty</td><td colspan=2>" + selisih + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;<font color=red>Total Qty Scanned : <span id=showQtyScan>0</span></font>&nbsp;)</td></tr>";
        rowshtml += "<tr><td>Barcode Input Type</td>";
        rowshtml += "<td><select id=barcodeType class=nospacing>";
        rowshtml += "<option value=1>Single/Multiple Barcode</option>";
        //rowshtml += "<option value=2>Single Barcode</option>";
        rowshtml += "</select>&nbsp;<input type=button id=addBarType value=Add class='btn btn-small btn-primary' onclick=Stkbarcode.addBarcodeInput() />&nbsp;<input id=clearBtn class='btn btn-small btn-danger' type=button value=Clear onclick=Stkbarcode.clearbarcode() /></td></tr>";
        //rowshtml += "<table width=80%>";
        rowshtml += "</thead><tbody id=rowBarcode></tbody>";
        rowshtml += "</table>";
        rowshtml += "<table><tr><td>";
        rowshtml += "<input class='btn btn-warning' type=button value=Back onclick=All.back_to_form('.nextForm2','.nextForm1') />";
        rowshtml += "&nbsp;<input id=saveBtn class='btn btn-primary' type=button value=Save onclick=Stkbarcode.saveBarcodeWHtoStk() />";

        rowshtml += "<input type=hidden id=trcd name=trcd value='" + dono + "' />";
        rowshtml += "<input type=hidden id=sendTo name=sendTo value='" + stk + "' />";
        //rowshtml += "<input type=hidden id=info name=info value='"+info+"' />";
        rowshtml += "<input type=hidden id=prdcd name=prdcd value='" + prdcd + "' />";
        rowshtml += "<input type=hidden id=qtysum name=qtysum value=" + selisih + " />";
        rowshtml += "<input type=hidden id=qtyord name=qtyord value=" + qtyOrd + " />";
        rowshtml += "<input type=hidden id=multiSum name=multiSum value=0 />";
        //rowshtml += "<input type=hidden id=singleSum name=singleSum value=0 />";
        rowshtml += "<div id=realInput></div>";
        rowshtml += "</td></tr></table>";
        rowshtml += "</form>";
        $(All.get_active_tab() + ".nextForm2").append(rowshtml);
        $(All.get_active_tab() + ".nospacing").css('margin-bottom', '1px');
    },

    saveBarcodeWHtoStk: function () {
        All.set_disable_button();
        var kosong = 0;
        var qtysum = parseInt($(All.get_active_tab() + "#qtyord").val());
        var trcd = $(All.get_active_tab() + "#trcd").val();
        if (qtysum >= 1) {
            $(All.get_active_tab() + ".forInput").each(function () {
                if (this.value == "") {
                    kosong++;
                }
            });
            var isix = parseInt(kosong);
            var xx = isix - 1;
            if (kosong === 0) {
                All.set_enable_button();
                $.post(All.get_url('stk/barcode/save'), $(All.get_active_tab() + "#saveBarcodeWhtoStk").serialize(), function (data) {
                    All.set_enable_button();
                    if (data.response == 'true') {
                        alert(data.message);
                        $(All.get_active_tab() + ".nextForm2").html(null);
                        //$(All.get_active_tab() + ".nextForm1").html(null);
                        $(All.get_active_tab() + ".nextForm1").show();
                        //Stkbarcode.getDetailProdByTTPVersi2(data.trcd);
                        All.ajaxShowDetailonNextForm('stk/barcode/trx/id/' + trcd);
                    } else {
                        alert(data.message);
                    }
                }, "json").fail(function () {
                    alert("Error requesting page");
                    All.set_enable_button();
                });
            }
            else {

                alert("Masih ada inputan barcode yang kosong..!!!, kosong : " + kosong);
                All.set_enable_button();

            }
        } else {
            alert("OK ");
            All.set_enable_button();

        }
    },

    keycari: function (evt) {
        evt = (evt) ? evt : event;
        var charCode = (evt.charCode) ? evt.charCode : ((evt.which) ? evt.which : evt.keyCode);
        if (charCode == 13 || charCode == 3 || charCode == 9) {
            var code = $(All.get_active_tab() + ' #src_barcode').val();
            // $("#form2").val(code);
            Stkbarcode.TraceBarcode(code);
            // $("#formSearchTrx").get(0);
            //  return false;
        }

    },



    TraceBarcode: function (id) {
        // alert('ajax');
        All.set_disable_button();
        All.get_image_load(".result");
        $(All.get_active_tab() + ' .nextForm1').html(null);
        $.ajax({
            dataType: 'json',
            url: All.get_url("stock_barcode/postTraceBarcode/" + id),
            type: 'GET',
            success:
                function (data) {
                    All.set_enable_button();
                    $(All.get_active_tab() + '.result').html(null);

                    if (data.response == 'true') {
                        $(All.get_active_tab() + '.nextForm1').html(null);
                        var arraydata = data.arraydata;
                        var rowshtml = "<form><table width='100%' class='table table-striped table-bordered bootstrap-datatable datatable'>";
                        rowshtml += "<thead>";
                        rowshtml += "<tr bgcolor=#f4f4f4><th colspan=6>Tracking Barcode Produk</th></tr>";
                        rowshtml += "<tr bgcolor=#f4f4f4>";
                        rowshtml += "<th width=5%>No</th>";
                        rowshtml += "<th width=15%>Tanggal</th>";
                        rowshtml += "<th width=15%>No DO/TTP</th>";
                        rowshtml += "<th width=25%>Asal</th>";
                        rowshtml += "<th>Tujuan</th>";
                        rowshtml += "</tr></thead><tbody>";
                        var detail = data.detail;
                        var header = data.header;
                        var jml_header = data.jml_header;

                        if (header != '0') {

                            $.each(header, function (key, value) {
                                // no = no+key;
                                // $.each(arraydata,function(key, value){
                                rowshtml += "<tr id=" + (key + 1) + ">";
                                rowshtml += "<td><div align=right>" + (key + 1) + "</div></td>";
                                rowshtml += "<td><div align=center><input type=hidden id=trcd" + (key + 1) + " value=" + value.CREATED_DATE + " />" + value.CREATED_DATE + "</div></td>";
                                rowshtml += "<td><div align=center><input type=hidden id=orderno" + (key + 1) + " value=" + value.NO_DO + " />" + value.NO_DO + "</div></td>";
                                rowshtml += "<td><div align=center><input type=hidden id=dfno" + (key + 1) + " value=" + value.ID_WAREHOUSE + " />" + value.WAREHOUSE_NAME + "</div></td>";
                                rowshtml += "<td><div align=center><input type=hidden id=fullnm" + (key + 1) + " value=" + value.ID_STOCKIES + " />" + value.STOCKIES_NAME + "</div></td>";
                                rowshtml += "</tr>";
                            });
                        }
                        if (detail != '0') {
                            $.each(detail, function (key, value) {
                                // $.each(arraydata,function(key, value){
                                rowshtml += "<tr id=" + (jml_header + key + 1) + ">";
                                rowshtml += "<td><div align=right>" + (jml_header + key + 1) + "</div></td>";
                                rowshtml += "<td><div align=center><input type=hidden id=trcd" + (jml_header + key + 1) + " value=" + value.tanggal + " />" + value.tanggal + "</div></td>";
                                rowshtml += "<td><div align=center><input type=hidden id=orderno" + (jml_header + key + 1) + " value=" + value.orderno + " />" + value.orderno + "</div></td>";
                                rowshtml += "<td><div align=center><input type=hidden id=dfno" + (jml_header + key + 1) + " value=" + value.loccd + " />" + value.asal + "</div></td>";
                                rowshtml += "<td><div align=center><input type=hidden id=fullnm" + (jml_header + key + 1) + " value=" + value.dfno + " />" + value.tujuan + "</div></td>";
                                rowshtml += "</tr>";
                            });
                        }

                        rowshtml += "</tbody></table></form>";
                        All.set_enable_button();
                        $(All.get_active_tab() + '.nextForm1').append(rowshtml);
                        // $(".result").html(null);
                        All.set_datatable();
                    } else {
                        $(All.get_active_tab() + ' .result').html(null);
                        var param = "Data Tidak Ditemukan..";
                        var err = "<div class='alert alert-error' align=center>" + param + "</div>";
                        $(All.get_active_tab() + ' .result').append(err);
                        // All.set_error_message(".result");
                    }

                },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + ':' + xhr.status);
                All.set_enable_button();
            }
        });
    },

    TraceBarcodeBak: function () {
        All.set_disable_button();
        All.get_image_load(".result");
        $.post(All.get_url('stock_barcode/postTraceBarcode'), $("#formSearchTrx").serialize(), function (data) {
            All.set_enable_button();
            if (data.response == 'true') {
                $(".nextForm1").html(null);
                var arraydata = data.arraydata;
                var rowshtml = "<form><table width='100%' class='table table-striped table-bordered bootstrap-datatable datatable'>";
                rowshtml += "<thead>";
                rowshtml += "<tr bgcolor=#f4f4f4><th colspan=6>Tracking Barcode Produk</th></tr>";
                rowshtml += "<tr bgcolor=#f4f4f4>";
                rowshtml += "<th width=5%>No</th>";
                rowshtml += "<th width=15%>Tanggal</th>";
                rowshtml += "<th width=15%>No DO/TTP</th>";
                rowshtml += "<th width=25%>Asal</th>";
                rowshtml += "<th>Tujuan</th>";
                rowshtml += "</tr></thead><tbody>";
                var detail = data.detail;
                var header = data.header;
                var jml_header = data.jml_header;

                if (header != '0') {

                    $.each(header, function (key, value) {
                        // no = no+key;
                        // $.each(arraydata,function(key, value){
                        rowshtml += "<tr id=" + (key + 1) + ">";
                        rowshtml += "<td><div align=right>" + (key + 1) + "</div></td>";
                        rowshtml += "<td><div align=center><input type=hidden id=trcd" + (key + 1) + " value=" + value.CREATED_DATE + " />" + value.CREATED_DATE + "</div></td>";
                        rowshtml += "<td><div align=center><input type=hidden id=orderno" + (key + 1) + " value=" + value.NO_DO + " />" + value.NO_DO + "</div></td>";
                        rowshtml += "<td><div align=center><input type=hidden id=dfno" + (key + 1) + " value=" + value.ID_WAREHOUSE + " />" + value.WAREHOUSE_NAME + "</div></td>";
                        rowshtml += "<td><div align=center><input type=hidden id=fullnm" + (key + 1) + " value=" + value.ID_STOCKIES + " />" + value.STOCKIES_NAME + "</div></td>";
                        rowshtml += "</tr>";
                    });
                }
                if (detail != '0') {
                    $.each(detail, function (key, value) {
                        // $.each(arraydata,function(key, value){
                        rowshtml += "<tr id=" + (jml_header + key + 1) + ">";
                        rowshtml += "<td><div align=right>" + (jml_header + key + 1) + "</div></td>";
                        rowshtml += "<td><div align=center><input type=hidden id=trcd" + (jml_header + key + 1) + " value=" + value.tanggal + " />" + value.tanggal + "</div></td>";
                        rowshtml += "<td><div align=center><input type=hidden id=orderno" + (jml_header + key + 1) + " value=" + value.orderno + " />" + value.orderno + "</div></td>";
                        rowshtml += "<td><div align=center><input type=hidden id=dfno" + (jml_header + key + 1) + " value=" + value.loccd + " />" + value.asal + "</div></td>";
                        rowshtml += "<td><div align=center><input type=hidden id=fullnm" + (jml_header + key + 1) + " value=" + value.dfno + " />" + value.tujuan + "</div></td>";
                        rowshtml += "</tr>";
                    });
                }

                rowshtml += "</tbody></table></form>";
                $(".nextForm1").append(rowshtml);
                // $(".result").html(null);
                All.set_datatable();
            } else {
                All.set_error_message(".result");
            }
        }, "json").fail(function () {
            alert("Error requesting page");
            $(".result").html(null);
            All.set_enable_button();
        });
    },

    generatePackingList: function () {
        All.set_disable_button();
        //All.get_image_load("result");
        $.post(All.get_url('stk/barcode/generate/pl'), $("#frmPrepPL").serialize(), function (data) {
            All.set_enable_button();
            if (data.response == 'true') {
                //Stkbarcode.listProdSummary(data.shipinfo.trcdGroup, '.nextForm2');
            } else {
                //All.set_error_message("result");
            }
        }, "json").fail(function () {
            alert("Error requesting page");
            $(".result").html(null);
            All.set_enable_button();
        });
    },
}
