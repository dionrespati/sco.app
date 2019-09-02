var $ = jQuery;

var All = {
	get_base : function() {
       var url = "http://www.k-linkmember.co.id/sco.app/";
<<<<<<< HEAD
       return url;  
    },
    
=======
       return url;
    },

>>>>>>> devel
    get_url : function(urlx) {
       var url = All.get_base() + urlx;
       return url;
    },
<<<<<<< HEAD
    
    get_active_tab : function() {
       var active_div =  $("#active_div").val();
       var x = "#tabs-" + active_div + " "; 
       return x;
    },
    
=======

    get_active_tab : function() {
       var active_div =  $("#active_div").val();
       var x = "#tabs-" + active_div + " ";
       return x;
    },

>>>>>>> devel
    get_image_load : function(set_to) {
        set_to = set_to || ".mainForm .result";
        $(All.get_box_content() + set_to).html("<center><img src="+ All.get_base() +"/assets/images/ajax-loader.gif ></center>");
    },
<<<<<<< HEAD
    
=======

>>>>>>> devel
    get_image_load2 : function() {
        //set_to = set_to || ".mainForm > .result";
        $(All.get_active_tab() + ".result").html("<center><img src="+ All.get_base() +"/assets/images/ajax-loader.gif ></center>");
    },
<<<<<<< HEAD
    
    set_wait_message: function() {
        var ht = "";
        ht += "<div class=wait_msg style='display: none;' align=center><font color=red>Please wait a moment...</font></div>";
        ht += "<div class=img_load></div>"; 
        return ht; 
    },
    
=======

    set_wait_message: function() {
        var ht = "";
        ht += "<div class=wait_msg style='display: none;' align=center><font color=red>Please wait a moment...</font></div>";
        ht += "<div class=img_load></div>";
        return ht;
    },

>>>>>>> devel
    clear_wait_message: function() {
        $(All.get_active_tab() + ".wait_msg").css('display', 'none');
        $(All.get_active_tab() + ".img_load").html(null);
    },
<<<<<<< HEAD
    
    set_amount_record : function(count) {
        var ht = "";
        ht += "<input type=hidden id=cnt_rec value="+count+" />";
        ht += "<input type=hidden id=rack_cnt value="+count+" />"; 
        return ht; 
    },
    
=======

    set_amount_record : function(count) {
        var ht = "";
        ht += "<input type=hidden id=cnt_rec value="+count+" />";
        ht += "<input type=hidden id=rack_cnt value="+count+" />";
        return ht;
    },

>>>>>>> devel
    get_wait_message:function() {
        $(All.get_active_tab() + ".wait_msg").css('display', 'block');
        //All.get_image_load(All.get_active_tab() + " .img_load");
        $(All.get_active_tab() + ".img_load").html("<center><img src="+ All.get_base() +"/assets/images/ajax-loader.gif ></center>");
    },
<<<<<<< HEAD
    
=======

>>>>>>> devel
    clear_wait_message: function() {
        $(All.get_active_tab() + ".wait_msg").css('display', 'none');
        $(All.get_active_tab() + ".img_load").html(null);
    },
<<<<<<< HEAD
    
    set_datatable : function() {
        $(All.get_active_tab() + ".datatable").dataTable({
        
        "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
        "sPaginationType": "bootstrap",
		"oLanguage": {
			
=======

    set_datatable : function() {
        $(All.get_active_tab() + ".datatable").dataTable({

        "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
        "sPaginationType": "bootstrap",
		"oLanguage": {

>>>>>>> devel
		},
        "bDestroy": true
	  });
      $(All.get_active_tab() + ".datatable").removeAttr('style');
    },
<<<<<<< HEAD
    
    set_disable_button : function() {
    	$(All.get_active_tab() + ".btn").attr('disabled', 'disabled');
       
    },
    
    set_enable_button : function() {
        $(All.get_active_tab() + ".btn").removeAttr('disabled');
        
    },
    
=======

    set_disable_button : function() {
    	$(All.get_active_tab() + ".btn").attr('disabled', 'disabled');

    },

    set_enable_button : function() {
        $(All.get_active_tab() + ".btn").removeAttr('disabled');

    },

>>>>>>> devel
    reset_all_input : function() {
        $(All.get_active_tab() + "input[type=file]").val(null);
        $(All.get_active_tab() + "input[type=text]").val(null);
		$(All.get_active_tab() + "input[type=password]").val(null);
        $(All.get_active_tab() + "textarea").val(null);
        $(All.get_active_tab() + "select").val("");
    },
<<<<<<< HEAD
    
=======

>>>>>>> devel
    reset_all_input2 : function() {
        $(All.get_active_tab() + "input[type=file]").val(null);
        $(All.get_active_tab() + "input[type=text]").val(null);
        $(All.get_active_tab() + "input[type=hidden]").val(null);
		$(All.get_active_tab() + "input[type=password]").val(null);
        $(All.get_active_tab() + "textarea").val(null);
        $(All.get_active_tab() + "select").val("");
    },
<<<<<<< HEAD
    
=======

>>>>>>> devel
    formUpdateActivate : function() {
    	$(All.get_active_tab() + "#inp_btn").css('display', 'none');
		$(All.get_active_tab() + "#upd_btn").css('display', 'block');
		$(All.get_active_tab() + ".setReadOnly").attr('readonly','readonly');
    },
<<<<<<< HEAD
    
    
    
=======



>>>>>>> devel
    reload_page : function(urlx) {
    	var x = All.get_active_tab();
    	$.ajax({
            url: All.get_url(urlx) ,
            type: 'GET',
            success:
            function(data){
                $(All.get_active_tab()).html(null);
                //$(All.get_active_tab()).html("<center><img src="+ All.get_base() +"/assets/images/ajax-loader.gif ></center>");
                $(All.get_active_tab()).html(data);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                 alert(thrownError + ':' +xhr.status);
				 All.set_enable_button();
            }
<<<<<<< HEAD
      }); 
     //alert(urlx);
    },
    
=======
      });
     //alert(urlx);
    },

>>>>>>> devel
    back_to_form : function(clear_div, show_div) {
        $(All.get_active_tab() + clear_div).html(null);
        $(All.get_active_tab() + show_div).show();
    },
<<<<<<< HEAD
    
=======

>>>>>>> devel
    back_to_form2 : function(clear_div, show_div, set_header) {
        $(All.get_box_content() + clear_div).html(null);
        $(All.get_box_content() + show_div).show();
        $(All.get_header_form()).html(set_header);
    },
<<<<<<< HEAD
    
=======

>>>>>>> devel
    get_header_form : function() {
        var getDiv = All.get_active_tab()+ "> .row-fluid > .block > .block-heading";
        return getDiv;
    },
<<<<<<< HEAD
    
=======

>>>>>>> devel
    get_box_content : function() {
        var getDiv = All.get_active_tab()+ "> .row-fluid > .block > .block-body > .box-content > ";
        return getDiv;
    },
<<<<<<< HEAD
    
    clear_div_in_boxcontent : function(set_clear) {
      $(All.get_box_content() + set_clear).html(null);  
    },
        
    set_error_message : function(set_to, param) {
        set_to = set_to || "hasil";
        param = param || "No result found";
        
        $(All.get_box_content() + set_to).html(null);
        $(All.get_box_content() + set_to).html("<div class='alert alert-error' align=center>"+param+"</div>");
    },
    
=======

    clear_div_in_boxcontent : function(set_clear) {
      $(All.get_box_content() + set_clear).html(null);
    },

    set_error_message : function(set_to, param) {
        set_to = set_to || "hasil";
        param = param || "No result found";

        $(All.get_box_content() + set_to).html(null);
        $(All.get_box_content() + set_to).html("<div class='alert alert-error' align=center>"+param+"</div>");
    },

>>>>>>> devel
    show_mainForm_after_process : function() {
        All.clear_div_in_boxcontent(".nextForm1");
        All.clear_div_in_boxcontent(".nextForm2");
        All.clear_div_in_boxcontent(".nextForm3");
        All.clear_div_in_boxcontent(".nextForm4");
<<<<<<< HEAD
        
        All.set_all_to_display();
        
        $(All.get_box_content() + ".mainForm").show();
        All.clear_div_in_boxcontent(".mainForm > .result");
    },  
    
=======

        All.set_all_to_display();

        $(All.get_box_content() + ".mainForm").show();
        All.clear_div_in_boxcontent(".mainForm > .result");
    },

>>>>>>> devel
    set_all_to_display : function() {
      $(All.get_box_content() + ".nextForm1").css('display', 'block');
      $(All.get_box_content() + ".nextForm2").css('display', 'block');
      $(All.get_box_content() + ".nextForm3").css('display', 'block');
      $(All.get_box_content() + ".nextForm4").css('display', 'block');
    },
<<<<<<< HEAD
    
=======

>>>>>>> devel
    set_success_message : function(set_to, param) {
        set_to = set_to || "hasil";
        param = param || "Success";
        $(All.get_box_content() + set_to).html(null);
        $(All.get_box_content() + set_to).html("<div class='alert alert-success' align=center>"+param+"</div>");
    },
	num : function(val) {
		var result = val.toString().split('').reverse().join("").match(/[0-9]{1,3}/g).join(".").match(/./g).reverse().join("");
		return result;
	},
<<<<<<< HEAD
	
=======

>>>>>>> devel
	num_normal : function(val) {
         var result = val.toString().split('').reverse().join("")
                          .match(/[0-9]{1,3}/g).join("")
                          .match(/./g).reverse().join("");
<<<<<<< HEAD
		return result;    
	}, 
    
=======
		return result;
	},

>>>>>>> devel
    nextFocus : function(h, prev, next, evt)
      {
          evt = (evt) ? evt : event;
    	  var charCode = (evt.charCode) ? evt.charCode :
    	  ((evt.which) ? evt.which : evt.keyCode);
    	  if (charCode == 13 || charCode == 3 || charCode == 9)
    	   {
    	     h.elements[next].focus( );
    		 //h.elements[next].select( );
    		 //trans.h.value = trans.h.value.toUpperCase();
             return false;
<<<<<<< HEAD
    
=======

>>>>>>> devel
    	   }
    	   else
    	   {
    	     if(charCode == 43)
    		 {
    		   h.elements[prev].focus( );
    		   //h.elements[next].select( );
    		   return false;
    		 }
    	   }
    	   return true
      },
<<<<<<< HEAD
      
    checkUncheckAll: function(theElement) 
    {
    
    	var theForm = theElement.form;
    
    	for(z=0; z<theForm.length;z++)
    	{
     
=======

    checkUncheckAll: function(theElement)
    {

    	var theForm = theElement.form;

    	for(z=0; z<theForm.length;z++)
    	{

>>>>>>> devel
    		if(theForm[z].type == 'checkbox' && theForm[z].name != 'checkall')
    		{
    			theForm[z].checked = theElement.checked;
    		}
<<<<<<< HEAD
     
    	}
     },
     
=======

    	}
     },

>>>>>>> devel
     checkUncheckByClass : function(kelas, setto) {
     	var tes = $(All.get_active_tab() + "." +kelas).is(':checked');
     	if(tes) {
     		if(kelas == "menux") {
     			$(All.get_active_tab() + "." +setto).prop('checked', true);
     		} else {
     			$(All.get_active_tab() + "." +setto).prop('checked', true);
     			$(All.get_active_tab() + "." +setto).val(1);
     		}
<<<<<<< HEAD
     		
=======

>>>>>>> devel
     	} else {
     		if(kelas == "menux") {
     			$(All.get_active_tab() + "." +setto).prop('checked', false);
     		} else {
     			$(All.get_active_tab() + "." +setto).prop('checked', false);
     			$(All.get_active_tab() + "." +setto).val(0);
<<<<<<< HEAD
     		}	
     		
     	}
     	
     },
     
=======
     		}

     	}

     },

>>>>>>> devel
     setValCheck : function(tes) {
     	var x = tes.value;
     	if(x == "0") {
     		tes.value = "1";
     	} else {
     		tes.value = "0";
     	}
     },
<<<<<<< HEAD
     
     should_integer : function(checkField, focusIfValid) {
        var nm = /^[0-9]+$/;
        var amount = $(All.get_active_tab() + "#" +checkField).val();
        
=======

     should_integer : function(checkField, focusIfValid) {
        var nm = /^[0-9]+$/;
        var amount = $(All.get_active_tab() + "#" +checkField).val();

>>>>>>> devel
        if(amount.match(nm))
        {
            $(All.get_active_tab() + ".to_submit").removeAttr('disabled', 'yes');
            $(All.get_active_tab() + "#" +focusIfValid).focus();
        }
        else if (amount == '') {
            $(All.get_active_tab() + "#" +checkField).focus();
<<<<<<< HEAD
            
=======

>>>>>>> devel
        }
        else
        {
            alert('Number should not negative or should not contain character');
            $(All.get_active_tab() + ".to_submit").attr('disabled', 'yes');
            $(All.get_active_tab() + "#" +checkField).focus();
            //$("#amount").focus();
<<<<<<< HEAD
        }        
    },
    
=======
        }
    },

>>>>>>> devel
    checkDoubleInput: function(url, param, paramValue) {
    	if(paramValue !== "") {
	    	All.set_disable_button();
			All.get_wait_message();
			$.ajax({
	            url: All.get_url(url + param+"/" +paramValue),
	            type: 'GET',
				dataType: 'json',
	            success:
	            function(data){
	                All.set_enable_button();
	                if(data.response == "true") {
						alert("Double " +param+ " with value : " +paramValue);
<<<<<<< HEAD
					} 
=======
					}
>>>>>>> devel
	            },
	            error: function (xhr, ajaxOptions, thrownError) {
	                 alert(thrownError + ':' +xhr.status);
					 All.set_enable_button();
	            }
	        });
	    } else {
	    	alert("Please fill the field..");
<<<<<<< HEAD
	    }  
    },
    
=======
	    }
    },

>>>>>>> devel
    getListData : function(url) {
    	All.set_disable_button();
		All.get_image_load();
		$.ajax({
            url: All.get_url(url) ,
            type: 'GET',
            success:
            function(data){
                All.set_enable_button();
				All.clear_div_in_boxcontent(".mainForm .result");
				$(All.get_box_content() + ".mainForm .result").html(data);
				//$(All.get_active_tab() + " #searchF").val(0);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                 alert(thrownError + ':' +xhr.status);
				 All.set_enable_button();
            }
        });
    },
<<<<<<< HEAD
    
=======

>>>>>>> devel
    postListData: function(formID, url) {
    	All.set_disable_button();
		//All.get_image_load();
		var activeForm = $('#'+formID);
		console.log(All.get_url(url));
		$.post(All.get_url(url) , $(All.get_active_tab() + "#"+ formID).serialize(), function(data)
<<<<<<< HEAD
        {              
            All.set_enable_button();
			All.clear_div_in_boxcontent(".mainForm > .result");
			$(All.get_box_content() + ".mainForm > .result").html(data);
            
        });  
       
    },
    
=======
        {
            All.set_enable_button();
			All.clear_div_in_boxcontent(".mainForm > .result");
			$(All.get_box_content() + ".mainForm > .result").html(data);

        });

    },

>>>>>>> devel
    ajaxFormGet : function(url) {
    	All.set_disable_button();
		All.get_image_load();
		$.ajax({
            url: All.get_url(url) ,
            type: 'GET',
            success:
            function(data){
                All.set_enable_button();
				$(All.get_active_tab() + ".result").html(null);
<<<<<<< HEAD
                $(All.get_active_tab() + ".result").html(data);   
=======
                $(All.get_active_tab() + ".result").html(data);
>>>>>>> devel
            },
            error: function (xhr, ajaxOptions, thrownError) {
                 alert(thrownError + ':' +xhr.status);
				 All.set_enable_button();
            }
        });
    },
<<<<<<< HEAD
    
=======

>>>>>>> devel
    ajaxFormPost : function(formID, url) {
		All.set_disable_button();
        All.get_image_load2();
        $.post(All.get_url(url) , $(All.get_active_tab() + "#"+ formID).serialize(), function(data)
<<<<<<< HEAD
        {  
            All.set_enable_button();
            $(All.get_active_tab() + ".result").html(null);
            $(All.get_active_tab() + ".result").html(data);   
        }).fail(function() { 
            alert("Error requesting page"); 
            All.set_enable_button();
        });
	},
    
=======
        {
            All.set_enable_button();
            $(All.get_active_tab() + ".result").html(null);
            $(All.get_active_tab() + ".result").html(data);
        }).fail(function() {
            alert("Error requesting page");
            All.set_enable_button();
        });
	},

>>>>>>> devel
    ajaxPostResetField : function(formID, url){
   	    All.set_disable_button();
        All.get_image_load2();
        $.post(All.get_url(url) , $(All.get_active_tab() + "#"+ formID).serialize(), function(data)
<<<<<<< HEAD
        {  
            All.reset_all_input();
            All.set_enable_button();
            $(All.get_active_tab() + " .result").html(null);
            $(All.get_active_tab() + " .result").html(data);   
        }).fail(function() { 
            alert("Error requesting page"); 
            All.set_enable_button();
        });
    },
    
=======
        {
            All.reset_all_input();
            All.set_enable_button();
            $(All.get_active_tab() + " .result").html(null);
            $(All.get_active_tab() + " .result").html(data);
        }).fail(function() {
            alert("Error requesting page");
            All.set_enable_button();
        });
    },

>>>>>>> devel
    cancelUpdateForm : function() {
    	$(All.get_active_tab() + " #upd_btn").css('display', 'none');
	    $(All.get_active_tab() + " #inp_btn").css('display', 'block');
	    $(All.get_active_tab() + " #id").val(null);
	    $(All.get_active_tab() + " .setReadOnly").removeAttr('readonly');
        $(All.get_active_tab() + " #qtyReq").removeAttr('readonly');
	    All.reset_all_input();
	    $(All.get_active_tab() + " #country_id").val("ID");
	    $(All.get_active_tab() + " #hq_id").val("BID06");
	    $(All.get_active_tab() + " #branch_id").val("B001");
	    $(All.get_active_tab() + " #year").val((new Date).getFullYear());
	    $(All.get_active_tab() + " .fileExistingInfo").html(null);
	    $(All.get_active_tab() + " .fileHiddenExistingInfo").val(null);
    },
<<<<<<< HEAD
	
=======

>>>>>>> devel
	ajaxShowDetailonNextForm : function(urlx) {
		All.set_disable_button();
		$.ajax({
            url: All.get_url(urlx),
            type: 'GET',
            success:
            function(data){
            	All.set_enable_button();
            	$(All.get_active_tab() + ".mainForm").hide();
                All.clear_div_in_boxcontent(".nextForm1");
<<<<<<< HEAD
                $(All.get_active_tab() + ".nextForm1").html(data);  
            },
		    error: function(jqXHR, textStatus, errorThrown) {
		       All.set_enable_button();
		    } 
        });	
	}, 
	
=======
                $(All.get_active_tab() + ".nextForm1").html(data);
            },
		    error: function(jqXHR, textStatus, errorThrown) {
		       All.set_enable_button();
		    }
        });
	},

>>>>>>> devel
	ajaxShowDetailonNextFormPost : function(url, formid) {
		All.set_disable_button();
		All.get_wait_message();
		$.post(All.get_url(url) , $(All.get_active_tab() + "#"+ formid).serialize(), function(data)
<<<<<<< HEAD
        {  
=======
        {
>>>>>>> devel
            All.set_enable_button();
            	$(All.get_active_tab() + ".mainForm").hide();
                All.clear_div_in_boxcontent(".nextForm1");
                $(All.get_active_tab() + ".nextForm1").html(data);
<<<<<<< HEAD
            
        }).fail(function() { 
            alert("Error requesting page"); 
            All.set_enable_button();
        });  
	}, 
	
=======

        }).fail(function() {
            alert("Error requesting page");
            All.set_enable_button();
        });
	},

>>>>>>> devel
	ajaxShowDetailonNextForm2 : function(urlx) {
		All.set_disable_button();
		$.ajax({
            url: All.get_url(urlx),
            type: 'GET',
            success:
            function(data){
            	All.set_enable_button();
            	$(All.get_active_tab() + ".nextForm1").hide();
                All.clear_div_in_boxcontent(".nextForm2");
<<<<<<< HEAD
                $(All.get_active_tab() + ".nextForm2").html(data);  
            },
		    error: function(jqXHR, textStatus, errorThrown) {
		       All.set_enable_button();
		    } 
        });	
	}, 
	
=======
                $(All.get_active_tab() + ".nextForm2").html(data);
            },
		    error: function(jqXHR, textStatus, errorThrown) {
		       All.set_enable_button();
		    }
        });
	},

>>>>>>> devel
	ajaxShowDetailonNextForm2Post : function(url, formid) {
		All.set_disable_button();
		All.get_wait_message();
		$.post(All.get_url(url) , $(All.get_active_tab() + "#"+ formid).serialize(), function(data)
<<<<<<< HEAD
        {  
=======
        {
>>>>>>> devel
            All.set_enable_button();
            	$(All.get_active_tab() + ".nextForm1").hide();
                All.clear_div_in_boxcontent(".nextForm2");
                $(All.get_active_tab() + ".nextForm2").html(data);
<<<<<<< HEAD
            
        }).fail(function() { 
            alert("Error requesting page"); 
            All.set_enable_button();
        });  
	}, 
    
=======

        }).fail(function() {
            alert("Error requesting page");
            All.set_enable_button();
        });
	},

>>>>>>> devel
    inputFormData: function(url, formid) {
    	All.set_disable_button();
		All.get_wait_message();
		$.post(All.get_url(url) , $(All.get_active_tab() + "#"+ formid).serialize(), function(data)
<<<<<<< HEAD
        {  
            All.set_enable_button();
			if(data.response == "false") {
                All.set_error_message(".mainForm .result", data.message);
            } 
            else {
                All.set_success_message(".mainForm .result", data.message);
				All.reset_all_input();
            } 
            
        }, "json").fail(function() { 
            alert("Error requesting page"); 
            All.set_enable_button();
        });  
    },
    
    ajaxPostUpdate : function(formid, formUpdate, showFormAfterPost) {
		
=======
        {
            All.set_enable_button();
			if(data.response == "false") {
                All.set_error_message(".mainForm .result", data.message);
            }
            else {
                All.set_success_message(".mainForm .result", data.message);
				All.reset_all_input();
            }

        }, "json").fail(function() {
            alert("Error requesting page");
            All.set_enable_button();
        });
    },

    ajaxPostUpdate : function(formid, formUpdate, showFormAfterPost) {

>>>>>>> devel
		All.set_disable_button();
		All.get_image_load();
		$.ajax({
            url: All.get_url(formUpdate),
            type: 'GET',
            dataType: "json",
            success:
            function(data){
                All.set_enable_button();
                alert(data.message);
                if(data.response == "true") {
                	All.ajaxFormPost(formid, showFormAfterPost);
<<<<<<< HEAD
                } 
=======
                }
>>>>>>> devel
            },
            error: function (xhr, ajaxOptions, thrownError) {
                 alert(thrownError + ':' +xhr.status);
				 All.set_enable_button();
            }
        });
	},
<<<<<<< HEAD
    
=======

>>>>>>> devel
    updateFormData: function(url, formid, nextToLoad) {
    	All.set_disable_button();
		All.get_wait_message();
		$.post(All.get_url(url) , $(All.get_active_tab() + "#" +formid).serialize(), function(data)
<<<<<<< HEAD
        {  
=======
        {
>>>>>>> devel
            All.set_enable_button();
			alert(data.message);
			if(data.response == "true") {
                	All.reset_all_input();
                	All.cancelUpdateForm();
                	All.getListData(nextToLoad);
<<<<<<< HEAD
	            } 
	            
            
        }, "json").fail(function() { 
            alert("Error requesting page"); 
            All.set_enable_button();
        });  
    },	
    
=======
	            }


        }, "json").fail(function() {
            alert("Error requesting page");
            All.set_enable_button();
        });
    },

>>>>>>> devel
    deleteFormData: function(url, param, nextToLoad) {
    	if (confirm('Anda yakin akan menghapus data ini ?')) {
		    	All.set_disable_button();
				All.get_wait_message();
<<<<<<< HEAD
				
=======

>>>>>>> devel
				$.ajax({
		            url: All.get_url(url + param) ,
		            type: 'GET',
					dataType: 'json',
		            success:
		            function(data){
		                All.set_enable_button();
		                alert(data.message);
<<<<<<< HEAD
						if(data.response == "true") {					
=======
						if(data.response == "true") {
>>>>>>> devel
							All.getListData(nextToLoad);
						}
		            },
		            error: function (xhr, ajaxOptions, thrownError) {
		                 alert(thrownError + ':' +xhr.status);
						 All.set_enable_button();
		            }
<<<<<<< HEAD
		      }); 
		     //$(All.get_active_tab() +  "tr" ).parent().remove()
		      //console.log("ok...");
		}      
     
      //alert('url :' +url+ 'param : ' +param+ 'load :' +nextToLoad);
    },
    
=======
		      });
		     //$(All.get_active_tab() +  "tr" ).parent().remove()
		      //console.log("ok...");
		}

      //alert('url :' +url+ 'param : ' +param+ 'load :' +nextToLoad);
    },

>>>>>>> devel
    deleteRecord: function(url, param, sec_record) {
    	if (confirm('Anda yakin akan menghapus data ini ?')) {
    		All.set_disable_button();
				All.get_wait_message();
<<<<<<< HEAD
				
=======

>>>>>>> devel
				$.ajax({
		            url: All.get_url(url + param) ,
		            type: 'GET',
					dataType: 'json',
		            success:
		            function(data){
		                All.set_enable_button();
		                alert(data.message);
<<<<<<< HEAD
						if(data.response == "true") {					
=======
						if(data.response == "true") {
>>>>>>> devel
							All.getListData(nextToLoad);
						}
		            },
		            error: function (xhr, ajaxOptions, thrownError) {
		                 alert(thrownError + ':' +xhr.status);
						 All.set_enable_button();
		            }
<<<<<<< HEAD
		      }); 
    		$(All.get_active_tab() +  "tr#" +sec_record).remove();
    	}	
    },
    
=======
		      });
    		$(All.get_active_tab() +  "tr#" +sec_record).remove();
    	}
    },

>>>>>>> devel
    deleteData : function(url, formID, showFormAfterPost) {
    	All.set_disable_button();
    	$.ajax({
            url: All.get_url(url) ,
            type: 'GET',
			dataType: 'json',
            success:
            function(data){
                All.set_enable_button();
                alert(data.message);
<<<<<<< HEAD
				if(data.response == "true") {					
=======
				if(data.response == "true") {
>>>>>>> devel
					//All.getListData(nextToLoad);
					All.ajaxFormPost(formID, showFormAfterPost);
				}
            },
            error: function (xhr, ajaxOptions, thrownError) {
                 alert(thrownError + ':' +xhr.status);
				 All.set_enable_button();
            }
<<<<<<< HEAD
        }); 
    },
	
=======
        });
    },

>>>>>>> devel
    reconcileData : function(url, formID, showFormAfterPost) {
    	All.set_disable_button();
    	$.ajax({
            url: All.get_url(url) ,
            type: 'GET',
			dataType: 'json',
            success:
            function(data){
                All.set_enable_button();
                alert(data.message);
<<<<<<< HEAD
				if(data.response == "true") {	
					//alert('masuk sini');				
=======
				if(data.response == "true") {
					//alert('masuk sini');
>>>>>>> devel
					//All.getListData(nextToLoad);
					All.ajaxFormPost(formID, showFormAfterPost);
				}
            },
            error: function (xhr, ajaxOptions, thrownError) {
                 alert(thrownError + ':' +xhr.status);
				 All.set_enable_button();
            }
<<<<<<< HEAD
        }); 
    },
    
=======
        });
    },

>>>>>>> devel
	convertDateIndo: function(tgl, to, delimiter)
     {
        var date1 = tgl.split("/");
        var tanggal = "";
        if(to == "ymd") {
            //tanggal = date1[2] + delimiter."".date1[1]."".delimiter.date1[0];
            tanggal = tanggal.concat(date1[2], delimiter, date1[1], delimiter, date1[0]);
        } else if (to == "mdy") {
            //tanggal = date1[1]."".delimiter."".date1[0]."".delimiter.date1[2];
            tanggal = tanggal.concat(date1[1], delimiter, date1[0], delimiter, date1[2]);
        }
        return tanggal;
     },
<<<<<<< HEAD
     
     dinamicTabContentHeight: function(){
   		 $('#contentY > .ui-tabs-panel').css('max-height',$(window).height() - 115);
   	 },
   	 
=======

     dinamicTabContentHeight: function(){
   		 $('#contentY > .ui-tabs-panel').css('max-height',$(window).height() - 115);
   	 },

>>>>>>> devel
   	 checkMultipleCheckbox : function(namex) {
   	 	var atLeastOneIsChecked = $(All.get_active_tab() + "input[name='"+namex+"']:checkbox:checked").length;
		if(atLeastOneIsChecked  < 1) {
			alert("Please select at least one checkbox..");
			return false;
<<<<<<< HEAD
		} 
   	 },
     
=======
		}
   	 },

>>>>>>> devel
     getFullNameByID : function(nilai, urlX, setValue) {
     	if(nilai === "") {
     		alert("Tidak boleh kosong..")
     	} else {
	        All.set_disable_button();
			$.ajax({
	            url: All.get_url(urlX) + "/" +nilai,
	            type: 'GET',
				dataType: 'json',
	            success:
	            function(data){
<<<<<<< HEAD
	            	
=======

>>>>>>> devel
	                All.set_enable_button();
	                if(data.response == "true") {
	                	$(All.get_active_tab() + setValue).val(data.arrayData[0].fullnm);
					} else {
						alert("Data "+nilai+ " not found");
						$(All.get_active_tab() + setValue).val(null);
					}
	            },
	            error: function (xhr, ajaxOptions, thrownError) {
	                 alert(thrownError + ':' +xhr.status);
					 All.set_enable_button();
	            }
	        });
<<<<<<< HEAD
	    }    
     },
     
=======
	    }
     },

>>>>>>> devel
     relogin : function(idx) {
     	var reloadForm = $(All.get_active_tab() + "#form_reload").val();
     	All.set_disable_button();
		All.get_wait_message();
		$.post(All.get_url("auth/inline") , $(All.get_active_tab() + "#" +idx).serialize(), function(data)
<<<<<<< HEAD
        {  
            All.set_enable_button();
			if(data.response == "true") {
                All.reload_page(reloadForm);
            } 
            else {
                alert(data.message);
            } 
            
        }, "json").fail(function() { 
            alert("Error requesting page"); 
            All.set_enable_button();
        }); 
     }
     
=======
        {
            All.set_enable_button();
			if(data.response == "true") {
                All.reload_page(reloadForm);
            }
            else {
                alert(data.message);
            }

        }, "json").fail(function() {
            alert("Error requesting page");
            All.set_enable_button();
        });
     }

>>>>>>> devel
     /*
     getJsonResponse : function(urlx) {
     	All.set_disable_button();
     	return
			$.ajax({
	            url: All.get_url(urlX),
	            type: 'GET',
				dataType: 'json',
	        });
     }
   	 */
<<<<<<< HEAD
    
} 

=======

}

$(document).ready(function() {
    $('.mainForm').keypress(function(event){
        if (event.keyCode == 10 || event.keyCode == 13)
            event.preventDefault();
    });
});
>>>>>>> devel
