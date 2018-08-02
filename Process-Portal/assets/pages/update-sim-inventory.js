$(document).ready(function(){
 $.validator.addMethod("SupplierCheck", function(value, element){
        var InStatusCode = $("#InStatusCode").val();
        if(InStatusCode == "402" ){		
            return true;
        }else{
            if(value == ""){
                return false;
            }else{
                return true;
            }
        }

    }, "Please Select Supplier.");		
    $("#addSupp").validate({
        rules:{
			IMEI:{		
                required: true				
			},
            MSISDN:{
                required: true,
				minlength:10,
				maxlength:10
            },          
			BillPlan:{
				required: true
			},
			statusCode:{
				required: true
			},
			serviceProvider:{
				required: true
			},
			supplierID:{
				required: true
				//SupplierCheck:true
			},
			hrdDevice:{
				SupplierCheck:true,
				required: true
			},
			EMPID:{
				required:true
			}
        },
        messages:{
			IMEI:{
				required: "Please Enter IMSI."
			},
            MSISDN:{
                required: "Please Enter MSISDN.",				
				minlength:"Please Enter Valid Number",
				maxlength:"Please Enter Valid Number"
            },           
			BillPlan:{
                required: "Please Select BillPlan."
            },
			statusCode:{
				required: "Please Select Status."
			},
			serviceProvider:{
				required: "Please Select Service Provider."
			},
			hrdDevice:{
				required: "Please Select Device."
			},
			EMPID:{
				required: "Please Select Employee."
			}
        }
    })
})
