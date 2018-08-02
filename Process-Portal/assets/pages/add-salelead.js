$(document).ready(function(){

        $("#addEmp").validate({
        rules:{
            EMPID:{
                required: true
            },
            Mode:{
                required: true                
            },          
            Name:{
                required: true               
            },    
            Email:{
                required: true,
				email:true				
            },
            Phone:{
                required:true,
				minlength:8,
				maxlength: 13
            },
            itemclassification:{
                     required:true
            },
            Status:{
               required:true
            }
        },
        messages:{
            EMPID:{
                required: "Please Select Employee."
            },
            Mode:{
                required: "Sales Mode is Required."
            },
            Name:{
                required: "Name is Required."
            },  
            Email:{
                required: "Please Enter Email."				
            },
             Phone:{
                required: "Please Enter Phone."
            },
             itemclassification:{
                required: "Select Classification."
            },
            Status:{
                required: "Select Status."
            }
        }
    })
})
