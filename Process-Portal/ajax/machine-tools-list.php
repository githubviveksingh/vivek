<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");
include("../includes/check_permission.php");
$requestData= $_REQUEST;

$customColumns = array("M.statusCode"=>"STATUSCODE", "M.classification"=>"MACHINEANDTOOLCLASS");

$columns = array(
// datatable column index  => database column name
    0 =>'M.identifier',
    1 => 'M.name',
    2=> 'M.description',
    3=> 'M.classification',
    4=>'M.locationID',
	5=>'M.statusCode',
    6=>'M.employeeID',    
    7=>'M.createdOn',
    8=>'L.name as locationName',
    9=>'E.name as employeeName',
	10=>'M.itemID'
);
// getting total number records without any search
$implodeData = implode(',', $columns);

$query = "SELECT [IMPLODEDATA] ";
$query .= " FROM tblMachine M  JOIN tblLocation L on L.identifier=M.locationID JOIN tblEmployee E on E.identifier=M.employeeID WHERE 1 = 1";
$counterQuery = $query;
$counterQuery = str_replace("[IMPLODEDATA]", "count(*) as counter", $counterQuery);
$totalCounter = getCounter($counterQuery, array());;


// getting records as per search parameters
$extraQuery = "";
foreach($columns as $key=>$value){

    if(strpos($value, " as ") !== false){
        $valuePart = explode(" as ", $value);
        $value = $valuePart[0];
    }

    if(array_key_exists($value, $customColumns)){
        if(!empty($requestData['search']['value'])){
            $val = $requestData['search']['value'];
            $str = "";

            foreach(${$customColumns[$value]} as $key=>$array){
                if(strpos(strtolower($array[1]), strtolower($val)) !== false){
                    $str .= $key.", ";
                }
            }
            $str = rtrim($str, ", ");
            if(!empty($str)){
                $extraQuery .= " OR ".$value." in (".$str.")";
            }
        }
    }
    if(!empty($requestData['search']['value'])){
        $extraQuery .= " OR ".$value." LIKE '".$requestData['search']['value']."%' ";
    }

}

if(!empty($extraQuery)){
    $query = $query. " and (".ltrim($extraQuery, " OR ").")";
}

$counterQuery = $query;
$counterQuery = str_replace("[IMPLODEDATA]", "count(*) as counter", $counterQuery);
$query = str_replace("[IMPLODEDATA]", $implodeData, $query);
$totalFiltered = 0;

//COUNT TOTAL NUMBER OF ROWS
$totalFiltered = getCounter($counterQuery, array());

$query .= " ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";  // adding length

$counter = 0;
$data = fetchData($query, array(), $counter);
$arrayData = array();
$countVal = 1;
foreach($data as $d){
    $array = array();
    $editLink = "update-machine-tools.php?smid=".$d["identifier"];
	$moveData=getMovementStatus($d['itemID']);
	$movementdata=getLocationName($moveData[0]['currentLocationID']).' To '.getLocationName($moveData[0]['newLocationID']);
	if($moveData!=0){
	   $actionOption='[OTHERACTIONS]<li ><a href="'.$editLink.'">Edit</a></li>'; 
	}else{
	   $actionOption='<li ><a href="'.$editLink.'">Edit</a></li> 
				   <li><a href="javascript:void(0);" class="changelc" current-location="'.$d['locationID'].'"  itemid="'.$d['itemID'].'" ctext="'.$d['name'].':'.$d['description'].'">Change Location</a></li>';
	}
    
    $otherActions = "";
    if(in_array($_SESSION['user']['type'], $permissionsArray["inventoryMovement"]["approval_pending"])){
        $otherActions = '<li><a href="javascript:void(0);" new-location="'.$moveData[0]['newLocationID'].'"  current-location="'.$moveData[0]['currentLocationID'].'" itemid="'.$d['itemID'].'" change-status="1" movement-data="'.$movementdata.'" iden="'.$moveData[0]['identifier'].'" class="btn green changellaction">Allow</a></li>
                   <li><a href="javascript:void(0);" new-location="'.$moveData[0]['newLocationID'].'" current-location="'.$moveData[0]['currentLocationID'].'" itemid="'.$d['itemID'].'" movement-data="'.$movementdata.'" iden="'.$moveData[0]['identifier'].'" change-status="-1" class="btn red changellaction">Reject</a></li>';
    }

    $actionOption = str_replace("[OTHERACTIONS]", $otherActions, $actionOption);
    $array[] = $requestData['start']+$countVal;
    $countVal++;
    $array[] = $d['name'];
    $array[] = $d["description"];
    $array[] = $MACHINEANDTOOLCLASS[$d["classification"]][1]." (".$d["classification"].")";
    $array[] = $d["locationName"];
    $array[] = $STATUSCODE[$d["statusCode"]][1]." (".$d["statusCode"].")";
    $array[] = $d['employeeName'];
    $array[] = $d['createdOn'];
    $array[] = '<div class="actions">
				<div class="btn-group">
					<a class="btn btn-primary btn-sm uppercase sbold" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> Actions
						<i class="fa fa-angle-down"></i>
					</a>
					<ul class="dropdown-menu pull-right btnActionCustom" id="sDrop">'.$actionOption.'
					</ul>
				</div>
			</div>';
    $arrayData[] = $array;
}

$json_data = array(
            "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal"    => intval( $totalCounter ),  // total number of records
            "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data"            => $arrayData   // total data array
            );

echo json_encode($json_data);
?>
