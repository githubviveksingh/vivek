$(document).ready(function(){
    $("#addSupp").validate({
        rules:{
            itemName:{
                required: true
            },
            location:{
                required: true
            },
			statusCode:{
				required: true
			}
        },
        messages:{
            itemName:{
                required: "Please Enter item Name."
            },
			location:{
                required: "Please Select location."
            },
			statusCode:{
				required: "Please Select Status."
			}
        }
    })
})
