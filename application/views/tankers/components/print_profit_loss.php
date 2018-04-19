<html>
<head>
    <title>Profit and Loss</title>
    <link href="<?= css()?>bootstrap.min.css" rel="stylesheet">
</head>
<body>

<style>
    table{
        font-size: 12px;
    }
</style>
<div id="page-wrapper" style="min-height: 700px;">
    <div class="container-fluid">


        <div class="row">
            <div class="col-lg-12">
                <section class="col-md-12">
                    <h3 class="">
                      Profit & Loss:<small><?= $profile->truck_number; ?></small>
                    </h3>
                </section>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 text-center">
                <h4><?= $this->carbon->createFromFormat('Y-m-d',$from_date)->toFormattedDateString()?> - <?= $this->carbon->createFromFormat('Y-m-d',$to_date)->toFormattedDateString() ?></h4>
            </div>
            <div class="col-md-12">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-user fa-fw"></i> Trips Expenses</h3>
                    </div>
                    <div class="panel-body">
                        <div class="list-group">
                            <table class="table sortable">
                                <thead>
                                <tr>
                                    <th>Trip Id</th>
                                    <th>Date</th>
                                    <th>Tanker#</th>
                                    <th>Tanker Expense</th>
                                    <th>Driver Expense</th>
                                    <th>PD Expense</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach($trips_expenses as $expense){
                                    ?>
                                    <tr>
                                        <td><?= $expense->trip_id ?></td>
                                        <td><?= ($expense->trip_date != '0000-00-00')?$this->carbon->createFromFormat('Y-m-d',$expense->trip_date)->toFormattedDateString():"--"; ?></td>
                                        <td><?= $expense->tanker_number ?></td>
                                        <td><?= $this->helper_model->money($expense->tanker_expense) ?></td>
                                        <td><?= $this->helper_model->money($expense->drivers_expense) ?></td>
                                        <td><?= $this->helper_model->money($expense->pd_expense) ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="3"><strong>Totals</strong></td>
                                    <td style="color: #003399"><strong><?= $this->helper_model->money($total_tanker_expense_trips) ?></strong></td>
                                    <td style="color: #003399"><strong><?= $this->helper_model->money($total_driver_expense_trips) ?></strong></td>
                                    <td style="color: #003399"><strong><?= $this->helper_model->money($total_pd_expense_trips) ?></strong></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-user fa-fw"></i> Other Tanker Expenses</h3>
                    </div>
                    <div class="panel-body">
                        <div class="list-group">
                            <table class="table sortable">
                                <thead>
                                <tr>
                                    <th>Expense Date</th>
                                    <th>Expense Title</th>
                                    <th>Tanker#</th>
                                    <th>Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach($other_tanker_expenses as $expense){
                                    ?>
                                    <tr>
                                        <td><?= ($expense->expense_date != '0000-00-00')?$this->carbon->createFromFormat('Y-m-d',$expense->expense_date)->toFormattedDateString():"--"; ?></td>
                                        <td><?= $expense->description ?></td>
                                        <td><?= $expense->tanker_number ?></td>
                                        <td><?= $this->helper_model->money($expense->amount) ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="3"><strong>Totals</strong></td>
                                    <td style="color: #003399"><strong><?= $this->helper_model->money($total_tanker_expense_other) ?></strong></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-user fa-fw"></i> Revenues</h3>
                    </div>
                    <div class="panel-body">
                        <div class="list-group">
                            <table class="table sortable">
                                <thead>
                                <tr>
                                    <th>Trip Id</th>
                                    <th>Trip Date</th>
                                    <th>Total Freight</th>
                                    <th>Net Freight</th>
                                    <th>Received</th>
                                    <th>Remaining</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach($revenues as $revenue){
                                    ?>
                                    <tr>
                                        <td><?= $revenue->trip_id ?></td>
                                        <td><?= ($revenue->trip_date != '0000-00-00')?$this->carbon->createFromFormat('Y-m-d',$revenue->trip_date)->toFormattedDateString():"--"; ?></td>
                                        <td><?= $this->helper_model->money($revenue->total_freight) ?></td>
                                        <td><?= $this->helper_model->money($revenue->net_freight) ?></td>
                                        <td><?= $this->helper_model->money($revenue->paid) ?></td>
                                        <td><?= $this->helper_model->money($revenue->remaining) ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="2"><strong>Totals</strong></td>
                                    <td style="color: #003399"><strong><?= $this->helper_model->money($total_freight) ?></strong></td>
                                    <td style="color: #003399"><strong><?= $this->helper_model->money($total_revenue) ?></strong></td>
                                    <td style="color: #003399"><strong><?= $this->helper_model->money($paid) ?></strong></td>
                                    <td style="color: #003399"><strong><?= $this->helper_model->money($remaining) ?></strong></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-user fa-fw"></i> Profit & Loss</h3>
                    </div>
                    <div class="panel-body">
                        <div class="list-group text-center">
                            <?php
                            $total_expenses = $total_tanker_expense_other + $total_tanker_expense_trips + $total_driver_expense_trips +$total_pd_expense_trips;
                            $profit = $total_revenue - $total_expenses;
                            ?>
                            <h4>Revenue = <span style="color: gray"><?= $this->helper_model->money($total_revenue) ?></span></h4>
                            <h4>Expense = Tanker Expenses(T) + Other Tanker Expenses + Driver Expense + PD Expense = <span style="color: gray"><?= $this->helper_model->money($total_expenses) ?></span></h4>
                            <h1><?php echo ($profit >= 0)?"Profit": "Loss"; ?> = <span style=" font-family: Courier; color: <?php echo ($profit >= 0)?"green": "red"; ?>"><?= $this->helper_model->money($profit) ?></span></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="<?= js()?>sorttable.js"></script>
</body>
</html>
