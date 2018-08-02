<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");

$requestData= $_REQUEST;
$customer=$_GET['customer'];
$customColumns = array("C.devicetype"=>"PRODUCTCAT");
$columns = array(
// datatable column index  => database column name
    0 =>'C.identifier',
    1 =>'C.devicetype',
    2=>'H.IMEI as itemname',
	3=>'S.MSISDN as simname',
    4=>'C.vehicleNo',
	5=>'C.itemID',
	6=>'C.simID'
);
// getting total number records without any search
$implodeData = implode(',', $columns);

$query = "SELECT [IMPLODEDATA] ";
$query .= " FROM tblCustomerItems C  LEFT JOIN tblHardware H on H.identifier=C.itemID  LEFT JOIN tblSim S on C.simID=S.identifier WHERE C.customerID='$customer' ";
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
 if(strpos( $columns[$requestData['order'][0]['column']], " as ") !== false){
	 $valuePart = explode(" as ",  $columns[$requestData['order'][0]['column']]);
     $columns[$requestData['order'][0]['column']]= $valuePart[0];
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
	$itemname=$d["itemname"];
	if($itemname=="")
	{
		$itemname='<b class="btn-danger">'.$d["itemID"].'</b>';
	}
	$simname=$d["simname"];
	if($simname=="")
	{
		$simname='<b class="btn-danger">'.$d["simID"].'</b>';		
	}
    $array = array();
    $editLink = "#?empid=".$d['identifier'];
    $array[] = $requestData['start']+$countVal;
    $countVal++;
    $array[] = $PRODUCTCAT[$d['devicetype']][1];
    $array[] = $itemname;
    $array[] = $simname;
    $array[] = $d["vehicleNo"];
    $array[] = "<a title='Edit Employee Details' href='".$editLink."' class='btn btn-default'><span class='fa fa-pencil'></span></i>";
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
