<div class="mainForm">
	<form class="form-horizontal" id="chkBundle">
		<fieldset>
			<label class="control-label showDt" for="typeahead">Tipe Produk Promo</label>
			<div class="controls">
				<select style="width: 450px;" id="promotype" name="promotype" onchange="listDetailBundling(this.value)">
					<option value="#">- Tipe -</option>
                    <?php 
                        if($listBundle !== null) {
                            foreach($listBundle as $dtaBundle) {
                                $parValue = $dtaBundle['kode_bundle']."|".$dtaBundle['jum_max'];
                                echo "<option value=\"$parValue\">".$dtaBundle['kode_bundle']." - ".$dtaBundle['nama_bundle']."</option>";
                            }
                        }
                    ?>
					
				</select>
                <input type="hidden" id="source_type" name="source_type" value="backend" />
			</div>
			<div class="showProd">

			</div>
            <label class="control-label" for="typeahead">&nbsp;</label>
            <div class="controls">
            <input tabindex="5" type="button" id="chkBnleButton" class="btn btn-primary .submit" name="checkBundleBtn" value="Check Kode Bundle" onclick="All.ajaxFormPost('chkBundle','product/bundling/code')" />
            </div>
		</fieldset>
		<div class="result"></div>
	</form>
</div>

<script>
	function listDetailBundling(param) {

        if(param !== "#") {
            All.set_disable_button();
            var resParam = param.split("|");
            $.ajax({
                url: All.get_url('product/bundling/list/') + resParam[0],
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    //console.log(data);
                    All.set_enable_button();
                    if(data.response == "true") {
                        
                        var jum = resParam[1];
                        $(All.get_active_tab()  + " .showProd").html(null);
                        var xhtml = "";
                        var arrayData = data.arrayData;

                        console.log(arrayData);
                        $.each(arrayData, function(key, value) {
                            var jum_max = parseInt(value.jum_max);
                            /* console.log(value.tipe);
                            console.log(key);
                            console.log(value.listprd); */
                            for(var i = 0; i < jum_max; i++) {
                                xhtml += "<label class='control-label' for='typeahead'>"+value.tipe+" : "+(i+1)+"</label>";
                                xhtml += "<select name='prdcd[]' style='width: 450px;'>"; 
                                var listprd = value.listprd;
                                console.log(listprd); 
                                
                                $.each(listprd, function(index, nilai) {
                                    xhtml += "<option value='"+nilai.kode+"'>"+nilai.prdnm+"</option>";
                                });    
                                xhtml += "</select>&nbsp;Qty :&nbsp;";
                                //xhtml += "<input type=text style='width:50px;' name='qty[]' value='' onkeyup='this.value=this.value.replace(/[^\d]/,'')'' />";
                                xhtml += "<select style='width:100px;' name='qty[]'>";
                                for(var j = 0; j <= jum_max; j++) {
                                    xhtml += "<option value="+j+">"+j+"</option>";
                                }    
                                xhtml += "</select>";
                                xhtml += "<br />"; 
                            } 
                        });   
                        $(All.get_active_tab()  + " .showProd").append(xhtml); 

                    } else {
                        alert(data.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    All.set_enable_button();
                }
            });
        }
	}
</script>