<?php 
    include("includes/check_session.php");
    include("includes/header.php");
    include("PHPMailer/class.phpmailer.php");
    $table="tblHardware";
    $mcid = isset($_GET['mcid'])?intval($_GET['mcid']):"0";
    if($mcid!=0){

        $empData                = getInventoryDetails($mcid,$table);
        $_SESSION['oldData']    = $empData;
        
        $empId          = $_GET['empID'];
        $contentArray   = array();
        $contentArray["alote_status"] = 2;

        // get assign hardware information---------------------------- 
        $hrdInfo    = getHardwareInfo($mcid);

        // get provide name------------------------

        $category = $hrdInfo[0]['productCat'];
        $categoryName='';
        foreach($PRODUCTCAT as $key=>$value){
                    if($key == $category){
                        $categoryName=$value[1];
                    }
                }
        
        $imeiName   = $hrdInfo[0]['IMEI'];
        $model      = $hrdInfo[0]['model'];
        
        // get trigger user data--------------------------------------
        $triggerInfo    = getTriggerInfo($mcid);
        $empID          = $triggerInfo[0]['triggeredBy'];

        // update alotted status-------------------------------------- 
        $updateHrd = updateData($table, $contentArray, "identifier", $mcid);
        $auditData      = array("old_values"=>$_SESSION['oldData'], "new_values"=>$contentArray);
        $auditID        = generateAudit("HRD_REJT", $mcid, $auditData, "");
        if($updateHrd){

            /* Send Mail */
            $mailbox        = file_get_contents("partial-forms/alotted-hardware-item-reject.html");
            $accept         = $_SESSION["user"]["name"];
            $empDetails     = getEmpDetails($empID);
            
            $empemail       = $empDetails['email'];
            $mailbox        = str_replace("[IMEINAME]" , $imeiName , $mailbox);
            $mailbox        = str_replace("[MODEL]" , $model , $mailbox);
            $mailbox        = str_replace("[CATEGORYNAME]" , $categoryName , $mailbox);
            $mailbox        = str_replace("[EMPNAME]" , $accept , $mailbox);

            $TITLE          = 'Allotted Hardware Rejected';
            $mailbox        = str_replace("[TITLE]" , $TITLE , $mailbox);
            
            $subject        = "Process Portal- allotted hardware rejected";
            $mailSender     = mailSend($empemail,$subject,$mailbox);

            $_SESSION["success"] = "Allotted Hardware Successfully Rejected.";

             header("Location: view-employee-details.php?empid=".$empId);
            exit();
        }
    }
?>