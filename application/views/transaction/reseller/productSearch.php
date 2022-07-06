<div class="mainForm">
  <form class="form-horizontal" enctype="multipart/form-data" id="formProductSrcRes">
    <fieldset>      
      <div class="control-group">
      	<label class="control-label" for="typeahead">Pencarian</label>
      	<div class="controls">
      		<select id="param" name="param" class="span4">
      			<option value="prdcd">Kode Produk</option>
      			<option value="prdnm">Nama Produk</option>
            <option value="nama_reseller">Nama Reseller</option>
      		</select>	
      	</div>
      	<label class="control-label" for="typeahead">Parameter/Nilai</label>
      	<div class="controls">
      		<input type="text" class="TabOnEnter span6" id="paramValue" name="paramValue" placeholder="Product ID / Name" />	
      	</div>
      	
      	<label class="control-label" for="typeahead">&nbsp</label>                             
        <div class="controls"  id="inp_btn">
            <input type="button" id="btn_input_user" class="btn btn-primary .submit" name="save" value="Submit" onclick="All.ajaxFormPost(this.form.id,'reseller/produk/list')" />
            <input type="reset" class="btn btn-reset" value="Reset" />
            <input type="button" id="btn_input_user" class="btn btn-primary .submit" name="save" value="List Produk Reseller" onclick="All.ajaxFormPost(this.form.id,'reseller/produk/all')" />
            <input type="button" id="btn_input_user" class="btn btn-primary .submit" name="save" value="List Nama Reseller" onclick="All.ajaxFormPost(this.form.id,'reseller/name/all')" />
         </div>
        </div> <!-- end control-group -->
      </div><!-- end control-group -->
    </fieldset>
  </form>   
  <div class="result"></div>
</div><!--/end mainForm-->