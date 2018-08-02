<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
            <li class="sidebar-toggler-wrapper hide">
                <div class="sidebar-toggler">
                    <span></span>
                </div>
            </li>
		    <?php if($page=CheckSideBarPermission("company")){ ?>
            <li class="nav-item">
                <a href="company.php" class="nav-link">
                    <i class="icon-settings" aria-hidden="true"></i>
                    <span class="title">Company</span>

                </a>
            </li>
			<?php } if($page=CheckSideBarPermission("employee")){ ?>
            <li class="nav-item">
                <a href="employee.php" class="nav-link">
                    <i class="icon-users"></i>
                    <span class="title">Employee</span>

                </a>
            </li>
			<?php } if($page=CheckSideBarPermission("suppliers")){ ?>
            <li class="nav-item">
               <a class="nav-link nav-toggle" href="javascript:void(0);">
                      <i class="fa fa-space-shuttle"></i>
                    <span class="title">Suppliers</span>
                    <span class="arrow"></span>
                </a>
				<ul class="sub-menu">
                    <li class="nav-item">
                        <a href="suppliers.php" class="nav-link">
                            <span class="title">Suppliers</span>
                        </a>
                    </li>
					<li class="nav-item">
                        <a href="supplier-types.php" class="nav-link">
                            <span class="title">Suppliers Types</span>
                        </a>
                    </li>
				</ul>
            </li>
			<?php } if($page=CheckSideBarPermission("purchase")){ ?>
            <li class="nav-item">
                <a href="purchase.php" class="nav-link">
                    <i class="fa fa-cart-arrow-down"></i>
                    <span class="title">Purchase</span>

                </a>				
            </li>
			<?php }  if($page=CheckSideBarPermission("inventory")){ ?>
             <li class="nav-item">
                <a class="nav-link nav-toggle" href="javascript:void(0);">
                    <i class="fa fa-book"></i>
                    <span class="title">Inventory</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item">
                        <a href="dashboard-siminventory.php" class="nav-link">
                            <span class="title">SIM Inventory</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="dashboard-officeitem.php" class="nav-link">
                            <span class="title">Office Items</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="dashboard-hardware.php" class="nav-link">
                            <span class="title">Hardware</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="dashboard-machine-tools.php" class="nav-link">
                            <span class="title">Machine & Tools</span>
                        </a>
                    </li>
                </ul>
            </li>
			<?php } if($page=CheckSideBarPermission("customers")){ ?>
            <li class="nav-item">
                <a href="customers.php" class="nav-link">
                    <i class="icon-users"></i>
                    <span class="title">Customers</span>

                </a>
            </li>
            <?php } if($page=CheckSideBarPermission("invoices")){ ?>
            <li class="nav-item">
                <a href="dashboard-invoice.php" class="nav-link">
                    <i class="icon-list"></i>
                    <span class="title">Invoices</span>

                </a>
            </li>
			<?php } if($page=CheckSideBarPermission("support")){ ?>
            <li class="nav-item">
                <a href="support.php" class="nav-link">
                    <i class="icon-support"></i>
                    <span class="title">Support</span>

                </a>
            </li>
			<?php } if($page=CheckSideBarPermission("audit")){ ?>
            <li class="nav-item">
                <a href="audit.php" class="nav-link">
                    <i class="fa fa-history"></i>
                    <span class="title">Audit Log</span>

                </a>
            </li>
			<?php } if($page=CheckSideBarPermission("usermanual")){ ?>
			<li class="nav-item">
                <a href="sample/User Manual – Process Portal.pdf" target="_blank" class="nav-link">
                    <i class="icon-layers"></i>
                    <span class="title">User Manual</span>

                </a>
            </li>
			<?php } ?>
			<!--<li class="nav-item">
                <a href="technician-scheduling.php" class="nav-link">
                    <i class="icon-users"></i>
                    <span class="title">Technician Scheduling</span>

                </a>
            </li>-->
        </ul>
    </div>
</div>
