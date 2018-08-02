var openedModal = "";
$(document).ready(function(){
    $("#fileTypes").select2({
        minimumResultsForSearch: -1,
        placeholder: "Choose Upload Type",
        width: "100%"
    });

    $("#inMode").select2({
        minimumResultsForSearch: -1,
        placeholder: "Choose IN Mode",
        width: "100%"
    });

    $("#purpose").select2({
        minimumResultsForSearch: -1,
        placeholder: "Choose Purpose",
        width: "100%"
    });
	
	var tableOption = {
         "serverSide": true,
         "processing": true,
		 "columnDefs": [ {
						"targets": [0,8],
						"orderable": false
						} ],
	    "order": [[ 7, "desc" ]],
        "ajax": './ajax/purchase-list-new.php',
        "scrollY":"100%"
    };
	
	if(oSearch != ""){
		tableOption.oSearch = oSearch;
	}
    var table = $('#dataTables').DataTable(tableOption);

    $('.checkStatus').on( 'click', function () {
            var i = $(this).attr('data-column'); 
            var v = $('.checkStatus:checked').map(function() {return this.value;}).get().join(',')
          table.column('5').search(v).draw();
     });

     $('.checkClassifications').on( 'click', function () {
            var i = $(this).attr('data-column'); 
            var v = $('.checkClassifications:checked').map(function() {return this.value;}).get().join(',')
          table.column('2').search(v).draw();
     }); 
    $("#deliveryDate").datepicker({
       format: "yyyy-mm-dd",
       autoclose: true,
       startDate: "dateToday"
    });

    $("#dDate").datepicker({
       format: "yyyy-mm-dd",
       autoclose: true,
       todayHighlight: true,
       endDate: "dateToday"
    });

    $("#DeliveryLocation").select2({
        placeholder: "Choose Delivery Location",
        width: "100%",
    })

    $(".modal_yes").click(function(){
        $(".model-body-h").html("Updating Status. Please do not close this window.");

        var purchaseID = $("#purchase_ID").val();
        var purchaseStatus = $("#purchase_status").val();
        var currentStatus = $("#current_status_other").val();
        $.ajax({
            type:"post",
            data: {"purchaseID": purchaseID, "purchaseStatus": purchaseStatus, "currentStatus":currentStatus},
            url: "ajax/purchase_status.php",
            success: function(data){
                var jsonData = $.parseJSON(data);
                $(openedModal).modal('hide');
                if(jsonData.success == ""){
                    alert("Could not updated. Please try again later");
                }else{
                    alert("Purchase Updated Successfully");
                    location.reload(true);
                }
            }

        })
    })

    $("#dataTables").on("click", ".actionStatus", function(){
        var dataID = $(this).attr("data-id");
        openedModal = "#STATUS_"+dataID;
        var headerID = "#STATUS_H_"+dataID;
        var currentStatus = $(this).attr("current-status");
        if($(openedModal).length > 0){
            $(headerID).html($(this).attr("data-po"));
			$("#dChallan").val($(this).attr("dchallan"));
            $("#purchaseIdentifier_"+dataID).val($(this).attr("purchase-id"));
            $("#purchaseStatus_"+dataID).val(dataID);
            $("#current_status_"+dataID).val(currentStatus);
            $(openedModal).modal({
                backdrop: 'static',
                keyboard: false
            });
            $(openedModal).modal('show');
        }else{
            var dataID = $(this).attr("data-id");
            var purchaseID = $(this).attr("purchase-id");
            $("#purchase_ID").val(purchaseID);
            $("#purchase_status").val(dataID);
            $("#current_status_other").val(currentStatus);
            openedModal = "#OTHER_STATUS";
            var headerID = "#OTHER_STATUS_H";
            $(headerID).html($(this).attr("data-po"));
            $(".model-body-h").html("Do You Really Want to Change The Status of Purchase to <b>"+purchaseStatuses[dataID][0]+"</b>");
            $(openedModal).modal({
                backdrop: 'static',
                keyboard: false
            });
            $(openedModal).modal('show');
        }
    })

    $("body").on('click','.closseModal',function(){		
		//console.log($(openedModal));
        //$(".modal").modal('hide');
		$(openedModal).removeClass("in");
		$(".modal-backdrop").remove();
		$(openedModal).hide();
    });
    $("#STATUS_FORM_413").validate({
        rules:{
            /*dod:{
                required: true
            },
            challan:{
                required: true
            },
            deliveryLocation:{
                required: true
            }*/
        },
        messages:{
            /*dod:{
                required: "Provide Date of Delivery"
            },
            challan:{
                required: "Please provide delivery Challan"
            },
           deliveryLocation:{
                required: "Provide Delivery Location"
            }*/
        },
        submitHandler: function(){

            $.ajax({
                type: "post",
                data: $("#STATUS_FORM_413").serialize(),
                url: "ajax/transit-submit.php",
                success: function(data){
                    location.reload(true);
                }
            })
        }
    });

    $("#STATUS_FORM_414").validate({
        rules:{
            dDate:{
                required: true
            },
            inMode:{
                required: true
            },
            purType:{
                required: true
            },
            purpose:{
                required: true
            }
        },
        messages:{
            dDate:{
                required: "Please choose Date IN"
            },
            inMode:{
                required: "Please choose In Mode"
            },
            purType:{
                required: "Choose Purchase Type"
            },
            purpose:{
                required: "Choose Purchase Purpose"
            }
        }
    })

    $(".submitModelForm").click(function(){
        var formID = "#"+$(this).attr("form-id");
        $(formID).submit();
    })
})


function processData(totalRows){
    var i;
    for(i=2; i<=totalRows;i++){
        console.log("Normal: ");
        console.log(i);
        $.ajax({
            type:"post",
            data:{rowNumber:i},
            url:"ajax/process-row.php",
            success:function(data){
                var perc = 100;
                var jsonData = $.parseJSON(data);
                // console.log(jsonData);
                var end = jsonData.end;
                console.log("Response: ");
                console.log(end);
                var rowNumber = jsonData.rowNumber;
                var error = jsonData.error;
                var rowData = jsonData.rowData;

                if(totalRows != "0"){
                    perc = (rowNumber-1)*100/totalRows;
                    var percent = perc+"%";
                    $("#progress-bar-excel").css("width", percent);
                }

                if(error != ""){
                    console.log(error);
                    console.log(rowData["A"]);
                    var trHtml = "";
                    for(var j = 0;j<selectedArray.length;j++){
                        var charCode = 65+j;
                        var val = rowData[String.fromCharCode(charCode)];
                        if(!val){
                            val = "";
                        }
                        trHtml += "<td>"+val+"</td>";
                    }
                    trHtml = "<tr>"+trHtml+"<td>"+error+"</td></tr>";
                    $(".errorTable tbody").append(trHtml);
                }

                if(end != "1"){

                }else{
                    i = totalRows+1;
                    console.log("Else: ");
                    console.log(i);
                }
            },
            async:false
        });
    }
    $("#myModal").modal("hide");
    if($(".errorTable tbody tr").length == 0){
        alert("Uploaded Successfully");
        location.reload(true);
    }

}
$(document).ready(function(){
    Dropzone.options.myAwesomeDropzone = {
        addRemoveLinks: true,
        init: function() {
            var myDropzone = this;
            document.getElementById("formsubmit").addEventListener("click", function(e) {
                $("#formsubmit").attr("disabled", true);
                if($("#fileTypes").val() != ""){
                    var showme = myDropzone.files;
                    if (showme==""){
                        alert("Please choose file to upload");
                        $("#formsubmit").attr("disabled", false);
                    }
                    var validFlag = $("#STATUS_FORM_414").valid();
                    if(!validFlag){
                        $("#formsubmit").attr("disabled", false);
                        return;
                    }else{
                        $("#apurchaseIdentifier").val($("#purchaseIdentifier_414").val())
                        $("#apurchaseStatus").val($("#purchaseStatus_414").val())
                        $("#aPurpose").val($("#purpose").val());
                        $("#aDatein").val($("#dDate").val());
                        $("#aChallanid").val($("#dChallan").val());
                        $("#aCourierno").val($("#dCourierno").val());
                        $("#aInmode").val($("#inMode").val());
                        $("#aEwayid").val($("#dEway").val());
                        $("#aPurchaseType").val($("#fileTypes").val());
                    }
                    e.preventDefault();
                    e.stopPropagation();
                    myDropzone.processQueue();
                }else{
                    alert("Please Choose Upload Type");
                    $("#formsubmit").attr("disabled", false);
                }
            });

            this.on("sendingmultiple", function() {

            });
            this.on("success", function(files, response) {
                console.log(response);
                var jsonRes = $.parseJSON(response);
                if(jsonRes.error != ""){
                    alert(jsonRes.error);
                    return;
                }
                var totalRows = jsonRes.totalRows;
            //	alert(totalRows);
                if(totalRows > 1){
                    $("#formsubmit").attr("disabled", false);
                    $("#myModal").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('#myModal').modal('show');
                    processData(totalRows);
                }else{
                    alert("No data to process");
                }
            });
            this.on("errormultiple", function(files, response) {

            });
          },
          autoProcessQueue: false,
          uploadMultiple: false,
          parallelUploads: 100,
          maxFiles: 1
      };
})
