$(document).ready(function(){

    $.validator.addMethod("drCheck", function(value, element){
        var sStatus = $("#SupportStatus").val();
        if(sStatus == "433"){
            if(value != ""){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }, "Please provide date of resolution");
	$.validator.addMethod("checkTech", function(value, element){
        var cStatus = $("#SupportStatus").val();
        if(cStatus == "431" || cStatus == "433"){
             if(value != ""){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }, "Please Enter Address");
    $("#addEmp").validate({
        rules:{
            Classification:{
                required: true
            },
            DateOfReport:{
                required: true
            },
            ServiceReportNo:{
                required: true
            },
            address:{
                checkTech: true
            },
			EmployeeID:{
				required: true
			},
            Status:{
                required:true
            },
            itemclassification:{
                     required:true
            },
            itemID:{
               required:true
            },
			CustomerID:{
				required:true
			},
            DateOfResolution:{
                drCheck: true
            },
            noOfVisit:{
                required: true,
                regex: /^[0-9]$|^[0-9]+\.?[0-9]+$/,
            }
        },
        messages:{
            Classification:{
                required: "Classification Required."
            },
            DateOfReport:{
                required: "Date Of Report is Required."
            },
            ServiceReportNo:{
                required: "Service Report No is Required."
            },           
            Status:{
                required: "Select Status."
            },
            itemclassification:{
                required: "Select Category."
            },
            itemID:{
                required: "Select Item."
            },
			EmployeeID:{
                required: "Select A Name."
            },
			CustomerID:{
                required: "Select Customer."
            },
            noOfVisit:{
                required: 'Enter no of visit site',
                regex: 'Enter only numeric value'
            }
        }
    })
})
