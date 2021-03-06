<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");
include("../includes/check_permission.php");
$requestData= $_REQUEST;
$emp=$_GET['emp'];
$customColumns = array("serviceProvider"=>"SIMSERPROVIDER");

$columns = array(
// datatable column index  => database column name
    0 =>'identifier',
    1=>'MSISDN',
    2 =>'serviceProvider',  
	3=>'createdOn',
	4=>'itemID',
    5=>'EMPID',
    13=> 'alote_status'
);
// getting total number records without any search
$implodeData = implode(',', $columns);
// $query = "SELECT  $implodeData";
// $query .= " FROM tblSim";
// $totalFiltered = 0;
// countData($query, $totalFiltered, array());
// $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

$query = "SELECT [IMPLODEDATA] ";
$query .= " FROM tblSim  WHERE 1 = 1 AND empID='$emp'";

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

$query .= " ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  ";  // adding length

$counter = 0;
$data = fetchData($query, array(), $counter);
$arrayData = array();
$countVal = 1;
foreach($data as $d){
    $array = array();
    $array[] = $requestData['start']+$countVal;
    $countVal++;
    $array[] = $d["MSISDN"];
    $array[] = $SIMSERPROVIDER[$d["serviceProvider"]][1];
	$audit=audit_details_Inventory($d["identifier"],'SIM_UPD','EMPID');
	$array[] = getEmpDetails($audit["triggeredBy"])['name'];
	$array[] = $audit["createdOn"]; 
    if($d['alote_status']==0){
        $array[] = "<span id='alotAction'><a href='accept-sim.php?mcid=".$d['identifier']."&empID=".$d['EMPID']."' id='acceptAlot' class='btn green'>Accept</a> | <a href='reject-sim.php?mcid=".$d['identifier']."&empID=".$d['EMPID']."' id='acceptAlot' class='btn red'>Reject</a></span>";
    }else if($d['alote_status']==1){  
       $array[] = "<button class='btn green'>Accepted</button>";
    }else if($d['alote_status']==2){  
      $array[] = "<button class='btn red'>Rejected</button>";
    }		
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
