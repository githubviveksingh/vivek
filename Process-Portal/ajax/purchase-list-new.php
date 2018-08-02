<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");
$requestData= $_REQUEST;

$customColumns = array("E.PurchaseClassification"=>"PURCHASECLASS","E.Status"=>"PURCHASESTATUS");
$columns = array(
// datatable column index  => database column name
    0 =>'E.identifier',
    1 =>'E.POReference',
    2=>'E.PurchaseClassification',
	3=>'E.Quantity',  
    4=>'L.name as Supplier',	
    5=>'E.Status',
	6=>'E.createdOn',
	7=>'P.name',
	8=>'E.uploadPO',
    9=>'E.DeliveryChallan'
);
// getting total number records without any search
$implodeData = implode(',', $columns);

$query = "SELECT [IMPLODEDATA] ";
$query .= " FROM tblPurchase E join tblSupplier L on L.identifier=E.SupplierID join tblaudit A on A.targetID=E.identifier and A.auditCode='PUR_ADD' join tblEmployee P on A.triggeredBy=P.identifier   WHERE 1 = 1 ";
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
                 if(strpos(strtolower($array[0]), strtolower($val)) !== false){
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
if(!empty($requestData['columns'][5]['search']['value'])){
    $supp_type=$requestData['columns'][5]['search']['value'];
if($supp_type!="null"){
$query = $query. " and E.Status IN (".$supp_type.")";
}
}

if(!empty($requestData['columns'][2]['search']['value'])){
    $supp_type=$requestData['columns'][2]['search']['value'];
if($supp_type!="null"){
$query = $query. " and E.PurchaseClassification IN (".$supp_type.")";
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
// echo $query;
// die();
$counter = 0;
$data = fetchData($query, array(), $counter);
$arrayData = array();
$countVal = 1;
foreach($data as $d){
    $array = array();
	$pStatus = $d["Status"];
    $editLink = "add-purchase.php?id=".$d['identifier'];
    $array[] = $requestData['start']+$countVal;
    $countVal++;
		
    $array[] = "<a href='upload/popdf/".$d['uploadPO']."' target='_blank'>".$d['POReference']."</a>";
    $array[] = $PURCHASECLASS[$d["PurchaseClassification"]][0];
    $array[] = $d["Quantity"];
   // $array[] = $d["DeliveryChallan"];
    $array[] = $d["Supplier"];
	$array[] = $PURCHASESTATUS[$d["Status"]][0];
	$array[] = $d["createdOn"];
	$array[] = $d["name"];
	$actions = '<div class="actions">
                        <div class="btn-group">
                            <a class="btn btn-primary btn-sm uppercase sbold" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> Mark
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu pull-right btnActionCustom">
                                [ACTIONS]
                            </ul>
                        </div>
                    </div>';
        $otherActions = "";
        if(isset($permissionsArray["inventory"][$pStatus]["edit"]) && $permissionsArray["inventory"][$pStatus]["edit"] == "1"){

            if(isset($permissionsArray["inventory"][$pStatus]["role"]) && in_array($_SESSION['user']['type'], $permissionsArray["inventory"][$pStatus]["role"])){
                $otherActions = '<li><a href="'.$editLink.'">EDIT</a></li>';
            }
        }

        foreach($permissionsArray["inventory"][$pStatus]["changeState"] as $changeStatuses){
            $checkArray = array();
            if(isset($permissionsArray["inventory"][$pStatus]["approval"])){
                $checkArray = $permissionsArray["inventory"][$pStatus]["approval"];
            }else{
                $checkArray = $permissionsArray["inventory"][$pStatus]["role"];
            }
            if(in_array($_SESSION['user']['type'], $checkArray)){
                $otherActions .= '<li><a href="javascript:void(0);" current-status="'.$d['Status'].'" dChallan="'.$d['DeliveryChallan'].'" purchase-id="'.$d['identifier'].'" data-id="'.$changeStatuses.'" class="actionStatus" data-po="'.$d['POReference'].'">'.$PURCHASESTATUS["$changeStatuses"][0].'</a></li>';
            }
        }

        if(!empty($otherActions)){
            $actions = str_replace("[ACTIONS]", $otherActions, $actions);
        }else{
            $actions = "";
        }      
    $array[] = $actions;
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
