<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");
include("../includes/check_permission.php");
$requestData= $_REQUEST;
$emp=$_GET['emp'];
//$customColumns = array("productCat"=>"PRODUCTCAT");

$columns = array(
// datatable column index  => database column name
    0 =>'identifier',
    1 => 'supplier_type',
    2=>'createdOn',
);
// getting total number records without any search
$implodeData = implode(',', $columns);

$query = "SELECT [IMPLODEDATA] ";
$query .= " FROM tblSupplierTypes WHERE 1=1";
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

$counterQuery = $query;
$counterQuery = str_replace("[IMPLODEDATA]", "count(*) as counter", $counterQuery);
$query = str_replace("[IMPLODEDATA]", $implodeData, $query);
$totalFiltered = 0;

//COUNT TOTAL NUMBER OF ROWS
$totalFiltered = getCounter($counterQuery, array());

$query .= " ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."     ";  // adding length

$counter = 0;
$data = fetchData($query, array(), $counter);
$arrayData = array();
$countVal = 1;
foreach($data as $d){
    $array = array();
	$editLink="supplier-types.php?sType=".$d['identifier'];
    $array[] = $requestData['start']+$countVal;
    $countVal++;
    $array[] = $d['supplier_type'];
	$array[] = $d["createdOn"]; 
    $array[] = '<a class="btn btn-default" href="'.$editLink.'"><span class="fa fa-pencil"></span></a>';  	
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
