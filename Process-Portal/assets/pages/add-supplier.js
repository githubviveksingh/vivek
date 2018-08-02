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
	/*$.validator.addMethod("panCheck", function(value, element){
        var panCard = $("#panCard").val();
           var regex = /[A-Za-z]{5}\d{4}[A-Za-z]{1}/g;
		   if (regex.test(panCard)) {
             return true;			   
        }else{
			  return false;	
		}
    }, "Please Enter Valid PAN No");	*/
    $("#addSupp").validate({
        rules:{
            empname:{
                required: true
            },
            email: { required: true, multiemails: true },
            phone:{
                required: true
            },            
			gst:{
                gstCheck: true
            },
			/*vat:{
				required: true
			},	
	        st:{
				required: true
			},	*/		
            aadhar:{
                required: true
            },
            doj:{
                required: true
            },
            address:{
                required: true
            },
			billCycle:{
				required: true
			}
        },
        messages:{
            empname:{
                required: "Supplier Name is Required."
            },
			email: {
			required: "Supplier Email ID is Required.",
			multiemails: "Enter Valid and Use , For multiple Emails."
			},           
            aadhar:{
                required: "Please Enter Aadhar Card No."
            },
            phone:{
                required: "Please Enter Phone."
            },
			billCycle:{
                required: "Please Select BillCycle."
            },
            address:{
                required: "Please Enter Supplier Address."
            }
        }
    })
})
