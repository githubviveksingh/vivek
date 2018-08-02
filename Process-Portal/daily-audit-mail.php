<?php
date_default_timezone_set("Asia/Kolkata");
include("PHPMailer/class.phpmailer.php");
include'includes/connect.php';
include'includes/functions.php';

$root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';

$html ='<!DOCTYPE html>
<html>
<head>
<meta  name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0," />
 <style>
   @import url("https://fonts.googleapis.com/css?family=Open+Sans");
</style>
</head>';
$html .='<div class="outer" style="margin:0 auto; padding:10px 0; font-size:14px; font-weight:400; line-height:20px; font-family: \'Open Sans\', sans-serif;">
   <div class="section" style="border-top: solid 2px #428bca;background: #1d2939;color: #fff;font-weight: 500;padding: 10px;">Daily Audit Report for '.date('jS  F Y').' for Process Portal</div>
   <div style="clear:both;"></div>';
$purchaseData=getAllDailyAuditReport('PUR_ADD','PUR_STATUS','PUR_UPD','');
if(count($purchaseData)>0)
{
	$html .='<div class="todayrow" style="border:2px solid #004c00;background:#428BCA;">
          <div class="update" style="color: #fff;float: left;width: auto;font-size: 17px;font-weight: 500;line-height: normal;padding: 8px;">
		  <font style="color: #fff;">Audit Report for</font>
            Purchase Section
		  </div>
			<table class="details"  cellpadding="5" style="border:1px solid;width:100%;background:#eee;">
				<thead>
					<tr>
					<th style="font-size:14px;font-weight:700;line-height:normal;text-align: left; padding:10px;background:#ddd;">PO Reference</th>
					<th style="font-size:14px;font-weight:700;line-height:normal;text-align: left; padding:10px;background:#ddd;">Status</th>
					<th style="font-size:14px;font-weight:700;line-height:normal;text-align: left; padding:10px;background:#ddd;">Trigger By</th>
					<th style="font-size:14px;font-weight:700;line-height:normal;text-align: left; padding:10px;background:#ddd;">Action</th>
					<th style="font-size:14px;font-weight:700;line-height:normal;text-align: left; padding:10px;background:#ddd;">Audit Details</th>
					</tr>
				</thead>
				<tbody>';
				$mailText="";
				foreach($purchaseData as $purchase)
        		{
                  $arraydata=$purchase['data'];
				  $jsondata=json_decode($arraydata,true);
				  if($purchase['auditCode']=="PUR_UPD"){
                         $mailText="";
					if($jsondata['old_values']['PurchaseID']!=$jsondata['new_values']['PurchaseID'])
					{
                       $mailText='<tr><td style="background:#ddd;">PurchaseID:</td><td style="background:#ddd;">'.$jsondata['old_values']['PurchaseID'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['PurchaseID'].'</td></tr>';
					}
					if($jsondata['old_values']['POReference']!=$jsondata['new_values']['POReference'])
					{
                       $mailText .='<tr><td style="background:#ddd;">POReference:</td><td style="background:#ddd;">'.$jsondata['old_values']['POReference'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['POReference'].'</td></tr>';
					}
					if($jsondata['old_values']['PurchaseClassification']!=$jsondata['new_values']['PurchaseClassification'])
					{
                       $mailText .='<tr><td style="background:#ddd;">PurchaseClassification:</td><td style="background:#ddd;">'.$PURCHASECLASS[$jsondata['old_values']['PurchaseClassification']][1].'('.$jsondata['old_values']['PurchaseClassification'].')</td><td style="background:#ddd;">'.$PURCHASECLASS[$jsondata['new_values']['PurchaseClassification']][1].'('.$jsondata['new_values']['PurchaseClassification'].')</td></tr>';
					}
					if($jsondata['old_values']['Quantity']!=$jsondata['new_values']['Quantity'])
					{
                       $mailText .='<tr><td style="background:#ddd;">Quantity:</td><td style="background:#ddd;">'.$jsondata['old_values']['Quantity'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['Quantity'].'</td></tr>';
					}
					if($jsondata['old_values']['SupplierID']!=$jsondata['new_values']['SupplierID'])
					{
					   $new_values=getSuppliers($jsondata['new_values']['SupplierID']);
					   $old_values=getSuppliers($jsondata['old_values']['SupplierID']);
                       $mailText .='<tr><td style="background:#ddd;">SupplierID</td><td style="background:#ddd;">'.$old_values[0]['name'].'('.$jsondata['old_values']['SupplierID'].')</td><td style="background:#ddd;">'.$new_values[0]['name'].'('.$jsondata['new_values']['SupplierID'].')</td></tr>';
					}
				 }
				 if($purchase['auditCode']=="PUR_ADD"){
					 $supp=getSuppliers($jsondata['SupplierID']);
					 $mailText="";
					 $mailText='<tr><td style="background:#ddd;">PurchaseID</td><td style="background:#ddd;">'.$jsondata['PurchaseID'].'</tr>';
					 $mailText .='<tr><td style="background:#ddd;">POReference</td><td style="background:#ddd;">'.$jsondata['POReference'].'</tr>';
					 $mailText .='<tr><td style="background:#ddd;">PurchaseClassification</td><td style="background:#ddd;">'.$PURCHASECLASS[$jsondata['PurchaseClassification']][1].'('.$jsondata['PurchaseClassification'].')</tr>';
					 $mailText .='<tr><td style="background:#ddd;">Quantity</td><td style="background:#ddd;">'.$jsondata['Quantity'].'</tr>';
					 $mailText .='<tr><td style="background:#ddd;">SupplierID</td><td style="background:#ddd;">'.$supp[0]['name'].'('.$jsondata['SupplierID'].')</tr>';
				 }
                if($purchase['auditCode']=="PUR_STATUS"){
					$mailText="";
					 $mailText='<tr><td style="background:#ddd;">Previous_Status</td><td style="background:#ddd;">'.$PURCHASESTATUS[$jsondata['Previous_Status']][1].'('.$jsondata['Previous_Status'].')</tr>';
					 $mailText .='<tr><td style="background:#ddd;">Current_Status</td><td style="background:#ddd;">'.$PURCHASESTATUS[$jsondata['Current_Status']][1].'('.$jsondata['Current_Status'].')</tr>';
				 }
				$purDetails=getPurchaseDetails($purchase['targetID']);
				$empD=getEmpDetails($purchase['triggeredBy']);
				$html .='<tr>
					<td style="border-top:solid 1px;"><a href="'.$root.'purchase.php?sSearch'.$purDetails['POReference'].'" target="_blank">'.$purDetails['POReference'].'</a></td>
					<td style="border-top:solid 1px;">'.$PURCHASESTATUS[$purDetails['Status']][1].'</td>
					<td style="border-top:solid 1px;">'.$empD['name'].'</td>
					<td style="border-top:solid 1px;">'.$AUDITCODES[$purchase['auditCode']].'</td>
					<td style="border-top:solid 1px;"><table style="border:solid 1px;width:100%">'.$mailText.'</table></td>
					</tr>
					';
					$mailText="";
				}
				$html .='</tbody>
			<table>
        </div>';
}
$employeeData=getAllDailyAuditReport('EMP_ADD','EMP_UPD','','');
if(count($employeeData)>0)
{
	$html .='<div class="section" style=" background: none; padding: 0px;"></div>
        <div class="todayrow" style="border:2px solid #004c00; ;background:#004c00;">
          <div class="update" style="color: #fff;float: left;width: auto;font-size: 17px;font-weight: 500;line-height: normal;padding: 8px;">
		  <font style="color:#FFF;">Audit Report for</font>
            Employee Section
		  </div>
			<table class="details"  cellpadding="" style="border:1px solid;width:100%;background:#eee;">
				<thead>
					<tr>
					<th style="font-size:14px;font-weight:700;line-height:normal;text-align: left; padding:10px;background:#ddd;">Employee</th>
					<th style="font-size:14px;font-weight:700;line-height:normal;text-align: left; padding:10px;background:#ddd;">Trigger By</th>
					<th style="font-size:14px;font-weight:700;line-height:normal;text-align: left; padding:10px;background:#ddd;">Action</th>
					<th style="font-size:14px;font-weight:700;line-height:normal;text-align: left; padding:10px;background:#ddd;">Audit Details</th>
					</tr>
				</thead>
				<tbody>';
				foreach($employeeData as $emp)
        		{
				$mailText="";
				  $arraydata=$emp['data'];
				  $jsondata=json_decode($arraydata,true);
				  if($emp['auditCode']=="EMP_UPD"){
					  $mailText="";
					  if($jsondata['old_values']['name']!=$jsondata['new_values']['name'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">name:</td><td style="background:#ddd;">'.$jsondata['old_values']['name'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['name'].'</td></tr>';
					   }
                      if($jsondata['old_values']['email']!=$jsondata['new_values']['email'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">email:</td><td style="background:#ddd;">'.$jsondata['old_values']['email'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['email'].'</td></tr>';
					   }
                      if($jsondata['old_values']['address']!=$jsondata['new_values']['address'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">address:</td><td style="background:#ddd;">'.$jsondata['old_values']['address'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['address'].'</td></tr>';
					   }
                      if($jsondata['old_values']['pan']!=$jsondata['new_values']['pan'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">pan:</td><td style="background:#ddd;">'.$jsondata['old_values']['pan'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['pan'].'</td></tr>';
					   }
                      if($jsondata['old_values']['aadhar']!=$jsondata['new_values']['aadhar'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">aadhar:</td><td style="background:#ddd;">'.$jsondata['old_values']['aadhar'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['aadhar'].'</td></tr>';
					   }
                     if($jsondata['old_values']['locationID']!=$jsondata['new_values']['locationID'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">locationID:</td><td style="background:#ddd;">'.$jsondata['old_values']['locationID'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['locationID'].'</td></tr>';
					   }
                     if($jsondata['old_values']['type']!=$jsondata['new_values']['type'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">type:</td><td style="background:#ddd;">'.$EMPTYPE[$jsondata['old_values']['type']].'('.$jsondata['old_values']['type'].')</td><td style="background:#ddd;">'.$EMPTYPE[$jsondata['new_values']['type']].'('.$jsondata['new_values']['type'].')</td></tr>';
					   }
                     if($jsondata['old_values']['status']!=$jsondata['new_values']['status'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">status:</td><td style="background:#ddd;">'.$jsondata['old_values']['status'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['status'].'</td></tr>';
					   }
				  }
				  if($emp['auditCode']=="EMP_ADD"){
					 $mailText='<tr><td style="background:#ddd;">name</td><td style="background:#ddd;">'.$jsondata['name'].'</tr>';
					 $mailText .='<tr><td style="background:#ddd;">email</td><td style="background:#ddd;">'.$jsondata['email'].'</tr>';
					 $mailText .='<tr><td style="background:#ddd;">pan</td><td style="background:#ddd;">'.$jsondata['pan'].'</tr>';
					 $mailText .='<tr><td style="background:#ddd;">type</td><td style="background:#ddd;">'.$EMPTYPE[$jsondata['type']].'('.$jsondata['type'].')</tr>';
					 $mailText .='<tr><td style="background:#ddd;">locationID</td><td style="background:#ddd;">'.$jsondata['locationID'].'</tr>';
					 $mailText .='<tr><td style="background:#ddd;">DoJ</td><td style="background:#ddd;">'.$jsondata['DoJ'].'</tr>';
					 $mailText .='<tr><td style="background:#ddd;">address</td><td style="background:#ddd;">'.$jsondata['address'].'</tr>';
				 }
				$empD=getEmpDetails($emp['triggeredBy']);
                $empname=getEmpDetails($emp['targetID']);
				$html .='<tr>
					<td style="border-top:solid 1px;"><a href="'.$root.'employee.php?sSearch='.$empname['name'].'" target="_blank">'.$empname['name'].'</a></td>
					<td style="border-top:solid 1px;">'.$empD['name'].'</td>
					<td style="border-top:solid 1px;">'.$AUDITCODES[$emp['auditCode']].'</td>
					<td style="border-top:solid 1px;"><table style="border:solid 1px;width:100%">'.$mailText.'</table></td>
					</tr>
					';
				}
				$html .='</tbody>
			<table>
        </div>';
}
$customersData=getAllDailyAuditReport('CUS_ADD','CUS_UPD','','');
if(count($customersData)>0)
{
	$html .='<div class="section" style="background: none;padding: 0px;"></div>
        <div class="todayrow" style="border:2px solid #004c00;background:#eac435;">
          <div class="update" style="color:#FFF;float: left;width: auto;font-size: 17px;font-weight: 500;line-height: normal;padding: 8px;">
		  <font style="color:#FFF;">Audit Report for</font>
            Customers
		  </div>
			<table class="details"  cellpadding="" style="border:1px solid; width:100%; background:#eee;">
				<thead>
					<tr>
					<th style="font-size:14px;font-weight:700;line-height:normal;text-align: left; padding:10px;background:#ddd;">Customer</th>
					<th style="font-size:14px;font-weight:700;line-height:normal;text-align: left; padding:10px;background:#ddd;">Trigger By</th>
					<th style="font-size:14px;font-weight:700;line-height:normal;text-align: left; padding:10px;background:#ddd;">Action</th>
					<th style="font-size:14px;font-weight:700;line-height:normal;text-align: left; padding:10px;background:#ddd;">Audit Details</th>
					</tr>
				</thead>
				<tbody>';
				foreach($customersData as $customer)
        		{
					$mailText="";
                  $arraydata=$customer['data'];
				  $jsondata=json_decode($arraydata,true);
				  if($customer['auditCode']=="CUS_UPD"){
					  $mailText="";
					  if($jsondata['old_values']['Name']!=$jsondata['new_values']['Name'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">Name:</td><td style="background:#ddd;">'.$jsondata['old_values']['Name'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['Name'].'</td></tr>';
					   }
                      if($jsondata['old_values']['email']!=$jsondata['new_values']['email'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">email:</td><td style="background:#ddd;">'.$jsondata['old_values']['email'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['email'].'</td></tr>';
					   }
					   if($jsondata['old_values']['phone']!=$jsondata['new_values']['phone'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">phone:</td><td style="background:#ddd;">'.$jsondata['old_values']['phone'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['phone'].'</td></tr>';
					   }
                      if($jsondata['old_values']['address']!=$jsondata['new_values']['address'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">address:</td><td style="background:#ddd;">'.$jsondata['old_values']['address'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['address'].'</td></tr>';
					   }
                      if($jsondata['old_values']['PAN']!=$jsondata['new_values']['PAN'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">PAN:</td><td style="background:#ddd;">'.$jsondata['old_values']['PAN'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['PAN'].'</td></tr>';
					   }
                      if($jsondata['old_values']['GST']!=$jsondata['new_values']['GST'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">GST:</td><td style="background:#ddd;">'.$jsondata['old_values']['GST'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['GST'].'</td></tr>';
					   }
                     if($jsondata['old_values']['billCycle']!=$jsondata['new_values']['billCycle'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">billCycle:</td><td style="background:#ddd;">'.$SUPPBILLCYL[$jsondata['old_values']['billCycle']].'('.$jsondata['old_values']['billCycle'].')</td><td style="background:#ddd;">'.$SUPPBILLCYL[$jsondata['new_values']['billCycle']].'('.$jsondata['new_values']['billCycle'].')</td></tr>';
					   }
                     if($jsondata['old_values']['employeeID']!=$jsondata['new_values']['employeeID'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">employeeID:</td><td style="background:#ddd;">'.$jsondata['old_values']['employeeID'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['employeeID'].'</td></tr>';
					   }
				    }
					if($customer['auditCode']=="CUS_ADD"){
						$mailText="";
					 $mailText='<tr><td style="background:#ddd;">Name</td><td style="background:#ddd;">'.$jsondata['Name'].'</tr>';
					 $mailText .='<tr><td style="background:#ddd;">email</td><td style="background:#ddd;">'.$jsondata['email'].'</tr>';
					 $mailText .='<tr><td style="background:#ddd;">pan</td><td style="background:#ddd;">'.$jsondata['PAN'].'</tr>';
					 $mailText .='<tr><td style="background:#ddd;">phone</td><td style="background:#ddd;">'.$jsondata['phone'].'</tr>';
					 $mailText .='<tr><td style="background:#ddd;">GST</td><td style="background:#ddd;">'.$jsondata['GST'].'</tr>';
					 $mailText .='<tr><td style="background:#ddd;">billCycle</td><td style="background:#ddd;">'.$SUPPBILLCYL[$jsondata['billCycle']].'('.$jsondata['billCycle'].')</tr>';
					 $mailText .='<tr><td style="background:#ddd;">address</td><td style="background:#ddd;">'.$jsondata['address'].'</tr>';
				    }
				$empD=getEmpDetails($customer['triggeredBy']);
				$cus=getCustomerDetails($customer['targetID']);
				$html .='<tr>
					<td style="border-top:solid 1px;"><a href="'.$root.'customers.php?sSearch='.$cus['Name'].'" target="_blank">'.$cus['Name'].'</a></td>
					<td style="border-top:solid 1px;">'.$empD['name'].'</td>
					<td style="border-top:solid 1px;">'.$AUDITCODES[$customer['auditCode']].'</td>
					<td style="border-top:solid 1px;"><table style="border:solid 1px;width:100%">'.$mailText.'</table></td>
					</tr>
					';
				}
				$html .='</tbody>
			<table>
        </div>';
}
$suppliersData=getAllDailyAuditReport('SUP_ADD','SUP_UPD','','');
if(count($suppliersData)>0)
{
	$html .='<div class="section" style="    background: none;    padding: 0px;"></div>
        <div class="todayrow" style="border:2px solid #004c00;background:#0dab0c;">

          <div class="update" style="color:#FFF;float: left;width: auto;font-size: 17px;font-weight: 500;line-height: normal;padding: 8px;">
		  <font style="color:#FFF;">Audit Report for</font>
            Suppliers
		  </div>
			<table class="details"  cellpadding="" style="border:1px solid;width:100%;background:#eee;">
				<thead>
					<tr>
					<th style="font-size:14px;font-weight:700;line-height:normal;text-align: left; padding:10px;background:#ddd;">Supplier Name</th>
					<th style="font-size:14px;font-weight:700;line-height:normal;text-align: left; padding:10px;background:#ddd;">Trigger By</th>
					<th style="font-size:14px;font-weight:700;line-height:normal;text-align: left; padding:10px;background:#ddd;">Action</th>
					<th style="font-size:14px;font-weight:700;line-height:normal;text-align: left; padding:10px;background:#ddd;">Audit Details</th>
					</tr>
				</thead>
				<tbody>';
				foreach($suppliersData as $suppliers)
        		{
				$mailText="";
				  $arraydata=$suppliers['data'];
				  $jsondata=json_decode($arraydata,true);
				  if($suppliers['auditCode']=="SUP_UPD"){
					  $mailText="";
					  if($jsondata['old_values']['name']!=$jsondata['new_values']['name'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">name:</td><td style="background:#ddd;">'.$jsondata['old_values']['name'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['name'].'</td></tr>';
					   }
                      if($jsondata['old_values']['email']!=$jsondata['new_values']['email'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">email:</td><td style="background:#ddd;">'.$jsondata['old_values']['email'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['email'].'</td></tr>';
					   }
					   if($jsondata['old_values']['phone']!=$jsondata['new_values']['phone'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">phone:</td><td style="background:#ddd;">'.$jsondata['old_values']['phone'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['phone'].'</td></tr>';
					   }
                      if($jsondata['old_values']['address']!=$jsondata['new_values']['address'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">address:</td><td style="background:#ddd;">'.$jsondata['old_values']['address'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['address'].'</td></tr>';
					   }
                      if($jsondata['old_values']['PAN']!=$jsondata['new_values']['pan'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">PAN:</td><td style="background:#ddd;">'.$jsondata['old_values']['PAN'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['pan'].'</td></tr>';
					   }
                      if($jsondata['old_values']['GST']!=$jsondata['new_values']['gst'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">GST:</td><td style="background:#ddd;">'.$jsondata['old_values']['GST'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['gst'].'</td></tr>';
					   }

                     if($jsondata['old_values']['billCycle']!=$jsondata['new_values']['billCycle'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">billCycle:</td><td style="background:#ddd;">'.$SUPPBILLCYL[$jsondata['old_values']['billCycle']].'('.$jsondata['old_values']['billCycle'].')</td><td style="background:#ddd;">'.$SUPPBILLCYL[$jsondata['new_values']['billCycle']].'('.$jsondata['new_values']['billCycle'].')</td></tr>';
					   }

				    }
					if($suppliers['auditCode']=="SUP_ADD"){
					$mailText="";
					 $mailText='<tr><td style="background:#ddd;">name</td><td style="background:#ddd;">'.$jsondata['name'].'</tr>';
					 $mailText .='<tr><td style="background:#ddd;">email</td><td style="background:#ddd;">'.$jsondata['email'].'</tr>';
					 $mailText .='<tr><td style="background:#ddd;">pan</td><td style="background:#ddd;">'.$jsondata['pan'].'</tr>';
					 $mailText .='<tr><td style="background:#ddd;">phone</td><td style="background:#ddd;">'.$jsondata['phone'].'</tr>';
					 $mailText .='<tr><td style="background:#ddd;">gst</td><td style="background:#ddd;">'.$jsondata['gst'].'</tr>';
					/* $mailText .='<tr><td style="background:#ddd;">vat</td><td style="background:#ddd;">'.$jsondata['vat'].'</tr>'; $mailText .='<tr><td style="background:#ddd;">st</td><td style="background:#ddd;">'.$jsondata['st'].'</tr>';*/
					 $mailText .='<tr><td style="background:#ddd;">billCycle</td><td style="background:#ddd;">'.$SUPPBILLCYL[$jsondata['billCycle']].'('.$jsondata['billCycle'].')</tr>';
					 $mailText .='<tr><td style="background:#ddd;">address</td><td style="background:#ddd;">'.$jsondata['address'].'</tr>';
				    }
				   $empD=getEmpDetails($suppliers['triggeredBy']);
				   $suppD=getSuppliers($suppliers['targetID']);
				   $html .='<tr>
					<td style="border-top:solid 1px;"><a href="'.$root.'suppliers.php?sSearch='.$suppD[0]['name'].'" target="_blank">'.$suppD[0]['name'].'</a></td>
					<td style="border-top:solid 1px;">'.$empD['name'].'</td>
					<td style="border-top:solid 1px;">'.$AUDITCODES[$suppliers['auditCode']].'</td>
				    <td style="border-top:solid 1px;"><table style="border:solid 1px;width:100%">'.$mailText.'</table></td>
					</tr>';
				   }
				$html .='</tbody>
			<table>
        </div>';
  }
$supportData=getAllDailyAuditReport('SPT_ADD','SPT_UPD','','');
if(count($supportData)>0)
{
	$html .='<div class="section" style="    background: none;    padding: 0px;"></div>
        <div class="todayrow" style="border:2px solid #004c00;border-bottom:solid;background:red;">

          <div class="update" style="color:#FFF;float: left;width: auto;font-size: 17px;font-weight: 500;line-height: normal;padding: 8px;">
		  <font style="color:#FFF;">Audit Report for</font>
            Support
		  </div>
			<table class="details"  cellpadding="" style="border:1px solid;width:100%;background:#eee;">
				<thead>
					<tr>
					<th style="font-size:14px;font-weight:700;line-height:normal;text-align: left; padding:10px;background:#ddd;">Service Type</th>
					<th style="font-size:14px;font-weight:700;line-height:normal;text-align: left; padding:10px;background:#ddd;">Trigger By</th>
					<th style="font-size:14px;font-weight:700;line-height:normal;text-align: left; padding:10px;background:#ddd;">Status</th>
					<th style="font-size:14px;font-weight:700;line-height:normal;text-align: left; padding:10px;background:#ddd;">Action</th>
					<th style="font-size:14px;font-weight:700;line-height:normal;text-align: left; padding:10px;background:#ddd;">Audit Details</th>
					</tr>
				</thead>
				<tbody>';
				foreach($supportData as $support)
        		{
				$mailText="";
				  $arraydata=$support['data'];
				  $jsondata=json_decode($arraydata,true);
				  if($support['auditCode']=="SPT_UPD"){
					  $mailText="";
					  if($jsondata['old_values']['itemID']!=$jsondata['new_values']['itemID'])
					   {
						 $old_values=$jsondata['old_values']['itemID'];
						 $new_values=$jsondata['new_values']['itemID'];
						 if($jsondata['old_values']['itemID']!=0){
						 	$mailText='<tr><td style="background:#ddd;">itemID:</td><td style="background:#ddd;">'.$old_values.'</td><td style="background:#ddd;">'.$new_values.'</td></tr>';
						 }
					   }

					   if($jsondata['old_values']['itemCategory']!=$jsondata['new_values']['itemCategory'])
					   {
						 $old_values=$jsondata['old_values']['itemCategory'];
						 $new_values=$jsondata['new_values']['itemCategory'];
						 if($jsondata['old_values']['itemCategory']==0){
						 	$mailText='<tr><td style="background:#ddd;">CategoryID:</td><td style="background:#ddd;">'.$old_values.'</td><td style="background:#ddd;">'.$new_values.'</td></tr>';
						 }
					     
					   }
                     /* if($jsondata['old_values']['itemCategory']!=$jsondata['new_values']['itemCategory'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">itemCategory:</td><td style="background:#ddd;">'.$ITEMCLASS[$jsondata['old_values']['itemCategory']][1].'('.$jsondata['old_values']['itemCategory'].')</td><td style="background:#ddd;">'.$ITEMCLASS[$jsondata['new_values']['itemCategory']][1].'('.$jsondata['new_values']['itemCategory'].')</td></tr>';
					   }*/
					   if($jsondata['old_values']['DateofReport']!=$jsondata['new_values']['DateofReport'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">DateofReport:</td><td style="background:#ddd;">'.$jsondata['old_values']['DateofReport'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['DateofReport'].'</td></tr>';
					   }
                      if($jsondata['new_values']['DateOfResolution']>0)
					   {
					     $mailText .='<tr><td style="background:#ddd;">DateOfResolution:</td><td style="background:#ddd;" colspan="2">'.$jsondata['new_values']['DateOfResolution'].'</td></tr>';
					   }
                      if($jsondata['old_values']['ServiceReportNo']!=$jsondata['new_values']['ServiceReportNo'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">ServiceReportNo:</td><td style="background:#ddd;">'.$jsondata['old_values']['ServiceReportNo'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['ServiceReportNo'].'</td></tr>';
					   }
                      if($jsondata['old_values']['CustomerID']!=$jsondata['new_values']['CustomerID'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">CustomerID:</td><td style="background:#ddd;">'.$jsondata['old_values']['CustomerID'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['CustomerID'].'</td></tr>';
					   }
                     if($jsondata['new_values']['technicianID']>0)
					   {
						  $old_values=getEmpDetails($jsondata['old_values']['technicianID']);
						  $new_values=getEmpDetails($jsondata['new_values']['technicianID']);
					     $mailText .='<tr><td style="background:#ddd;">TechnicianID:</td><td style="background:#ddd;">'.$old_values['name'].'</td><td style="background:#ddd;">'.$new_values['name'].'</td></tr>';
					   }
					   if($jsondata['old_values']['Status']!=$jsondata['new_values']['Status'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">Status:</td><td style="background:#ddd;">'.$SUPPORTSTS[$jsondata['old_values']['Status']][1].'('.$jsondata['old_values']['Status'].')</td><td style="background:#ddd;">'.$SUPPORTSTS[$jsondata['new_values']['Status']][1].'('.$jsondata['new_values']['Status'].')</td></tr>';
					   }
					   if($jsondata['old_values']['additionalInfo']!=$jsondata['new_values']['additionalInfo'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">Issue Details:</td><td style="background:#ddd;">'.$jsondata['old_values']['additionalInfo'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['additionalInfo'].'</td></tr>';
					   }
					   if($jsondata['old_values']['identificationInfo']!=$jsondata['new_values']['identificationInfo'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">Issue Identification:</td><td style="background:#ddd;">'.$jsondata['old_values']['identificationInfo'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['identificationInfo'].'</td></tr>';
					   }
					   if($jsondata['old_values']['closingNote']!=$jsondata['new_values']['closingNote'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">Issue Resolution:</td><td style="background:#ddd;">'.$jsondata['old_values']['closingNote'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['closingNote'].'</td></tr>';
					   }
					   if($jsondata['old_values']['noOfVisit']!=$jsondata['new_values']['noOfVisit'])
					   {
					     $mailText .='<tr><td style="background:#ddd;">No of site visit:</td><td style="background:#ddd;">'.$jsondata['old_values']['noOfVisit'].'</td><td style="background:#ddd;">'.$jsondata['new_values']['noOfVisit'].'</td></tr>';
					   }
					   
                    }
					if($support['auditCode']=="SPT_ADD"){
						$mailText="";
					 $mailText .='<tr><td style="background:#ddd;">DateofReport</td><td style="background:#ddd;">'.$jsondata['DateofReport'].'</td></tr>';
					 $mailText .='<tr><td style="background:#ddd;">ServiceReportNo</td><td style="background:#ddd;">'.$jsondata['ServiceReportNo'].'</tr>';
					 $cus=getCustomerDetails($jsondata['CustomerID']);
					 $mailText .='<tr><td style="background:#ddd;">CustomerID</td><td style="background:#ddd;">'.$cus['Name'].'('.$jsondata['CustomerID'].')</td></tr>';
					 $mailText .='<tr><td style="background:#ddd;">Status</td><td style="background:#ddd;">'.$SUPPORTSTS[$jsondata['Status']][1].'('.$jsondata['Status'].')</td></tr>';

					$technicianID=getEmpDetails($jsondata['technicianID']);
					 $mailText .='<tr><td style="background:#ddd;">Assign To</td><td style="background:#ddd;">'.$technicianID['name'].'</td></tr>';
					 $mailText .='<tr><td style="background:#ddd;">Additional Info</td><td style="background:#ddd;">'.$jsondata['additionalInfo'].'</td></tr>';
				    }
				    $empName=getEmpDetails($support['triggeredBy']);
                    $sDetails=getSupportDetails($support['targetID']);
				    $html .='<tr>
					<td style="border-top:solid 1px;"><a href="'.$root.'support.php?sSearch='.$SUPPORTTYPE[$jsondata['Classification']].'" target="_blank">'.$SUPPORTTYPE[$jsondata['Classification']].'</a></td>
					<td style="border-top:solid 1px;">'.$empName['name'].'</td>
				    <td style="border-top:solid 1px;">'.$SUPPORTSTS[$sDetails['Status']][1].'</td>
				    <td style="border-top:solid 1px;">'.$AUDITCODES[$support['auditCode']].'</td>
					<td style="border-top:solid 1px;"><table style="border:solid 1px; width:100%">'.$mailText.'</table></td>
					</tr>
					';
				}
				$html .='</tbody>
			<table>
        </div>';
}
$inventoryMoveData=getAllDailyAuditReport('MAL_LUP','HRD_LUP','OIT_LUP','SIM_LUP');
if(count($inventoryMoveData)>0)
{
	$html .='<div class="section" style="    background: none;    padding: 0px;"></div>
        <div class="todayrow" style="border:2px solid #004c00;border-bottom:solid;background:#4485f6;">

          <div class="update" style="color:#FFF;float: left;width: auto;font-size: 17px;font-weight: 500;line-height: normal;padding: 8px;">
		  <font style="color:#FFF;">Audit Report for</font>
            Inventory Movement
		  </div>
			<table class="details"  cellpadding="" style="border:1px solid;width:100%;background:#eee;">
				<thead>
					<tr>
					<th style="font-size:14px;font-weight:700;line-height:normal;text-align: left; padding:10px;background:#ddd;">ItemID</th>
					<th style="font-size:14px;font-weight:700;line-height:normal;text-align: left; padding:10px;background:#ddd;">Action</th>
					<th style="font-size:14px;font-weight:700;line-height:normal;text-align: left; padding:10px;background:#ddd;">Trigger By</th>
					<th style="font-size:14px;font-weight:700;line-height:normal;text-align: left; padding:10px;background:#ddd;">Audit Details</th>
					</tr>
				</thead>
				<tbody>';
				foreach($inventoryMoveData as $invMove)
        		{
                    $mailText="";
				    $empName=getEmpDetails($invMove['triggeredBy']);
                    if($invMove['auditCode']=='MAL_LUP')
					{
						$itemName=getInvenItemDetails($invMove['targetID'],'MAL_LUP');
						$itemDetails='<a href="'.$root.'machine-tools.php?sSearch='.$itemName.'" target="_blank">'.$itemName.' ( '.$invMove['targetID'].' )</a>';
					}
                    if($invMove['auditCode']=='HRD_LUP')
					{
						$itemName=getInvenItemDetails($invMove['targetID'],'HRD_LUP');
						$itemDetails='<a href="'.$root.'hardware-inventory.php?sSearch='.$itemName.'" target="_blank">'.$itemName.' ( '.$invMove['targetID'].' )</a>';
					}
                    if($invMove['auditCode']=='OIT_LUP')
					{
						$itemName=getInvenItemDetails($invMove['targetID'],'OIT_LUP');
						$itemDetails='<a href="'.$root.'office-item.php?sSearch='.$itemName.'" target="_blank">'.$itemName.' ( '.$invMove['targetID'].' )</a>';
					}
                    if($invMove['auditCode']=='SIM_LUP')
					{
						$itemName=getInvenItemDetails($invMove['targetID'],'SIM_LUP');
						$itemDetails='<a href="'.$root.'sim-inventory.php?sSearch='.$itemName.'" target="_blank">'.$itemName.' ( '.$invMove['targetID'].' )</a>';
					}
                  $mailText="";
				  $arraydata=$invMove['data'];
				  $jsondata=json_decode($arraydata,true);
                  $mailText='<tr><td style="background:#ddd;">Location:</td><td style="background:#ddd;">'.$jsondata['Previous_Location'].'</td><td style="background:#ddd;">'.$jsondata['Current_Location'].'</td></tr>';
                  $mailText .='<tr><td style="background:#ddd;">Remarks:</td><td style="background:#ddd;">'.$jsondata['Previous_Remarks'].'</td><td style="background:#ddd;">'.$jsondata['Current_Remarks'].'</td></tr>';
				    $html .='<tr>
					<td style="border-top:solid 1px;">'.$itemDetails.'</td>
					<td style="border-top:solid 1px;">'.$AUDITCODES[$invMove['auditCode']].'</td>
					<td style="border-top:solid 1px;">'.$empName['name'].'</td>
					<td style="border-top:solid 1px;"><table style="border:solid 1px; width:100%">'.$mailText.'</table></td>
					</tr>';
					 $mailText="";
				}
				$html .='</tbody>
			<table>
        </div>';
}
if(count($purchaseData)<=0 AND  count($employeeData)<=0 AND  count($suppliersData)<=0 AND  count($supportData)<=0 AND  count($customersData)<=0)
{
$html .='<h3 style="color: #004C00">Today No Task Performed By Team.</h3>';
}
$html .=' </div>
    </div>
  </body>
</html>';

foreach($permission=$permissionsArray["inventory"]["411"]["notification"] as $role)
	{
    $admins=getAllempBYDaily($role);
		foreach($admins as $admin)
		{
		$emails[]=$admin['email'];
		}
	}
$emailTo=implode(',',$emails);
$date=date('jS F Y/G:i a');
$cDate = date("H");
if($cDate == "20")
{
	$subject='Daily Summary of '.$date;
}else{
	$subject='Interim Update of '.$date;
}

//$emailTo="vivek@dynakode.com";
$mailSender=mailSend($emailTo,$subject,$html);
?>