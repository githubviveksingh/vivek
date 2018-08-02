<?php
include("includes/check_session.php");
include("includes/header.php");
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
                        <?php
                        if(isset($_SESSION['error'])){?>
                            <div class="alert alert-danger"><?php echo $_SESSION["error"];?></div>
                        <?php unset($_SESSION["error"]);}
                        ?>
                         <h4>Dashboard</h4>
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
    </body>
</html>
