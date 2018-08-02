<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");
include("../includes/check_permission.php");

$pageStart = $_GET['start'];
$societyId = $_SESSION['user']['society_id'];
$table = <<<EOT
 (
  SELECT E.identifier, E.POReference, E.PurchaseClassification, L.name as Supplier, E.uploadPO, E.Quantity, E.DeliveryChallan,E.DeliveryLocation, E.Status,E.createdOn FROM tblPurchase E join tblSupplier L on L.identifier=E.SupplierID ORDER BY E.identifier DESC

 ) temp
EOT;

$primaryKey = 'identifier';

$columns = array(
   array( 'db' => 'identifier','dt' => 0 ),
   array( 'db' => 'POReference',        'dt' => 1 ),
   array( 'db' => 'PurchaseClassification',   'dt' => 2 ),
   array( 'db' => 'Quantity', 'dt' => 3 ),
   array( 'db' => 'DeliveryChallan', 'dt' => 4),
   array( 'db' => 'Supplier', 'dt' => 5 ),
   array( 'db' => 'Status', 'dt' => 6 ),
   array( 'db' => 'createdOn', 'dt' => 7 ),
   array( 'db' => 'Status', 'dt'=>'pStatus'),
   array( 'db' => 'uploadPO', 'dt'=>'PONo')
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
       $pStatus = $json['data'][$key]["pStatus"];
       $editLink = "add-purchase.php?id=".$json['data'][$key][0];
       $str = '';
	    $json['data'][$key][1] = "<a href='upload/popdf/".$json['data'][$key]['PONo']."' target='_blank'>".$json['data'][$key][1]."</a>";
       $json['data'][$key][2] =    $PURCHASECLASS[$json['data'][$key][2]][0];
       $actions = '<div class="actions">
                        <div class="btn-group">
                            <a class="btn btn-primary btn-sm uppercase sbold" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> Mark
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu pull-right">
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
                $otherActions .= '<li><a href="javascript:void(0);" current-status="'.$json['data'][$key][6].'" purchase-id="'.$json['data'][$key][0].'" data-id="'.$changeStatuses.'" class="actionStatus" data-po="'.$json['data'][$key][1].'">'.$PURCHASESTATUS["$changeStatuses"][0].'</a></li>';
            }
        }

        if(!empty($otherActions)){
            $actions = str_replace("[ACTIONS]", $otherActions, $actions);
        }else{
            $actions = "";
        }

        $json['data'][$key][6] = $PURCHASESTATUS[$json['data'][$key][6]][0];

        $json['data'][$key][8] = $actions;
        $json['data'][$key][0] = $counter;
        $counter++;
   }
}
echo json_encode($json);
?>
