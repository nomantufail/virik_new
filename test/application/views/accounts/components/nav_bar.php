<div class="col-lg-12">
    <ul id="myTab" class="nav nav-pills" style="border-bottom: 0px solid;">
        <li class="<?php if($section=='cash_purchase'){echo "active";} ?>">
            <a href="<?= $this->helper_model->controller_path()."" ?>"><i class="fa fa-fw fa-file-o"></i> Ledger</a>
        </li>
        <li class="<?php if($section == 'credit_purchase'){echo "active";} ?>">
            <a href="<?= $this->helper_model->controller_path()."" ?>"><i class="fa fa-fw fa-file-o"></i> Income Statement</a>
        </li>
        <li class="<?php if($section == 'cash'){echo "active";} ?>">
            <a href="<?= $this->helper_model->controller_path()."" ?>"><i class="fa fa-fw fa-file-o"></i> Balance Sheet</a>
        </li>
        <li class="<?php if($section == 'credit'){echo "active";} ?>">
            <a href="<?= $this->helper_model->controller_path()."" ?>"><i class="fa fa-fw fa-file-o"></i> Trial Balance</a>
        </li>
        <li class="<?php if($section == 'credit'){echo "active";} ?>">
            <a href="<?= $this->helper_model->controller_path()."" ?>"><i class="fa fa-fw fa-file-o"></i> Debit / Credit</a>
        </li>
        <li class="<?php if($section == 'credit'){echo "active";} ?>">
            <a href="<?= $this->helper_model->controller_path()."" ?>"><i class="fa fa-fw fa-file-o"></i> Journal</a>
        </li>
    </ul>
</div>