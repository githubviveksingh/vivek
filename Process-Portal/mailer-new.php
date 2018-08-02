<?php
date_default_timezone_set("Asia/Kolkata");
include("PHPMailer/class.phpmailer.php");
include'includes/connect.php';
include'includes/functions.php';
$root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
?>
<!DOCTYPE html>
<html>
<head>
	<title>index</title>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/daily-mail.style.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</head>
<body>
	<div class="outer_table table-responsive">
	
	<div class="main_sec">
		<h4>Daily Audit Report for <?php echo date('jS  F Y'); ?>  for Process Portal</h4>
	   </div>
	<div class="main_sec2">
		<h4>Audit Report for Purchase Section</h4>
	</div>
	<?php $purchaseData=getAllDailyAuditReport('PUR_ADD','PUR_STATUS','PUR_UPD','');
	if(count($purchaseData)>0)
	{ ?>
		<table class="table table_cutom">
			<tr>
				<th>PO Reference</th>
				<th>Status</th>
				<th>Trigger By</th>
				<th>Action</th>
			</tr>
	<?php $mailText="";
		foreach($purchaseData as $purchase)
		{
		  $arraydata=$purchase['data'];
		  $jsondata=json_decode($arraydata,true);
		  if($purchase['auditCode']=="PUR_UPD"){
			$mailText="";
			if($jsondata['old_values']['PurchaseID']!=$jsondata['new_values']['PurchaseID'])
			{ ?>
				<td class="text_blue"><?php echo $jsondata['old_values']['PurchaseID']; ?></td>
				
				<td>Purchase Cancelled</td>
				<td>Praveenjyot Singh</td>
				<td>Purchase Status Changed</td>
			</tr>
			<div><tr ><td class="border_custom" colspan="4"><span>Audit Details</span></td></tr></div>
			<tr>
				<td class="bold_font" colspan="1">Previous_Status:</td>
				<td colspan="2" >New Entry(411)</td>
			</tr>
			<tr>
				<td class="bold_font" colspan="1">Current_Status:</td>
				<td colspan="2" >Purchase Cancelled(415)</td>
			</tr>
		</table>
		<table class="table table_cutom">
			<tr>
				<th>PO Reference</th>
				<th>Status</th>
				<th>Trigger By</th>
				<th>Action</th>
			</tr>
			<tr>
				<td class="text_blue">Internal Stock 7</td>
				<td>Purchase Cancelled</td>
				<td>Praveenjyot Singh</td>
				<td>Purchase Status Changed</td>
			</tr>
			<tr ><td class="border_custom" colspan="4"><span>Audit Details</span></td></tr>
			<tr>
				<td class="bold_font" colspan="1">Previous_Status:</td>
				<td colspan="2" >New Entry(411)</td>
			</tr>
			<tr>
				<td class="bold_font" colspan="1">Current_Status:</td>
				<td colspan="2" >Purchase Cancelled(415)</td>
			</tr>
		</table>
		<table class="table table_cutom">
			<tr>
				<th>PO Reference</th>
				<th>Status</th>
				<th>Trigger By</th>
				<th>Action</th>
			</tr>
			<tr>
				<td class="text_blue">Internal Stock 8</td>
				<td>Purchase Cancelled</td>
				<td>Praveenjyot Singh</td>
				<td>Purchase Status Changed</td>
			</tr>
			<tr ><td class="border_custom" colspan="4"><span>Audit Details</span></td></tr>
			<tr>
				<td class="bold_font" colspan="1">Previous_Status:</td>
				<td colspan="2" >New Entry(411)</td>
			</tr>
			<tr>
				<td class="bold_font" colspan="1">Current_Status:</td>
				<td colspan="2" >Purchase Cancelled(415)</td>
			</tr>
		</table>
		<table class="table table_cutom">
			<tr>
				<th>PO Reference</th>
				<th>Status</th>
				<th>Trigger By</th>
				<th>Action</th>
			</tr>
			<tr>
				<td class="text_blue">Internal Stock 9</td>
				<td>Purchase Cancelled</td>
				<td>Praveenjyot Singh</td>
				<td>Purchase Status Changed</td>
			</tr>
			<tr ><td class="border_custom" colspan="4"><span>Audit Details</span></td></tr>
			<tr>
				<td class="bold_font" colspan="1">Previous_Status:</td>
				<td colspan="2" >New Entry(411)</td>
			</tr>
			<tr>
				<td class="bold_font" colspan="1">Current_Status:</td>
				<td colspan="2" >Purchase Cancelled(415)</td>
			</tr>
		</table>
		<table class="table table_cutom">
			<tr>
				<th>PO Reference</th>
				<th>Status</th>
				<th>Trigger By</th>
				<th>Action</th>
			</tr>
			<tr>
				<td class="text_blue">DK/NB/17-18/L</td>
				<td>Purchase Closed</td>
				<td>Aashish Manocha</td>
				<td>Purchase Added</td>
			</tr>
			<tr ><td class="border_custom" colspan="4"><span>Audit Details</span></td></tr>
			<tr>
				<td class="bold_font" colspan="1">PurchaseID</td>
				<td colspan="2" >5</td>
			</tr>
			<tr>
				<td class="bold_font" colspan="1">POReference</td>
				<td colspan="2" >DK/NB/17-18/L</td>
			</tr>
			<tr>
				<td class="bold_font" colspan="1">Purchase Classification</td>
				<td colspan="2" >Purchased for Sales(101)</td>
			</tr>
			<tr>
				<td class="bold_font" colspan="1">Quantity</td>
				<td colspan="2" >10</td>
			</tr>
			<tr>
				<td class="bold_font" colspan="1">SupplierID</td>
				<td colspan="2" >DYNAKODE INNOVATION PVT LTD(26)</td>
			</tr>
		</table>
		<table class="table table_cutom">
			<tr>
				<th>PO Reference</th>
				<th>Status</th>
				<th>Trigger By</th>
				<th>Action</th>
			</tr>
			<tr>
				<td class="text_blue">DK/NB/17-18/L</td>
				<td>Purchase Closed</td>
				<td>Naveen Bhalla</td>
				<td>Purchase Status Changed</td>
			</tr>
			<tr ><td class="border_custom" colspan="4"><span>Audit Details</span></td></tr>
			<tr>
				<td class="bold_font" colspan="1">Previous_Status:</td>
				<td colspan="2" >New Entry(411)</td>
			</tr>
			<tr>
				<td class="bold_font" colspan="1">Current_Status:</td>
				<td colspan="2" >Initialized Purchase(412)</td>
			</tr>
		</table>
		<table class="table table_cutom">
			<tr>
				<th>PO Reference</th>
				<th>Status</th>
				<th>Trigger By</th>
				<th>Action</th>
			</tr>
			<tr>
				<td class="text_blue">DK/NB/17-18/L</td>
				<td>Purchase Closed</td>
				<td>Aashish Manocha</td>
				<td>Purchase Status Changed</td>
			</tr>
			<tr ><td class="border_custom" colspan="4"><span>Audit Details</span></td></tr>
			<tr>
				<td class="bold_font" colspan="1">Previous_Status:</td>
				<td colspan="2" >New Entry(411)</td>
			</tr>
			<tr>
				<td class="bold_font" colspan="1">Current_Status:</td>
				<td colspan="2" >Initialized Purchase(412)</td>
			</tr>
		</table>
		<table class="table table_cutom">
			<tr>
				<th>PO Reference</th>
				<th>Status</th>
				<th>Trigger By</th>
				<th>Action</th>
			</tr>
			<tr>
				<td class="text_blue">DK/NB/17-18/L</td>
				<td>Purchase Closed</td>
				<td>Aashish Manocha</td>
				<td>Purchase Status Changed</td>
			</tr>
			<tr ><td class="border_custom" colspan="4"><span>Audit Details</span></td></tr>
			<tr>
				<td class="bold_font" colspan="1">Previous_Status:</td>
				<td colspan="2" >Initialized Purchase(412)</td>
			</tr>
			<tr>
				<td class="bold_font" colspan="1">Current_Status:</td>
				<td colspan="2" >In Transit(413)</td>
			</tr>
		</table>
		<table class="table table_cutom">
			<tr>
				<th>PO Reference</th>
				<th>Status</th>
				<th>Trigger By</th>
				<th>Action</th>
			</tr>
			<tr>
				<td class="text_blue">DK/NB/17-18/L</td>
				<td>Purchase Closed</td>
				<td>Aashish Manocha</td>
				<td>Purchase Status Changed</td>
			</tr>
			<tr ><td class="border_custom" colspan="4"><span>Audit Details</span></td></tr>
			<tr>
				<td class="bold_font" colspan="1">Previous_Status:</td>
				<td colspan="2" >In Transit(413)</td>
			</tr>
			<tr>
				<td class="bold_font" colspan="1">Current_Status:</td>
				<td colspan="2" >Purchase Delivered(414)</td>
			</tr>
		</table>
		<table class="table table_cutom">
			<tr>
				<th>PO Reference</th>
				<th>Status</th>
				<th>Trigger By</th>
				<th>Action</th>
			</tr>
			<tr>
				<td class="text_blue">DK/NB/17-18/L</td>
				<td>Purchase Closed</td>
				<td>Aashish Manocha</td>
				<td>Purchase Status Changed</td>
			</tr>
			<tr ><td class="border_custom" colspan="4"><span>Audit Details</span></td></tr>
			<tr>
				<td class="bold_font" colspan="1">Previous_Status:</td>
				<td colspan="2" >Purchase Delivered(414)</td>
			</tr>
			<tr>
				<td class="bold_font" colspan="1">Current_Status:</td>
				<td colspan="2">Purchase Closed(416)</td>
			</tr>
		</table>
		<div class="main_sec2">
			<h4>Audit Report for Employee Section</h4>
		</div>
		<table class="table table_cutom">
			<tr>
				<th colspan="2">PO Reference</th>
				<th colspan="2">Trigger By</th>
				<th colspan="2">Action</th>
			</tr>
			<tr>
				<td colspan="2" class="text_blue">DK/NB/17-18/L</td>
				<td colspan="2">Aashish Manocha</td>
				<td colspan="2">Purchase Added</td>
			</tr>
			<tr ><td class="border_custom" colspan="6"><span>Audit Details</span></td></tr>
			<tr>
				<td colspan="2"></td>
				<td colspan="2"></td>
			</tr>
			<tr>
				<td colspan="2"></td>
				<td colspan="2"></td>
			</tr>
		</table>
		<div class="main_sec2">
			<h4>Audit Report for Customers</h4>
		</div>
		<table class="table table_cutom">
			<tr>
				<th colspan="2">Customer</th>
				<th colspan="2">Trigger By</th>
				<th colspan="2">Action</th>
			</tr>
			<tr>
				<td colspan="2" class="text_blue">ZIPGO TECHNOLOGIES PVT LTD</td>
				<td colspan="2">Praveenjyot Singh</td>
				<td colspan="2">Customer Updated</td>
			</tr>
			<tr ><td class="border_custom" colspan="6"><span>Audit Details</span></td></tr>
			<tr>
				<td class="bold_font" colspan="2">email:</td>
				<td>kishan@zipgo.in</td>
			</tr>
				<tr>
					<td colspan="2"></td>
					<td colspan="2">kishan@zipgo.in,kishan@zipgo.in</td>
				</tr>
			
			<tr>
				<td class="bold_font" colspan="2">email:</td>
				<td colspan="2">kishan@zipgo.in,kishan@zipgo.in</td>
			</tr>
				<tr>
					<td colspan="2"></td>
					<td colspan="2">kishan@zipgo.in</td>
				</tr>
			
		</table>
	</div>
</body>
</html>