<?php
include("includes/check_session.php");
include("includes/header.php");
$activeHref = "employee.php";
include("includes/html-header.php");
include_once("includes/connect.php");
                                include_once("includes/functions.php");
                                include_once("excel/Classes/PHPExcel/IOFactory.php");
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
                             <div class="col-md-12 tabbable-line boxless tabbable-reversed tab-content tab_0">
                                <div class="portlet box yellow">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-gift"></i><?php echo 'Upload Products.';?>
                                        </div>
                                    </div>
                                    <div class="portlet-body form">
                               <?php
			if(isset($_POST['submit'])) {
				if(isset($_FILES['uploadFile']['name']) && $_FILES['uploadFile']['name'] != "" && $_POST['ptype'] != "" ) {
					$allowedExtensions = array("xls","xlsx");
					$ext = pathinfo($_FILES['uploadFile']['name'], PATHINFO_EXTENSION);
					if(in_array($ext, $allowedExtensions)) {
						$file_size = $_FILES['uploadFile']['size'] / 1024;
						if($file_size < 50) {
							$file = "upload/".$_FILES['uploadFile']['name'];
							$isUploaded = copy($_FILES['uploadFile']['tmp_name'], $file);
							if($isUploaded) {
								try {
									//Load the excel(.xls/.xlsx) file
									$objPHPExcel = PHPExcel_IOFactory::load($file);
								} catch (Exception $e) {
									die('Error loading file "' . pathinfo($file, PATHINFO_BASENAME). '": ' . $e->getMessage());
								}									
								//An excel file may contains many sheets, so you have to specify which one you need to read or work with.
								$sheet = $objPHPExcel->getSheet(0);
								//It returns the highest number of rows
								$total_rows = $sheet->getHighestRow();
								//It returns the highest number of columns
								$highest_column = $sheet->getHighestColumn();
								
								//echo '<h4>Data from excel file</h4>';
								//echo '<table cellpadding="5" cellspacing="1" border="1" class="responsive">';
								//$query = "insert into  user_details_demo (`id`, `name`, `mobile`, `country`) VALUES";
								  $contentArray = array();
							
								//Loop through each row of the worksheet
								for($row =2; $row <= $total_rows; $row++) {
									$single_row = $sheet->rangeToArray('A' . $row . ':' . $highest_column . $row, NULL, TRUE, FALSE);
									 $strsingle=$single_row['0'];									
							 $contentArray['name']=$strsingle['1'];
							  $contentArray['mobile']=$strsingle['2'];
							  $contentArray['country']=$strsingle['3'];
								 addData("user_details_demo", $contentArray);
								}
								?> <div class="alert alert-success">
								<button class="close" data-close="alert"></button>
								<span><?php echo 'File Uploaded Successfully.';?></span>
								</div><?php
                                unlink($file);
                            } else {
								?> <div class="alert alert-danger">
								<button class="close" data-close="alert"></button>
								<span><?php echo 'File not uploaded!.';?></span>
								</div><?php
                            }
                        } else {
							?> <div class="alert alert-danger">
								<button class="close" data-close="alert"></button>
								<span><?php echo 'Maximum file size should not cross 50 KB on size!';?></span>
								</div><?php
                        }
                    } else {
						?> <div class="alert alert-danger">
								<button class="close" data-close="alert"></button>
								<span><?php echo 'This type of file not allowed!';?></span>
								</div><?php
                    }
                } else {
					?> <div class="alert alert-danger">
								<button class="close" data-close="alert"></button>
								<span><?php echo 'Select an Product Category && excel file.';?></span>
								</div><?php
                }
            }
            ?>
                     <form action="<?php echo $_SERVER['PHP_SELF'];?>"  class="form-horizontal" method="post" enctype="multipart/form-data">
                                    <div class="form-body">
                                            <div class="form-group">
                                            <label class="col-md-3 control-label">Product</label>
                                            <div class="col-md-4">
                                            <select name="ptype" class="selectbox form-control">
                                            <option></option>
                                            <option value="machine">Machine & Tools</option>
                                            <option value="hardware">Device Hardware</option>
                                            <option value="ITEM_SIM">SIM</option>
                                            </select>
                                            </div>
                                            </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Select A File</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Employee Name" type="file" name="uploadFile">
                                                    </div>
                                                </div>
                                                <div class="form-actions fluid">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <input type="submit" class="btn green" name="submit" value="Upload">
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
        <script src="assets/global/plugins/select2/js/select2.full.min.js"></script>
        <script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
      
         <script>
        $(document).ready(function(){
            $(".selectbox").select2();
            $("#doj").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            });
            $("#dor").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            });
        })

        $(document).ready(function(){
            $(".nav-item").each(function(){
                var href = $(this).find("a.nav-link").attr("href");
                console.log(href);
            })
        })
        </script>
    </body>
</html>
