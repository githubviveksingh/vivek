<?php
include("../includes/check_session.php");
include("../includes/connect.php");
include("../includes/functions.php");
if(isset($_POST['dataid'])){
	$rowsData=getAll_invoice_desc($_POST['dataid']);
	?>
	<h5><b><?php echo getCustomerDetails(getInvoiceDetails($_POST['dataid'])['buyer'])['Name'];?> ( <?php echo getInvoiceDetails($_POST['dataid'])['invoice_no'];?> ) </b></h5>
	<table class="table table-bordered table-hover table-striped table-responsive">
	<tr><th>Description of Goods</th><th>QTY</th><th>Rate/QTY</th><th>Amount</th></tr>
	
	<?php
    $amount=0	;	
	foreach($rowsData as $rowDt)
	{ 
	$amount +=$rowDt['amount'];
	?>
	<tr>
		<td> <?php echo $rowDt['name']; ?> </td>
		<td><?php echo $rowDt['qty']; ?> </td>
		<td><?php echo $rowDt['rate']; ?> </td>
		<td><?php echo $rowDt['amount']; ?> </td>
	</tr>
		<?php 
	}
	?>
	<tr>
    	<th> Sub Total</th>
		<th> </th>
		<th> </th>
		<td> <?php echo number_format($amount,2); ?> </td>
	</tr>	
	<tr>
    	<th> Tax</th>
		<td colspan='3'><table class="table table-bordered">
		<?php $query = "SELECT tax FROM tblinvoice where identifier='".$_POST['dataid']."' ";
		$count = 0;
		$tax_2=0;
		$tax1=0;
		$data = fetchData($query, array(), $count);		
		$data=explode(',',$data[0]['tax']);
		foreach($data as $d){			
			$query = "SELECT * FROM tblinvoice_tax where name='".$d."' ";
			$count = 0;
			$dataTax = fetchData($query, array(), $count);	
			$rate=$dataTax[0]['rate']; 
			$type=$dataTax[0]['type']; 
			?>
			<tr>
			<td><?php 	echo $dataTax[0]['name']; ?></td>
			<td><?php 	echo $rate.' '.$type;	 ?></td>
			<td><?php 	if($type=='%'){ echo number_format($amount*$rate/100,2);
			$tax1 +=$amount*$rate/100; }
               if($type=='' && $dataTax[0]['name']=="DIMTS FEES"){ 
			     echo  number_format($rowDt['qty']*$rate,2);
                 $tax_2 +=$rowDt['qty']*$rate;
			   }
			?></td>
			</tr>
			<?php 		
		} ?>
		
		</table></td>
	</tr>
	<tr>
    	<th> Total</th>
		<th> </th>
		<th> </th>
		<td> <?php echo round($amount+$tax_2+$tax1).'.00'; ?> </td>
	</tr>
	</table>
<?php 	
}
?>