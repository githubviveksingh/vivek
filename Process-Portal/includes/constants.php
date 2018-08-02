<?php
//EXCEL FILE FORMATS
$ITEM_SIM = array("MSISDN", "Unique Identifier", "Service Provider Code", "APN Value", "Bill Plan","Location Code","Status Code");
$ITEM_OFFICE = array("Item Name", "Description", "Location Code", "Status Code");
$ITEM_HARDWARE = array("Model Number","Unique Identifier","Product Category","Location Code","Status Code");
$ITEM_MNT = array("Equipment Name","Description","Classification","Employee ID","Location Code","Status Code");
$SUPPLIERS_EXL=array("NAME","EMAIL","PHONE","GST","VAT","ST","PAN","BILLCYCLE","ADDRESS");
$CUSTOMERS_EXL=array("NAME","EMAIL","PHONE","GST","VAT","ST","PAN","BILLCYCLE","ADDRESS","EMPID");
$INVOICE_EXL=array("NAME","InvoiceNo");
//EXCEL FILE FORMATS END

$DS = DIRECTORY_SEPARATOR;
$UPLOADFOLDER = "upload";
$AUDITCODES = array("EMP_ADD"=>"Employee Added", "EMP_UPD"=>"Employee Updated",'SUP_ADD'=>"Supplier Added","SUP_UPD"=>"Supplier Updated", "PUR_ADD"=>"Purchase Added", "PUR_UPD"=>"Purchase Updated", "PUR_STATUS"=>"Purchase Status Changed","SPT_ADD"=>"Support Added","SPT_UPD"=>"Support Updated","CUS_ADD"=>"Customer Added","CUS_UPD"=>"Customer Updated","MAL_LUP"=>"Machine And Tools Inventory Movement","HRD_LUP"=>"Hardware Inventory Movement","OIT_LUP"=>"Office Items Inventory Movement","SIM_LUP"=>"SIM Inventory Movement","SIM_UPD"=>"Sim Inventory Updated","MCN_UPD"=>"Machine Inventory Updated","HRD_UPD"=>"Hardware Inventory Updated","OFC_UPD"=>"OFFICE Inventory Updated","MCN_ACPT"=>"Allotted Machine Accepted","MCN_REJT"=>"Allotted Machine Rejected","HRD_ACPT"=>"Allotted Hardware Accepted","HRD_REJT"=>"Allotted Hardware Rejected","SIM_ACPT"=>"Allotted Sim Accepted","SIM_REJT"=>"Allotted Sim Rejected","OFC_ACPT"=>"Allotted Office Item Accepted","OFC_REJT"=>"Allotted Office Item Rejected","HRD_ANOTH"=>"Allotted Hardware Item To other Employee","OFC_ANOTH"=>"Allotted OFFICE Inventory Item To other Employee","SIM_ANOTH"=>"Allotted Sim To other Employee","MCN_ANOTH"=>"Allotted Machine To other Employee");
//$EMPTYPE = array("A"=>"Admin","MG"=>"Management", "M"=>"Managers","E"=>"Employee", "Ac"=>"Accountant");
$EMPTYPE = array("1"=>"System Admin","2"=>"Admin", "3"=>"Employee","4"=>"Manager", "5"=>"Accounts","6"=>'Technician',"7"=>"HR","8"=>"Support");

$EMPSTATUS = array("A"=>"Active", "R"=>"Resigned");
$SUPPBILLCYL = array("1"=>"Weekly", "2"=>"Monthly","3"=>"Quaterly","4"=>"Annual","5"=>"One Time Payment");
$PURCHASECLASS=array("101"=>array("PUR_SALES","Purchased for Sales"),"102"=>array("PUR_TEST","Purchased for test purpose"),"103"=>array("PUR_SAMPLE","Purchased for Sampling"),"104"=>array("PUR_INTERNAL","Purchased for internal consumption"),"105"=>array("PUR_SERVICE","Purchased Services"),"106"=>array("PUR_ASSET","Purchase Asset"));
$ITEMCLASS=array("201"=>array("ITEM_HARDWARE","Hardware Unit"),"202"=>array("ITEM_SIM","SIM Card"),"203"=>array("ITEM_MNT","Machine and Tools"),"204"=>array("ITEM_OFFICE","Office Accessory"));
$PRODUCTCAT=array("301"=>array("PRD_GPS","GPS product"),"302"=>array("PRD_RFID","RFID product"),"303"=>array("PRD_BLE","BLE product"),"304"=>array("PRD_LORA","Lora product"),"305"=>array("PRD_TELE","Telecom product"),"306"=>array("PRD_MACHINE","Laptop/PC/Desktop or any such hardware"));
$SIMSERPROVIDER = array("601"=>array("SIM_AIRTEL","AIRTEL"), "602"=>array("SIM_IDEA","IDEA"),"603"=>array("SIM_REL","Reliance"),"604"=>array("SIM_BSNL","BSNL"),"605"=>array("SIM_VODAFONE","VODAFONE"));
$MACHINEANDTOOLCLASS=array("501"=>array("MT_MEASUREMENT","Measurement Tool"),"502"=>array("MT_CONSUMABLE","Consumable Tool"),"503"=>array("MT_TOOLS","Hardware Tool"));
$STATUSCODE=array("401"=>array("STATUS_INVENTORY","Present in Inventory"),"402"=>array("STATUS_SOLD","Sold"),"403"=>array("STATUS_LEASED","Rented Out"),"404"=>array("STATUS_DAMAGED","Marked Damaged"),"405"=>array("STATUS_RETURNED","Returned to Supplier"),"406"=>array("STATUS_LOST","Unit is lost"),
            "407"=>array("STATUS_ALOTTED","Alotted to Employee"),"408"=>array("STATUS_DUPLICATE","Mark Duplicate/Delete"),"409"=>array("STATUS_INSTALLED","Installed"));
$PURCHASESTATUS=array("411"=>array("PURCHASE_NEW","New Entry"),"412"=>array("PURCHASE_INT","Initialized Purchase"),"413"=>array("PURCHASE_TRANSIT","In Transit"),"414"=>array("PURCHASE_DELIVERED","Purchase Delivered"), "415"=>array("PURCHASE_CANCEL", "Purchase Cancelled"), "416"=>array("PURCAHSE_CLOSED", "Purchase Closed"));
$SUPPORTCLASSIFICATION=array('1'=>'Hardware','2'=>'Software','3'=>'Client Related','4'=>'SIM','5'=>'Others');
$SUPPORTTYPE=array('1'=>'Installation','2'=>'Maintenance','3'=>'Software','4'=>'Others');
$SOFTWARECATEGORIES=array('1'=>'Troubleshoot','2'=>'Server Update','3'=>'Account Activities','4'=>'Reports');
$OTHERSCATEGORIES=array('1'=>'Custom Reports','2'=>'Enquiry');
//$SUPPORTSTS=array('431'=>'SUPPORT_ASSIGNED',"432"=>"SUPPORT_OPEN","433"=>"SUPPORT_RESOLVED");
$SUPPORTSTS=array("431"=>array("SUPPORT_ASSIGNED","Assigned to"),"432"=>array("SUPPORT_OPEN","Open To Assign"),"433"=>array("SUPPORT_OPEN","Resolved."));
$SALESSTS=array("421"=>array("SALES_LEAD","Sale Lead"),"422"=>array("SALES_QUOTE","Sale Quote"),"423"=>array("SALES_FINAL","Sale Final"),"424"=>array("SALES_SUCCESS","Sale Success"),"425"=>array("SALES_NG","Sale NG"));

$INOUTMODE = array("701"=>"Courier", "702"=>"Hand Deliver", "703"=>"Hand Pick");
$PURPOSE = array("801"=>"Demo", "802"=>"Purchase", "803"=>"Replacement", "804"=>"Sales", "805"=>"Sample", "806"=>"Temporary");

$permissionsArray = permissionsArray();
?>
