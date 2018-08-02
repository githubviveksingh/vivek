<?php 
    include("includes/check_session.php");
    include("includes/header.php");
    include("PHPMailer/class.phpmailer.php");
    $table="tblMachine";
    $mcid = isset($_GET['mcid'])?intval($_GET['mcid']):"0";
    if($mcid!=0){

        $empId          = $_GET['empID'];
        $contentArray   = array();
        $contentArray["alote_status"] = 1;

        // get assign machine information---------------------------- 
        $machineInfo    = getMachineInfo($mcid);

        // get classification name-----------------------------------
        $classification     = $machineInfo[0]['classification'];
        $classificationName = '';
        foreach($MACHINEANDTOOLCLASS as $key=>$value){
            if($key == $classification){
                $classificationName = $value[1];
            } 
        }

        $name           = $machineInfo[0]['name'];
        $description    = $machineInfo[0]['description'];
        
        // get trigger user data--------------------------------------
        $triggerInfo    = getTriggerInfo($mcid);
        $empID          = $triggerInfo[0]['triggeredBy'];

        // update alotted status-------------------------------------- 
        $updateMachine  = updateData($table, $contentArray, "identifier", $mcid);
        if($updateMachine){

            /* Send Mail */
            $mailbox        = file_get_contents("partial-forms/alotted-machine-tool-accept.html");
            $accept         = $_SESSION["user"]["name"];
            $empDetails     = getEmpDetails($empID);
            
            $empemail       = $empDetails['email'];
            $mailbox        = str_replace("[NAME]" , $name , $mailbox);
            $mailbox        = str_replace("[DESCRIPTION]" , $description , $mailbox);
            $mailbox        = str_replace("[CLASSIFICATION]" , $classificationName , $mailbox);
            $mailbox        = str_replace("[EMPNAME]" , $accept , $mailbox);

            $TITLE          = 'allotted Machine Accepted';
            $mailbox        = str_replace("[TITLE]" , $TITLE , $mailbox);
            
            $subject        = "Process Portal- allotted machime-tools accepted";
            $mailSender     = mailSend($empemail,$subject,$mailbox);

            $_SESSION["success"] = "Machine Tools Successfully Accepted.";

             header("Location: view-employee-details.php?empid=".$empId);
            exit();
        }
    }
?>