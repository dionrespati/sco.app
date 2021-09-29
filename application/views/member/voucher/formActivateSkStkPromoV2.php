<div class="mainForm">
  <span id="form_check_voucher">
    <form class="form-horizontal" method="post" id="frm_reg" name="frm_reg">
      <fieldset>

        <label class="control-label" for="typeahead">Search Type</label>
        <div class="controls">
          <select
            class="span7"
            name="type"
            id="type"
            onchange="changeType()"
          >
            <option value="0">--Select Type--</option>
            <option
              value="voucher-promo"
            >List Voucher Promo SK D'flora 50rb,Black Jam Small 75rb dan Chloro 90rb</option>
            <option value="voucher-status">Check Voucher Status</option>
          </select>
        </div>

        <div class="voucher-promo">
          <label class="control-label" for="typeahead">Voucher Status</label>
          <div class="controls">
            <select
              class="span7"
              name="status"
              id="status"
            >
              <option value="1">Belum Digunakan</option>
              <option value="2">Sudah Digunakan</option>
            </select>
          </div>
          <div class="clearfix"></div>
        </div>

        <div class="clearfix"></div>

        <div class="voucher-status">
          <label class="control-label" for="typeahead">Voucher No</label>
          <div class="controls">
            <input type="text" class="span7" id="voucherno" name="voucherno" value="" />
          </div>
          <div class="clearfix"></div>

          <label class="control-label" for="typeahead">Voucher Key</label>
          <div class="controls">
            <input type="text" class="span7" id="voucherkey" name="voucherkey" value="" />
          </div>
        </div>

        <div class="control-group">
          <div class="clearfix"></div>
          <label class="control-label" for="typeahead">&nbsp;</label>
          <div class="controls">
            <input type="button" id="submits" class="btn btn-primary" onclick="checkVoucher()" name="submit"
              value="Check Voucher" />
          </div>
        </div>

      </fieldset>
      <input type="hidden" name="state" id="state" />

    </form>
  </span>
  <div id="hasil"></div>
</div>

<script>

  $(document).ready(function() {
    $(".voucher-status").hide();
    $(".voucher-promo").hide();
  })

  function changeType() {
    var type = $("#type").val();

    if (type === 'voucher-status') {
      $(".voucher-status").show();
      $(".voucher-promo").hide();
    } else if (type === 'voucher-promo') {
      $(".voucher-status").hide();
      $(".voucher-promo").show();
    } else {
      $(".voucher-status").hide();
      $(".voucher-promo").hide();
    }
  }

  function checkVoucher() {
    All.set_disable_button();
    var voucherno = $("#voucherno").val();
    var voucherkey = $("#voucherkey").val();
    var type = $("#type").val();
    var status = $("#status").val();
    $.ajax({
      url: All.get_url('release/check/vch-stk'),
      type: 'POST',
      data: {
        voucherno: voucherno,
        voucherkey: voucherkey,
        type: type,
        status: status
      },
      success: function (data) {
        All.set_enable_button();
        $("#hasil").html(null);
        $("#hasil").html(data);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        All.set_enable_button();
      }
    });
  }

  function releaseVch(vchno, vchkey, stk) {
    All.set_disable_button();
    $.ajax({
      url: All.get_url('c_member/releaseVchStk/'),
      type: 'POST',
      data: {
        voucherno: vchno,
        voucherkey: vchkey,
        user: stk
      },
      success: function (data) {
        All.set_enable_button();
        $("#hasil").html(null);
        $("#hasil").html(data);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        All.set_enable_button();
      }
    });
  }
</script>