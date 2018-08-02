$(document).ready(function(){

    $.validator.addMethod("checkPass", function(value, element){
        var empID = $("#empID").val();
        if(empID != "0"){
            return true;
        }else{
            if(value == ""){
                return false;
            }else{
                return true;
            }
        }

    }, "Please Enter Password.");	
	$.validator.addMethod("panCheck", function(value, element){
        var panCard = $("#panCard").val();
           var regex = /[A-Za-z]{5}\d{4}[A-Za-z]{1}/g;
		   if (regex.test(panCard)) {
             return true;			   
        }else{
			  return false;	
		}
    }, "Please Enter Valid PAN No.");
    /*$.validator.addMethod("adharCheker", function(value, element){
        var adharCard = $("#adharCard").val();
           var regex = /^\d{4}\s\d{4}\s\d{4}/g;
		   if (regex.test(adharCard)) {
             return true;			   
        }else{
			  return false;	
		}
    }, "Please Enter Valid Aadhar Card No.");	*/
    $.validator.addMethod("drCheck", function(value, element){
        var empStatus = $("#empStatus").val();
        if(empStatus == "R"){
            if(value != ""){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }, "Please provide date of Resign");
    $("#addEmp").validate({

        rules:{
            empname:{
                required: true
            },
            email:{
                required: true,
                email: true
            },
            password:{
                checkPass: true
            },
            pan:{
                panCheck: true
            },
            aadhar:{
                required: true
            },
            doj:{
                required: true
            },
            address:{
                required: true
            },
			dor:{
                drCheck: true
            },
			emptype:{
				 required: true
			},
			location:{
				required: true
			},
			empstatus:{
				required: true
			}
        },
        messages:{
            empname:{
                required: "Employee Name is Required."
            },
            email:{
                required: "Employee Email ID is Required."
            },            
            doj:{
                required: "Please Enter Date of Joining."
            },
            address:{
                required: "Please Enter Employee Address."
            },
			emptype:{
                required: "Please Select Employee Type."
            },
			location:{
                required: "Please Select location."
            },
			empstatus:{
				required: "Please Select Status."
			},
			aadhar:{
				required: "Please Enter Aadhar No."
			}
        }
    })
})
