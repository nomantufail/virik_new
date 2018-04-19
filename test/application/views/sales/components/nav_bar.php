<div class="col-lg-12">
    <ul id="myTab" class="nav nav-pills" style="border-bottom: 0px solid;">
        <li class="<?php if($section=='cash_sale'){echo "active";} ?>">
            <a href="<?= $this->helper_model->controller_path()."cash_sale" ?>"><i class="fa fa-fw fa-plus-circle"></i> Cash Sale</a>
        </li>
        <li class="<?php if($section == 'credit_sale'){echo "active";} ?>">
            <a href="<?= $this->helper_model->controller_path()."credit_sale" ?>"><i class="fa fa-fw fa-plus-circle"></i> Credit Sale</a>
        </li>
        <li class="<?php if($section == 'cash'){echo "active";} ?>">
            <a href="<?= $this->helper_model->controller_path()."cash" ?>"><i class="fa fa-fw fa-file-o"></i> Cash Sale History</a>
        </li>
        <li class="<?php if($section == 'credit'){echo "active";} ?>">
            <a href="<?= $this->helper_model->controller_path()."credit" ?>"><i class="fa fa-fw fa-file-o"></i> Credit Sale History</a>
        </li>
    </ul>
</div>