<?php
    // Include autoloader
    require_once 'dompdf/autoload.inc.php';
    ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>index</title>
</head>
<style type="text/css">
    .table-responsive {
        min-height: .01%;
        overflow-x: auto;
    }
    .invoice{
        text-align: center;
        margin-top: 3px;
        margin-bottom: 5px;
    }
    .table-responsive table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
         border: 1px solid #ccc;
        font-family: sans-serif;
    }
    table tr td{
       line-height: 13px;
       font-size: 12px;
       padding: 5px 5px;
       border:1px solid #ccc;
    }
    .t_head{
        font-weight: bold;
    }
    td.sno {
        width: 50px;
    }
    td.des {
        width: 300px;
    }
    td.des1{
        text-align: right;
    }
    .text_center{
        text-align: center;
    }
</style>
<body>
     <?php $invoice_no = $_GET['invoice_no'];
        include("includes/connect.php");
        include("includes/functions.php");
        if(isset($invoice_no) && !empty($invoice_no)){
            $invoice = array();
            $invoice_qry = "SELECT * FROM tblinvoice where identifier = '".$invoice_no."'";
            $i_count = 0;
            $invoice = fetchData($invoice_qry, array(), $i_count);//print_r($invoice);
            
            if(isset($invoice) && !empty($invoice)){
                foreach($invoice as $inv)
                {
                    $i_id = $inv['identifier']; //invoice id
                    $c_id = $inv['company_name']; //company id
                    $company_invoice = $inv['invoice_no'];
                    $date = $inv['date'];
                }
                $comp_qry = "SELECT * FROM tblcompany where identifier = '".$c_id."'";
                $c_count = 0;
                $company = fetchData($comp_qry, array(), $c_count);
                $com_name = '';
                foreach($company as $cmp) 
                {
                    $com_name = $cmp['com_name']; //customer id
                    $com_email = $cmp['com_email'];
                    $com_phone = $cmp['com_phone'];
                    $com_pan = $cmp['com_pan'];
                    $com_gst = $cmp['com_gst'];
                    $com_cin = $cmp['com_cin'];
                    $com_address = $cmp['com_address'];
                    $com_vat = $cmp['com_vat'];
                    $com_service_taxno = $cmp['com_service_taxno'];
                    $com_bankname = $cmp['com_bankname'];
                    $com_acno = $cmp['com_acno'];
                    $com_bank_ifsc = $cmp['com_bank_ifsc'];
                }
                
                // Get customer Details
                $cust_qry = "SELECT * FROM tblcustomer where identifier = '".$c_id."'";
                $c_count = 0;
                $customer = fetchData($cust_qry, array(), $c_count);
                foreach($customer as $cust)
                {
                    $c_name = $cust['Name'];
                    $c_phone = $cust['phone'];
                    $c_email = $cust['email'];
                    $c_gst = $cust['GST'];
                    $c_pan = $cust['PAN'];
                    $c_address = $cust['address'];
                }

                // Get Invoice Description Details
                $invDesc_qry = "SELECT * FROM tblinvoicedesc where invoice_no = '".$invoice_no."'";
                $invDesc_count = 0;
                $invDesc = fetchData($invDesc_qry, array(), $invDesc_count); 
               
            }
            
        } ?>
    <div class="table-responsive">
        <h1 class="invoice">Tax Invoice</h1>
        <table>
            <tr>
                <td ><span class="t_head"><?php echo $com_name;?></span><br>
                    <span><?php echo $com_address;?></span><br>
                    <span><?php echo $c_gst;?></span><br>
                    State Name : Delhi, Code : 07<br>
                    CIN: U72300DL2010PTC197683<br>
                    E-Mail : <span><?php echo $c_email;?></span><br>
                </td>
                <td>
                    <table>
                        <tr>
                            <td style=" border-top:1px solid transparent; border-left: 1px solid transparent;">
                                Invoice No.</span><br>
                                <span class="t_head"><?php echo $company_invoice;?></span>
                            </td>
                            <td style="border-top:1px solid transparent; border-right: 1px solid transparent;">
                                Dated<br>
                                <span class="t_head"><?php echo $date;?></span>
                            </td>
                            <tr>
                                <td style="border-top:1px solid transparent; border-left: 1px solid transparent;">Delivery Note</td>
                                <td style="border-top:1px solid transparent; border-right: 1px solid transparent;">Mode/Terms of Payment</td>
                            </tr>
                            <tr>
                                <td style="border-top:1px solid transparent; border-left: 1px solid transparent;">Supplier’s Ref.</td>
                                <td style="border-top:1px solid transparent; border-right: 1px solid transparent;">Other Reference(s)</td>
                            </tr>
                            <tr>
                                <td style="border-top:1px solid transparent; border-left: 1px solid transparent; border-bottom: 1px solid transparent;">Buyer’s Order No.</td>
                                <td style="border-top:1px solid transparent; border-right: 1px solid transparent; border-bottom: 1px solid transparent;">Dated</td>
                            </tr>
                            
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    Buyer<br>
                    <span class="t_head"><?php echo $c_name;?></span><br>
                    <?php echo $c_address;?><br>
                    PAN/IT No :<?php echo $c_pan;?><br>
                    State Name : Delhi, Code : 07<br>
                </td>
                <td>
                    <table style="border:1px solid transparent;">
                        <tr>
                            <td style="border-top:1px solid transparent; border-left: 1px solid transparent;">Despatch Document No</td>
                            <td style="border-top:1px solid transparent; border-right: 1px solid transparent;">Delivery Note Date</td>
                        </tr>
                        <tr>
                            <td style="border-top:1px solid transparent; border-left: 1px solid transparent;">Despatched through</td>
                            <td style="border-top:1px solid transparent; border-right: 1px solid transparent;">Destination</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="border-top:1px solid transparent; border-right: 1px solid transparent; border-left: 1px solid transparent; border-bottom: 1px solid transparent">
                                Terms of Delivery
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

        </table>
        <table style="border-bottom: 1px solid #ccc;">
            <tr>
                <!--start update--><td class="" width="10%">SI NO.</td>
                <td class="" width="30%">Description of Goods</td>
                <td width="10%">HSN/SAC</td>
                <td width="10%">GST Rate</td>
                <td width="10%">Quantity</td>
                <td width="10%">Rate</td>
                <td width="10%">per</td>
                <td width="10%">Amount</td><!--end update-->
            </tr>
            <?php $i = 1;$sub_total = 0;$total_qty = 0;$count = 0;
                foreach($invDesc as $invD)
                { 
                    $ind_name = $invD['name'];
                    $hsn_sac = $invD['hsn_sac'];
                    $ind_qty = $invD['qty'];
                    $ind_rate = $invD['rate'];
                    $ind_amount = $ind_qty*$ind_rate; 
                    $sub_total = $sub_total + ($ind_qty*$ind_rate);
                    $total_qty = $total_qty+$ind_qty;
                    ?>
                    <!--start update--><tr>
                        <td class="sno" style="border-bottom: 1px solid transparent;"><?php echo $i;?></td>
                        <td class="des" style="border-bottom: 1px solid transparent;"><b><?php echo $ind_name;?></b></td>
                        <td style="border-bottom: 1px solid transparent;"><?php echo $hsn_sac;?></td>
                        <td style="border-bottom: 1px solid transparent;">18 %</td>
                        <td class="t_head" style="border-bottom: 1px solid transparent;"><?php echo round($ind_qty,2);?> QTY</td>
                        <td style="border-bottom: 1px solid transparent;"><?php echo round($ind_rate,2);?></td>
                        <td style="border-bottom: 1px solid transparent;">QTY</td>
                        <td class="t_head" style="border-bottom: 1px solid transparent;"><?php echo $ind_amount;?></td>
                    </tr><!--end update-->
                <?php $i++;$count++;} ?>
            <tr>
                <td class="sno"></td>
                <td class="des" style="border-bottom: 1px solid transparent;">01.04.2018 to 30.04.2018</td>
                <td style="border-bottom: 1px solid transparent;"></td>
                <td style="border-bottom: 1px solid transparent;"></td>
                <td style="border-bottom: 1px solid transparent;"></td>
                <td style="border-bottom: 1px solid transparent;"></td>
                <td style="border-bottom: 1px solid transparent;"></td>
                <td style="border-bottom: 1px solid transparent;"></td>
            </tr>
            
            <tr>
                <td class="sno"></td>
                <td class="des" style="border-bottom: 1px solid transparent;"></td>
                <td style="border-bottom: 1px solid transparent;"></td>
                <td style="border-bottom: 1px solid transparent;"></td>
                <td style="border-bottom: 1px solid transparent;"></td>
                <td style="border-bottom: 1px solid transparent;"></td>
                <td style="border-bottom: 1px solid transparent;"></td>
                <td style="border-bottom: 1px solid transparent;"><?php echo round($sub_total,2);?></td>
            </tr>

            <?php $query = "SELECT * FROM tblInvoice_tax ";
            $count = 0;
            $sql1 = fetchData($query, array(), $count);
           
            /*checked data to fetch and set flag*/

            $tax_selected = "SELECT tax FROM tblInvoice where identifier = ".$invoice_no;      
            $sql2 = getTaxes($tax_selected, array());

            $tax = explode(',',$sql2); $total_taxs_amt = 0; 
            foreach ($sql1 as $value) {           
                   if(in_array($value['name'], $tax)){  ?>
            
            <tr>
                <td class="sno"></td>
                <td class="des1 t_head" style="font-style: italic; border-bottom: 1px solid transparent;"><?php echo $value['name'];?></td>
                <td style="border-bottom: 1px solid transparent;"></td>
                <td style="border-bottom: 1px solid transparent;">
                    <?php if($value['name'] == 'DIMTS FEES'){echo $value['rate'];}else{echo $value['rate'].'%';} ?></td>
                <td style="border-bottom: 1px solid transparent;"></td>
                <td style="border-bottom: 1px solid transparent;"></td>
                <td style="border-bottom: 1px solid transparent;"></td>
                <td class="t_head" style="border-bottom: 1px solid transparent;">
                    <?php if($value['name'] == 'DIMTS FEES') {
                                $tax_amt =   $value['rate'];
                            }else{
                                $tax_amt =  $sub_total * ($value['rate']/100);
                            }
                            echo $tax_amt;
                            $total_taxs_amt = $total_taxs_amt + $tax_amt;
                    ?>                    
                </td>
            </tr>
            <?php } 
            } ?>
            <!--start update-->
            <tr style="background: #f1efef;"><!--end update-->
                <td class="sno"></td>
                <td class="des1">Total</td>
                <td></td>
                <td></td>
                <td class="t_head"><?php echo $total_qty; ?> QTY</td>
                <td></td>
                <td></td>
                <td class="t_head"><?php  $total =  $sub_total + $total_taxs_amt;echo round($total); ?></td>
            </tr>
            <tr>
                <td colspan="7">Amount Chargeable (in words)</td>
                <td>E & OE</td>
            </tr>
            <tr>
                <td colspan="8" class="t_head">INR <?php echo ucwords(numberTowords(round($total)));?> Only</td>
                </tr>
           <?php  
                $tax_selected = "SELECT tax FROM tblInvoice where identifier = ".$invoice_no;      
                $sql2 = getTaxes($tax_selected, array());
                $total_taxs_amt = 0;
                $i=0;
                foreach ($sql1 as $value) { 
                   if(in_array($value['name'], $tax)){ 
                      if(($value['name'] != 'DIMTS FEES') && ($value['name'] == 'IGST OUTPUT'))  { 
                        ?>
                        <tr>
                            <td colspan="2" class="text_center">HSN/SAC</td>
                            <td class="text_center">Taxable</td>
                            <td class="text_center" colspan="4">Integrated Tax</td>
                            <td class="text_center">Total</td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>Value</td>
                            <td colspan="2">Rate</td>
                            <td colspan="2">Amount</td>
                            <td>Tax Amount</td>
                        </tr>
                        <tr>
                            <td colspan="2"><?php echo $hsn_sac;?></td>
                            <td><?php echo $sub_total;?></td>
                            <?php 
                            
                            foreach ($sql1 as $value) {           
                               if(in_array($value['name'], $tax)){ 
                                  if(($value['name'] != 'DIMTS FEES') && ($value['name'] == 'IGST OUTPUT')) { ?>
                                        <td colspan="2"><?php echo $value['rate']?>%</td>
                                        <td colspan="2"><?php  
                                                    if($value['name'] == 'DIMTS FEES') {
                                                        $tax_amt =  $sub_total  + $value['rate'];
                                                    }else{
                                                        $tax_amt =  $sub_total * ($value['rate']/100);
                                                    }
                                                    echo $tax_amt;
                                                    $total_taxs_amt = $total_taxs_amt + $tax_amt;?>
                                                   
                                        </td>
                                  <?php } 
                                } 
                            } ?>
                            <td class="t_head"><?php  echo $total_taxs_amt;?></td>              
                        </tr>
                        <tr>
                            <td colspan="2" class="des1 t_head">Total</td>
                            <td class="t_head"><?php echo $sub_total;?></td>
                            <?php 
                            foreach ($sql1 as $value) {           
                               if(in_array($value['name'], $tax)){ 
                                  if(($value['name'] != 'DIMTS FEES') && ($value['name'] == 'IGST OUTPUT')) { ?>
                                    <td colspan="2"></td> 
                                    <td class="t_head" colspan="2"><?php  
                                        if($value['name'] == 'DIMTS FEES') {
                                            $tax_amt =  $sub_total  + $value['rate'];
                                        }else{
                                            $tax_amt =  $sub_total * ($value['rate']/100);
                                        }
                                        echo $tax_amt;
                                        $total_taxs_amt = $total_taxs_amt + $tax_amt; ?>
                                        
                                    </td>
                                    <?php } 
                                } 
                            } ?>
                            <td class="t_head"><?php echo $total_taxs_amt;?></td>
                        </tr>  
                  <?php  }
                    }
                }
                foreach ($sql1 as $value) { 
                    if($i==0){
                        if(in_array($value['name'], $tax)){ 
                            if(($value['name'] != 'DIMTS FEES') && (($value['name'] == 'SGST OUTPUT') || ($value['name'] == 'CGST Output'))) { ?>
                                <tr>
                                    <td colspan="2" class="text_center">HSN/SAC</td>
                                    <td class="text_center">Taxable</td>
                                    <td class="text_center" colspan="2">Central Tax</td>
                                    <td class="text_center" colspan="2">State Tax</td>
                                    <td class="text_center">Total</td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td>Value</td>
                                    <td>Rate</td>
                                    <td>Amount</td>
                                    <td>Rate</td>
                                    <td>Amount</td>
                                    <td>Tax Amount</td>
                                </tr>
                                <tr>
                                    <td colspan="2"><?php echo $hsn_sac;?></td>
                                    <td><?php echo $sub_total;?></td>
                                    <?php 
                                    foreach ($sql1 as $value) {           
                                       if(in_array($value['name'], $tax)){ 
                                          if(($value['name'] != 'DIMTS FEES') && (($value['name'] == 'SGST OUTPUT') || ($value['name'] == 'CGST Output'))) { ?>
                                                <td><?php echo $value['rate']?>%</td>
                                                <td><?php  
                                                            if($value['name'] == 'DIMTS FEES') {
                                                                $tax_amt =  $sub_total  + $value['rate'];
                                                            }else{
                                                                $tax_amt =  $sub_total * ($value['rate']/100);
                                                            }
                                                            echo $tax_amt;
                                                            $total_taxs_amt = $total_taxs_amt + $tax_amt;?>
                                                           
                                                </td>
                                          <?php } 
                                        } 
                                    } ?>
                                    <td class="t_head"><?php  echo $total_taxs_amt;?></td>              
                                </tr>
                                <tr>
                                    <td colspan="2" class="des1 t_head">Total</td>
                                    <td class="t_head"><?php echo $sub_total;?></td>
                                    <?php 

                                    foreach ($sql1 as $value) {           
                                       if(in_array($value['name'], $tax)){ 
                                          if(($value['name'] != 'DIMTS FEES') && (($value['name'] == 'SGST OUTPUT') || ($value['name'] == 'CGST Output'))) { ?>
                                            <td></td> 
                                            <td class="t_head"><?php  
                                                if($value['name'] == 'DIMTS FEES') {
                                                    $tax_amt =  $sub_total  + $value['rate'];
                                                }else{
                                                    $tax_amt =  $sub_total * ($value['rate']/100);
                                                }
                                                echo $tax_amt;
                                                $total_taxs_amt = $total_taxs_amt + $tax_amt; ?>
                                                
                                            </td>
                                            <?php } 
                                        } 
                                    } ?>
                                    <td class="t_head"><?php echo $total_taxs_amt;?></td>
                                </tr>  
                    <?php  }
                        } 
                    } $i++;
                } ?>
            <tr>
                <td colspan="3" style="border-right: 1px solid transparent; border-bottom: 1px solid transparent;">Tax Amount (in words)  :</td>
                <td colspan="5" class="t_head" style="border-left: 1px solid transparent; border-bottom: 1px solid transparent;">INR <?php echo ucwords(numberTowords(round($total_taxs_amt)));?> Only</td>
            </tr>
            <tr>
                <td colspan="4" style="border-bottom:1px solid #ccc; border-right:1px solid transparent; border-top:1px solid transparent;">
                    <table style="border:1px solid transparent">
                        <tr>
                            <td style="border:1px solid transparent;">Company's VAT TIN:</td>
                            <td class="t_head" style="border:1px solid transparent;">07110472656</td>
                        </tr>
                        <tr>
                            <td style="border:1px solid transparent;">Company's Service Tax No. :</td>
                            <td class="t_head" style="border:1px solid transparent;">AADCD3075GSD001</td>
                        </tr>
                        <tr>
                            <td style="border:1px solid transparent;">Company's PAN :</td>
                            <td class="t_head" style="border:1px solid transparent;">AADCD3075G</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="border:1px solid transparent; text-decoration: underline; display: inline-block;">Declaration</td>
                        </tr>
                    </table>
                    <br><span>We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.</span>
                </td>
                <td colspan="4" style="border-bottom: 1px solid #ccc;">
                    <table style="border-bottom: 1px solid transparent">
                        <tr>
                            <td colspan="2" style="border:1px solid transparent;">Company's Bank Details</td>
                            <td colspan="2" style="border:1px solid transparent"></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="border:1px solid transparent;">Bank Name :</td>
                            <td colspan="2" class="t_head" style="border:1px solid transparent;"><?php echo $com_bankname;?></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="border:1px solid transparent;">A/c No. :</td>
                            <td colspan="2" class="t_head" style="border:1px solid transparent"><?php echo $com_acno;?></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="border-left: 1px solid transparent; border-right: 1px solid transparent; border-top: 1px solid transparent;">Branch & IFS Code :<?php echo $com_bank_ifsc;?></td>
                            <td colspan="2" class="t_head" style="border-left: 1px solid transparent; border-right: 1px solid transparent; border-top: 1px solid transparent;">I.P.Extension Delhi & HDFC0000922</td>
                        </tr>
                        <tr>
                            <td  colspan="4" class="t_head" style="position: relative;left: 300px;" >
                                for <?php echo $com_name;?><br><br>
                                Authorised Signatory
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <center><p> SUBJECT TO DELHI JURISDICTION<br>
    Electronic Invoice Signature Not required</p></center>
    </div>
</body>
</html>
<?php
$html = ob_get_clean();
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->render();
$dompdf->stream("invoice.pdf");?>