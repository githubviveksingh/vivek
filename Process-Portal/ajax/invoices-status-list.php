<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");

$requestData    = $_REQUEST;
$status         = $_GET['status'];
$_SESSION['history_link']=$_SERVER['HTTP_REFERER'];

$customColumns  = array();
$columns        = array(
// datatable column index  => database column name
    0 =>'I.identifier',
    1 =>'Q.com_name as comname',
    2 =>'C.Name as buyerName',
	3 =>'I.invoice_no',
    4 =>'I.date',
	//5=>'I.total_qty',
    6 =>'I.status',
	7 =>'I.total_amount',
    8 =>'I.createdOn',
    9 =>'I.buyer'
);
// getting total number records without any search
$implodeData = implode(',', $columns);
$query  = "SELECT [IMPLODEDATA] ";
if(isset($_GET['status']) && isset($_GET['cur_month'])){
    $query .= " FROM tblInvoice I  INNER JOIN tblCustomer C on C.identifier=I.buyer INNER JOIN tblCompany Q on Q.identifier=I.company_name  where MONTH(I.date) = MONTH(CURRENT_DATE())
AND YEAR(I.date) = YEAR(CURRENT_DATE()) AND I.status=".$status." ";
}else if(isset($_GET['status'])){
    $query .= " FROM tblInvoice I  INNER JOIN tblCustomer C on C.identifier=I.buyer INNER JOIN tblCompany Q on Q.identifier=I.company_name  where I.status=".$status." ";

}else{
    $query .= " FROM tblInvoice I  JOIN tblCustomer C on C.identifier=I.buyer join tblCompany Q on Q.identifier=I.company_name";
}

$counterQuery   = $query;
$counterQuery   = str_replace("[IMPLODEDATA]", "count(*) as counter", $counterQuery);
$totalCounter   = getCounter($counterQuery, array());
//$cbillCycle     = getCustomerbillCycle($customer);


// getting records as per search parameters
$extraQuery = "";
foreach($columns as $key=>$value){

    if(strpos($value, " as ") !== false){
        $valuePart  = explode(" as ", $value);
        $value      = $valuePart[0];
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
	$valuePart  = explode(" as ",  $columns[$requestData['order'][0]['column']]);
    $columns[$requestData['order'][0]['column']]= $valuePart[0];
 }
if(!empty($extraQuery)){
    $query      = $query. " and (".ltrim($extraQuery, " OR ").")";
}

if(!empty($requestData['columns'][6]['search']['value'])){
    $supp_type=$requestData['columns'][6]['search']['value'];
    if($supp_type!="null"){
        $query = $query. " and I.status IN (".$supp_type.")";
    }
}

if(!empty($requestData['columns'][2]['search']['value'])){
    $supp_type=$requestData['columns'][2]['search']['value'];
    if($supp_type!="null"){
        $query = $query. " and I.buyer IN (".$supp_type.")";
    }
}

if(!empty($requestData['columns'][1]['search']['value'])){
    $supp_type=$requestData['columns'][1]['search']['value'];
    if($supp_type!="null"){
        $query = $query. " and comname IN (".$supp_type.")";
    }
}

$counterQuery   = $query;
$counterQuery   = str_replace("[IMPLODEDATA]", "count(*) as counter", $counterQuery);
$query          = str_replace("[IMPLODEDATA]", $implodeData, $query);
$totalFiltered  = 0;


//COUNT TOTAL NUMBER OF ROWS
$totalFiltered = getCounter($counterQuery, array());

$query .= " ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";  // adding length
$counter    = 0;
$data       = fetchData($query, array(), $counter);
$arrayData  = array();
$countVal   = 1;



foreach($data as $d){

    if(isset($_GET['status']) && !empty($_GET['status'])){
       $statusLink ='';
    }else{
        
        $statusLink ='<li>
                        <a title="Paid/Unpaid" href="javascript:;"  class="PaidAmount" data-id="'.$d['identifier'].'">Paid/Unpaid</a>
                    </li>'; 
    }


    /* get billCycle date differance */
    $cbillCycle     = getCustomerbillCycle($d['buyer']);
    if($cbillCycle['billCycle']==1){
        $date    = $d["date"];
        $dueDate = date('Y-m-d', strtotime('+1 week', strtotime($date)));
    }elseif ($cbillCycle['billCycle']==2) {
        $date    = $d["date"];
        $dueDate = date('Y-m-d', strtotime('+1 month', strtotime($date)));
    }elseif ($cbillCycle['billCycle']==3) {
        $date    = $d["date"];
        $dueDate = date('Y-m-d', strtotime('+3 month', strtotime($date)));
    }elseif ($cbillCycle['billCycle']==4) {
        $date    = $d["date"];
        $dueDate = date('Y-m-d', strtotime('+12 month', strtotime($date)));
    }elseif ($cbillCycle['billCycle']==5) {
        $dueDate = 'One Time Payment';
    }
    if($d['status'] == 0){
        $status     = "Unpaid";
    }if($d['status'] == 1) {
        $status     = "Pending";
    }if($d['status'] == 2) {
        $status     = "Paid";
    }
	
    $array      = array();
    $editLink   = "add-new-items.php?customer=".$d['buyer']."&invoice_no=".$d['identifier'];
    $pdfLink    = "invoice_pdf.php?invoice_no=".$d['identifier'];
    //$editLink = "add-new-items.php?customer=&invoice_no=".$d['identifier'];
    if(isset($_GET['status']) && $_GET['status']==0){
    $array[]    = "<input type='checkbox' class='checkItem' style='margin-left: 10px;' value='".$d['identifier']."'>";
    }
    $array[]    = $requestData['start']+$countVal;
    $countVal++;
    $array[]    = $d["comname"];
    $array[]    = $d["buyerName"];
    $array[]    = $d['invoice_no'];
    $array[]    = $d["date"];
    $array[]    = $dueDate;
	$array[]    = $status;
	$array[]    = $d["total_amount"];
	$array[]    = $d["createdOn"];
    $array[]    = '<div class="actions">
                    <div class="btn-group">
                        <a class="btn btn-primary btn-sm uppercase sbold" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> Actions
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu pull-right btnActionCustom" id="sDrop">
                            <li>
                                <a title="View Details" href="javascript:;" class="CustumA" data-id="'.$d['identifier'].'"  >View</a>
                            </li>
                            <li>
                                <a title="Edit" href="'.$editLink.'">Edit</a>
                            </li>
                                '.$statusLink.'                           
                            <li>
                                <a title="Generate PDF" href="'.$pdfLink.'">Generate PDF</a>
                            </li>
                            <li>
                                <a title="New Invoice" href="javascript:;"  class="NewInvoice" data-id="'.$d['identifier'].'">Create New Invoice</a>
                            </li>
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
