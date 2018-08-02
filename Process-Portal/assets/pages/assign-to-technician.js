 $(document).ready(function(){
 $("#addEmp").validate({
        rules:{
            address:{
                required: true
            },
            EmployeeID:{
                required: true
            }
        },
        messages:{
            address:{
                required: "Client address Required."
            },
            EmployeeID:{
                required: "Please Select Technician."
            }
        }
    })
})
