<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="index.php">
                <img src="assets/layouts/layout/img/logo.png" alt="logo" class="logo-default" /> </a>
            <div class="menu-toggler sidebar-toggler">
                <span></span>
            </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
            <span></span>
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">

                <li class="dropdown dropdown-user">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <span class="username username-hide-on-mobile"> <?php echo ucfirst($_SESSION['user']['name']);?> </span>
                    </a>
                    <?php $identifier= $_SESSION['user']['identifier'];  ?>
					<ul class="dropdown-menu dropdown-menu-default">
                                    <li>
                                        <a href="view-employee-details.php?empid=<?php echo $identifier; ?>">
                                            <i class="icon-user"></i> My Profile </a>
                                    </li>
                                    <li>
                                        <a href="change-password.php">
                                            <i class="icon-calendar"></i>Change Password</a>
                                    </li>
                                    <li class="divider"> </li>                                   
                                    <li>
                                        <a href="logout.php" title="logout">
                                            <i class="icon-key"></i> Log Out </a>
                                    </li>
                                </ul>
                </li>
                <li class="dropdown dropdown-quick-sidebar-toggler">
                    <a href="logout.php" class="dropdown-toggle">
                        <i class="icon-logout"></i>
                    </a>
                </li>
                <!-- END QUICK SIDEBAR TOGGLER -->
            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>
