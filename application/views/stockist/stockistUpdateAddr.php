<?php
if($result !== null) { 	
?>
<div class="mainForm">
  <form class="form-horizontal" enctype="multipart/form-data" id="formUpdateStockist">
    <fieldset>      
      <div class="control-group">
      	<label class="control-label" for="typeahead">Kode Stockist</label>
      	<div class="controls">
      		<input <?php echo $onchange; ?> <?php echo $loccd_read; ?> type="text" class="TabOnEnter" id="loccd" style="width: 150px;" name="loccd" value="<?php echo $result[0]->loccd ?>" placeholder="Kode Stockist" onkeydown="All.upperCaseValue(this)" />	
      	</div>
      	<label class="control-label" for="typeahead">Nama Stockist</label>
      	<div class="controls">
      		<input value="<?php echo $result[0]->fullnm ?>"  type="text" class="TabOnEnter span6" id="fullnm" name="fullnm" readonly="readonly" placeholder="Stockist Fullname / Owner" />	
      	</div>
      	<label class="control-label" for="typeahead">Alamat</label>
      	<div class="controls">
      		<input value="<?php echo $result[0]->addr1 ?>"  type="text" class="TabOnEnter span6" id="addr1" name="addr1" onkeydown="All.upperCaseValue(this)" />	
      	</div>
      	<label class="control-label" for="typeahead">&nbsp;</label>
      	<div class="controls">
      		<input value="<?php echo $result[0]->addr2 ?>"  type="text" class="TabOnEnter span6" id="addr2" name="addr2" onkeydown="All.upperCaseValue(this)" />	
      	</div>
      	<label class="control-label" for="typeahead">&nbsp;</label>
      	<div class="controls">
      		<input value="<?php echo $result[0]->addr3 ?>"  type="text" class="TabOnEnter span6" id="addr3" name="addr3" onkeydown="All.upperCaseValue(this)" />	
      	</div>
      	<label class="control-label" for="typeahead">No HP</label>
      	<div class="controls">
      		<input value="<?php echo $result[0]->tel_hp ?>"  type="text" class="TabOnEnter span6" id="tel_hp" name="tel_hp" onkeyup="All.numOnly(this)" />	
      	</div>
      	<label class="control-label" for="typeahead">No Telp Kantor</label>
      	<div class="controls">
      		<input value="<?php echo $result[0]->tel_of ?>"  type="text" class="TabOnEnter span6" id="tel_of" name="tel_of" onkeyup="All.numOnly(this)" />	
      	</div>
      	<label class="control-label" for="typeahead">No Telp Rumah</label>
      	<div class="controls">
      		<input value="<?php echo $result[0]->tel_hm ?>"  type="text" class="TabOnEnter span6" id="tel_hm" name="tel_hm" onkeyup="All.numOnly(this)" />	
      	</div>
		  <label class="control-label" for="typeahead">Latitude</label>
      	<div class="controls">
      		<input value="<?php echo $result[0]->latitude ?>"  type="text" class="TabOnEnter span6" id="latitude" name="latitude" />	
      	</div>
      	<label class="control-label" for="typeahead">Longitude</label>
      	<div class="controls">
      		<input value="<?php echo $result[0]->longitude ?>"  type="text" class="TabOnEnter span6" id="longitude" name="longitude" />	
      	</div>
      	<?php
      	if($idstk == "BID06") {
      	?>
		<!--  
      	<label class="control-label" for="typeahead">Lastkit Member</label>
      	<div class="controls">
      		<input value="<?php echo $result[0]->lastkitno ?>" readonly="readonly"  type="text" class="TabOnEnter span6" id="lastkitno" name="lastkitno" />	
      	</div>
      	<label class="control-label" for="typeahead">Max Kuota Reg Member</label>
      	<div class="controls">
      		<input value="<?php echo $result[0]->limitkit ?>"  type="text" readonly="readonly" class="TabOnEnter span6" id="limitkit" name="limitkit" />	
      	</div>
      	<label class="control-label" for="typeahead">Sisa Kuota Input</label>
      	<div class="controls">
      		<input value="<?php echo $result[0]->sisa_kuota ?>"  type="text" readonly="readonly" class="TabOnEnter span6" id="sisa_kuota" name="sisa_kuota" />	
      	</div> -->
      	<?php
		} else {
		?>
		<!--
		<label class="control-label" for="typeahead">Lastkit Member</label>
      	<div class="controls">
      		<input value="<?php echo $result[0]->lastkitno ?>" readonly="readonly"  type="text" readonly="readonly" class="TabOnEnter span6" id="lastkitno" name="lastkitno" />	
      	</div>
      	<label class="control-label" for="typeahead">Max Kuota Reg Member</label>
      	<div class="controls">
      		<input value="<?php echo $result[0]->limitkit ?>"  type="text" readonly="readonly" class="TabOnEnter span6" id="limitkit" name="limitkit" />	
      	</div>
      	<label class="control-label" for="typeahead">Sisa Kuota Input</label>
      	<div class="controls">
      		<input value="<?php echo $result[0]->sisa_kuota ?>"  type="text" readonly="readonly" class="TabOnEnter span6" id="sisa_kuota" name="sisa_kuota" />	
      	</div> -->
		<?php		
		}
      	?>
		<label class="control-label" for="typeahead">Provinsi</label>
      	<div class="controls">
      		<select id="province" name="province" onchange="showAreaSelect(this,'stockist/kabupaten/list','#kabupaten')">
			   <option value="">--Pilih disini--</option>
			   <?php
                 foreach($listProvince as $dta)
                 {
                    if($result[0]->kode_provinsi == $dta->kode)
                    {
                        echo "<option value=\"$dta->kode\" selected>$dta->nama</option>";
                    }
                    else
                    {
                     echo "<option value=\"$dta->kode\">$dta->nama</option>";
                    }
                 }
                ?>
			</select>
      	</div>
		<label class="control-label" for="typeahead">Kabupaten</label>
      	<div class="controls">
      		<select id="kabupaten" name="kabupaten" onchange="showAreaSelect(this,'stockist/kecamatan/list','#kecamatan')">
			   <option value="">--Pilih disini--</option>
			   <?php 
			 	if($listKabupaten !== null) {
					foreach($listKabupaten as $dta)
					{
						if($result[0]->kode_kabupaten == $dta->kode)
						{
							echo "<option value=\"$dta->kode\" selected>$dta->nama</option>";
						}
						else
						{
						echo "<option value=\"$dta->kode\">$dta->nama</option>";
						}
					}	
				}  
			   ?>
			</select>
      	</div>
		<label class="control-label" for="typeahead">Kecamatan</label>
      	<div class="controls">
      		<select id="kecamatan" name="kecamatan" onchange="showAreaSelect(this,'stockist/kelurahan/list','#kelurahan')">
			   <option value="">--Pilih disini--</option>
			   <?php 
			 	if($listKecamatan !== null) {
					foreach($listKecamatan as $dta)
					{
						if($result[0]->KEC_JNE == $dta->kode)
						{
							echo "<option value=\"$dta->kode\" selected>$dta->nama</option>";
						}
						else
						{
						echo "<option value=\"$dta->kode\">$dta->nama</option>";
						}
					}	
				}  
			   ?>
			</select>
      	</div>
		<label class="control-label" for="typeahead">Kelurahan</label>
      	<div class="controls">
      		<select id="kelurahan" name="kelurahan" onchange="showAreaSelect(this,'stockist/kodepos','#postcd')">
			   <option value="">--Pilih disini--</option>
			   <?php 
			 	if($listKelurahan !== null) {
					foreach($listKelurahan as $dta)
					{
						if($result[0]->kelurahan == $dta->kode)
						{
							echo "<option value=\"$dta->kode\" selected>$dta->nama</option>";
						}
						else
						{
						echo "<option value=\"$dta->kode\">$dta->nama</option>";
						}
					}	
				}  
			   ?>
			</select>
      	</div>
		  <label class="control-label" for="typeahead">Kode Pos</label>
      	<div class="controls">
      		<input value="<?php echo $result[0]->postcd ?>"  type="text" class="TabOnEnter span2" id="postcd" name="postcd" />	
      	</div>            
      	<label class="control-label" for="typeahead">&nbsp</label>                             
        <div class="controls"  id="inp_btn">
            <input type="button" id="btn_input_user" class="btn btn-primary .submit" name="save" value="Submit" onclick="All.ajaxPostResetField(this.form.id,'stockist/addr/update')" />
            <input type="reset" class="btn btn-reset" value="Reset" />
            
         </div>
        </div> <!-- end control-group -->
      </div><!-- end control-group -->
    </fieldset>
  </form>   
  <div class="result"></div>
</div><!--/end mainForm-->
<script>
	function showAreaSelect(param, url, setTo) {
		var idx = param.id;
		var nilai = param.value;
		var urlx = url + "/" +nilai;
		All.set_disable_button();
		$.ajax({
            url: All.get_url(urlx) ,
			type: 'GET',
			dataType: "json",
            success:
            function(data){
				All.set_enable_button();
				if(data.response == "true") {
					console.log(idx);
					var arrayData = data.arrayData;
					if(idx == "kelurahan") {
						$(All.get_active_tab() + setTo).val(arrayData[0].kodepos);
					} else {
						$(All.get_active_tab() + setTo).html(null);
						var rowhtml = "<option value=''>--Pilih disini--</option>";
						$.each(arrayData, function(key, value) {
							rowhtml += "<option value='"+value.kode+"'>"+value.nama+"</option>";
						});	
						$(All.get_active_tab() + setTo).append(rowhtml);
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
</script>
<?php
}
?>