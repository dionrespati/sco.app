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
                        class="btn btn-primary"
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
                        class="btn btn-success"
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
  prefix = param.slice(0, 3);
    $.ajax({
        url: 'http://sco-api.k-link.me/find-ssr',
        type: 'POST',
        data: {
            batchno: param
        },
        headers: {
          'App-Key': 'k-linkmember'
        },
        dataType: 'json',
        success: result => {
            $(':input','#formInputList')
              .not(':button, :submit, :reset, :hidden, #ssr')
              .val('')
            if (result.status === 'failed') {
              alert(result.message)
            } else {

              // convert voucher deposit cash into string
              const vc = result.data.map(el => {
                if (el.no_deposit !== null && el.no_deposit !== undefined) {
                  return `'${el.no_deposit}'`
                }
              })
              .filter(el => el !== undefined)

              $('#kode_stockist').val(result.data[0].loccd)
              $('#tgl_ssr').val(result.data[0].batchdate)

              $('#v_deposit').val(vc)

              $('#total_dp').val(formatter(
                result.data[result.data.length-1].total_dp
              ))

              $('#total_bv').val(formatter(
                result.data[result.data.length-1].total_bv
              ))

              $('#bonus_period').val(result.data[0].bnsperiod)

              if (result.data[0].trcd2) {
                $('#ipno').val(result.data[0].trcd2)
              } else {
                $('#ipno').val(result.data[0].trcd2)
              }

              if (result.data[0].csno) {
                $('#cn_no').val(result.data[0].csno)
                $('input[name=change]').hide()
                $('input[name=save]').prop('disabled', true)
                alert(result.message)
              } else {
                $('#total_dp').val(formatter(
                  result.data[result.data.length-1].total_dp
                ))
                $('#total_bv').val(formatter(
                  result.data[result.data.length-1].total_bv
                ))
                $('#cn_no').val('')
                $('input[name=change]').show()
                $('#bonus_period').prop('readonly', false)
                $('input[name=save]').prop('disabled', false)
              }
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + ':' +xhr.status);
        }
    })
    if (prefix === 'PVR') getPvrIp(param);
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