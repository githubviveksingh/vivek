<?php 
    include("includes/check_session.php");
    include("includes/header.php");
    include("PHPMailer/class.phpmailer.php");
    $table="tblOfficeItem";
    $mcid = isset($_GET['mcid'])?intval($_GET['mcid']):"0";
    if($mcid!=0){

        $empData                = getInventoryDetails($mcid,$table);
        $_SESSION['oldData']    = $empData;
        
        $empId          = $_GET['empID'];
        $contentArray   = array();
        $contentArray["alote_status"] = 2;

        // get assign office inventory information---------------------------- 
        $officeInfo    = getOfficeInventoryInfo($mcid);

        // get category name-----------------------------------
        $category = $officeInfo[0]['productCat'];
        $categoryName='';
        foreach($PRODUCTCAT as $key=>$value){
            if($key == $category){
                $categoryName=$value[1];
            }
        }

        $itemName       = $officeInfo[0]['itemName'];
        $description    = $officeInfo[0]['description'];
        
        // get trigger user data--------------------------------------
        $triggerInfo    = getTriggerInfo($mcid);
        $empID          = $triggerInfo[0]['triggeredBy'];

        // update alotted status-------------------------------------- 
        $updateOffice  = updateData($table, $contentArray, "identifier", $mcid);
        $auditData      = array("old_values"=>$_SESSION['oldData'], "new_values"=>$contentArray);
        $auditID        = generateAudit("OFC_REJT", $mcid, $auditData, "");
        if($updateOffice){

            /* Send Mail */
            $mailbox        = file_get_contents("partial-forms/alotted-office-item-reject.html");
            $accept         = $_SESSION["user"]["name"];
            $empDetails     = getEmpDetails($empID);
            
            $empemail       = $empDetails['email'];
            $mailbox        = str_replace("[ITEMNAME]" , $itemName , $mailbox);
            $mailbox        = str_replace("[DESCRIPTION]" , $description , $mailbox);
            $mailbox        = str_replace("[CATEGORYNAME]" , $categoryName , $mailbox);
            $mailbox        = str_replace("[EMPNAME]" , $accept , $mailbox);

            $TITLE          = 'allotted Office Inventory Rejected';
            $mailbox        = str_replace("[TITLE]" , $TITLE , $mailbox);
            
            $subject        = "Process Portal- allotted office inventory rejected";
            $mailSender     = mailSend($empemail,$subject,$mailbox);

            $_SESSION["success"] = "Office Inventory Successfully Rejected.";

             header("Location: view-employee-details.php?empid=".$empId);
            exit();
        }
    }
?>