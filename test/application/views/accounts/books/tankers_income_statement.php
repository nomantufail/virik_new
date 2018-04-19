<style>

    .white-popup {

        position: relative;

        background: #FFF;

        padding: 20px;

        width: auto;

        max-width: 1000px;

        margin: 20px auto;

    }
    .trial_balance_settings_popup{
        position: relative;
        background: #FFF;
        padding: 20px;
        width: auto;
        max-width: 600px;
        margin: 20px auto;
    }
    .search-table{
        font-size: 12px;
    }
    .search-table input{
        width:100%;
        height: 30px;
    }
    .search-table select{
        width:100%;
        height: 30px;
    }
    table{
        font-size: 12px;
    }
</style>
<script>
    function show_monthly_results(month, path){

        var path = path+month;

        window.location.href = path;

    }

</script>

<div id="page-wrapper" style="min-height: 700px;">

    <div class="container-fluid">



        <!--Including the main navbar for accounts section-->
        <div class="row">
            <?php
            include_once(APPPATH."views/accounts/components/main_nav_bar.php");
            ?>
        </div>

        <div class="row">
            <h3 style="text-align: center;">Income Statement</h3>
        </div>

        <div class="row">
            <div class="panel-body">
                <!--SEARCH AREA FOR TANKERS LEDGERS-->
                <div class="col-lg-12">
                    <form action="" method="get">
                        <table class="search-table" style="width:100%;">
                            <tr>
                                <td style="width: 23%;"><b>Tanker#: </b><br>
                                    <select name="tanker_id[]" multiple class="tankers_select">
                                        <?php foreach($tankers as $tanker): ?>
                                            <?php
                                            $selected = (in_array($tanker->id, $selected_tanker_ids)?'selected':'');
                                            ?>
                                            <option value="<?= $tanker->id ?>" <?= $selected ?>><?= $tanker->truck_number ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td style="width: 23%;"><b>Csutomer: </b><br>
                                    <select name="customer" class="customers_select">
                                        <option value="" <?= (($selected_customer_id == '')?'selected':'') ?>>--All--</option>
                                        <?php foreach($customers as $customer): ?>
                                            <?php
                                            $selected = ($customer->id == $selected_customer_id)?'selected':'';
                                            ?>
                                            <option value="<?= $customer->id ?>" <?= $selected ?>><?= $customer->name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td><b>From: </b><input type="date" value="<?= $selected_from ?>" name="from"></td>
                                <td><b>To: </b><input type="date" value="<?= $selected_to ?>" name="to"></td>
                                <td><br><input type="submit" value="Generate" style="width: 100%;"></td>
                            </tr>
                        </table>
                    </form>
                </div>
                <!--search area ends here-->

                <div class="col-lg-12">
                    <form name="selection_form" id="selection_form" method="post" action="<?php
                    if(strpos($this->helper_model->page_url(),'?') == false){
                        echo $this->helper_model->page_url()."?";
                    }else{echo $this->helper_model->page_url()."&";}
                    ?>print">
                        <table id="tankers_income_statement_table" class="table table-bordered table-hover table-striped" style="font-size:12px;">

                            <thead style="">
                            <tr>
                                <th></th>
                                <th><div><input id="" type="checkbox" name="column[]" value="tanker_number" style="" checked></div></th>
                                <th><div><input id="" type="checkbox" name="column[]" value="routes" style="" checked></div></th>
                                <th><div><input id="" type="checkbox" name="column[]" value="shortage_expense" style="" checked></div></th>
                                <th><div><input id="" type="checkbox" name="column[]" value="other_expense" style="" checked></div></th>
                                <th><div><input id="" type="checkbox" name="column[]" value="total_expenses" style="" checked></div></th>
                                <th><div><input id="" type="checkbox" name="column[]" value="total_income" style="" checked></div></th>
                                <th><div><input id="" type="checkbox" name="column[]" value="profit/loss" style="" checked></div></th>

                            </tr>
                            <tr>
                                <th><input id="parent_checkbox" onchange="check_boxes();" type="checkbox" style="" checked></th>
                                <th style="" >
                                    <a href="<?php echo $this->helper_model->sorting_info('tankers.truck_number'); ?>">
                                        <i class="<?= $this->helper_model->sorting_icon('tankers.truck_number', 'string'); ?>"> </i>
                                        Tanker#
                                    </a>
                                </th>
                                <th style="">Routes</th>
                                <th style="width: 10%" >
                                    <a href="<?php echo $this->helper_model->sorting_info('shortage_expenses'); ?>">
                                        <i class="<?= $this->helper_model->sorting_icon('shortage_expenses', 'numeric'); ?>"> </i>
                                        Shortage Expenses
                                    </a>
                                </th>
                                <th style="width: 10%" >
                                    <a href="<?php echo $this->helper_model->sorting_info('other_expenses'); ?>">
                                        <i class="<?= $this->helper_model->sorting_icon('other_expenses', 'numeric'); ?>"> </i>
                                        Other Expenses
                                    </a>
                                </th>
                                <th style="width: 10%" >
                                    <a href="<?php echo $this->helper_model->sorting_info('total_expenses'); ?>">
                                        <i class="<?= $this->helper_model->sorting_icon('total_expenses', 'numeric'); ?>"> </i>
                                        Total Expenses
                                    </a>
                                </th>
                                <th style="width: 55%">
                                    <a href="<?php echo $this->helper_model->sorting_info('total_income'); ?>">
                                        <i class="<?= $this->helper_model->sorting_icon('total_income', 'numeric'); ?>"> </i>
                                        Total Income
                                    </a>
                                </th>
                                <th style="width: 11%;">
                                    <a href="<?php echo $this->helper_model->sorting_info('profit'); ?>">
                                        <i class="<?= $this->helper_model->sorting_icon('profit', 'numeric'); ?>"> </i>
                                        Profit/Loss
                                    </a>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $total_shortage_expense = 0;
                            $total_other_expense = 0;
                            $total_expense = 0;
                            $total_revenue = 0;
                            ?>

                            <?php  foreach($tanker_income_report['income_statement'] as $record): ?>
                                <?php
                                $total_shortage_expense += $record->shortage_expense;
                                $total_other_expense += $record->other_expense;
                                $total_expense += $record->total_expense();
                                $total_revenue += $record->total_income;
                                ?>
                                <tr style="">
                                    <td><input class="filter_check_box" type="checkbox" name="check[]" style="" checked value="<?= $record->tanker_id; ?>"></td>
                                    <td><?= $record->tanker_number ?></td>
                                    <td style="font-size: 11px; width: 50%;">
                                        <?php
                                        $tankers_routes = $tanker_income_report['tankers_routes'];
                                        $tanker_routes = $tankers_routes->tanker_routes($record->tanker_id);
                                        foreach($tanker_routes as $route)
                                        {
                                            echo $route['source']." To ".$route['destination']."_<span style='color:red;'>(".$route['counter'].")</span><br>";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?= $this->helper_model->money(round($record->shortage_expense,3)) ?>
                                    </td>
                                    <td>
                                        <?= $this->helper_model->money(round($record->other_expense,3)) ?>
                                    </td>
                                    <td>
                                        <?= $this->helper_model->money(round($record->total_expense(),3)) ?>
                                    </td>
                                    <td>
                                        <?= $this->helper_model->money(round($record->total_income,3)) ?>
                                    </td>
                                    <td>
                                        <?= ((($record->total_income - $record->total_expense()) < 0)?"<span style='color:red;'>".$this->helper_model->money(round(($record->total_income - $record->total_expense()),3))."</span>":"<span style='color:green;'>".$this->helper_model->money(round(($record->total_income - $record->total_expense()),3))."</span>") ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="3" style="text-align: right;">Totals:</th>
                                <th><?= $this->helper_model->money(round($total_shortage_expense, 3)) ?></th>
                                <th><?= $this->helper_model->money(round($total_other_expense, 3)) ?></th>
                                <th><?= $this->helper_model->money(round($total_expense, 3)) ?></th>
                                <th colspan=""><?= $this->helper_model->money(round($total_revenue, 3)) ?></th>
                                <th>
                                    <?= ((($total_revenue - $total_expense) < 0)?"<span style='color:red;'>".$this->helper_model->money(round(($total_revenue - $total_expense),3))."</span>":"<span style='color:green;'>".$this->helper_model->money(round(($total_revenue - $total_expense),3))."</span>") ?>
                                </th>
                            </tr>
                            </tfoot>
                        </table>
                    </form>
                </div>
            </div>
        </div>

    </div>



</div>

<script src="<?= js()."jquery.magnific-popup.min.js"; ?>"></script>

<script>

    $('.trial_balance_settings_link').magnificPopup({
        type: 'inline',
        showCloseBtn:false
    });

    $(".tankers_select").select2();
    $(".customers_select").select2();

</script>