$(document).ready(function(){
        $("#addEmp").validate({
        rules:{
            "devicetype[]": "required",
			"itemID[]": "required"
        },
        messages:{
          "devicetype[]": "Please select device type",
		  "itemID[]": "Please select item"
        }
    })
})
