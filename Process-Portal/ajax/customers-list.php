<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");
include("../includes/check_permission.php");
$pageStart = $_GET['start'];
$societyId = $_SESSION['user']['society_id'];
$table = <<<EOT
 (  
   SELECT E.identifier, E.name, E.email, L.name as emplyoee, E.phone,E.accountID FROM tblCustomer E join tblEmployee L on L.identifier=E.employeeID
 ) temp
EOT;

$primaryKey = 'identifier';

$columns = array(
   array( 'db' => 'identifier','dt' => 0 ),
   array( 'db' => 'name',   'dt' => 1 ),
   array( 'db' => 'email', 'dt' => 2 ),
   array( 'db' => 'phone', 'dt' => 3),
   array( 'db' => 'emplyoee', 'dt' => 4 ),
   array( 'db' => 'accountID', 'dt' => 5 )
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
      $editLink = "add-customer.php?empid=".$res[0];
	   $addLink = "add-customer-items.php?empid=".$res[5];
	   $invLink = "upload-invoices.php?customer=".$res[0];
      $json['data'][$key][6] = "<a title='Edit Customer Details' href='".$editLink."' class='btn btn-default'><span class='fa fa-pencil'></span></i></a>
	  <a title='Add Customer Item' href='".$addLink."' class='btn btn-default'><span class='fa fa-link'></span></i></a><a title='Invoice Details' href='".$invLink."' class='btn btn-default'><span class='fa fa-list'></span></i></a>";
      $json['data'][$key][0] = $counter;
      $counter++;
   }
}
echo json_encode($json);
?>
