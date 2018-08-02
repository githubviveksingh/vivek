function EMP_ADD(d){
    $("#modalTitle").html("Employee Added");
    var jsonDecode = $.parseJSON(d.data);
    var html = "<thead><tr><th>Column</th><th>Value</th></tr></thead> \
                <tbody> \
                    <tr><td>Name</td><td>"+jsonDecode.name+"</td></tr> \
                    <tr><td>PAN</td><td>"+jsonDecode.pan+"</td></tr> \
                    <tr><td>Aadhar</td><td>"+jsonDecode.aadhar+"</td></tr> \
                    <tr><td>Location</td><td>"+jsonDecode.locationID+"</td></tr> \
                    <tr><td>Date of Joining</td><td>"+jsonDecode.DoJ+"</td></tr> \
                </tbody>";

    $("#modalTable").html(html);

}
function EMP_UPD(d){
    $("#modalTitle").html("Employee Updated");
    var jsonDecode = $.parseJSON(d.data);

    var nameClass = "";
    var panClass = "";
    var aadharClass = "";
    var locClass = "";
    var dojClass = "";
    if(jsonDecode.old_values.name != jsonDecode.new_values.name){
        nameClass = "alert alert-danger";
    }
    if(jsonDecode.old_values.pan != jsonDecode.new_values.pan){
        panClass = "alert alert-danger";
    }
    if(jsonDecode.old_values.aadhar != jsonDecode.new_values.aadhar){
        aadharClass = "alert alert-danger";
    }
    if(jsonDecode.old_values.locationID != jsonDecode.new_values.locationID){
        locClass = "alert alert-danger";
    }
    if(jsonDecode.old_values.DoJ != jsonDecode.new_values.DoJ){
        dojClass = "alert alert-danger";
    }

    var html = '<thead><tr><th>Column</th><th>Previous Value</th><th>New Value</th></tr></thead> \
                <tbody> \
                    <tr class="'+nameClass+'"><td>Name</td><td>'+jsonDecode.old_values.name+'</td><td>'+jsonDecode.new_values.name+'</td></tr> \
                    <tr class="'+panClass+'"><td>PAN</td><td>'+jsonDecode.old_values.pan+'</td><td>'+jsonDecode.new_values.pan+'</td></tr> \
                    <tr class="'+aadharClass+'"><td>Aadhar</td><td>'+jsonDecode.old_values.aadhar+'</td><td>'+jsonDecode.new_values.aadhar+'</td></tr> \
                    <tr class="'+locClass+'"><td>Location</td><td>'+jsonDecode.old_values.locationID+'</td><td>'+jsonDecode.new_values.locationID+'</td></tr> \
                    <tr class="'+dojClass+'"><td>Date of Joining</td><td>'+jsonDecode.old_values.DoJ+'</td><td>'+jsonDecode.new_values.DoJ+'</td></tr> \
                </tbody>';

    $("#modalTable").html(html);
}

function PUR_STATUS(d){
    $("#modalTitle").html("Purchase Status Changed");
    var jsonDecode = $.parseJSON(d.data);
    
    var html = "<thead><tr><th>Column</th><th>Value</th></tr></thead> \
                <tbody> \
                    <tr><td>Status</td><td>Previous Status: "+jsonDecode.Previous_Status+"<br/>Current Status: "+jsonDecode.Current_Status+"</td></tr> \
                    [OTHERDATA] \
                </tbody>";

    var otherData = "";
    if(typeof jsonDecode.Other_Data != 'undefined'){
        otherData = "<tr><td>Date of Delivery</td><td>"+jsonDecode.Other_Data.DateOfDelivery+"</td></tr> \
                    <tr><td>Delivery Challan</td><td>"+jsonDecode.Other_Data.DeliveryChallan+"</td></tr> \
                    <tr><td>Delivery Location</td><td>"+jsonDecode.Other_Data.DeliveryLocation+"</td></tr>";
    }

    html = html.replace("[OTHERDATA]", otherData);
    $("#modalTable").html(html);

}

function PUR_UPD(d){
    $("#modalTitle").html("Purchase Updated");
    var jsonDecode = $.parseJSON(d.data);
    var t1 = "", t2 = "", t3 = "", t4 = "", t5 = "";
    if(jsonDecode.old_values.PurchaseID != jsonDecode.new_values.PurchaseID){
        t1 = "alert alert-danger";
    }
    if(jsonDecode.old_values.POReference != jsonDecode.new_values.POReference){
        t2 = "alert alert-danger";
    }
    if(jsonDecode.old_values.PurchaseClassification != jsonDecode.new_values.PurchaseClassification){
        t3 = "alert alert-danger";
    }
    if(jsonDecode.old_values.Quantity != jsonDecode.new_values.Quantity){
        t4 = "alert alert-danger";
    }
    if(jsonDecode.old_values.SupplierID != jsonDecode.new_values.SupplierID){
        t5 = "alert alert-danger";
    }

    var html = '<thead><tr><th>Column</th><th>Previous Value</th><th>New Value</th></tr></thead> \
                <tbody> \
                    <tr class="'+t1+'"><td>Purchase ID</td><td>'+jsonDecode.old_values.PurchaseID+'</td><td>'+jsonDecode.new_values.PurchaseID+'</td></tr> \
                    <tr class="'+t2+'"><td>PO Reference</td><td>'+jsonDecode.old_values.POReference+'</td><td>'+jsonDecode.new_values.POReference+'</td></tr> \
                    <tr class="'+t3+'"><td>Classification</td><td>'+jsonDecode.old_values.PurchaseClassification+'</td><td>'+jsonDecode.new_values.PurchaseClassification+'</td></tr> \
                    <tr class="'+t4+'"><td>Quantity</td><td>'+jsonDecode.old_values.Quantity+'</td><td>'+jsonDecode.new_values.Quantity+'</td></tr> \
                    <tr class="'+t5+'"><td>Supplier ID</td><td>'+jsonDecode.old_values.SupplierID+'</td><td>'+jsonDecode.new_values.SupplierID+'</td></tr> \
                </tbody>';

    $("#modalTable").html(html);
}

function PUR_ADD(d){
    $("#modalTitle").html("Purchase Added");
    var jsonDecode = $.parseJSON(d.data);
    var html = "<thead><tr><th>Column</th><th>Value</th></tr></thead> \
                <tbody> \
                    <tr><td>PurchaseID</td><td>"+jsonDecode.PurchaseID+"</td></tr> \
                    <tr><td>PO Reference</td><td>"+jsonDecode.POReference+"</td></tr> \
                    <tr><td>Purchase Classification</td><td>"+jsonDecode.PurchaseClassification+"</td></tr> \
                    <tr><td>Quantity</td><td>"+jsonDecode.Quantity+"</td></tr> \
                    <tr><td>Supplier ID</td><td>"+jsonDecode.SupplierID+"</td></tr> \
                    <tr><td>Status</td><td>"+jsonDecode.status+"</td></tr> \
                </tbody>";

    $("#modalTable").html(html);
}

function SUP_ADD(d){
    $("#modalTitle").html("Supplier Added");
    var jsonDecode = $.parseJSON(d.data);
    var html = "<thead><tr><th>Column</th><th>Value</th></tr></thead> \
                <tbody> \
					<tr><td>Name</td><td>"+jsonDecode.name+"</td></tr> \
					<tr><td>Email</td><td>"+jsonDecode.email+"</td></tr> \
					<tr><td>Phone</td><td>"+jsonDecode.phone+"</td></tr> \
					<tr><td>PAN</td><td>"+jsonDecode.pan+"</td></tr> \
					<tr><td>GST</td><td>"+jsonDecode.gst+"</td></tr> \
					<tr><td>BillCycle</td><td>"+jsonDecode.billCycle+"</td></tr> \
					<tr><td>Address</td><td>"+jsonDecode.address+"</td></tr> \
                </tbody>";

    $("#modalTable").html(html);

}
function SUP_UPD(d){
    $("#modalTitle").html("Supplier Updated");
    var jsonDecode = $.parseJSON(d.data);

    var nameClass = "";
    var panClass = "";
    var vat = "";
    var email = "";
    var phone = "";
	var gst = "";
	var st = "";
	var billCycle = "";
	var address = "";
    if(jsonDecode.old_values.name != jsonDecode.new_values.name){
        nameClass = "alert alert-danger";
    }
    if(jsonDecode.old_values.PAN != jsonDecode.new_values.pan){
        panClass = "alert alert-danger";
    }
    if(jsonDecode.old_values.email != jsonDecode.new_values.email){
        email = "alert alert-danger";
    }
    if(jsonDecode.old_values.phone != jsonDecode.new_values.phone){
        phone = "alert alert-danger";
    }
	if(jsonDecode.old_values.GST != jsonDecode.new_values.gst){
        gst = "alert alert-danger";
    }
    if(jsonDecode.old_values.billCycle != jsonDecode.new_values.billCycle){
        gst = "alert alert-danger";
    }
	if(jsonDecode.old_values.address != jsonDecode.new_values.address){
        address = "alert alert-danger";
    }
    var html = '<thead><tr><th>Column</th><th>Previous Value</th><th>New Value</th></tr></thead> \
                <tbody> \
                    <tr class="'+nameClass+'"><td>Name</td><td>'+jsonDecode.old_values.name+'</td><td>'+jsonDecode.new_values.name+'</td></tr> \
                    <tr class="'+panClass+'"><td>PAN</td><td>'+jsonDecode.old_values.PAN+'</td><td>'+jsonDecode.new_values.pan+'</td></tr> \
                    <tr class="'+email+'"><td>Email</td><td>'+jsonDecode.old_values.email+'</td><td>'+jsonDecode.new_values.email+'</td></tr> \
                    <tr class="'+phone+'"><td>Phone</td><td>'+jsonDecode.old_values.phone+'</td><td>'+jsonDecode.new_values.phone+'</td></tr> \
					<tr class="'+gst+'"><td>GST</td><td>'+jsonDecode.old_values.GST+'</td><td>'+jsonDecode.new_values.gst+'</td></tr> \
					<tr class="'+billCycle+'"><td>BillCycle</td><td>'+jsonDecode.old_values.billCycle+'</td><td>'+jsonDecode.new_values.billCycle+'</td></tr> \
					<tr class="'+address+'"><td>address</td><td>'+jsonDecode.old_values.address+'</td><td>'+jsonDecode.new_values.address+'</td></tr> \
                </tbody>';

    $("#modalTable").html(html);
}
function SPT_ADD(d){
	$("#modalTitle").html("Support Added");
    var jsonDecode = $.parseJSON(d.data);
    var html = "<thead><tr><th>Column</th><th>Value</th></tr></thead> \
                <tbody> \
                    <tr><td>DateofReport</td><td>"+jsonDecode.DateofReport+"</td></tr> \
                    <tr><td>Classification</td><td>"+jsonDecode.Classification+"</td></tr> \
                    <tr><td>ServiceReportNo</td><td>"+jsonDecode.ServiceReportNo+"</td></tr> \
					<tr><td>CustomerID</td><td>"+jsonDecode.CustomerID+"</td></tr> \
					<tr><td>Status</td><td>"+jsonDecode.Status+"</td></tr> \
                </tbody>";
    $("#modalTable").html(html);
}

function SPT_UPD(d){
    $("#modalTitle").html("Support Updated");
    var jsonDecode = $.parseJSON(d.data);

    var itemID = "";
    var itemCategory = "";
    var DateofReport = "";
    var Classification = "";
    var ServiceReportNo = "";
	var CustomerID = "";
	var Status = "";
    var technicianID = "";
    var IssueDetails = "";
    var IssueIdentification = "";
    var IssueResolution = "";
	var SiteVisit = "";
	var DateOfResolution = "";
    if(jsonDecode.old_values.itemID != jsonDecode.new_values.itemID){
        itemID = "alert alert-danger";
    }
    if(jsonDecode.old_values.itemCategory != jsonDecode.new_values.itemCategory){
        itemCategory = "alert alert-danger";
    }
    if(jsonDecode.old_values.DateofReport != jsonDecode.new_values.DateofReport){
        vat = "alert alert-danger";
    }
    if(jsonDecode.old_values.Classification != jsonDecode.new_values.Classification){
        Classification = "alert alert-danger";
    }
    if(jsonDecode.old_values.ServiceReportNo != jsonDecode.new_values.ServiceReportNo){
        ServiceReportNo = "alert alert-danger";
    }
	if(jsonDecode.old_values.CustomerID != jsonDecode.new_values.CustomerID){
        CustomerID = "alert alert-danger";
    }	
    if(jsonDecode.old_values.technicianID != jsonDecode.new_values.technicianID){
        gst = "alert alert-danger";
    }
	if(jsonDecode.old_values.DateOfResolution != jsonDecode.new_values.DateOfResolution){
        address = "alert alert-danger";
    }
    if(jsonDecode.old_values.additionalInfo != jsonDecode.new_values.additionalInfo){
        address = "alert alert-danger";
    }
    if(jsonDecode.old_values.identificationInfo != jsonDecode.new_values.identificationInfo){
        address = "alert alert-danger";
    }
    if(jsonDecode.old_values.closingNote != jsonDecode.new_values.closingNote){
        address = "alert alert-danger";
    }
    if(jsonDecode.old_values.noOfVisit != jsonDecode.new_values.noOfVisit){
        address = "alert alert-danger";
    }
    var html = '<thead><tr><th>Column</th><th>Previous Value</th><th>New Value</th></tr></thead> \
                <tbody> \
                    <tr class="'+itemID+'"><td>itemID</td><td>'+jsonDecode.old_values.itemID+'</td><td>'+jsonDecode.new_values.itemID+'</td></tr> \
                    <tr class="'+itemCategory+'"><td>PAN</td><td>'+jsonDecode.old_values.itemCategory+'</td><td>'+jsonDecode.new_values.itemCategory+'</td></tr> \
                    <tr class="'+DateofReport+'"><td>DateofReport</td><td>'+jsonDecode.old_values.DateofReport+'</td><td>'+jsonDecode.new_values.DateofReport+'</td></tr> \
                    <tr class="'+Classification+'"><td>Classification</td><td>'+jsonDecode.old_values.Classification+'</td><td>'+jsonDecode.new_values.Classification+'</td></tr> \
                    <tr class="'+ServiceReportNo+'"><td>ServiceReportNo</td><td>'+jsonDecode.old_values.ServiceReportNo+'</td><td>'+jsonDecode.new_values.ServiceReportNo+'</td></tr> \
					<tr class="'+CustomerID+'"><td>CustomerID</td><td>'+jsonDecode.old_values.CustomerID+'</td><td>'+jsonDecode.new_values.CustomerID+'</td></tr> \
					<tr class="'+Status+'"><td>Status</td><td>'+jsonDecode.old_values.Status+'</td><td>'+jsonDecode.new_values.Status+'</td></tr> \
					<tr class="'+technicianID+'"><td>TechnicianID</td><td>'+jsonDecode.old_values.technicianID+'</td><td>'+jsonDecode.new_values.technicianID+'</td></tr> \
					<tr class="'+DateOfResolution+'"><td>DateOfResolution</td><td>'+jsonDecode.old_values.DateOfResolution+'</td><td>'+jsonDecode.new_values.DateOfResolution+'</td></tr> \
                    <tr class="'+IssueDetails+'"><td>IssueDetails</td><td>'+jsonDecode.old_values.additionalInfo+'</td><td>'+jsonDecode.new_values.additionalInfo+'</td></tr> \
                    <tr class="'+IssueIdentification+'"><td>IssueIdentification</td><td>'+jsonDecode.old_values.identificationInfo+'</td><td>'+jsonDecode.new_values.identificationInfo+'</td></tr> \
                    <tr class="'+IssueResolution+'"><td>IssueResolution</td><td>'+jsonDecode.old_values.closingNote+'</td><td>'+jsonDecode.new_values.closingNote+'</td></tr> \
                    <tr class="'+SiteVisit+'"><td>SiteVisit</td><td>'+jsonDecode.old_values.noOfVisit+'</td><td>'+jsonDecode.new_values.noOfVisit+'</td></tr> \
                </tbody>';

    $("#modalTable").html(html);
}
function CUS_ADD(d){
	$("#modalTitle").html("Customer Added");
    var jsonDecode = $.parseJSON(d.data);
    var html = "<thead><tr><th>Column</th><th>Value</th></tr></thead> \
                <tbody> \
					<tr><td>Name</td><td>"+jsonDecode.Name+"</td></tr> \
					<tr><td>email</td><td>"+jsonDecode.email+"</td></tr> \
					<tr><td>phone</td><td>"+jsonDecode.phone+"</td></tr> \
					<tr><td>PAN</td><td>"+jsonDecode.PAN+"</td></tr> \
					<tr><td>GST</td><td>"+jsonDecode.GST+"</td></tr> \
					<tr><td>BillCycle</td><td>"+jsonDecode.billCycle+"</td></tr> \
					<tr><td>Address</td><td>"+jsonDecode.address+"</td></tr> \
					<tr><td>AccountManager</td><td>"+jsonDecode.employeeID+"</td></tr> \
                </tbody>";

    $("#modalTable").html(html);
}
function CUS_UPD(d){
    $("#modalTitle").html("Customer Updated");
    var jsonDecode = $.parseJSON(d.data);

    var nameClass = "";
    var panClass = "";
    var vat = "";
    var email = "";
    var phone = "";
	var gst = "";
	var st = "";
	var billCycle = "";
	var address = "";
	var employeeID="";
    if(jsonDecode.old_values.Name != jsonDecode.new_values.Name){
        nameClass = "alert alert-danger";
    }
    if(jsonDecode.old_values.PAN != jsonDecode.new_values.PAN){
        panClass = "alert alert-danger";
    }
    if(jsonDecode.old_values.email != jsonDecode.new_values.email){
        email = "alert alert-danger";
    }
    if(jsonDecode.old_values.phone != jsonDecode.new_values.phone){
        phone = "alert alert-danger";
    }
	if(jsonDecode.old_values.GST != jsonDecode.new_values.GST){
        gst = "alert alert-danger";
    }	
    if(jsonDecode.old_values.billCycle != jsonDecode.new_values.billCycle){
        gst = "alert alert-danger";
    }
	if(jsonDecode.old_values.address != jsonDecode.new_values.address){
        address = "alert alert-danger";
    }
	if(jsonDecode.old_values.employeeID != jsonDecode.new_values.employeeID){
        employeeID = "alert alert-danger";
    }
    var html = '<thead><tr><th>Column</th><th>Previous Value</th><th>New Value</th></tr></thead> \
                <tbody> \
                    <tr class="'+nameClass+'"><td>Name</td><td>'+jsonDecode.old_values.Name+'</td><td>'+jsonDecode.new_values.Name+'</td></tr> \
                    <tr class="'+panClass+'"><td>PAN</td><td>'+jsonDecode.old_values.PAN+'</td><td>'+jsonDecode.new_values.PAN+'</td></tr> \
                    <tr class="'+email+'"><td>Email</td><td>'+jsonDecode.old_values.email+'</td><td>'+jsonDecode.new_values.email+'</td></tr> \
                    <tr class="'+phone+'"><td>Phone</td><td>'+jsonDecode.old_values.phone+'</td><td>'+jsonDecode.new_values.phone+'</td></tr> \
					<tr class="'+gst+'"><td>GST</td><td>'+jsonDecode.old_values.GST+'</td><td>'+jsonDecode.new_values.GST+'</td></tr> \
					<tr class="'+billCycle+'"><td>BillCycle</td><td>'+jsonDecode.old_values.billCycle+'</td><td>'+jsonDecode.new_values.billCycle+'</td></tr> \
					<tr class="'+address+'"><td>address</td><td>'+jsonDecode.old_values.address+'</td><td>'+jsonDecode.new_values.address+'</td></tr> \
					<tr class="'+employeeID+'"><td>employeeID</td><td>'+jsonDecode.old_values.employeeID+'</td><td>'+jsonDecode.new_values.employeeID+'</td></tr> \
                </tbody>';

    $("#modalTable").html(html);
}
function MAL_LUP(d){
    $("#modalTitle").html("Machine And Tools Inventory Movement.");
    var jsonDecode = $.parseJSON(d.data);
    
    var html = "<thead><tr><th>Column</th><th>Value</th></tr></thead> \
                <tbody> \
                    <tr><td>Location</td><td>Previous Location: "+jsonDecode.Previous_Location+"<br/>Current Location: "+jsonDecode.Current_Location+"</td></tr> \
					<tr><td>Remarks</td><td>"+jsonDecode.Current_Remarks+"</td></tr> \
                    [OTHERDATA] \
                </tbody>";
    
    $("#modalTable").html(html);

}
function HRD_LUP(d){
    $("#modalTitle").html("Hardware Inventory Movement.");
    var jsonDecode = $.parseJSON(d.data);    
    var html = "<thead><tr><th>Column</th><th>Value</th></tr></thead> \
                <tbody> \
                    <tr><td>Location</td><td>Previous Location: "+jsonDecode.Previous_Location+"<br/>Current Location: "+jsonDecode.Current_Location+"</td></tr> \
					<tr><td>Remarks</td><td>"+jsonDecode.Current_Remarks+"</td></tr> \
                    [OTHERDATA] \
                </tbody>";    
    $("#modalTable").html(html);
}
function OIT_LUP(d){
    $("#modalTitle").html("Office Items Inventory Movement.");
    var jsonDecode = $.parseJSON(d.data);    
    var html = "<thead><tr><th>Column</th><th>Value</th></tr></thead> \
                <tbody> \
                    <tr><td>Location</td><td>Previous Location: "+jsonDecode.Previous_Location+"<br/>Current Location: "+jsonDecode.Current_Location+"</td></tr> \
					<tr><td>Remarks</td><td>"+jsonDecode.Current_Remarks+"</td></tr> \
                    [OTHERDATA] \
                </tbody>";    
    $("#modalTable").html(html);
}
function SIM_LUP(d){
    $("#modalTitle").html("SIM Inventory Movement.");
    var jsonDecode = $.parseJSON(d.data);    
    var html = "<thead><tr><th>Column</th><th>Value</th></tr></thead> \
                <tbody> \
                    <tr><td>Location</td><td>Previous Location: "+jsonDecode.Previous_Location+"<br/>Current Location: "+jsonDecode.Current_Location+"</td></tr> \
					<tr><td>Remarks</td><td>"+jsonDecode.Current_Remarks+"</td></tr> \
                    [OTHERDATA] \
                </tbody>";    
    $("#modalTable").html(html);
}

function HRD_UPD(d){
    $("#modalTitle").html("Hardware Inventory Updated");
    var jsonDecode = $.parseJSON(d.data);    
    var html = "<thead><tr><th>Column</th><th>Value</th></tr></thead> \
                <tbody> \
                    <tr><td>IMEI</td><td>"+jsonDecode.new_values.IMEI+"</td></tr> \
                    <tr><td>Model</td><td>"+jsonDecode.new_values.model+"</td></tr> \
                    <tr><td>Product Cat</td><td>"+jsonDecode.new_values.productCat+"</td></tr> \
                    <tr><td>Status Code</td><td>"+jsonDecode.new_values.statusCode+"</td></tr> \
                    <tr><td>Employee ID</td><td>"+jsonDecode.new_values.empID+"</td></tr> \
                    [OTHERDATA] \
                </tbody>";    
    $("#modalTable").html(html);
}

function OFC_UPD(d){
    $("#modalTitle").html("OFFICE Inventory Updated");
    var jsonDecode = $.parseJSON(d.data);    
    var html = "<thead><tr><th>Column</th><th>Value</th></tr></thead> \
                <tbody> \
                    <tr><td>Item Name</td><td>"+jsonDecode.new_values.itemName+"</td></tr> \
                    <tr><td>Description</td><td>"+jsonDecode.new_values.description+"</td></tr> \
                    <tr><td>Status Code</td><td>"+jsonDecode.new_values.statusCode+"</td></tr> \
                    <tr><td>Employee ID</td><td>"+jsonDecode.new_values.EMPID+"</td></tr> \
                    [OTHERDATA] \
                </tbody>";    
    $("#modalTable").html(html);
}

function SIM_UPD(d){
    $("#modalTitle").html("Sim Inventory Updated");
    var jsonDecode = $.parseJSON(d.data);    
    var html = "<thead><tr><th>Column</th><th>Value</th></tr></thead> \
                <tbody> \
                    <tr><td>IMEI</td><td>"+jsonDecode.new_values.IMEI+"</td></tr> \
                    <tr><td>MSISDN</td><td>"+jsonDecode.new_values.MSISDN+"</td></tr> \
                    <tr><td>Service Provider</td><td>"+jsonDecode.new_values.serviceProvider+"</td></tr> \
                    <tr><td>APN Value</td><td>"+jsonDecode.new_values.APNValue+"</td></tr> \
                    <tr><td>Bill Plan</td><td>"+jsonDecode.new_values.BillPlan+"</td></tr> \
                    <tr><td>Status Code</td><td>"+jsonDecode.new_values.statusCode+"</td></tr> \
                    <tr><td>Employee ID</td><td>"+jsonDecode.new_values.empID+"</td></tr> \
                    [OTHERDATA] \
                </tbody>";    
    $("#modalTable").html(html);
}

function MCN_UPD(d){
    $("#modalTitle").html("Machine Inventory Updated");
    var jsonDecode = $.parseJSON(d.data);    
    var html = "<thead><tr><th>Column</th><th>Value</th></tr></thead> \
                <tbody> \
                    <tr><td>Name</td><td>"+jsonDecode.new_values.name+"</td></tr> \
                    <tr><td>Description</td><td>"+jsonDecode.new_values.description+"</td></tr> \
                    <tr><td>Classification</td><td>"+jsonDecode.new_values.classification+"</td></tr> \
                    <tr><td>Status Code</td><td>"+jsonDecode.new_values.statusCode+"</td></tr> \
                    <tr><td>Employee ID</td><td>"+jsonDecode.new_values.employeeID+"</td></tr> \
                    [OTHERDATA] \
                </tbody>";    
    $("#modalTable").html(html);
}

function MCN_ACPT(d){
    $("#modalTitle").html("Allotted Machine Accepted");
    var jsonDecode = $.parseJSON(d.data); 
    var aStatus=jsonDecode.new_values.alote_status;
    var status;
    if(aStatus==1){
        status='Accepted';
    }else{
        status='Rejected';
    }

    var html = "<thead><tr><th>Column</th><th>Value</th></tr></thead> \
                <tbody> \
                    <tr><td>Name</td><td>"+jsonDecode.old_values.name+"</td></tr> \
                    <tr><td>Description</td><td>"+jsonDecode.old_values.description+"</td></tr> \
                    <tr><td>Classification</td><td>"+jsonDecode.old_values.classification+"</td></tr> \
                    <tr><td>Status Code</td><td>"+jsonDecode.old_values.statusCode+"</td></tr> \
                    <tr><td>Employee ID</td><td>"+jsonDecode.old_values.employeeID+"</td></tr> \
                    <tr><td>Allotted Status</td><td>"+status+"</td></tr> \
                    [OTHERDATA] \
                </tbody>";    
    $("#modalTable").html(html);
}

function MCN_REJT(d){
    $("#modalTitle").html("Allotted Machine Rejected");
    var jsonDecode = $.parseJSON(d.data); 
    var aStatus=jsonDecode.new_values.alote_status;
    var status;
    if(aStatus==1){
        status='Accepted';
    }else{
        status='Rejected';
    }

    var html = "<thead><tr><th>Column</th><th>Value</th></tr></thead> \
                <tbody> \
                    <tr><td>Name</td><td>"+jsonDecode.old_values.name+"</td></tr> \
                    <tr><td>Description</td><td>"+jsonDecode.old_values.description+"</td></tr> \
                    <tr><td>Classification</td><td>"+jsonDecode.old_values.classification+"</td></tr> \
                    <tr><td>Status Code</td><td>"+jsonDecode.old_values.statusCode+"</td></tr> \
                    <tr><td>Employee ID</td><td>"+jsonDecode.old_values.employeeID+"</td></tr> \
                    <tr><td>Allotted Status</td><td>"+status+"</td></tr> \
                    [OTHERDATA] \
                </tbody>";    
    $("#modalTable").html(html);
}


function SIM_ACPT(d){
    $("#modalTitle").html("Alloted Sim Accepted");
    var jsonDecode = $.parseJSON(d.data);    
    var aStatus=jsonDecode.new_values.alote_status;
    var status;
    if(aStatus==1){
        status='Accepted';
    }else{
        status='Rejected';
    }
    var html = "<thead><tr><th>Column</th><th>Value</th></tr></thead> \
                <tbody> \
                    <tr><td>IMEI</td><td>"+jsonDecode.old_values.IMEI+"</td></tr> \
                    <tr><td>MSISDN</td><td>"+jsonDecode.old_values.MSISDN+"</td></tr> \
                    <tr><td>Service Provider</td><td>"+jsonDecode.old_values.serviceProvider+"</td></tr> \
                    <tr><td>APN Value</td><td>"+jsonDecode.old_values.APNValue+"</td></tr> \
                    <tr><td>Bill Plan</td><td>"+jsonDecode.old_values.BillPlan+"</td></tr> \
                    <tr><td>Status Code</td><td>"+jsonDecode.old_values.statusCode+"</td></tr> \
                    <tr><td>Employee ID</td><td>"+jsonDecode.old_values.empID+"</td></tr> \
                    <tr><td>Allotted Status</td><td>"+status+"</td></tr> \
                    [OTHERDATA] \
                </tbody>";    
    $("#modalTable").html(html);
}

function SIM_REJT(d){
    $("#modalTitle").html("Alloted Sim Rejected");
    var jsonDecode = $.parseJSON(d.data);    
    var aStatus=jsonDecode.new_values.alote_status;
    var status;
    if(aStatus==1){
        status='Accepted';
    }else{
        status='Rejected';
    }
    var html = "<thead><tr><th>Column</th><th>Value</th></tr></thead> \
                <tbody> \
                    <tr><td>IMEI</td><td>"+jsonDecode.old_values.IMEI+"</td></tr> \
                    <tr><td>MSISDN</td><td>"+jsonDecode.old_values.MSISDN+"</td></tr> \
                    <tr><td>Service Provider</td><td>"+jsonDecode.old_values.serviceProvider+"</td></tr> \
                    <tr><td>APN Value</td><td>"+jsonDecode.old_values.APNValue+"</td></tr> \
                    <tr><td>Bill Plan</td><td>"+jsonDecode.old_values.BillPlan+"</td></tr> \
                    <tr><td>Status Code</td><td>"+jsonDecode.old_values.statusCode+"</td></tr> \
                    <tr><td>Employee ID</td><td>"+jsonDecode.old_values.empID+"</td></tr> \
                    <tr><td>Allotted Status</td><td>"+status+"</td></tr> \
                    [OTHERDATA] \
                </tbody>";    
    $("#modalTable").html(html);
}

function HRD_ACPT(d){
    $("#modalTitle").html("Alloted Hardware Accepted");
    var jsonDecode = $.parseJSON(d.data);    
    var aStatus=jsonDecode.new_values.alote_status;
    var status;
    if(aStatus==1){
        status='Accepted';
    }else{
        status='Rejected';
    }
    var html = "<thead><tr><th>Column</th><th>Value</th></tr></thead> \
                <tbody> \
                    <tr><td>IMEI</td><td>"+jsonDecode.old_values.IMEI+"</td></tr> \
                    <tr><td>Model</td><td>"+jsonDecode.old_values.model+"</td></tr> \
                    <tr><td>Product Cat</td><td>"+jsonDecode.old_values.productCat+"</td></tr> \
                    <tr><td>Status Code</td><td>"+jsonDecode.old_values.statusCode+"</td></tr> \
                    <tr><td>Employee ID</td><td>"+jsonDecode.old_values.EMPID+"</td></tr> \
                    <tr><td>Allotted Status</td><td>"+status+"</td></tr> \
                    [OTHERDATA] \
                </tbody>";    
    $("#modalTable").html(html);
}
function HRD_REJT(d){
    $("#modalTitle").html("Allotted Hardware Rejected");
    var jsonDecode = $.parseJSON(d.data);    
    var aStatus=jsonDecode.new_values.alote_status;
    var status;
    if(aStatus==1){
        status='Accepted';
    }else{
        status='Rejected';
    }
    var html = "<thead><tr><th>Column</th><th>Value</th></tr></thead> \
                <tbody> \
                    <tr><td>IMEI</td><td>"+jsonDecode.old_values.IMEI+"</td></tr> \
                    <tr><td>Model</td><td>"+jsonDecode.old_values.model+"</td></tr> \
                    <tr><td>Product Cat</td><td>"+jsonDecode.old_values.productCat+"</td></tr> \
                    <tr><td>Status Code</td><td>"+jsonDecode.old_values.statusCode+"</td></tr> \
                    <tr><td>Employee ID</td><td>"+jsonDecode.old_values.EMPID+"</td></tr> \
                    <tr><td>Allotted Status</td><td>"+status+"</td></tr> \
                    [OTHERDATA] \
                </tbody>";    
    $("#modalTable").html(html);
}

function OFC_ACPT(d){
    $("#modalTitle").html("Allotted Office Item Accepted");
    var jsonDecode = $.parseJSON(d.data);    
    var aStatus=jsonDecode.new_values.alote_status;
    var status;
    if(aStatus==1){
        status='Accepted';
    }else{
        status='Rejected';
    }
    var html = "<thead><tr><th>Column</th><th>Value</th></tr></thead> \
                <tbody> \
                    <tr><td>Item Name</td><td>"+jsonDecode.old_values.itemName+"</td></tr> \
                    <tr><td>Description</td><td>"+jsonDecode.old_values.description+"</td></tr> \
                    <tr><td>Status Code</td><td>"+jsonDecode.old_values.statusCode+"</td></tr> \
                    <tr><td>Employee ID</td><td>"+jsonDecode.old_values.EMPID+"</td></tr> \
                    <tr><td>Allotted Status</td><td>"+status+"</td></tr> \
                    [OTHERDATA] \
                </tbody>";    
    $("#modalTable").html(html);
}
function OFC_REJT(d){
    $("#modalTitle").html("Allotted Office Item Rejected");
    var jsonDecode = $.parseJSON(d.data);    
    var aStatus=jsonDecode.new_values.alote_status;
    var status;
    if(aStatus==1){
        status='Accepted';
    }else{
        status='Rejected';
    }
    var html = "<thead><tr><th>Column</th><th>Value</th></tr></thead> \
                <tbody> \
                    <tr><td>Item Name</td><td>"+jsonDecode.old_values.itemName+"</td></tr> \
                    <tr><td>Description</td><td>"+jsonDecode.old_values.description+"</td></tr> \
                    <tr><td>Status Code</td><td>"+jsonDecode.old_values.statusCode+"</td></tr> \
                    <tr><td>Employee ID</td><td>"+jsonDecode.old_values.EMPID+"</td></tr> \
                    <tr><td>Allotted Status</td><td>"+status+"</td></tr> \
                    [OTHERDATA] \
                </tbody>";    
    $("#modalTable").html(html);
}

function HRD_ANOTH(d){
    $("#modalTitle").html("Allotted Hardware Item To other Employee");
    var jsonDecode = $.parseJSON(d.data);    
    var html = "<thead><tr><th>Column</th><th>Value</th></tr></thead> \
                <tbody> \
                    <tr><td>IMEI</td><td>"+jsonDecode.new_values.IMEI+"</td></tr> \
                    <tr><td>Model</td><td>"+jsonDecode.new_values.model+"</td></tr> \
                    <tr><td>Product Cat</td><td>"+jsonDecode.new_values.productCat+"</td></tr> \
                    <tr><td>Status Code</td><td>"+jsonDecode.new_values.statusCode+"</td></tr> \
                    <tr><td>Current Employee ID</td><td>"+jsonDecode.new_values.empID+"</td></tr> \
                    <tr><td>Assigned Employee ID</td><td>"+jsonDecode.old_values.EMPID+"</td></tr> \
                    [OTHERDATA] \
                </tbody>";    
    $("#modalTable").html(html);
}

function OFC_ANOTH(d){
    $("#modalTitle").html("Allotted OFFICE Inventory Item To other Employee");
    var jsonDecode = $.parseJSON(d.data);    
    var html = "<thead><tr><th>Column</th><th>Value</th></tr></thead> \
                <tbody> \
                    <tr><td>Item Name</td><td>"+jsonDecode.new_values.itemName+"</td></tr> \
                    <tr><td>Description</td><td>"+jsonDecode.new_values.description+"</td></tr> \
                    <tr><td>Status Code</td><td>"+jsonDecode.new_values.statusCode+"</td></tr> \
                    <tr><td>Current Employee ID</td><td>"+jsonDecode.new_values.EMPID+"</td></tr> \
                    <tr><td>Assigned Employee ID</td><td>"+jsonDecode.old_values.EMPID+"</td></tr> \
                    [OTHERDATA] \
                </tbody>";    
    $("#modalTable").html(html);
}

function SIM_ANOTH(d){
    $("#modalTitle").html("Allotted Sim To other Employee");
    var jsonDecode = $.parseJSON(d.data);    
    var html = "<thead><tr><th>Column</th><th>Value</th></tr></thead> \
                <tbody> \
                    <tr><td>IMEI</td><td>"+jsonDecode.new_values.IMEI+"</td></tr> \
                    <tr><td>MSISDN</td><td>"+jsonDecode.new_values.MSISDN+"</td></tr> \
                    <tr><td>Service Provider</td><td>"+jsonDecode.new_values.serviceProvider+"</td></tr> \
                    <tr><td>APN Value</td><td>"+jsonDecode.new_values.APNValue+"</td></tr> \
                    <tr><td>Bill Plan</td><td>"+jsonDecode.new_values.BillPlan+"</td></tr> \
                    <tr><td>Status Code</td><td>"+jsonDecode.new_values.statusCode+"</td></tr> \
                    <tr><td>Current Employee ID</td><td>"+jsonDecode.new_values.empID+"</td></tr> \
                    <tr><td>Assigned Employee ID</td><td>"+jsonDecode.old_values.empID+"</td></tr> \
                    [OTHERDATA] \
                </tbody>";    
    $("#modalTable").html(html);
}

function MCN_ANOTH(d){
    $("#modalTitle").html("Allotted Machine To other Employee");
    var jsonDecode = $.parseJSON(d.data);    
    var html = "<thead><tr><th>Column</th><th>Value</th></tr></thead> \
                <tbody> \
                    <tr><td>Name</td><td>"+jsonDecode.new_values.name+"</td></tr> \
                    <tr><td>Description</td><td>"+jsonDecode.new_values.description+"</td></tr> \
                    <tr><td>Classification</td><td>"+jsonDecode.new_values.classification+"</td></tr> \
                    <tr><td>Status Code</td><td>"+jsonDecode.new_values.statusCode+"</td></tr> \
                    <tr><td>Current Employee ID</td><td>"+jsonDecode.new_values.employeeID+"</td></tr> \
                    <tr><td>Assigned Employee ID</td><td>"+jsonDecode.old_values.employeeID+"</td></tr> \
                    [OTHERDATA] \
                </tbody>";    
    $("#modalTable").html(html);
}



$("#dataTables tbody").on('click', 'tr.tableRow', function(){
    var row = table.row($(this));
    var data = row.data();
    var func = row.data().auditCode;
    var fn = window[func];
    $('#auditModel').modal('show');

    if(typeof fn === "function"){
        fn(data);
    }
})
