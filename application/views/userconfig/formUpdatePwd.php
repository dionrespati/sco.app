<div class="mainForm">
  <form class="form-horizontal" enctype="multipart/form-data" id="formUpdatePwd">
    <fieldset>      
      <div class="control-group">                      
        <label class="control-label" for="typeahead">Username</label>                             
        <div class="controls">
            <input type="text" readonly="readonly" id="username" name="username" class="span4" value="<?php echo $username; ?>" />
        </div>
        <label class="control-label" for="typeahead">Password Lama</label>                             
        <div class="controls">
            <input type="text" id="old_password" name="old_password" class="span4" value="" />
        </div>
        <label class="control-label" for="typeahead">Password Baru</label>                             
        <div class="controls">
            <input type="text" id="new_password" name="new_password" class="span4" value="" />
        </div>
        <label class="control-label" for="typeahead">&nbsp;</label>                             
        <div class="controls"  id="inp_btn">
            <input tabindex="3" type="button" id="btn_input_user" class="btn btn-primary .submit" name="save" value="Submit" onclick="All.ajaxFormPost(this.form.id, 'password/change/save')" />
            <input tabindex="4"  type="reset" class="btn btn-reset" value="Reset" />
         </div>
       </div> <!-- end control-group -->
     </fieldset>
  </form>   
  <div class="result"></div>
</div><!--/end mainForm-->
