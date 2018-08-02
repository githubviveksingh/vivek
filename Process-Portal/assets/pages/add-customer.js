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
            cusname:{
                required: true
            },
            email:{
                required: true,
                multiemails: true 
            },          
         /*    pan:{
                panCheck: true
            },    
            GST:{
                gstCheck: true
            }, */
            /* VAT:{
                required: true
            }, 
             ST:{
                required: true
            },   */      
            phone:{
                required: true,
                minlength:10
            },
            address:{
                required: true
            },
			billCycle:{
				required: true
			},
			EMPID:{
				required: true
			}
        },
        messages:{
            cusname:{
                required: "Customer Name is Required."
            },
            email:{
                required: "Customer Email ID is Required.",
				multiemails: "Enter Valid and Use , For multiple Emails."
            },
           /*  pan:{
                required: "Please Enter PAN No."
            },  
            GST:{
                required: "Please Enter GST No."
            }, */
           /*  VAT:{
                required: "Please Enter VAT No."
            }, 
             ST:{
                required: "Please Enter ST No."
            },   */        
            phone:{
                required: "Please Enter Phone.",
				minlength:"Please Enter Atleast One Contact No."
            },
            address:{
                required: "Please Enter Customer Address."
            },
			billCycle:{
				required: "Please Select billCycle."
			},
			EMPID:{
				required: "Please Select Account Manager."
			}
        }
    })
})
