$(document).ready(function(){
    $("#ChnagePass").validate({
        rules:{
            Current_password:{
                required: true
            },
			New_password:{
				required: true,
				minlength : 5
            },
			Re_New_password:{
				minlength : 5,
                equalTo : "#New_password"
			}
        },
        messages:{           
			Current_password:{
                required: "Please Enter Your Current Password."
            },
			New_password:{
                required: "Please Enter New Password."
            },
			Re_New_password:{
                required: "Please Re Type New Password."
            }
        }
    })
})
