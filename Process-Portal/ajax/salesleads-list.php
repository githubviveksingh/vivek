<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");

$pageStart = $_GET['start'];
$societyId = $_SESSION['user']['society_id'];
$table = <<<EOT
 (
   SELECT E.identifier,E.Mode, L.name as customer, E.Classification,E.Status, E.Name,E.Location,E.Email,E.Phone FROM tblSaleLead E join tblEmployee L on L.identifier=E.EmployeeID
 ) temp
EOT;
$primaryKey = 'identifier';
$columns = array(
	array( 'db' => 'identifier','dt' => 0 ),
	array( 'db' => 'customer',   'dt' => 1 ),
	array( 'db' => 'Mode', 'dt' => 2 ),
	array( 'db' => 'Classification', 'dt' => 3 ),
	array( 'db' => 'Name', 'dt' => 4),
	array( 'db' => 'Location', 'dt' => a ),
	array( 'db' => 'Email', 'dt' => 5 ),
	array( 'db' => 'Phone', 'dt' => 6 ),
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
		$editLink = "add-salelead.php?empid=".$res[0];
		$json['data'][$key][3]= $ITEMCLASS[$json['data'][$key][3]][1].' ['.$res[3].']';
		$json['data'][$key][7]= $SALESSTS[$json['data'][$key][7]][1].' ['.$res[7].']';
	    $json['data'][$key][8] ="<a title='Edit Supplier Details' href='".$editLink."' class='btn btn-default'><span class='fa fa-pencil'></span></i>";
		$json['data'][$key][0] = $counter;
		$counter++;
   }
}
echo json_encode($json);
?>
