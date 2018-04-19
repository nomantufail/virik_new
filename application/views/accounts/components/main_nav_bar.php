<?php
/**
 * Created by Zeenomlabs.
 * User: ZeenomLabs
 * Date: 3/31/15
 * Time: 12:03 AM
 */ 

?>
<div class="col-lg-12">

    <section class="col-md-3" style="">
        <h3 class="">
            Account Books <small></small>
        </h3>
    </section>

    <section class="col-md-9">
        <ul id="myTab" class="nav nav-pills" style="border-bottom: 0px solid;">
            <li class="<?php if($this->uri->segment(2) == 'journal'){echo "active";} ?>" ><a href="<?= base_url()."accounts/journal/$agent/$agent_id/?".$_SERVER['QUERY_STRING']?>">Journal</a></li>
            <li class="<?php if($this->uri->segment(2) == 'trial_balance'){echo "active";} ?>"><a href="<?= base_url()."accounts/trial_balance/$agent/$agent_id/?".$_SERVER['QUERY_STRING']?>">Trial Balance</a></li>
            <li class="<?php if($this->uri->segment(2) == 'balance_sheet'){echo "active";} ?>"><a href="<?= base_url()."accounts/balance_sheet/$agent/$agent_id/?".$_SERVER['QUERY_STRING']?>">Balance Sheet</a></li>
            <li class="<?php if($this->uri->segment(2) == 'income_statement'){echo "active";} ?>"><a href="<?= base_url()."accounts/income_statement/$agent/$agent_id/?".$_SERVER['QUERY_STRING']?>">Income Statement</a></li>
            <li class="dropdown <?php if($this->uri->segment(2) == 'tankers_ledger' || $this->uri->segment(2) == 'tankers_income_statement'){echo "active";} ?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Tankers <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li class="<?php if($this->uri->segment(2) == 'tankers_ledger'){echo "active";} ?>">
                        <a href="<?= base_url()."accounts/tankers_ledger" ?>"><i class="fa fa-fw fa-file-o"></i> Ledger</a>
                    </li>
                    <li class="<?php if($this->uri->segment(2) == 'tankers_income_statement'){echo "active";} ?>">
                        <a href="<?= base_url()."accounts/tankers_income_statement" ?>"><i class="fa fa-fw fa-file-o"></i> Income Statement</a>
                    </li>
                    <li class="<?php if($this->uri->segment(2) == 'tankers_income_statement_2'){echo "active";} ?>">
                        <a href="<?= base_url()."accounts/tankers_income_statement_2" ?>"><i class="fa fa-fw fa-file-o"></i> Income Statement 2</a>
                    </li>
                </ul>
            </li>
        </ul>
    </section>
</div>