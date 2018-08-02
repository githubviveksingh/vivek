<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");

include '../excel/Classes/PHPExcel/IOFactory.php';

if(isset($_POST['rowNumber'])){
    $rowNumber = $_POST['rowNumber'];
    $filepath=$_SESSION["upload"]['filepath'];
    $itemType=$_SESSION["upload"]['type'];
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
			
            //check for empty row
            if(!empty($row["A"])){  
			    $name=$row['A'];
                $checkRFIDDuplicacy = checkDuplicacy("tblCustomer", "Name", $name, "identifier", "0");
						if($checkRFIDDuplicacy){
						$returnData["error"] = "Duplicate Customer";
						$returnData["rowData"] = $row;
						}else{
						$contentArray['Name'] = $row['A'];
						
						if($email!=""){ $contentArray["email"] = $row['B']; }
						if($row['C']!=""){ $contentArray["phone"] = $row['C']; }
						if($row['D']!=""){ $contentArray["GST"] = $row['D']; }
						if($row['E']!=""){ $contentArray["VAT"] = $row['E']; }		
                        if($row['F']!=""){ $contentArray["ST"] = $row['F']; }
                        if($row['G']!=""){ $contentArray["PAN"] = $row['G']; }
						$billCycle=explode("-", $row["H"]);
						$contentArray["billCycle"] = $billCycle[0];
						if($row['I']!=""){ $contentArray["address"] = $row['I']; }
						if($row['J']!=""){ $contentArray["employeeID"] = $row['J']; }
						addData("tblCustomer", $contentArray);
						}
            }else{
                $returnData['end'] = "1";
                $returnData['rowNumber'] = $rowNumber+1;
				
                echo json_encode($returnData);
                die();
            }
        }
        else{
            $returnData['end'] = "1";
            $returnData['rowNumber'] = $rowNumber+1;
			
            echo json_encode($returnData);
            die();

        }
    } catch(Exception $e) {
    	die('Error loading file "'.pathinfo($filepath,PATHINFO_BASENAME).'": '.$e->getMessage());
    }

    echo json_encode($returnData);
}
?>
