<?php
include("includes/check_session.php");
include("includes/connect.php");
include("includes/functions.php");
$core = Core::getInstance();
$stmt=$core->dbh->prepare('select identifier,name,email,address,pan,aadhar,type,locationID,DoJ,DoR,status from tblEmployee');
$stmt->execute();
$columnHeader ='';
$columnHeader = "Identifier"."\t"."Name"."\t"."Email"."\t"."Address"."\t"."Pan"."\t"."Aadhar"."\t"."type"."\t"."locationID"."\t"."DoJ"."\t"."DoR"."\t"."status"."\t";
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
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Employee.xls");
header("Pragma: no-cache");
header("Expires: 0");
echo ucwords($columnHeader)."\n".$setData."\n";
?>