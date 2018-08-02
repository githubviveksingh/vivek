<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");

if(isset($_POST['purchaseType'])){

    $return = array();

    $filePath   = "..".$DS.$UPLOADFOLDER.$DS.'xml'.$DS.$_FILES['file']['name'];
    $file       = $_FILES['file']['name'];

    $fileInfo = pathinfo($_FILES['file']['name']);
    if(strtolower($fileInfo["extension"]) != "xml"){
        $return["error"] = "Please Upload only xml File.";
        echo json_encode($return);
        die();
    }

   /* if (file_exists($filePath)) {
        $return["error"] = "This invoice already upladed. Upload another invoice XML file";
        echo json_encode($return);
        die();

    }else{
*/
    $uploadFlag = move_uploaded_file($_FILES['file']["tmp_name"], $filePath);

    if($uploadFlag){
        $xml            = simplexml_load_file('../upload/xml/'.$file);
        if(isset($xml) && $xml!=''){
           
        $invoiceArray   = array();
        foreach($xml->BODY->IMPORTDATA as $invoiceData){
            $companyName    = (string)$invoiceData->REQUESTDESC->STATICVARIABLES->SVCURRENTCOMPANY;  
            $i              = 0;
            foreach($invoiceData->REQUESTDATA->TALLYMESSAGE as $vocher)
            {
                $totalAmount;
                $taxData    = '';
                $j          = 0;

                $date       = (string)$vocher->VOUCHER->DATE;
                $year       = substr($date, 0,4);
                $month      = substr($date, -4,-2);
                $day        = substr($date, 6,7);
                $date1      = $year.'-'.$month.'-'.$day;
                /*$originalDate = $date1;
                $date1 = date("d-M-Y", strtotime($originalDate));*/

                $taxData    = [];
                $partyname  = (string)$vocher->VOUCHER->PARTYNAME;
                $invoice_no = (string)$vocher->VOUCHER->VOUCHERNUMBER;
                if(isset($vocher->VOUCHER->{'LEDGERENTRIES.LIST'}))
                foreach($vocher->VOUCHER->{'LEDGERENTRIES.LIST'} as $data){
                    if($j==0){
                        $totalAmount    = str_replace('-', '', (string)$data->AMOUNT);
                    }
                    if($j>0){
                        $taxData[]      = (string)$data->LEDGERNAME;
                    }
                     $j++;
                 }

                $taxes  = implode(',', $taxData);

                $invoiceDescArray=array();
                if(isset($vocher->VOUCHER->{'ALLINVENTORYENTRIES.LIST'}))
                foreach($vocher->VOUCHER->{'ALLINVENTORYENTRIES.LIST'} as $invoiceDesc){

                    $name   = (string)$invoiceDesc->STOCKITEMNAME;
                    $rate   = (string)$invoiceDesc->RATE;
                    $amount = (string)$invoiceDesc->AMOUNT;
                    $qty    = (string)$invoiceDesc->ACTUALQTY;
                    $invoiceDescArray[]=array("name"=>$name,"rate"=>$rate,"amount"=>$amount,"qty"=>$qty);

                }

                $invoiceArray[$i] = array("date"=>$date1,"buyer"=>$partyname,"invoice_no"=>$invoice_no,"company_name"=>$companyName,"total_amount"=>$totalAmount,"tax"=>$taxes,"desc"=>$invoiceDescArray);
                $i++;
            }
        }
        $contentArray       = [];
        $contentArrayDesc   = [];

        foreach ($invoiceArray as $invoiceData) {
            if((isset($invoiceData['buyer']) && !empty($invoiceData['buyer'])) && (isset($invoiceData['total_amount']) && !empty($invoiceData['total_amount'])) ){

                $checkInvoiceDuplicacy = checkDuplicacy("tblInvoice", "invoice_no", $invoiceData['invoice_no'], "identifier", "0");

                if($checkInvoiceDuplicacy){
                    $return['error']['buyer'][]         = $invoiceData['buyer'];
                    $return["error"]["invoice"][]       = $invoiceData['invoice_no'];
                    $return["error"]["error_type"][]    = "Duplicate Invoice";
                }else{

                $buyerEname          = $invoiceData['buyer'];

                $buyer=getTableDetailsByColumn('tblCustomer','Name',$buyerEname)['identifier'];
                if($buyer=="")  
                    {
                    /*$_SESION['error']=$buyerEname;
                    $contentArray['buyer']=$buyerEname;*/

                    $return['error']['buyer'][]         = $buyerEname;
                    $return["error"]["invoice"][]       = $invoiceData['invoice_no'];
                    $return["error"]["error_type"][]    = "Customer name does not exist in customer record";



                }else{
                    $contentArray['buyer']=$buyer;
                
                
                $companyName  = $invoiceData['company_name'];

                $cName=getTableDetailsByColumn('tblCompany','com_name',$companyName)['identifier'];
                if($cName=="")  
                    {
                    $_SESION['error']=$companyName;
                    $contentArray['company_name']=$companyName;
                }else{
                    $contentArray['company_name']=$cName;
                }
                $contentArray['invoice_no']     = $invoiceData['invoice_no'];
                $contentArray['date']           = $invoiceData['date'];
                $contentArray['total_amount']   = $invoiceData['total_amount'];
                $contentArray['tax']            = $invoiceData['tax'];
                /* Insert data in Invoice table. */
                $id                             = addData("tblInvoice", $contentArray);
       
                foreach ($invoiceData['desc'] as $invoiceDesc) {
                    $contentArrayDESC['invoice_no']     = $id;
                    $contentArrayDESC['name']           = $invoiceDesc['name'];
                    //$contentArrayDESC['gst_rate']=$invoiceDesc['buyer'];
                    $contentArrayDESC['qty']            = $invoiceDesc['qty'];
                    $contentArrayDESC['rate']           = $invoiceDesc['rate'];
                    $contentArrayDESC['amount']         = $invoiceDesc['amount'];
                    /* Insert data in Invoice description table  */
                    addData("tblInvoiceDesc", $contentArrayDESC);
                    # code...
                }
                }

            }
            }
        }

        if(isset($return['error']['buyer']) && !empty($return['error']['buyer']) ){
             echo json_encode($return);
            die();

        }else{
           $return["success"] = "Invoice Data successfully saved in database";
            echo json_encode($return);
            die();
        }
           

        }else{
            unlink('../upload/xml/'.$file);
            $return["error"] = "uploaded invoice not proper in XML structure. Please correct this.";
            echo json_encode($return);
            die();
        }    
    }else{
         $return["error"] = "Invoice Data do not be save. Please try again";
            echo json_encode($return);
            die();
   // }
}
}

?>
