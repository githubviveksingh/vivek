<?php 
    include("includes/check_session.php");
    include("includes/header.php");
    include("PHPMailer/class.phpmailer.php");
    $table="tblSim";
    $mcid = isset($_GET['mcid'])?intval($_GET['mcid']):"0";
    if($mcid!=0){

        $empData                = getInventoryDetails($mcid,$table);
        $_SESSION['oldData']    = $empData;
        $empId          = $_GET['empID'];
        $contentArray   = array();
        $contentArray["alote_status"] = 2;

        // get assign sim information---------------------------- 
        $simInfo    = getSimInfo($mcid);

        // get provide name------------------------
        $serviceProvider =  $simInfo[0]['serviceProvider'];
        $providerName='';
        foreach($SIMSERPROVIDER as $key=>$value){
            if($key == $serviceProvider){
                $providerName = $value[1];
            } 
        }
        
        $imeiName   = $simInfo[0]['IMEI'];
        $msidn      = $simInfo[0]['MSISDN'];
        $apn        = $simInfo[0]['APNValue'];
        $billpan    = $simInfo[0]['BillPlan'];
        
        // get trigger user data--------------------------------------
        $triggerInfo    = getTriggerInfo($mcid);
        $empID          = $triggerInfo[0]['triggeredBy'];

        // update alotted status-------------------------------------- 
        $updateSim      = updateData($table, $contentArray, "identifier", $mcid);
        $auditData      = array("old_values"=>$_SESSION['oldData'], "new_values"=>$contentArray);
        $auditID        = generateAudit("SIM_REJT", $mcid, $auditData, "");
        if($updateSim){

            /* Send Mail */
            $mailbox        = file_get_contents("partial-forms/alotted-sim-reject.html");
            $accept         = $_SESSION["user"]["name"];
            $empDetails     = getEmpDetails($empID);
            
            $empemail       = $empDetails['email'];
            $mailbox        = str_replace("[IMEINAME]" , $imeiName , $mailbox);
            $mailbox        = str_replace("[PROVIDERNAME]" , $providerName , $mailbox);
            $mailbox        = str_replace("[MSISDNAME]" , $msidn , $mailbox);
            $mailbox        = str_replace("[APNAME]" , $apn , $mailbox);
            $mailbox        = str_replace("[BILLPAN]" , $billpan , $mailbox);
            $mailbox        = str_replace("[EMPNAME]" , $accept , $mailbox);

            $TITLE          = 'allotted Sim Rejected';
            $mailbox        = str_replace("[TITLE]" , $TITLE , $mailbox);
            
            $subject        = "Process Portal- allotted machime-tools accepted";
            $mailSender     = mailSend($empemail,$subject,$mailbox);

            $_SESSION["success"] = "Allotted Sim Successfully Rejected.";

             header("Location: view-employee-details.php?empid=".$empId);
            exit();
        }
    }
?>