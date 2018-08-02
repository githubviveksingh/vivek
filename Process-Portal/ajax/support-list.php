<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");

$pageStart = $_GET['start'];
$societyId = $_SESSION['user']['society_id'];
$table = <<<EOT
 (
   SELECT TIMESTAMPDIFF(HOUR,E.DateofReport, NOW()) as timediff, E.identifier,E.itemID, E.itemCategory, L.name as customer,T.name as tech, E.DateofReport,E.Status, E.DateOfResolution,E.ServiceReportNo,E.technicianID FROM tblSupport E join tblCustomer L on L.identifier=E.CustomerID left join tblEmployee T on T.identifier=E.technicianID
 ) temp
EOT;

$primaryKey = 'identifier';
$columns = array(
	array( 'db' => 'identifier','dt' => 0 ),
	array( 'db' => 'ServiceReportNo',   'dt' => 1 ),
	array( 'db' => 'itemID',   'dt' => 2 ),
	array( 'db' => 'DateofReport', 'dt' => 3 ),
	array( 'db' => 'customer', 'dt' => 4),
	array( 'db' => 'itemCategory', 'dt' => 'a' ),
	array( 'db' => 'timediff', 'dt' => 'b' ),
	array( 'db' => 'tech', 'dt' => 5 ),
	array( 'db' => 'DateOfResolution', 'dt' => 6 ),
	array( 'db' => 'Status', 'dt' => 7 )
);

$sql_details = array(
   'user' => Config::read('db.user'),
   'pass' => Config::read('db.password'),
   'db'   => Config::read('db.basename'),
   'host' => Config::read('db.host')
);
require( 'ssp.class.php' );
$json = SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns );

if(count($json['data'])){
   $counter = $pageStart+1;
   foreach($json['data'] as $key => $res){
	  // $Empname=getEmpDetails($res[5]);
		$editLink = "add-support.php?empid=".$res[0];
		$assignLink = "assign-technician.php?supportID=".$res[0];
		if($res[6]<=0){
			$DateOfResolution="";
		}else{
			$DateOfResolution=$res[6];
		}
		// To check Hours to support
		if($res[b] > '48' && $res[7]!='433'){
			$json['data'][$key][7]='<a class="btn yellow">'.$SUPPORTSTS[$json['data'][$key][7]][1].' ['.$res[7].']</b>';
		}else{
			$json['data'][$key][7]=$SUPPORTSTS[$json['data'][$key][7]][1].' ['.$res[7].']';
		}
		$json['data'][$key][2]= getInventoryShortDetails($res[2],$res['a']).' ['.$res[2].']';
		//$json['data'][$key][5]= $Empname['name'].' ['.$res[5].']';
		$json['data'][$key][6]= $DateOfResolution;
	    $json['data'][$key][8] ='<select class="selectbox form-control" onchange="location = this.value;">
								<option selected disabled>Select Option</option>
								<option value="'.$editLink.'">Edit</option>
								<option value="'.$assignLink.'">Assign Technician</option>
								</select>';
		$json['data'][$key][0] = $counter;
		$counter++;
   }
}
echo json_encode($json);
?>