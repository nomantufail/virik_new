<html>

<head>

    <title>Tanker Ledger</title>

    <link href="<?= css()?>bootstrap.min.css" rel="stylesheet">

</head>
<style>

    .white-popup {
        position: relative;
        background: #FFF;
        padding: 20px;
        width: auto;
        max-width: 1000px;
        margin: 20px auto;
    }
    table{
        font-size: <?= $font_size ?>;
    }
    table td, th{
        padding: 5px;
    }
</style>
<script>

    function show_monthly_results(month, path){
        var path = path+month;
        window.location.href = path;
    }

</script>
<body>
<div id="page-wrapper" style="min-height: 700px;">

    <div class="container-fluid">



        <!--Including the main navbar for accounts section-->
        <div class="row">
            <div class="col-lg-12" style="text-align: center;">
                <h3>Tankers Income Statement</h3>
                <h4>Customer: <?= ($selected_customer != null)?ucwords($selected_customer->name):"All" ?></h4>
                <h4>Tankers:
                    <?php
                    if(sizeof($selected_tankers) > 0){
                        foreach($selected_tankers as $tanker)
                        {
                            echo ", ".$tanker->truck_number;
                        }
                    }else{
                        echo "All";
                    }
                    ?>
                </h4>
                <?php
                echo Carbon::createFromFormat('Y-m-d',$selected_from)->toFormattedDateString();
                echo " To ";
                echo Carbon::createFromFormat('Y-m-d',$selected_to)->toFormattedDateString();
                ?>
            </div>
        </div>

        <div class="row">
            <div class="panel-body">

                <div class="col-lg-12">

                    <table class="table table-bordered table-hover table-striped" style="font-size:12px; min-width: 800px;" border="1">

                        <thead style="">
                        <tr>
                            <?= ((in_array('tanker_number', $columns) == true)?"<th style='text-align: left;'>Tanker#</th>":"") ?>
                            <?= ((in_array('routes', $columns) == true)?"<th style='text-align: left;'>Routes</th>":"") ?>
                            <?= ((in_array('shortage_expense', $columns) == true)?"<th style='width: 10%;text-align: left;'>Shortage Expenses</th>":"") ?>
                            <?= ((in_array('other_expense', $columns) == true)?"<th style='width: 10%;text-align: left;'>Other Expenses</th>":"") ?>
                            <?= ((in_array('total_expenses', $columns) == true)?"<th style='width: 10%;text-align: left;'>Total Expenses</th>":"") ?>
                            <?= ((in_array('total_income', $columns) == true)?"<th style='width: 55%;text-align: left;'>Total Income</th>":"") ?>
                            <?= ((in_array('profit/loss', $columns) == true)?"<th style='width: 11%; text-align: left;'>Profit/Loss</th>":"") ?>
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

                                <?php if(in_array('tanker_number', $columns) == true): ?>
                                    <td style'text-align: left;'><?= $record->tanker_number ?></td>
                                <?php endif; ?>

                                <?php if(in_array('routes', $columns) == true): ?>
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
                                <?php endif; ?>

                                <?php if(in_array('shortage_expense', $columns) == true): ?>
                                    <td style'text-align: left;'>
                                        <?= $this->helper_model->money(round($record->shortage_expense,3)) ?>
                                    </td>
                                <?php endif; ?>
                                <?php if(in_array('other_expense', $columns) == true): ?>
                                    <td style'text-align: left;'>
                                        <?= $this->helper_model->money(round($record->other_expense,3)) ?>
                                    </td>
                                <?php endif; ?>
                                <?php if(in_array('total_expenses', $columns) == true): ?>
                                    <td style'text-align: left;'>
                                        <?= $this->helper_model->money(round($record->total_expense(),3)) ?>
                                    </td>
                                <?php endif; ?>

                                <?php if(in_array('total_income', $columns) == true): ?>
                                    <td style'text-align: left;'>
                                        <?= $this->helper_model->money(round($record->total_income,3)) ?>
                                    </td>
                                <?php endif; ?>

                                <?php if(in_array('profit/loss', $columns) == true): ?>
                                    <td style'text-align: left;'>
                                        <?= ((($record->total_income - $record->total_expense()) < 0)?"<span style='color:red;'>".$this->helper_model->money(round(($record->total_income - $record->total_expense()),3))."</span>":"<span style='color:green;'>".$this->helper_model->money(round(($record->total_income - $record->total_expense()),3))."</span>") ?>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <?= ((in_array('tanker_number', $columns) == true)?"<th style='text-align: right;'>Totals</th>":"") ?>
                            <?= ((in_array('tanker_number', $columns) == true)?"<th style='text-align: right;'></th>":"") ?>
                            <?= ((in_array('shortage_expense', $columns) == true)?"<th style='width: 10%;text-align: left;'>".$this->helper_model->money(round($total_shortage_expense, 3))."</th>":"") ?>
                            <?= ((in_array('other_expense', $columns) == true)?"<th style='width: 10%;text-align: left;'>".$this->helper_model->money(round($total_other_expense, 3))."</th>":"") ?>
                            <?= ((in_array('total_expenses', $columns) == true)?"<th style='width: 10%;text-align: left;'>".$this->helper_model->money(round($total_expense, 3))."</th>":"") ?>
                            <?= ((in_array('total_income', $columns) == true)?"<th style='width: 55%;text-align: left;'>".$this->helper_model->money(round($total_revenue, 3))."</th>":"") ?>
                            <?= ((in_array('profit/loss', $columns) == true)?"<th style='width: 11%;text-align: left;'>".((($total_revenue - $total_expense) < 0)?"<span style='color:red;'>".$this->helper_model->money(round(($total_revenue - $total_expense),3))."</span>":"<span style='color:green;'>".$this->helper_model->money(round(($total_revenue - $total_expense),3))."</span>")."</th>":"") ?>
                        </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>

    </div>



</div>

</body>
</html>