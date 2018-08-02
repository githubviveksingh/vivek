<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");
include("../includes/check_permission.php");
$requestData= $_REQUEST;

$customColumns = array("S.Status"=>"SUPPORTSTS",'S.itemID'=>'ItemDetails','S.Classification'=>'SUPPORTTYPE');

$columns = array(
// datatable column index  => database column name
    0 => 'S.identifier',
    1 => 'S.DateofReport',
    2 => 'S.raisedBy',
    3 => 'S.Classification',
    4 => 'C.Name',
    5 => 'E.name',
    6 => 'S.Status',
	7 => 'S.DateOfResolution',
    8 => 'S.itemCategory',
	9 => 'S.ServiceReportNo',
    10 => 'S.itemID'
    
	
	);
// getting total number records without any search
$implodeData = implode(',', $columns);

$query = "SELECT [IMPLODEDATA] ";
$query .= " FROM tblSupport S JOIN tblCustomer C on C.identifier=S.CustomerID left join tblEmployee E on E.identifier=S.technicianID  WHERE 1 = 1";
$counterQuery = $query;
$counterQuery = str_replace("[IMPLODEDATA]", "count(*) as counter", $counterQuery);
$totalCounter = getCounter($counterQuery, array());;


// getting records as per search parameters
$extraQuery = "";
foreach($columns as $key=>$value){

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

if(!empty($requestData['columns'][4]['search']['value'])){
    $supp_type=$requestData['columns'][4]['search']['value'];
    if($supp_type!="null"){
        $query = $query. " and CustomerID IN (".$supp_type.")";
    }
}

if(!empty($requestData['columns'][5]['search']['value'])){
    $supp_type=$requestData['columns'][5]['search']['value'];
    if($supp_type!="null"){
        $query = $query. " and technicianID IN (".$supp_type.")";
    }
}

if(!empty($requestData['columns'][6]['search']['value'])){
    $supp_type=$requestData['columns'][6]['search']['value'];
    if($supp_type!="null"){
        $query = $query. " and S.Status IN (".$supp_type.")";
    }
}



$counterQuery = $query;
$counterQuery = str_replace("[IMPLODEDATA]", "count(*) as counter", $counterQuery);
$query = str_replace("[IMPLODEDATA]", $implodeData, $query);
$totalFiltered = 0;

//COUNT TOTAL NUMBER OF ROWS
// echo $counterQuery;
$totalFiltered = getCounter($counterQuery, array());
//echo $requestData['order'][0]['dir'];
//die();
$query .= " ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";  // adding length
$counter = 0;

$data = fetchData($query, array(), $counter);
$arrayData = array();
$countVal = 1;
foreach($data as $d){
    $array = array();
	$editLink = "add-support.php?empid=".$d["identifier"];
	$assignLink = "assign-technician.php?supportID=".$d["identifier"];
    $viewLink = "view-support.php?sID=".$d["identifier"];
    $DateOfResolution=$d["DateOfResolution"];
   if($DateOfResolution<=0){
	   $DateOfResolution="";
   }
	$itemdetails=getInventoryDetails($d['itemID'],"tblHardware");
	
	$itemdetail=$itemdetails['IMEI'].'-'.$itemdetails['model'];
	$current_status=$SUPPORTSTS[$d["Status"]][1];	
	if($d['Status']=="433"){
	$actionOption="<a title='View Support Details' href='".$viewLink."' class='btn btn-default'><span class='fa fa-eye'></span></i></a>";
	}else{
	$actionOption="<a title='Edit Support Details' href='".$editLink."' class='btn btn-default'><span class='fa fa-pencil'></span></i></a>     <a title='View Support Details' href='".$viewLink."' class='btn btn-default'><span class='fa fa-eye'></span></i></a>";
	}
    $array[] = $requestData['start']+$countVal;
    $countVal++;
    $array[] = $d["DateofReport"];
    $array[] = $d['raisedBy'];
    $array[] = $SUPPORTTYPE[$d["Classification"]];
	$array[] = $d["Name"];
	$array[] = $d["name"];
    $array[] = $current_status;
	$array[] = $DateOfResolution;
	$array[]=$actionOption;
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
