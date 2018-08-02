<?php 
include("includes/check_session.php");
include("includes/header.php"); ?>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<?php 
$link_array = explode('/',$_SERVER['HTTP_REFERER']);
$page = end($link_array);
$last = explode('.',$page);
if($last[0]=='invoices'){
    $activeHref = "dashboard-invoice.php";
}else{
   $activeHref     = "customers.php"; 
}
$invoice_no     = $_GET['invoice_no'];
$customer_id    = $_GET['customer'];
if(isset($invoice_no) && !empty($invoice_no)){
    $invoice        = array();
    $invoice_qry    = "SELECT * FROM tblInvoice where identifier = '".$invoice_no."'";
    $i_count        = 0;
    $invoice        = fetchData($invoice_qry, array(), $i_count);//print_r($invoice);
    
    if(isset($invoice) && !empty($invoice)){
        foreach($invoice as $inv)
        {
            $c_id           = $inv['buyer']; //customer id
            $invoice_code   = $inv['invoice_no'];
            $tax            = explode(',',$inv['tax']);
            $taxes          = count($tax);

        }

        // Get customer Details
        $cust_qry   = "SELECT * FROM tblCustomer where identifier = '".$c_id."'";
        $c_count    = 0;
        $customer   = fetchData($cust_qry, array(), $c_count);

        $cust_qry_frm_inv = "SELECT customer_info FROM tblInvoice where identifier = '".$invoice_no."'";
        $cinv_count         = 0;
        $updated_customer   = fetchData($cust_qry_frm_inv, array(), $cinv_count);
        foreach($updated_customer as $cust_frm_in)
        {
            $cinv_name      = $cust_frm_in['customer_info'];
            $json_data      = json_decode($cinv_name,true); 
            $cinv_name      = $json_data['Name'];
            $cinv_phone     = $json_data['phone'];
            $cinv_email     = $json_data['email'];
            $cinv_gst       = $json_data['GST'];
            $cinv_pan       = $json_data['PAN'];
            $cinv_address   = $json_data['address'];
        }

        foreach($customer as $cust)
        {
            $c_name     = (isset($cinv_name) && !empty($cinv_name))?$cinv_name:$cust['Name'];
            $c_phone    = (isset($cinv_phone) && !empty($cinv_phone))?$cinv_phone:$cust['phone'];
            $c_email    = (isset($cinv_email) && !empty($cinv_email))?$cinv_email:$cust['email'];
            $c_gst      = (isset($cinv_gst) && !empty($cinv_gst))?$cinv_gst:$cust['GST'];
            $c_pan      = (isset($cinv_pan) && !empty($cinv_pan))?$cinv_pan:$cust['PAN'];
            $c_address  = (isset($cinv_address) && !empty($cinv_address))?$cinv_address:$cust['address'];
        }

        // Get Invoice Description Details
        $invDesc_qry    = "SELECT * FROM tblInvoiceDesc where invoice_no = '".$invoice_no."'";
        $invDesc_count  = 0;
        $invDesc        = fetchData($invDesc_qry, array(), $invDesc_count); 
    }
    
}

if($_POST['submit']=="Create New Invoice"){ 

    $no             = count($_POST['desc_of_goods']); //item name
    $contentArray   = array();  //invoice desc detail
    $invoiceArray   = array(); //invoice detail
    $custArray      = array(); //cust desc detail

    // Start - genearte new invoice no
    $invDesc_qry    = "SELECT max(invoice_no) as invoice_no FROM tblInvoice where 1 limit 1";
    $invDesc_count  = 0;
    $invDesc        = fetchData($invDesc_qry, array(), $invDesc_count); 
    if(isset($invDesc) && !empty($invDesc)) { 
        foreach($invDesc as $invD){ 
            $invoice_no1    = substr($invD['invoice_no'], strpos($invD['invoice_no'], "-") + 1);
            $new_invoice_no = $invoice_no1+1; 
        }
    }
    //End 

    //Start -Invoice Data
    $invoiceArray['company_name']   = $_POST['company_name'];
    $invoiceArray['buyer']          = $customer_id;
    $invoiceArray['date']           = date('d-F-Y');
    $invoiceArray['total_amount']   = $_POST['total_amount'];
    $invoiceArray['invoice_no']     = 'DK/18-19/SR-'.$new_invoice_no;
    $invoiceArray['tax']            = $_POST['hidden_tax_type'];
    $invoiceArray['total_qty']      = '';     

    $invoice_no = addData("tblInvoice", $invoiceArray); 
    //End

    // Start Customer Data
    $custData['Name']               = $_POST['c_name'];
    $custData['phone']              = $_POST['c_phone'];
    $custData['email']              = $_POST['c_email'];
    $custData['GST']                = $_POST['c_gst'];
    $custData['PAN']                = $_POST['c_panno']; 
    $custData['address']            = $_POST['c_addr'];  
    $custArray['customer_info']     = json_encode($custData,true); 

    $column3                        = 'identifier';
    updateData("tblInvoice", $custArray, $column3 , $invoice_no); 
    // End Customer Data



    //Start - Invoice Desc data
    for ($i=0; $i <$no ; $i++) {     //invoice detail

    $title_of_goods     = $_POST['title_of_goods'][$i];
    if($invoiceArray!=""){ 
        
        $contentArray['name']   = $title_of_goods;
        $contentArray['desc']   = $_POST['desc_of_goods'][$i];
        $quantity               = $_POST['quantity'][$i];
        if($quantity!=""){
            $contentArray['qty']=$quantity;
        }
        $rate_per_qty=$_POST['rate_per_qty'][$i];
        if($rate_per_qty!=""){
            $contentArray['rate']   = $rate_per_qty;
        }
        $contentArray['amount']     = $quantity*$rate_per_qty;
        $contentArray['invoice_no'] = $invoice_no;
        $contentArray['hsn_sac']    = '';  
        $contentArray['gst_rate']   = '';

        $identifier = $_POST['identifier'][$i];
        if(isset($identifier) && !empty($identifier)) {
            $column1    = 'identifier';
            $id         = updateData("tblInvoiceDesc", $contentArray, $column1 , $identifier);
        }else{
            $id = addData("tblInvoiceDesc", $contentArray);
        }
    }
    //End    
    } 
    $_SESSION["success"] = "New Item Details Added Successfully.";
    header("Location: {$_SESSION['history_link']}"); 
}

 
if($_POST['update']=="Update"){ 

   $no = count($_POST['title_of_goods']);//item name
    
   $contentArray    = array();  //invoice desc detail
   $custArray       = array(); //customer detail
   $invoiceArray    = array(); //invoice detail
   

   //Start- Invoice Data
   $invoiceArray['company_name']    = $_POST['company_name'];
   $invoiceArray['buyer']           = $customer_id;
   $invoiceArray['date']            = date('d-F-Y');
   $invoiceArray['total_amount']    = $_POST['total_amount'];
   $invoiceArray['tax']             = $_POST['hidden_tax_type'];
   $invoiceArray['total_qty']       = ''; 

   $column2 = 'identifier';
   updateData("tblInvoice", $invoiceArray, $column2 , $invoice_no);
   //End

   //Start - Customer data
   $custData['Name']            = $_POST['c_name'];
   $custData['phone']           = $_POST['c_phone'];
   $custData['email']           = $_POST['c_email'];
   $custData['GST']             = $_POST['c_gst'];
   $custData['PAN']             = $_POST['c_panno']; 
   $custData['address']         = $_POST['c_addr'];   
   $custArray['customer_info']  = json_encode($custData,true); 

   $column3     = 'identifier';
   updateData("tblInvoice", $custArray, $column3 , $invoice_no);
   //End

   //Start- Invoice Desc Data
   for ($i=0; $i <$no ; $i++) { 

       $title_of_goods=$_POST['title_of_goods'][$i];
       if($title_of_goods!=""){

            $identifier                 = $_POST['identifier'][$i];
            $contentArray['invoice_no'] = $invoice_no;
            $contentArray['name']       = $title_of_goods;
            $contentArray['desc']       = $_POST['desc_of_goods'][$i];
            $quantity                   = $_POST['quantity'][$i];
            if($quantity!=""){
                $contentArray['qty']    = $quantity;
            }
            $rate_per_qty               = $_POST['rate_per_qty'][$i];
            if($rate_per_qty!=""){
                $contentArray['rate']   = $rate_per_qty;
            }
            $contentArray['amount']     = $quantity*$rate_per_qty;
            $contentArray['hsn_sac']    = '';  
            $contentArray['gst_rate']   = '';
                
            if(isset($identifier) && !empty($identifier) && $identifier!='') { 
                $column1    = 'identifier'; 
                $id         = updateData("tblInvoiceDesc", $contentArray, $column1 , $identifier);
            }else{ 
                $id         = addData("tblInvoiceDesc", $contentArray);
            }
                
            
        }
         
   }
    //End
    $_SESSION["success"] = "New Item Details Updated Successfully.";
    header("Location: {$_SESSION['history_link']}");   
}
include("includes/html-header.php");
?>
<style>
.select2{
    width:100% !important;
}
.btn.green.remove_field {
    margin-top: 22px;
}
 
</style>

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
        <div class="page-wrapper">
            <?php
                include("includes/topheader.php");
            ?>
            <div class="clearfix"> </div>
            <div class="page-container">
                <?php
                    include("includes/left_sidebar.php");
                ?>
                <div class="page-content-wrapper">
                    <div class="page-content">
                         <div class="row">
                             <div class="col-md-12 tabbable-line boxless tabbable-reversed tab-content tab_0">
                                <div class="portlet box yellow">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-gift"></i><?php //echo $_GET['invoice_no'];?>  : Create/Update invoice - <?php echo $invoice_code;?>
                                        </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <form class="form-horizontal" id="addItems" method="post" action="add-new-items.php?customer=<?php echo $_GET['customer'];?>&invoice_no=<?php echo $invoice_no;?>">
                                        <?php
                                            if(isset($_SESSION["success"])){?>
                                                <div class="alert alert-success">
                                                    <button class="close" data-close="alert"></button>
                                                    <span><?php echo $_SESSION["success"];?></span>
                                                </div>
                                            <?php unset($_SESSION["success"]);}
                                            ?>
                                            <?php
                                            if(isset($_SESSION["error"])){?>
                                                <div class="alert alert-danger">
                                                    <button class="close" data-close="alert"></button>
                                                    <span><?php echo $_SESSION["error"];?></span>
                                                </div>
                                            <?php unset($_SESSION["error"]);}
                                            ?>
                                            <input type="hidden" name="identifier" value="1" id="">
                                            <div class="form-body" id="containerItems">
                                                <div class="form-group">
                                                    <div class="col-md-3">
                                                    <label>Company name <span class="required" aria-required="true"> * </span> </label>
                                                        <select name="company_name"  class="form-control" required>
                                                            <option value="1">Dynakode</option>
                                                        </select>
                                                    </div>
                                                </div>                              
                                            </div>
                                            <div class="form-body" id="containerItems">
                                                <div class="form-group">
                                                    <div class="col-md-3">
                                                        <label>Customer Name <span class="required" aria-required="true"> * </span></label>
                                                        <input type="text" name="c_name" required class="form-control" value="<?php echo $c_name;?>">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Phone no <span class="required" aria-required="true"> * </span></label>
                                                        <input type="text" name="c_phone" required class="form-control" value="<?php echo $c_phone;?>">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Email Id<span class="required" aria-required="true"> * </span></label>
                                                        <input type="text" name="c_email" required class="form-control" value="<?php echo $c_email;?>">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>GST <span class="required" aria-required="true"> * </span></label>
                                                        <input type="text" name="c_gst" id="gstNo" required class="form-control" value="<?php echo $c_gst;?>">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label>Pan No <span class="required" aria-required="true"> * </span></label>
                                                        <input type="text" id="panCard" name="c_panno" required class="form-control" value="<?php echo $c_pan;?>">
                                                    </div>
                                                </div>  
                                                <div class="form-group">
                                                <div class="col-md-4">
                                                        <label>Address <span class="required" aria-required="true"> * </span></label>
                                                        <textarea name="c_addr" required class="form-control" value="<?php echo $c_address;?>"><?php echo $c_address;?></textarea>
                                                    </div>
                                                </div>                                
                                            </div>
                                            <div class="form-body items_detail" id="containerItems">
                                                <?php if($invDesc_count>0) { $i = 1;
                                                    foreach($invDesc as $invD) {  ?>
                                                        <div class="form-group">
                                                            <div class="col-md-3">
                                                            <label>Title <span class="required" aria-required="true"> * </span></label>
                                                            <input type="text" name="title_of_goods[]" required class="form-control" value="<?php echo $invD['name']?>">
                                                            <input type="hidden" name="identifier[]"  class="form-control" value="<?php echo $invD['identifier']?>">
                                                            </div>
                                                            <div class="col-md-3">
                                                            <label>Description</label>
                                                            <textarea name="desc_of_goods[]" required class="form-control" value="<?php echo $invD['desc']?>"><?php echo $invD['desc']?></textarea>
                                                            </div>
                                                            <div class="col-md-1 qty" >
                                                            <label>Qty <span class="required" aria-required="true"> * </span></label>
                                                            
                                                            <?php $qty=explode('.', $invD['qty']);

                                                                $qty1=$qty[0];

                                                                $rate=explode('.', $invD['rate']);

                                                                $rate1=$rate[0];
                                                                $individual_total=$qty1*$rate1;
                                                             ?>

                                                                <input type="text" name="quantity[]" required class="form-control quantity" value=<?php echo $qty1; ?>>
                                                                <input type="hidden"  class="form-control mainqty" value=<?php echo $qty1; ?>>
                                                                
                                                            </div>
                                                            <div class="col-md-2" id="">
                                                            <label>Rate/Qty <span class="required" aria-required="true"> * </span></label>
                                                            <input type="text" name="rate_per_qty[]" required class="form-control rate_per_qty" value="<?php echo $rate1; ?>"><input type="hidden" class="form-control mainrate" value="<?php echo $rate1; ?>">
                                                            </div>
                                                            <div class="col-md-2" id="">
                                                            <label>Total<span class="required" aria-required="true"></span></label>
                                                            <input type="text" name="individual_total[]" class="form-control individual_total" readonly value="<?php echo $individual_total; ?>">
                                                            </div>
                                                            <?php if($i == $invDesc_count) { ?>
                                                            <div class="col-md-2" id="add_field_0"  data-id="0">
                                                             </div>
                                                            <?php } ?>
                                                        </div> 
                                                    <?php $i++;} 
                                                }else{ ?>
                                                        <div class="form-group">
                                                            <div class="col-md-3">
                                                            <label>Title <span class="required" aria-required="true"> * </span></label>
                                                            <input type="text" name="title_of_goods[]" required class="form-control" value="">
                                                            </div>
                                                            <input type="hidden" name="identifier[]"  class="form-control" value="">
                                                            <div class="col-md-3">
                                                            <label>Description</label>
                                                            <input type="text" name="desc_of_goods[]" required class="form-control" value="">
                                                            </div>
                                                            <div class="col-md-1 qty" >
                                                            <label>Qty <span class="required" aria-required="true"> * </span></label>
                                                            <div class="input-group">
                                                                
                                                                <input type="text" name="quantity[]" required class="form-control quantity" value="">
                                                                <input type="hidden" class="form-control mainqty" value="0">
                                                            </div>
                                                            </div>
                                                            <div class="col-md-2" id="">
                                                            <label>Rate/Qty <span class="required" aria-required="true"> * </span></label>
                                                            <input type="text" name="rate_per_qty[]" required class="form-control rate_per_qty" value="">
                                                            <input type="hidden" class="form-control mainrate" value="0">
                                                            </div>
                                                            <div class="col-md-2" id="">
                                                            <label>Total<span class="required" aria-required="true"></span></label>
                                                            <input type="text" name="individual_total[]" class="form-control individual_total" readonly value="">
                                                            </div>
                                                            <div class="col-md-2" id="add_field_0"  data-id="0">
                                                            
                                                             </div>
                                                        </div>   
                                                <?php } ?>                             
                                            </div>
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <div class="col-md-2" id="">
                                                        <a href="javascript:void(0)" id="add_field" class="btn green add_field"><span class="fa fa-plus"></span> More</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-body" id="containerItems">
                                                <div class="form-group col-md-12">
                                                    <label>Taxes <span class="required" aria-required="true"> * </span></label>
                                                    <div class="col-md-9"> 
                                                <?php 
                                                        $query = "SELECT * FROM tblinvoice_tax ";
                                                        $count = 0;
                                                        $sql1 = fetchData($query, array(), $count);
                                                       
                                                        /*checked data to fetch and set flag*/
        
                                                        $tax_selected = "SELECT tax FROM tblInvoice where identifier = ".$invoice_no;      
                                                        $sql2 = getTaxes($tax_selected, array());

                                                        $tax = explode(',',$sql2);
                                                           foreach ($sql1 as $value) {           
                                                               if(in_array($value['name'], $tax)){
                                                                ?>
                                                                       
                                                                    <input type="checkbox" checked="true" name="tax_type" data-rate = "<?php echo $value['rate'];?>" value="<?php echo $value['name'];?>"> <?php echo $value['name']?>
                                                                    <input type="hidden" name="hidden_tax_type"> 
                                                                    <input type="hidden" name="rate" class="tax_rate" value="<?php echo $value['rate'];?>"><br>
                                                                
                                                              <?php }else{ ?>
                                                                  
                                                                    <input type="checkbox" name="tax_type" data-rate = "<?php echo $value['rate'];?>" value="<?php echo $value['name'];?>"> <?php echo $value['name'];?>
                                                                    <input type="hidden" name="hidden_tax_type"> 
                                                                    <input type="hidden" name="rate" class="tax_rate" value="<?php echo $value['rate'];?>"><br>
                                                                
                                                            <?php  }
                                                            }
                                                    ?>    
                                                    </div>
                                                    <div class="col-md-3"> 
                                                        <?php $query = "SELECT SUM(rate*qty) as subtotal FROM tblInvoiceDesc where invoice_no = '".$invoice_no."'";
                                                        $count = 0;
                                                        $sql = fetchData($query, array(), $count);
                                                        foreach($sql as $key)
                                                        { 
                                                            $subTotal = $key['subtotal'];
                                                        }
                                                        ?>
                                                        <span><b>SUBTOTAL (INR)</b> : </span><span class="subtotal"><?php echo $subTotal;?></span><br>
                                                        <span class="tax_selected"></span><br>
                                                        <input type="hidden" name="total_amount" class="total_amount">
                                                        <span style="font-size: 15px"><b>TOTAL (INR)</b> : </span>
                                                        <span class="total"><?php echo $subTotal;?></span>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="form-body" id="containerItems">
                                                <div class="form-group">
                                                    <div class="col-md-9"></div>
                                                   
                                                    <div class="col-md-3">
                                                       
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions fluid">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <input type="submit" class="btn green" name="update" value="Update" >
                                                        <input type="submit" class="btn green" name="submit" value="Create New Invoice">
                                                         <a href="upload-invoices.php?customer=<?php echo $_GET['customer'];?>" class="btn green">Cancel</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                             </div>
                         </div>
                    </div>
                </div>
            </div>
            <?php
                include("includes/footer.php");
            ?>
        </div>

        <?php
            include("includes/common_js.php");
        ?>
        <link href="assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" />
        <link href="assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" />
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" />
        
         <link rel="stylesheet" href="assets/global/plugins/datatables/datatables.min.css">
        <link rel="stylesheet" href="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css">
        <script src="assets/global/plugins/datatables/datatables.min.js"></script>
        <script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"></script>

        <script src="assets/global/plugins/select2/js/select2.full.min.js"></script>
        <script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="assets/pages/add-customerItem.js"></script>     
        <script src="assets/pages/add-new-items.js"></script>       
        <script type="text/javascript">
        var counter = 1;
        $(function(){
             $('.add_field').click(function(e){ 
                var max_fields_limit      = 10;
                e.preventDefault();
                if(counter < max_fields_limit){ //check conditions
                    counter++;
                    $('.items_detail').append('<div class="form-group"><div class="col-md-3"><label>Title <span class="required" aria-required="true"> * </span></label><input type="text" name="title_of_goods[]" required class="form-control"></div><div class="col-md-3"><label>Description</label><textarea name="desc_of_goods[]" required class="form-control" value=""></textarea></div><div class="col-md-1 qty"><label>Qty <span class="required" aria-required="true"> * </span></label><input type="text" name="quantity[]" value="1" required class="form-control quantity"><input type="hidden" class="form-control mainqty" value="1"></div><div class="col-md-2" ><label>Rate/Qty <span class="required" aria-required="true"> * </span></label><input type="text" required name="rate_per_qty[]" class="form-control rate_per_qty" value="0"><input type="hidden" class="form-control mainrate" value="0"></div><div class="col-md-2" id=""><label>Total<span class=""></span></label><input type="text" name="individual_total[]" class="form-control individual_total" readonly value=""></div><a href="javascript:void(0)" class="btn green remove_field"><span class="fa fa-close"></span></a></div>');
                }
            });
            
            $('.items_detail').on("click",".remove_field", function(e){ //user click on remove text links
                  e.preventDefault(); $(this).parent('div').remove(); counter--;
                  
              })
           
        });
        </script>
        <script>
        $(document).ready(function(){   
            $(document).ready(function(){  
            var quantitiy=1;
                $(document).on('click','.quantity-right-plus',function(e){
                    
                    // Stop acting like a button
                    e.preventDefault();
               
                    var quantity = parseInt($(this).closest('div').find('.quantity').val());
                    
                    // If is not undefined
                        
                        $(this).closest('div').find('.quantity').val(quantity + 1);

                      
                        // Increment
                    
                });

                $(document).on('click','.quantity-left-minus',function(e){                
                    // Stop acting like a button
                    e.preventDefault();
                    // Get the field name
                    var quantity = parseInt($(this).closest('div').find('.quantity').val());
                    
                    // If is not undefined
                  
                        // Increment
                        if(quantity>1){
                        $(this).closest('div').find('.quantity').val(quantity - 1);
                        }
                });

            function fetch_itemList(str, id)
            {
                if (str == "") {
                    $("#ItemsList_"+id).html("");
                    return;
                }else {
                    if(str == "301"){
                    $("#vehicle_"+id).removeClass("hide");
                    $("#sim_"+id).removeClass("hide");
                    }else{
                    $("#vehicle_"+id).addClass("hide");
                    $("#sim_"+id).addClass("hide");
                    }
                    $.ajax({
                        url: "ajax/fetchHrdByType.php",
                        type: "get",
                        data: {q: str},
                        success: function(data){
                            $("#ItemsList_"+id).html(data);
                        }
                    })
                }
            }           
            $(".selectbox").select2();
            $(".selectboxItem").select2();
            $("#doj").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            });
            $("#dor").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            });

            $("#addItems").on("change", ".selectbox", function(){
                var id = $(this).attr("data-id");
                var selectedVal = $(this).val();
                fetch_itemList(selectedVal, id);
            })
        })
            function fetch_itemList(str, id)
            {
                if (str == "") {
                    $("#ItemsList_"+id).html("");
                    return;
                }else {
                    if(str == "301"){
                    $("#vehicle_"+id).removeClass("hide");
                    $("#sim_"+id).removeClass("hide");
                    }else{
                    $("#vehicle_"+id).addClass("hide");
                    $("#sim_"+id).addClass("hide");
                    }
                    $.ajax({
                        url: "ajax/fetchHrdByType.php",
                        type: "get",
                        data: {q: str},
                        success: function(data){
                            $("#ItemsList_"+id).html(data);
                        }
                    })
                }
            }           
            $(".selectbox").select2();
            $(".selectboxItem").select2();
            $("#doj").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            });
            $("#dor").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            });

            $("#addItems").on("change", ".selectbox", function(){
                var id = $(this).attr("data-id");
                var selectedVal = $(this).val();
                fetch_itemList(selectedVal, id);
            })
        })
        $(document).ready(function(){
            $(".nav-item").each(function(){
                var href = $(this).find("a.nav-link").attr("href");
                console.log(href);
            }); 
            $('#dataTables').DataTable({
                 "serverSide": true,
                 "processing": true,
                 "columnDefs": [ {
                        "targets": [0,5],
                        "orderable": false
                        } ],
                 "order": [[ 1, "asc" ]],
                 "ajax": './ajax/customer-items-list.php?customer=<?php echo $_GET['empid']; ?>'
            });   

            
            var chkd = $('input:checkbox:checked');
             
            if(chkd) {  
                var vals = chkd.map(function() { 
                    return this.value;
                })
                .get().join(',');
                 
                $('input[name="hidden_tax_type"]').attr('value',vals); 
                // $('input[name="rate"]').val(); 
                var tax_rate =  $(this).data('rate'); 
                var vals1 = chkd.map(function() {
                    var tax_name = this.value;  
                    if(tax_name == 'DIMTS FEES'){
                        var tax_rate = $(this).data('rate'); 
                        var subtotal = $('.subtotal').text();
                        var tax_amt = parseInt(subtotal) + parseInt(tax_rate);
                        vals1 = '<span>'+tax_name+' ('+tax_rate+') : '+Math.round(tax_amt,2)+'</span>';
                    }else{                        
                        var tax_rate = $(this).data('rate');
                        var tax_amt = ($('.subtotal').text()) * (tax_rate/100);
                        vals1 = '<span>'+tax_name+' ('+tax_rate+'%) : '+Math.round(tax_amt,2)+'</span>'; 
                    }
                    return vals1;
                }).get().join('<br> ');

                var tax_amt = 0;
                $.each($( "input:checkbox:checked" ), function(){ 
                    $('.tax_selected').html(vals1);
                    var tax_name = this.value; 
                    if(tax_name == 'DIMTS FEES'){
                        var tax_rate = $(this).data('rate');
                        var subtotal = $('.subtotal').text();
                        tax_amt += tax_rate; 
                        var total =  $('.total').text(parseInt(subtotal) + tax_amt);
                        $('.total_amount').val(Math.round(parseInt(subtotal) + tax_amt),2);
                    }else{
                        var tax_rate = $(this).data('rate');
                        var subtotal = $('.subtotal').text();
                        tax_amt += subtotal * (tax_rate/100); 
                        var total =  $('.total').text(parseInt(subtotal) + tax_amt);
                        $('.total_amount').val(Math.round(parseInt(subtotal) + tax_amt),2);
                    }
                });              
            }
                



            $('input:checkbox').on('change', function() {
                var chkd = $('input:checkbox:checked');
                console.log(chkd.length);
                 
                if(chkd) {  
                    var vals = chkd.map(function() { 
                        return this.value;
                    })
                    .get().join(',');
                     
                    $('input[name="hidden_tax_type"]').attr('value',vals); 
                    // $('input[name="rate"]').val(); 
                    var tax_rate =  $(this).data('rate'); 
                    var vals1 = chkd.map(function() {
                        var tax_name = this.value; 
                        if(tax_name == 'DIMTS FEES'){
                            var tax_rate = $(this).data('rate'); 
                            var subtotal = $('.subtotal').text();
                            var tax_amt = parseInt(tax_rate);
                            vals1 = '<span>'+tax_name+' ('+tax_rate+') : '+Math.round(tax_amt,2)+'</span>';
                        }else{                        
                            var tax_rate = $(this).data('rate');
                            var tax_amt = ($('.subtotal').text()) * (tax_rate/100);
                            vals1 = '<span>'+tax_name+' ('+tax_rate+'%) : '+Math.round(tax_amt,2)+'</span>'; 
                        }
                        return vals1;
                    }).get().join('<br> ');
 
                    var tax_amt = 0;
                    $.each($( "input:checkbox:checked" ), function(){ 
                        $('.tax_selected').html(vals1);
                        var tax_name = this.value; 
                        if(tax_name == 'DIMTS FEES'){
                            var tax_rate = $(this).data('rate');
                            var subtotal = $('.subtotal').text();
                            tax_amt += tax_rate; 
                            var total =  $('.total').text(parseInt(subtotal) + tax_amt);
                            $('.total_amount').val(Math.round(parseInt(subtotal) + tax_amt),2);
                        }else{
                            var tax_rate = $(this).data('rate');
                            var subtotal = $('.subtotal').text();
                            tax_amt += subtotal * (tax_rate/100); 
                            var total =  $('.total').text(parseInt(subtotal) + tax_amt);
                            $('.total_amount').val(Math.round(parseInt(subtotal) + tax_amt),2);
                        }
                    });              
                }
                
                 
                 
                //alert($("input:checkbox").is(':checked'));
 
                if($("input:checkbox").is(':checked')==false){
                    var tax_name = this.value; 
                    $('.tax_selected').html(vals1);
                    if(tax_name == 'DIMTS FEES'){
                        var tax_rate = $(this).data('rate');
                        var tax_amt = tax_rate; 
                        var subtotal = $('.total').text();
                        var total =  $('.total').text(parseInt(subtotal) - parseInt(tax_amt));
                        $('.total_amount').val(Math.round(parseInt(subtotal) + tax_amt),2);
                    }else{
                        var tax_rate = $(this).data('rate');
                        var tax_amt = ($('.subtotal').text()) * (tax_rate/100); 
                        var subtotal = $('.subtotal').text();
                        var total =  $('.total').text(parseInt(subtotal) - parseInt(tax_rate/100));
                        $('.total_amount').val(Math.round(parseInt(subtotal) + tax_amt),2);
                    }
                }
            });

          $(document).on('blur','input.form-control.quantity', function() {  
                var ctAmt = 0;
                $('.subtotal').text('0');

                var qty  = this.value = this.value.replace(/[^0-9\.]/g,'');
                if(qty=='' || qty<=0){
                    alert('Enter possitive number for quantity');
                    qty=$(this).parents('.qty').find('.mainqty').val();
                    $(this).val(qty);
                    $('input.form-control.quantity').each(function(){ 
                        ctAmt += $(this).val() * $(this).parent().next().children('input[name*="rate_per_qty[]"]').val();
                        var ind_total = $(this).val() * $(this).parent().next().children('input[name*="rate_per_qty[]"]').val();                    
                        $(this).closest('.form-group').find('input[name="individual_total[]"]').val(ind_total);  
                        $('.subtotal').text( ctAmt );
                    });
                    var chkd = $('input:checkbox:checked');
                    if(chkd) {  
     
                        var vals = chkd.map(function() { 
                            return this.value;
                        })
                        .get().join(', ');
                         
                        $('input[name="hidden_tax_type"]').attr('value',vals); 
                        // $('input[name="rate"]').val(); 
                        var tax_rate =  $(this).data('rate'); 
                        var vals1 = chkd.map(function() {
                            var tax_name = this.value; 
                            var tax_rate = $(this).data('rate');
                            var tax_amt = Math.round(($('.subtotal').text()) * (tax_rate/100),2);
                            vals1 = '<span>'+tax_name+' ('+tax_rate+'%) : '+tax_amt+'</span>'; 
                            return vals1;
                        }).get().join('<br> ');
     
                        var tax_amt = 0;
                        $.each($( "input:checkbox:checked" ), function(){ 
                            $('.tax_selected').html(vals1);
                            var tax_rate = $(this).data('rate');
                            var subtotal = $('.subtotal').text();
                            tax_amt += subtotal * (tax_rate/100); 
                            var total =  $('.total').text(Math.round(parseInt(subtotal) + tax_amt),2);
                            $('.total_amount').val(Math.round(parseInt(subtotal) + tax_amt),2);
                        });                
                    }
                }else{

                    $('input.form-control.quantity').each(function(){
                        ctAmt += $(this).val() * $(this).parent().next().children('input[name*="rate_per_qty[]"]').val();                        
                        $('.subtotal').text( ctAmt );
                    });
                    var ind_total = $(this).val() * $(this).parent().next().children('input[name*="rate_per_qty[]"]').val();                    
                    $(this).closest('.form-group').find('input[name="individual_total[]"]').val(ind_total); 
                    var chkd = $('input:checkbox:checked');
                    if(chkd) {  
     
                        var vals = chkd.map(function() { 
                            return this.value;
                        })
                        .get().join(', ');
                         
                        $('input[name="hidden_tax_type"]').attr('value',vals); 
                        // $('input[name="rate"]').val(); 
                        var tax_rate =  $(this).data('rate'); 
                        var vals1 = chkd.map(function() {
                            var tax_name = this.value; 
                            var tax_rate = $(this).data('rate');
                            var tax_amt = Math.round(($('.subtotal').text()) * (tax_rate/100),2);
                            vals1 = '<span>'+tax_name+' ('+tax_rate+'%) : '+tax_amt+'</span>'; 
                            return vals1;
                        }).get().join('<br> ');
     
                        var tax_amt = 0;
                        $.each($( "input:checkbox:checked" ), function(){ 
                            $('.tax_selected').html(vals1);
                            var tax_rate = $(this).data('rate');
                            var subtotal = $('.subtotal').text();
                            tax_amt += subtotal * (tax_rate/100); 
                            var total =  $('.total').text(Math.round(parseInt(subtotal) + tax_amt),2);
                            $('.total_amount').val(Math.round(parseInt(subtotal) + tax_amt),2);
                        });                
                    }
                 }
            });
            $(document).on('blur','input.form-control.rate_per_qty', function() {  
                var ctAmt = 0;
                $('.subtotal').text('0');
                var rate  = this.value = this.value.replace(/[^0-9\.]/g,'');
                if(rate=='' || rate<=0){
                    alert('Enter possitive number for rate');
                    rate=$(this).parent().find('.mainrate').val();
                    $(this).val(rate);
                    $('input.form-control.rate_per_qty').each(function(){
                    ctAmt += $(this).val() * $(this).parent().prev().children('input[name*="quantity[]"]').val();
                    $('.subtotal').text( ctAmt );
                });
                var chkd = $('input:checkbox:checked');
                if(chkd) {  
 
                    var vals = chkd.map(function() { 
                        return this.value;
                    })
                    .get().join(', ');
                     
                    $('input[name="hidden_tax_type"]').attr('value',vals); 
                    // $('input[name="rate"]').val(); 
                    var tax_rate =  $(this).data('rate'); 
                    var vals1 = chkd.map(function() {
                        var tax_name = this.value; 
                        var tax_rate = $(this).data('rate');
                        var tax_amt = Math.round(($('.subtotal').text()) * (tax_rate/100),2);
                        vals1 = '<span>'+tax_name+'('+tax_rate+'%) : '+tax_amt+'</span>'; 
                        return vals1;
                    }).get().join('<br> ');
 
                    var tax_amt = 0;
                    $.each($( "input:checkbox:checked" ), function(){ 
                        $('.tax_selected').html(vals1);
                        var tax_rate = $(this).data('rate');
                        var subtotal = $('.subtotal').text();
                        tax_amt += subtotal * (tax_rate/100); 
                        var total =  $('.total').text(Math.round(parseInt(subtotal) + tax_amt),2);
                        $('.total_amount').val(Math.round(parseInt(subtotal) + tax_amt),2);
                    });             
                }
                }else{
                $('input.form-control.rate_per_qty').each(function(){
                    ctAmt += $(this).val() * $(this).parent().prev().children('input[name*="quantity[]"]').val();
                    var ind_total = $(this).val() * $(this).parent().prev().children('input[name*="quantity[]"]').val();                    
                    $(this).closest('.form-group').find('input[name="individual_total[]"]').val(ind_total);
                    $('.subtotal').text( ctAmt );
                });
                var chkd = $('input:checkbox:checked');
                if(chkd) { 
                    var vals = chkd.map(function() { 
                        return this.value;
                    })
                    .get().join(', ');
                     
                    $('input[name="hidden_tax_type"]').attr('value',vals); 
                    // $('input[name="rate"]').val(); 
                    var tax_rate =  $(this).data('rate'); 
                    var vals1 = chkd.map(function() {
                        var tax_name = this.value; 
                        var tax_rate = $(this).data('rate');
                        var tax_amt = Math.round(($('.subtotal').text()) * (tax_rate/100),2);
                        vals1 = '<span>'+tax_name+'('+tax_rate+'%) : '+tax_amt+'</span>'; 
                        return vals1;
                    }).get().join('<br> ');
 
                    var tax_amt = 0;
                    $.each($( "input:checkbox:checked" ), function(){ 
                        $('.tax_selected').html(vals1);
                        var tax_rate = $(this).data('rate');
                        var subtotal = $('.subtotal').text();
                        tax_amt += subtotal * (tax_rate/100); 
                        var total =  $('.total').text(Math.round(parseInt(subtotal) + tax_amt),2);
                        $('.total_amount').val(Math.round(parseInt(subtotal) + tax_amt),2);
                    });                
                }
            }
 
            });
        
            $(document).on('click','.remove_field', function() {
                
                var ctAmt = 0;
                $('.subtotal').text('0');
                $('input.form-control.quantity').each(function(){
                    ctAmt += $(this).val() * $(this).parent().next().children('input[name*="rate_per_qty[]"]').val();
                    $('.subtotal').text( ctAmt );
                });
                var chkd = $('input:checkbox:checked');
                if(chkd) {  
 
                    var vals = chkd.map(function() { 
                        return this.value;
                    })
                    .get().join(',');
                     
                    $('input[name="hidden_tax_type"]').attr('value',vals); 
                    // $('input[name="rate"]').val(); 
                    var tax_rate =  $(this).data('rate'); 
                    var vals1 = chkd.map(function() {
                        var tax_name = this.value; 
                        var tax_rate = $(this).data('rate');
                        var tax_amt = Math.round(($('.subtotal').text()) * (tax_rate/100),2);
                        vals1 = '<span>'+tax_name+' ('+tax_rate+'%) : '+tax_amt+'</span>'; 
                        return vals1;
                    }).get().join('<br> ');
 
                    var tax_amt = 0;
                    $.each($( "input:checkbox:checked" ), function(){ 
                        $('.tax_selected').html(vals1);
                        var tax_rate = $(this).data('rate');
                        var subtotal = $('.subtotal').text();
                        tax_amt += subtotal * (tax_rate/100); 
                        var total =  $('.total').text(Math.round(parseInt(subtotal) + tax_amt),2);
                        $('.total_amount').val(Math.round(parseInt(subtotal) + tax_amt),2);
                    });                 
                }
            });
          
        });
        </script>
    </body>
</html>