<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");

include '../excel/Classes/PHPExcel/IOFactory.php';

if(isset($_POST['rowNumber'])){

    $purchaseID = $_SESSION['formData']["purchaseIdentifier"];
    $purpose = $_SESSION['formData']["purpose"];
    $datein = $_SESSION['formData']["datein"];
    $challanid = $_SESSION['formData']["challanid"];
    $courierno = $_SESSION['formData']["courierno"];
    $inmode = $_SESSION['formData']["inmode"];
    $ewayid = $_SESSION['formData']["ewayid"];
    $purchaseType = $_SESSION['formData']["purchaseType"];

    $rowNumber = $_POST['rowNumber'];
    $filepath=$_SESSION["upload"]['filepath'];
    $itemType=$_SESSION["upload"]['type'];
    $PID=$_SESSION["upload"]['PID'];
    $returnData = array("rowNumber"=>"", "end"=>0, "error"=>"", "rowData"=>"");
   try {
        $inputFileType = PHPExcel_IOFactory::identify($filepath);
    	$objPHPExcel = PHPExcel_IOFactory::load($filepath);
    	$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
        $processRowNumber = $rowNumber;
         if(isset($sheetData[$processRowNumber])){
            $returnData['rowNumber'] = $rowNumber+1;
            $returnData['end'] = 0;
            $row = $sheetData[$processRowNumber];

            //GENERATE ITEM ID
            $ItemID=generateItemID();

            //GET ITEM CLASS CODE
            $itemClassCode = getItemClassCode($itemType);

            //inventory array
            $contentArray2 = array();
            // $contentArray2['Location'] = $LocationCode['0'];
            $contentArray2['ItemID'] = $ItemID;
            $contentArray2['ItemClass'] = $itemClassCode;
            $contentArray2['DateIN'] = $datein;
            $contentArray2['PurchaseID']=$PID;
            $contentArray2['DeliveryChallanID']=$challanid;
            $contentArray2["CourierNoIn"] = $courierno;
            $contentArray2["InMode"] = $inmode;
            $contentArray2["EwayBillID"] = $ewayid;
            $contentArray2["Purpose"] = $purpose;

            //check for empty row
            if(!empty($row["A"])){

                //item type is sim for inventory
                if($itemType=='ITEM_SIM'){
                    $contentArray = getSIMInsertArray($row);

                    //CHECK ERROR MESSAGE
                    if(!empty($contentArray["error"])){
                        $returnData["error"] = $contentArray["error"];
                        $returnData["rowData"] = $row;
                        echo json_encode($returnData);
                        die();
                    }else{
                        $contentArray2['Location'] = $contentArray["simData"]['locationID'];
                        $contentArray["simData"]["itemID"] = $ItemID;
                        addData("tblSim", $contentArray["simData"]);
                    }
                }
                //ITEM TYPE IS OFFICE ACCESSORY
                else if($itemType=='ITEM_OFFICE'){
                    $contentArray = getOfficeInsertArray($row);

                    //CHECK ERROR MESSAGE
                    if(!empty($contentArray["error"])){
                        $returnData["error"] = $contentArray["error"];
                        $returnData["rowData"] = $row;
                        echo json_encode($returnData);
                        die();
                    }else{
                        $contentArray2['Location'] = $contentArray["officeData"]['locationID'];
                        $contentArray["officeData"]["itemID"] = $ItemID;
                        addData("tblOfficeItem", $contentArray["officeData"]);
                    }
                }
                //ITEM TYPE IS HARDWARE UNIT
                else if($itemType == "ITEM_HARDWARE"){
                    $contentArray = getHardwareInsertArray($row);

                    //CHECK ERROR MESSAGE
                    if(!empty($contentArray["error"])){
                        $returnData["error"] = $contentArray["error"];
                        $returnData["rowData"] = $row;
                        echo json_encode($returnData);
                        die();
                    }else{
                        $contentArray2['Location'] = $contentArray["hardwareData"]['locationID'];
                        $contentArray["hardwareData"]["itemID"] = $ItemID;
                        addData("tblHardware", $contentArray["hardwareData"]);
                    }
                }

                //ITEM TYPE IS MACHINE AND TOOLS
                else if($itemType == "ITEM_MNT"){
                    $contentArray = getMNTInsertArray($row);

                    //CHECK ERROR MESSAGE
                    if(!empty($contentArray["error"])){
                        $returnData["error"] = $contentArray["error"];
                        $returnData["rowData"] = $row;
                        echo json_encode($returnData);
                        die();
                    }else{
                        $contentArray2['Location'] = $contentArray["mntData"]['locationID'];
                        $contentArray["mntData"]["itemID"] = $ItemID;
                        addData("tblMachine", $contentArray["mntData"]);
                    }
                }
                addData("tblInventory", $contentArray2);
            }else{
                $returnData['end'] = "1";
                $returnData['rowNumber'] = $rowNumber+1;

                //UPDATE PURCHASE STATUS
                $contentArray = array();
                $contentArray["Status"] = "414";
                updateData("tblPurchase", $contentArray, "identifier", $PID);

                $currentStatus = $_SESSION['upload']["currentStatus"];
                $contentArray = array("Previous_Status"=>$currentStatus, "Current_Status"=>"414");
                generateAudit("PUR_STATUS", $PID, $contentArray, $notifyAction="");

                echo json_encode($returnData);
                die();
            }
        }
        else{
            $returnData['end'] = "1";
            $returnData['rowNumber'] = $rowNumber+1;


            //UPDATE PURCHASE STATUS
            $contentArray = array();
            $contentArray["Status"] = "414";
            updateData("tblPurchase", $contentArray, "identifier", $PID);

            $currentStatus = $_SESSION['upload']["currentStatus"];
            $contentArray = array("Previous_Status"=>$currentStatus, "Current_Status"=>"414");
            generateAudit("PUR_STATUS", $PID, $contentArray, $notifyAction="");

            echo json_encode($returnData);
            die();

        }
    } catch(Exception $e) {
    	die('Error loading file "'.pathinfo($filepath,PATHINFO_BASENAME).'": '.$e->getMessage());
    }

    if($returnData["end"] == "1" || $rowNumber == $_SESSION['upload']['totalRows']){
        //UPDATE PURCHASE STATUS
        $contentArray = array();
        $contentArray["Status"] = "414";

        updateData("tblPurchase", $contentArray, "identifier", $PID);

        $currentStatus = $_SESSION['upload']["currentStatus"];
        $contentArray = array("Previous_Status"=>$currentStatus, "Current_Status"=>"414");
        generateAudit("PUR_STATUS", $PID, $contentArray, $notifyAction="");
    }

    echo json_encode($returnData);
}
?>
