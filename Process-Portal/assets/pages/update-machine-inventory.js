$(document).ready(function(){
    $("#addSupp").validate({
        rules:{
            name:{
                required: true
            },
			location:{
                required: true
            },
			statusCode:{
				required: true
			},
			classification:{
				required: true
			},
			EMPID:{
				required: true
			}
        },
        messages:{           
			location:{
                required: "Please Select location."
            },
			name:{
                required: "Please Enter name."
            },
			classification:{
                required: "Please Enter classification."
            },
			statusCode:{
				required: "Please Select Status."
			},
			EMPID:{
				required: "Please Select Employee."
			}
        }
    })
})
