$(document).ready(function(){
        $.validator.addMethod("gstCheck", function(value, element){
        var gstNo = $("#gstNo").val();
           var gstinformat = new RegExp('^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$');
		   if (gstinformat.test(gstNo)) {
             return true;			   
        }else{
			  return false;	
		}
    }, "Please Enter Valid GST No");
	$.validator.addMethod("panCheck", function(value, element){
        var panCard = $("#panCard").val();
           var regex = /[A-Za-z]{5}\d{4}[A-Za-z]{1}/g;
		   if (regex.test(panCard)) {
             return true;			   
        }else{
			  return false;	
		}
    }, "Please Enter Valid PAN No");	
	
	jQuery.validator.addMethod("multiemails", function(value, element) {
         if (this.optional(element)) // return true on optional element
             return true;
         var emails = value.split(/[;,]+/); // split element by , and ;
         valid = true;
         for (var i in emails) {
             value = emails[i];
             valid = valid &&
                     jQuery.validator.methods.email.call(this, $.trim(value), element);
         }
         return valid;
     },
   jQuery.validator.messages.multiemails
   );	
        $("#addEmp").validate({
        rules:{
            com_name:{
                required: true
            },
            com_email:{
                required: true,
                multiemails: true 
            },
            com_phone:{
                required: true,
                minlength:10
            },
            com_address:{
                required: true
            },
			com_acno:{
				required: true
			},
			com_bankname:{
				required: true
			},
			com_bank_ifsc:{
				required: true
			}
        },
        messages:{
            com_name:{
                required: "Company name is Required."
            },
            com_email:{
                required: " Email ID is Required.",
				multiemails: "Enter Valid and Use , For multiple Emails."
            },                 
            com_phone:{
                required: "Please Enter Phone.",
				minlength:"Please Enter Atleast One Contact No."
            },
            com_address:{
                required: "Please Enter Customer Address."
            },
			com_acno:{
				required: "Enter account number."
			},
			com_bankname:{
				required: "Bankname Required."
			},
			com_bank_ifsc:{
				required: "bank_ifsc Required."
			}
        }
    })
})
