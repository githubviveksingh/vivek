<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");
include("../includes/check_permission.php");
$pageStart = $_GET['start'];
$societyId = $_SESSION['user']['society_id'];
$table = <<<EOT
 (  
   SELECT identifier, com_name, com_email,com_pan,com_phone,com_gst,com_bankname FROM tblCompany
 ) temp
EOT;

$primaryKey = 'identifier';
$columns = array(
   array( 'db' => 'identifier','dt' => 0 ),
   array( 'db' => 'com_name',   'dt' => 1 ),
   array( 'db' => 'com_email', 'dt' => 2 ),
   array( 'db' => 'com_pan', 'dt' => 3),
   array( 'db' => 'com_phone', 'dt' => 4 ),
   array( 'db' => 'com_gst', 'dt' => 5 ),
    array( 'db' => 'com_bankname', 'dt' => 6 )
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
       $editLink = "add-company.php?empid=".$res[0];
      $json['data'][$key][7] = "<a title='Edit Customer Details' href='".$editLink."' class='btn btn-default'><span class='fa fa-pencil'></span></i>";
      $json['data'][$key][0] = $counter;
      $counter++;
   }
}
echo json_encode($json);
?>
