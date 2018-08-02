<?php
include("includes/check_session.php");
include("includes/header.php");
$activeHref = "change-password.php";
$cpass=$_SESSION['user']['password'];
if(isset($_POST['submit']))
{
	$current_password=md5($_POST['Current_password']);
	$newpassword=$_POST['New_password'];
	$rt_newpassword=$_POST['Re_New_password'];
	if($current_password==$cpass)
	{
		if($newpassword==$rt_newpassword){
		$contentArray["password"] = md5($newpassword);
		updateData('tblEmployee', $contentArray, "identifier", $_SESSION['user']['identifier']);
		$msg= "New Password Updated Successfully.";
		}else{
		$msg = "Invalid New password & Re-type New Password.";
		}
	}else{
		$msg= "Invalid Current password.";
	}
}
include("includes/html-header.php");
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
                             <div class="col-lg-12 col-xs-12 col-sm-12">
                                 <div class="portlet box yellow">
                                     <div class="portlet-title">
                                         <div class="caption">
                                             <i class="fa fa-key"></i>Change Password
                                         </div>                                        
                                     </div>
                                     <div class="portlet-body">
									 <?php if($msg!=""){ ?>
                                                <div class="alert alert-success">
                                                    <button class="close" data-close="alert"></button>
                                                    <span><?php echo $msg;?></span>
                                                </div> 
									 <?php } ?>												
                                        <form class="form-horizontal" id="ChnagePass" method="post" action="">
                                            <div class="form-body">                                              
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Current Password</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Enter Current Password" type="password" name="Current_password" >
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">New Password</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" id="New_password" placeholder="Enter New Password" type="password" name="New_password" >
                                                    </div>
                                                </div> 
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Re-type New Password</label>
                                                    <div class="col-md-4">
                                                        <input class="form-control" placeholder="Re-type New Password" type="password" name="Re_New_password" >
                                                    </div>
                                                </div> 												
                                            </div>
                                            <div class="form-actions fluid">
                                                <div class="row">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <input type="submit" class="btn green" name="submit" value="Submit">
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
        <script src="assets/pages/change-password.js"></script>           
    </body>

</html>
