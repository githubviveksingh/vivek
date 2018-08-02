<?php 
    include("includes/check_session.php");
    include("includes/header.php");
    include("PHPMailer/class.phpmailer.php");
    $table="tblOfficeItem";
    $mcid = isset($_GET['mcid'])?intval($_GET['mcid']):"0";
    if($mcid!=0){

        $empId          = $_GET['empID'];
        $contentArray   = array();
        $contentArray["alote_status"] = 1;

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
        if($updateOffice){

            /* Send Mail */
            $mailbox        = file_get_contents("partial-forms/alotted-office-item-accept.html");
            $accept         = $_SESSION["user"]["name"];
            $empDetails     = getEmpDetails($empID);
            
            $empemail       = $empDetails['email'];
            $mailbox        = str_replace("[ITEMNAME]" , $itemName , $mailbox);
            $mailbox        = str_replace("[DESCRIPTION]" , $description , $mailbox);
            $mailbox        = str_replace("[CATEGORYNAME]" , $categoryName , $mailbox);
            $mailbox        = str_replace("[EMPNAME]" , $accept , $mailbox);
            $TITLE          = 'allotted Office Inventory Accepted';
            $mailbox        = str_replace("[TITLE]" , $TITLE , $mailbox);
            
            $subject        = "Process Portal- allotted office inventory accepted";
            $mailSender     = mailSend($empemail,$subject,$mailbox);

            $_SESSION["success"] = "Office Inventory Successfully Accepted.";

             header("Location: view-employee-details.php?empid=".$empId);
            exit();
        }
    }
?>