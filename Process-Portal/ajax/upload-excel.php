<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");

include '../excel/Classes/PHPExcel/IOFactory.php';


if(isset($_POST['purchaseType'])){
    $return = array("error"=>"", "totalRows"=>"0");
    $itemType = $_POST['purchaseType'];
    $_SESSION['formData'] = $_POST;

    if(!isset($$itemType)){
        $return["error"] = "Upload File Type Does not exist.";
        echo json_encode($return);
        die();
    }else{
        //CHECK UPLOADED FILE IS OF EXCEL TYPE
        $fileInfo = pathinfo($_FILES['file']['name']);
        if(strtolower($fileInfo["extension"]) != "xlsx"){
            $return["error"] = "Please Upload Excel File.";
            echo json_encode($return);
            die();
        }

        //UPLOAD FILE TO SERVER
        $filePath = "..".$DS.$UPLOADFOLDER.$DS.time()."_".$_FILES['file']['name'];
        $uploadFlag = move_uploaded_file($_FILES['file']["tmp_name"], $filePath);
        if($uploadFlag !== true){
            $return["error"] = "File could not be uploaded. Please try again later.";
            echo json_encode($return);
            die();
        }

        //CHECK EXCEL SHEET COLUMN WITH GIVEN CONSTANT COLUMNS
        $objPHPExcel = PHPExcel_IOFactory::load($filePath);
        $highestRow = $objPHPExcel->getActiveSheet()->getHighestRow();
        $sheetData = $objPHPExcel->getActiveSheet()->getRowIterator(1)->current();

        $return["totalRows"] = $highestRow;

        $cellIterator = $sheetData->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);

        //COLLECTING HEADER COLUMN NAME FROM EXCEL
        $colArray = array();
        foreach ($cellIterator as $cell) {
            array_push($colArray, trim($cell->getValue()));
        }

        //CHECKING REQUIRED COLUMNS FOR UPLOAD FILE TYPE
        foreach($$itemType as $colName){
            if(!in_array($colName, $colArray)){
                $return["error"] = 'Column "'.$colName.'" does not exist. Please add this column in file before uploading.';
                echo json_encode($return);
                die();
            }
        }
        $_SESSION["upload"]['filepath'] = $filePath;
        $_SESSION["upload"]['type'] = $itemType;
        $_SESSION["upload"]['PID'] = $_POST['purchaseIdentifier'];
        $_SESSION['upload']["currentStatus"] = $_POST['current_status'];
        $_SESSION['upload']['totalRows'] = $highestRow;
        echo json_encode($return);
    }
}
?>
