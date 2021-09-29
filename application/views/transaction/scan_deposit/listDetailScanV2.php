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
                    <table width="100%">
                        <tr>
                            <td width="15%">Kode Stockist&nbsp;</td>
                            <td>
                                <input type="text" class="span12 typeahead" id="substockistcode"
                                    <?php echo isset($edit) ? $edit :'' ?> name="substockistcode"
                                    value="<?php echo $user; ?>" onchange="Sales.get_sc_info()" />
                            </td>
                            <?php echo isset($kategori) ? $kategori : ''; ?>
                            
                        </tr>
                       
                        <tr hidden>
                            <td>C/O Sub Stockist Code&nbsp;</td>
                            <td>
                                <input type="text" readonly="yes" class="span12 typeahead"
                                    id="uplinesub" name="uplinesub" value="<?php echo $user; ?>" />
                            </td>
                            <td>C/O Sub Stockist Name&nbsp;</td>
                            <td>
                                <input type="text" readonly="yes" class="span12 typeahead"
                                    id="uplinesubnm" name="uplinesubnm"
                                    value="<?php echo $dtsubname; ?>" />
                            </td>
                        </tr>
                        
                        <?php echo isset($member) ? $member : ''; ?>
                        <tr>
                            <td>Nama Stockist&nbsp;</td>
                            <td><input type="text" readonly="yes" class="span12 typeahead"
                                    id="substkname" name="substkname"
                                    value="<?php echo $dtsubname; ?>" /></td>
                                    <td>&nbsp;&nbsp;No Voucher Deposit&nbsp;</td>
                            <td>
                                <input type="text" readonly="yes" class="span12 typeahead"
                                    id="trxno" name="trxno"
                                    value="<?php echo isset($no_trx) ? $no_trx :'Auto' ?>" />
                            </td>
                            
                            
                        </tr>
                
                        
                        <tr>
                            <td>ID Member&nbsp;</td>
                            <td><input tabindex="1" type="text" class="span12 typeahead" id="idmemb"
                                    name="idmemb" style="text-transform:uppercase" /></td>
                           
                            <td>&nbsp;&nbsp;Tgl Input&nbsp;</td>
                            <td>
                                    <input  type="text" readonly="yes" class="span12 typeahead"
                                        id="trxdate" name="trxdate"
                                        value="<?php echo isset($createdt) ? $createdt :$dateNow ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td>Scan Vch Cash&nbsp;</td> 
                            <td>
                                    <input  tabindex="2" type="text" style="width: 200px; line-height: 18px;" id="scan" name="scan"
                                        <?php echo isset($status) ? $status :'' ?>
                                        onchange="loaddatavalid()">
                                    <input  tabindex="3" type="btn" class="btn btn-mini btn-success" value="Check Voucher" onclick="loaddatavalid()" />
                            </td>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                    </table>
                </td>
                        
                    <hr>
                    <br />
                    <!--<span id="show_form"></span> -->
                    <table class="table table-striped table-bordered" id="tblListVch">
                        <thead>
                            <tr bgcolor="#f4f4f4">
                                <th>No</th>
                                <th width="10%">Scan</th>
                                <th width="10%">Kategori</th>
                                <th width="25%">Kode Member</th>
                                <th width="25%">Nama Member</th>
                                <th width="25%">Nilai Voucher</th>
                                <th width="25%">Act</th>
                            </tr>
                        </thead>
                        <tbody id="dataPrd">
                            <?php
                            $no=0;
                            if(isset($LIST_DETAIL)):
                                foreach($LIST_DETAIL as $row):
                                    $no++;?>
                                    <tr id="<?php echo $no; ?>">
                                        <td align="right"><?php echo $no;?></td>
                                        <td align="center"><?php echo $row->voucher_scan;?></td>
                                        <td align="center"><?php echo $row->kategori;?></td>
                                        <td><?php echo $row->dfno;?></td>
                                        <td><?php echo $row->fullnm;?></td>
                                        <td><input readonly="yes" type="text" style="text-align:right;"
                                                class="span12 typeahead"
                                                value="<?php echo number_format($row->nominal,2,".","");?>"></td>
                                        <td align=center>
                                        <?php
                                            //echo $user;
                                            if($usergroup == "ADMIN") {
                                                echo "<a class='btn btn-mini btn-danger' onclick=\"javascript:hapusVchCash('$row->voucher_scan','$row->dfno','$row->id_header','$no')\"><i class='icon-white icon-trash'></i></a>";
                                            }
                                        ?>
                                        </td>
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
                                        value="<?php echo isset($sisa) ? number_format($sisa,2,".","") :'0.00' ?>" />
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
                                <input type="button" class="btn btn-warning span20" name="back" value="Kembali"
                                    onclick="All.back_to_form(' .nextForm1', ' .mainForm')" />
                            </td>
                        <?php } else { ?>
                            <td>
                                <input type="button" class="btn btn-warning span20" name="back" value="Batal"
                                    onclick="All.back_to_form(' .result', ' .mainForm')" />
                            </td>
                        <?php } ?>
                            <?php
                        if(isset($nosave)){
                            echo'';
                        } else {
                        ?>
                            <td align="right">
                                <input type="button" class="btn btn-primary span20" name="submit" value="Simpan"
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

        function hapusVchCash(vchCash, idMember, idDeposit, no) {
            var x = confirm("Anda yakin akan menghapus voucher "+vchCash+" ini ?");
            if (x) {
                $.ajax({
                    type: "POST",
                    url: All.get_url('scan/vch/delete'),
                    dataType: 'json',
                    data: {
                        voucher_key: vchCash,
                        id_deposit: idDeposit,
                        id_member: idMember
                    },
                    success: function (data) {
                        alert(data.message);
                        if(data.response == "true") {
                            $(All.get_active_tab() + "#tblListVch tbody#dataPrd tr#" + no).remove();  
                            var deposit_baru = parseInt(data.arrayData.jumlah_deposit);
                            $(All.get_active_tab() + " #total_all").val(deposit_baru.toFixed(2))
                            $(All.get_active_tab() + " #total_all_show").val(deposit_baru.toFixed(2))  
                        }
                    }
                });  
            }  
        }

        function loaddatavalid() {
            var idmemb = $(All.get_active_tab() + ' #idmemb').val();
            var str = $(All.get_active_tab() + ' #scan').val();
            var kaet = $(All.get_active_tab() + ' #kategori2').val();
            var i = 0;
            var counter = 0;
            var total_all = parseInt($(All.get_active_tab() + " #total_all").val());

            //$(All.get_active_tab() + ' #dataPrd').val();
            for (i = 0; i < document.getElementById('dataPrd').getElementsByTagName("tr").length + 1; i++) {
                var nama = $(All.get_active_tab() + " tr#" + i).find('input').val();
                console.log("nama=" + nama);
                console.log("i=" + i);
                if (str == nama) {
                    counter++;
                }
            }
            console.log("counter = " + counter);
            if (idmemb != '') {
                if (counter > 0) {
                    //$(All.get_active_tab() + ' #scan').val('');
                    alert('Voucher yang anda masukkan sama (duplikat)');
                } else {
                    $.ajax({
                        type: "POST",
                        url: All.get_url('transaction/scan_voucher/getVch'),
                        dataType: 'json',
                        data: {
                            scan: str,
                            kat: kaet,
                            idmemb: idmemb
                        },
                        success: function (data) {
                            if (data.response == 'true') {
                                var arraydata = data.arrayData[0];
                                var dfno = arraydata.DistributorCode;
                                var fullnm = arraydata.fullnm;
                               /*  if (data.arraydata.dfno == null) {
                                    var dfno = '-';
                                    var fullnm = '-';
                                } */
                                var rowCount = document.getElementById('dataPrd').getElementsByTagName("tr").length;
                                var no = rowCount + 1;
                                nox = no++;

                                nilai_vch = parseFloat(arraydata.VoucherAmt);
                                $("<tr data-status=\"I\" id='" + no + "'>" +
                                    "<td align=\"right\"><input type=\"hidden\" name=\"idpendaftar[]\" id=\"idpendaftar\" value=\"" +
                                    arraydata.VoucherNo + "\" />" + no + "</td>" +
                                    "<td align=\"center\"><input type=\"hidden\" name=\"row[]\" id=\"row\" value=\"" +
                                    no + "\" />" + arraydata.VoucherNo + "</td>" +
                                    "<td align=\"center\"><input type=\"hidden\" name=\"jv[]\" id=\"jv\" value=\"Voucher Cash\" />Voucher Cash</td>" +
                                    "<td><input type=\"hidden\" name=\"memvc[]\" id=\"memvc\" value=\"" +
                                    dfno + "\" />" + dfno + "</td>" +
                                    "<td>" + fullnm + "</td>" +
                                    "<td><input type='hidden' id='amt" + no + "' name='amt[]' value=" +
                                    nilai_vch.toFixed(2) +
                                    "><input readonly=\"yes\" type=\"text\" style=\"text-align:right;\" class=\"span12 typeahead\" id=\"tampilan\"  name=\"tampilan\" value=" +
                                    nilai_vch.toFixed(2) + " /></td>" +
                                    "<td><input type=\"button\" name=\"ibtnDel\" id=\"ibtnDel\" value=\"Delete\" onclick='deletes(" +
                                    no + ")'  /></td>" +
                                    " </tr>").appendTo("#dataPrd");
                                console.log(rowCount);
                                total_all = total_all + nilai_vch;
                                //var gd = addCommas(total_all);
                                $(All.get_active_tab() + " #total_all").val(total_all.toFixed(2))
                                $(All.get_active_tab() + " #total_all_show").val(total_all.toFixed(2))

                                $(All.get_active_tab() + ' #scan').val('');
                                $(All.get_active_tab() + ' #idmemb').val(''); 
                                $(All.get_active_tab() + " #idmemb").focus();
                            } else {
                                alert(data.message);
                                //console.log(data.arraydata);
                            }
                           /*  $(All.get_active_tab() + ' #scan').val('');
                            $(All.get_active_tab() + ' #idmemb').val(''); */
                            $(All.get_active_tab() + " #idmemb").focus();
                        }
                    });
                }
            } else {
                $(All.get_active_tab() + ' #scan').val('');
                alert('Data member harus diisi');
            }
        }

        function deletes(frm) {
            var total_all = parseInt($(All.get_active_tab() + " #total_all").val());
            var dp = $(All.get_active_tab() + " #amt" + frm).val();
            total_all = total_all - parseInt(dp);
            var gd = addCommas(total_all);
            $(All.get_active_tab() + " #total_all").val(total_all);
            $(All.get_active_tab() + " #total_all_show").val(gd);
            $(All.get_active_tab() + " tr#" + frm).remove();
        }

        function locker() {
            $(All.get_active_tab() + " #kategori").val($(All.get_active_tab() + " #kategori2").val());
            document.getElementById("kategori2").disabled = true;
        }
    </script>

    <script>
        function simpandata(ix) {
            $(All.get_active_tab() + " #save").attr("disabled", "disabled");
            var masalah = 0;
            if (ix == 0) {
                $.post(('<?php echo base_url();?>transaction/scan_voucher/simpanScan'), $(All.get_active_tab() + " #frm_ttp").serialize(), function (data) {
                    if (data == true) {
                        alert("Berhasil disimpan!");
                    }
                }, "json").fail(function () {
                    alert("Error requesting page");
                });
            } else {
                if ($(All.get_active_tab() + ' #kategori').val() == '') {
                    masalah++;
                    alert("Mohon isi jenis voucher");
                    $(All.get_active_tab() + " #kategori").focus();
                }
                if ($(All.get_active_tab() + ' #substockistcode').val() == '') {
                    masalah++;
                    alert("Mohon isi data Stockies");
                    $(All.get_active_tab() + " #substockistcode").focus();
                }
                if ($(All.get_active_tab() + ' #distributorcode').val() == '') {
                    masalah++;
                    alert("Mohon isi data member");
                    $(All.get_active_tab() + " #distributorcode").focus();
                }
                if ($(All.get_active_tab() + ' #total_all').val() == '0') {
                    masalah++;
                    alert("Mohon isi Voucher");
                    $(All.get_active_tab() + " #scan").focus();
                }
                if (masalah == 0) {
                    All.set_disable_button();
                    $.post(('<?php echo base_url();?>transaction/scan_voucher/simpanScan2/<?php echo $id ?>'), $(All.get_active_tab() + " #frm_ttp")
                            .serialize(),
                            function (data) {
                                /* 
                                if (data != false) {
                                    alert("Berhasil disimpan dengan kode transaksi " + data);
                                    // window.location.replace('<?php echo site_url();?>c_sales_pvr/getDepositList');
                                    All.ajaxFormPost('formInputList','scan/list');
                                } else {
                                    alert("Voucher sudah di klaim");
                                    // window.location.replace('<?php echo site_url();?>c_sales_pvr/getDepositList');
                                } 
                                */
                                All.set_enable_button();
                                alert(data.message);
                                /* if(data.response == "false") {
                                    alert(data.message);
                                } */

                            }, "json")
                        .fail(function () {
                            alert("Error requesting page");
                            All.set_enable_button();
                        });
                }
            }
        }
    </script>