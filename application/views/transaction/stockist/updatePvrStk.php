<div class="mainForm">
    <form class="form-horizontal" enctype="multipart/form-data" id="updStkPvr">
        <fieldset>
            <div class="control-group">
                <label class="control-label" for="typeahead">PVR No</label>
                <div class="controls">
                    <input type="text" class="fullnm_width" name="pvr" id="pvr" onchange="checkNilaiPvr()" />
                    <input
                        tabindex="5"
                        type="button"
                        class="btn btn-primary"
                        name="check" value="Check"
                        onclick="checkNilaiPvr()"/>
                </div>
                <div class="clearfix"></div>
                <label class="control-label" for="typeahead">Kode Stockist</label>
                <div class="controls">
                    <input type="text" style="width:150px;" name="kode_stockist" id="kode_stockist" onchange="All.getFullNameByID(this.value,'db2/get/fullnm/from/mssc/loccd','#nama_stockist')">
                    <input readonly type="text" class="fullnm_width" name="nama_stockist" id="nama_stockist">
                </div>
                
                <label class="control-label" for="typeahead">&nbsp;</label>
                <div class="controls">
                    <input
                        tabindex="5"
                        type="button"
                        class="btn btn-success"
                        name="save" value="Update Stokist"
                        onclick="updatePvr()"/>
                </div>
                <!-- end control-group -->
            </div><!-- end control-group -->
        </fieldset>
    </form>
    <div class="result"></div>
</div>
<script>
  function checkNilaiPvr() {
    const nilai = $(All.get_active_tab() + " #pvr").val();
    if(nilai !== "") {
	    All.set_disable_button();
			$.ajax({
        url: All.get_url('sales/pvr/updatestk/get/') + nilai,
        type: 'GET',
				dataType: 'json',
        success:
        function(data){
          All.set_enable_button();
          if(data.response == "true") {
            const { arrayData } = data;
            const { loccd, fullnm } = arrayData[0];
            $(All.get_active_tab() + " #kode_stockist").val(loccd);
            $(All.get_active_tab() + " #nama_stockist").val(fullnm);
          } else {
            $(All.get_active_tab() + " #kode_stockist").val(null);
            $(All.get_active_tab() + " #nama_stockist").val(null);
          }
          
          
	      }, error: function (xhr, ajaxOptions, thrownError) {
	        alert(thrownError + ':' +xhr.status);
					All.set_enable_button();
	      }
	    });
    } else {
      alert("PVR harus diisi..");
    }
  }

  function updatePvr() {
    const pvr = $(All.get_active_tab() + " #pvr").val();
    const kode_stockist = $(All.get_active_tab() + " #kode_stockist").val();
    All.set_disable_button();
    $.ajax({
      url: All.get_url('sales/pvr/updatestk/save'),
      type: 'POST',
      dataType: 'json',
      data: {pvr: pvr, kode_stockist: kode_stockist},
      success:
      function(data){
        All.set_enable_button();
        alert(data.message);
        
      }, error: function (xhr, ajaxOptions, thrownError) {
        alert(thrownError + ':' +xhr.status);
        All.set_enable_button();
      }
    });
    
  }
</script>