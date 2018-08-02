<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");

$pageStart = $_GET['start'];
$societyId = $_SESSION['user']['society_id'];
$table = <<<EOT
 (  
   SELECT E.identifier,E.SupportID,E.Location, L.name as Employee, E.CreateDate,E.Status FROM tblTechnicianScheduling E join tblEmployee L on L.identifier=E.EmployeeID
 ) temp
EOT;

$primaryKey = 'identifier';

$columns = array(
	array( 'db' => 'identifier','dt' => 0 ),
	array( 'db' => 'SupportID',   'dt' => 1 ),
	array( 'db' => 'Employee',   'dt' => 2 ),
	array( 'db' => 'CreateDate', 'dt' => 3 ),
	array( 'db' => 'Location', 'dt' => 4),
	array( 'db' => 'Status', 'dt' => 5 )	
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
		$editLink = "assign-technician.php?empid=".$res[0];
		$assignLink = "assign-technician.php?supportID=".$res[0];
		$json['data'][$key][1]=getSupportSNo($res[1]);
		$json['data'][$key][5]=$SUPPORTSTS[$json['data'][$key][5]][1].' ['.$res[5].']';
		$json['data'][$key][6] = "<a title='Edit Technician Scheduling Details' href='".$editLink."' class='btn btn-default'><span class='fa fa-pencil'></span></i>";
		$json['data'][$key][0] = $counter;
		$counter++;
   }
}
echo json_encode($json);
?>
