$(document).ready(function(){
    $("#addSupp").validate({
        rules:{
            model:{
                required: true
            },
			location:{
                required: true
            },
			statusCode:{
				required: true
			},
			productCat:{
				required: true
			}
        },
        messages:{           
			location:{
                required: "Please Select location."
            },
			model:{
                required: "Please Enter model."
            },
			productCat:{
                required: "Please Enter Category."
            },
			statusCode:{
				required: "Please Select Status."
			}
        }
    })
})
