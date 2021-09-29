<?php 
    if($username == "BID06") {
        $ket1 = "Mobile Stk / Stockist"; 
        $ket2 = "Member / MS / Stk";    
        $ket3 = "No TTP / DO";
    } else {
        $ket1 = "Mobile Stockist";     
        $ket2 = "ID Member / Mobile Stk";
        $ket3 = "No TTP";
    }
?>
<div class="mainForm">
    <form class="form-horizontal" method="post" id="fromScanSearchV2" name="fromScanSearchV2">
        <fieldset>
            <div class="control-group">
                <label class="control-label" for="typeahead">Jenis Transaksi</label>
                <div class="controls">
                    <!--<span class="span2" style="line-height:30px;">Tipe Pencarian</span>-->
                    <select id="tipe" name="tipe" class="control">
                        <option value="1">Member</option>
                        <option value="2"><?php echo $ket1; ?></option>
                    </select>

                </div>
                <div>
                    <label class="control-label" for="typeahead"><?php echo $ket2; ?></label>
                    <div class="controls">
                        <input type="text" style="width: 180px;" id="id_member_ms" name="id_member_ms" onchange="cekFullNm(this.value)" />
                        <input type="text" readonly="readonly" style="width: 380px;" id="nama" name="nama" />
                        <input type="hidden" id="kode_stk" name="kode_stk" value="<?php echo $username; ?>" />
                    </div>
                </div>
                <div>
                    <label class="control-label" for="typeahead"><?php echo $ket3; ?></label>
                    <div class="controls">
                        <input type="text" style="width: 180px;" id="no_ttp" name="no_ttp" /> &nbsp;* Bila Transaksi belum diinput, kosongkan saja
                    </div>
                </div>
                <div>
                    <label class="control-label" for="typeahead">Barcode</label>
                    <div class="controls">
                        <input type="text" style="width: 450px;" id="barcode" name="barcode" />
                        * Tekan Enter bila input tidak menggunakan barcode
                    </div>
                </div>
                <div>
                    <label class="control-label" for="typeahead">&nbsp;</label>
                    <div class="controls">
                        <input class="btn btn-mini btn-primary" type="button" value="Simpan Barcode" onclick="simpanBarcode()" />
                        <input type="hidden" id="jumrow" value="0" />
                    </div>
                </div>
                
            </div>
        </fieldset>
        <div class="result">
            <table id="tblbarcode" align="center" width="100%" class="table table-striped table-bordered">
                <thead>
                    <tr>
                    <th>Barcode</th>
                    <th width="10%">Qty</th>
                    <th width="10%">Hapus</th>
                    </tr>
                </thead>
                <tbody id="bodyBarcode">

                </tbody>    
            </table>
        </div>
    </form>
      


</div>
<div clas="load"></div>
<?php //setDatePicker(); ?>
<script>
let arrBarcode = [];
let arrBarcodeDetail = [];
let jumRow = 0;

$(All.get_active_tab() + " #barcode").on('keydown', function(e) {
    if (e.which == 13) {

        console.log(arrBarcode);
        console.log(arrBarcodeDetail);

        e.preventDefault();
        let nilai = this.value;

        let resArr = nilai.split("|");
        let jum = resArr.length;

        const check = setBarcodeDetail(resArr);
        if(check) {
            jumRow++;
            let htmlx = "";
            htmlx += "<tr id='"+jumRow+"'>";
            htmlx += "<td><input readonly='readonly' id='brcode"+jumRow+"' type='text' value='"+nilai+"' class='span20' /></td><td>"+jum+"</td>";
            htmlx += "<td align=center><button onclick='hapusBarcode("+jumRow+")' type='button' class='btn btn-mini btn-danger'><i class='icon-trash icon-white'></i></button></td>";
            htmlx += "<tr>";

            $(All.get_active_tab() + " #bodyBarcode").append(htmlx);

            arrBarcode.push(nilai);
            console.log(arrBarcode);
            console.log(arrBarcodeDetail);
            console.log("jum row : " +jumRow);
            

            $(All.get_active_tab() + " #barcode").val("");
        } else {
            alert("Sudah ada barcode yang sama")
        }

    }
});

function hapusBarcode(param) {
    let idHapus = $(All.get_active_tab() + " #brcode" +param).val();

    $(All.get_active_tab() + " #bodyBarcode tr#" +param).remove();
    arrBarcode = arrBarcode.filter(e => e !== idHapus)

    let valBarDetail = idHapus.split("|");

    arrBarcodeDetail = arrBarcodeDetail.filter(
    function(e) {
      return this.indexOf(e) < 0;
    },valBarDetail);

    console.log("arrbarcode : " +arrBarcode);
    console.log("arrBarcodeDetail : " +arrBarcodeDetail);
    console.log("jum row : " +jumRow);
    console.log("var bar : " +valBarDetail);
}

function setBarcodeDetail(array) {
    /* let countX = array.length;
    let hasil;
    for(let i = 0; i < countX; i++) {
        
        hasil = arrBarcodeDetail.indexOf(array[i]);
        if(hasil !== -1) {
            console.log('masuk sini' +hasil);
            return false;
        }
    } */
    arrBarcodeDetail = [
        ...arrBarcodeDetail,
        ...array
    ]
    return true;
}

function simpanBarcode() {
    let tipe = $(All.get_active_tab() + " #tipe").val();
    let id_member_ms = $(All.get_active_tab() + " #id_member_ms").val();
    let nama = $(All.get_active_tab() + " #nama").val();
    let no_ttp = $(All.get_active_tab() + " #no_ttp").val();
    let kode_stk = $(All.get_active_tab() + " #kode_stk").val();
    

    let kosong = 0;
    if(id_member_ms === "" || id_member_ms === " ") {
        kosong++;
        alert("ID Member / Kode MS silahkan diisi..");
        return;
    }

    if(arrBarcode.length === 0) {
        kosong++;
        alert("Barcode kosong..");
        return;
    }

    All.set_disable_button();
    All.get_wait_message();
    $.ajax({
        url: All.get_url('stk/barcode/simpan'),
        type: 'POST',
        dataType: 'json',
        data: {tipe: tipe, id_member_ms: id_member_ms, kode_stk: kode_stk, nama: nama, no_ttp: no_ttp, barcode: arrBarcode},
        success:
        function(data){
            All.set_enable_button();
            if(data.response == "true") {
                alert("Simpan barcode berhasil..");
                $(All.get_active_tab() + " #no_ttp").val(null);
                $(All.get_active_tab() + " #bodyBarcode").html(null);
                arrBarcode = [];
                arrBarcodeDetail = [];

                console.log(arrBarcode);
                console.log(arrBarcodeDetail);

            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + ':' +xhr.status);
                All.set_enable_button();
        }
    });
}

function cekFullNm(nilai) {
    let tipe = $(All.get_active_tab() + " #tipe").val();

    All.set_disable_button();
    All.get_wait_message();
    $.ajax({
        url: All.get_url('stk/barcode/check/') +tipe+ "/" +nilai,
        type: 'GET',
        dataType: 'json',
        success:
        function(data){
            All.set_enable_button();
            if(data.response == "true") {
                
                $(All.get_active_tab() + " #nama").val(data.arrayData[0].fullnm);
                $(All.get_active_tab() + " #no_ttp").focus();
                $(All.get_active_tab() + " #id_member_ms").val(nilai.toUpperCase());
            } else {
                alert(data.message);
                $(All.get_active_tab() + " #nama").val(null);
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + ':' +xhr.status);
                All.set_enable_button();
        }
    });
}
</script>