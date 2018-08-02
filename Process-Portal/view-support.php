<?php
include("includes/check_session.php");
include("includes/header.php");
$activeHref = "support.php";
include("includes/html-header.php");
$empData = getSupportDetails($_GET['sID']);
$empData2 = getAssignTechDetails($_GET['sID']);
?>
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
                            <div class="col-md-12">
                                <div class="portlet light portlet-fit bordered">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="icon-microphone font-green"></i>
                                            <span class="caption-subject bold font-green uppercase">Support Report No : <?php echo $empData['ServiceReportNo']; ?> </span>
                                            <span class="caption-helper"></span>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="timeline">
                                            <!-- TIMELINE ITEM -->
                                            <div class="timeline-item">
                                                <div class="timeline-badge">
                                                    <div class="timeline-icon">
                                                        <i class="fa fa-history font-red-intense"></i>
                                                    </div>
                                                </div>
                                                <div class="timeline-body">
                                                    <div class="timeline-body-arrow"> </div>
                                                    <div class="timeline-body-head">
                                                        <div class="timeline-body-head-caption">
                                                            <span class="timeline-body-alerttitle font-yellow-intense">Item Category : <?php echo $SUPPORTTYPE[$empData["Classification"]];?></span><br>
															 <span class="timeline-body-alerttitle font-yellow-intense">Item name : <?php 
															 $itemDetails=getCustomerItemsDetails($empData['itemID']);
															 $itemHrd=getInventoryDetails($itemDetails['itemID'],"tblHardware");
															 $itemSIM=getInventoryDetails($itemDetails['simID'],"tblSim");
															 $hrd=$itemHrd['IMEI'];	
															if($itemHrd['IMEI']=="")
															{
															$hrd=$itemHrd['model'];	
															}
															echo $item=$itemDetails['vehicleNo'].' | '.$hrd.' | '.$itemSIM['MSISDN'];

															?></span>
															 <p><b>Additional Info: </b><?php echo $empData['additionalInfo']; ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- END TIMELINE ITEM -->
											<!-- TIMELINE ITEM -->
                                            <div class="timeline-item">
                                                <div class="timeline-badge">
                                                    <div class="timeline-icon">
                                                        <i class="icon-docs font-yellow-haze"></i>
                                                    </div>
                                                </div>
                                                <div class="timeline-body">
                                                    <div class="timeline-body-arrow"> </div>
                                                    <div class="timeline-body-head">
                                                        <div class="timeline-body-head-caption">
                                                            <span class="timeline-body-alerttitle font-yellow-intense">Date of Report : <?php echo $empData['DateofReport'];?></span><br>
															 <span class="timeline-body-alerttitle font-yellow-intense">Customer Name : <?php $customer= getCustomerDetails($empData['CustomerID']); echo $customer['Name'].'('.$empData['CustomerID'].')';?></span><br>
															 <span class="timeline-body-alerttitle font-yellow-intense">Customer Address : <?php echo $empData2['Location'];?></span><br>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- END TIMELINE ITEM -->
											<div class="timeline-item">
                                                <div class="timeline-badge">
                                                    <div class="timeline-icon">
                                                        <i class="icon-support font-green"></i>
                                                    </div>
                                                </div>
                                                <div class="timeline-body">
                                                    <div class="timeline-body-arrow"> </div>
                                                    <div class="timeline-body-head">
                                                        <div class="timeline-body-head-caption">
                                                            <span class="timeline-body-alerttitle font-yellow-intense">Technician Name: <?php $tech=getEmpDetails($empData['technicianID']); echo $tech['name'].'('.$empData['technicianID'].')';?></span><br>
															 <span class="timeline-body-alerttitle font-yellow-intense">Date Of Resolution : <?php echo $empData['DateOfResolution'];?></span><br>
															 <span class="timeline-body-alerttitle font-yellow-intense">Status : <?php echo $SUPPORTSTS[$empData["Status"]][1].'('.$empData['Status'].')';?></span><br>	
															 <p><b>Closing Note</B>: <?php echo $empData['closingNote']; ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
        <link rel="stylesheet" href="assets/global/plugins/datatables/datatables.min.css">
        <link rel="stylesheet" href="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css">
        <script src="assets/global/plugins/datatables/datatables.min.js"></script>
        <script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"></script>
		<link href="assets/global/plugins/dropzone/dropzone.min.css" rel="stylesheet" />
        <script src="assets/global/plugins/dropzone/dropzone.min.js"></script>
    </body>

</html>
