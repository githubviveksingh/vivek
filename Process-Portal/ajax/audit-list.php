<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");
include("../includes/check_permission.php");
$pageStart = $_GET['start'];
$societyId = $_SESSION['user']['society_id'];
$table = <<<EOT
 (
    SELECT A.identifier, A.auditCode, A.targetID, A.data, A.notifyAction, A.createdOn, E.name as triggeredName FROM tblAudit A JOIN tblEmployee E on E.identifier=A.triggeredBy
 ) temp
EOT;

$primaryKey = 'identifier';

$columns = array(
   array( 'db' => 'identifier','dt' => 0 ),
   array( 'db' => 'auditCode', 'dt' => 1 ),
   array( 'db' => 'auditCode', 'dt' => "auditCode" ),
   array( 'db' => 'targetID',   'dt' => 2 ),
   array( 'db' => 'data', 'dt' => "data" ),
   array( 'db' => 'notifyAction', 'dt' => 3),
   array( 'db' => 'triggeredName', 'dt' => 4 ),
   array( 'db' => 'createdOn', 'dt' => 5 )
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
       $jsonData = json_decode($json["data"][$key]["data"]);
       $json["data"][$key][1] = $AUDITCODES[$json["data"][$key][1]];

       if(strpos($json["data"][$key]["auditCode"], "EMP_") !== false){
           if(isset($jsonData->new_values)){
               $json["data"][$key][2] = $jsonData->new_values->name;
           }else{
               $json["data"][$key][2] = $jsonData->name;
           }
       }
       $json['data'][$key][0] = $counter;
       $counter++;
   }
}
echo json_encode($json);
?>
