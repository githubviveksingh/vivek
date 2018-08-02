<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");

$requestData= $_REQUEST;

$customColumns = array("E.status"=>"EMPSTATUS", "E.type"=>"EMPTYPE");
$columns = array(
// datatable column index  => database column name
    1 =>'E.identifier',
    2 =>'E.name',
    3=>'E.email',
	4=>'E.locationID',
    5=>'E.type',
    6=>'E.status',	
    7=>'L.name as locationName'
);
// getting total number records without any search
$implodeData = implode(',', $columns);

$query = "SELECT [IMPLODEDATA] ";
$query .= " FROM tblEmployee E  JOIN tblLocation L on L.identifier=E.locationID WHERE 1 = 1";
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

            foreach($$customColumns[$value] as $key=>$array){
                if(strpos(strtolower($array), strtolower($val)) !== false){
                    $str .= $key.", ";
                }
            }
            $str = rtrim($str, ", ");
            if(!empty($str)){
                if(intval($str)){
                    $extraQuery .= " OR ".$value." in (".$str.")";
                }else{
                    $extraQuery .= " OR ".$value." in ('".$str."')";
                }

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

$query .= " ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  ";  // adding length

$counter = 0;
$data = fetchData($query, array(), $counter);
$arrayData = array();
$countVal = 1;
foreach($data as $d){
    $array = array();
    $editLink = "add-employee.php?empid=".$d['identifier'];
	$viewLink = "view-employee-details.php?empid=".$d['identifier'];
    $array[] = $requestData['start']+$countVal;
    $countVal++;  
	$array[] = $d['identifier'];
    $array[] = $d['name'];
    $array[] = $d["email"];
    $array[] = $d["locationName"];
    $array[] = $EMPTYPE[$d["type"]];
    $array[] = $EMPSTATUS[$d["status"]];
    $array[] = "<a title='Edit Employee Details' href='".$editLink."' class='btn btn-default'><span class='fa fa-pencil'></span></i></a> <a title='View Employee Details' href='".$viewLink."' class='btn btn-default'><span class='fa fa-eye'></span></i></a>";
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
