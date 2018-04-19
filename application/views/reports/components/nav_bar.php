<?php
/**
 * Created by Zeenomlabs.
 * User: ZeenomLabs
 * Date: 4/20/15
 * Time: 10:49 PM
 */
?>
<ul id="myTab" class="nav nav-pills">
    <li class="<?php if($this->router->fetch_method() == 'index'){echo "active";} ?>"><a href="<?= base_url()."reports/index/";?>">Customer Reports</a></li>
    <li class="<?php if($this->router->fetch_method() == 'company'){echo "active";} ?>"><a href="<?= base_url()."reports/company/";?>">Company Reports</a></li>
    <li class="<?php if($this->router->fetch_method() == ''){echo "active";} ?>">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Calculation Sheet <b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li class="<?php if($this->uri->segment(2) == 'tankers_ledger'){echo "active";} ?>">
                <a href="<?= base_url()."reports/calculation_sheet_black_oil" ?>"><i class="fa fa-fw fa-file-o"></i> Black Oil</a>
            </li>
            <li class="<?php if($this->uri->segment(2) == 'tankers_ledger'){echo "active";} ?>">
                <a href="<?= base_url()."reports/calculation_sheet_white_oil" ?>"><i class="fa fa-fw fa-file-o"></i> White Oil</a>
            </li>
            <li class="<?php if($this->uri->segment(2) == 'tankers_ledger'){echo "active";} ?>">
                <a href="<?= base_url()."reports/calculation_sheet_custom" ?>"><i class="fa fa-fw fa-file-o"></i> Custom</a>
            </li>
        </ul>
    </li>
    <li class="<?php if($this->router->fetch_method() == 'shortage_report'){echo "active";} ?>"><a href="<?= base_url()."reports/shortage_report/";?>">Shortage Reports</a></li>
    <!--<li class="<?php /*if($this->router->fetch_method() == 'freight_report'){echo "active";} */?>"><a href="<?/*= base_url()."reports/freight_report/";*/?>">Freight Reports</a></li>-->
    <li class="<?php if($this->router->fetch_method() == 'vehicle_position_report'){echo "active";} ?>"><a href="<?= base_url()."reports/vehicle_position_report/";?>">Vehicle Position</a></li>
    <li class="<?php if($this->router->fetch_method() == ''){echo "active";} ?>">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Tankers Report <b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li class="<?php if($this->uri->segment(2) == 'tankers_expense_report'){echo "active";} ?>">
                <a href="<?= base_url()."reports/make_irfan_tankers_report" ?>"><i class="fa fa-fw fa-file-o"></i> Expense Report</a>
            </li>
        </ul>
    </li>
</ul>