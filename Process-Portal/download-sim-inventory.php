<?php
include("includes/check_session.php");
include("includes/connect.php");
include("includes/functions.php");
$core = Core::getInstance();
if($_GET['tag']=="SIM-INVENTORY"){
$stmt=$core->dbh->prepare('select identifier,IMEI,MSISDN,serviceProvider,APNValue,locationID,statusCode,itemID,customerID,BillPlan,hrdDevice,empID from tblSim');
$stmt->execute();
$columnHeader ='';
$columnHeader = "Identifier"."\t"."IMEI"."\t"."MSISDN"."\t"."Service Provider"."\t"."APN Value"."\t"."locationID"."\t"."statusCode"."\t"."itemID"."\t"."customerID"."\t"."BillPlan"."\t"."Hardware Device"."\t"."Assign To Employee"."\t";
}
if($_GET['tag']=="OFFICE-ITEMS")
{
$stmt=$core->dbh->prepare('select identifier,itemName,itemID,description,locationID,statusCode from tblOfficeItem');
$stmt->execute();
$columnHeader ='';
$columnHeader = "Identifier"."\t"."itemName"."\t"."itemID"."\t"."description"."\t"."locationID"."\t"."Status Code"."\t";
}
if($_GET['tag']=="HARDWARE-INVENTORY")
{
$stmt=$core->dbh->prepare('select identifier,IMEI,model,productCat,locationID,statusCode,itemID,supplierID,EMPID from tblHardware');
$stmt->execute();
$columnHeader ='';
$columnHeader = "Identifier"."\t"."IMEI"."\t"."model"."\t"."productCat"."\t"."locationID"."\t"."Status Code"."\t"."itemID"."\t"."supplier"."\t"."EMPID"."\t";
}
if($_GET['tag']=="MACHINE-INVENTORY")
{
$stmt=$core->dbh->prepare('select identifier,name,description,classification,locationID,statusCode,itemID,supplierID,employeeID from tblMachine');
$stmt->execute();
$columnHeader ='';
$columnHeader = "Identifier"."\t"."name"."\t"."description"."\t"."classification"."\t"."locationID"."\t"."Status Code"."\t"."itemID"."\t"."supplier"."\t"."EMPID"."\t";
}
if($_GET['tag']=="Customers")
{
$stmt=$core->dbh->prepare('select identifier,Name,address,GST,PAN,email,phone,billCycle,employeeID from tblCustomer');
$stmt->execute();
$columnHeader ='';
$columnHeader = "Identifier"."\t"."Name"."\t"."address"."\t"."GST"."\t"."PAN"."\t"."email"."\t"."phone"."\t"."billCycle"."\t"."Assign to Emp"."\t";
}
if($_GET['tag']=="PURCHASE")
{
$stmt=$core->dbh->prepare('select identifier,PurchaseID,POReference,PurchaseClassification,Quantity,DateOfDelivery,DeliveryChallan,DeliveryLocation,CollectedBy,Status,uploadPO,createdOn from tblPurchase');
$stmt->execute();
$columnHeader ='';
$columnHeader = "Identifier"."\t"."PurchaseID"."\t"."POReference"."\t"."PurchaseClassification"."\t"."Quantity"."\t"."DateOfDelivery"."\t"."DeliveryChallan"."\t"."DeliveryLocation"."\t"."CollectedBy"."\t"."Status"."\t"."PO PDF"."\t"."Created On"."\t";
}
$setData='';
while($rec =$stmt->FETCH(PDO::FETCH_ASSOC))
{
  $rowData = '';
  foreach($rec as $value)
  {
    $value = '"' . $value . '"' . "\t";
    $rowData .= $value;
  }
  $setData .= trim($rowData)."\n";
}

$filename=$_GET['tag'].date('y-m-d');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$filename.".xls");
header("Pragma: no-cache");
header("Expires: 0");
echo ucwords($columnHeader)."\n".$setData."\n";
?>