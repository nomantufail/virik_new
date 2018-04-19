<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>VirikLogistics | </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
    <!-- Bootstrap 3.3.2 -->
    <?php
    include_once("headerStuff.php");
    ?>

    <script>
        function export_script()
        {
            var action = document.getElementById("selection_form").action;
            var new_action = action.replace("print","&export");
            var new_action = new_action.replace("?print","?export");
            document.getElementById("selection_form").action = new_action;
            document.getElementById("selection_form").submit();
        }
        function print_script()
        {
            var action = document.getElementById("selection_form").action;
            var new_action = action.replace("&export","&print");
            var new_action = new_action.replace("?export","?print");
            document.getElementById("selection_form").action = new_action;
            document.getElementById("selection_form").submit();
        }
    </script>

</head>
<body class="skin-blue">

    <div id="wrapper">


    <header class="main-header">
        <a href="<?= base_url().""; ?>" class="logo"><b>Virik</b>Logistics</a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Messages: style can be found in dropdown.less-->
                    <li class="dropdown">
                        <a target="" class="btn btn-xs btn-success" onclick="print_script();" href="<?php
                        /*if(strpos($this->helper_model->page_url(),'?') == false){
                            echo $this->helper_model->page_url()."?";
                        }else{echo $this->helper_model->page_url()."&";}*/
                        ?>#" ><i class="fa fa-print"></i> Print</a>
                    </li>

                    <li class="dropdown">
                        <a target="" class="btn btn-success btn-xs" onclick="export_script();" href="<?php
                        ?>#" ><i class="fa fa-file-excel-o"></i> Excel</a>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> <b class="caret"></b></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> <b class="caret"></b></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> virik <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="#"><i class="fa fa-fw fa-user"></i> Profile</a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-fw fa-envelope"></i> Inbox</a>
                            </li>
                            <li>
                                <a href="<?= base_url()."settings/" ?>"><i class="fa fa-fw fa-gear"></i> Settings</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="<?= base_url()."logout.html"; ?>"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li class="header">MAIN NAVIGATION</li>

                <li class="<?php if($page == 'home'){echo "active";} ?>">
                    <a href="<?= base_url().""; ?>"><i class="fa fa-fw fa-home"></i> Home</a>
                </li>

                <li class="<?php if($page == 'customers'){echo "active";} ?>">
                    <a href="<?= base_url()."customers.html"; ?>"><i class="fa fa-fw fa-users"></i> Customers</a>
                </li>

                <li class="<?php if($page == 'carriageContractors'){echo "active";} ?>">
                    <a href="<?= base_url()."carriageContractors.html"; ?>"><i class="fa fa-fw fa-users"></i> Carriage Contractors</a>
                </li>

                <li class="<?php if($page == 'companies'){echo "active";} ?>">
                    <a href="<?= base_url()."companies.html"; ?>"><i class="fa fa-fw fa-users"></i> Companies</a>
                </li>

                <li class="<?php if($page == 'otherAgents'){echo "active";} ?>">
                    <a href="<?= base_url()."otherAgents.html"; ?>"><i class="fa fa-fw fa-users"></i> Other Agents</a>
                </li>
                <li class="<?php if($page == 'manageCommissions'){echo "active";} ?>">
                    <a href="<?= base_url()."manageCommissions.html"; ?>"><i class="fa fa-fw fa-ambulance"></i> Manage Commissions</a>
                </li>
                <li class="treeview <?= (($this->uri->segment(1) == 'manageaccounts')?'active':'') ?>">
                    <a href="#">
                        <i class="fa fa-plane"></i> <span>Manage Accounts</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a style="<?php if($this->uri->segment(2) == 'white_oil'){echo "color:white;";} ?>" href="<?= base_url()."manageaccounts/white_oil"; ?>"><i class="fa fa-eye"></i> White Oil</a></li>
                        <li><a style="<?php if($this->uri->segment(2) == 'black_oil'){echo "color:white;";} ?>" href="<?= base_url()."manageaccounts/black_oil"; ?>"><i class="fa fa-eye"></i> Black Oil</a></li>
                    </ul>
                </li>

                <li class="<?php if($page == 'accounts'){echo "active";} ?>">
                    <a href="<?= base_url()."accounts.html" ?>"><i class="fa fa-fw fa-money"></i>View Accounts</a>
                </li>

                <li class="treeview <?= (($this->uri->segment(1) == 'trips')?'active':'') ?>">
                    <a href="#">
                        <i class="fa fa-plane"></i> <span>Trips</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?php if((isset($trip_master_type)) && ($trip_master_type == 'primary')){echo "active";} ?>">
                            <a href="#"><i class="fa fa-circle-o"></i> Primary <i class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                                <li><a style="<?php if($this->uri->segment(2) == '' || $this->uri->segment(2) == 'primary' || $this->uri->segment(2) == 'index'){echo "color:white;";} ?>" href="<?= base_url()."trips/show/primary"; ?>"><i class="fa fa-eye"></i> View</a></li>
                                <li><a style="<?php if($this->uri->segment(2) == 'make' && $this->uri->segment(3) == ''){echo "color:white;";} ?>" href="<?= base_url()."trips/make"; ?>"><i class="fa fa-plus-circle"></i> Self Mail</a></li>
                                <li><a style="<?php if($this->uri->segment(3) == 'local'){echo "color:white;";} ?>" href="<?= base_url()."trips/make/local"; ?>"><i class="fa fa-plus-circle"></i> General Trip</a></li>
                                <li><a style="<?php if($this->uri->segment(3) == 'local_self'){echo "color:white;";} ?>" href="<?= base_url()."trips/make/local_self"; ?>"><i class="fa fa-plus-circle"></i> Local Self</a></li>
                                <li><a style="<?php if($this->uri->segment(3) == 'local_self'){echo "color:white;";} ?>" href="<?= base_url()."trips/make/general_local"; ?>"><i class="fa fa-plus-circle"></i> General Local</a></li>
                            </ul>
                        </li>
                        <li class="<?php if((isset($trip_master_type)) && ($trip_master_type == 'secondary')){echo "active";} ?>">
                            <a href="#"><i class="fa fa-circle-o"></i> Secondary <i class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                                <li><a style="<?php if($this->uri->segment(2) == 'secondary'){echo "color:white;";} ?>" href="<?= base_url()."trips/show/secondary"; ?>"><i class="fa fa-eye"></i> View</a></li>
                                <li><a style="<?php if($this->uri->segment(3) == 'local_cmp'){echo "color:white;";} ?>" href="<?= base_url()."trips/make/local_cmp"; ?>"><i class="fa fa-plus-circle"></i> Local Cmp</a></li>
                            </ul>
                        </li>
                        <li class="<?php if((isset($trip_master_type)) && ($trip_master_type == 'secondary_local')){echo "active";} ?>">
                            <a href="#"><i class="fa fa-circle-o"></i> Secondary Local Trip<i class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                                <li><a style="<?php if($this->uri->segment(2) == 'secondary'){echo "color:white;";} ?>" href="<?= base_url()."trips/show/secondary_local"; ?>"><i class="fa fa-eye"></i> View</a></li>
                                <li><a style="<?php if($this->uri->segment(3) == 'local_cmp'){echo "color:white;";} ?>" href="<?= base_url()."trips/make/secondary_local"; ?>"><i class="fa fa-plus-circle"></i> Secondary Local</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li class="<?php if($this->uri->segment(1) == 'shortages'){echo "active";} ?>">
                    <a href="<?= base_url()."shortages"; ?>"><i class="fa fa-fw fa-list"></i> Shortages</a>
                </li>

                <li class="treeview <?= (($this->uri->segment(1) == 'routes')?'active':'') ?>">
                    <a href="#">
                        <i class="fa fa-paw"></i> <span>Routes</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a style="<?php if($this->uri->segment(3) == '' || $this->uri->segment(3) == 'primary'){echo "color:white;";} ?>" href="<?= base_url()."routes/index"; ?>"><i class="fa fa-eye"></i> Primary</a></li>
                        <li><a style="<?php if($this->uri->segment(3) == 'secondary'){echo "color:white;";} ?>" href="<?= base_url()."routes/index/secondary"; ?>"><i class="fa fa-eye"></i> Secondary</a></li>
                        <li><a style="<?php if($this->uri->segment(3) == 'secondary'){echo "color:white;";} ?>" href="<?= base_url()."routes/index/secondary_local"; ?>"><i class="fa fa-eye"></i> Secondary Local</a></li>
                    </ul>
                </li>

                <li class="<?php if($page == 'tankers'){echo "active";} ?>">
                    <a href="<?= base_url()."tankers.html"; ?>"><i class="fa fa-fw fa-cab"></i> Tankers</a>
                </li>

                <li class="<?php if($page == 'reports'){echo "active";} ?>">
                    <a href="<?= base_url()."reports.html"; ?>"><i class="fa fa-fw fa-file-o"></i> Reports</a>
                </li>
                <li class="<?php if($page == 'drivers'){echo "active";} ?>">
                    <a href="<?= base_url()."drivers.html"; ?>"><i class="fa fa-fw fa-user"></i> Drivers</a>
                </li>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

