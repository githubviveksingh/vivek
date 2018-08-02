<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");

$requestData= $_REQUEST;

$customColumns = array("itemID"=>"itemID", "statusCode"=>"STATUSCODE");

$columns = array(
// datatable column index  => database column name
    0 =>'identifier',
    1 =>'ServiceReportNo',
    2=> 'itemID',
    3=> 'DateofReport',
    4=>'customer',
	4=>'customer',
	4=>'customer',
	4=>'customer',
);
// getting total number records without any search
$implodeData = implode(',', $columns);
// $query = "SELECT  $implodeData";
// $query .= " FROM tblSim";
// $totalFiltered = 0;
// countData($query, $totalFiltered, array());
// $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

$query = "SELECT [IMPLODEDATA] ";
$query .= " FROM tblSim WHERE 1 = 1";

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
    }else{
        if(!empty($requestData['search']['value'])){
            $extraQuery .= " OR ".$value." LIKE '".$requestData['search']['value']."%' ";
        }
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
foreach($data as $d){
    $array = array();
    $editLink = "add-supplier.php?empid=".$d["identifier"];
    $array[] = $d["identifier"];
    $array[] = $SIMSERPROVIDER[$d["serviceProvider"]][1];
    $array[] = $d["MSISDN"];
    $array[] = $d["BillPlan"];
    $array[] = $STATUSCODE[$d["statusCode"]][1]." (".$d["statusCode"].")";
    $array[] = "<a title='Edit Supplier Details' href='".$editLink."' class='btn btn-default'><span class='fa fa-pencil'></span></i>";
    $arrayData[] = $array;
}
$json_data = array(
            "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal"    => intval( $counter ),  // total number of records
            "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data"            => $arrayData   // total data array
            );

echo json_encode($json_data);
?>
