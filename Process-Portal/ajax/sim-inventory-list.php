<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");

$pageStart = $_GET['start'];
$societyId = $_SESSION['user']['society_id'];
$table = <<<EOT
 (
  SELECT * from tblSim
 ) temp
EOT;

$primaryKey = 'identifier';

$columns = array(
   array( 'db' => 'identifier','dt' => 0 ),
   array( 'db' => 'serviceProvider',   'dt' => 1 ),
   array( 'db' => 'MSISDN', 'dt' => 2 ),
   array( 'db' => 'BillPlan', 'dt' => 3),
   array( 'db' => 'statusCode', 'dt' => 4 )
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
       $editLink = "add-supplier.php?empid=".$res[0];
        $str = '';
        $json['data'][$key][1] = $SIMSERPROVIDER[$json['data'][$key][1]][1];
       $json['data'][$key][4] = $STATUSCODE[$json['data'][$key][4]][1];
      $json['data'][$key][5] = "<a title='Edit Supplier Details' href='".$editLink."' class='btn btn-default'><span class='fa fa-pencil'></span></i>";
      $json['data'][$key][0] = $counter;
      $counter++;
   }
}
echo json_encode($json);
?>
