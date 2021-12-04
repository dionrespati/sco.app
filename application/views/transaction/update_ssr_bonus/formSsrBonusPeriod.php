<div class="mainForm">
    <form class="form-horizontal" enctype="multipart/form-data" id="formInputList">
        <fieldset>
            <div class="control-group">
                <label class="control-label" for="typeahead">SSR/MSR</label>
                <div class="controls">
                    <input type="text" class="fullnm_width" name="ssr" id="ssr" onchange="checkSsr(this.value)" />
                    <input
                        tabindex="5"
                        type="button"
                        class="btn btn-primary"
                        name="check" value="Check"
                        onclick="checkSsr($('#ssr').val())"/>
                </div>
                <div class="clearfix"></div>
                <label class="control-label" for="typeahead">Kode Stockist</label>
                <div class="controls">
                    <input readonly type="text" class="fullnm_width" name="kode_stockist" id="kode_stockist">
                </div>
                <div class="clearfix"></div>
                <label class="control-label" for="typeahead">No. Voucher Deposit</label>
                <div class="controls">
                    <input readonly type="text" class="fullnm_width" name="v_deposit" id="v_deposit">
                </div>
                <div class="clearfix"></div>
                <label class="control-label" for="typeahead">Tgl. SSR</label>
                <div class="controls">
                    <input readonly type="text" class="fullnm_width" name="tgl_ssr" id="tgl_ssr">
                </div>
                <div class="clearfix"></div>
                <label class="control-label" for="typeahead">Total DP</label>
                <div class="controls">
                    <input readonly type="text" class="fullnm_width" name="total_dp" id="total_dp">
                </div>
                <div class="clearfix"></div>
                <label class="control-label" for="typeahead">Total BV</label>
                <div class="controls">
                    <input readonly type="text" class="fullnm_width" name="total_bv" id="total_bv">
                </div>
                <div class="clearfix"></div>
                <label class="control-label" for="typeahead">Incoming Payment</label>
                <div class="controls">
                    <input readonly type="text" class="fullnm_width" name="ipno" id="ipno">
                </div>
                <div class="clearfix"></div>
                <label class="control-label" for="typeahead">Periode Bonus</label>
                <div class="controls">
                    <input readonly type="text" name="bonus_period" id="bonus_period">
                    <input
                        style="display: none;"
                        tabindex="5"
                        type="button"
                        class="btn btn-primary btn-upd-period"
                        name="change" value="Ubah Periode"
                        onclick="changeBonusPeriod()"/>
                </div>
                <div class="clearfix"></div>
                <label class="control-label" for="typeahead">CN</label>
                <div class="controls">
                    <input readonly type="text" class="fullnm_width" name="cn_no" id="cn_no">
                </div>
                <div class="clearfix"></div>
                <label class="control-label" for="typeahead">&nbsp;</label>
                <div class="controls">
                    <input
                        tabindex="5"
                        type="button"
                        class="btn btn-success boleh_klik"
                        name="save" value="Recover"
                        onclick="recoverSsr()"/>
                </div>
                <!-- end control-group -->
            </div><!-- end control-group -->
        </fieldset>
    </form>
    <div class="result"></div>
</div>
<!--/end mainForm-->
<script>

const getTotal = (arr, key) => {
  return (
    arr.map(el => parseFloat(el[key]))
    .reduce((val, el) => val + el)
  )
}
const formatter = (num, type = '') => {
  if (type) {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: type,
      minimumFractionDigits: 2
    }).format(num)
  } else {
    return new Intl.NumberFormat('id-ID', {
      minimumFractionDigits: 2
    }).format(num)
  }
}
function checkSsr(param) {
  /*prefix = param.slice(0, 3);
  if (prefix === 'PVR') {
    getPvrIp(param);
  } else {
    
    url: 'http://sco-api.k-link.me/find-ssr',
        type: 'POST',
        data: {
            batchno: param
        },
        headers: {
          'App-Key': 'k-linkmember'
        },
    */
    All.set_disable_button();
    $.ajax({
        url: 'sales/generated/check-ssr',
        type: 'POST',
        data: {
            batchno: param
        },
        dataType: 'json',
        success: function(data) {
          All.set_enable_button();
				  const { response } = data;
          if(response === 'true') {
            const { arrayData } = data;
            const { 
              loccd, total_dp, total_bv, bnsperiod, trcd2, no_deposit, batchdate, csno 
            } = arrayData[0];

            $(All.get_active_tab() + "#ipno").val(trcd2);
            $(All.get_active_tab() + "#bonus_period").val(bnsperiod);
            $(All.get_active_tab() + "#total_bv").val(All.num(parseInt(total_bv)));
            $(All.get_active_tab() + "#total_dp").val(All.num(parseInt(total_dp)));
            $(All.get_active_tab() + "#tgl_ssr").val(batchdate);
            $(All.get_active_tab() + "#v_deposit").val(no_deposit);
            $(All.get_active_tab() + "#kode_stockist").val(loccd);
            $(All.get_active_tab() + "#cn_no").val(csno);

            if(csno !== null && csno !== '') {
              alert(`Laporan sudah diproses menjadi ${csno}`);
              $(All.get_active_tab() + " .boleh_klik").attr("disabled", "disabled");
              $(All.get_active_tab() + "#bonus_period").attr("readonly", "readonly");
              $(All.get_active_tab() + ".btn-upd-period").css("display", "none");
            } else {
              $(All.get_active_tab() + ".boleh_klik").removeAttr("disabled");
              $(All.get_active_tab() + "#bonus_period").removeAttr("readonly");
              $(All.get_active_tab() + ".btn-upd-period").css("display", "block");
            }
          } else {
            alert(data.message);
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(thrownError + ':' +xhr.status);
				  All.set_enable_button();
        }
    });
    
}

function recoverSsr() {
    $.ajax({
        url: All.get_url('sales/generated/recover-ssr'),
        type: 'POST',
        data: {
            ssr: $('#ssr').val(),
            vch: $('#v_deposit').val(),
            cn_no: $('#cn_no').val()
        },
        dataType: 'json',
        success: function (data) {
            alert(data.message)
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(thrownError + ':' +xhr.status);
        }
    })
}

function changeBonusPeriod() {
    $.ajax({
        url: All.get_url('sales/generated/change-bonus-period'),
        type: 'POST',
        data: {
            bnsperiod: $('#bonus_period').val(),
            batchno: $('#ssr').val(),
            cn_no: $('#cn_no').val()
        },
        dataType: 'json',
        success: function (data) {
            alert(data.message)
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(`${thrownError} : ${xhr.status}`)
        }
    })
}

function getPvrIp(ssr) {
  $.ajax({
    url: All.get_url('transaction/sales_trans/getPvrIp'),
    type: 'POST',
    dataType: 'json',
    data: {
      ssr: ssr,
    },
    success: data => {
      $('#ipno').val(data.arrayData[0].trcd)
    }
  })
}
</script>
<?php setDatePicker(); ?>