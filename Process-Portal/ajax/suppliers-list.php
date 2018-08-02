<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");

$requestData= $_REQUEST;

$customColumns = array("billCycle"=>"SUPPBILLCYL");
$columns = array(
// datatable column index  => database column name
    0 =>'identifier',
    1 =>'name',
    2=>'email',
	3=>'phone',
    4=>'address',
    5=>'PAN',	
    6=>'billCycle',
	7=>'supplier_type as sType'
);
// getting total number records without any search
$implodeData = implode(',', $columns);

$query = "SELECT [IMPLODEDATA] ";
$query .= " FROM tblSupplier WHERE 1 = 1";
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
		$supp=getSuppTypeId($requestData['search']['value'])[0];
		 $suppvalue=$supp['identifier'];
		if($suppvalue!=""){
         $extraQuery .= " OR supplier_type IN ('".$suppvalue."')";
	   }
        $extraQuery .= " OR ".$value." LIKE '".$requestData['search']['value']."%' ";
    }	 
}

if(!empty($extraQuery)){
    $query = $query. " and (".ltrim($extraQuery, " OR ").")";
}

if(!empty($requestData['columns'][1]['search']['value'])){
	$supp_type=$requestData['columns'][1]['search']['value'];
if($supp_type!="null"){
$query = $query. " and supplier_type IN (".$supp_type.")";
}
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
    $editLink = "add-supplier.php?empid=".$d['identifier'];
    $array[] = $requestData['start']+$countVal;
    $countVal++;
	//foreach($d['sType'] as $
	if($d['sType']!=""){
		$types=array();
	$sType=explode(',',$d['sType']);
	foreach($sType as $type)
	{
		$supp=getSuppliersTypes($type)[0];
		$types[]=$supp['supplier_type'];
	}
	$Type=implode(',',$types);
	}else{
		$Type="";
	}	
    $array[] = $d['name'];
    $array[] = $d["email"];
    $array[] = $d["phone"];
    $array[] = $d["address"];
    $array[] = $d["PAN"];
	$array[] = $SUPPBILLCYL[$d["billCycle"]];
	$array[] = $Type;
    $array[] = "<a title='Edit Supplier Details' href='".$editLink."' class='btn btn-default'><span class='fa fa-pencil'></span></i></a>";
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
