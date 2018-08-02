$(document).ready(function(){
	$.validator.addMethod("checkFile", function(value, element){
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

    }, "Please Upload PO PDF File.");
    $("#addPOREF").validate({
        rules:{
            poID:{
                required: true
            },
            uploadLink:{
				checkFile: true
            }           
        },
        messages:{
            poID:{
                required: "Please Enter PO Reference Number."
            },
			uploadLink:{
                required: "Please Upload PO PDF File."
            }
        }
    })
})
