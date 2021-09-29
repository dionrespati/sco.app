var $ = jQuery;

var paystk = {
	get_image_load2 : function() {
        //set_to = set_to || ".mainForm > .result";
        $(".result").html("<center><img src="+ All.get_base() +"/asset/images/ajax-loader.gif ></center>");
   },
   
	setSelectPay : function() {
	  	 var x = $("#bank").val();
	  	 //alert("isi : " +x);
	  	 var bankDesc = $("#bank option:selected").text();
	  	 var str = x.split("|");
	  	 $("#bankid").val(str[0]);
	  	 $("#bankCode").val(str[1]);
	  	 //$("#bankDesc").val(bankDesc);
	  	 $("#bankDesc").val(str[4]);
	  	 $("#bankDescDetail").val(str[5]);
	  	 $("#charge_connectivity").val(str[2]);
	  	 $("#charge_admin").val(str[3]);
   },
   
   ajaxFormPost : function(formID, url) {
		$(".btn").attr("disabled", "disabled");
        paystk.get_image_load2();
        $.post(All.get_url(url) , $(" #"+ formID).serialize(), function(data)
        {  
            $(".btn").removeAttr("disabled");
            $(".result").html(null);
            $(".result").append(data);   
        }).fail(function() { 
            $(".btn").removeAttr("disabled");
            $(".btn").removeAttr("disabled");
        });
	},
	
	previewStkPay : function() {
		var x = $(All.get_active_tab() + ' #listTrx input:checked').length;
		if($(All.get_active_tab() + " #bank").val() == '') {
			alert("Metode pembayaran harus di pilih..");
			return false;
		} else if(x == 0) {
			alert("Belum ada SSR yang dipilih.."); 
			return false;
		}  else {
		    return true;
		}
	},
	
	previewPayment : function(formID, url) {
		$(All.get_active_tab() + " .btn").attr("disabled", "disabled");
        paystk.get_image_load2();
        $.post(All.get_url(url) , $(" #"+ formID).serialize(), function(data)
        {  
            $(All.get_active_tab() + " .btn").removeAttr("disabled");
            $(All.get_active_tab() + " .mainForm").hide();
            $(All.get_active_tab() + " .nextForm1").html(null);
            $(All.get_active_tab() + " .nextForm1").html(data);   
        }).fail(function() { 
            $(All.get_active_tab() + " .btn").removeAttr("disabled");
            $(All.get_active_tab() + " .btn").removeAttr("disabled");
        });
	},
	
	addSelectedPrice : function(param) {
		
	},
	
	checkUncheckAll: function(theElement) 
    {
        var tot = 0;
    	/*var theForm = theElement.form;
        if(theForm.length == 0) {
        	alert("kosong");
        }
    	for(z=0; z<theForm.length;z++)
    	{
            //tot += parseInt(theForm[z].value);
    		if(theForm[z].type == 'checkbox' && theForm[z].name != 'checkall')
    		{
    			theForm[z].checked = theElement.checked;
    		}
     
    	}*/
    	
    	
    	
    	console.log();
    	
    	if(theElement.checked) {
    	   $(".pilihan").each(function(){
		      $(".pilihan").prop( "checked", true );
		      tot += parseInt($(this).attr("rel"));
		   });	
		   $("#selected_pay").val(null);
    	   $("#selected_pay").val(All.num(tot));
    	} else {
    	   $(".pilihan").prop( "checked", false );	
    	   $("#selected_pay").val(null);
    	   $("#selected_pay").val(All.num("0"));	
    	}
    	
    	/*$(".pays").each(function () {
            tot += parseInt($(this).val());          
        });
        
    	$("#selected_pay").val(null);
    	$("#selected_pay").val(All.num(tot));*/
    },
    
    countSelectedPrice : function() {
    	var tot = 0;
    	$(".pilihan:checked").each(function(){
		      tot += parseInt($(this).attr("rel"));
		});
		
    	$("#selected_pay").val(null);
    	$("#selected_pay").val(All.num(tot));

    },
    
    ajaxDetailNextForm : function(url, setTo) {
    	$(".btn").attr("disabled", "disabled");
		$.ajax({
            url: All.get_url(url),
            type: 'GET',
            success:
            function(data){
            	$(".btn").removeAttr("disabled");
            	if(setTo == "nextForm1") {
	            	$(".mainForm").hide();
	                $(".nextForm1").html(null);
	                $(".nextForm1").html(data);  
               } else if(setTo == "nextForm2") {
               	    $(".nextForm1").hide();
	                $(".nextForm2").html(null);
	                $(".nextForm2").html(data);
               }
            },
		    error: function(jqXHR, textStatus, errorThrown) {
		       $(".btn").removeAttr("disabled");
		    } 
        });
   },
   
   back_to_form : function(backTo, clearDiv) {
   	   $(backTo).show();
       $(clearDiv).html(null);
   }
}