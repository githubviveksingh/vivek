<?php
//$folder = "track";
include("constants.php");
$core = Core::getInstance();

function addData($table, $contentData){
    global $core;
    $content = array();
    $column = "";
    $columnKey = "";
    foreach($contentData as $key=>$data){
        $content[':'.$key] = $data;
        $column = $column."`".$key."`".",";
        $columnKey = $columnKey.":".$key.",";
    }
    $columnKey = rtrim($columnKey, ",");
    $column = rtrim($column, ",");
    $column = "(".$column.")";
    $columnKey = "(".$columnKey.")";
    $insert = "INSERT INTO ".$table." ".$column." VALUES ".$columnKey;
    $result = $core->dbh->prepare($insert);
    $result->execute($content);
    $lastId = $core->dbh->lastInsertId();
    return $lastId;
}

function updateData($table, $contentData, $column, $id){
    global $core;
    $content = array();
    $columnKey = "";
    foreach($contentData as $key=>$data){
        $content[':'.$key] = $data;
        $columnKey = $columnKey."`".$key."`"."=:".$key.",";
    }
    $columnKey = rtrim($columnKey, ",");
    $update = "UPDATE ".$table." SET ".$columnKey." WHERE ".$column."=:".$column;
    $content[':'.$column] = $id;

    $result = $core->dbh->prepare($update);
    $result->execute($content);
    $lastId = $id;
    return $lastId;
}
function updateDataMulti($table, $contentData, $whereQuery){
    global $core;
    $content = array();
    $wherequery = " WHERE ";
    $whereQ = " WHERE ";
    $columnKey = "";
    $tableData = "";
    foreach($contentData as $key=>$data){
        $content[':'.$key] = $data;
        $columnKey = $columnKey."`".$key."`"."=:".$key.",";
        $tableData = $tableData."`".$key."`"."='".$data."',";
    }

    foreach($whereQuery as $key=>$data){
        $wherequery = $wherequery." ";
        $whereQ = $whereQ." ";
        $count = 0;
        foreach($data as $key1=>$value1){
            if($count == "0"){
                $wherequery = $wherequery. "`".$key1."`=:".$key1;
                $whereQ = $whereQ. "`".$key1."`=".$value1;
            }else{
                $wherequery = $wherequery. " $key `".$key1."`=:".$key1;
                $whereQ = $whereQ. " $key `".$key1."`=".$value1;
            }
            $content[':'.$key1] = $value1;
            $count++;
        }
    }

    $columnKey = rtrim($columnKey, ",");
    $tableData = rtrim($tableData, ",");

    $updateQuery = "UPDATE ".$table." SET ".$tableData.$whereQ;

    $update = "UPDATE ".$table." SET ".$columnKey.$wherequery;
    $result = $core->dbh->prepare($update);
    $result->execute($content);

    $lastId = $id;
    return $lastId;
}

function deleteData($query ,$array = array(), &$count ){

    global $core;
    $select = $query;
    $result = $core->dbh->prepare($select);
    $result->execute($array);
}



function fetchData($query, $array=array(), &$count){
    global $core;
    $select = $query;
    $result = $core->dbh->prepare($select);
    $result->execute($array);
    $count = $result->rowCount();
    $dataRecord = array();
    while($record = $result->fetch(PDO::FETCH_ASSOC)){
        array_push($dataRecord, $record);
    }
    return $dataRecord;
}

function countData($query, &$totalCount, $array = array()){
    global $core;
    $select = $query;

    $result = $core->dbh->prepare($select);
    $result->execute($array);
    $totalCount = $result->rowCount();
}

function getCounter($query, $array = array()){
    global $core;
    $select = $query;
    $counter = 0;
    $count = 0;
    $data = fetchData($query, $array, $count);
    if($count){
        $counter = $data[0]['counter'];
    }
    return $counter;
}

function getAllLocations(){
    $locations = array();
    $query = "SELECT * FROM tblLocation order by name asc";
    $count = 0;
    $locations = fetchData($query, array(), $count);
    return $locations;
}
function getLocationName($location){
    $locations = array();
    $query = "SELECT * FROM tblLocation where identifier='".$location."'";
    $count = 0;
    $locations = fetchData($query, array(), $count);
	return $locations[0][name];
}
function getInvoiceID(){
    $locations = array();
    $query = "SELECT identifier FROM tblinvoice order by identifier desc limit 1";
    $count = 0;
    $locations = fetchData($query, array(), $count);
	$in_id=$locations[0]['identifier']+1;
	if($in_id =="")
	{
		$in_id=1;
	}
	return $in_id;
}
function getMovementStatus($itemid)
{
	$details = array();
    $query = "SELECT * FROM tblInventoryMovement where itemID='".$itemid."' AND status='0' ORDER BY identifier LIMIT 1";
    $count = 0;
    $details = fetchData($query, array(), $count);
	 if($count){
	   return $details;
	 }else{
		 return 0;
	 }
}
function getSuppliers($id = "0"){
    $supplier = array();
    if($id == "0"){
        $query = "SELECT * FROM tblSupplier order by name asc";
        $array = array();
    }else{
        $query = "SELECT * FROM tblSupplier where identifier=:id";
        $array = array(":id"=>$id);
    }
    $count = 0;
    $supplier = fetchData($query, $array, $count);
    return $supplier;
}
function getSuppliersTypes($id = "0"){
    $supplier = array();
    if($id == "0"){
        $query = "SELECT * FROM tblSupplierTypes order by supplier_type asc";
        $array = array();
    }else{
        $query = "SELECT * FROM tblSupplierTypes where identifier=:id";
        $array = array(":id"=>$id);
    }
    $count = 0;
    $supplier = fetchData($query, $array, $count);
    return $supplier;
}
function getSuppTypeId($id){
    $supplier = array();    
    $query = "SELECT * FROM tblSupplierTypes where supplier_type like '$id%'";
    $array = array(":id"=>$id);
    $count = 0;
    $supplier = fetchData($query, $array, $count);
    return $supplier;
}
function getAllEmp(){
    $emps = array();
    $query = "SELECT * FROM tblEmployee where status='A' order by name asc";
    $count = 0;
    $emps = fetchData($query, array(), $count);
    return $emps;
}
function getAllPO(){
    $emps = array();
    $query = "SELECT * FROM tblPO order by POID asc";
    $count = 0;
    $emps = fetchData($query, array(), $count);
    return $emps;
}
function getAllCustomer(){
    $emps = array();
    $query = "SELECT * FROM tblCustomer order by name asc";
    $count = 0;
    $emps = fetchData($query, array(), $count);
    return $emps;
}
function getAllHardwareDevices(){
    $emps = array();
    $query = "SELECT IMEI,identifier,model FROM tblHardware ";
    $count = 0;
    $emps = fetchData($query, array(), $count);
    return $emps;
}
function getAllempBYRole($t1,$t2,$t3,$t4,$t5)
{
	$emps = array();
    $query = "SELECT * FROM tblEmployee where (status='A') AND (type='".$t1."' OR type='".$t2."' OR type='".$t3."' OR type='".$t4."' OR type='".$t5."') order by name asc";
    $count = 0;
    $emps = fetchData($query, array(), $count);
    return $emps;
}
function getAllempBYDaily($t1)
{
	$emps = array();
    $query = "SELECT * FROM tblEmployee where status='A' AND type='".$t1."'  order by name asc";
    $count = 0;
    $emps = fetchData($query, array(), $count);
    return $emps;
}
function getEmpDetails($empID){
    $empData = array();
    $query = "SELECT * FROM tblEmployee where identifier=:id";
    $count = 0;
    $data = fetchData($query, array(":id"=>$empID), $count);
    if($count){
        $empData = $data[0];
    }
    return $empData;
}
function getCustomerItemsDetails($id)
{
	$empData = array();
    $query = "SELECT * FROM tblCustomerItems where identifier=:id";
    $count = 0;
    $data = fetchData($query, array(":id"=>$id), $count);
    if($count){
        $empData = $data[0];
    }
    return $empData;
}
function getPODetails($poid)
{
	$empData = array();
    $query = "SELECT * FROM tblPO where identifier=:id";
    $count = 0;
    $data = fetchData($query, array(":id"=>$poid), $count);
    if($count){
        $empData = $data[0];
    }
    return $empData;
}
function getPurchaseDetails($empID){
    $empData = array();
    $query = "SELECT * FROM tblPurchase where identifier=:id";
    $count = 0;
    $data = fetchData($query, array(":id"=>$empID), $count);
    if($count){
        $empData = $data[0];
    }
    return $empData;
}
function getSaleLeadDetails($empID){
    $empData = array();
    $query = "SELECT * FROM tblSaleLead where identifier=:id";
    $count = 0;
    $data = fetchData($query, array(":id"=>$empID), $count);
    if($count){
        $empData = $data[0];
    }
    return $empData;
}
function getSuppDetails($empID){
    $empData = array();
    $query = "SELECT * FROM tblSupplier where identifier=:id";
    $count = 0;
    $data = fetchData($query, array(":id"=>$empID), $count);
    if($count){
        $empData = $data[0];
    }
    return $empData;
}
function getCustomerDetails($empID){
 $empData = array();
    $query = "SELECT * FROM tblCustomer where identifier=:id";
    $count = 0;
    $data = fetchData($query, array(":id"=>$empID), $count);
    if($count){
        $empData = $data[0];
    }
    return $empData;
}
function getInventoryDetails($smid,$tbl){
 $empData = array();
    $query = "SELECT * FROM ".$tbl." where identifier=:id";
    $count = 0;
    $data = fetchData($query, array(":id"=>$smid), $count);
    if($count){
        $empData = $data[0];
    }
    return $empData;
}
function getTableDetailsByColumn($tbl,$column,$val){
    $empData = array();
    $query = "SELECT * FROM ".$tbl." where ".$column."=:id";
    $count = 0;
    $data = fetchData($query, array(":id"=>$val), $count);
    if($count){
        $empData = $data[0];
    }
    return $empData;
}
function getSupportDetails($empID){
    $empData = array();
    $query = "SELECT * FROM tblSupport where identifier=:id";
    $count = 0;
    $data = fetchData($query, array(":id"=>$empID), $count);
    if($count){
        $empData = $data[0];
    }
    return $empData;
}
function getAuditDetails($column,$value,$column2,$value2){
    $empData = array();
    $query = "SELECT * FROM tblAudit where $column='".$value."' AND $column2='".$value2."'";
    $count = 0;
    $data = fetchData($query, array(), $count);
    if($count){
        $empData = $data[0];
    }
    return $empData;
}
function audit_details_Inventory($targetID,$auditCode,$tag){
	$dataarray = array();
    $query = "SELECT * FROM tblAudit where targetID='$targetID' AND auditCode='$auditCode'";
    $count = 0;
    $data = fetchData($query, array(), $count);
	foreach($data as $d){
	$jsondata=json_decode($d['data'],true);
    	if($jsondata['old_values']['empID']!=$jsondata['new_values']['empID'] && $jsondata['new_values']['empID']!="" )
	  {		  
		  $dataarray['triggeredBy']=$d['triggeredBy'];
		  $dataarray['createdOn']=$d['createdOn'];
		  return $dataarray;
	  }else{
        $dataarray['triggeredBy']=$d['triggeredBy'];
          $dataarray['createdOn']=$d['createdOn'];
          return $dataarray;
      }
	}
}
function getInventory($column,$value){
    $empData = array();
	$query = "SELECT * FROM tblInventory where $column='".$value."'";
    $count = 0;
    $data = fetchData($query, array(), $count);
    if($count){
        $empData = $data[0];
    }
    return $empData;
}
function getAssignTechDetails($empID){
 $empData = array();
    $query = "SELECT * FROM tblTechnicianScheduling where SupportID=:id";
    $count = 0;
    $data = fetchData($query, array(":id"=>$empID), $count);
    if($count){
        $empData = $data[0];
    }
    return $empData;
}
function checkDuplicateEmail($email, $id=0){
    $query = "SELECT * FROM tblEmployee where email=:email and identifier!=:id";
    $count = 0;
    $data = fetchData($query, array(":email"=>$email, ":id"=>$id), $count);
    if($count){
        return true;
    }else{
        return false;
    }
}
function checkDuplicateEmailCustomer($email, $id=0){
    $query = "SELECT * FROM tblCustomer where email=:email and identifier!=:id";
    $count = 0;
    $data = fetchData($query, array(":email"=>$email, ":id"=>$id), $count);
    if($count){
        return true;
    }else{
        return false;
    }
}
function checkDuplicateTypeSupplier($name, $id=0){
    $query = "SELECT * FROM tblSupplierTypes where supplier_type=:name and identifier!=:id";
    $count = 0;
    $data = fetchData($query, array(":name"=>$name, ":id"=>$id), $count);
    if($count){
        return true;
    }else{
        return false;
    }
}
function checkDuplicatePOREF($poRef, $id=0)
{
	$query = "SELECT * FROM tblPO where POID=:poRef and identifier!=:id";
    $count = 0;
    $data = fetchData($query, array(":poRef"=>$poRef, ":id"=>$id), $count);
    if($count){
        return true;
    }else{
        return false;
    }
}



// use to get the trigger user information--------------------------
function getTriggerInfo($id)
{
    $data = array();
    $query = "SELECT * FROM tblAudit where (targetID='".$id."') ORDER BY identifier DESC limit 1";

    $count = 0;
    $data = fetchData($query, array(), $count);
    return $data;
}

// use to get the information of alotted machine---------------------
function getMachineInfo($id)
{
    $data = array();
    $query = "SELECT * FROM tblmachine where (identifier='".$id."') ";

    $count = 0;
    $data = fetchData($query, array(), $count);
    return $data;
}

// use to get the information of alotted sim---------------------
function getSimInfo($id)
{
    $data = array();
    $query = "SELECT * FROM tblSim where (identifier='".$id."') ";

    $count = 0;
    $data = fetchData($query, array(), $count);
    return $data;
}

// use to get the information of alotted hardware---------------------
function getHardwareInfo($id)
{
    $data = array();
    $query = "SELECT * FROM tblHardware where (identifier='".$id."') ";

    $count = 0;
    $data = fetchData($query, array(), $count);
    return $data;
}

// use to get the information of alotted office inventory---------------------
function getOfficeInventoryInfo($id)
{
    $data = array();
    $query = "SELECT * FROM tblofficeitem where (identifier='".$id."') ";

    $count = 0;
    $data = fetchData($query, array(), $count);
    return $data;
}
function getInvenItemDetails($id,$tbl)
{
if($tbl=="SIM_LUP"){
	$query = "SELECT MSISDN FROM tblSim where identifier=:itemID";
    $count = 0;
    $data = fetchData($query, array(":itemID"=>$id), $count);
    if($count){
        return $data[0][MSISDN];
    }else{
        return false;
    }
 }
if($tbl=="HRD_LUP"){
	$query = "SELECT model FROM tblHardware where identifier=:itemID";
    $count = 0;
    $data = fetchData($query, array(":itemID"=>$id), $count);
    if($count){
        return $data[0][model];
    }else{
        return false;
    }
 }
if($tbl=="MAL_LUP"){
	$query = "SELECT name FROM tblMachine where identifier=:itemID";
    $count = 0;
    $data = fetchData($query, array(":itemID"=>$id), $count);
    if($count){
        return $data[0][name];
    }else{
        return false;
    }
 }
if($tbl=="OIT_LUP"){
	$query = "SELECT itemName FROM tblOfficeItem where identifier=:itemID";
    $count = 0;
    $data = fetchData($query, array(":itemID"=>$id), $count);
    if($count){
        return $data[0][itemName];
    }else{
        return false;
    }
 }
}
function getInventoryShortDetails($id,$tbl)
{
if($tbl=="202"){
	$query = "SELECT MSISDN FROM tblSim where itemID=:itemID";
    $count = 0;
    $data = fetchData($query, array(":itemID"=>$id), $count);
    if($count){
        return $data[0][MSISDN];
    }else{
        return false;
    }
 }
if($tbl=="201"){
	$query = "SELECT model FROM tblHardware where itemID=:itemID";
    $count = 0;
    $data = fetchData($query, array(":itemID"=>$id), $count);
    if($count){
        return $data[0][model];
    }else{
        return false;
    }
 }
if($tbl=="203"){
	$query = "SELECT name FROM tblMachine where itemID=:itemID";
    $count = 0;
    $data = fetchData($query, array(":itemID"=>$id), $count);
    if($count){
        return $data[0][name];
    }else{
        return false;
    }
 }
if($tbl=="204"){
	$query = "SELECT itemName FROM tblOfficeItem where itemID=:itemID";
    $count = 0;
    $data = fetchData($query, array(":itemID"=>$id), $count);
    if($count){
        return $data[0][itemName];
    }else{
        return false;
    }
 }
}
function checkDuplicateEmailSupp($email, $id=0){
    $query = "SELECT * FROM tblSupplier where email=:email and identifier!=:id";
    $count = 0;
    $data = fetchData($query, array(":email"=>$email, ":id"=>$id), $count);
    if($count){
        return true;
    }else{
        return false;
    }
}
function checkDuplicacy($table, $checkColName, $checkColVal, $ignoreIdColName, $ignoreIdColValue){
    $query = "SELECT * FROM ".$table." where ".$checkColName."=? and ".$ignoreIdColName."!=?";
    $core = Core::getInstance();
    $array = array($checkColVal, $ignoreIdColValue);
    $result = $core->dbh->prepare($query);
    $result->execute($array);
    if($result->rowCount()){
        return true;
    }else{
        return false;
    }
}

function getNoItemsInventory($table, $checkColName, $checkColVal){
	if($checkColName && $checkColVal!=""){
    $query = "SELECT * FROM ".$table." where ".$checkColName."=? ";
    $core = Core::getInstance();
    $array = array($checkColVal);
	}else{
	$query = "SELECT * FROM ".$table." where 1=1 ";
    $core = Core::getInstance();
    $array = array();
	}
    $result = $core->dbh->prepare($query);
    $result->execute($array);
    if($result->rowCount()){
        return $result->rowCount();
    }else{
        return $result->rowCount();
    }
}

function getSIMInsertArray($row){
    global $STATUSCODE, $SIMSERPROVIDER;
    $return = array("error"=>"", "simData"=>array());
    $MSISDN = $row['A'];
    $IMEI=$row['B'];
    $serviceProvider = $row['C'];
    $APNValue = $row['D'];
    $BillPlan = $row['E'];
    $LocationCode = $row['F'];
    $statusCode = $row['G'];
    $LocationCode = explode("-", $LocationCode);
    $statusCode = explode("-", $statusCode);
    $serviceProvider = explode("-", $serviceProvider);

    //CHECK LOCATION ID
    if(checkLocation("identifier", $LocationCode[0]) === false){
        $return["error"] = "Location does not exist.";
        return $return;
    }

    //check status code
    if(!array_key_exists($statusCode[0], $STATUSCODE)){
        $return["error"] = "Status code does not match.";
        return $return;
    }

    //check service provider exist or not
    if(!array_key_exists($serviceProvider[0], $SIMSERPROVIDER)){
        $return["error"] = "Status code does not match.";
        return $return;
    }

    $checkRFIDDuplicacy = checkDuplicacy("tblSim", "MSISDN", $MSISDN, "identifier", "0");

    if($checkRFIDDuplicacy){
        $return["error"] = "Duplicate MSISDN";
        return $return;
    }

    $return["simData"]["locationID"] = $LocationCode[0];

    $return["simData"]['IMEI'] = $IMEI;
    $return["simData"]['MSISDN'] = $MSISDN;
    $return["simData"]['serviceProvider'] = $serviceProvider['0'];
    $return["simData"]['APNValue'] = $APNValue;
    $return["simData"]['BillPlan'] = $BillPlan;
    $return["simData"]['statusCode'] = $statusCode['0'];

    return $return;
}

function getOfficeInsertArray($row){
    global $STATUSCODE;
    $return = array("error"=>"", "officeData"=>array());
    $itemName = $row["A"];
    $description = $row["B"];
    $locationCode = explode("-", $row["C"]);
    $statusCode = explode("-", $row["D"]);

    //CHECK LOCATION ID
    if(checkLocation("identifier", $locationCode[0]) === false){
        $return["error"] = "Location does not exist.";
        return $return;
    }

    //check status code
    if(!array_key_exists($statusCode[0], $STATUSCODE)){
        $return["error"] = "Status code does not match.";
        return $return;
    }

    $return["officeData"]["itemName"] = $itemName;
    $return["officeData"]["description"] = $description;
    $return["officeData"]["locationID"] = $locationCode[0];
    $return["officeData"]["statusCode"] = $statusCode[0];
    return $return;
}

function getHardwareInsertArray($row){
    global $STATUSCODE, $PRODUCTCAT;
    $return = array("error"=>"", "hardwareData"=>array());
    $model = $row["A"];
    $imei = $row["B"];
    $productCat = explode("-", $row["C"]);
    $locationCode = explode("-", $row["D"]);
    $statusCode = explode("-", $row["E"]);


    //check product code
    if(!array_key_exists($productCat[0], $PRODUCTCAT)){
        $return["error"] = "Product Category does not match.";
        return $return;
    }

    //CHECK LOCATION ID
    if(checkLocation("identifier", $locationCode[0]) === false){
        $return["error"] = "Location does not exist.";
        return $return;
    }

    //check status code
    if(!array_key_exists($statusCode[0], $STATUSCODE)){
        $return["error"] = "Status code does not match.";
        return $return;
    }

    if(!empty($imei)){
        $checkHardDuplicacy = checkDuplicacy("tblHardware", "IMEI", $imei, "identifier", "0");
        if($checkHardDuplicacy){
            $return["error"] = "IMEI Already Exist.";
            return $return;
        }
    }


    $return["hardwareData"]["IMEI"] = $imei;
    $return["hardwareData"]["model"] = $model;
    $return["hardwareData"]["productCat"] = $productCat[0];
    $return["hardwareData"]["locationID"] = $locationCode[0];
    $return["hardwareData"]["statusCode"] = $statusCode[0];

    return $return;
}

function getMNTInsertArray($row){
    global $STATUSCODE, $MACHINEANDTOOLCLASS;
    $return = array("error"=>"", "mntData"=>array());

    $equipName = $row["A"];
    $description = $row["B"];
    $classificationCode = explode("-", $row["C"]);
    $empID = $row["D"];
    $locationCode = explode("-", $row["E"]);
    $statusCode = explode("-", $row["F"]);

    //CHECK LOCATION ID
    if(checkLocation("identifier", $locationCode[0]) === false){
        $return["error"] = "Location does not exist.";
        return $return;
    }

    //check status code
    if(!array_key_exists($statusCode[0], $STATUSCODE)){
        $return["error"] = "Status code does not match.";
        return $return;
    }

    //CHECK CLASSIFICATION CODE
    if(!array_key_exists($classificationCode[0], $MACHINEANDTOOLCLASS)){
        $return["error"] = "Classification code does not match.";
        return $return;
    }

    $return["mntData"]["name"] = $equipName;
    $return["mntData"]["description"] = $description;
    $return["mntData"]["classification"] = $classificationCode[0];
    $return["mntData"]["statusCode"] = $statusCode[0];
    $return["mntData"]["employeeID"] = $empID;
    $return["mntData"]["locationID"] = $locationCode[0];

    return $return;
}

function checkLocation($column, $value){
    $query = "SELECT * FROM tblLocation where $column='".$value."'";
    $count = 0;
    $data = fetchData($query, array(), $count);
    if($count){
        return true;
    }else{
        return false;
    }
}

function getItemClassCode($selectedItem){
    global $ITEMCLASS;
    $code = "0";
    foreach($ITEMCLASS as $key=>$itemArray){
        if($itemArray[0] == $selectedItem){
            $code = $key;
            break;
        }
    }
    return $code;
}

function generateItemID()
{
    global $core;
    $select ='select ItemID from tblInventory order by identifier desc limit 1';
    $result = $core->dbh->prepare($select);
    $result->execute($array);
    $count = $result->rowCount();
    $record = $result->fetch(PDO::FETCH_ASSOC);
    if($count){
       $identifier=$record['ItemID']+1;
       return $identifier;
    }else{
       return 1;
    }
}
function generatePurchaseID()
{
    global $core;
    $select ='select max(PurchaseID) as PurchaseID from tblPurchase order by identifier desc limit 1';
    $result = $core->dbh->prepare($select);
    $result->execute($array);
    $count = $result->rowCount();
    $record = $result->fetch(PDO::FETCH_ASSOC);
    if($count){
       $identifier=$record['PurchaseID']+1;
       return $identifier;
    }else{
       return 1;
    }
}

function generateAudit($auditCode, $targetID, $data=array(), $notifyAction=""){
    global $_SESSION;
    $auditID = 0;
    $contentArray = array();
    $contentArray["auditCode"] = $auditCode;
    $contentArray["targetID"] = $targetID;
    $contentArray["data"] = json_encode($data);
    $contentArray["notifyAction"] = $notifyAction;
    $contentArray["triggeredBy"] = $_SESSION["user"]["identifier"];
    $auditID = addData("tblAudit", $contentArray);
    return $auditID;
}

function permissionsArray(){
    global $_SESSION, $DS;
    $permissionArray = array();
    $permissionFile = __DIR__.$DS."..".$DS."process-permission.xml";
    $xml=simplexml_load_file($permissionFile);

    foreach($xml->process as $processName){
        $permissionArray["$processName[name]"] = array();
        if($processName["name"] == "inventory"){
            foreach($processName->state as $state){
                $permissionArray["$processName[name]"]["$state[current]"] = array();
                if(isset($state->changeState)){
                    $permissionArray["$processName[name]"]["$state[current]"]["changeState"] = explode(",", $state->changeState);
                }
                if(isset($state->role)){
                    $permissionArray["$processName[name]"]["$state[current]"]["role"] = explode(",", $state->role);
                }
                if(isset($state->approval)){
                    $permissionArray["$processName[name]"]["$state[current]"]["approval"] = explode(",", $state->approval);
                }
                if(isset($state->notification)){
                    $permissionArray["$processName[name]"]["$state[current]"]["notification"] = explode(",", $state->notification);
                }
                if(isset($state->edit)){
                    $permissionArray["$processName[name]"]["$state[current]"]["edit"] = (string)$state->edit;
                }

            }
        }

        if($processName["name"] == "pagesPermission"){
            foreach($processName->page as $pageData){
                if(isset($pageData->role)){
                    $permissionArray["$processName[name]"]["$pageData[name]"] = explode(",", $pageData->role);
                }
            }
        }

        if($processName["name"] == "inventoryMovement"){
            foreach($processName->state as $stateData){
                if(isset($stateData->role)){
                    $permissionArray["$processName[name]"]["$stateData[name]"] = explode(",", $stateData->role);
                }
            }
        }

		if($processName["name"] == "menuPermissions"){
            foreach($processName->state as $stateData){
                if(isset($stateData->role)){
                    $permissionArray["$processName[name]"]["$stateData[name]"] = explode(",", $stateData->role);
                }
            }
        }
    }
    return $permissionArray;
}
function CheckSideBarPermission($page)
{
	global $_SERVER, $permissionsArray, $_SESSION;
	$pager=$permissionsArray["menuPermissions"][$page];
	if(!in_array($_SESSION['user']['type'], $pager)){
	 $pagePer = false;
	}else{
	 $pagePer = true;
	}
	return $pagePer;
}

function checkPagePermission(){
    global $_SERVER, $permissionsArray, $_SESSION;

    $permission = true;
    $page = basename($_SERVER["PHP_SELF"]);

    if($page == "index.php"){
        $permission = true;
    }else if(isset($permissionsArray["pagesPermission"][$page])){
        $roles = $permissionsArray["pagesPermission"][$page];
        if(!in_array($_SESSION['user']['type'], $roles)){
            $permission = false;
        }
    }else{
        echo "ELSE";
        $permission = false;
    }

    return $permission;
}
function getAllDailyAuditReport($auditCode1,$auditCode2,$auditCode3,$auditCode4)
{
	$data = array();
    $query = "SELECT * FROM tblAudit where (auditCode='".$auditCode1."' OR auditCode='".$auditCode2."' OR auditCode='".$auditCode3."' OR auditCode='".$auditCode4."') AND (DATE(createdOn) = CURDATE())";
    $count = 0;
    $data = fetchData($query, array(), $count);
    return $data;
}
function GeneratePass()
{
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $password = substr( str_shuffle( $chars ), 0, 6 );
    return $password;
}
function mailSend($to, $subject, $body, $bcc="", $cc=array(), $fromName="Process-Portal", $from="process.dynakode@gmail.com")
{
    $mail = new PHPMailer(); // create a new object
    $mail->IsSMTP(); // enable SMTP
    $mail->SMTPAuth = true; // authentication enabled
    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465; // or 587
    $mail->IsHTML(true);
    $mail->Username = "process.dynakode@gmail.com";
    $mail->Password = "redcar@2233";
    $mail->SetFrom($from, $fromName);
    $mail->AddReplyTo($from, $fromName);
    $mail->Subject = $subject;
    $mail->Body = $body;
    if(is_array($to)){
        $emailAddresses = $to;
    }else{
        $emailAddresses = explode(",", $to);
    }

   foreach($emailAddresses as $addEmail){
       $mail->AddAddress($addEmail);
   }

    if(!empty($bcc))
        $mail->AddBCC($bcc);
    if(count($cc)){
        foreach($cc as $ccMail){
            if(!empty($ccMail)){
                $mail->AddCC($ccMail);
            }
        }
    }
  $result = $mail->Send();
  return $result;
}
function getAll_invoice_desc($inv){	
	$data = array();
    $query = "SELECT * FROM tblinvoicedesc where (invoice_no='".$inv."') ";
    $count = 0;
    $data = fetchData($query, array(), $count);
    return $data;
}
function getInvoiceTaxDetails($inv){	
   $dataTax = array();	
	 $query = "SELECT * FROM tblinvoice_tax where name='".$inv."' ";
	 $count = 0;
     $dataTax = fetchData($query, array(), $count);	
	 return $dataTax;
}
function getInvoiceDetails($inv){	
   $dataTax = array();	
	 $query = "SELECT * FROM tblinvoice where identifier='".$inv."' ";
	 $count = 0;
     $dataTax = fetchData($query, array(), $count);	
	 return $dataTax[0];
}
function getCompanyDetails($id){	
   $dataTax = array();	
	 $query = "SELECT * FROM tblCompany where identifier='".$id."' ";
	 $count = 0;
     $dataTax = fetchData($query, array(), $count);	
	 return $dataTax[0];
}

function getMasterTaxes(){
   $tax = array();
   $query = "SELECT * FROM tblInvoice_tax ";
   $count = 0;
   $tax = fetchData($query, array(), $count);
   return $tax;
}
function getTaxes($query, $array = array()){
   global $core;
   $select = $query;
   $counter = 0;
   $count = 0;
   $data = fetchData($query, $array, $count);
   if($count){
       $counter = $data[0]['tax'];
   }
   return $counter;
}
/* use to get customer bill cycle */
function getCustomerbillCycle($cid){
    $dataTax = array(); 
     $query = "SELECT billCycle FROM tblcustomer where identifier='".$cid."' ";
     $count = 0;
     $dataTax = fetchData($query, array(), $count); 
     return $dataTax[0];
}

/* Get invoice data......*/
function getInvoices($table, $status){
    
    $query = "SELECT * FROM ".$table." where status=".$status." ";
    $core = Core::getInstance();
    $array = array();
    $result = $core->dbh->prepare($query);
    $result->execute($array);
    if($result->rowCount()){
        return $result->rowCount();
    }else{
        return $result->rowCount();
    }
}

/**/
function getAllCompany(){
   $company = array();
   $query = "SELECT * FROM tblCompany ";
   $count = 0;
   $company = fetchData($query, array(), $count);
   return $company;
}

/* Get total invoice amount status wise......*/
function getStatusWiseAmount($table, $status){
    
    $query = "SELECT * FROM ".$table." where status=".$status." ";
    $count = 0;
    $invoiceData = fetchData($query, array(), $count);
    $totalAmount;
    $amount;
    $paidAmount;

    if($status==0){
        foreach ($invoiceData as $value) {
            $totalAmount +=$value['total_amount'];
        } 
    }elseif ($status==1) {
        foreach ($invoiceData as $value) {
            $amount+=$paidAmount+$value['total_amount'];
            $paidAmount+=$paidAmount+$value['paid_amount'];
            $totalAmount=$amount-$paidAmount;
        } 
    }elseif ($status==2) {
        foreach ($invoiceData as $value) {
            $totalAmount +=$value['total_amount'];
        
        } 
    }

    return number_format($totalAmount,2);
}

/* Get invoice month wise data......*/
function getCurrentMonthInvoices($table, $status){
    
    $query = "SELECT * FROM ".$table." WHERE MONTH(date) = MONTH(CURRENT_DATE())
AND YEAR(date) = YEAR(CURRENT_DATE()) and status=".$status." ";
    $core = Core::getInstance();
    $array = array();
    $result = $core->dbh->prepare($query);
    $result->execute($array);
    if($result->rowCount()){
        return $result->rowCount();
    }else{
        return $result->rowCount();
    }
}

/* Get current month invoice amount status wise......*/
function getCurrentStatusWiseAmount($table, $status){
    $query = "SELECT * FROM ".$table." WHERE MONTH(date) = MONTH(CURRENT_DATE())
AND YEAR(date) = YEAR(CURRENT_DATE()) and status=".$status." ";
    $count = 0;
    $invoiceData = fetchData($query, array(), $count);
    $totalAmount;
    $amount;
    $paidAmount;

    if($status==0){
        foreach ($invoiceData as $value) {
            $totalAmount +=$value['total_amount'];
        } 
    }elseif ($status==1) {
        foreach ($invoiceData as $value) {
            $amount+=$paidAmount+$value['total_amount'];
            $paidAmount+=$paidAmount+$value['paid_amount'];
            $totalAmount=$amount-$paidAmount;
        } 
    }elseif ($status==2) {
        foreach ($invoiceData as $value) {
            $totalAmount +=$value['total_amount'];
        
        } 
    }

    return number_format($totalAmount,2);
}

/* Use to Generate Invoice PDF*/
function numberTowords($num)
{
   $ones = array(
   1 => "one",
   2 => "two",
   3 => "three",
   4 => "four",
   5 => "five",
   6 => "six",
   7 => "seven",
   8 => "eight",
   9 => "nine",
   10 => "ten",
   11 => "eleven",
   12 => "twelve",
   13 => "thirteen",
   14 => "fourteen",
   15 => "fifteen",
   16 => "sixteen",
   17 => "seventeen",
   18 => "eighteen",
   19 => "nineteen"
   );
   $tens = array(
   1 => "ten",
   2 => "twenty",
   3 => "thirty",
   4 => "forty",
   5 => "fifty",
   6 => "sixty",
   7 => "seventy",
   8 => "eighty",
   9 => "ninety"
   );
   $hundreds = array(
   "hundred",
   "thousand",
   "million",
   "billion",
   "trillion",
   "quadrillion"
   ); //limit t quadrillion
   $num = number_format($num,2,".",",");
   $num_arr = explode(".",$num);
   $wholenum = $num_arr[0];
   $decnum = $num_arr[1];
   $whole_arr = array_reverse(explode(",",$wholenum));
   krsort($whole_arr);
   $rettxt = "";
   foreach($whole_arr as $key => $i){
   if($i < 20){
   $rettxt .= $ones[$i];
   }elseif($i < 100){
   $rettxt .= $tens[substr($i,0,1)];
   $rettxt .= " ".$ones[substr($i,1,1)];
   }else{
   $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0];
   $rettxt .= " ".$tens[substr($i,1,1)];
   $rettxt .= " ".$ones[substr($i,2,1)];
   }
   if($key > 0){
   $rettxt .= " ".$hundreds[$key]." ";
   }
   }
   if($decnum > 0){
   $rettxt .= " and ";
   if($decnum < 20){
   $rettxt .= $ones[$decnum];
   }elseif($decnum < 100){
   $rettxt .= $tens[substr($decnum,0,1)];
   $rettxt .= " ".$ones[substr($decnum,1,1)];
   }
   }
   return $rettxt;
   }

   extract($_POST);
   if(isset($convert))
   {
   echo "<p align='center' style='color:blue'>".numberTowords("$num")."</p>";
   }


?>
